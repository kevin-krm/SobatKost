# Progress Report — Week 7

**Project:** SobatKost

**Anggota:**
* 2472007 — Richard Vincentius Christian Dinata
* 2472018 — Kevin Kornelius Martadinata
* 2472028 — Ferdi Gunawan

---

## Design Patterns

### Decorator Pattern
Dilakukan perbaikan dan penyempurnaan pada *Decorator Pattern* di modul Tagihan. Pola ini difokuskan agar sistem mampu menambahkan ekstra biaya (seperti tambahan parkir, AC, dsb.) ke dalam struk tagihan pokok secara dinamis saat *runtime* tanpa perlu merombak kelas atau kode utama dari tagihan itu sendiri.

---

## Implemented Features — Week 7

* Perbaikan implementasi fitur peringatan jatuh tempo tagihan di UI penyewa
* Membatasi akses unggah bukti pembayaran agar hanya dapat diinisiasi oleh Penyewa.
* Memperbaiki validasi peran sehingga aksi verifikasi (terima/tolak pembayaran) tampil dengan benar untuk Owner dan Penjaga.
* Menambahkan validasi anti-double payment: cegah unggah bukti baru jika status pembayaran sebelumnya masih "Proses" atau "Berhasil".
* Membuka kembali akses unggah pembayaran jika riwayat pembayaran sebelumnya "Ditolak" oleh admin.
* Mengotomatisasi perubahan status tagihan menjadi "Lunas" ketika pembayaran diverifikasi sukses.
* Memperbaiki bug bentrok variabel (scope collision) di sidebar yang menyebabkan detail tagihan menampilkan data yang salah.
* Perbaikan pada error message di bagian tagihan (user)
* Perbaikan pada kode dummy agar data yang ditampilkan lebih realistis
* Perombakan struktur dokumentasi secara masif dengan menyuntikkan komentar berstandar *"human-readable"* di level fungsi pada 100% *file* DAO dan Controller untuk menunjang presentasi.


### Notes

Untuk menjalankan project:

1. Clone project ke folder `xampp/htdocs`
2. Jalankan services XAMPP:

   * Apache
   * MySQL
3. Import database dari:

```bash
xampp/htdocs/SobatKost/database/SobatKost.sql
```

4. Akses aplikasi melalui browser:

```text
http://localhost/SobatKost
```

Untuk Login dengan Data Dummy yang ada, gunakan:
- richard@sobatkost.com (Owner)
- agus@sobatkost.com (Penjaga)
- siti@mail.com (Penyewa)

Silahkan reset password terlebih dahulu dan buat password baru untuk login

---

## Current Progress

> Bagian ini diisi berdasarkan progress implementasi real-time minggu ini.

| NRP     | Nama                                | Task                                                                               | Status      |
| ------- | ----------------------------------- | ---------------------------------------------------------------------------------- | ----------- |
| 2472018 | Kevin Kornelius Martadinata         | Arsitektur & Keamanan: Autentikasi, Profil Penghuni, Dashboard Visual, Log Riwayat | Done |
| 2472028 | Ferdi Gunawan                       | Logika Bisnis & Keuangan: Billing System, Pembayaran, Reminder, Laporan Keuangan   | Done |
| 2472007 | Richard Vincentius Christian Dinata | Komunikasi & Manajemen Aset: Inventaris, Komplain, Broadcast, Aturan Kost          | Done |

**Status:** `Not Started` · `In Progress` · `Done`

### Detail Progress Minggu Ini

| Divisi                             | Total Progress |
| ---------------------------------- |---------------:|
| Arsitektur & Keamanan              |         33.32% |
| Logika Bisnis & Keuangan           |         33.32% |
| Komunikasi & Manajemen Aset        |         33.32% |
| **Total Implementasi Keseluruhan** |    **100,00%** |

### Progress Fitur Utama

* Fitur 1 — Autentikasi/Role → 100%
* Fitur 2 — Manajemen Inventaris → 100%
* Fitur 3 — Profil Penghuni → 100%
* Fitur 4 — Dashboard Visual → 100%
* Fitur 5 — Generator Tagihan → 100%
* Fitur 6 - Pencatatan Pembayaran → 100%
* Fitur 7 - Notifikasi Jatuh Tempo → 100%
* Fitur 8 — Sistem Tiket Komplain → 100%
* Fitur 9 — Log Riwayat → 100%
* Fitur 10 — Laporan Keuangan → 100%
* Fitur 11 — Broadcast Pengumuman → 100%
* Fitur 12 — Manajemen Aturan → 100%

---
