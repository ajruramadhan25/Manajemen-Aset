<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetLoan;
use App\Models\User;
use App\Models\AssetUnit;
use App\Models\AssetLoanReturn;
use Exception;
use Illuminate\Support\Facades\DB;

class AssetLoanService
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Checkout an asset to a user.
     */
    /**
     * @param array|null $unitIds Optional array of asset_unit IDs to loan specifically
     */
    public function checkout(Asset $asset, User $user, int $quantityBorrowed = 1, string $notes = null, array $unitIds = null): AssetLoan
    {
        if ($quantityBorrowed < 1) {
            throw new Exception("Quantity must be at least 1.");
        }

        // Calculate available stock (total quantity - borrowed quantity)
        $totalBorrowed = AssetLoan::where('asset_id', $asset->id)
            ->where('status', 'borrowed')
            ->sum('quantity_borrowed');
        
        $availableStock = $asset->quantity - $totalBorrowed;

        if ($availableStock < $quantityBorrowed) {
            throw new Exception("Insufficient stock. Available: {$availableStock}, Requested: {$quantityBorrowed}");
        }

        return DB::transaction(function () use ($asset, $user, $quantityBorrowed, $notes, $unitIds) {
            // Create Loan Record (store original_quantity as initial borrowed amount)
            $loan = AssetLoan::create([
                'asset_id' => $asset->id,
                'quantity_borrowed' => $quantityBorrowed,
                'original_quantity' => $quantityBorrowed,
                'user_id' => $user->id,
                'loan_date' => now(),
                'status' => 'borrowed',
                'notes' => $notes,
            ]);

            // If specific unit IDs provided, validate and assign them
            if (!empty($unitIds)) {
                $unitCount = count($unitIds);
                if ($unitCount !== $quantityBorrowed) {
                    throw new Exception("Number of selected units ({$unitCount}) must equal quantity requested ({$quantityBorrowed}).");
                }

                // Fetch units and ensure availability
                $units = AssetUnit::whereIn('id', $unitIds)->where('asset_id', $asset->id)->where('status', 'available')->lockForUpdate()->get();
                if ($units->count() !== $unitCount) {
                    throw new Exception('One or more selected units are not available.');
                }

                foreach ($units as $unit) {
                    // mark unit as borrowed
                    $unit->update(['status' => 'borrowed']);
                    // attach to loan
                    $loan->units()->attach($unit->id);
                }
            }

            // Update Asset Status - only if no more stock available
            $totalBorrowed = AssetLoan::where('asset_id', $asset->id)
                ->where('status', 'borrowed')
                ->sum('quantity_borrowed');
            
            if ($totalBorrowed >= $asset->quantity) {
                $asset->update(['status' => 'deployed']);
            }

            // Log Audit
            $this->auditService->log('CHECKOUT', $asset, [
                'loan_id' => $loan->id,
                'user_id' => $user->id,
                'quantity_borrowed' => $quantityBorrowed,
                'notes' => $notes
            ]);

            return $loan;
        });
    }

    /**
     * Checkin (return) an asset.
     */
    public function checkin(AssetLoan $loan): void
    {
        if ($loan->status !== 'borrowed') {
            throw new Exception("This loan is already returned.");
        }

        DB::transaction(function () use ($loan) {
            // Update Loan Record
            $loan->update([
                'return_date' => now(),
                'status' => 'returned',
            ]);

            // If loan has unit assignments, record returns and mark them available
            // Keep units attached so they remain visible in return history
            $units = $loan->units()->get();
            if ($units->count() > 0) {
                $batch = (string) \Illuminate\Support\Str::uuid();
                foreach ($units as $unit) {
                    // Check if this unit was already recorded as returned (partial return)
                    $alreadyReturned = AssetLoanReturn::where('asset_loan_id', $loan->id)
                        ->where('asset_unit_id', $unit->id)
                        ->exists();
                    
                    // Only record return if not already recorded
                    if (!$alreadyReturned) {
                        try {
                            AssetLoanReturn::create([
                                'asset_loan_id' => $loan->id,
                                'asset_unit_id' => $unit->id,
                                'returned_at' => now(),
                                'notes' => null,
                                'return_batch' => $batch,
                            ]);
                        } catch (\Exception $e) {
                            // Log return record creation failure but don't fail the return
                            \Log::warning("Failed to create AssetLoanReturn: " . $e->getMessage());
                        }
                    }

                    $unit->update(['status' => 'available']);
                    // Don't detach - keep units attached for history visibility
                }
            }

            // Update Asset Status - set back to available if there's stock left
            $asset = $loan->asset;
            $totalBorrowed = AssetLoan::where('asset_id', $asset->id)
                ->where('status', 'borrowed')
                ->sum('quantity_borrowed');
            
            if ($totalBorrowed < $asset->quantity) {
                $asset->update(['status' => 'available']);
            }

            // Log Audit
            $this->auditService->log('CHECKIN', $asset, [
                'loan_id' => $loan->id,
                'quantity_returned' => $loan->quantity_borrowed
            ]);
        });
    }

    /**
     * Checkin specific units from a loan (partial return)
     */
    public function checkinUnits(AssetLoan $loan, array $unitIds, array $unitNotes = []): void
    {
        if ($loan->status !== 'borrowed') {
            throw new Exception("This loan is already returned.");
        }

        DB::transaction(function () use ($loan, $unitIds, $unitNotes) {
            // Ensure all unitIds are actually attached to this loan
            $attached = $loan->units()->whereIn('asset_units.id', $unitIds)->pluck('asset_units.id')->toArray();
            $missing = array_diff($unitIds, $attached);
            if (count($missing) > 0) {
                throw new Exception('One or more units are not associated with this loan.');
            }

            $count = count($unitIds);

            // Create a batch id for this return operation so multiple units returned together are grouped
            $batch = (string) \Illuminate\Support\Str::uuid();
            // Mark units available and record returns, but keep them attached for history visibility
            foreach ($unitIds as $uid) {
                $unit = \App\Models\AssetUnit::findOrFail($uid);
                
                // Get notes for this unit if provided
                $notes = isset($unitNotes[$uid]) ? trim($unitNotes[$uid]) : null;
                
                // Try to create return record, but don't fail return if it doesn't work
                try {
                    AssetLoanReturn::create([
                        'asset_loan_id' => $loan->id,
                        'asset_unit_id' => $unit->id,
                        'returned_at' => now(),
                        'notes' => $notes,
                        'return_batch' => $batch,
                    ]);
                } catch (\Exception $e) {
                    \Log::warning("Failed to create AssetLoanReturn for unit {$uid}: " . $e->getMessage());
                }

                $unit->update(['status' => 'available']);
                // Don't detach - keep units attached for history visibility
            }

            // Decrease quantity_borrowed
            $loan->quantity_borrowed = max(0, $loan->quantity_borrowed - $count);

            if ($loan->quantity_borrowed === 0) {
                $loan->status = 'returned';
                $loan->return_date = now();
            }

            $loan->save();

            // Update asset status if needed
            $asset = $loan->asset;
            $totalBorrowed = AssetLoan::where('asset_id', $asset->id)
                ->where('status', 'borrowed')
                ->sum('quantity_borrowed');
            if ($totalBorrowed < $asset->quantity) {
                $asset->update(['status' => 'available']);
            }

            $this->auditService->log('CHECKIN_PARTIAL', $asset, [
                'loan_id' => $loan->id,
                'units_returned' => $unitIds,
                'quantity_returned' => $count,
            ]);
        });
    }
}
