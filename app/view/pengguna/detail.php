<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">
                        <i class="bi bi-person-fill text-primary me-2"></i>Detail Pengguna
                    </h3>
                    <p class="text-muted small mb-0">Melihat seluruh informasi lengkap data pengguna.</p>
                </div>
                <a href="/SobatKost/pengguna" class="btn btn-outline-secondary btn-sm shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-gradient-primary py-3 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3 d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 50px; height: 50px; background-color: rgba(255, 255, 255, 0.9); border-radius: 50%; font-size: 20px;">
                            <?= strtoupper(substr($pengguna->getNamaLengkap(), 0, 2)) ?>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold"><?= htmlspecialchars($pengguna->getNamaLengkap()) ?></h5>
                            <span class="badge bg-white text-primary mt-1"><?= htmlspecialchars($pengguna->getNamaPeran()) ?></span>
                        </div>
                    </div>
                    <div>
                        <?php
                        $statusAktif = $pengguna->getStatusAktif();
                        $statusBadge = ($statusAktif === 'aktif') ? 'bg-success' : 'bg-danger';
                        $statusLabel = ucfirst($statusAktif);
                        ?>
                        <span class="badge <?= $statusBadge ?> px-3 py-2 fs-6 rounded-pill"><?= $statusLabel ?></span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="bi bi-card-text text-primary me-2"></i>Informasi Akun
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

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">PASSWORD (SENSITIF)</label>
                                <div class="input-group">
                                    <input type="password" id="passwordField" class="form-control bg-light border-0 py-2 rounded-start-3 text-secondary" value="<?= htmlspecialchars($pengguna->getPassword()); ?>" readonly>
                                    <button class="btn btn-light border-0 px-3 rounded-end-3" type="button" id="togglePasswordBtn" onclick="togglePasswordVisibility()">
                                        <i class="bi bi-eye-slash-fill text-muted" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <span class="text-muted small" style="font-size: 11px;">Password terenkripsi (hash). Klik tombol mata untuk menampilkan hash password.</span>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label text-muted small fw-semibold">ROLE / PERAN</label>
                                    <input type="text" class="form-control bg-light border-0 py-2 rounded-3 text-secondary" value="<?= htmlspecialchars($pengguna->getNamaPeran()); ?>" readonly>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label text-muted small fw-semibold">TANGGAL MASUK</label>
                                    <input type="text" class="form-control bg-light border-0 py-2 rounded-3 text-secondary" value="<?= date('d M Y, H:i', strtotime($pengguna->getCreatedAt())); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 d-flex flex-column align-items-center text-center">
                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3 w-100 text-md-start">
                                <i class="bi bi-person-badge-fill text-primary me-2"></i>Foto KTP
                            </h5>
                            <div class="w-100 flex-grow-1 d-flex flex-column align-items-center justify-content-center">
                                <?php if (!empty($pengguna->getFotoKtp())) : ?>
                                    <div class="img-container shadow-sm rounded-4 p-2 bg-white border mb-3 w-100" style="max-width: 320px;">
                                        <img src="/SobatKost/<?= htmlspecialchars($pengguna->getFotoKtp()) ?>" class="img-fluid rounded-3" style="max-height: 220px; object-fit: contain; cursor: pointer;" onclick="openFullscreenKtp()">
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary px-3 rounded-pill" onclick="openFullscreenKtp()">
                                        <i class="bi bi-fullscreen me-1"></i> Perbesar Gambar
                                    </button>
                                <?php else : ?>
                                    <div class="d-flex flex-column align-items-center justify-content-center text-muted p-4 border border-dashed rounded-4 bg-light w-100" style="height: 220px; max-width: 320px; border-style: dashed !important; border-width: 2px !important;">
                                        <i class="bi bi-image-fill fs-1 text-secondary mb-2"></i>
                                        <span class="small fw-semibold">Belum Ada Foto KTP</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light border-top-0 p-4 d-flex justify-content-end align-items-center">
                    <a href="/SobatKost/pengguna" class="btn btn-outline-secondary px-4 me-2 shadow-sm rounded-3">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <a href="/SobatKost/index.php?url=pengguna/edit&id=<?= $pengguna->getId(); ?>" class="btn btn-warning px-4 shadow-sm text-dark rounded-3 fw-semibold">
                        <i class="bi bi-pencil-square me-1"></i>Edit Data
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($pengguna->getFotoKtp())) : ?>
<div class="modal fade" id="fullscreenKtpModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="/SobatKost/<?= htmlspecialchars($pengguna->getFotoKtp()) ?>" class="img-fluid rounded-4 shadow-lg" style="max-height: 85vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
