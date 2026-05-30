<?php
require_once APP_PATH . '/dao/KomplainDao.php';
require_once APP_PATH . '/model/Komplain.php';
require_once APP_PATH . '/model/DashboardNotifier.php';

class KomplainController
{
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

    public function delete($id)
    {
        $dao = new KomplainDao();
        $dao->deleteKomplain($id);
        header("Location: /SobatKost/index.php?url=komplain");
        exit;
    }

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