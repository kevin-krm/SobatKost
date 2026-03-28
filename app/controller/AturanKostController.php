<?php
namespace App\Controller;

use App\Dao\AturanKostDao;
use App\Model\AturanKost;

class AturanKostController {
    private $aturanDao;

    public function __construct() {
        $this->aturanDao = new AturanKostDao();
    }

    public function index() {
        $dataAturan = $this->aturanDao->showAllAturan();
    }

    public function tambahAturan() {
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
    }
}
?>