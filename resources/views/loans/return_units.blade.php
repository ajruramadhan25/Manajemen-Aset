<x-app-layout>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Kembalikan Unit untuk Pemakaian #{{ $loan->id }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('loans.return.units', $loan) }}" method="POST">
                @csrf
                <p><strong>Aset:</strong> {{ $loan->asset->name }}</p>
                <p><strong>Pengguna:</strong> {{ $loan->user->name }}</p>
                <div class="mb-3">
                    <label class="form-label">Pilih Unit yang Dikembalikan</label>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th style="width: 50px;">Pilih</th>
                                <th>Unit</th>
                                <th>Catatan Pengembalian (opsional)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignedUnits as $unit)
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" name="unit_ids[]" value="{{ $unit->id }}" id="u{{ $unit->id }}">
                                    </td>
                                    <td>
                                        <label class="form-check-label" for="u{{ $unit->id }}">{{ $unit->unique_identifier ?? ('Unit #' . $unit->id) }}</label>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="unit_notes[{{ $unit->id }}]" placeholder="mis: lecet, penyok, dll">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <button class="btn btn-primary">Proses Pengembalian</button>
                <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-app-layout>
