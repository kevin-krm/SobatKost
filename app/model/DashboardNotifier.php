<?php
namespace App\Model;

class DashboardNotifier implements Observer {
    public function update(Subject $subject) {
        // Logika saat menerima notifikasi perubahan status
        $status_baru = $subject->getStatusKomplain();
        $id_penyewa = $subject->getIdPengguna();
        $id_komplain = $subject->getIdKomplain();

        // Dalam skenario nyata, di sini kamu akan menggunakan DAO
        // untuk melakukan insert ke tabel `pengumuman` spesifik untuk user ini
        // atau menyimpan log notifikasi ke tabel khusus notifikasi.

        // Untuk saat ini, kita bisa mensimulasikannya dengan error_log
        error_log("NOTIFIKASI SISTEM: Tiket Komplain {$id_komplain} milik pengguna {$id_penyewa} kini berstatus: {$status_baru}");
    }
}