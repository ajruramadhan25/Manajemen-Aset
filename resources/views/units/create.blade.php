<x-app-layout>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Tambah Unit Aset</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('units.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Aset</label>
                    <select name="asset_id" class="form-select" required>
                        <option value="">Pilih Aset</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}">{{ $asset->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Identifier (mis. plat)</label>
                    <input type="text" name="unique_identifier" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="available">Available</option>
                        <option value="borrowed">Borrowed</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="retired">Retired</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>

                <button class="btn btn-primary">Buat Unit</button>
                <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-app-layout>
