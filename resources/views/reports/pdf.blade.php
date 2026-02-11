<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Laporan Aset</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 18px; margin: 0 0 10px; }
        h2 { font-size: 14px; margin: 16px 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f4f4f4; }
        .metric { margin-bottom: 6px; }
    </style>
</head>
<body>
    <h1>Laporan Aset</h1>
    <div class="metric">Periode: {{ $periodLabel }}</div>
    <div class="metric">Total Aset (types): {{ $totalAssetTypes }}</div>
    <div class="metric">Total Units: {{ $totalUnits }}</div>
    <div class="metric">Total Harga Aset: Rp {{ number_format($totalAssetValue, 0, ',', '.') }}</div>
    <div class="metric">Total Dipakai (Unit): {{ $borrowedUnits }}</div>
    <div class="metric">Total Pernah Dipakai (Unit): {{ $returnedUnits }}</div>
    <div class="metric">Kerugian (Unit Tidak Bisa Dipakai): {{ $notUsableUnits }} unit (Rp {{ number_format($notUsableValue, 0, ',', '.') }})</div>
    <div class="metric">Status dihitung: {{ implode(', ', $notUsableStatuses) }}</div>

    <h2>Status Unit</h2>
    <table>
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

    <h2>Status Aset</h2>
    <table>
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

    <h2>Detail Aset Sedang Dipakai</h2>
    <table>
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
                    <td colspan="4">Tidak ada pemakaian aktif.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Detail Aset Pernah Dipakai</h2>
    <table>
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
                    <td colspan="5">Belum ada riwayat pemakaian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
