<?php
$activeTab = (isset($_GET['tab']) && $_GET['tab'] === 'pengeluaran') ? 'pengeluaran' : 'pemasukan';
$filterQuery = http_build_query([
    'filter_type' => $filterType,
    'bulan' => $bulanFilter,
    'tahun' => $tahunFilter
]);
$filterQueryPemasukan = $filterQuery . '&tab=pemasukan';
$filterQueryPengeluaran = $filterQuery . '&tab=pengeluaran';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Laporan Keuangan</h2>
        <p class="text-muted">Pantau pemasukan, pengeluaran, dan profit kost.</p>
    </div>
    <a href="/SobatKost/index.php?url=keuangan/create" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-2"></i> Tambah Pengeluaran
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="/SobatKost/index.php" method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="url" value="keuangan">
            <input type="hidden" name="tab" value="<?= htmlspecialchars($activeTab) ?>">
            <div class="col-md-3">
                <label for="filter_type" class="form-label fw-semibold">Filter Periode</label>
                <select name="filter_type" id="filter_type" class="form-select" onchange="toggleFilterInputs()">
                    <option value="all" <?= $filterType === 'all' ? 'selected' : '' ?>>Keseluruhan</option>
                    <option value="year" <?= $filterType === 'year' ? 'selected' : '' ?>>Per Tahun</option>
                    <option value="month" <?= $filterType === 'month' ? 'selected' : '' ?>>Per Bulan</option>
                </select>
            </div>
            <div class="col-md-3" id="tahun_filter_group">
                <label for="tahun" class="form-label fw-semibold">Tahun</label>
                <input type="number" name="tahun" id="tahun" class="form-control" value="<?= htmlspecialchars($tahunFilter) ?>" min="2000" max="2100">
            </div>
            <div class="col-md-3" id="bulan_filter_group">
                <label for="bulan" class="form-label fw-semibold">Bulan</label>
                <input type="month" name="bulan" id="bulan" class="form-control" value="<?= htmlspecialchars($bulanFilter) ?>">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-2"></i> Terapkan
                </button>
            </div>
            <div class="col-md-auto">
                <a href="/SobatKost/index.php?url=keuangan&filter_type=all" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-2"></i> Reset
                </a>
            </div>
            <div class="col-md text-md-end">
                <span class="text-muted small">Ringkasan periode: <?= htmlspecialchars($labelPeriode) ?></span>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Total Pendapatan</p>
                        <h4 class="fw-bold mb-0 text-success">Rp <?= number_format($ringkasan['total_pemasukan'] ?? 0, 0, ',', '.') ?></h4>
                    </div>
                    <i class="bi bi-arrow-down-circle text-success fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Total Pengeluaran</p>
                        <h4 class="fw-bold mb-0 text-danger">Rp <?= number_format($ringkasan['total_pengeluaran'] ?? 0, 0, ',', '.') ?></h4>
                    </div>
                    <i class="bi bi-arrow-up-circle text-danger fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php $profitClass = (($ringkasan['total_profit'] ?? 0) < 0) ? 'text-danger' : 'text-primary'; ?>
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Total Profit</p>
                        <h4 class="fw-bold mb-0 <?= $profitClass ?>">Rp <?= number_format($ringkasan['total_profit'] ?? 0, 0, ',', '.') ?></h4>
                    </div>
                    <i class="bi bi-graph-up-arrow <?= $profitClass ?> fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs border-bottom mb-4">
    <li class="nav-item">
        <a class="nav-link fw-semibold <?= $activeTab === 'pemasukan' ? 'active text-success' : 'text-muted' ?>" href="/SobatKost/index.php?url=keuangan&<?= htmlspecialchars($filterQueryPemasukan) ?>">
            Pendapatan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-semibold <?= $activeTab === 'pengeluaran' ? 'active text-danger' : 'text-muted' ?>" href="/SobatKost/index.php?url=keuangan&<?= htmlspecialchars($filterQueryPengeluaran) ?>">
            Pengeluaran
        </a>
    </li>
</ul>

