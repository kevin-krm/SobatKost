<script src="/SobatKost/public/js/admin.js"></script>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Manajemen Inventaris</h2>
        <p class="text-muted">Kelola fasilitas dan aset di dalam setiap kamar.</p>
    </div>
    <a href="/SobatKost/index.php?url=inventaris/create" class="btn btn-primary shadow-sm">
        <i class="bi bi-box-seam me-2"></i> Tambah Barang
    </a>
</div>

<p class="text-muted small">Total inventaris: <?= $totalData ?></p>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">

            <div class="mb-3">
                <input
                        type="text"
                        id="searchInventariss"
                        class="form-control"
                        placeholder="Cari inventaris..."
                        onkeyup="searchInventaris()"
                >
            </div>

            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID BARANG</th>
                    <th>ID KAMAR</th>
                    <th>NAMA BARANG</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center">AKSI LAINNYA</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($inventarisList)) : ?>
                    <tr>
                        <td colspan="5" class="text-center p-4 text-muted">
                            Belum ada data inventaris kamar
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($inventarisList as $inv) : ?>
                        <?php
                        $kondisi = $inv->getKondisiBarang();

                        if ($kondisi == 'Bagus') {
                            $badge = 'bg-success text-white';
                        } elseif ($kondisi == 'Rusak Ringan') {
                            $badge = 'bg-warning text-dark';
                        } else {
                            $badge = 'bg-danger text-white';
                        }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($inv->getIdInventaris()) ?></td>
                            <td><strong><?= htmlspecialchars($inv->getIdKamar()) ?></strong></td>
                            <td><?= htmlspecialchars($inv->getNamaBarang()) ?></td>

                            <td class="text-center">
                                <form action="/SobatKost/index.php?url=inventaris/updateStatus&id=<?= $inv->getIdInventaris() ?>" method="POST" class="d-flex justify-content-center align-items-center gap-2 m-0">

                                    <select
                                            name="kondisi_barang"
                                            class="form-select form-select-sm <?= $badge ?>"
                                            style="width: 150px; font-weight: 500;"
                                            onchange="this.className='form-select form-select-sm ' +
                                            (this.value == 'Bagus' ? 'bg-success text-white' :
                                            (this.value == 'Rusak Ringan' ? 'bg-warning text-dark' :
                                            'bg-danger text-white'))"
                                    >
                                        <option value="Bagus" <?= $kondisi == 'Bagus' ? 'selected' : '' ?>>
                                            Bagus
                                        </option>
                                        <option value="Rusak Ringan" <?= $kondisi == 'Rusak Ringan' ? 'selected' : '' ?>>
                                            Rusak Ringan
                                        </option>
                                        <option value="Rusak Berat" <?= $kondisi == 'Rusak Berat' ? 'selected' : '' ?>>
                                            Rusak Berat
                                        </option>
                                    </select>

                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Update
                                    </button>
                                </form>
                            </td>

                            <td class="text-center">
                                <a
                                        href="/SobatKost/index.php?url=inventaris/edit&id=<?= $inv->getIdInventaris() ?>"
                                        class="btn btn-sm btn-outline-primary me-1"
                                        title="Edit Barang"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a
                                        href="/SobatKost/index.php?url=inventaris/delete&id=<?= $inv->getIdInventaris() ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Hapus Barang"
                                        onclick="return confirm('Yakin ingin menghapus barang ini dari inventaris?');"
                                >
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
                                    href="/SobatKost/index.php?url=inventaris&page=<?= $i ?>"
                            >
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>