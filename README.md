# SobatKost

Platform manajemen properti digital untuk operasional rumah kost yang terintegrasi, mulai dari pengelolaan penghuni, inventaris kamar, pencatatan keuangan, hingga sistem komplain fasilitas.

**Deskripsi:** Platform Manajemen Kost Digital untuk Pengelolaan Penghuni, Inventaris, Keuangan, dan Komunikasi Berbasis Terpusat

**Tim Pengembang:**

* 2472007 - Richard Vincentius Christian Dinata
* 2472018 - Kevin Kornelius Martadinata
* 2472028 - Ferdi Gunawan

---

## Tutorial menjalankan aplikasi

**Sebelum menjalankan aplikasi, pastikan perangkat sudah memiliki:**

* XAMPP (Apache dan MySQL)
* Git
* Browser (Google Chrome/Microsoft Edge) 
* PHP sesuai dengan kebutuhan project 

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

**Buka XAMPP Control Panel, kemudian aktifkan::**

* Apache
* MySQL

Pastikan kedua service berjalan tanpa error. 

**Import database yang berada di folder berikut ke MySQL:**

```bash
xampp/htdocs/SobatKost/database/SobatKost.sql
```

**Cara import database:**

1.  Buka browser dan akses: http://localhost/phpmyadmin
2. Pilih menu **Import**
3. Upload file `SobatKost.sql` dari folder database project
4. Klik **Import** untuk menjalankan import

Setelah semua selesai, buka browser di alamat [http://localhost/SobatKost](http://localhost/SobatKost) untuk melihat hasilnya.

**Jika aplikasi tidak dapat dibuka:**
1. Pastikan Apache dan MySQL aktif.
2. Pastikan folder project berada di:  xampp/htdocs/SobatKost
3. Pastikan database sudah berhasil di-import.
4. Periksa konfigurasi koneksi database pada file konfigurasi aplikasi.


## Guideline Pemakaian (User Guide)

Aplikasi SobatKost dirancang dengan sistem autentikasi multi-user yang memisahkan hak akses dan fungsionalitas berdasarkan peran (role) pengguna. Berikut adalah alur penggunaan untuk masing-masing peran:

### A. Alur Penggunaan Bagi Owner & Penjaga Kost (Dashboard Admin)
Peran **Owner** dan **Penjaga** memiliki akses kontrol penuh terhadap operasional kost melalui panel admin pusat.

1. **Login & Dashboard Utama**:
   * Masuk menggunakan akun Owner atau Penjaga.
   * Lihat ringkasan visual real-time status kamar (terisi, kosong, perbaikan) serta ringkasan keuangan masuk/keluar.
2. **Manajemen Kamar & Inventaris**:
   * Masuk ke menu **Kamar** untuk menambah kamar baru, menentukan tipe/harga sewa, dan melihat ketersediaan.
   * Masuk ke menu **Inventaris** untuk menginput fasilitas kamar (AC, kasur, lemari) beserta status kelayakannya.
3. **Pendaftaran Penghuni & Kontrak**:
   * Daftarkan akun penyewa baru di menu **Pengguna**.
   * Buat kontrak sewa di menu **Kontrak** dengan menghubungkan data penyewa, kamar yang disewa, dan tanggal sewa.
4. **Billing & Tagihan**:
   * Membuat tagihan di menu **Tagihan** berdasarkan pilihan kontrak sewa yang didaftarkan.
   * Mengirimkan notifikasi jatuh tempo secara otomatis kepada penyewa.
5. **Verifikasi Pembayaran**:
   * Ketika penyewa mengunggah bukti pembayaran, periksa unggahan tersebut di menu **Pembayaran**.
   * Lakukan validasi bukti transfer secara manual, lalu klik **Verifikasi** jika sah, atau **Tolak** jika tidak sesuai.
6. **Laporan Keuangan**:
   * Pantau grafik pemasukan sewa dan input pengeluaran operasional (seperti biaya listrik, perbaikan gedung) di menu **Keuangan** untuk melacak profitabilitas secara otomatis.
7. **Broadcast Pengumuman & Aturan**:
   * Posting pengumuman mendesak (seperti pemadaman air/listrik) di menu **Pengumuman**.
   * Kelola aturan tinggal di kost di menu **Aturan** agar dapat diakses online oleh seluruh penyewa.
8. **Tindak Lanjut Komplain**:
   * Buka menu **Komplain** untuk melihat laporan kerusakan dari penyewa. Anda dapat melihat status tiket komplain dari *Diajukan* -> *Diproses* -> *Selesai* seiring berjalannya perbaikan.

### B. Alur Penggunaan Bagi Penyewa Kost (Portal Penyewa)
Peran **Penyewa** memiliki akses mandiri melalui portal ramah pengguna untuk mengurus tagihan, pembayaran, dan komplain tanpa harus menghubungi pengelola secara langsung.

1. **Dashboard Penyewa**:
   * Masuk menggunakan akun Penyewa.
   * Dashboard menyajikan pengumuman terbaru.
2. **Melihat Tagihan & Mengunggah Pembayaran**:
   * Pilih menu **Tagihan** untuk melihat daftar tagihan aktif dan detail nominalnya.
   * Klik **Bayar / Upload Bukti**, unggah file gambar bukti transfer bank Anda, dan kirimkan. Status tagihan akan berubah menjadi *Menunggu Verifikasi*.
3. **Mengajukan Tiket Komplain Kerusakan**:
   * Jika ada fasilitas kamar/kost yang rusak, masuk ke menu **Komplain**.
   * Klik **Buat Komplain**, isi formulir dengan deskripsi kerusakan fasilitas, lalu kirimkan.
   * Anda dapat memberikan feedback progres perbaikan dari kamar anda kepada pihak kost secara langsung pada tabel riwayat komplain Anda.
4. **Pengaturan Akun & Keamanan**:
   * Masuk ke menu **Profil** untuk mengubah alamat e-mail aktif atau melakukan reset password demi privasi akun Anda.

---

## Data Akun Demo (Data Dummy)

Untuk mempermudah pengujian seluruh fitur di atas, Anda dapat masuk menggunakan data akun demo berikut:

* **Owner (Pemilik Kost)**:
  * **Email**: `richard@sobatkost.com`
* **Penjaga Kost**:
  * **Email**: `agus@sobatkost.com`
* **Penyewa Kost (Penyewa Kamar)**:
  * **Email**: `siti@mail.com`

> [!IMPORTANT]
> Untuk alasan keamanan autentikasi, silakan gunakan fitur **Reset Password / Lupa Password** pada halaman login terlebih dahulu untuk membuat kata sandi baru untuk akun dummy sebelum masuk ke sistem.

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
