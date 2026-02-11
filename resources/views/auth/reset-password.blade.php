<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <h4 class="mb-2">Reset Password</h4>
            <p class="mb-4">Masukkan password baru untuk akun Anda.</p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-control"
                        value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru</label>
                    <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password" />
                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password" />
                    @error('password_confirmation') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary d-grid w-100">
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
