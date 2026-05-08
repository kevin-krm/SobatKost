<script src="/SobatKost/public/js/admin.js"></script>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Tiket Komplain</h2>
        <p class="text-muted">
            Pelaporan dan pelacakan progres perbaikan fasilitas kost.
        </p>
    </div>

    <a href="/SobatKost/index.php?url=komplain/create" class="btn btn-primary shadow-sm">
        <i class="bi bi-tools me-2"></i>
        Tambah Komplain
    </a>
</div>

<p class="text-muted small">Total tiket: <?= $totalData ?></p>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <div class="mb-3">
                <input
                        type="text"
                        id="searchKomplain"
                        class="form-control"
                        placeholder="Cari komplain..."
                        onkeyup="searchKomplain()"
                >
            </div>

            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID TIKET</th>
                    <th>ID PENGGUNA</th>
                    <th>JUDUL MASALAH</th>
                    <th class="text-center">DESKRIPSI</th>
                    <th>TANGGAL LAPOR</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center">AKSI LAINNYA</th>
                </tr>
                </thead>

                <tbody>
                <?php if (!empty($komplainList)): ?>
                    <?php foreach ($komplainList as $k): ?>

                        <?php
                        $modalId = "descModal" . $k->getIdKomplain();
                        $status = $k->getStatusKomplain();

                        if ($status === "Menunggu") {
                            $badge = "bg-warning text-dark";
                        } elseif ($status === "Diproses") {
                            $badge = "bg-primary text-white";
                        } elseif ($status === "Selesai") {
                            $badge = "bg-success text-white";
                        } else {
                            $badge = "bg-secondary text-white";
                        }
                        ?>

                        <tr>
                            <td><?= htmlspecialchars($k->getIdKomplain()) ?></td>
                            <td><?= htmlspecialchars($k->getIdPengguna()) ?></td>
                            <td><?= htmlspecialchars($k->getJudulMasalah()) ?></td>

                            <td class="text-center">
                                <button
                                        class="btn btn-sm btn-outline-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#<?= $modalId ?>"
                                >
                                    Lihat
                                </button>
                            </td>

                            <td>
                                <?= date('d M Y H:i', strtotime($k->getTanggalLapor())) ?>
                            </td>

                            <td class="text-center">
                                <form
                                        action="/SobatKost/index.php?url=komplain/updateStatus&id=<?= $k->getIdKomplain() ?>"
                                        method="POST"
                                        class="d-flex justify-content-center align-items-center gap-2 m-0"
                                >

                                    <select
                                            name="status_komplain"
                                            class="form-select form-select-sm <?= $badge ?>"
                                            style="width: 140px; font-weight: 500;"
                                            onchange="this.className='form-select form-select-sm ' +
                                            (this.value == 'Menunggu' ? 'bg-warning text-dark' :
                                            (this.value == 'Diproses' ? 'bg-primary text-white' :
                                            (this.value == 'Selesai' ? 'bg-success text-white' :
                                            'bg-secondary text-white')))"
                                    >
                                        <option value="Menunggu" <?= $status == 'Menunggu' ? 'selected' : '' ?>>
                                            Menunggu
                                        </option>

                                        <option value="Diproses" <?= $status == 'Diproses' ? 'selected' : '' ?>>
                                            Diproses
                                        </option>

                                        <option value="Selesai" <?= $status == 'Selesai' ? 'selected' : '' ?>>
                                            Selesai
                                        </option>
                                    </select>

                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Update
                                    </button>
                                </form>
                            </td>

                            <td class="text-center">
                                <a
                                        href="/SobatKost/index.php?url=komplain/edit&id=<?= $k->getIdKomplain() ?>"
                                        class="btn btn-sm btn-outline-primary me-1"
                                        title="Edit komplain"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a
                                        href="/SobatKost/index.php?url=komplain/delete&id=<?= $k->getIdKomplain() ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Hapus komplain"
                                        onclick="return confirm('Yakin ingin menghapus tiket komplain ini?');"
                                >
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="<?= $modalId ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            Deskripsi Masalah
                                        </h5>

                                        <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                        ></button>
                                    </div>

                                    <div class="modal-body text-start">
                                        <?= nl2br(htmlspecialchars($k->getDeskripsi())) ?>
                                    </div>

                                    <div class="modal-footer">
                                        <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal"
                                        >
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7" class="text-center p-4 text-muted">
                            Belum ada data komplain.
                        </td>
                    </tr>

                <?php endif; ?>
                </tbody>
            </table>

            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <a
                                    class="page-link"
                                    href="/SobatKost/index.php?url=komplain&page=<?= $i ?>"
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