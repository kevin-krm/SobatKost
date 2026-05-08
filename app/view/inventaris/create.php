<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Tambah Inventaris Kamar</h2>
        <p class="text-muted">Tambahkan data fasilitas atau aset baru ke dalam kamar.</p>
    </div>
    <a href="/SobatKost/index.php?url=inventaris" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="/SobatKost/index.php?url=inventaris/store" method="POST">

            <div class="mb-3">
                <label for="id_kamar" class="form-label fw-bold">ID Kamar (Lokasi)</label>
                <input type="text" class="form-control" id="id_kamar" name="id_kamar" placeholder="Contoh: K-101" required>
                <small class="text-muted">*Pastikan ID Kamar sudah terdaftar di data Kamar.</small>
            </div>

            <div class="mb-3">
                <label for="nama_barang" class="form-label fw-bold">Nama Barang / Fasilitas</label>
                <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Contoh: Kasur Springbed Single" required>
            </div>

            <div class="mb-4">
                <label for="kondisi_barang" class="form-label fw-bold">Kondisi Saat Ini</label>
                <select class="form-select" id="kondisi_barang" name="kondisi_barang" required>
                    <option value="" disabled selected>-- Pilih Kondisi Barang --</option>
                    <option value="Bagus">Bagus</option>
                    <option value="Rusak Ringan">Rusak Ringan</option>
                    <option value="Rusak Berat">Rusak Berat</option>
                </select>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>