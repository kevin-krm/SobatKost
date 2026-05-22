<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Data Pengguna</h2>
        <p class="text-muted">
            Kelola informasi penyewa kost Anda secara terpusat.
        </p>
    </div>

    <a href="/SobatKost/index.php?url=pengguna/create" class="btn btn-primary shadow-sm">
        <i class="bi bi-person-plus-fill me-2"></i>
        Tambah Pengguna
    </a>
</div>

<p class="text-muted small">Total pengguna: <?= $totalData ?></p>

<?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">

            <div class="mb-3">
                <input
                        type="text"
                        id="searchPengguna"
                        class="form-control"
                        placeholder="Cari pengguna..."
                        onkeyup="searchPengguna()"
                >
            </div>

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                <tr>
                    <th>ID PENGGUNA</th>
                    <th>NAMA PENGGUNA</th>
                    <th>TANGGAL MASUK</th>
                    <th>E-MAIL</th>
                    <th>ROLE</th>
                    <th>KTP</th>
                    <th class="text-center">AKSI</th>
                </tr>
                </thead>

                <tbody>
                <?php if (empty($penggunaList)) : ?>

                    <tr>
                        <td colspan="7" class="text-center p-4 text-muted">
                            Belum ada data pengguna
                        </td>
                    </tr>

                <?php else : ?>

                    <?php foreach ($penggunaList as $p) : ?>

                        <?php
                        $badgeColor = "bg-secondary";

                        if ($p->getNamaPeran() === "Owner") {
                            $badgeColor = "bg-danger";
                        } elseif ($p->getNamaPeran() === "Penjaga") {
                            $badgeColor = "bg-primary";
                        } elseif ($p->getNamaPeran() === "Penyewa") {
                            $badgeColor = "bg-success";
                        }

                        $editUrl = "/SobatKost/index.php?url=pengguna/edit&id=" . $p->getId();
                        $deleteUrl = "/SobatKost/index.php?url=pengguna/delete&id=" . $p->getId();
                        $modalId = "ktpModal" . $p->getId();
                        ?>

                        <tr>
                            <td>
                                <?= htmlspecialchars($p->getId()) ?>
                            </td>

                            <td>
                                <div>
                                    <?= htmlspecialchars($p->getNamaLengkap()) ?>
                                </div>
                                <small class="text-muted">
                                    <?= htmlspecialchars($p->getNomorTelepon() ?? '-') ?>
                                </small>
                            </td>

                            <td>
                                <?= date('d M Y', strtotime($p->getCreatedAt())) ?>
                            </td>

                            <td>
                                <div>
                                    <?= htmlspecialchars($p->getEmail()) ?>
                                </div>
                                <small class="text-muted">
                                    ••••••••
                                </small>
                            </td>

                            <td class="text-center">
                                <span class="badge <?= $badgeColor ?>">
                                    <?= htmlspecialchars($p->getNamaPeran()) ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <button
                                        class="btn btn-sm btn-outline-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#<?= $modalId ?>"
                                >
                                    Lihat
                                </button>
                            </td>

                            <td class="text-center">
                                <a
                                        href="<?= $editUrl ?>"
                                        class="btn btn-sm btn-outline-primary me-1"
                                        title="Edit pengguna"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a
                                        href="<?= $deleteUrl ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Hapus pengguna"
                                        onclick="return confirm('Yakin ingin menghapus pengguna ini?');"
                                >
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="<?= $modalId ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            Foto KTP
                                        </h5>
                                        <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                        ></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="/SobatKost/<?= $p->getFotoKtp() ?>" class="img-fluid rounded">
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                    href="/SobatKost/index.php?url=pengguna&page=<?= $i ?>"><?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>

        </div>
    </div>
</div>