<?php
require_once APP_PATH . '/dao/KontraKDao.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Detail Tagihan</h2>
        <p class="text-muted">
            Informasi lengkap tagihan dan riwayat pembayaran Anda.
        </p>
    </div>
    <a href="/SobatKost/index.php?url=user/tagihan" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<!-- Informasi Tagihan -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
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
                        <h6 class="fw-bold">
                            <span class="badge bg-info">
                                <?php
                                $kontraKDao = new KontraKDao();
                                $kontrak = $kontraKDao->getKontrakById($tagihan->getIdKontrak());
                                echo htmlspecialchars($kontrak ? $kontrak->getTipeSewa() : '-');
                                ?>
                            </span>
                        </h6>
                    </div>
                </div>

                <hr>

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

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Tanggal Jatuh Tempo</p>
                        <h6 class="fw-bold">
                            <?= date('d/m/Y', strtotime($tagihan->getTanggalJatuhTempo())) ?>
                            <?php if ($tagihan->isOverdue()): ?>
                                <span class="badge bg-danger ms-2">OVERDUE</span>
                            <?php endif; ?>
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Status Tagihan</p>
                        <h6 class="fw-bold">
                            <span class="badge <?= $tagihan->getStatusTagihan() === 'Lunas' ? 'bg-success' : 'bg-warning' ?>">
                                <?= $tagihan->getStatusTagihan() ?>
                            </span>
                        </h6>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <p class="text-muted small mb-1">Total Tagihan</p>
                        <h4 class="fw-bold text-primary">Rp <?= number_format($tagihan->getTotalTagihan(), 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <?php if ($tagihan->getStatusTagihan() === 'Belum Lunas'): ?>
                    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#payModal">
                        <i class="bi bi-credit-card-2-front me-2"></i> Bayar Sekarang
                    </button>
                <?php else: ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        Tagihan ini telah dilunasi.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informasi Tambahan</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-1">Dibuat Pada</p>
                <p class="mb-3"><?= date('d/m/Y H:i', strtotime($tagihan->getCreatedAt())) ?></p>

                <p class="text-muted small mb-1">Diperbarui Pada</p>
                <p><?= date('d/m/Y H:i', strtotime($tagihan->getUpdatedAt())) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembayaran -->
<div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payModalLabel">Pembayaran Tagihan - <?= htmlspecialchars($tagihan->getIdTagihan()) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentForm" action="/SobatKost/index.php?url=pembayaran/store" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_tagihan" value="<?= htmlspecialchars($tagihan->getIdTagihan()) ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Metode Pembayaran</label>
                        <select id="metodeSelect" name="metode_pembayaran" class="form-select" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="Transfer">Transfer</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Tunai">Tunai</option>
                        </select>
                    </div>

                    <div id="methodFields">
                        <!-- Dynamic fields inserted by JS -->
                    </div>

                    <div class="alert alert-info mt-3">
                        Total yang harus dibayar: <strong>Rp <?= number_format($tagihan->getTotalTagihan(), 0, ',', '.') ?></strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function(){
        const metodeSelect = document.getElementById('metodeSelect');
        const methodFields = document.getElementById('methodFields');

        function clearFields(){
            methodFields.innerHTML = '';
        }

        function createInput(name, label, type='text', required=true){
            const div = document.createElement('div');
            div.className = 'mb-3';
            const lab = document.createElement('label');
            lab.className = 'form-label';
            lab.textContent = label;
            const inp = document.createElement('input');
            inp.className = 'form-control';
            inp.name = name;
            inp.type = type;
            if(required) inp.required = true;
            div.appendChild(lab);
            div.appendChild(inp);
            return div;
        }

        function createSelect(name, label, options, required=true){
            const div = document.createElement('div');
            div.className = 'mb-3';
            const lab = document.createElement('label');
            lab.className = 'form-label';
            lab.textContent = label;
            const sel = document.createElement('select');
            sel.className = 'form-select';
            sel.name = name;
            if(required) sel.required = true;
            const empty = document.createElement('option'); empty.value = ''; empty.textContent = '-- Pilih --'; sel.appendChild(empty);
            options.forEach(opt => {
                const o = document.createElement('option'); o.value = opt; o.textContent = opt; sel.appendChild(o);
            });
            div.appendChild(lab);
            div.appendChild(sel);
            return div;
        }

        metodeSelect && metodeSelect.addEventListener('change', function(){
            clearFields();
            const v = this.value;
            if(v === 'Transfer'){
                methodFields.appendChild(createInput('bank_tujuan', 'Nama Bank'));
                methodFields.appendChild(createInput('no_rekening', 'Nomor Rekening', 'text'));
                methodFields.appendChild(createInput('nama_pemilik', 'Nama Pemilik Rekening'));
                // optional bukti
                const divFile = document.createElement('div');
                divFile.className = 'mb-3';
                const lab = document.createElement('label'); lab.className = 'form-label'; lab.textContent = 'Upload Bukti (opsional)';
                const inp = document.createElement('input'); inp.type = 'file'; inp.className = 'form-control'; inp.name = 'bukti_pembayaran';
                divFile.appendChild(lab); divFile.appendChild(inp);
                methodFields.appendChild(divFile);
            } else if(v === 'E-Wallet'){
                const wallets = ['GCash','PayMaya','OVO','Dana','LinkAja'];
                methodFields.appendChild(createSelect('jenis_ewallet', 'Jenis E-Wallet', wallets));
                methodFields.appendChild(createInput('nomor_akun', 'Nomor Akun', 'text'));
                methodFields.appendChild(createInput('nama_pemilik', 'Nama Pemilik'));
                const divFile = document.createElement('div');
                divFile.className = 'mb-3';
                const lab = document.createElement('label'); lab.className = 'form-label'; lab.textContent = 'Upload Bukti (opsional)';
                const inp = document.createElement('input'); inp.type = 'file'; inp.className = 'form-control'; inp.name = 'bukti_pembayaran';
                divFile.appendChild(lab); divFile.appendChild(inp);
                methodFields.appendChild(divFile);
            } else if(v === 'Tunai'){
                methodFields.appendChild(createInput('nama_penerima', 'Nama Penerima'));
                methodFields.appendChild(createInput('lokasi_pembayaran', 'Lokasi Pembayaran'));
            }
        });
    })();
