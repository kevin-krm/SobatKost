<?php
/**
 * Menangani logika halaman tata tertib kost. Mulai dari nambah aturan baru, mengedit yang ada, sampai menampilkan list aturan ke penghuni.
 */
require_once APP_PATH . '/dao/AturanKostDao.php';
require_once APP_PATH . '/model/AturanKost.php';

class AturanKostController {
    private $aturanDao;

    public function __construct() {
        $this->aturanDao = new AturanKostDao();
    }

    // --- DASHBOARD ADMIN ---
    /**
     * Menampilkan halaman admin untuk mengelola daftar tata tertib kost.
     */
    public function index() {
        $aturanList = $this->aturanDao->showAllAturan();
        $contentView = APP_PATH . '/view/aturan/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Menampilkan form untuk membuat aturan baru.
     */
    public function create() {
        $contentView = APP_PATH . '/view/aturan/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Menyimpan aturan baru ke database via AturanKostDao.php.
     */
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

    /**
     * Menampilkan form untuk mengubah isi aturan.
     */
    public function edit($id) {
        $aturan = $this->aturanDao->getAturanById($id);
        if (!$aturan) {
            echo "<h3>Aturan tidak ditemukan</h3>";
            exit;
        }
        $contentView = APP_PATH . '/view/aturan/edit.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Menyimpan hasil editan aturan kembali ke database.
     */
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

    /**
     * Mencabut aturan dari sistem.
     */
    public function delete($id) {
        $this->aturanDao->deleteAturan($id);
        header("Location: /SobatKost/index.php?url=aturan");
        exit;
    }

    // --- DASHBOARD PENYEWA (USER) ---
    /**
     * Menampilkan daftar tata tertib di layar Penyewa agar mereka bisa baca dan patuh.
     */
    public function userIndex() {
        $aturanList = $this->aturanDao->showAllAturan();
        $contentView = APP_PATH . '/view/user/aturan/index.php';
        require_once APP_PATH . '/view/user/index.php';
    }
}
?>