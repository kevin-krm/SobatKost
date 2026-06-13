<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Dashboard Tagihan</h2>
        <p class="text-muted">
            Ringkasan dan analisis tagihan kost.
        </p>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<!-- Statistik Utama -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">Total Tagihan</p>
                        <h4 class="fw-bold mb-0"><?= $statistik['total_tagihan'] ?? 0 ?></h4>
                    </div>
                    <i class="bi bi-receipt text-primary fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">Belum Lunas</p>
                        <h4 class="fw-bold mb-0 text-warning"><?= $tagihanBelumLunas ?></h4>
                    </div>
                    <i class="bi bi-hourglass-split text-warning fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">Overdue</p>
                        <h4 class="fw-bold mb-0 text-danger"><?= $tagihanOverdue ?></h4>
                    </div>
                    <i class="bi bi-exclamation-triangle text-danger fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">Lunas</p>
                        <h4 class="fw-bold mb-0 text-success"><?= $statistik['total_lunas'] ?? 0 ?></h4>
                    </div>
                    <i class="bi bi-check-circle text-success fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Total Penerimaan -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted small mb-1">Total Penerimaan (Tagihan Lunas)</p>
                <h3 class="fw-bold text-success">Rp <?= number_format($statistik['total_penerimaan'] ?? 0, 0, ',', '.') ?></h3>
                <small class="text-muted">
                    Dari <?= $statistik['total_lunas'] ?? 0 ?> tagihan yang telah dilunasi
                </small>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="/SobatKost/index.php?url=tagihan" class="btn btn-outline-primary w-100">
                            <i class="bi bi-list me-2"></i> Lihat Semua Tagihan
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/SobatKost/index.php?url=tagihan/create" class="btn btn-outline-success w-100">
                            <i class="bi bi-plus-circle me-2"></i> Generate Tagihan Baru
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/SobatKost/index.php?url=pembayaran" class="btn btn-outline-info w-100">
                            <i class="bi bi-cash-coin me-2"></i> Kelola Pembayaran
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/SobatKost/index.php?url=tagihan/send-reminders" class="btn btn-outline-warning w-100">
                            <i class="bi bi-bell me-2"></i> Kirim Reminder
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reminder Jatuh Tempo -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Reminder Jatuh Tempo 7 Hari</h5>
                <span class="badge bg-warning text-dark"><?= count($tagihanJatuhTempo ?? []) ?> tagihan</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID TAGIHAN</th>
                                <th>PENYEWA</th>
                                <th>KAMAR</th>
                                <th>TOTAL</th>
                                <th>JATUH TEMPO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tagihanJatuhTempo)): ?>
                                <tr>
                                    <td colspan="5" class="text-center p-4 text-muted">
                                        Tidak ada tagihan yang jatuh tempo dalam 7 hari.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tagihanJatuhTempo as $t): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($t->getIdTagihan()) ?></td>
                                        <td><?= htmlspecialchars($t->getNamaLengkap() ?? '-') ?></td>
                                        <td><?= htmlspecialchars($t->getNomorKamar() ?? '-') ?></td>
                                        <td class="fw-bold">Rp <?= number_format($t->getTotalTagihan(), 0, ',', '.') ?></td>
                                        <td><?= date('d/m/Y', strtotime($t->getTanggalJatuhTempo())) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Info Dashboard -->
<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informasi Penting</h5>
            </div>
            <div class="card-body">
                <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Tagihan Overdue</h6>
                <p class="text-muted small mb-3">
                    Ada <?= $tagihanOverdue ?> tagihan yang telah melewati tanggal jatuh tempo. 
                    Segera hubungi penyewa untuk melakukan pembayaran.
                </p>

                <hr>

                <h6 class="fw-bold mb-2"><i class="bi bi-info-circle text-info me-2"></i>Tips Manajemen</h6>
                <ul class="small text-muted mb-0">
                    <li>Generate tagihan secara berkala sesuai dengan kontrak sewa</li>
                    <li>Verifikasi pembayaran dengan cermat</li>
                    <li>Kirim reminder pembayaran sebelum jatuh tempo</li>
                    <li>Arsipkan dokumentasi pembayaran dengan baik</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Petunjuk Penggunaan</h5>
            </div>
            <div class="card-body">
                <h6 class="fw-bold mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Langkah 1: Generate Tagihan</h6>
                <p class="small text-muted mb-2">
                    Klik "Generate Tagihan Baru" dan pilih kontrak sewa. Sistem akan menghitung otomatis berdasarkan tipe sewa.
                </p>

                <h6 class="fw-bold mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Langkah 2: Verifikasi Pembayaran</h6>
                <p class="small text-muted mb-2">
                    Penyewa akan mengunggah bukti pembayaran. Periksa bukti dan terima atau tolak pembayaran.
                </p>

                <h6 class="fw-bold mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Langkah 3: Catat Pembayaran</h6>
                <p class="small text-muted mb-0">
                    Setelah verifikasi, sistem otomatis akan mengubah status tagihan menjadi Lunas.
                </p>
            </div>
        </div>
    </div>
</div>
