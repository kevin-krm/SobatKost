<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Data Penghuni</h2>
        <p class="text-muted">Kelola informasi penyewa kost Anda secara terpusat.</p>
    </div>
    <a href="/SobatKost/penghuni/create" class="btn btn-primary shadow-sm">
        <i class="bi bi-person-plus-fill me-2"></i> Tambah Penghuni
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light-blue">
                <tr>
                    <th class="ps-4">ID PENGGUNA</th>
                    <th>NAMA PENGHUNI</th>
                    <th>TANGGAL MASUK</th>
                    <th>STATUS</th>
                    <th class="text-center">ROLE</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($penghuniList)):
                    $no = 1;
                    foreach ($penghuniList as $p):
                        $badgeColor = ($p->nama_peran == 'Owner') ? 'bg-success' : 'bg-primary';
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">
                                    <?= htmlspecialchars($p->id_pengguna) ?>
                                </div>
                            </td>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">
                                    <?= htmlspecialchars($p->nama_lengkap) ?>
                                </div>

                                <small class="text-muted">
                                    <?= htmlspecialchars($p->nomor_telepon ?? 'No Telp Tidak Ada') ?>
                                </small>
                            </td>
                            <td><?= htmlspecialchars($p->nama_lengkap) ?></td>
                            <td><?= htmlspecialchars($p->nama_lengkap) ?></td>
                            <td><?= htmlspecialchars($p->nama_lengkap) ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data penghuni.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>