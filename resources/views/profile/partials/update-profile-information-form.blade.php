<section>
    <div class="mb-3">
        <h5 class="mb-1">Informasi Profil</h5>
        <p class="text-muted mb-0">Perbarui data akun dan email Anda.</p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success" role="alert">
            Perubahan profil berhasil disimpan.
        </div>
    @endif

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Nama</label>
                <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="profile_photo" class="form-label">Foto Profil</label>
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('assets/img/avatars/1.png') }}" alt="Foto Profil" class="rounded-circle" width="64" height="64">
                    <div class="flex-grow-1">
                        <input id="profile_photo" name="profile_photo" type="file" class="form-control" accept="image/*" />
                        @error('profile_photo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="col-12">
                    <div class="alert alert-warning mb-0" role="alert">
                        Email Anda belum terverifikasi.
                        <button form="send-verification" class="btn btn-sm btn-outline-warning ms-2">Kirim Ulang Verifikasi</button>
                    </div>
                    @if (session('status') === 'verification-link-sent')
                        <div class="small text-success mt-2">Tautan verifikasi baru sudah dikirim.</div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-end mt-3">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</section>
