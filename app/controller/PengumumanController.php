<?php
require_once APP_PATH . '/dao/PengumumanDao.php';
require_once APP_PATH . '/model/Pengumuman.php';

class PengumumanController {
    private $pengumumanDao;

    public function __construct() {
        $this->pengumumanDao = new PengumumanDao();
    }

    // --- DASHBOARD ADMIN ---
    public function index() {
        $pengumumanList = $this->pengumumanDao->showAllPengumuman();
        $contentView = APP_PATH . '/view/pengumuman/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function create() {
        $contentView = APP_PATH . '/view/pengumuman/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $judul = $_POST['judul'] ?? null;
            $konten = $_POST['konten'] ?? null;

            if ($judul && $konten) {
                $pengumuman = new Pengumuman();
                $pengumuman->setJudul($judul);
                $pengumuman->setKonten($konten);

                $this->pengumumanDao->addPengumuman($pengumuman);
            }
        }
        header("Location: /SobatKost/index.php?url=pengumuman");
        exit;
    }

    public function edit($id) {
        $pengumuman = $this->pengumumanDao->getPengumumanById($id);
        if (!$pengumuman) {
            echo "<h3>Pengumuman tidak ditemukan</h3>";
            exit;
        }
        $contentView = APP_PATH . '/view/pengumuman/edit.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $judul = $_POST['judul'] ?? null;
            $konten = $_POST['konten'] ?? null;

            if ($judul && $konten) {
                $pLama = $this->pengumumanDao->getPengumumanById($id);

                $pengumuman = new Pengumuman();
                $pengumuman->setIdPengumuman($id);
                $pengumuman->setJudul($judul);
                $pengumuman->setKonten($konten);
                // Pertahankan tanggal siar lama
                $pengumuman->setTanggalSiar($pLama->getTanggalSiar());

                // Kita butuh fungsi updatePengumuman di DAO! (Akan kita tambahkan setelah ini)
                $this->pengumumanDao->updatePengumuman($pengumuman);
            }
        }
        header("Location: /SobatKost/index.php?url=pengumuman");
        exit;
    }

    public function delete($id) {
        $this->pengumumanDao->deletePengumuman($id);
        header("Location: /SobatKost/index.php?url=pengumuman");
        exit;
    }

    // --- DASHBOARD PENYEWA (USER) ---
    public function userIndex() {
        $pengumumanList = $this->pengumumanDao->showAllPengumuman();
        $contentView = APP_PATH . '/view/user/pengumuman/index.php';
        require_once APP_PATH . '/view/user/index.php';
    }
}
?>