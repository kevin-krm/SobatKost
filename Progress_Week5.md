# Progress Report — Week 5

**Project:** SobatKost

**Anggota:**
* 2472007 — Richard Vincentius Christian Dinata
* 2472018 — Kevin Kornelius Martadinata
* 2472028 — Ferdi Gunawan

---

## Design Patterns

### penguatan Singleton Pattern

Pembaruan minggu ini berfokus pada penguatan Singleton Pattern dalam manajemen koneksi database. Kami meningkatkan penanganan error pada PDOUtil agar sistem lebih stabil saat terjadi kegagalan koneksi (fail-safe mechanism), dengan memastikan sesi pengguna diakhiri secara otomatis jika koneksi database terputus. Selain itu, dilakukan refactoring pada controller keuangan dan home untuk meningkatkan efisiensi pengambilan data dan performa sistem. 

---

## Implemented Features — Week 5

* Implementasi Fitur Auto Logout jika server mati
* Improve Sidebar & pembatasan akses
* Improve Dashboard statistik (admin)
* Fix laporan keuangan

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
| Arsitektur & Keamanan              |         33.32% |
| Logika Bisnis & Keuangan           |         24.57% |
| Komunikasi & Manajemen Aset        |         33.32% |
| **Total Implementasi Keseluruhan** |     **91,21%** |

### Progress Fitur Utama

* Fitur 1 — Autentikasi/Role → 100%
* Fitur 2 — Manajemen Inventaris → 100%
* Fitur 3 — Profil Penghuni → 100%
* Fitur 4 — Dashboard Visual → 100%
* Fitur 5 — Generator Tagihan → 100%
* Fitur 6 - Pencatatan Pembayaran → 95%
* Fitur 7 - Notifikasi Jatuh Tempo → 0%
* Fitur 8 — Sistem Tiket Komplain → 100%
* Fitur 9 — Log Riwayat → 100%
* Fitur 10 — Laporan Keuangan → 100%
* Fitur 11 — Broadcast Pengumuman → 100%
* Fitur 12 — Manajemen Aturan → 100%

---
