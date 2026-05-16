<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Detail Pembayaran</h2>
        <p class="text-muted">
            Informasi lengkap pembayaran dan verifikasi.
        </p>
    </div>
    <a href="/SobatKost/index.php?url=pembayaran" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<!-- Flash Messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <?= $_SESSION['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <!-- Detail Pembayaran -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informasi Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">ID Pembayaran</p>
                        <h6 class="fw-bold"><?= htmlspecialchars($pembayaran->getIdPembayaran()) ?></h6>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">ID Tagihan</p>
                        <h6 class="fw-bold">
                            <a href="/SobatKost/index.php?url=tagihan/detail&id=<?= $pembayaran->getIdTagihan() ?>">
                                <?= htmlspecialchars($pembayaran->getIdTagihan()) ?>
                            </a>
                        </h6>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Metode Pembayaran</p>
                        <h6 class="fw-bold">
                            <i class="bi <?= $pembayaran->getMetodeIcon() ?> me-2"></i>
                            <?= htmlspecialchars($pembayaran->getMetodePembayaran()) ?>
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Tanggal Pembayaran</p>
                        <h6 class="fw-bold"><?= date('d/m/Y H:i:s', strtotime($pembayaran->getTanggalBayar())) ?></h6>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <p class="text-muted small mb-1">Status Verifikasi</p>
                        <h6 class="fw-bold">
                            <span class="badge <?= $pembayaran->getStatusBadge() ?>" style="font-size: 14px; padding: 8px 12px;">
                                <?= $pembayaran->getStatusVerifikasi() ?>
                            </span>
                        </h6>
                    </div>
                </div>

                <?php if ($pembayaran->getBuktiPembayaran()): ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted small mb-2">Bukti Pembayaran</p>
                            <div>
                                <?php 
                                    $ext = strtolower(pathinfo($pembayaran->getBuktiPembayaran(), PATHINFO_EXTENSION));
                                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])):
                                ?>
                                    <img src="<?= htmlspecialchars($pembayaran->getBuktiPembayaran()) ?>" class="img-fluid" style="max-width: 100%; max-height: 400px;">
                                <?php else: ?>
                                    <a href="<?= htmlspecialchars($pembayaran->getBuktiPembayaran()) ?>" target="_blank" class="btn btn-info">
                                        <i class="bi bi-file-earmark-pdf me-2"></i> Lihat Dokumen
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Detail Tagihan -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informasi Tagihan Terkait</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Biaya Sewa</p>
                        <h6 class="fw-bold">Rp <?= number_format($tagihan->getTotalBiayaSewa(), 0, ',', '.') ?></h6>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Biaya Tambahan</p>
                        <h6 class="fw-bold">Rp <?= number_format($tagihan->getBiayaTambahan(), 0, ',', '.') ?></h6>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Total Tagihan</p>
                        <h6 class="fw-bold text-primary">Rp <?= number_format($tagihan->getTotalTagihan(), 0, ',', '.') ?></h6>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Status Tagihan</p>
                        <h6 class="fw-bold">
                            <span class="badge <?= $tagihan->getStatusTagihan() === 'Lunas' ? 'bg-success text-white' : 'bg-warning text-dark' ?>">
                                <?= $tagihan->getStatusTagihan() ?>
                            </span>
                        </h6>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <p class="text-muted small mb-1">Tanggal Jatuh Tempo</p>
                        <h6 class="fw-bold"><?= date('d/m/Y', strtotime($tagihan->getTanggalJatuhTempo())) ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Aksi Verifikasi (Admin) -->
        <?php if ($_SESSION['user']['role'] === 'Admin' && $pembayaran->getStatusVerifikasi() === 'Proses'): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Aksi Verifikasi</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/SobatKost/index.php?url=pembayaran/verify&id=<?= $pembayaran->getIdPembayaran() ?>&status=Berhasil" class="btn btn-success" onclick="return confirm('Verifikasi pembayaran ini sebagai Berhasil?')">
                            <i class="bi bi-check-circle me-2"></i> Terima Pembayaran
                        </a>
                        <a href="/SobatKost/index.php?url=pembayaran/verify&id=<?= $pembayaran->getIdPembayaran() ?>&status=Ditolak" class="btn btn-danger" onclick="return confirm('Tolak pembayaran ini?')">
                            <i class="bi bi-x-circle me-2"></i> Tolak Pembayaran
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Timeline Status -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker <?= $pembayaran->getStatusVerifikasi() !== 'Proses' && $pembayaran->getStatusVerifikasi() !== 'Ditolak' ? 'completed' : '' ?>">
                            <i class="bi bi-arrow-up"></i>
                        </div>
                        <div class="timeline-content">
                            <p class="text-muted small">Upload Pembayaran</p>
                            <p class="fw-bold"><?= date('d/m/Y H:i', strtotime($pembayaran->getTanggalBayar())) ?></p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-marker <?= $pembayaran->getStatusVerifikasi() !== 'Proses' ? 'completed' : '' ?>">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="timeline-content">
                            <p class="text-muted small">
                                <?php 
                                    if ($pembayaran->getStatusVerifikasi() === 'Proses') {
                                        echo 'Menunggu Verifikasi Admin';
                                    } elseif ($pembayaran->getStatusVerifikasi() === 'Berhasil') {
                                        echo 'Pembayaran Terverifikasi';
                                    } else {
                                        echo 'Pembayaran Ditolak';
                                    }
                                ?>
                            </p>
                            <p class="fw-bold"><?= date('d/m/Y H:i', strtotime($pembayaran->getUpdatedAt())) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
}

.timeline-item {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
    position: relative;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 9px;
    top: 40px;
    bottom: -20px;
    width: 2px;
    background: #dee2e6;
}

.timeline-marker {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    flex-shrink: 0;
    z-index: 1;
}

.timeline-marker.completed {
    background: #198754;
    color: white;
}

.timeline-content p {
    margin: 0;
}
</style>
