<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Edit Biaya Operasional</h2>
        <p class="text-muted">Ubah data pengeluaran yang sudah ada.</p>
    </div>
    <a href="/SobatKost/index.php?url=keuangan" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="/SobatKost/index.php?url=keuangan/update&id=<?= $biaya->getIdBiaya() ?>" method="POST">
            <div class="mb-3">
                <label class="form-label">ID Biaya</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($biaya->getIdBiaya()) ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="kategori_biaya" class="form-label">Kategori Biaya</label>
                <select name="kategori_biaya" id="kategori_biaya" class="form-select" required>
                    <option value="Listrik" <?= $biaya->getKategoriBiaya() == 'Listrik' ? 'selected' : '' ?>>Listrik</option>
                    <option value="Air" <?= $biaya->getKategoriBiaya() == 'Air' ? 'selected' : '' ?>>Air</option>
                    <option value="Kebersihan" <?= $biaya->getKategoriBiaya() == 'Kebersihan' ? 'selected' : '' ?>>Kebersihan</option>
                    <option value="Gaji Karyawan" <?= $biaya->getKategoriBiaya() == 'Gaji Karyawan' ? 'selected' : '' ?>>Gaji Karyawan</option>
                    <option value="Perbaikan" <?= $biaya->getKategoriBiaya() == 'Perbaikan' ? 'selected' : '' ?>>Perbaikan</option>
                    <option value="Lainnya" <?= $biaya->getKategoriBiaya() == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="jumlah_biaya" class="form-label">Jumlah Biaya (Rp)</label>
                <input type="number" name="jumlah_biaya" id="jumlah_biaya" class="form-control" value="<?= htmlspecialchars($biaya->getJumlahBiaya()) ?>" required min="0" step="0.01">
            </div>
            <div class="mb-3">
                <label for="tanggal_pengeluaran" class="form-label">Tanggal Pengeluaran</label>
                <input type="date" name="tanggal_pengeluaran" id="tanggal_pengeluaran" class="form-control" value="<?= htmlspecialchars($biaya->getTanggalPengeluaran()) ?>" required>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="form-control" rows="3"><?= htmlspecialchars($biaya->getKeterangan()) ?></textarea>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
