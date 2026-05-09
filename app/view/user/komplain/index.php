<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Tiket Komplain Saya</h2>
        <p class="text-muted">Pantau status laporan perbaikan fasilitas kamar Anda.</p>
    </div>
    <a href="/SobatKost/index.php?url=komplain/create" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-2"></i> Buat Komplain Baru
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID TIKET</th>
                    <th>JUDUL MASALAH</th>
                    <th>DESKRIPSI</th>
                    <th>TANGGAL LAPOR</th>
                    <th class="text-center">STATUS</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($komplainList)) : ?>
                    <tr><td colspan="5" class="text-center p-4 text-muted">Belum ada komplain yang Anda buat.</td></tr>
                <?php else : ?>
                    <?php foreach ($komplainList as $k) : ?>
                        <?php
                        // Pewarnaan Badge Status
                        $status = $k->getStatusKomplain();
                        $badge = $status == 'Selesai' ? 'bg-success' : ($status == 'Diproses' ? 'bg-primary' : 'bg-warning text-dark');
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($k->getIdKomplain()) ?></strong></td>
                            <td><?= htmlspecialchars($k->getJudulMasalah()) ?></td>
                            <td><?= htmlspecialchars($k->getDeskripsi()) ?></td>
                            <td><?= htmlspecialchars($k->getTanggalLapor()) ?></td>
                            <td class="text-center">
                                <span class="badge <?= $badge ?> px-3 py-2"><?= htmlspecialchars($status) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>