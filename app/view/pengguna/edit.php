<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="bi bi-pencil-square"></i>
                    Edit Data Pengguna
                </h4>
                <a href="/SobatKost/pengguna" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">
                    <strong>Form Edit Pengguna</strong>
                </div>
                <div class="card-body">

                    <form action="/SobatKost/pengguna/update/<?= $pengguna['id_pengguna']; ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">ID Pengguna</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    value="<?= $pengguna['id_pengguna']; ?>"
                                    readonly
                            >
                        </div>
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">
                                Nama Lengkap
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="nama_lengkap"
                                name="nama_lengkap"
                                value="<?= $pengguna['nama_lengkap']; ?>"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text"
                                   class="form-control"
                                   id="nomor_telepon"
                                   name="nomor_telepon"
                                   value="<?= $pengguna['nomor_telepon']; ?>"
                                   required
                                   >
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email
                            </label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                value="<?= $pengguna['email']; ?>"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label for="kata_sandi" class="form-label">Password</label>
                            <input type="text"
                                   class="form-control"
                                   id="kata_sandi"
                                   name="kata_sandi"
                                   value="<?= $pengguna['kata_sandi']; ?>"
                                   readonly
                            >
                        </div>
                        <div class="mb-4">
                            <label for="kata_sandi" class="form-label">
                                Password Baru (Opsional)
                            </label>
                            <input
                                    type="password"
                                    class="form-control"
                                    id="kata_sandi"
                                    name="kata_sandi"
                                    placeholder="Kosongkan jika tidak ingin mengganti"
                            >
                        </div>
                        <div class="mb-3">
                            <label for="id_peran" class="form-label">
                                Role
                            </label>
                            <select
                                class="form-select"
                                id="id_peran"
                                name="id_peran"
                                required >
                                <option value="1" <?= $pengguna['id_peran'] == 1 ? 'selected' : ''; ?>>
                                    Owner
                                </option>
                                <option value="2" <?= $pengguna['id_peran'] == 2 ? 'selected' : ''; ?>>
                                    Penjaga
                                </option>
                                <option value="3" <?= $pengguna['id_peran'] == 3 ? 'selected' : ''; ?>>
                                    Penyewa
                                </option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i>
                                Update Data Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>