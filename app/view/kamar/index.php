<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Data Kamar</h2>
        <p class="text-muted">
            Kelola data kamar kost Anda.
        </p>
    </div>

    <a href="/SobatKost/index.php?url=kamar/create" class="btn btn-primary shadow-sm">
        <i class="bi bi-house-add me-2"></i>
        Tambah Kamar
    </a>
</div>

<p class="text-muted small">
    Total kamar: <?= $totalData ?>
</p>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">

            <div class="mb-3">
                <input
                        type="text"
                        id="searchKamar"
                        class="form-control"
                        placeholder="Cari kamar..."
                        onkeyup="searchKamar()"
                >
            </div>

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                <tr>
                    <th>ID KAMAR</th>
                    <th>NOMOR</th>
                    <th>TIPE</th>
                    <th class="text-center">STATUS</th> <th>HARGA</th>
                    <th class="text-center">AKSI (UBAH STATUS)</th>
                    <th class="text-center">AKSI LAINNYA</th>
                </tr>
                </thead>

                <tbody>
                <?php if (empty($kamarList)) : ?>

                    <tr>
                        <td colspan="7" class="text-center p-4 text-muted">
                            Belum ada data kamar
                        </td>
                    </tr>

                <?php else : ?>

                    <?php foreach ($kamarList as $k) : ?>

                        <?php
                        $status = $k->getStatusKamar();
                        $badge = "bg-secondary";

                        if ($status === "Tersedia") {
                            $badge = "bg-success";
                        } elseif ($status === "Terisi") {
                            $badge = "bg-danger";
                        } elseif ($status === "Perbaikan") {
                            $badge = "bg-warning text-dark";
                        }

                        $editUrl = "/SobatKost/index.php?url=kamar/edit&id=" . $k->getId();
                        $deleteUrl = "/SobatKost/index.php?url=kamar/delete&id=" . $k->getId();
                        ?>

                        <tr>
                            <td><?= htmlspecialchars($k->getId()) ?></td>

                            <td><?= htmlspecialchars($k->getNomorKamar()) ?></td>

                            <td><?= htmlspecialchars($k->getTipeKamar() ?? '-') ?></td>

                            <td class="text-center">
                                <span class="badge <?= $badge ?>">
                                    <?= htmlspecialchars($status) ?>
                                </span>
                            </td>

                            <td>
                                Rp <?= number_format($k->getHargaDasar(), 0, ',', '.') ?>
                            </td>

                            <td class="text-center">
                                <form action="/SobatKost/index.php?url=kamar/updateStatus&id=<?= $k->getId() ?>" method="POST" class="d-flex justify-content-center align-items-center gap-2 m-0">
                                    <select name="status_kamar" class="form-select form-select-sm" style="width: 110px;">
                                        <option value="Tersedia" <?= $status == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                                        <option value="Terisi" <?= $status == 'Terisi' ? 'selected' : '' ?>>Terisi</option>
                                        <option value="Perbaikan" <?= $status == 'Perbaikan' ? 'selected' : '' ?>>Perbaikan</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>

                            <td class="text-center">
                                <a
                                        href="<?= $editUrl ?>"
                                        class="btn btn-sm btn-outline-primary me-1"
                                        title="Edit kamar"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a
                                        href="<?= $deleteUrl ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Hapus kamar"
                                        onclick="return confirm('Yakin hapus kamar ini?');"
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
                                    href="/SobatKost/index.php?url=kamar&page=<?= $i ?>"
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