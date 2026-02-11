<section>
    <div class="mb-3">
        <h5 class="mb-1">Ubah Password</h5>
        <p class="text-muted mb-0">Gunakan password yang kuat untuk menjaga keamanan akun.</p>
    </div>

    @if (session('status') === 'password-updated')
        <div class="alert alert-success" role="alert">
            Password berhasil diperbarui.
        </div>
    @endif

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="row g-3">
            <div class="col-md-4">
                <label for="update_password_current_password" class="form-label">Password Saat Ini</label>
                <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
                @if ($errors->updatePassword->get('current_password'))
                    <div class="text-danger small mt-1">{{ $errors->updatePassword->first('current_password') }}</div>
                @endif
            </div>

            <div class="col-md-4">
                <label for="update_password_password" class="form-label">Password Baru</label>
                <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
                @if ($errors->updatePassword->get('password'))
                    <div class="text-danger small mt-1">{{ $errors->updatePassword->first('password') }}</div>
                @endif
            </div>

            <div class="col-md-4">
                <label for="update_password_password_confirmation" class="form-label">Konfirmasi Password</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
                @if ($errors->updatePassword->get('password_confirmation'))
                    <div class="text-danger small mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                @endif
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <button type="submit" class="btn btn-primary">Simpan Password</button>
        </div>
    </form>
</section>
