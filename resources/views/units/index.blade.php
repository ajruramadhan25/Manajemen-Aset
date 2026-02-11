<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Unit Aset</h5>
            <div class="d-flex">
                <form method="GET" action="{{ route('units.index') }}" class="me-2">
                    <div class="input-group input-group-sm">
                        <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari identifier, aset, atau catatan...">
                        <button class="btn btn-outline-secondary" type="submit">Cari</button>
                    </div>
                </form>
                <a href="{{ route('units.create') }}" class="btn btn-primary btn-sm">Tambah Unit</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Aset</th>
                            <th>Identifier</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($units as $unit)
                            <tr>
                                <td>{{ $unit->id }}</td>
                                <td>{{ $unit->asset->name ?? '-' }}</td>
                                <td><strong>{{ $unit->unique_identifier ?? '-' }}</strong></td>
                                <td>
                                    <span class="badge bg-label-{{ $unit->status == 'available' ? 'success' : ($unit->status == 'retired' ? 'danger' : ($unit->status == 'maintenance' ? 'warning' : 'secondary')) }}">
                                        {{ ucfirst($unit->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($unit->status !== 'available' && $unit->status !== 'borrowed')
                                            <form action="{{ route('units.available', $unit) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-outline-success btn-sm" title="Set Available">Available</button>
                                            </form>
                                        @endif
                                        @if($unit->status !== 'maintenance')
                                            <form action="{{ route('units.maintenance', $unit) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-outline-warning btn-sm" title="Set Maintenance">Maintenance</button>
                                            </form>
                                        @endif
                                        @if($unit->status !== 'retired')
                                            <form action="{{ route('units.retire', $unit) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-outline-danger btn-sm" title="Retire">Retire</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('units.edit', $unit) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $units->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
