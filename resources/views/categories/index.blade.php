<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Kategori</h5>
            <div class="d-flex gap-2">
                <form action="{{ route('categories.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari kategori..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary"><i class="bx bx-search"></i></button>
                </form>
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Kategori
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible m-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible m-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Tipe</th>
                        <th>Jumlah Aset</th>
                        <th>Tanggal Dibuat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($categories as $category)
                        <tr>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td><span class="badge bg-label-info">Parent</span></td>
                            <td><span class="badge bg-label-primary">{{ $category->getTotalAssetCount() }} Aset</span></td>
                            <td>{{ $category->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('categories.edit', $category) }}"
                                        class="btn btn-icon btn-outline-warning" title="Edit">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                        class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-outline-danger" title="Delete">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @if($category->children->count() > 0)
                            @foreach($category->children as $subcategory)
                                <tr class="table-light">
                                    <td style="padding-left: 3rem;">└─ {{ $subcategory->name }}</td>
                                    <td><span class="badge bg-label-secondary">Sub</span></td>
                                    <td><span class="badge bg-label-primary">{{ $subcategory->getTotalAssetCount() }} Aset</span></td>
                                    <td>{{ $subcategory->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('categories.edit', $subcategory) }}"
                                                class="btn btn-icon btn-outline-warning" title="Edit">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $subcategory) }}" method="POST"
                                                class="delete-form" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-outline-danger" title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>