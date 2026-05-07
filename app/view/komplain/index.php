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

<div class="mb-3">
    <input
            type="text"
            id="searchKomplain"
            class="form-control"
            placeholder="Cari Komplain..."
            onkeyup="searchKomplain()"
    >
</div>

<p class="text-muted small">Total tiket: <?= $totalData ?></p>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID TIKET</th>
                    <th>ID PENGGUNA</th>
                    <th>JUDUL MASALAH</th>
                    <th class="text-center">STATUS</th>
                    <th>TANGGAL LAPOR</th>
                    <th class="text-center">AKSI</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($komplainList)): ?>
                    <?php foreach ($komplainList as $k): ?>
                        <tr>
                            <td><?= htmlspecialchars($k->getIdKomplain()) ?></td>
                            <td><?= htmlspecialchars($k->getIdPengguna()) ?></td>
                            <td><?= htmlspecialchars($k->getJudulMasalah()) ?></td>
                            <td class="text-center">
                                <span class="badge <?= $k->getStatusKomplain() == 'Selesai' ? 'bg-success' : ($k->getStatusKomplain() == 'Diproses' ? 'bg-warning text-dark' : 'bg-secondary') ?>">
                                    <?= htmlspecialchars($k->getStatusKomplain()) ?>
                                </span>
                            </td>
                            <td><?= date('d M Y H:i', strtotime($k->getTanggalLapor())) ?></td>
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
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center p-4 text-muted">
                            Belum ada data komplain.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>