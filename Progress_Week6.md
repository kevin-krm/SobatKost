# Progress Report — Week 6

**Project:** SobatKost

**Anggota:**
* 2472007 — Richard Vincentius Christian Dinata
* 2472018 — Kevin Kornelius Martadinata
* 2472028 — Ferdi Gunawan

---

## Design Patterns

### Adapter Pattern

Pembaruan minggu ini berfokus pada implementasi Adapter Pattern dalam sistem notifikasi tagihan. Adapter Pattern digunakan untuk menjembatani perbedaan antarmuka antara sistem pengecekan tagihan dengan layanan notifikasi, sehingga sistem dapat memberikan informasi kepada pengguna apabila terdapat tagihan yang sudah melewati batas waktu pembayaran.

---

## Implemented Features — Week 6

* Implementasi Fitur reset password pada halaman login
* Mencabut akses admin untuk merubah password & e-mail user
* Implementasi fitur update e-mail sebagai pengguna
* Implementasi fitur notifikasi jatuh tempo (Adspter pattern)
* Perbaikan Decorator pattern pada tagihan

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
| 2472028 | Ferdi Gunawan                       | Logika Bisnis & Keuangan: Billing System, Pembayaran, Reminder, Laporan Keuangan   | In Progress |
| 2472007 | Richard Vincentius Christian Dinata | Komunikasi & Manajemen Aset: Inventaris, Komplain, Broadcast, Aturan Kost          | Done |

**Status:** `Not Started` · `In Progress` · `Done`

### Detail Progress Minggu Ini

| Divisi                             | Total Progress |
| ---------------------------------- | -------------: |
| Arsitektur & Keamanan              |         33.32% |
| Logika Bisnis & Keuangan           |         32.99% |
| Komunikasi & Manajemen Aset        |         33.32% |
| **Total Implementasi Keseluruhan** |     **99,54%** |

### Progress Fitur Utama

* Fitur 1 — Autentikasi/Role → 100%
* Fitur 2 — Manajemen Inventaris → 100%
* Fitur 3 — Profil Penghuni → 100%
* Fitur 4 — Dashboard Visual → 100%
* Fitur 5 — Generator Tagihan → 100%
* Fitur 6 - Pencatatan Pembayaran → 95%
* Fitur 7 - Notifikasi Jatuh Tempo → 100%
* Fitur 8 — Sistem Tiket Komplain → 100%
* Fitur 9 — Log Riwayat → 100%
* Fitur 10 — Laporan Keuangan → 100%
* Fitur 11 — Broadcast Pengumuman → 100%
* Fitur 12 — Manajemen Aturan → 100%

---
