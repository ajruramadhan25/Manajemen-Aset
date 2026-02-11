<x-app-layout>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Kategori Baru</h5>
            <small class="text-muted float-end">Kategori parent atau sub-kategori</small>
        </div>
        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="parent_category_id">Kategori Parent (Opsional)</label>
                    <select class="form-select" id="parent_category_id" name="parent_category_id">
                        <option value="">-- Tidak Ada (Kategori Utama) --</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_category_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Kosongkan untuk membuat kategori utama, atau pilih untuk membuat sub-kategori</small>
                    @error('parent_category_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name">Nama Kategori</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Contoh: Elektronik atau Laptop"
                        value="{{ old('name') }}" required />
                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-app-layout>