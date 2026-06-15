<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Daftar Pembayaran</h2>
        <p class="text-muted">
            Kelola dan verifikasi pembayaran dari penyewa.
        </p>
    </div>
</div>

<!-- Filter Status -->
<div class="mb-3">
    <div class="btn-group" role="group">
        <a href="/SobatKost/index.php?url=pembayaran&status=Proses" class="btn btn-outline-warning <?= ($_GET['status'] ?? 'Proses') === 'Proses' ? 'active' : '' ?>">
            <i class="bi bi-hourglass-split me-2"></i> Proses (<?= $statistik['total_proses'] ?? 0 ?>)
        </a>
        <a href="/SobatKost/index.php?url=pembayaran&status=Berhasil" class="btn btn-outline-success <?= ($_GET['status'] ?? 'Proses') === 'Berhasil' ? 'active' : '' ?>">
            <i class="bi bi-check-circle me-2"></i> Berhasil (<?= $statistik['total_berhasil'] ?? 0 ?>)
        </a>
        <a href="/SobatKost/index.php?url=pembayaran&status=Ditolak" class="btn btn-outline-danger <?= ($_GET['status'] ?? 'Proses') === 'Ditolak' ? 'active' : '' ?>">
            <i class="bi bi-x-circle me-2"></i> Ditolak (<?= $statistik['total_ditolak'] ?? 0 ?>)
        </a>
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

<!-- Tabel Pembayaran -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <div class="mb-3 p-3">
                <input type="text" id="searchPembayaran" class="form-control" placeholder="Cari pembayaran..." onkeyup="searchPembayaran()">
            </div>

            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID PEMBAYARAN</th>
                        <th>TAGIHAN</th>
                        <th>PENYEWA</th>
                        <th>METODE</th>
                        <th>TANGGAL</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">DETAIL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pembayaranList)): ?>
                        <tr>
                            <td colspan="7" class="text-center p-4 text-muted">
                                Belum ada data pembayaran dengan status ini
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pembayaranList as $p): 
                            $status_class = $p->getStatusVerifikasi() === 'Berhasil' ? 'bg-success text-white' : 
                                          ($p->getStatusVerifikasi() === 'Ditolak' ? 'bg-danger text-white' : 'bg-warning text-dark');
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($p->getIdPembayaran()) ?></td>
                                <td>
                                    <a href="/SobatKost/index.php?url=tagihan/detail&id=<?= $p->getIdTagihan() ?>">
                                        <?= htmlspecialchars($p->getIdTagihan()) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($p->getNamaLengkap() ?? '-') ?></td>
                                <td>
                                    <i class="bi <?= $p->getMetodeIcon() ?> me-2"></i>
                                    <?= htmlspecialchars($p->getMetodePembayaran()) ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($p->getTanggalBayar())) ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $status_class ?>">
                                        <?= $p->getStatusVerifikasi() ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="/SobatKost/index.php?url=pembayaran/detail&id=<?= $p->getIdPembayaran() ?>" class="btn btn-sm btn-info">
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

<!-- Pagination -->
<?php if ($totalPage > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="/SobatKost/index.php?url=pembayaran&status=<?= $_GET['status'] ?? 'Proses' ?>&page=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<script>
function searchPembayaran() {
    const input = document.getElementById('searchPembayaran');
    const table = document.querySelector('table tbody');
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input.value.toLowerCase()) ? '' : 'none';
    });
}
</script>
