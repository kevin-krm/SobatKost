<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Edit Tiket Komplain</h2>
        <p class="text-muted">Perbarui detail laporan atau ubah status penanganan masalah.</p>
    </div>
    <a href="/SobatKost/index.php?url=komplain" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="/SobatKost/index.php?url=komplain/update&id=<?= $komplain->getIdKomplain() ?>" method="POST">

            <div class="mb-3">
                <label class="form-label fw-bold">Judul Masalah</label>
                <input type="text" class="form-control" name="judul_masalah"
                       value="<?= htmlspecialchars($komplain->getJudulMasalah()) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Deskripsi Kerusakan</label>
                <textarea class="form-control" name="deskripsi" rows="4" required><?= htmlspecialchars($komplain->getDeskripsi()) ?></textarea>
            </div>

            <div class="mb-4">
                <label for="status_komplain" class="form-label fw-bold">Status Penanganan</label>
                <select class="form-select" id="status_komplain" name="status_komplain" required>
                    <option value="Menunggu" <?= $komplain->getStatusKomplain() == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                    <option value="Diproses" <?= $komplain->getStatusKomplain() == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                    <option value="Selesai" <?= $komplain->getStatusKomplain() == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>