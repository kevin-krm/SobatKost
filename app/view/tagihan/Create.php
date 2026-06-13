<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Generate Tagihan Baru</h2>
        <p class="text-muted">
            Buat tagihan baru berdasarkan kontrak sewa.
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
        <form action="/SobatKost/index.php?url=tagihan/store" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="id_kontrak" class="form-label fw-bold">Pilih Kontrak Sewa</label>
                        <select id="id_kontrak" name="id_kontrak" class="form-select" required onchange="updateKontrakInfo()">
                            <option value="">-- Pilih Kontrak --</option>
                            <?php if (!empty($kontrakList)): ?>
                                <?php foreach ($kontrakList as $k): ?>
                                    <option value="<?= $k->getIdKontrak() ?>" 
                                            data-tipe="<?= $k->getTipeSewa() ?>"
                                            data-harga="<?= $k->getHargaDasar() ?>"
                                            data-total-sewa="<?= TagihanFactory::hitungTotalBiayaSewa($k->getTipeSewa(), $k->getHargaDasar()) ?>"
                                            data-penyewa="<?= htmlspecialchars($k->getNamaLengkap()) ?>"
                                            data-kamar="<?= $k->getNomorKamar() ?>">
                                        <?= $k->getIdKontrak() ?> - <?= $k->getNamaLengkap() ?> (<?= $k->getNomorKamar() ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Informasi Kontrak</label>
                        <div class="alert alert-info" id="kontrakInfo">
                            <p class="mb-1"><strong>Tipe Sewa:</strong> <span id="tipe_sewa">-</span></p>
                            <p class="mb-1"><strong>Harga Dasar:</strong> Rp <span id="harga_dasar">0</span></p>
                            <p class="mb-1"><strong>Biaya Sewa Otomatis:</strong> Rp <span id="biaya_sewa_otomatis">0</span></p>
                            <p class="mb-0"><strong>Penyewa:</strong> <span id="nama_penyewa">-</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="biaya_tambahan" class="form-label fw-bold">Biaya Tambahan (Optional)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="biaya_tambahan" name="biaya_tambahan" class="form-control" value="0" min="0" step="1000">
                        </div>
                        <small class="text-muted">Biaya tambahan seperti denda, inventaris, atau layanan tambahan</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Tagihan (Preview)</label>
                        <div class="alert alert-success">
                            <p class="mb-1"><strong>Biaya Sewa:</strong> Rp <span id="preview_sewa">0</span></p>
                            <p class="mb-1"><strong>Biaya Tambahan:</strong> Rp <span id="preview_tambahan">0</span></p>
                            <p class="mb-0"><strong>Total:</strong> Rp <span id="preview_total">0</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i> Generate Tagihan
                        </button>
                        <a href="/SobatKost/index.php?url=tagihan" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function formatCurrency(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

function updateKontrakInfo() {
    const select = document.getElementById('id_kontrak');
    const option = select.options[select.selectedIndex];
    
    document.getElementById('tipe_sewa').textContent = option.dataset.tipe || '-';
    document.getElementById('harga_dasar').textContent = formatCurrency(option.dataset.harga || 0);
    document.getElementById('biaya_sewa_otomatis').textContent = formatCurrency(option.dataset.totalSewa || 0);
    document.getElementById('nama_penyewa').textContent = option.dataset.penyewa || '-';
    
    // Update preview
    const biayaSewa = parseInt(option.dataset.totalSewa) || 0;
    const tambahan = parseInt(document.getElementById('biaya_tambahan').value) || 0;
    const total = biayaSewa + tambahan;
    
    document.getElementById('preview_sewa').textContent = formatCurrency(biayaSewa);
    document.getElementById('preview_tambahan').textContent = formatCurrency(tambahan);
    document.getElementById('preview_total').textContent = formatCurrency(total);
}

document.getElementById('biaya_tambahan').addEventListener('change', updateKontrakInfo);
</script>
