<section>
    <div class="mb-3">
        <h5 class="mb-1">Hapus Akun</h5>
        <p class="text-muted mb-0">
            Menghapus akun akan menghapus seluruh data Anda secara permanen.
        </p>
    </div>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        Hapus Akun
    </button>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus Akun</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">Masukkan password untuk mengonfirmasi penghapusan akun.</p>
                        <label for="delete_account_password" class="form-label">Password</label>
                        <input
                            id="delete_account_password"
                            name="password"
                            type="password"
                            class="form-control @if($errors->userDeletion->get('password')) is-invalid @endif"
                            placeholder="Password"
                        />
                        @if ($errors->userDeletion->get('password'))
                            <div class="invalid-feedback">{{ $errors->userDeletion->first('password') }}</div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modalEl = document.getElementById('deleteAccountModal');
                if (modalEl) {
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            });
        </script>
    @endif
</section>
