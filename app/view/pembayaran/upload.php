<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Upload Bukti Pembayaran</h2>
        <p class="text-muted">
            Unggah bukti transfer atau konfirmasi pembayaran Anda.
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

<div class="row">
    <div class="col-md-8">
        <!-- Info Tagihan -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informasi Tagihan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">ID Tagihan</p>
                        <h6 class="fw-bold"><?= htmlspecialchars($tagihan->getIdTagihan()) ?></h6>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Tipe Sewa</p>
                        <h6 class="fw-bold"><?= htmlspecialchars($tagihan->getTipeSewa() ?? '-') ?></h6>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Biaya Sewa</p>
                        <h6 class="fw-bold">Rp <?= number_format($tagihan->getTotalBiayaSewa(), 0, ',', '.') ?></h6>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Biaya Tambahan</p>
                        <h6 class="fw-bold">Rp <?= number_format($tagihan->getBiayaTambahan(), 0, ',', '.') ?></h6>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <p class="text-muted small mb-1">Total Yang Harus Dibayar</p>
                        <h4 class="fw-bold text-primary">Rp <?= number_format($tagihan->getTotalTagihan(), 0, ',', '.') ?></h4>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <p class="text-muted small mb-1">Tanggal Jatuh Tempo</p>
                        <h6 class="fw-bold"><?= date('d/m/Y', strtotime($tagihan->getTanggalJatuhTempo())) ?></h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Upload Pembayaran -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Pilih Metode Pembayaran</h5>
            </div>
            <div class="card-body">
                <form action="/SobatKost/index.php?url=pembayaran/store" method="POST" enctype="multipart/form-data" id="paymentForm">
                    <input type="hidden" name="id_tagihan" value="<?= $tagihan->getIdTagihan() ?>">

                    <div class="mb-4">
                        <label class="form-label fw-bold">Metode Pembayaran</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="metode_transfer" value="Transfer" onchange="updatePaymentForm()" required>
                                    <label class="form-check-label" for="metode_transfer">
                                        <strong><i class="bi bi-bank me-2"></i>Transfer Bank</strong>
                                        <br>
                                        <small class="text-muted">Transfer ke rekening kost</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="metode_ewallet" value="E-Wallet" onchange="updatePaymentForm()" required>
                                    <label class="form-check-label" for="metode_ewallet">
                                        <strong><i class="bi bi-wallet2 me-2"></i>E-Wallet</strong>
                                        <br>
                                        <small class="text-muted">GCash, OVO, Dana, dll</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="metode_tunai" value="Tunai" onchange="updatePaymentForm()" required>
                                    <label class="form-check-label" for="metode_tunai">
                                        <strong><i class="bi bi-cash-coin me-2"></i>Tunai</strong>
                                        <br>
                                        <small class="text-muted">Pembayaran langsung</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form untuk Transfer -->
                    <div id="transfer_form" class="payment-method-form d-none">
                        <div class="alert alert-info">
                            <h6 class="mb-2"><i class="bi bi-info-circle me-2"></i>Rekening Bank Kost</h6>
                            <p class="mb-0">Bank BCA: 1234567890 a/n SobatKost</p>
                        </div>

                        <div class="mb-3">
                            <label for="bank_tujuan" class="form-label fw-bold">Bank Tujuan</label>
                            <input type="text" id="bank_tujuan" name="bank_tujuan" class="form-control" placeholder="Misal: BCA, Mandiri, BNI">
                        </div>

                        <div class="mb-3">
                            <label for="no_rekening" class="form-label fw-bold">Nomor Rekening</label>
                            <input type="text" id="no_rekening" name="no_rekening" class="form-control" placeholder="Nomor rekening tujuan">
                        </div>

                        <div class="mb-3">
                            <label for="nama_pemilik_transfer" class="form-label fw-bold">Nama Pemilik Rekening</label>
                            <input type="text" id="nama_pemilik_transfer" name="nama_pemilik" class="form-control" placeholder="Nama sesuai rekening">
                        </div>

                        <div class="mb-3">
                            <label for="bukti_transfer" class="form-label fw-bold">Unggah Bukti Transfer</label>
                            <input type="file" id="bukti_transfer" name="bukti_pembayaran" class="form-control" accept="image/*,.pdf" required>
                            <small class="text-muted">Format: JPG, PNG, atau PDF (Max 5MB)</small>
                        </div>
                    </div>

                    <!-- Form untuk E-Wallet -->
                    <div id="ewallet_form" class="payment-method-form d-none">
                        <div class="mb-3">
                            <label for="jenis_ewallet" class="form-label fw-bold">Pilih E-Wallet</label>
                            <select id="jenis_ewallet" name="jenis_ewallet" class="form-select">
                                <option value="">-- Pilih E-Wallet --</option>
                                <option value="GCash">GCash</option>
                                <option value="PayMaya">PayMaya</option>
                                <option value="OVO">OVO</option>
                                <option value="Dana">Dana</option>
                                <option value="LinkAja">LinkAja</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nomor_akun_ewallet" class="form-label fw-bold">Nomor Akun E-Wallet</label>
                            <input type="text" id="nomor_akun_ewallet" name="nomor_akun" class="form-control" placeholder="Nomor HP atau akun Anda">
                        </div>

                        <div class="mb-3">
                            <label for="nama_pemilik_ewallet" class="form-label fw-bold">Nama Pemilik</label>
                            <input type="text" id="nama_pemilik_ewallet" name="nama_pemilik" class="form-control" placeholder="Nama Anda">
                        </div>

                        <div class="mb-3">
                            <label for="bukti_ewallet" class="form-label fw-bold">Unggah Bukti Pembayaran</label>
                            <input type="file" id="bukti_ewallet" name="bukti_pembayaran" class="form-control" accept="image/*,.pdf" required>
                            <small class="text-muted">Format: JPG, PNG, atau PDF (Max 5MB)</small>
                        </div>
                    </div>

                    <!-- Form untuk Tunai -->
                    <div id="tunai_form" class="payment-method-form d-none">
                        <div class="alert alert-warning">
                            <h6 class="mb-2"><i class="bi bi-exclamation-circle me-2"></i>Pembayaran Tunai</h6>
                            <p class="mb-0">Silakan membayar ke kantor kost secara langsung atau kepada admin yang ditunjuk</p>
                        </div>

                        <div class="mb-3">
                            <label for="nama_penerima" class="form-label fw-bold">Nama Penerima</label>
                            <input type="text" id="nama_penerima" name="nama_penerima" class="form-control" placeholder="Nama admin yang menerima">
                        </div>

                        <div class="mb-3">
                            <label for="lokasi_pembayaran" class="form-label fw-bold">Lokasi Pembayaran</label>
                            <input type="text" id="lokasi_pembayaran" name="lokasi_pembayaran" class="form-control" placeholder="Lokasi pembayaran (kantor, rumah, dll)">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i> Kirim Pembayaran
                        </button>
                        <a href="/SobatKost/index.php?url=tagihan" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Panduan Pembayaran -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Panduan Pembayaran</h5>
            </div>
            <div class="card-body">
                <h6 class="fw-bold mb-2"><i class="bi bi-info-circle text-info me-2"></i>Transfer Bank</h6>
                <p class="small text-muted mb-3">
                    1. Transfer ke rekening kost<br>
                    2. Unggah bukti transfer<br>
                    3. Tunggu verifikasi admin
                </p>

                <hr>

                <h6 class="fw-bold mb-2"><i class="bi bi-wallet2 text-success me-2"></i>E-Wallet</h6>
                <p class="small text-muted mb-3">
                    1. Pilih jenis e-wallet<br>
                    2. Transfer ke nomor e-wallet kost<br>
                    3. Unggah bukti transfer
                </p>

                <hr>

                <h6 class="fw-bold mb-2"><i class="bi bi-cash-coin text-warning me-2"></i>Tunai</h6>
                <p class="small text-muted">
                    1. Bayar langsung ke kantor<br>
                    2. Catat waktu dan penerima<br>
                    3. Laporkan pembayaran di sini
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function updatePaymentForm() {
    const method = document.querySelector('input[name="metode_pembayaran"]:checked').value;
    
    // Hide all forms
    document.querySelectorAll('.payment-method-form').forEach(form => {
        form.classList.add('d-none');
    });
    
    // Show selected form
    if (method === 'Transfer') {
        document.getElementById('transfer_form').classList.remove('d-none');
        // Remove required from other inputs
        document.querySelectorAll('#ewallet_form input, #tunai_form input').forEach(input => {
            input.removeAttribute('required');
        });
        document.querySelectorAll('#transfer_form input, #transfer_form select').forEach(input => {
            if (input.name !== 'bukti_pembayaran') input.required = true;
        });
    } else if (method === 'E-Wallet') {
        document.getElementById('ewallet_form').classList.remove('d-none');
        document.querySelectorAll('#transfer_form input, #tunai_form input').forEach(input => {
            input.removeAttribute('required');
        });
        document.querySelectorAll('#ewallet_form input, #ewallet_form select').forEach(input => {
            if (input.name !== 'bukti_pembayaran') input.required = true;
        });
    } else if (method === 'Tunai') {
        document.getElementById('tunai_form').classList.remove('d-none');
        document.querySelectorAll('#transfer_form input, #ewallet_form input').forEach(input => {
            input.removeAttribute('required');
        });
        document.querySelectorAll('#tunai_form input').forEach(input => {
            input.required = true;
        });
    }
}
</script>
