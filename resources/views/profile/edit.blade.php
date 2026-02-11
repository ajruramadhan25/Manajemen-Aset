<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">Profil Saya</h4>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        @php
                            $profilePhotoUrl = $user->profile_photo_path
                                ? asset('storage/' . $user->profile_photo_path)
                                : asset('assets/img/avatars/1.png');
                        @endphp
                        <img src="{{ $profilePhotoUrl }}" alt="Foto Profil" class="rounded-circle mb-3" width="96" height="96">
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-2">{{ $user->email }}</p>

                        <div class="d-flex justify-content-center gap-2">
                            <span class="badge bg-label-primary text-uppercase">{{ $user->role ?? 'karyawan' }}</span>
                            @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <span class="badge bg-label-warning">Belum Verifikasi</span>
                            @else
                                <span class="badge bg-label-success">Terverifikasi</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
