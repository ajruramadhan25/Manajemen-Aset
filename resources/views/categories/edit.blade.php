<x-app-layout>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Kategori</h5>
            <small class="text-muted float-end">Perbarui nama kategori</small>
        </div>
        <div class="card-body">
            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="parent_category_id">Kategori Parent (Opsional)</label>
                    <select class="form-select" id="parent_category_id" name="parent_category_id">
                        <option value="">-- Tidak Ada (Kategori Utama) --</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_category_id', $category->parent_category_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_category_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name">Nama Kategori</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ old('name', $category->name) }}" required />
                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update Kategori</button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-app-layout>