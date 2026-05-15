<script src="/SobatKost/public/js/admin.js"></script>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="bi bi-file-earmark-plus"></i>
                    Tambah Kontrak Baru
                </h4>
                <a href="/SobatKost/index.php?url=kontrak" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>Form Data Kontrak</strong>
                </div>

                <div class="card-body">
                    <?php if (isset($_SESSION['error'])) : ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $_SESSION['error']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/SobatKost/index.php?url=kontrak/store">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Kamar</label>
                            <select name="id_kamar" class="form-select" required>
                                <option value="">-- Pilih Kamar --</option>
                                <?php foreach ($kamarList as $k) : ?>
                                    <?php
                                    $status = $k->getStatusKamar();
                                    $labelStatus = ($status !== 'Tersedia') ? " [$status]" : "";
                                    ?>
                                    <option value="<?= $k->getId() ?>">
                                        No: <?= htmlspecialchars($k->getNomorKamar()) ?> - <?= htmlspecialchars($k->getTipeKamar()) ?><?= $labelStatus ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text small text-muted">
                                Anda bisa memilih kamar yang "Terisi" untuk pemesanan periode berikutnya.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Penyewa</label>
                            <select name="id_pengguna" class="form-select" required>
                                <option value="">-- Pilih Penyewa --</option>
                                <?php foreach ($penggunaList as $p) : ?>
                                    <option value="<?= $p['id_pengguna'] ?>">
                                        <?= htmlspecialchars($p['nama_lengkap']) ?> (ID: <?= $p['id_pengguna'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required onchange="hitungTanggalSelesai()">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipe Sewa</label>
                            <select name="tipe_sewa" id="tipe_sewa" class="form-select" required onchange="hitungTanggalSelesai()">
                                <option value="">-- Pilih Tipe Sewa --</option>
                                <option value="Harian">Harian</option>
                                <option value="Bulanan">Bulanan</option>
                                <option value="Tahunan">Tahunan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" readonly>
                            <small class="text-muted">Otomatis dihitung berdasarkan tanggal mulai dan tipe sewa</small>
                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="bi bi-save me-1"></i> Simpan Kontrak
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>