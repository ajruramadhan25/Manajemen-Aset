<x-app-layout>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Tambah Brand Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="name">Nama Brand</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="contoh: ASUS"
                        value="{{ old('name') }}" required />
                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="4"
                        placeholder="Deskripsi brand...">{{ old('description') }}</textarea>
                    @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="logo">Logo (Opsional)</label>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*" />
                    @error('logo') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Brand</button>
                <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-app-layout>
