<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="bi bi-pencil-square"></i>
                    Edit Data Pengguna
                </h4>
                <a href="/SobatKost/index.php?url=pengguna/detail&id=<?= $pengguna->getId(); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>Form Edit Pengguna</strong>
                </div>
                <div class="card-body">
                    <form method="POST" action="/SobatKost/index.php?url=pengguna/update&id=<?= $pengguna->getId(); ?>" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">ID Pengguna</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    value="<?= htmlspecialchars($pengguna->getId()); ?>"
                                    readonly
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    name="nama_lengkap"
                                    value="<?= htmlspecialchars($pengguna->getNamaLengkap()); ?>"
                                    required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    name="nomor_telepon"
                                    value="<?= htmlspecialchars($pengguna->getNomorTelepon()); ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input
                                    type="email"
                                    class="form-control bg-light"
                                    name="email"
                                    value="<?= htmlspecialchars($pengguna->getEmail()); ?>"
                                    readonly
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="id_peran">
                                <option value="1" <?= ($pengguna->getIdPeran() == 1) ? 'selected' : '' ?>>Owner</option>
                                <option value="2" <?= ($pengguna->getIdPeran() == 2) ? 'selected' : '' ?>>Penjaga</option>
                                <option value="3" <?= ($pengguna->getIdPeran() == 3) ? 'selected' : '' ?>>Penyewa</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status Aktif</label>
                            <select class="form-select" name="status_aktif">
                                <option value="aktif" <?= ($pengguna->getStatusAktif() === 'aktif') ? 'selected' : '' ?>>Aktif</option>
                                <option value="nonaktif" <?= ($pengguna->getStatusAktif() === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto KTP Saat Ini</label>
                            <br>

                            <?php if (!empty($pengguna->getFotoKtp())) : ?>

                                <img
                                <img src="/SobatKost/<?= $pengguna->getFotoKtp() ?>"
                                        id="ktpPreview"
                                        class="img-fluid rounded shadow-sm mb-2"
                                        style="max-height:200px;"
                                >

                            <?php else : ?>

                                <p class="text-muted">Belum ada foto KTP</p>

                                <img
                                        id="ktpPreview"
                                        src=""
                                        class="img-fluid rounded shadow-sm"
                                        style="max-height:200px; display:none;"
                                >

                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ganti Foto KTP</label>

                            <input
                                    type="file"
                                    name="foto_ktp"
                                    class="form-control"
                                    accept="image/png, image/jpeg"
                                    onchange="previewKTP(event)"
                            >

                            <small class="text-muted">
                                Kosongkan jika tidak ingin mengganti foto (Format: JPG, JPEG, PNG)
                            </small>
                        </div>

                        <button class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>