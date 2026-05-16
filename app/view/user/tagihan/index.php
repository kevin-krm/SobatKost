<?php
require_once APP_PATH . '/dao/KontraKDao.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Tagihan Saya</h2>
        <p class="text-muted">
            Lihat dan kelola tagihan sewa Anda.
        </p>
    </div>
</div>

<!-- Statistik Ringkas -->
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
                        <h4 class="fw-bold mb-0 text-warning"><?= $statistik['total_belum_lunas'] ?? 0 ?></h4>
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
                        <p class="text-muted small mb-0">Sudah Lunas</p>
                        <h4 class="fw-bold mb-0 text-success"><?= $statistik['total_lunas'] ?? 0 ?></h4>
                    </div>
                    <i class="bi bi-check-circle text-success fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">Total Terhutang</p>
                        <h5 class="fw-bold mb-0 text-danger">Rp <?= number_format($statistik['total_terhutang'] ?? 0, 0, ',', '.') ?></h5>
                    </div>
                    <i class="bi bi-cash-coin text-danger fs-3"></i>
                </div>
            </div>
        </div>
    </div>
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

<!-- Tabel Tagihan -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <div class="mb-3 p-3">
                <input type="text" id="searchTagihan" class="form-control" placeholder="Cari tagihan..." onkeyup="searchTagihan()">
            </div>

            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID TAGIHAN</th>
                        <th>KAMAR</th>
                        <th>TIPE SEWA</th>
                        <th>TOTAL</th>
                        <th>JATUH TEMPO</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tagihanList)): ?>
                        <tr>
                            <td colspan="7" class="text-center p-4 text-muted">
                                Belum ada data tagihan
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tagihanList as $t): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($t->getIdTagihan()) ?></strong>
                                </td>
                                <td>
                                    <?php
                                    // Get kontrak info
                                    $kontraKDao = new KontraKDao();
                                    $kontrak = $kontraKDao->getKontrakById($t->getIdKontrak());
                                    if ($kontrak) {
                                        echo htmlspecialchars($kontrak->getIdKamar());
                                    }
                                    ?>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php
                                        $kontraKDao = new KontraKDao();
                                        $kontrak = $kontraKDao->getKontrakById($t->getIdKontrak());
                                        echo htmlspecialchars($kontrak ? $kontrak->getTipeSewa() : '-');
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    Rp <?= number_format($t->getTotalTagihan(), 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?= date('d/m/Y', strtotime($t->getTanggalJatuhTempo())) ?>
                                    <?php if ($t->isOverdue()): ?>
                                        <span class="badge bg-danger ms-2">OVERDUE</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($t->getStatusTagihan() === 'Lunas'): ?>
                                        <span class="badge bg-success">LUNAS</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">BELUM LUNAS</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="/SobatKost/index.php?url=user/tagihan/detail&id=<?= urlencode($t->getIdTagihan()) ?>" 
                                       class="btn btn-sm btn-primary" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function searchTagihan() {
    const input = document.getElementById("searchTagihan").value.toUpperCase();
    const table = document.querySelector("table tbody");
    const rows = table.querySelectorAll("tr");

    rows.forEach(row => {
        const text = row.innerText.toUpperCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}
</script>
