<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Aset</h5>
            @if(in_array((Auth::user()->role ?? 'karyawan'), ['admin', 'super_admin'], true))
                <a href="{{ route('assets.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Aset
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible m-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="card-body border-bottom">
            <form action="{{ route('assets.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Cari Nama/Kode</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari aset..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories->where('parent_category_id', '!=', null) as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Brand</label>
                    <select name="brand_id" class="form-select">
                        <option value="">Semua Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-search"></i> Filter
                        </button>
                        <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Aset</th>
                        <th>Kode</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Harga Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($assets as $asset)
                        <tr>
                            <td>
                                <a href="{{ route('assets.show', $asset) }}" class="text-body">
                                    <i class="bx bx-cube fa-lg text-primary me-3"></i> <strong>{{ $asset->name }}</strong>
                                </a>
                            </td>
                            <td>{{ $asset->asset_code }}</td>
                            <td><span class="badge bg-label-info">{{ $asset->category->name ?? '-' }}</span></td>
                            <td>
                                @php
                                    $statusColors = [
                                        'available' => 'success',
                                        'deployed' => 'primary',
                                        'maintenance' => 'warning',
                                        'broken' => 'danger',
                                    ];
                                    $color = $statusColors[$asset->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-label-{{ $color }} me-1">{{ strtoupper($asset->status) }}</span>
                            </td>
                            <td>
                                @php
                                    $totalBorrowed = \App\Models\AssetLoan::where('asset_id', $asset->id)
                                        ->where('status', 'borrowed')
                                        ->sum('quantity_borrowed');
                                    $availableStock = $asset->quantity - $totalBorrowed;
                                @endphp
                                <span class="badge bg-label-{{ $availableStock > 0 ? 'success' : 'danger' }}">
                                    {{ $availableStock }}/{{ $asset->quantity }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($asset->price, 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($asset->price * $asset->quantity, 0, ',', '.') }}</strong></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('assets.show', $asset) }}" class="btn btn-icon btn-outline-info"
                                        title="Detail">
                                        <i class="bx bx-show"></i>
                                    </a>
                                    @if(in_array((Auth::user()->role ?? 'karyawan'), ['admin', 'super_admin'], true))
                                        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-icon btn-outline-warning"
                                            title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-outline-danger" title="Delete">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data aset.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $assets->links() }}
        </div>
    </div>
</x-app-layout>