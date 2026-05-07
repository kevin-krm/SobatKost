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

        // Logika pagination
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $komplainList = $dao->getKomplainPage($limit, $offset);

        $totalData = $dao->countKomplain();
        $totalPage = ceil($totalData / $limit);

        // Memanggil View dengan Master Layout
        $contentView = APP_PATH . '/view/komplain/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    // 2. Menampilkan form tambah komplain
    public function create()
    {
        $contentView = APP_PATH . '/view/komplain/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    // 3. Memproses penyimpanan data komplain baru ke Database
    public function store()
    {
        // Gunakan ID Dummy penyewa (Budi Santoso) untuk simulasi saat ini
        $id_pengguna = 'U-2605002';

        $judul = $_POST['judul_masalah'];
        $deskripsi = $_POST['deskripsi'];

        // Membuat objek model Komplain sesuai Constructor di model/Komplain.php
        $komplain = new Komplain(
            null,
            $id_pengguna,
            $judul,
            $deskripsi,
            'Menunggu',     // status_komplain default
            null            // tanggal_lapor (otomatis dari DB)
        );

        $dao = new KomplainDao();
        $dao->insertKomplain($komplain);

        // Alur redirect sesuai standar Kevin
        header("Location: /SobatKost/komplain");
        exit;
    }

    // 4. Memproses Update Status sekaligus memicu Observer Pattern
    public function updateStatus($id)
    {
        $status_baru = $_POST['status_komplain'];

        $dao = new KomplainDao();
        $komplain = $dao->getKomplainById($id);

        if ($komplain) {
            // Implementasi Observer Pattern sesuai tanggung jawab teknismu
            $notifier = new DashboardNotifier();
            $komplain->attach($notifier);

            // Perubahan status memicu notify() otomatis
            $komplain->setStatusKomplain($status_baru);

            // Simpan perubahan status ke database melalui DAO
            $dao->updateStatus($komplain);
        }

        header("Location: /SobatKost/komplain");
        exit;
    }

    // 5. Menghapus data Tiket Komplain
    public function delete($id)
    {
        $dao = new KomplainDao();
        $dao->deleteKomplain($id);

        header("Location: /SobatKost/komplain");
        exit;
    }

    /**
     * Menampilkan form edit berdasarkan ID komplain
     */
    public function edit($id)
    {
        $dao = new KomplainDao();

        // Mencari data komplain spesifik ke database melalui DAO
        $komplain = $dao->getKomplainById($id);

        // Validasi jika data tidak ditemukan
        if (!$komplain) {
            echo "<h3>Data tiket komplain tidak ditemukan</h3>";
            exit;
        }

        // Mengirim data $komplain ke view edit.php
        $contentView = APP_PATH . '/view/komplain/edit.php';
        require_once APP_PATH . '/view/index.php';
    }
}
?>