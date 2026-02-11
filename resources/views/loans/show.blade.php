<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Pemakaian Aset #{{ $loan->id }}</h5>
            <div>
                <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Kembali</a>
                @if($loan->status == 'borrowed')
                    <form action="{{ route('loans.return', $loan) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-danger" onclick="return confirm('Kembalikan seluruh pemakaian?')">Kembalikan Semua</button>
                    </form>
                @endif
            </div>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Aset</dt>
                <dd class="col-sm-9">{{ $loan->asset->name ?? '-' }}</dd>

                <dt class="col-sm-3">Pengguna</dt>
                <dd class="col-sm-9">{{ $loan->user->name ?? '-' }}</dd>

                <dt class="col-sm-3">Jumlah Dipakai</dt>
                <dd class="col-sm-9">{{ $loan->original_quantity ?? $loan->quantity_borrowed }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{{ ucfirst($loan->status) }}</dd>

                <dt class="col-sm-3">Tanggal Pakai</dt>
                <dd class="col-sm-9">{{ $loan->loan_date?->format('d M Y H:i') ?? '-' }}</dd>

                <dt class="col-sm-3">Tanggal Kembali</dt>
                <dd class="col-sm-9">{{ $loan->return_date?->format('d M Y H:i') ?? '-' }}</dd>

                <dt class="col-sm-3">Units</dt>
                <dd class="col-sm-9">
                    @if($loan->units->count())
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Identifier</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loan->units as $i => $unit)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $unit->unique_identifier ?? ('Unit #' . $unit->id) }}</td>
                                        <td>{{ ucfirst($unit->status) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        -
                    @endif
                </dd>

                <dt class="col-sm-3">Catatan</dt>
                <dd class="col-sm-9">{{ $loan->notes ?? '-' }}</dd>
            </dl>
        </div>
    </div>
</x-app-layout>
