<div class="container mt-4">

    <div class="row justify-content-center">

        <div class="col-md-7 col-lg-6">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="bi bi-person-plus"></i>
                    Tambah Penghuni Baru
                </h4>

                <a href="/SobatKost/penghuni" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>Form Data Penghuni</strong>
                </div>

                <div class="card-body">
                    <form action="/SobatKost/penghuni/store" method="POST">
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">
                                Nama Lengkap
                            </label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="nama_lengkap"
                                    name="nama_lengkap"
                                    placeholder="Masukkan nama lengkap"
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
                                    placeholder="contoh@email.com"
                                    required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="id_peran" class="form-label">
                                Peran
                            </label>

                            <select
                                    class="form-select"
                                    id="id_peran"
                                    name="id_peran"
                                    required
                            >
                                <option value="" selected disabled>
                                    Pilih Peran...
                                </option>

                                <option value="1">Owner</option>
                                <option value="2">Penjaga</option>
                                <option value="3">Penyewa</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="kata_sandi" class="form-label">
                                Kata Sandi (Default)
                            </label>

                            <input
                                    type="password"
                                    class="form-control"
                                    id="kata_sandi"
                                    name="kata_sandi"
                                    placeholder="Masukkan kata sandi"
                                    required
                            >
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i>
                                Simpan Data Penghuni
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>