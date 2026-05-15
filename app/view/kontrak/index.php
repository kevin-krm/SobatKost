<script src="/SobatKost/public/js/admin.js"></script>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Data Kontrak</h2>
        <p class="text-muted">
            Kelola data kontrak penyewaan kamar kost secara terpusat.
        </p>
    </div>
    <a href="/SobatKost/index.php?url=kontrak/create" class="btn btn-primary shadow-sm">
        <i class="bi bi-file-earmark-plus-fill me-2"></i>
        Tambah Kontrak
    </a>
</div>

<p class="text-muted small">
    Total kontrak: <?= $totalData ?>
</p>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <div class="mb-3 p-3 pb-0">
                <input
                    type="text"
                    id="searchKontrak"
                    class="form-control"
                    placeholder="Cari kontrak..."
                    onkeyup="searchKontrak()"
                >
            </div>

            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID KONTRAK</th>
                    <th>ID PENGGUNA</th>
                    <th>ID KAMAR</th>
                    <th>TANGGAL MULAI</th>
                    <th>TANGGAL SELESAI</th>
                    <th>TIPE SEWA</th>
                    <th>STATUS</th>
                    <th class="text-center">AKSI</th>
                </tr>
                </thead>

                <tbody>
                <?php if (empty($kontrakList)) : ?>
                    <tr>
                        <td colspan="8" class="text-center p-4 text-muted">
                            Belum ada data kontrak
                        </td>
                    </tr>

                <?php else : ?>
                    <?php foreach ($kontrakList as $k) : ?>
                        <?php
                        $statusValue = $k->getStatusAktif();
                        $statusText = '';
                        $statusBadge = '';

                        switch ($statusValue) {
                            case 1:
                                $statusText = 'Valid (Menunggu)';
                                $statusBadge = 'bg-warning text-dark';
                                break;
                            case 2:
                                $statusText = 'Aktif';
                                $statusBadge = 'bg-success';
                                break;
                            case 0:
                            default:
                                $statusText = 'Selesai/Nonaktif';
                                $statusBadge = 'bg-danger';
                                break;
                        }

                        $editUrl = "/SobatKost/index.php?url=kontrak/edit&id=" . $k->getIdKontrak();
                        $deleteUrl = "/SobatKost/index.php?url=kontrak/delete&id=" . $k->getIdKontrak();
                        ?>

                        <tr>
                            <td><?= htmlspecialchars($k->getIdKontrak()) ?></td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($k->getNamaLengkap()) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($k->getIdPengguna()) ?></small>
                            </td>
                            <td><?= htmlspecialchars($k->getIdKamar()) ?></td>

                            <td>
                                <?= $k->getTanggalMulai() ? date('d M Y', strtotime($k->getTanggalMulai())) : '-' ?>
                            </td>

                            <td>
                                <?= $k->getTanggalSelesai() ? date('d M Y', strtotime($k->getTanggalSelesai())) : '-' ?>
                            </td>

                            <td class="text-center">
                                <?php
                                $badgeTipe = 'bg-secondary';
                                if ($k->getTipeSewa() === 'Harian') {
                                    $badgeTipe = 'bg-info text-dark';
                                } elseif ($k->getTipeSewa() === 'Bulanan') {
                                    $badgeTipe = 'bg-primary';
                                } elseif ($k->getTipeSewa() === 'Tahunan') {
                                    $badgeTipe = 'bg-dark';
                                }
                                ?>
                                <span class="badge <?= $badgeTipe ?>">
                                    <?= htmlspecialchars($k->getTipeSewa()) ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="badge <?= $statusBadge ?>">
                                    <?= $statusText ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <a href="<?= $editUrl ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit kontrak">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= $deleteUrl ?>" class="btn btn-sm btn-outline-danger" title="Hapus kontrak"
                                   onclick="return confirm('Yakin ingin menghapus kontrak ini?');">
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
                            <a
                                class="page-link"
                                href="/SobatKost/index.php?url=kontrak&page=<?= $i ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>