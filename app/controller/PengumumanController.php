<?php
namespace App\Controller;

use App\Dao\PengumumanDao;
use App\Model\Pengumuman;

class PengumumanController {
    private $pengumumanDao;

    public function __construct() {
        $this->pengumumanDao = new PengumumanDao();
    }

    public function index() {
        $dataPengumuman = $this->pengumumanDao->showAllPengumuman();
    }

    public function tambahPengumuman() {
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
    }
}
?>