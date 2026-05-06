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
                    <th>STATUS</th>
                    <th>HARGA</th>
                    <th class="text-center">AKSI</th>
                </tr>
                </thead>

                <tbody>
                <?php if (empty($kamarList)) : ?>

                    <tr>
                        <td colspan="6" class="text-center p-4 text-muted">
                            Belum ada data kamar
                        </td>
                    </tr>

                <?php else : ?>

                    <?php foreach ($kamarList as $k) : ?>

                        <?php
                        $badge = "bg-secondary";

                        if ($k->status_kamar === "Tersedia") {
                            $badge = "bg-success";
                        } elseif ($k->status_kamar === "Terisi") {
                            $badge = "bg-danger";
                        } elseif ($k->status_kamar === "Perbaikan") {
                            $badge = "bg-warning";
                        }

                        $editUrl = "/SobatKost/index.php?url=kamar/edit&id=" . $k->id_kamar;
                        $deleteUrl = "/SobatKost/index.php?url=kamar/delete&id=" . $k->id_kamar;
                        ?>

                        <tr>
                            <td>
                                <?= htmlspecialchars($k->id_kamar) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($k->nomor_kamar) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($k->tipe_kamar ?? '-') ?>
                            </td>

                            <td class="text-center">
                                <span class="badge <?= $badge ?>">
                                    <?= htmlspecialchars($k->status_kamar) ?>
                                </span>
                            </td>

                            <td>
                                Rp <?= number_format($k->harga_dasar, 0, ',', '.') ?>
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