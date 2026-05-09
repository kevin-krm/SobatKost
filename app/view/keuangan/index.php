<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Laporan Keuangan</h2>
        <p class="text-muted">Kelola data biaya operasional kost.</p>
    </div>
    <a href="/SobatKost/index.php?url=keuangan/create" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-2"></i> Tambah Pengeluaran
    </a>
</div>

<p class="text-muted small">Total pengeluaran tercatat: <?= $totalData ?></p>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID BIAYA</th>
                    <th>KATEGORI</th>
                    <th>JUMLAH (Rp)</th>
                    <th>TANGGAL</th>
                    <th>KETERANGAN</th>
                    <th class="text-center">AKSI</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($biayaList)) : ?>
                    <tr>
                        <td colspan="6" class="text-center p-4 text-muted">
                            Belum ada data biaya operasional
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($biayaList as $biaya) : ?>
                        <tr>
                            <td><?= htmlspecialchars($biaya->getIdBiaya()) ?></td>
                            <td><?= htmlspecialchars($biaya->getKategoriBiaya()) ?></td>
                            <td><?= number_format($biaya->getJumlahBiaya(), 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($biaya->getTanggalPengeluaran()) ?></td>
                            <td><?= htmlspecialchars($biaya->getKeterangan()) ?></td>
                            <td class="text-center">
                                <a href="/SobatKost/index.php?url=keuangan/edit&id=<?= $biaya->getIdBiaya() ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit Biaya">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="/SobatKost/index.php?url=keuangan/delete&id=<?= $biaya->getIdBiaya() ?>" class="btn btn-sm btn-outline-danger" title="Hapus Biaya" onclick="return confirm('Yakin ingin menghapus data biaya ini?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>

            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="/SobatKost/index.php?url=keuangan&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
