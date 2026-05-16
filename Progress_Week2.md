# Progress Report — Week 2

**Project:** SobatKost

**Anggota:**
* 2472007 — Richard Vincentius Christian Dinata
* 2472018 — Kevin Kornelius Martadinata
* 2472028 — Ferdi Gunawan

---

## Design Patterns

### 1. Strategy Pattern — Validasi Metode Pembayaran

Pada progress minggu ini, implementasi utama difokuskan untuk memungkinkan aplikasi untuk menangani berbagai skema pembayaran seperti Transfer Bank, E-Wallet, atau Tunai. Dengan Strategy Pattern, logika validasi untuk masing-masing metode dipisahkan, sehingga penambahan metode baru di masa depan cukup dilakukan dengan menambah kelas baru tanpa mengubah kode lama. 

---

## Implemented Features — Week 2

* Membuat tampilan halaman pengumuman.
* Membuat tampilan halaman aturan kost.
* Memperbaiki Login UI dan password hashing.
* Implementasi CRUD Kontrak dan menyambungkan status kamar dengan status kontrak.
* Implementasi fitur generator tagihan.
* Memperbaiki fitur tagihan pada user.

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
| Arsitektur & Keamanan              |         29.15% |
| Logika Bisnis & Keuangan           |         16.24% |
| Komunikasi & Manajemen Aset        |         32.90% |
| **Total Implementasi Keseluruhan** |     **78.29%** |

### Progress Fitur Utama

* Fitur 1 — Autentikasi/Role → 100%
* Fitur 2 — Manajemen Inventaris → 100%
* Fitur 3 — Profil Penghuni → 100%
* Fitur 4 — Dashboard Visual → 70%
* Fitur 5 — Generator Tagihan → 70%
* Fitur 8 — Sistem Tiket Komplain → 100%
* Fitur 10 — Laporan Keuangan → 95%

---
