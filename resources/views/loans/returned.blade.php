<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Riwayat Pengembalian</h5>
            <div class="d-flex">
                <form method="GET" action="{{ route('loans.returned') }}" class="d-flex me-2">
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control form-control-sm me-1" placeholder="Cari aset/pengguna/unit...">
                    <input type="date" name="date_from" class="form-control form-control-sm me-1" value="{{ request('date_from') }}">
                    <input type="date" name="date_to" class="form-control form-control-sm me-1" value="{{ request('date_to') }}">
                    <button class="btn btn-sm btn-outline-secondary" type="submit">Filter</button>
                </form>
                <a href="{{ route('loans.index') }}" class="btn btn-outline-primary">Kembali ke Pemakaian Aset</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Aset</th>
                            <th>Pengguna</th>
                            <th>Unit</th>
                            <th>Catatan Pengembalian</th>
                            <th>Tanggal Kembali</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batches as $batch)
                            <tr>
                                <td>{{ $batch->batch_id }}</td>
                                <td>{{ $batch->loan->asset->name ?? '-' }}</td>
                                <td>{{ $batch->user->name ?? '-' }}</td>
                                <td>
                                    @foreach($batch->units as $u)
                                        <div>{{ $u->unit->unique_identifier ?? ('Unit #' . $u->unit->id) }}</div>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($batch->units as $u)
                                        <div>{{ $u->notes ?? '-' }}</div>
                                    @endforeach
                                </td>
                                <td>{{ $batch->returned_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('loans.show', $batch->loan) }}" class="btn btn-sm btn-outline-info">Detail Pemakaian</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $batches->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
