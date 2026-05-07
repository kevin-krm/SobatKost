<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2>Buat Tiket Komplain</h2>
            <p>Silakan isi detail kerusakan fasilitas yang Anda alami.</p>
        </div>
        <a href="/SobatKost/komplain" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="/SobatKost/komplain/store" method="POST">
                <div class="mb-3">
                    <label for="judul_masalah" class="form-label">Judul Masalah</label>
                    <input type="text" class="form-control" id="judul_masalah" name="judul_masalah" placeholder="Contoh: AC Kamar Bocor" required>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi Detail</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan secara detail kerusakan yang terjadi..." required></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Kirim Komplain
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>