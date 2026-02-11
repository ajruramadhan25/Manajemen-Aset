<x-app-layout>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Unit Aset</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('units.update', $unit) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Aset</label>
                    <select name="asset_id" class="form-select" required>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" {{ $asset->id == $unit->asset_id ? 'selected' : '' }}>{{ $asset->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Identifier (mis. plat)</label>
                    <input type="text" name="unique_identifier" class="form-control" value="{{ old('unique_identifier', $unit->unique_identifier) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="available" {{ $unit->status == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="borrowed" {{ $unit->status == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                        <option value="maintenance" {{ $unit->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="retired" {{ $unit->status == 'retired' ? 'selected' : '' }}>Retired</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $unit->notes) }}</textarea>
                </div>

                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-app-layout>
