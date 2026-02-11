<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Brand: {{ $brand->name }}</h5>
            <a href="{{ route('brands.index') }}" class="btn btn-outline-primary">Kembali</a>
        </div>
        <div class="card-body">
            <p><strong>Deskripsi:</strong> {{ $brand->description ?? '-' }}</p>

            <h6 class="mt-4">Aset pada brand ini</h6>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Aset</th>
                            <th>Kategori</th>
                            <th>Jumlah Unit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                            <tr>
                                <td>{{ $asset->id }}</td>
                                <td>{{ $asset->name }}</td>
                                <td>{{ $asset->category?->name ?? '-' }}</td>
                                <td>{{ $asset->quantity }}</td>
                                <td>
                                    <a href="{{ route('assets.show', $asset) }}" class="btn btn-sm btn-info">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada aset untuk brand ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $assets->links() }}
            </div>
        </div>
    </div>
</x-app-layout>