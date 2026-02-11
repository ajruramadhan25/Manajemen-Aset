<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\Brand;
use App\Models\Category;
use App\Models\AssetUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Asset::with(['category', 'brand']);

        if (request('search')) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('asset_code', 'like', '%' . request('search') . '%');
            });
        }

        if (request('category_id')) {
            $query->where('category_id', request('category_id'));
        }

        if (request('brand_id')) {
            $query->where('brand_id', request('brand_id'));
        }

        $assets = $query->latest()->paginate(10);
        $categories = Category::all();
        $brands = Brand::all();
        
        return view('assets.index', compact('assets', 'categories', 'brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('assets.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssetRequest $request)
    {
        $data = $request->validated();

        // Remove parent_category_id - it's only for UI, not to be saved
        unset($data['parent_category_id']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('assets', 'public');
        }

        $asset = Asset::create($data);

        // Handle unit_identifiers from form (indexed array from dynamic fields)
        $unitIdentifiers = $request->input('unit_identifiers', []);
        if (!empty($unitIdentifiers)) {
            foreach ($unitIdentifiers as $index => $identifier) {
                $identifier = trim($identifier);
                if (!empty($identifier)) {
                    $exists = AssetUnit::where('asset_id', $asset->id)
                        ->where('unique_identifier', $identifier)
                        ->exists();
                    if (!$exists) {
                        AssetUnit::create([
                            'asset_id' => $asset->id,
                            'unique_identifier' => $identifier,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
    }

    /**
     * Display the specified resource.
     */


    public function show(Asset $asset, \App\Services\DepreciationService $depreciationService)
    {
        $asset->load(['category', 'loans.user', 'activeLoan.user']); // Eager load relationships
        $depreciationSchedule = $depreciationService->calculateStraightLine($asset);
        $logs = \App\Models\AuditLog::where('target_type', Asset::class)
            ->where('target_id', $asset->id)
            ->latest()
            ->get();

        return view('assets.show', compact('asset', 'depreciationSchedule', 'logs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('assets.edit', compact('asset', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $data = $request->validated();

        // Remove parent_category_id - it's only for UI, not to be saved
        unset($data['parent_category_id']);

        if ($request->hasFile('image')) {
            // Delete old image check
            if ($asset->image && Storage::disk('public')->exists($asset->image)) {
                Storage::disk('public')->delete($asset->image);
            }
            $data['image'] = $request->file('image')->store('assets', 'public');
        }

        $asset->update($data);

        // Handle unit_identifiers from form (indexed array from dynamic fields)
        $unitIdentifiers = $request->input('unit_identifiers', []);
        
        // Collect non-empty identifiers
        $newIdentifiers = [];
        foreach ($unitIdentifiers as $identifier) {
            $identifier = trim($identifier);
            if (!empty($identifier)) {
                $newIdentifiers[] = $identifier;
            }
        }
        
        // Get existing units
        $existingUnits = $asset->units;
        $existingIdentifiers = $existingUnits->pluck('unique_identifier')->toArray();
        
        // Delete units that are no longer in the new list and are NOT attached to loans
        foreach ($existingUnits as $unit) {
            if (!in_array($unit->unique_identifier, $newIdentifiers)) {
                // Check if unit is attached to any loan
                $hasLoans = $unit->loans()->exists();
                if (!$hasLoans) {
                    $unit->forceDelete(); // Actually delete from database, not just soft delete
                }
            }
        }
        
        // Create new units that don't exist yet - query database to be sure
        if (!empty($newIdentifiers)) {
            foreach ($newIdentifiers as $identifier) {
                $exists = AssetUnit::where('asset_id', $asset->id)
                    ->where('unique_identifier', $identifier)
                    ->exists();
                if (!$exists) {
                    AssetUnit::create([
                        'asset_id' => $asset->id,
                        'unique_identifier' => $identifier,
                    ]);
                }
            }
        }

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        if ($asset->image && Storage::disk('public')->exists($asset->image)) {
            Storage::disk('public')->delete($asset->image);
        }

        $asset->forceDelete();

        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }
}
