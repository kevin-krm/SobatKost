<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Buat Tiket Komplain</h2>
        <p class="text-muted">Laporkan masalah fasilitas kost Anda secara detail.</p>
    </div>
    <a href="/SobatKost/index.php?url=user/komplain" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="/SobatKost/index.php?url=komplain/store" method="POST">

            <div class="mb-4">
                <label for="judul_masalah" class="form-label fw-bold">Topik / Judul Masalah</label>
                <input type="text" class="form-control" id="judul_masalah" name="judul_masalah" placeholder="Contoh: AC Kamar Bocor, Keran Air Mati" required>
            </div>

            <div class="mb-4">
                <label for="deskripsi" class="form-label fw-bold">Deskripsi Detail</label>

                <!-- Box Instruksi Formatting (Sesuai Permintaan Dosen) -->
                <div class="alert alert-info py-2 mb-3 border-0" style="font-size: 0.9rem; background-color: #e0f2fe;">
                    <i class="bi bi-info-circle-fill me-2 text-primary"></i>
                    <strong>Tips Penulisan:</strong> Gunakan tanda bintang (*) untuk membuat rincian poin (bullet points). <br>
                    <span class="ms-4 text-muted">Contoh: * Air menetes sangat deras dari unit indoor.</span>
                </div>

                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="6" placeholder="Jelaskan masalah Anda selengkap mungkin..." required></textarea>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-send-fill me-2"></i> Kirim Komplain
                </button>
            </div>
        </form>
    </div>
</div>