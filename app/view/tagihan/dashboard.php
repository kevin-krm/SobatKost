<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Dashboard Tagihan</h2>
        <p class="text-muted">
            Ringkasan dan analisis tagihan kost.
        </p>
    </div>
</div>

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

<!-- Aksi Cepat -->
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
                        <a href="/SobatKost/index.php?url=pengumuman/create" class="btn btn-outline-warning w-100">
                            <i class="bi bi-megaphone me-2"></i> Buat Pengumuman
                        </a>
                    </div>
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
