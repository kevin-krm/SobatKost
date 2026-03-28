<script src="/SobatKost/public/js/admin.js"></script>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="bi bi-house-add"></i>
                    Tambah Kamar
                </h4>

                <a href="/SobatKost/kamar" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>Form Data Kamar</strong>
                </div>

                <div class="card-body">
                    <form method="POST" action="/SobatKost/kamar/store">

                        <div class="mb-3">
                            <label class="form-label">Nomor Kamar</label>
                            <input type="text" name="nomor_kamar" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipe Kamar</label>
                            <select name="tipe_kamar" class="form-select" required>
                                <option value="">-- Pilih Tipe Kamar --</option>
                                <option value="AC">AC</option>
                                <option value="Standard">Standard</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga Dasar</label>
                            <input type="number" name="harga_dasar" class="form-control">
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