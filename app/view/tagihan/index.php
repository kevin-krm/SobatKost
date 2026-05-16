<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Daftar Tagihan</h2>
        <p class="text-muted">
            Kelola dan generate tagihan kost Anda.
        </p>
    </div>

    <a href="/SobatKost/index.php?url=tagihan/create" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-2"></i>
        Generate Tagihan
    </a>
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
                        <p class="text-muted small mb-0">Overdue</p>
                        <h4 class="fw-bold mb-0 text-danger"><?= $statistik['total_overdue'] ?? 0 ?></h4>
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
                        <p class="text-muted small mb-0">Total Penerimaan</p>
                        <h5 class="fw-bold mb-0 text-success">Rp <?= number_format($statistik['total_penerimaan'] ?? 0, 0, ',', '.') ?></h5>
                    </div>
                    <i class="bi bi-check-circle text-success fs-3"></i>
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
                        <th>PENYEWA</th>
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
                            <td colspan="8" class="text-center p-4 text-muted">
                                Belum ada data tagihan
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tagihanList as $t): 
                            $status_class = $t->getStatusTagihan() === 'Lunas' ? 'bg-success text-white' : 'bg-warning text-dark';
                            $is_overdue = $t->isOverdue() ? 'text-danger fw-bold' : '';
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($t->getIdTagihan()) ?></td>
                                <td><?= htmlspecialchars($t->getNamaLengkap() ?? '-') ?></td>
                                <td><?= htmlspecialchars($t->getNomorKamar() ?? '-') ?></td>
                                <td><?= htmlspecialchars($t->getTipeSewa() ?? '-') ?></td>
                                <td class="fw-bold">Rp <?= number_format($t->getTotalTagihan(), 0, ',', '.') ?></td>
                                <td class="<?= $is_overdue ?>"><?= date('d/m/Y', strtotime($t->getTanggalJatuhTempo())) ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $status_class ?>">
                                        <?= $t->getStatusTagihan() ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="/SobatKost/index.php?url=tagihan/detail&id=<?= $t->getIdTagihan() ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="/SobatKost/index.php?url=tagihan/edit&id=<?= $t->getIdTagihan() ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/SobatKost/index.php?url=tagihan/delete&id=<?= $t->getIdTagihan() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus tagihan?')">
                                        <i class="bi bi-trash"></i>
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
                    <a class="page-link" href="/SobatKost/index.php?url=tagihan&page=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<script>
function searchTagihan() {
    const input = document.getElementById('searchTagihan');
    const table = document.querySelector('table tbody');
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input.value.toLowerCase()) ? '' : 'none';
    });
}
</script>
