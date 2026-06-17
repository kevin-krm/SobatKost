<?php
/**
 * Mengatur lalu lintas komplain penghuni (contoh: AC bocor). Admin memantau tiket dari sini.
 */
require_once APP_PATH . '/dao/KomplainDao.php';
require_once APP_PATH . '/model/Komplain.php';
require_once APP_PATH . '/model/DashboardNotifier.php';

class KomplainController
{
    /**
     * Menampilkan daftar tiket komplain yang masuk untuk ditangani oleh pihak admin.
     */
    public function index()
    {
        $dao = new KomplainDao();
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $komplainList = $dao->getKomplainPage($limit, $offset);
        $totalData = $dao->countKomplain();
        $totalPage = ceil($totalData / $limit);
        $contentView = APP_PATH . '/view/komplain/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Menampilkan riwayat laporan komplain khusus yang pernah dibuat oleh penyewa tersebut secara pribadi.
     */
    public function userIndex()
    {
        session_status() === PHP_SESSION_ACTIVE ?: session_start();
        $id_pengguna = $_SESSION['user']['id'] ?? 'U-2605002';

        $dao = new KomplainDao();
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $komplainList = $dao->getKomplainByUserIdPage($id_pengguna, $limit, $offset);
        $totalData = $dao->countKomplainByUserId($id_pengguna);
        $totalPage = ceil($totalData / $limit);

        $contentView = APP_PATH . '/view/user/komplain/index.php';
        require_once APP_PATH . '/view/user/index.php';
    }

    /**
     * Menampilkan form keluhan agar penyewa dapat melaporkan kerusakan fasilitas kost.
     */
    public function create()
    {
        session_status() === PHP_SESSION_ACTIVE ?: session_start();
        if (isset($_SESSION['user']['id_peran']) && $_SESSION['user']['id_peran'] == 3) {
            $contentView = APP_PATH . '/view/user/komplain/create.php';
            require_once APP_PATH . '/view/user/index.php';
        } else {
            $contentView = APP_PATH . '/view/komplain/create.php';
            require_once APP_PATH . '/view/index.php';
        }
    }

    /**
     * Menyimpan laporan kerusakan fasilitas baru ke sistem melalui KomplainDao.php.
     */
    public function store()
    {
        session_status() === PHP_SESSION_ACTIVE ?: session_start();
        $id_pengguna = $_SESSION['user']['id'] ?? 'U-2605001';

        $judul = $_POST['judul_masalah'];
        $deskripsi = $_POST['deskripsi'];

        $komplain = new Komplain(null, $id_pengguna, $judul, $deskripsi, 'Menunggu', null);
        $dao = new KomplainDao();
        $dao->insertKomplain($komplain);

        // Jika penyewa (peran 3), kembalikan ke dashboard user. Jika admin, ke dashboard admin.
        if (isset($_SESSION['user']['id_peran']) && $_SESSION['user']['id_peran'] == 3) {
            header("Location: /SobatKost/index.php?url=user/komplain");
        } else {
            header("Location: /SobatKost/index.php?url=komplain");
        }
        exit;
    }

    // Untuk simpan dari Form Edit (Judul, Deskripsi, Status)
    /**
     * Memperbarui isi detail laporan komplain yang sudah ada.
     */
    public function update($id)
    {
        $dao = new KomplainDao();
        $kLama = $dao->getKomplainById($id);

        if (!$kLama) {
            header("Location: /SobatKost/index.php?url=komplain");
            exit;
        }

        $judul = $_POST['judul_masalah'];
        $deskripsi = $_POST['deskripsi'];
        $status = $_POST['status_komplain'];

        $kBaru = new Komplain(
            $id,
            $kLama->getIdPengguna(),
            $judul,
            $deskripsi,
            $status,
            $kLama->getTanggalLapor()
        );

        $dao->updateKomplainPenuh($kBaru);

        header("Location: /SobatKost/index.php?url=komplain");
        exit;
    }

    // Tetap dipertahankan untuk dropdown cepat di Tabel Index
    /**
     * Mengubah status komplain (misalnya dari 'Menunggu' menjadi 'Diproses').
     * Relasi: Perubahan status ini dapat memicu pola Observer Pattern.
     */
    public function updateStatus($id)
    {
        session_status() === PHP_SESSION_ACTIVE ?: session_start();
        $status_baru = $_POST['status_komplain'];

        $dao = new KomplainDao();
        $komplain = $dao->getKomplainById($id);

        if ($komplain) {
            $notifier = new DashboardNotifier();
            $komplain->attach($notifier);
            $komplain->setStatusKomplain($status_baru);
            $dao->updateStatus($komplain);
        }

        // Pengecekan cerdas untuk kembali ke halaman yang benar
        if (isset($_SESSION['user']['id_peran']) && $_SESSION['user']['id_peran'] == 3) {
            header("Location: /SobatKost/index.php?url=user/komplain");
        } else {
            header("Location: /SobatKost/index.php?url=komplain");
        }
        exit;
    }

    /**
     * Menghapus tiket laporan komplain dari sistem secara permanen.
     */
    public function delete($id)
    {
        $dao = new KomplainDao();
        $dao->deleteKomplain($id);
        header("Location: /SobatKost/index.php?url=komplain");
        exit;
    }

    /**
     * Menampilkan form untuk memperbaiki teks laporan komplain.
     */
    public function edit($id)
    {
        $dao = new KomplainDao();
        $komplain = $dao->getKomplainById($id);
        if (!$komplain) {
            echo "<h3>Data tiket komplain tidak ditemukan</h3>";
            exit;
        }
        $contentView = APP_PATH . '/view/komplain/edit.php';
        require_once APP_PATH . '/view/index.php';
    }
}
?>