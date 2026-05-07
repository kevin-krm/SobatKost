<?php
require_once APP_PATH . '/dao/KomplainDao.php';
require_once APP_PATH . '/model/Komplain.php';
require_once APP_PATH . '/model/DashboardNotifier.php'; // Untuk Observer Pattern

class KomplainController
{
    // 1. Menampilkan daftar komplain
    public function index()
    {
        $dao = new KomplainDao();

        $komplainList = $dao->getAllKomplain();

        // Alur Program Baru: Memanggil View menggunakan Master Layout
        $contentView = APP_PATH . '/view/komplain/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    // 2. Menampilkan form tambah komplain
    public function create()
    {
        $contentView = APP_PATH . '/view/komplain/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    // 3. Proses Observer Pattern saat update status
    public function updateStatus($id)
    {
        $status_baru = $_POST['status_komplain'];

        $komplain = new Komplain();
        $komplain->setIdKomplain($id);

        // Daftarkan Observer
        $notifier = new DashboardNotifier();
        $komplain->attach($notifier);

        // Ubah status (Otomatis memicu notifikasi Observer)
        $komplain->setStatusKomplain($status_baru);

        // Simpan ke database
        $dao = new KomplainDao();
        $dao->updateStatusKomplain($komplain);

        // Redirect kembali ke halaman daftar komplain
        header("Location: /SobatKost/komplain");
        exit;
    }
}
?>