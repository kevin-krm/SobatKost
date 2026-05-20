<script src="/SobatKost/public/js/admin.js"></script>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="bi bi-pencil-square"></i>
                    Edit Kontrak Sewa
                </h4>
                <a href="/SobatKost/index.php?url=kontrak" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>Form Edit Kontrak</strong>
                </div>

                <div class="card-body">
                    <?php if (isset($_SESSION['error'])) : ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $_SESSION['error']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/SobatKost/index.php?url=kontrak/update&id=<?= $kontrak->getIdKontrak() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">ID Kontrak</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($kontrak->getIdKontrak()) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Penyewa</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($kontrak->getNamaLengkap()) ?>" readonly>
                            <input type="hidden" name="id_pengguna" value="<?= htmlspecialchars($kontrak->getIdPengguna()) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Kamar</label>
                            <select name="id_kamar" class="form-select" required>
                                <option value="">-- Pilih Kamar --</option>
                                <?php foreach ($kamarList as $k) : ?>
                                    <?php
                                    $status = $k->getStatusKamar();
                                    $labelStatus = ($status !== 'Tersedia') ? " [$status]" : "";
                                    $selected = ($k->getId() == $kontrak->getIdKamar()) ? 'selected' : '';
                                    ?>
                                    <option value="<?= $k->getId() ?>" <?= $selected ?>>
                                        No: <?= htmlspecialchars($k->getNomorKamar()) ?> - <?= htmlspecialchars($k->getTipeKamar()) ?><?= $labelStatus ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text small text-muted">
                                Anda bisa memilih kamar yang "Terisi" untuk pemesanan periode berikutnya.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Mulai</label>
                            <?php if ($kontrak->getStatusAktif() == 2) : ?>
                                <input type="date" class="form-control bg-light" value="<?= htmlspecialchars($kontrak->getTanggalMulai()) ?>" readonly disabled>
                                <input type="hidden" name="tanggal_mulai" id="tanggal_mulai" value="<?= htmlspecialchars($kontrak->getTanggalMulai()) ?>">
                                <div class="form-text text-warning small">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Kontrak sedang aktif, tanggal mulai tidak dapat diubah.
                                </div>
                            <?php else : ?>
                                <input type="date"
                                       name="tanggal_mulai"
                                       id="tanggal_mulai"
                                       class="form-control"
                                       value="<?= htmlspecialchars($kontrak->getTanggalMulai()) ?>"
                                       min="<?= htmlspecialchars($kontrak->getTanggalMulai()) ?>"
                                       required
                                       onchange="hitungTanggalSelesai()">
                                <div class="form-text text-muted small">Tanggal tidak boleh dimundurkan dari jadwal semula.</div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipe Sewa</label>
                            <select name="tipe_sewa" id="tipe_sewa" class="form-select" required onchange="hitungTanggalSelesai()">
                                <option value="">-- Pilih Tipe Sewa --</option>
                                <option value="Harian" <?= ($kontrak->getTipeSewa() === 'Harian') ? 'selected' : '' ?>>Harian</option>
                                <option value="Bulanan" <?= ($kontrak->getTipeSewa() === 'Bulanan') ? 'selected' : '' ?>>Bulanan</option>
                                <option value="Tahunan" <?= ($kontrak->getTipeSewa() === 'Tahunan') ? 'selected' : '' ?>>Tahunan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="<?= htmlspecialchars($kontrak->getTanggalSelesai()) ?>" readonly>
                            <small class="text-muted">Otomatis dihitung berdasarkan tanggal mulai dan tipe sewa</small>
                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
