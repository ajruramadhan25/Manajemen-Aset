<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Pemakaian Aset</h5>
            <form method="GET" action="{{ route('loans.index') }}" class="d-flex align-items-center">
                <input type="search" name="search" value="{{ request('search') }}" class="form-control form-control-sm me-2" placeholder="Cari aset/pengguna/unit...">
                <div class="me-2">
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" placeholder="From">
                </div>
                <div class="me-2">
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" placeholder="To">
                </div>
                <button class="btn btn-sm btn-outline-secondary" type="submit">Filter</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Aset</th>
                            <th>Pengguna</th>
                            <th>Jumlah</th>
                            <th>Units</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                            <tr>
                                <td>{{ $loan->id }}</td>
                                <td>{{ $loan->asset->name ?? '-' }}</td>
                                <td>{{ $loan->user->name ?? '-' }}</td>
                                <td>
                                    @if($loan->status === 'returned')
                                        {{ $loan->original_quantity ?? $loan->quantity_borrowed }}
                                    @else
                                        {{ $loan->quantity_borrowed }}
                                    @endif
                                </td>
                                <td>
                                    @if($loan->units->count())
                                        {{ $loan->units->count() }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ ucfirst($loan->status) }}</td>
                                <td>{{ $loan->loan_date->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm btn-info">Detail</a>
                                    @if($loan->status == 'borrowed')
                                        <a href="{{ route('loans.return.units.form', $loan) }}" class="btn btn-sm btn-outline-primary ms-1">Return Units</a>
                                        <form action="{{ route('loans.return', $loan) }}" method="POST" class="d-inline ms-1">
                                            @csrf
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Return seluruh pemakaian?')">Return All</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $loans->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
