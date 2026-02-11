<x-app-layout>
    <div class="row">
        <!-- Asset Details -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    @php
                        $assetImageUrl = null;
                        if ($asset->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($asset->image)) {
                            $assetImageUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($asset->image);
                        }
                    @endphp

                    @if($assetImageUrl)
                        <img src="{{ $assetImageUrl }}" alt="Asset Image" class="img-fluid rounded mb-3"
                            style="max-height: 200px;">
                    @else
                        <div class="d-flex justify-content-center align-items-center bg-label-secondary rounded mb-3"
                            style="height: 200px;">
                            <i class="bx bx-image fs-1"></i>
                        </div>
                    @endif


                    <h4>{{ $asset->name }}</h4>
                    <p class="text-muted">{{ $asset->asset_code }}</p>

                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span
                            class="badge bg-label-{{ $asset->status == 'available' ? 'success' : ($asset->status == 'deployed' ? 'primary' : 'warning') }}">
                            {{ strtoupper($asset->status) }}
                        </span>
                        <span class="badge bg-label-info">{{ $asset->category->name }}</span>
                    </div>

                    <div class="d-grid gap-2">
                        @php
                            $activeLoans = \App\Models\AssetLoan::where('asset_id', $asset->id)
                                ->where('status', 'borrowed')
                                ->get();
                            $totalBorrowed = $activeLoans->sum('quantity_borrowed');
                            $availableStock = $asset->quantity - $totalBorrowed;
                        @endphp

                        @if($availableStock > 0)
                            <a href="{{ route('loans.create', $asset) }}" class="btn btn-primary">
                                <i class="bx bx-export me-1"></i> Checkout (Pemakaian)
                            </a>
                        @else
                            <button type="button" class="btn btn-secondary" disabled>
                                <i class="bx bx-export me-1"></i> Stok Habis
                            </button>
                        @endif

                        @if($activeLoans->count() > 0 && in_array((Auth::user()->role ?? 'karyawan'), ['admin', 'super_admin'], true))
                            <div class="alert alert-warning mb-0">
                                <strong>Sedang Dipakai:</strong>
                                @foreach($activeLoans as $loan)
                                    <div class="mt-2">
                                        <small>
                                            • {{ $loan->user->name }} ({{ $loan->quantity_borrowed }} unit) sejak {{ $loan->loan_date->format('d M Y') }}
                                            <form action="{{ route('loans.return', $loan) }}" method="POST" class="d-inline ms-2">
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-success" 
                                                    onclick="return confirm('Return {{ $loan->quantity_borrowed }} unit untuk {{ $loan->user->name }}?')">
                                                    Return
                                                </button>
                                            </form>
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(in_array((Auth::user()->role ?? 'karyawan'), ['admin', 'super_admin'], true))
                            <a href="{{ route('assets.edit', $asset) }}" class="btn btn-outline-secondary">Edit Asset</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Tabs -->
        <div class="col-md-8">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-details">
                            Detail & Spesifikasi
                        </button>
                    </li>
                    @if(in_array((Auth::user()->role ?? 'karyawan'), ['admin', 'super_admin'], true))
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-depreciation">
                                Depresiasi
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-history">
                                Riwayat (Log)
                            </button>
                        </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <!-- Details Tabs -->
                    <div class="tab-content">
                        <!-- Detail Tab -->
                        <div class="tab-pane fade show active" id="navs-details" role="tabpanel">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th class="w-25">Jumlah Aset</th>
                                        <td>{{ $asset->quantity }} unit</td>
                                    </tr>
                                    <tr>
                                        <th>Harga Satuan</th>
                                        <td>Rp {{ number_format($asset->price, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Harga Total</th>
                                        <td><strong>Rp {{ number_format($asset->price * $asset->quantity, 2) }}</strong></td>
                                    </tr>
                                    @php
                                        $totalBorrowed = \App\Models\AssetLoan::where('asset_id', $asset->id)
                                            ->where('status', 'borrowed')
                                            ->sum('quantity_borrowed');
                                        $availableStock = $asset->quantity - $totalBorrowed;
                                    @endphp
                                    <tr>
                                        <th>Stok Tersedia</th>
                                        <td>
                                            <span class="badge bg-label-{{ $availableStock > 0 ? 'success' : 'danger' }}">
                                                {{ $availableStock }} unit
                                            </span>
                                            ({{ $totalBorrowed }} unit dipakai)
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Beli</th>
                                        <td>{{ $asset->purchase_date->format('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Masa Manfaat</th>
                                        <td>{{ $asset->useful_life }} Tahun</td>
                                    </tr>
                                    <tr>
                                        <th>Nilai Sisa</th>
                                        <td>Rp {{ number_format($asset->residual_value, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Dibuat Pada</th>
                                        <td>{{ $asset->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            @if($asset->units && $asset->units->count() > 0)
                                <h5 class="mt-4">Unit Individu</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Identifier</th>
                                                <th>Status</th>
                                                <th>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($asset->units as $unit)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $unit->unique_identifier ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge bg-label-{{ $unit->status == 'available' ? 'success' : ($unit->status == 'borrowed' ? 'warning' : 'secondary') }}">
                                                            {{ ucfirst($unit->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $unit->notes ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if(in_array((Auth::user()->role ?? 'karyawan'), ['admin', 'super_admin'], true))
                        <!-- Depreciation Tab -->
                        <div class="tab-pane fade" id="navs-depreciation" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tahun Ke</th>
                                            <th>Tanggal</th>
                                            <th>Penyusutan</th>
                                            <th>Nilai Buku</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($depreciationSchedule as $schedule)
                                            <tr>
                                                <td>{{ $schedule['year'] }}</td>
                                                <td>{{ $schedule['date'] }}</td>
                                                <td>Rp {{ number_format($schedule['depreciation_amount'], 2) }}</td>
                                                <td>Rp {{ number_format($schedule['book_value'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- History Tab -->
                        <div class="tab-pane fade" id="navs-history" role="tabpanel">
                            <ul class="timeline">
                                @forelse($logs as $log)
                                    <li class="timeline-item timeline-item-transparent">
                                        <span class="timeline-point timeline-point-primary"></span>
                                        <div class="timeline-event">
                                            <div class="timeline-header mb-1">
                                                <h6 class="mb-0">{{ $log->action }}</h6>
                                                <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-2">{{ $log->user->name ?? 'System' }} melakukan
                                                {{ strtolower($log->action) }}
                                            </p>
                                            @if($log->details && is_array($log->details))
                                                <div class="bg-label-secondary p-2 rounded">
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach($log->details as $key => $value)
                                                            <li>
                                                                <small><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                                                                {{ is_array($value) ? json_encode($value) : $value }}</small>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                @empty
                                    <p class="text-center">Belum ada riwayat.</p>
                                @endforelse
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>