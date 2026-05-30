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
                    <th class="text-center">UPDATE STATUS</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($komplainList)) : ?>
                    <tr><td colspan="5" class="text-center p-4 text-muted">Belum ada komplain yang Anda buat.</td></tr>
                <?php else : ?>
                    <?php foreach ($komplainList as $k) : ?>
                        <?php
                        $status = $k->getStatusKomplain();
                        $badge = $status == 'Selesai' ? 'bg-success text-white' : ($status == 'Diproses' ? 'bg-primary text-white' : 'bg-warning text-dark');
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($k->getIdKomplain()) ?></strong></td>
                            <td><?= htmlspecialchars($k->getJudulMasalah()) ?></td>
                            <td><small style="white-space: pre-line;"><?= htmlspecialchars($k->getDeskripsi()) ?></small></td>
                            <td><?= date('d M Y', strtotime($k->getTanggalLapor())) ?></td>

                            <td class="text-center" style="width: 180px;">
                                <?php if ($status === 'Selesai'): ?>
                                    <button class="btn btn-sm btn-secondary w-100" disabled>
                                        <i class="bi bi-lock-fill"></i> Selesai
                                    </button>
                                <?php else: ?>
                                    <form action="/SobatKost/index.php?url=komplain/updateStatus&id=<?= $k->getIdKomplain() ?>" method="POST" class="m-0" onsubmit="return confirmSelesai(this);">
                                        <select name="status_komplain" class="form-select form-select-sm mb-2 <?= $badge ?>" onchange="this.className='form-select form-select-sm mb-2 ' + (this.value == 'Menunggu' ? 'bg-warning text-dark' : (this.value == 'Diproses' ? 'bg-primary text-white' : 'bg-success text-white'))">
                                            <option value="Menunggu" <?= $status == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                            <option value="Diproses" <?= $status == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                                            <option value="Selesai">Selesai</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-primary w-100">Simpan Status</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmSelesai(form) {
        if (form.status_komplain.value === 'Selesai') {
            return confirm("Apakah Anda yakin tiket ini sudah Selesai? Status tidak bisa diubah lagi jika sudah Selesai.");
        }
        return true;
    }
</script>