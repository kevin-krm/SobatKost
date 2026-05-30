<script src="/SobatKost/public/js/admin.js"></script>

<?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success'])) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

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
                    <th>NAMA PENGGUNA</th>
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
                            </td>
                            <td><?= htmlspecialchars($k->getIdKamar()) ?></td>

                            <td>
                                <?= $k->getTanggalMulai() ? date('d M Y', strtotime($k->getTanggalMulai())) : '-' ?>
                            </td>

                            <td>
                                <?php
                                $tglSelesai = $k->getTanggalSelesai();
                                echo (!empty($tglSelesai) && $tglSelesai !== '0000-00-00') ? date('d M Y', strtotime($tglSelesai)) : '-';
                                ?>
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
                                <!-- Logika Tombol Edit -->
                                <?php if ($statusValue == 0) : ?>
                                    <button class="btn btn-sm btn-outline-secondary me-1" disabled title="Kontrak selesai tidak dapat diubah">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                <?php else : ?>
                                    <a href="<?= $editUrl ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit kontrak">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                <?php endif; ?>

                                <!-- Logika Tombol Akhiri Kontrak -->
                                <?php if ($statusValue == 2): ?>
                                    <?php if (empty($k->getTanggalSelesai())): ?>
                                        <button class="btn btn-sm btn-outline-warning me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#terminateModal<?= $k->getIdKontrak() ?>"
                                                title="Akhiri Kontrak">
                                            <i class="bi bi-stop-circle"></i>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline-secondary" disabled
                                                title="Kontrak sudah memiliki tanggal selesai">
                                            <i class="bi bi-stop-circle"></i>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <!-- Logika Tombol Hapus -->
                                <?php if ($statusValue == 1) : ?>
                                    <a href="<?= $deleteUrl ?>" class="btn btn-sm btn-outline-danger" title="Hapus kontrak"
                                       onclick="return confirm('Yakin ingin menghapus kontrak ini?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php else : ?>
                                    <?php
                                    $msgTooltip = ($statusValue == 2) ? 'Kontrak aktif tidak dapat dihapus!' : 'Kontrak selesai tidak dapat dihapus!';
                                    ?>
                                    <button class="btn btn-sm btn-outline-secondary" disabled title="<?= $msgTooltip ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        <!-- Modal Akhiri Kontrak -->
                        <?php if ($statusValue == 2) : ?>
                            <div class="modal fade" id="terminateModal<?= $k->getIdKontrak() ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                                                Akhiri Kontrak - <?= htmlspecialchars($k->getIdKontrak()) ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST" action="/SobatKost/index.php?url=kontrak/terminate&id=<?= $k->getIdKontrak() ?>" onsubmit="return validateTerminateDate('<?= $k->getIdKontrak() ?>', '<?= $k->getTanggalMulai() ?>')">
                                            <div class="modal-body text-start">
                                                <div class="alert alert-info">
                                                    Anda akan mengakhiri kontrak kamar <strong><?= htmlspecialchars($k->getNomorKamar()) ?></strong> untuk penyewa <strong><?= htmlspecialchars($k->getNamaLengkap()) ?></strong>.
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Tanggal Akhir Kontrak</label>
                                                    <?php 
                                                        $today = date('Y-m-d');
                                                        $minDate = ($today > $k->getTanggalMulai()) ? $today : $k->getTanggalMulai();
                                                    ?>
                                                    <input type="date" 
                                                           name="tanggal_selesai" 
                                                           id="terminate_date_<?= $k->getIdKontrak() ?>" 
                                                           class="form-control" 
                                                           min="<?= $minDate ?>" 
                                                           required>
                                                    <div class="form-text small text-muted mt-2">
                                                        <ul>
                                                            <li>Tidak boleh kurang dari tanggal mulai: <strong><?= date('d M Y', strtotime($k->getTanggalMulai())) ?></strong></li>
                                                            <li>Tidak boleh kurang dari tanggal hari ini.</li>
                                                            <li>Harus berada di <strong>hari terakhir</strong> pada bulan yang dipilih (misal: tanggal 28/29/30/31).</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning text-dark fw-bold">Akhiri Kontrak</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

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
<div>
</div>