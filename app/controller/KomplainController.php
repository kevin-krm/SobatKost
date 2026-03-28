<?php
namespace App\Controller;

use App\Model\Komplain;
use App\Model\DashboardNotifier;
use App\Dao\KomplainDao;

class KomplainController {
    public function updateStatus() {
        // 1. Ambil input (misal dari form POST)
        $id_komplain_input = $_POST['id_komplain'] ?? null;
        $status_baru = $_POST['status_komplain'] ?? null;

        if ($id_komplain_input && $status_baru) {
            // 2. Idealnya DAO menarik data komplain lama, tapi untuk contoh kita inisialisasi model
            $komplain = new Komplain();
            $komplain->setIdKomplain($id_komplain_input);
            $komplain->setIdPengguna("U-2403001"); // Simulasi ID Penyewa

            // 3. Daftarkan Observer
            $notifier = new DashboardNotifier();
            $komplain->attach($notifier);

            // 4. Ubah status (Ini memicu $notifier->update() secara otomatis)
            $komplain->setStatusKomplain($status_baru);

            // 5. Simpan ke database menggunakan DAO (kamu perlu melengkapi isi KomplainDao.php)
            // $komplainDao = new KomplainDao();
            // $komplainDao->updateStatus($komplain);

            // 6. Redirect ke halaman view
            // header("Location: index.php?page=komplain");
        }
    }
}