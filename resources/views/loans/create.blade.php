<x-app-layout>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Checkout Aset (Pemakaian)</h5>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h6>Detail Aset</h6>
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md me-3">
                        <span class="avatar-initial rounded-circle bg-label-primary"><i class='bx bx-cube'></i></span>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $asset->name }}</h6>
                        <small>{{ $asset->asset_code }}</small>
                        <div class="small text-muted mt-1">
                            <strong>Jumlah Total:</strong> {{ $asset->quantity }} unit<br>
                            <strong>Harga Satuan:</strong> Rp {{ number_format($asset->price, 0, ',', '.') }}<br>
                            <strong>Harga Total:</strong> Rp {{ number_format($asset->price * $asset->quantity, 0, ',', '.') }}<br>
                            @php
                                $totalBorrowed = \App\Models\AssetLoan::where('asset_id', $asset->id)
                                    ->where('status', 'borrowed')
                                    ->sum('quantity_borrowed');
                                $availableStock = $asset->quantity - $totalBorrowed;
                            @endphp
                            <strong>Stok Tersedia:</strong> <span class="badge bg-label-{{ $availableStock > 0 ? 'success' : 'danger' }}">{{ $availableStock }} unit</span>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('loans.store', $asset) }}" method="POST">
                @csrf
                @if(!empty($canSelectUser) && $canSelectUser)
                    <div class="mb-3">
                        <label class="form-label" for="user_id">Pengguna (Karyawan)</label>
                        <select id="user_id" name="user_id" class="form-select" required>
                            <option value="">Pilih Karyawan</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="mb-3">
                        <label class="form-label">Pengguna</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label" for="quantity_borrowed">Jumlah yang Dipakai</label>
                    @php
                        $totalBorrowed = \App\Models\AssetLoan::where('asset_id', $asset->id)
                            ->where('status', 'borrowed')
                            ->sum('quantity_borrowed');
                        $availableStock = $asset->quantity - $totalBorrowed;
                    @endphp
                    <input type="number" class="form-control" id="quantity_borrowed" name="quantity_borrowed" 
                        placeholder="1" min="1" max="{{ $availableStock }}"
                        value="{{ old('quantity_borrowed', 1) }}" required />
                    <small class="form-text text-muted">Maksimal: {{ $availableStock }} unit</small>
                    @error('quantity_borrowed') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                @if(isset($availableUnits) && $availableUnits->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">Pilih Unit Spesifik</label>
                        <div class="small text-muted mb-2">Centang unit yang ingin dipakai. Jumlah yang dicentang harus sama dengan jumlah di atas.</div>
                        <div id="unitSelectError" class="alert alert-danger d-none">Pilih minimal satu unit.</div>
                        <div class="row">
                            @foreach($availableUnits as $unit)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input unit-checkbox" type="checkbox" value="{{ $unit->id }}" name="unit_ids[]" id="unit_{{ $unit->id }}">
                                        <label class="form-check-label" for="unit_{{ $unit->id }}">
                                            {{ $unit->unique_identifier ?? ('Unit #' . $unit->id) }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('unit_ids') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <script>
                        (function(){
                            const checkboxes = document.querySelectorAll('.unit-checkbox');
                            const qtyInput = document.getElementById('quantity_borrowed');
                            const form = document.querySelector('form');

                            const errorBox = document.getElementById('unitSelectError');

                            function updateQty() {
                                const checked = Array.from(checkboxes).filter(ch => ch.checked).length;
                                if (checked > 0) {
                                    qtyInput.value = checked;
                                }
                                if (errorBox) {
                                    errorBox.classList.toggle('d-none', checked > 0);
                                }
                            }

                            checkboxes.forEach(ch => ch.addEventListener('change', updateQty));

                            form.addEventListener('submit', function(e) {
                                const checked = Array.from(checkboxes).filter(ch => ch.checked).length;
                                if (checked > 0) {
                                    qtyInput.value = checked;
                                } else {
                                    if (errorBox) {
                                        errorBox.classList.remove('d-none');
                                    }
                                    e.preventDefault();
                                }
                            });
                        })();
                    </script>
                @endif

                <div class="mb-3">
                    <label class="form-label" for="notes">Catatan / Keperluan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"
                        placeholder="Contoh: Untuk keperluan proyek A"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Proses Pemakaian</button>
                <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-app-layout>