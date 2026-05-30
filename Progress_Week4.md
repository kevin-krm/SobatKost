# Progress Report — Week 4

**Project:** SobatKost

**Anggota:**
* 2472007 — Richard Vincentius Christian Dinata
* 2472018 — Kevin Kornelius Martadinata
* 2472028 — Ferdi Gunawan

---

## Design Patterns

### 1. Observer Pattern (Behavioral) — Sistem Tiket Komplain

Pada progress minggu ini, implementasi pola desain utama difokuskan pada pembaruan alur **Sistem Tiket Komplain**. Kami menerapkan **Observer Pattern** di mana penyewa (User) bertindak sebagai *Observer*.

Melalui pola ini, ketika terjadi pembaruan status komplain (misalnya dari "Menunggu" menjadi "Diproses" atau "Selesai"), sistem secara otomatis men-*trigger* pembaruan atau notifikasi ke *dashboard* penyewa. Implementasi ini terlihat jelas pada pemisahan logika hak akses di mana perubahan status tiket langsung diikat (*attach*) ke `DashboardNotifier` tanpa perlu melakukan sinkronisasi notifikasi secara manual, sehingga sistem menjadi lebih reaktif dan transparan. 

---

## Implemented Features — Week 4

**Arsitektur & Logika Bisnis:**
* Perbaikan tampilan user secara keseluruhan sesuai revisi presentasi.
* Perbaikan tampilan dan logika bisnis untuk modul Kontrak Sewa.
* Penambahan status aktif pengguna.
* Pembuatan fitur update password di halaman user.

**Komunikasi & Manajemen Aset (Penyelesaian Revisi Presentasi):**
* **Tiket Komplain (Admin):**
   * Menyederhanakan tabel (hanya menampilkan ID, Pengguna, Kamar, Judul).
   * Menghapus tombol aksi (Edit, Delete, Update Status) demi transparansi.
   * Memindahkan Tanggal, Status, dan Deskripsi ke dalam Modal Pop-up (Lihat Detail).
* **Tiket Komplain (User):**
   * Membuat file `create.php` khusus user yang terpisah dari admin, lengkap dengan kotak tips formatting.
   * Memindahkan hak akses update status ke sisi user (perbaikan izin di `routes/web.php`).
   * Menambahkan validasi JavaScript "Apakah Yakin?" saat memilih status Selesai.
   * Menambahkan fitur Lock (tombol gembok) jika tiket sudah mencapai status Selesai.
* **Pengumuman (User):**
   * Menghapus menu Pengumuman dari sidebar.
   * Menarik 10 data pengumuman terbaru dan menampilkannya secara langsung di halaman utama Dashboard User.
* **Aturan Kost (User):**
   * Mengubah tampilan Accordion (buka-tutup) menjadi *list* statis yang langsung memunculkan deskripsi agar lebih mudah dibaca.

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
- budi@mail.com (Penyewa)

password default yang digunakan adalah '123'

---

## Current Progress

> Bagian ini diisi berdasarkan progress implementasi real-time minggu ini.

| NRP     | Nama                                | Task                                                                               | Status      |
| ------- | ----------------------------------- | ---------------------------------------------------------------------------------- | ----------- |
| 2472018 | Kevin Kornelius Martadinata         | Arsitektur & Keamanan: Autentikasi, Profil Penghuni, Dashboard Visual, Log Riwayat | In Progress |
| 2472028 | Ferdi Gunawan                       | Logika Bisnis & Keuangan: Billing System, Pembayaran, Reminder, Laporan Keuangan   | In Progress |
| 2472007 | Richard Vincentius Christian Dinata | Komunikasi & Manajemen Aset: Inventaris, Komplain, Broadcast, Aturan Kost          | In Progress |

**Status:** `Not Started` · `In Progress` · `Done`

### Detail Progress Minggu Ini

| Divisi                             | Total Progress |
| ---------------------------------- | -------------: |
| Arsitektur & Keamanan              |         31.23% |
| Logika Bisnis & Keuangan           |         24.57% |
| Komunikasi & Manajemen Aset        |         33.32% |
| **Total Implementasi Keseluruhan** |     **89,12%** |

### Progress Fitur Utama

* Fitur 1 — Autentikasi/Role → 100%
* Fitur 2 — Manajemen Inventaris → 100%
* Fitur 3 — Profil Penghuni → 100%
* Fitur 4 — Dashboard Visual → 80%
* Fitur 5 — Generator Tagihan → 100%
* Fitur 6 - Pencatatan Pembayaran → 95%
* Fitur 7 - Notifikasi Jatuh Tempo → 0%
* Fitur 8 — Sistem Tiket Komplain → 100%
* Fitur 9 — Log Riwayat → 95%
* Fitur 10 — Laporan Keuangan → 100%
* Fitur 11 — Broadcast Pengumuman → 100%
* Fitur 12 — Manajemen Aturan → 100%

---
