<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Broadcast Pengumuman</h2>
        <p class="text-muted">Kelola informasi massal untuk seluruh penyewa kost.</p>
    </div>
    <a href="/SobatKost/index.php?url=pengumuman/create" class="btn btn-primary shadow-sm">
        <i class="bi bi-megaphone me-2"></i> Buat Pengumuman
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID PENGUMUMAN</th>
                    <th>JUDUL</th>
                    <th>KONTEN / ISI</th>
                    <th>TANGGAL SIAR</th>
                    <th class="text-center">AKSI</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($pengumumanList)) : ?>
                    <tr><td colspan="5" class="text-center p-4">Belum ada pengumuman yang disiarkan.</td></tr>
                <?php else : ?>
                    <?php foreach ($pengumumanList as $p) : ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($p->getIdPengumuman() ?? '') ?></strong></td>
                            <td><?= htmlspecialchars($p->getJudul() ?? '') ?></td>
                            <td><?= htmlspecialchars($p->getKonten() ?? '') ?></td>
                            <td><?= htmlspecialchars($p->getCreatedAt() ?? '') ?></td>
                            <td class="text-center">
                                <a href="/SobatKost/index.php?url=pengumuman/edit&id=<?= $p->getIdPengumuman() ?>"
                                   class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="/SobatKost/index.php?url=pengumuman/delete&id=<?= $p->getIdPengumuman() ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Yakin ingin menghapus pengumuman ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>