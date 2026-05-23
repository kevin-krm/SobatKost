# Progress Report — Week 3

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

## Implemented Features — Week 3

* Membuat tampilan edit Kontrak Sewa
* Finalisasi menu Kontrak Sewa
* Penambahan pesan error pada create & edit pengguna jika e-mail sudah digunakan
* Penambahan pesan error di menu pengguna & kamar (jika ID sudah menjadi foreign key, maka tidak bisa dihapus)
* Perbaikan tampilan minor pada menu kamar
* Penambahan pengecekan pada menu kamar, jika status "Terisi" maka tidak bisa edit & delete
* perbaikan UI/UX fitur Inventaris (Input kamar diubah menjadi dropdown) 
* perbaikan UI/UX fitur Tiket Komplain (Komplain tidak bisa edit & delete jika status "Selesai")
* Implementasi fitur pembayaran pada halaman User
* Perbaikan tampilan Sidebar (sidebar active, formatting, scroll) untuk Admin dan User

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
| Arsitektur & Keamanan              |         30.81% |
| Logika Bisnis & Keuangan           |         24.57% |
| Komunikasi & Manajemen Aset        |         33.32% |
| **Total Implementasi Keseluruhan** |     **88,7%** |

### Progress Fitur Utama

* Fitur 1 — Autentikasi/Role → 95%
* Fitur 2 — Manajemen Inventaris → 100%
* Fitur 3 — Profil Penghuni → 100%
* Fitur 4 — Dashboard Visual → 80%
* Fitur 5 — Generator Tagihan → 100%
* Fitur 6 - Pencatatan Pembayaran → 100%
* Fitur 7 - Notifikasi Jatuh Tempo → 0%
* Fitur 8 — Sistem Tiket Komplain → 100%
* Fitur 9 — Log Riwayat → 95%
* Fitur 10 — Laporan Keuangan → 100%
* Fitur 11 — Broadcast Pengumuman → 100%
* Fitur 12 — Manajemen Aturan → 100%

---
