<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetLoan;
use App\Models\User;
use App\Services\AssetLoanService;
use Illuminate\Http\Request;

class AssetLoanController extends Controller
{
    protected $loanService;

    public function __construct(AssetLoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function create(Asset $asset)
    {
        $role = auth()->user()->role ?? 'karyawan';
        $canSelectUser = $role === 'super_admin';
        $users = $canSelectUser ? User::all() : collect();
        // Load available units for asset-level borrowing
        $availableUnits = $asset->units()->where('status', 'available')->get();
        return view('loans.create', compact('asset', 'users', 'availableUnits', 'canSelectUser'));
    }

    public function index(Request $request)
    {
        $query = AssetLoan::with('asset', 'user', 'units')->latest();

        if ((auth()->user()->role ?? 'karyawan') === 'karyawan') {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('date_from')) {
            $query->whereDate('loan_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('loan_date', '<=', $request->input('date_to'));
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('asset', function ($qa) use ($s) {
                    $qa->where('name', 'like', "%{$s}%");
                })
                ->orWhereHas('user', function ($qu) use ($s) {
                    $qu->where('name', 'like', "%{$s}%");
                })
                ->orWhereHas('units', function ($qu) use ($s) {
                    $qu->where('unique_identifier', 'like', "%{$s}%");
                })
                ->orWhere('notes', 'like', "%{$s}%");
            });
        }

        $loans = $query->paginate(10)->appends($request->only(['date_from', 'date_to', 'search']));
        return view('loans.index', compact('loans'));
    }

    /**
     * Display returned loans (history of returns)
     */
    public function returnedIndex(Request $request)
    {
        // Load returned unit records and allow filtering by returned_at date range
        $retQuery = \App\Models\AssetLoanReturn::with(['unit', 'loan.asset', 'loan.user'])
            ->orderByDesc('returned_at');

        if ((auth()->user()->role ?? 'karyawan') === 'karyawan') {
            $retQuery->whereHas('loan', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        if ($request->filled('date_from')) {
            $retQuery->whereDate('returned_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $retQuery->whereDate('returned_at', '<=', $request->input('date_to'));
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $retQuery->where(function ($q) use ($s) {
                $q->whereHas('loan.asset', function ($qa) use ($s) {
                    $qa->where('name', 'like', "%{$s}%");
                })
                ->orWhereHas('loan.user', function ($qu) use ($s) {
                    $qu->where('name', 'like', "%{$s}%");
                })
                ->orWhereHas('unit', function ($qu) use ($s) {
                    $qu->where('unique_identifier', 'like', "%{$s}%");
                })
                ->orWhere('notes', 'like', "%{$s}%");
            });
        }

        $returns = $retQuery->get();

        $groups = $returns->groupBy(function ($r) {
            return $r->return_batch ?? ('single_'.$r->id);
        });

        // Build an array of batches with metadata and unit lists
        $batches = $groups->map(function ($items, $batchId) {
            $first = $items->first();
            return (object) [
                'batch_id' => $batchId,
                'loan' => $first->loan,
                'user' => $first->loan->user,
                'returned_at' => $items->first()->returned_at ?? $items->first()->created_at,
                'notes' => $items->first()->notes,
                'units' => $items,
                'count' => $items->count(),
            ];
        })->values();

        // Simple paginator for the batches
        $page = request()->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $batches->slice($offset, $perPage)->values(),
            $batches->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('loans.returned', ['batches' => $paginated]);
    }

    /**
     * Show details for a single loan
     */
    public function show(AssetLoan $loan)
    {
        if ((auth()->user()->role ?? 'karyawan') === 'karyawan' && $loan->user_id !== auth()->id()) {
            abort(403);
        }
        $loan->load('asset', 'user', 'units');
        return view('loans.show', compact('loan'));
    }

    public function showReturnUnitsForm(AssetLoan $loan)
    {
        if ((auth()->user()->role ?? 'karyawan') === 'karyawan' && $loan->user_id !== auth()->id()) {
            abort(403);
        }
        // show units currently assigned to this loan
        $assignedUnits = $loan->units()->get();
        return view('loans.return_units', compact('loan', 'assignedUnits'));
    }

    public function returnUnits(Request $request, AssetLoan $loan)
    {
        if ((auth()->user()->role ?? 'karyawan') === 'karyawan' && $loan->user_id !== auth()->id()) {
            abort(403);
        }
        $request->validate([
            'unit_ids' => 'required|array',
            'unit_ids.*' => 'integer|exists:asset_units,id',
            'unit_notes' => 'nullable|array',
            'unit_notes.*' => 'nullable|string',
        ]);

        try {
            $unitIds = $request->input('unit_ids');
            $unitNotes = $request->input('unit_notes', []);
            $this->loanService->checkinUnits($loan, $unitIds, $unitNotes);
            return redirect()->route('loans.index')->with('success', 'Unit berhasil dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request, Asset $asset)
    {
        $role = auth()->user()->role ?? 'karyawan';

        if ($role !== 'super_admin') {
            $request->merge(['user_id' => auth()->id()]);
        }

        $requireUnits = $asset->units()->where('status', 'available')->exists();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'quantity_borrowed' => 'required|integer|min:1|max:' . $asset->quantity,
            'unit_ids' => $requireUnits ? 'required|array|min:1' : 'nullable|array',
            'unit_ids.*' => 'integer|exists:asset_units,id',
            'notes' => 'nullable|string',
        ], [
            'unit_ids.required' => 'Pilih minimal satu unit untuk dipinjam.',
            'unit_ids.min' => 'Pilih minimal satu unit untuk dipinjam.',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $unitIds = $request->input('unit_ids');
            if (!empty($unitIds)) {
                $unitCount = count($unitIds);
                if ((int) $request->input('quantity_borrowed') !== $unitCount) {
                    return back()
                        ->withInput()
                        ->withErrors(['quantity_borrowed' => 'Jumlah yang dipinjam harus sama dengan jumlah unit yang dipilih.']);
                }
            }
            $this->loanService->checkout($asset, $user, $request->quantity_borrowed, $request->notes, $unitIds);
            return redirect()->route('assets.show', $asset)->with('success', 'Aset berhasil dipinjamkan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function return(AssetLoan $loan)
    {
        if ((auth()->user()->role ?? 'karyawan') === 'karyawan' && $loan->user_id !== auth()->id()) {
            abort(403);
        }
        try {
            $this->loanService->checkin($loan);
            return back()->with('success', 'Aset berhasil dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
