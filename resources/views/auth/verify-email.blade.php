<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <h4 class="mb-2">Verifikasi Email</h4>
            <p class="mb-4">PT. Azkayra Group - Kami sudah mengirim tautan verifikasi ke email Anda.</p>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success" role="alert">
                    Tautan verifikasi baru sudah dikirim ke email Anda.
                </div>
            @endif

            <p class="text-muted">
                Silakan klik tautan verifikasi di email untuk melanjutkan. Jika belum menerima email, kirim ulang di bawah ini.
            </p>

            <div class="d-flex flex-wrap gap-2 mt-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
