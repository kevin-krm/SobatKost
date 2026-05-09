<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Tambah Aturan Baru</h2>
    <a href="/SobatKost/index.php?url=aturan" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="/SobatKost/index.php?url=aturan/store" method="POST">
            <div class="mb-3">
                <label for="judul_aturan" class="form-label fw-bold">Judul Aturan</label>
                <input type="text" class="form-control" id="judul_aturan" name="judul_aturan" placeholder="Contoh: Jam Malam" required>
            </div>
            <div class="mb-4">
                <label for="deskripsi_aturan" class="form-label fw-bold">Deskripsi Aturan</label>
                <textarea class="form-control" id="deskripsi_aturan" name="deskripsi_aturan" rows="4" placeholder="Jelaskan detail aturannya di sini..." required></textarea>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i> Simpan Aturan
                </button>
            </div>
        </form>
    </div>
</div>