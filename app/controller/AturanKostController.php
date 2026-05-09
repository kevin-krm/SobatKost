<?php
require_once APP_PATH . '/dao/AturanKostDao.php';
require_once APP_PATH . '/model/AturanKost.php';

class AturanKostController {
    private $aturanDao;

    public function __construct() {
        $this->aturanDao = new AturanKostDao();
    }

    // --- DASHBOARD ADMIN ---
    public function index() {
        $aturanList = $this->aturanDao->showAllAturan();
        $contentView = APP_PATH . '/view/aturan/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function create() {
        $contentView = APP_PATH . '/view/aturan/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $judul = $_POST['judul_aturan'] ?? null;
            $deskripsi = $_POST['deskripsi_aturan'] ?? null;

            if ($judul && $deskripsi) {
                $aturan = new AturanKost();
                $aturan->setJudulAturan($judul);
                $aturan->setDeskripsiAturan($deskripsi);
                $this->aturanDao->addAturan($aturan);
            }
        }
        header("Location: /SobatKost/index.php?url=aturan");
        exit;
    }

    public function edit($id) {
        $aturan = $this->aturanDao->getAturanById($id);
        if (!$aturan) {
            echo "<h3>Aturan tidak ditemukan</h3>";
            exit;
        }
        $contentView = APP_PATH . '/view/aturan/edit.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $judul = $_POST['judul_aturan'] ?? null;
            $deskripsi = $_POST['deskripsi_aturan'] ?? null;

            if ($judul && $deskripsi) {
                $aturan = new AturanKost();
                $aturan->setIdAturan($id);
                $aturan->setJudulAturan($judul);
                $aturan->setDeskripsiAturan($deskripsi);
                $this->aturanDao->updateAturan($aturan);
            }
        }
        header("Location: /SobatKost/index.php?url=aturan");
        exit;
    }

    public function delete($id) {
        $this->aturanDao->deleteAturan($id);
        header("Location: /SobatKost/index.php?url=aturan");
        exit;
    }

    // --- DASHBOARD PENYEWA (USER) ---
    public function userIndex() {
        $aturanList = $this->aturanDao->showAllAturan();
        $contentView = APP_PATH . '/view/user/aturan/index.php';
        require_once APP_PATH . '/view/user/index.php';
    }
}
?>