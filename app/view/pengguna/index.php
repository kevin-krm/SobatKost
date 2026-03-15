<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Data Pengguna</h2>
        <p class="text-muted">Kelola informasi penyewa kost Anda secara terpusat.</p>
    </div>
    <a href="/SobatKost/pengguna/create" class="btn btn-primary shadow-sm">
        <i class="bi bi-person-plus-fill me-2"></i> Tambah Pengguna
    </a>
</div>
<p class="text-muted small">
    Total pengguna: <?= $totalData ?>
</p>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <div class="mb-3">
                <input
                        type="text"
                        id="searchPengguna"
                        class="form-control"
                        placeholder="Cari pengguna..."
                        onkeyup="searchPengguna()" >
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
                <?php if(empty($penggunaList)): ?>
                    <tr>
                        <td colspan="7" class="text-center p-4 text-muted">Belum ada data pengguna</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($penggunaList as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p->id_pengguna) ?></td>
                            <td>
                                <div><?= htmlspecialchars($p->nama_lengkap) ?></div>
                                <small class="text-muted">
                                    <?= htmlspecialchars($p->nomor_telepon ?? '-') ?>
                                </small>
                            </td>
                            <td>
                                <?= date('d M Y', strtotime($p->created_at)) ?>
                            </td>
                            <td>
                                <div><?= htmlspecialchars($p->email) ?></div>
                                <small class="text-muted">••••••••</small>
                            </td>
                            <td class="text-center">
                                <?php
                                $badgeColor = "bg-secondary";
                                if($p->nama_peran == "Admin"){
                                    $badgeColor = "bg-danger";
                                }
                                elseif($p->nama_peran == "Pengguna"){
                                    $badgeColor = "bg-primary";
                                }
                                ?>
                                <span class="badge <?= $badgeColor ?>">
                                <?= htmlspecialchars($p->nama_peran) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button
                                        class="btn btn-sm btn-outline-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ktpModal<?= $p->id_pengguna ?>"
                                >Lihat
                                </button>
                            </td>
                            <td class="text-center">
                                <a
                                        href="/SobatKost/pengguna/edit/<?= $p->id_pengguna ?>"
                                        class="btn btn-sm btn-outline-primary me-1"
                                        title="Edit pengguna"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a
                                        href="/SobatKost/pengguna/delete/<?= $p->id_pengguna ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Hapus pengguna"
                                        onclick="return confirm('Yakin ingin menghapus pengguna ini?');"
                                >
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <div class="modal fade" id="ktpModal<?= $p->id_pengguna ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Foto KTP</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="/SobatKost/<?= $p->foto_ktp ?>" class="img-fluid rounded">
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
                    <?php for($i=1;$i<=$totalPage;$i++): ?>
                        <li class="page-item <?= ($page==$i)?'active':'' ?>">
                            <a class="page-link"
                               href="/SobatKost/pengguna?page=<?= $i ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>