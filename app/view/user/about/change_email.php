<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">
                <i class="bi bi-envelope-fill text-primary me-2"></i>Ubah E-mail
            </h3>
            <p class="text-muted small mb-0">Ubah alamat e-mail untuk akun SobatKost Anda.</p>
        </div>
        <a href="/SobatKost/index.php?url=user/about" class="btn btn-outline-secondary btn-sm rounded-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (isset($_SESSION['error'])) : ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header py-3 text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
                    <h5 class="mb-0 fw-semibold text-white">Form Ubah E-mail</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="/SobatKost/index.php?url=user/about/updateEmail">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">E-MAIL SAAT INI</label>
                            <input type="text" class="form-control bg-light border-0 py-2 rounded-3 text-secondary" value="<?= htmlspecialchars($pengguna->getEmail()); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">E-MAIL BARU</label>
                            <input type="email" name="email_baru" class="form-control bg-light border-0 py-2 rounded-3 text-dark" placeholder="Masukkan alamat e-mail baru" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-semibold">PASSWORD SAAT INI</label>
                            <input type="password" name="password" class="form-control bg-light border-0 py-2 rounded-3 text-dark" placeholder="Konfirmasi password Anda" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 rounded-3 fw-semibold shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> Simpan E-mail Baru
                            </button>
                            <a href="/SobatKost/index.php?url=user/about" class="btn btn-light py-2 rounded-3 fw-semibold text-secondary">
                                Batalkan
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
