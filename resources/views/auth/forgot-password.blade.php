<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <h4 class="mb-2">Lupa Password</h4>
            <p class="mb-4">Masukkan email akun Anda, kami akan mengirim tautan untuk reset password.</p>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus />
                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary d-grid w-100">
                    Kirim Tautan Reset Password
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
