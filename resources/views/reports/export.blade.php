<table>
    <tr><td colspan="2"><strong>Laporan Aset</strong></td></tr>
    <tr><td>Periode</td><td>{{ $periodLabel }}</td></tr>
    <tr><td>Total Aset (types)</td><td>{{ $totalAssetTypes }}</td></tr>
    <tr><td>Total Units</td><td>{{ $totalUnits }}</td></tr>
    <tr><td>Total Harga Aset</td><td>{{ $totalAssetValue }}</td></tr>
    <tr><td>Total Dipakai (Unit)</td><td>{{ $borrowedUnits }}</td></tr>
    <tr><td>Total Pernah Dipakai (Unit)</td><td>{{ $returnedUnits }}</td></tr>
    <tr><td>Kerugian (Unit Tidak Bisa Dipakai)</td><td>{{ $notUsableUnits }}</td></tr>
    <tr><td>Kerugian (Rp)</td><td>{{ $notUsableValue }}</td></tr>
    <tr><td>Status dihitung</td><td>{{ implode(', ', $notUsableStatuses) }}</td></tr>
</table>

<table>
    <tr><td colspan="2"><strong>Status Unit</strong></td></tr>
    <tr><th>Status</th><th>Jumlah</th></tr>
    @foreach($unitStatusCounts as $status => $count)
        <tr><td>{{ $status }}</td><td>{{ $count }}</td></tr>
    @endforeach
</table>

<table>
    <tr><td colspan="2"><strong>Status Aset</strong></td></tr>
    <tr><th>Status</th><th>Jumlah</th></tr>
    @foreach($assetStatusCounts as $status => $count)
        <tr><td>{{ $status }}</td><td>{{ $count }}</td></tr>
    @endforeach
</table>

<table>
    <tr><td colspan="4"><strong>Detail Aset Sedang Dipakai</strong></td></tr>
    <tr><th>Aset</th><th>Pengguna</th><th>Jumlah</th><th>Tanggal Pakai</th></tr>
    @forelse($borrowedLoans as $loan)
        <tr>
            <td>{{ $loan->asset->name ?? '-' }}</td>
            <td>{{ $loan->user->name ?? '-' }}</td>
            <td>{{ $loan->quantity_borrowed }}</td>
            <td>{{ optional($loan->loan_date)->format('Y-m-d') }}</td>
        </tr>
    @empty
        <tr><td colspan="4">Tidak ada pemakaian aktif.</td></tr>
    @endforelse
</table>

<table>
    <tr><td colspan="5"><strong>Detail Aset Pernah Dipakai</strong></td></tr>
    <tr><th>Aset</th><th>Pengguna</th><th>Jumlah</th><th>Tanggal Pakai</th><th>Tanggal Kembali</th></tr>
    @forelse($returnedLoans as $loan)
        <tr>
            <td>{{ $loan->asset->name ?? '-' }}</td>
            <td>{{ $loan->user->name ?? '-' }}</td>
            <td>{{ $loan->original_quantity ?? $loan->quantity_borrowed }}</td>
            <td>{{ optional($loan->loan_date)->format('Y-m-d') }}</td>
            <td>{{ optional($loan->return_date)->format('Y-m-d') }}</td>
        </tr>
    @empty
        <tr><td colspan="5">Belum ada riwayat pemakaian.</td></tr>
    @endforelse
</table>
