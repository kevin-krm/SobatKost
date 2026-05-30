<script src="/SobatKost/public/js/admin.js"></script>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Tiket Komplain</h2>
        <p class="text-muted">
            Pelaporan dan pelacakan progres perbaikan fasilitas kost.
        </p>
    </div>
</div>

<p class="text-muted small">Total tiket: <?= $totalData ?></p>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <div class="mb-3 p-3 pb-0">
                <input type="text" id="searchKomplain" class="form-control" placeholder="Cari tiket..." onkeyup="searchKomplain()">
            </div>

            <table class="table table-bordered table-striped align-middle mb-0 mt-3">
                <thead class="table-light">
                <tr>
                    <th>ID TIKET</th>
                    <th>PENGGUNA</th>
                    <th>KAMAR</th>
                    <th>JUDUL MASALAH</th>
                    <th class="text-center">DETAIL</th>
                </tr>
                </thead>

                <tbody>
                <?php if (!empty($komplainList)): ?>
                    <?php foreach ($komplainList as $k): ?>
                        <?php
                        $modalId = "descModal" . $k->getIdKomplain();
                        $status = $k->getStatusKomplain();
                        $badge = $status == 'Selesai' ? 'bg-success' : ($status == 'Diproses' ? 'bg-primary' : 'bg-warning text-dark');
                        ?>

                        <tr>
                            <td><strong><?= htmlspecialchars($k->getIdKomplain()) ?></strong></td>
                            <td><?= htmlspecialchars($k->getNamaPengguna() ?? $k->getIdPengguna()) ?></td>
                            <td><?= htmlspecialchars($k->getIdKamar() ?? '-') ?></td>
                            <td><?= htmlspecialchars($k->getJudulMasalah()) ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">
                                    <i class="bi bi-eye"></i> Lihat Detail
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="<?= $modalId ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Detail Komplain</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <span class="text-muted d-block" style="font-size: 0.85rem;">Tanggal Lapor</span>
                                            <strong><?= date('d M Y - H:i', strtotime($k->getTanggalLapor())) ?></strong>
                                        </div>
                                        <div class="mb-3">
                                            <span class="text-muted d-block" style="font-size: 0.85rem;">Status Saat Ini</span>
                                            <span class="badge <?= $badge ?> px-3 py-2 mt-1"><?= htmlspecialchars($status) ?></span>
                                        </div>
                                        <hr>
                                        <div>
                                            <span class="text-muted d-block mb-2" style="font-size: 0.85rem;">Deskripsi Masalah</span>
                                            <p style="white-space: pre-line;"><?= htmlspecialchars($k->getDeskripsi()) ?></p>
                                        </div>
                                    </div>
                                    <div class="modal-footer d-flex justify-content-between">
                                        <small class="text-muted">*Admin hanya dapat memantau tiket.</small>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center p-4 text-muted">Belum ada data komplain.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="/SobatKost/index.php?url=komplain&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>