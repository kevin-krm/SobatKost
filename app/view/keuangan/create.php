<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Tambah Biaya Operasional</h2>
        <p class="text-muted">Masukkan data pengeluaran baru.</p>
    </div>
    <a href="/SobatKost/index.php?url=keuangan" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="/SobatKost/index.php?url=keuangan/store" method="POST">
            <div class="mb-3">
                <label for="kategori_biaya" class="form-label">Kategori Biaya</label>
                <select name="kategori_biaya" id="kategori_biaya" class="form-select" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    <option value="Listrik">Listrik</option>
                    <option value="Air">Air</option>
                    <option value="Kebersihan">Kebersihan</option>
                    <option value="Gaji Karyawan">Gaji Karyawan</option>
                    <option value="Perbaikan">Perbaikan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="jumlah_biaya" class="form-label">Jumlah Biaya (Rp)</label>
                <input type="number" name="jumlah_biaya" id="jumlah_biaya" class="form-control" required min="0" step="0.01">
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
