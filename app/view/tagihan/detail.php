<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Detail Tagihan</h2>
        <p class="text-muted">
            Informasi lengkap tagihan dan riwayat pembayaran.
        </p>
    </div>
    <a href="/SobatKost/index.php?url=tagihan" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<!-- Informasi Tagihan -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informasi Tagihan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">ID Tagihan</p>
                        <h6 class="fw-bold"><?= htmlspecialchars($tagihan->getIdTagihan()) ?></h6>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">ID Kontrak</p>
                        <h6 class="fw-bold"><?= htmlspecialchars($tagihan->getIdKontrak()) ?></h6>
                    </div>
                </div>

                <hr>

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
                        <p class="text-muted small mb-1">Tanggal Jatuh Tempo</p>
                        <h6 class="fw-bold"><?= date('d/m/Y', strtotime($tagihan->getTanggalJatuhTempo())) ?></h6>
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
                        <p class="text-muted small mb-1">Total Tagihan</p>
                        <h4 class="fw-bold text-primary">Rp <?= number_format($tagihan->getTotalTagihan(), 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <?php if ($tagihan->getStatusTagihan() === 'Belum Lunas'): ?>
                    <a href="/SobatKost/index.php?url=pembayaran/upload&id=<?= $tagihan->getIdTagihan() ?>" class="btn btn-success w-100 mb-2">
                        <i class="bi bi-upload me-2"></i> Upload Pembayaran
                    </a>
                <?php endif; ?>

                <a href="/SobatKost/index.php?url=tagihan/edit&id=<?= $tagihan->getIdTagihan() ?>" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil me-2"></i> Edit
                </a>

                <a href="/SobatKost/index.php?url=tagihan/delete&id=<?= $tagihan->getIdTagihan() ?>" class="btn btn-danger w-100" onclick="return confirm('Hapus tagihan?')">
                    <i class="bi bi-trash me-2"></i> Hapus
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informasi Lainnya</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-1">Dibuat Pada</p>
                <p class="mb-3"><?= date('d/m/Y H:i', strtotime($tagihan->getCreatedAt())) ?></p>

                <p class="text-muted small mb-1">Diperbarui Pada</p>
                <p><?= date('d/m/Y H:i', strtotime($tagihan->getUpdatedAt())) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Pembayaran -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">Riwayat Pembayaran</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID PEMBAYARAN</th>
                        <th>METODE</th>
                        <th>TANGGAL</th>
                        <th>BUKTI</th>
                        <th class="text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pembayaranList)): ?>
                        <tr>
                            <td colspan="5" class="text-center p-4 text-muted">
                                Belum ada riwayat pembayaran
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pembayaranList as $p): ?>
                            <tr>
                                <td>
                                    <a href="/SobatKost/index.php?url=pembayaran/detail&id=<?= $p->getIdPembayaran() ?>">
                                        <?= htmlspecialchars($p->getIdPembayaran()) ?>
                                    </a>
                                </td>
                                <td>
                                    <i class="bi <?= $p->getMetodeIcon() ?> me-2"></i>
                                    <?= htmlspecialchars($p->getMetodePembayaran()) ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($p->getTanggalBayar())) ?></td>
                                <td>
                                    <?php if ($p->getBuktiPembayaran()): ?>
                                        <a href="<?= htmlspecialchars($p->getBuktiPembayaran()) ?>" target="_blank" class="btn btn-sm btn-info">
                                            <i class="bi bi-file-earmark"></i> Lihat
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $p->getStatusBadge() ?>">
                                        <?= $p->getStatusVerifikasi() ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
