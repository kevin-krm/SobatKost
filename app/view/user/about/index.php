<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">
                <i class="bi bi-person-circle text-primary me-2"></i>Tentang Saya
            </h3>
            <p class="text-muted small mb-0">Lihat informasi akun Anda.</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])) : ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])) : ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-header py-3 text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3 d-flex align-items-center justify-content-center text-primary fw-bold bg-white" style="width: 50px; height: 50px; border-radius: 50%; font-size: 20px;">
                            <?= strtoupper(substr($pengguna->getNamaLengkap(), 0, 2)) ?>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold text-white"><?= htmlspecialchars($pengguna->getNamaLengkap()) ?></h5>
                            <span class="badge bg-light text-primary mt-1"><?= htmlspecialchars($pengguna->getNamaPeran()) ?></span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                        <i class="bi bi-card-text text-primary me-2"></i>Informasi Profil
                    </h5>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">ID PENGGUNA</label>
                        <input type="text" class="form-control bg-light border-0 py-2 rounded-3 text-secondary fw-semibold" value="<?= htmlspecialchars($pengguna->getId()); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">NAMA LENGKAP</label>
                        <input type="text" class="form-control bg-light border-0 py-2 rounded-3 text-secondary" value="<?= htmlspecialchars($pengguna->getNamaLengkap()); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">NOMOR TELEPON</label>
                        <input type="text" class="form-control bg-light border-0 py-2 rounded-3 text-secondary" value="<?= htmlspecialchars($pengguna->getNomorTelepon() ?? '-'); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">EMAIL</label>
                        <input type="text" class="form-control bg-light border-0 py-2 rounded-3 text-secondary" value="<?= htmlspecialchars($pengguna->getEmail()); ?>" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                        <i class="bi bi-shield-lock-fill text-primary me-2"></i>Ubah Password
                    </h5>
                    <p class="text-muted small mb-4">
                        Demi keamanan akun Anda, silakan ganti password default yang diberikan oleh admin saat pertama kali didaftarkan.
                    </p>

                    <form method="POST" action="/SobatKost/index.php?url=user/about/updatePassword">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">Password Saat Ini (Lama)</label>
                            <div class="input-group">
                                <input type="password" name="password_lama" id="passwordLamaField" class="form-control bg-light border-0 py-2 rounded-start-3" required>
                                <button class="btn btn-light border-0 px-3 rounded-end-3" type="button" onclick="togglePasswordVisibility('passwordLamaField', 'toggleLamaIcon')">
                                    <i class="bi bi-eye-slash-fill text-muted" id="toggleLamaIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password_baru" id="passwordBaruField" class="form-control bg-light border-0 py-2 rounded-start-3" required minlength="4">
                                <button class="btn btn-light border-0 px-3 rounded-end-3" type="button" onclick="togglePasswordVisibility('passwordBaruField', 'toggleBaruIcon')">
                                    <i class="bi bi-eye-slash-fill text-muted" id="toggleBaruIcon"></i>
                                </button>
                            </div>
                            <small class="text-muted" style="font-size: 11px;">Password minimal terdiri dari 4 karakter.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-semibold">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="konfirmasi_password" id="konfirmasiPasswordField" class="form-control bg-light border-0 py-2 rounded-start-3" required>
                                <button class="btn btn-light border-0 px-3 rounded-end-3" type="button" onclick="togglePasswordVisibility('konfirmasiPasswordField', 'toggleKonfirmasiIcon')">
                                    <i class="bi bi-eye-slash-fill text-muted" id="toggleKonfirmasiIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 rounded-3 fw-semibold shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan Password Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>