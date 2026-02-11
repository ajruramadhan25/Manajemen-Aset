<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetUnit;
use Illuminate\Http\Request;

class AssetUnitController extends Controller
{
    public function index(Request $request)
    {
        $query = AssetUnit::with('asset')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('unique_identifier', 'like', "%{$s}%")
                  ->orWhere('notes', 'like', "%{$s}%")
                  ->orWhereHas('asset', function ($qa) use ($s) {
                      $qa->where('name', 'like', "%{$s}%");
                  });
            });
        }

        $units = $query->paginate(10);
        return view('units.index', compact('units'));
    }

    public function create(Request $request)
    {
        $assets = Asset::all();
        return view('units.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'unique_identifier' => 'nullable|string|max:255',
            'status' => 'required|in:available,borrowed,maintenance,retired',
            'notes' => 'nullable|string',
        ]);

        $unit = AssetUnit::create($data);
        $assetId = $unit->asset_id;
        Asset::where('id', $assetId)->update([
            'quantity' => AssetUnit::where('asset_id', $assetId)->count(),
        ]);

        return redirect()->route('units.index')->with('success', 'Unit berhasil dibuat.');
    }

    public function edit(AssetUnit $unit)
    {
        $assets = Asset::all();
        return view('units.edit', compact('unit', 'assets'));
    }

    public function update(Request $request, AssetUnit $unit)
    {
        $data = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'unique_identifier' => 'nullable|string|max:255',
            'status' => 'required|in:available,borrowed,maintenance,retired',
            'notes' => 'nullable|string',
        ]);

        $oldAssetId = $unit->asset_id;
        $unit->update($data);
        $newAssetId = $unit->asset_id;

        if ($oldAssetId !== $newAssetId) {
            Asset::where('id', $oldAssetId)->update([
                'quantity' => AssetUnit::where('asset_id', $oldAssetId)->count(),
            ]);
        }

        Asset::where('id', $newAssetId)->update([
            'quantity' => AssetUnit::where('asset_id', $newAssetId)->count(),
        ]);
        return redirect()->route('units.index')->with('success', 'Unit diperbarui.');
    }

    public function destroy(AssetUnit $unit)
    {
        $assetId = $unit->asset_id;
        $unit->forceDelete();
        Asset::where('id', $assetId)->update([
            'quantity' => AssetUnit::where('asset_id', $assetId)->count(),
        ]);
        return back()->with('success', 'Unit dihapus.');
    }

    public function show(AssetUnit $unit)
    {
        return view('units.show', compact('unit'));
    }

    public function retire(AssetUnit $unit)
    {
        if ($unit->status === 'retired') {
            return back()->with('info', 'Unit sudah retired.');
        }

        $unit->update(['status' => 'retired', 'notes' => ($unit->notes ?? '') . ' [Retired on ' . now()->format('d M Y') . ']']);
        return back()->with('success', 'Unit berhasil di-retire.');
    }

    public function maintenance(AssetUnit $unit)
    {
        if ($unit->status === 'maintenance') {
            return back()->with('info', 'Unit sudah dalam maintenance.');
        }

        $unit->update(['status' => 'maintenance']);
        return back()->with('success', 'Unit masuk maintenance.');
    }

    public function available(AssetUnit $unit)
    {
        if ($unit->status === 'available') {
            return back()->with('info', 'Unit sudah available.');
        }

        if ($unit->status === 'borrowed') {
            return back()->with('error', 'Unit sedang dipinjam. Kembalikan melalui peminjaman.');
        }

        $unit->update(['status' => 'available']);
        return back()->with('success', 'Unit set available.');
    }
}
