<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Edit Aturan Kost</h2>
    <a href="/SobatKost/index.php?url=aturan" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="/SobatKost/index.php?url=aturan/update&id=<?= $aturan->getIdAturan() ?>" method="POST">
            <div class="mb-3">
                <label for="judul_aturan" class="form-label fw-bold">Judul Aturan</label>
                <input type="text" class="form-control" id="judul_aturan" name="judul_aturan" value="<?= htmlspecialchars($aturan->getJudulAturan()) ?>" required>
            </div>
            <div class="mb-4">
                <label for="deskripsi_aturan" class="form-label fw-bold">Deskripsi Aturan</label>
                <textarea class="form-control" id="deskripsi_aturan" name="deskripsi_aturan" rows="4" required><?= htmlspecialchars($aturan->getDeskripsiAturan()) ?></textarea>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>