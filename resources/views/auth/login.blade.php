<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
                <a href="{{ url('/') }}" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="width: 32px; height: 32px;">
                    </span>
                    <span class="app-brand-text demo text-body fw-bolder">{{ config('app.name', 'Manajemen Aset') }}</span>
                </a>
            </div>
            <!-- /Logo -->

            <h4 class="mb-2">Sistem Manajemen Aset</h4>
            <p class="mb-4">PT. Azkayra Group - Silakan masuk untuk mengelola aset secara terpusat.</p>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan email"
                        autofocus value="{{ old('email') }}" required />
                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                <small>Lupa Password?</small>
                            </a>
                        @endif
                    </div>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control" name="password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password" />
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                        <label class="form-check-label" for="remember-me"> Ingat Saya </label>
                    </div>
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
                </div>
            </form>

            <p class="text-center">
                <span>Belum punya akun?</span>
                <a href="{{ route('register') }}">
                    <span>Buat akun</span>
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>