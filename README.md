# SobatKost

Platform manajemen properti digital untuk operasional rumah kost yang terintegrasi, mulai dari pengelolaan penghuni, inventaris kamar, pencatatan keuangan, hingga sistem komplain fasilitas.

**Deskripsi:** Platform Manajemen Kost Digital untuk Pengelolaan Penghuni, Inventaris, Keuangan, dan Komunikasi Berbasis Terpusat

**Tim Pengembang:**

* 2472007 - Richard Vincentius Christian Dinata
* 2472018 - Kevin Kornelius Martadinata
* 2472028 - Ferdi Gunawan

---

## Tutorial menjalankan aplikasi

**Clone project ke folder `xampp/htdocs`:**

1. Buka terminal atau Git Bash.
2. Masuk ke folder `xampp/htdocs`.
3. Jalankan perintah `git clone` menggunakan URL repository GitHub project.
4. Pastikan folder project tersimpan dengan nama `SobatKost`.

Contoh:

```bash
cd C:/xampp/htdocs
git clone https://github.com/kevin-krm/SobatKost.git
```

**Jalankan services XAMPP berikut:**

* Apache
* MySQL

**Import database yang berada di folder berikut ke MySQL:**

```bash
xampp/htdocs/SobatKost/database/SobatKost.sql
```

**Cara import database:**

1. Buka phpMyAdmin melalui XAMPP.
2. Pilih menu **Import**.
3. Upload file `SobatKost.sql` dari folder database project.
4. Klik **Import** untuk menjalankan import.

Setelah semua selesai, buka browser di alamat [http://localhost/SobatKost](http://localhost/SobatKost) untuk melihat hasilnya.

Untuk Login dengan Data Dummy yang ada, gunakan:
- richard@sobatkost.com (Owner)
- agus@sobatkost.com (Penjaga)
- siti@mail.com (Penyewa)

Silahkan reset password terlebih dahulu dan buat password baru untuk login

---

## Latar Belakang

* **Pencatatan Manual yang Tidak Efisien:** Banyak pengelolaan rumah kost masih dilakukan secara manual, seperti pencatatan penghuni, tagihan, dan status kamar, sehingga rentan terhadap kehilangan data dan kesalahan administrasi.
* **Kurangnya Transparansi Tagihan dan Pembayaran:** Penyewa sering kesulitan mengetahui detail tagihan serta status pembayaran, sementara pemilik harus melakukan pengecekan manual terhadap bukti transfer dan mutasi bank.
* **Sulitnya Pemantauan Ketersediaan Kamar:** Pemilik harus mengecek langsung kondisi fisik kamar untuk mengetahui apakah kamar kosong, terisi, atau sedang dalam perbaikan.
* **Pengelolaan Inventaris yang Tidak Terstruktur:** Fasilitas kamar seperti AC, kasur, dan lemari sering tidak terdokumentasi dengan baik, sehingga menyulitkan pemantauan kerusakan dan tanggung jawab penghuni.
* **Penanganan Komplain yang Tidak Terpantau:** Laporan kerusakan fasilitas sering disampaikan secara informal tanpa sistem pelacakan yang jelas, sehingga progres perbaikan sulit dipantau.
* **Komunikasi dan Aturan Kost Tidak Tersampaikan Merata:** Informasi penting seperti pengumuman pemadaman listrik atau aturan kost sering tidak diterima semua penghuni secara konsisten.

---

## Proposal

* **Solusi Utama:** SobatKost diusulkan sebagai platform terintegrasi yang menghubungkan Owner, Penjaga Kost, dan Penyewa dalam satu sistem terpusat untuk meningkatkan efisiensi operasional dan transparansi pengelolaan rumah kost.
* **Sistem Autentikasi Multi-User:** Memisahkan hak akses berdasarkan peran seperti Owner, Penjaga Kost, dan Penyewa untuk menjaga keamanan data serta membatasi akses sesuai tanggung jawab masing-masing.
* **Dashboard Visual Ketersediaan Kamar:** Menyediakan tampilan status kamar secara real-time agar pemilik dapat langsung melihat kamar kosong, terisi, atau dalam perbaikan tanpa pengecekan fisik.
* **Generator Tagihan Otomatis:** Sistem secara otomatis menghitung total biaya sewa beserta biaya tambahan untuk meminimalkan kesalahan perhitungan manual.
* **Pencatatan Pembayaran dan Reminder:** Penyewa dapat mengunggah bukti pembayaran, sementara sistem memberikan notifikasi jatuh tempo agar keterlambatan pembayaran dapat dikurangi.
* **Sistem Tiket Komplain Terstruktur:** Penyewa dapat melaporkan kerusakan fasilitas secara formal dan memantau status perbaikannya secara transparan melalui dashboard.
* **Broadcast dan E-Rules:** Menyediakan sistem pengumuman digital dan aturan kost berbasis online agar seluruh penghuni menerima informasi secara merata.

---

## Plan

* **Arsitektur dan Keamanan Sistem:** Membangun koneksi database terpusat menggunakan **Singleton Pattern** untuk memastikan efisiensi koneksi database dan mencegah pemborosan resource server pada proses CRUD yang intensif.
* **Generator Tagihan dan Tipe Sewa:** Mengimplementasikan **Factory Pattern** pada sistem billing untuk membuat objek tagihan berbeda berdasarkan tipe sewa seperti harian, bulanan, dan tahunan tanpa mengubah logika utama program.
* **Metode Pembayaran Fleksibel:** Menggunakan **Strategy Pattern** agar sistem dapat menangani berbagai metode pembayaran seperti transfer bank, e-wallet, dan tunai dengan logika validasi yang terpisah.
* **Sistem Komplain Real-Time:** Menerapkan **Observer Pattern** pada fitur tiket komplain sehingga ketika status komplain berubah, penyewa akan mendapatkan pembaruan otomatis tanpa notifikasi manual dari admin.
* **Penambahan Biaya Tambahan:** Menggunakan **Decorator Pattern** pada billing system untuk menambahkan biaya layanan tambahan seperti parkir atau fasilitas ekstra tanpa mengubah struktur kelas inti tagihan.
* **Integrasi Layanan Eksternal:** Mengimplementasikan **Adapter Pattern** untuk integrasi layanan notifikasi pihak ketiga seperti email atau API reminder agar format data internal dapat terhubung dengan sistem eksternal secara fleksibel.
* **Penerapan Clean Code dan MVC:** Seluruh sistem dibangun menggunakan arsitektur MVC dengan DAO untuk menjaga separation of concerns, maintainability, scalability, serta memastikan seluruh kode mudah dipahami dan dikembangkan di masa depan.
