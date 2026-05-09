<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Edit Pengumuman</h2>
    <a href="/SobatKost/index.php?url=pengumuman" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="/SobatKost/index.php?url=pengumuman/update&id=<?= $pengumuman->getIdPengumuman() ?>" method="POST">
            <div class="mb-3">
                <label for="judul" class="form-label fw-bold">Judul Pengumuman</label>
                <input type="text" class="form-control" id="judul" name="judul" value="<?= htmlspecialchars($pengumuman->getJudul()) ?>" required>
            </div>
            <div class="mb-4">
                <label for="konten" class="form-label fw-bold">Isi Pesan</label>
                <textarea class="form-control" id="konten" name="konten" rows="5" required><?= htmlspecialchars($pengumuman->getKonten()) ?></textarea>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>