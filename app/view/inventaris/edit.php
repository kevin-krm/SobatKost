<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Edit Inventaris Kamar</h2>
        <p class="text-muted">Perbarui data fasilitas atau pindahkan lokasi barang.</p>
    </div>
    <a href="/SobatKost/index.php?url=inventaris" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="/SobatKost/index.php?url=inventaris/update&id=<?= $inventaris->getIdInventaris() ?>" method="POST">

            <div class="mb-3">
                <label for="id_kamar" class="form-label fw-bold">ID Kamar (Lokasi)</label>
                <input type="text" class="form-control" id="id_kamar" name="id_kamar" value="<?= htmlspecialchars($inventaris->getIdKamar()) ?>" required>
            </div>

            <div class="mb-3">
                <label for="nama_barang" class="form-label fw-bold">Nama Barang / Fasilitas</label>
                <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?= htmlspecialchars($inventaris->getNamaBarang()) ?>" required>
            </div>

            <div class="mb-4">
                <label for="kondisi_barang" class="form-label fw-bold">Kondisi Saat Ini</label>
                <select class="form-select" id="kondisi_barang" name="kondisi_barang" required>
                    <option value="Bagus" <?= $inventaris->getKondisiBarang() == 'Bagus' ? 'selected' : '' ?>>Bagus</option>
                    <option value="Rusak Ringan" <?= $inventaris->getKondisiBarang() == 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                    <option value="Rusak Berat" <?= $inventaris->getKondisiBarang() == 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
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