<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Edit Tagihan</h2>
        <p class="text-muted">
            Ubah informasi tagihan.
        </p>
    </div>
</div>

<!-- Flash Messages -->
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <?= $_SESSION['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="/SobatKost/index.php?url=tagihan/update&id=<?= $tagihan->getIdTagihan() ?>" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ID Tagihan</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($tagihan->getIdTagihan()) ?>" disabled>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ID Kontrak</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($tagihan->getIdKontrak()) ?>" disabled>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="total_biaya_sewa" class="form-label fw-bold">Biaya Sewa</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="total_biaya_sewa" class="form-control" value="<?= $tagihan->getTotalBiayaSewa() ?>" disabled>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="biaya_tambahan" class="form-label fw-bold">Biaya Tambahan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="biaya_tambahan" name="biaya_tambahan" class="form-control" 
                                   value="<?= $tagihan->getBiayaTambahan() ?>" min="0" step="1000" onchange="updateTotal()">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal_jatuh_tempo" class="form-label fw-bold">Tanggal Jatuh Tempo</label>
                        <input type="date" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" class="form-control" 
                               value="<?= $tagihan->getTanggalJatuhTempo() ?>" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status_tagihan" class="form-label fw-bold">Status Tagihan</label>
                        <select id="status_tagihan" name="status_tagihan" class="form-select" required>
                            <option value="Belum Lunas" <?= $tagihan->getStatusTagihan() === 'Belum Lunas' ? 'selected' : '' ?>>
                                Belum Lunas
                            </option>
                            <option value="Lunas" <?= $tagihan->getStatusTagihan() === 'Lunas' ? 'selected' : '' ?>>
                                Lunas
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Tagihan</label>
                        <div class="alert alert-info">
                            <h5 class="mb-0">Rp <span id="total_display"><?= number_format($tagihan->getTotalTagihan(), 0, ',', '.') ?></span></h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
                        </button>
                        <a href="/SobatKost/index.php?url=tagihan/detail&id=<?= $tagihan->getIdTagihan() ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function updateTotal() {
    const biaya_sewa = parseInt(document.getElementById('total_biaya_sewa').value) || 0;
    const biaya_tambahan = parseInt(document.getElementById('biaya_tambahan').value) || 0;
    const total = biaya_sewa + biaya_tambahan;
    
    document.getElementById('total_display').textContent = new Intl.NumberFormat('id-ID').format(total);
}
</script>
