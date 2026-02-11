<x-app-layout>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Laporan Aset</h5>
                <small class="text-muted">Periode: {{ $periodLabel }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('reports.export.excel', request()->query()) }}" class="btn btn-outline-success btn-sm">Export Excel</a>
                <a href="{{ route('reports.export.pdf', request()->query()) }}" class="btn btn-outline-danger btn-sm">Export PDF</a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="date" name="date_from" class="form-control" value="{{ $filterDateFrom }}" placeholder="Dari tanggal">
                </div>
                <div class="col-md-4">
                    <input type="date" name="date_to" class="form-control" value="{{ $filterDateTo }}" placeholder="Sampai tanggal">
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </form>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="fw-semibold">Total Aset (types)</div>
                            <div class="fs-4">{{ $totalAssetTypes }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="fw-semibold">Total Units</div>
                            <div class="fs-4">{{ $totalUnits }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="fw-semibold">Total Harga Aset</div>
                            <div class="fs-4">Rp {{ number_format($totalAssetValue, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="fw-semibold">Total Dipakai (Unit)</div>
                            <div class="fs-4">{{ $borrowedUnits }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="fw-semibold">Total Pernah Dipakai (Unit)</div>
                            <div class="fs-4">{{ $returnedUnits }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="fw-semibold">Kerugian (Unit Tidak Bisa Dipakai)</div>
                            <div class="fs-4">{{ $notUsableUnits }} unit</div>
                            <div class="text-muted">Rp {{ number_format($notUsableValue, 0, ',', '.') }}</div>
                            <div class="small text-muted">Status dihitung: {{ implode(', ', $notUsableStatuses) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Status Unit</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unitStatusCounts as $status => $count)
                                <tr>
                                    <td>{{ ucfirst($status) }}</td>
                                    <td>{{ $count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Status Aset</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assetStatusCounts as $status => $count)
                                <tr>
                                    <td>{{ ucfirst($status) }}</td>
                                    <td>{{ $count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
                    <h6 class="mb-0">Detail Aset Sedang Dipakai</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Aset</th>
                                <th>Pengguna</th>
                            <th>Jumlah</th>
                                <th>Tanggal Pakai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowedLoans as $loan)
                            <tr>
                                <td>{{ $loan->asset->name ?? '-' }}</td>
                                <td>{{ $loan->user->name ?? '-' }}</td>
                                <td>{{ $loan->quantity_borrowed }}</td>
                                <td>{{ optional($loan->loan_date)->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada pemakaian aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
                    <h6 class="mb-0">Detail Aset Pernah Dipakai</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Aset</th>
                                <th>Pengguna</th>
                            <th>Jumlah</th>
                                <th>Tanggal Pakai</th>
                            <th>Tanggal Kembali</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returnedLoans as $loan)
                            <tr>
                                <td>{{ $loan->asset->name ?? '-' }}</td>
                                <td>{{ $loan->user->name ?? '-' }}</td>
                                <td>{{ $loan->original_quantity ?? $loan->quantity_borrowed }}</td>
                                <td>{{ optional($loan->loan_date)->format('d M Y') }}</td>
                                <td>{{ optional($loan->return_date)->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada riwayat pemakaian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
