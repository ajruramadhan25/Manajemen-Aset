<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <h4 class="mb-2">Konfirmasi Password</h4>
            <p class="mb-4">Area ini aman. Silakan masukkan password untuk melanjutkan.</p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password" />
                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary d-grid w-100">Konfirmasi</button>
            </form>
        </div>
    </div>
</x-guest-layout>
