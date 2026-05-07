<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2>Edit Tiket Komplain</h2>
            <p>Perbarui detail laporan atau ubah status penanganan masalah.</p>
        </div>
        <a href="/SobatKost/komplain" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="/SobatKost/index.php?url=komplain/updateStatus&id=<?= $komplain->getIdKomplain() ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label">Judul Masalah</label>
                    <input type="text" class="form-control" name="judul_masalah"
                           value="<?= htmlspecialchars($komplain->getJudulMasalah()) ?>" readonly>
                    <small class="text-muted text-danger">*Judul laporan tidak dapat diubah setelah dikirim.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi Kerusakan</label>
                    <textarea class="form-control" name="deskripsi" rows="4" readonly><?= htmlspecialchars($komplain->getDeskripsi()) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="status_komplain" class="form-label">Status Penanganan (Observer Trigger)</label>
                    <select class="form-select" id="status_komplain" name="status_komplain">
                        <option value="Menunggu" <?= $komplain->getStatusKomplain() == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                        <option value="Diproses" <?= $komplain->getStatusKomplain() == 'Diproses' ? 'selected' : '' ?>>Sedang Diperbaiki (Diproses)</option>
                        <option value="Selesai" <?= $komplain->getStatusKomplain() == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Perubahan Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>