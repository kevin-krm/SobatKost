<?php
require_once APP_PATH . '/dao/PenghuniDao.php';

class PenghuniController
{
    public function index()
    {
        $dao = new PenghuniDao();
        $penghuniList = $dao->getAllPenghuni();
        // Path ke view penghuni
        $contentView = APP_PATH . '/view/penghuni/index.php';
        // Panggil view utama
        require_once APP_PATH . '/view/index.php';
    }

    public function create() {
        $contentView = APP_PATH . '/view/penghuni/Create.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_lengkap = $_POST['nama_lengkap'] ?? '';
            $email        = $_POST['email'] ?? '';
            $id_peran     = $_POST['id_peran'] ?? ''; // Ambil dari form
            $kata_sandi   = $_POST['kata_sandi'] ?? '';

            if (!empty($nama_lengkap) && !empty($email)) {
                $dao = new PenghuniDao();
                $dao->insertPengguna($id_peran, $nama_lengkap, $email, $kata_sandi);
                // Redirect menggunakan path yang sesuai dengan .htaccess
                header("Location: /SobatKost/penghuni");
                exit();
            }
        }
    }

}


