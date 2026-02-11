<x-app-layout>
    @if(in_array((Auth::user()->role ?? 'karyawan'), ['admin', 'super_admin'], true))
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 order-1">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-cube"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Total Aset (tipe)</span>
                    <h3 class="card-title mb-2">{{ $totalAssets }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 order-2">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-cube"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Total Unit</span>
                    <h3 class="card-title mb-2">{{ $totalUnits ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 order-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-check-circle"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Unit Tersedia</span>
                    <h3 class="card-title mb-2">{{ $availableUnits ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 order-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-user"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Unit Dipakai</span>
                    <h3 class="card-title mb-2">{{ $borrowedUnits ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang, {{ Auth::user()->name }}!</h5>
                            <p class="mb-4">
                                Anda berhasil masuk sebagai <span class="fw-bold">{{ ucfirst(Auth::user()->role ?? 'karyawan') }}</span>. Lihat ringkasan aset di bawah ini.
                            </p>

                            <a href="{{ route('assets.index') }}" class="btn btn-sm btn-outline-primary">Lihat Aset</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140"
                                alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                data-app-light-img="illustrations/man-with-laptop-light.png" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <span class="fw-semibold d-block mb-1">Pemakaian Aktif</span>
                    <h3 class="card-title mb-2">{{ $myBorrowed ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <span class="fw-semibold d-block mb-1">Total Unit Dipakai</span>
                    <h3 class="card-title mb-2">{{ $myBorrowedUnits ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <span class="fw-semibold d-block mb-1">Riwayat Pengembalian</span>
                    <h3 class="card-title mb-2">{{ $myReturned ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">Selamat Datang, {{ Auth::user()->name }}!</h5>
                    <p class="mb-4">Silakan kelola pemakaian dan pengembalian aset Anda.</p>
                    <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-primary me-2">Pemakaian Saya</a>
                    <a href="{{ route('loans.returned') }}" class="btn btn-sm btn-outline-secondary">Riwayat Pengembalian</a>
                </div>
            </div>
        </div>
    </div>
    @endif
</x-app-layout>