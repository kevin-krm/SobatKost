<script src="/SobatKost/public/js/admin.js"></script>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="bi bi-person-plus"></i>
                    Tambah Pengguna Baru
                </h4>

                <a href="/SobatKost/pengguna" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>Form Data Pengguna</strong>
                </div>

                <div class="card-body">
                    <form method="POST" action="/SobatKost/pengguna/store" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="text" name="kata_sandi" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="id_peran" class="form-control">
                                <option value="1">Owner</option>
                                <option value="2">Penjaga</option>
                                <option value="3">Penyewa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Foto KTP</label>
                            <input
                                    type="file"
                                    name="foto_ktp"
                                    class="form-control"
                                    accept="image/png, image/jpeg"
                                    onchange="previewKTP(event)">
                        </div>
                        <div class="mb-3 text-center">
                            <img
                                    id="ktpPreview"
                                    src=""
                                    class="img-fluid rounded shadow-sm"
                                    style="max-height:200px; display:none;">
                        </div>
                        <button class="btn btn-primary">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>