# Progress Report — Week 1

**Project:** SobatKost

**Anggota:**
* 2472007 — Richard Vincentius Christian Dinata
* 2472018 — Kevin Kornelius Martadinata
* 2472028 — Ferdi Gunawan

---

## Design Patterns

### 1. Singleton Pattern — Koneksi Database & Konfigurasi Sistem

Pada progress minggu ini, implementasi utama difokuskan pada struktur arsitektur sistem menggunakan PHP dengan pola MVC serta koneksi database terpusat. **Singleton Pattern** digunakan pada koneksi database agar seluruh proses CRUD pada fitur seperti login, data pengguna, profil penghuni, inventaris, dan laporan keuangan hanya menggunakan satu instance koneksi.

Penerapan ini membantu efisiensi resource server, menghindari pemborosan memori, serta mencegah terlalu banyak koneksi database yang terbuka secara bersamaan.

---

### 2. Factory Pattern — Generator Tagihan

SobatKost memiliki beberapa tipe sewa seperti harian, bulanan, dan tahunan. Setiap tipe memiliki logika perhitungan tagihan yang berbeda. Dengan menggunakan Factory Pattern, sistem dapat membuat objek tagihan sesuai tipe sewa tanpa mengubah logika utama program.

Penerapan ini mendukung prinsip Open-Closed Principle (OCP) karena penambahan tipe tagihan baru dapat dilakukan tanpa memodifikasi kode yang sudah ada.

---

### 3. Observer Pattern — Update Status Komplain

Pada progress awal fitur Komplain, sistem dirancang agar perubahan status komplain dapat langsung diketahui oleh penyewa tanpa perlu pemberitahuan manual dari admin. Untuk itu digunakan **Observer Pattern**, di mana perubahan status pada data komplain akan menjadi trigger notifikasi pembaruan pada dashboard pengguna.

Penerapan ini membuat sistem lebih terstruktur dan mengurangi ketergantungan langsung antara admin dan penyewa (*loose coupling*).

---

## Implemented Features — Week 1

### Inisiasi Project

* Inisiasi project web app menggunakan PHP dengan arsitektur MVC.
* Menyiapkan struktur project berbasis DAO (Data Access Object) untuk menjaga separation of concerns.
* Implementasi awal sistem autentikasi multi-user (Role Management) untuk Owner, Penjaga Kost, dan Penyewa.
* Pembuatan halaman Login dan Dashboard Admin.
* Implementasi CRUD Data Pengguna sebagai acuan struktur fitur lainnya.
* Implementasi awal Dashboard Visual untuk memantau status kamar.
* Implementasi awal Manajemen Inventaris Kamar.
* Implementasi awal Tiket Komplain.
* Implementasi awal Laporan Keuangan Bulanan.

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
| Arsitektur & Keamanan              |         19.32% |
| Logika Bisnis & Keuangan           |          7.91% |
| Komunikasi & Manajemen Aset        |         12.66% |
| **Total Implementasi Keseluruhan** |     **39.89%** |

### Progress Fitur Utama

* Fitur 1 — Autentikasi/Role → 80%
* Fitur 2 — Manajemen Inventaris → 100%
* Fitur 3 — Profil Penghuni → 100%
* Fitur 4 — Dashboard Visual → 50%
* Fitur 8 — Sistem Tiket Komplain → 50%
* Fitur 10 — Laporan Keuangan → 95%

---