<?php if ($activeTab === 'pemasukan') : ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-1 text-success">Daftar Pendapatan</h5>
                    <p class="text-muted small mb-0">Menampilkan <?= count($pemasukanList) ?> dari <?= $totalPemasukanData ?> data pada <?= htmlspecialchars($labelPeriode) ?></p>
                </div>
                <i class="bi bi-arrow-down-circle text-success fs-4"></i>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah</th>
                        <th>Metode Pembayaran</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($pemasukanList)) : ?>
                        <tr>
                            <td colspan="7" class="text-center p-4 text-muted">
                                Belum ada pemasukan pada periode ini
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($pemasukanList as $index => $pemasukan) : ?>
                            <?php $jumlahPemasukan = ($pemasukan['total_biaya_sewa'] ?? 0) + ($pemasukan['biaya_tambahan'] ?? 0); ?>
                            <tr>
                                <td><?= $offsetPemasukan + $index + 1 ?></td>
                                <td><?= date('d/m/Y', strtotime($pemasukan['tanggal_bayar'])) ?></td>
                                <td>Tagihan Kost</td>
                                <td>
                                    <div><?= htmlspecialchars($pemasukan['nama_lengkap'] ?? '-') ?> - Kamar <?= htmlspecialchars($pemasukan['nomor_kamar'] ?? '-') ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($pemasukan['id_tagihan']) ?></div>
                                </td>
                                <td class="fw-bold text-success">Rp <?= number_format($jumlahPemasukan, 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($pemasukan['metode_pembayaran']) ?></td>
                                <td class="text-center">
                                    <a href="/SobatKost/index.php?url=pembayaran/detail&id=<?= urlencode($pemasukan['id_pembayaran']) ?>" class="btn btn-sm btn-outline-primary" title="Lihat Pembayaran">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPagePemasukan > 1) : ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-end mb-0">
                        <?php for ($i = 1; $i <= $totalPagePemasukan; $i++) : ?>
                            <li class="page-item <?= ($pagePemasukan == $i) ? 'active' : '' ?>">
                                <a class="page-link" href="/SobatKost/index.php?url=keuangan&page_pemasukan=<?= $i ?>&page_pengeluaran=<?= $pagePengeluaran ?>&<?= htmlspecialchars($filterQueryPemasukan) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
<?php else : ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-1 text-danger">Daftar Pengeluaran</h5>
                    <p class="text-muted small mb-0">Menampilkan <?= count($biayaList) ?> dari <?= $totalData ?> data pada <?= htmlspecialchars($labelPeriode) ?></p>
                </div>
                <i class="bi bi-arrow-up-circle text-danger fs-4"></i>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah</th>
                        <th class="text-center">Aksi</th>
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
                        <?php foreach ($biayaList as $index => $biaya) : ?>
                            <tr>
                                <td><?= $offsetPengeluaran + $index + 1 ?></td>
                                <td><?= date('d/m/Y', strtotime($biaya->getTanggalPengeluaran())) ?></td>
                                <td><?= htmlspecialchars($biaya->getKategoriBiaya()) ?></td>
                                <td>
                                    <div><?= htmlspecialchars($biaya->getKeterangan() ?: '-') ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($biaya->getIdBiaya()) ?></div>
                                </td>
                                <td class="fw-bold text-danger">Rp <?= number_format($biaya->getJumlahBiaya(), 0, ',', '.') ?></td>
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
            </div>

            <?php if ($totalPagePengeluaran > 1) : ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-end mb-0">
                        <?php for ($i = 1; $i <= $totalPagePengeluaran; $i++) : ?>
                            <li class="page-item <?= ($pagePengeluaran == $i) ? 'active' : '' ?>">
                                <a class="page-link" href="/SobatKost/index.php?url=keuangan&page_pemasukan=<?= $pagePemasukan ?>&page_pengeluaran=<?= $i ?>&<?= htmlspecialchars($filterQueryPengeluaran) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<script>
function toggleFilterInputs() {
    const filterType = document.getElementById('filter_type').value;
    document.getElementById('tahun_filter_group').style.display = filterType === 'year' ? '' : 'none';
    document.getElementById('bulan_filter_group').style.display = filterType === 'month' ? '' : 'none';
}

toggleFilterInputs();
</script>
