<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Brand</h5>
            <div class="d-flex gap-2">
                <form action="{{ route('brands.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari brand..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary"><i class="bx bx-search"></i></button>
                </form>
                <a href="{{ route('brands.create') }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus"></i> Tambah Brand
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Aset</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                        <tr>
                            <td>
                                @if($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" 
                                        class="rounded" style="max-height: 40px; max-width: 40px;">
                                @else
                                    <span class="text-muted small">Tidak ada</span>
                                @endif
                            </td>
                            <td><strong><a href="{{ route('brands.show', $brand) }}">{{ $brand->name }}</a></strong></td>
                            <td>{{ Str::limit($brand->description, 50) }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $brand->assets->count() }}</span>
                            </td>
                            <td>
                                <a href="{{ route('brands.edit', $brand) }}" class="btn btn-sm btn-warning">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('brands.destroy', $brand) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Tidak ada brand
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">
            {{ $brands->links() }}
        </div>
    </div>
</x-app-layout>