</script>

<!-- Riwayat Pembayaran -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">Riwayat Pembayaran</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID PEMBAYARAN</th>
                        <th>METODE</th>
                        <th>TANGGAL</th>
                        <th>BUKTI</th>
                        <th class="text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pembayaranList)): ?>
                        <tr>
                            <td colspan="5" class="text-center p-4 text-muted">
                                Belum ada riwayat pembayaran
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pembayaranList as $p): ?>
                            <tr>
                                <td>
                                    <a href="/SobatKost/index.php?url=pembayaran/detail&id=<?= $p->getIdPembayaran() ?>">
                                        <?= htmlspecialchars($p->getIdPembayaran()) ?>
                                    </a>
                                </td>
                                <td>
                                    <i class="bi <?= $p->getMetodeIcon() ?> me-2"></i>
                                    <?= htmlspecialchars($p->getMetodePembayaran()) ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($p->getTanggalBayar())) ?></td>
                                <td>
                                    <?php if ($p->getBuktiPembayaran()): ?>
                                        <a href="<?= htmlspecialchars($p->getBuktiPembayaran()) ?>" target="_blank" class="btn btn-sm btn-info">
                                            <i class="bi bi-file-earmark"></i> Lihat
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $p->getStatusVerifikasi() === 'Berhasil' ? 'bg-success' : ($p->getStatusVerifikasi() === 'Ditolak' ? 'bg-danger' : 'bg-warning') ?>">
                                        <?= $p->getStatusVerifikasi() ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
