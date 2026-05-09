<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Manajemen Aturan Kost (E-Rules)</h2>
        <p class="text-muted">Kelola tata tertib yang berlaku bagi seluruh penyewa.</p>
    </div>
    <a href="/SobatKost/index.php?url=aturan/create" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-2"></i> Tambah Aturan
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID ATURAN</th>
                    <th>JUDUL ATURAN</th>
                    <th>DESKRIPSI / PENJELASAN</th>
                    <th>TERAKHIR UPDATE</th>
                    <th class="text-center">AKSI</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($aturanList)) : ?>
                    <tr><td colspan="5" class="text-center p-4">Belum ada aturan kost yang ditambahkan.</td></tr>
                <?php else : ?>
                    <?php foreach ($aturanList as $a) : ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($a->getIdAturan() ?? '') ?></strong></td>
                            <td><?= htmlspecialchars($a->getJudulAturan() ?? '') ?></td>
                            <td><?= htmlspecialchars($a->getDeskripsiAturan() ?? '') ?></td>
                            <td><?= htmlspecialchars($a->getUpdatedAt() ?? '') ?></td>
                            <td class="text-center">
                                <a href="/SobatKost/index.php?url=aturan/edit&id=<?= $a->getIdAturan() ?>" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="/SobatKost/index.php?url=aturan/delete&id=<?= $a->getIdAturan() ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus aturan ini?')">
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