<?php
namespace App\Controller;

use App\Dao\InventarisDao;
use App\Model\Inventaris;

class InventarisController {
    private $inventarisDao;

    public function __construct() {
        $this->inventarisDao = new InventarisDao();
    }

    public function index() {
        $id_kamar = $_GET['id_kamar'] ?? 'K-101'; // Default kamar contoh
        $dataInventaris = $this->inventarisDao->showInventarisByKamar($id_kamar);
    }

    public function tambahBarang() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_kamar = $_POST['id_kamar'] ?? null;
            $nama_barang = $_POST['nama_barang'] ?? null;
            $kondisi_barang = $_POST['kondisi_barang'] ?? null;

            if ($id_kamar && $nama_barang && $kondisi_barang) {
                $inventarisBaru = new Inventaris();
                $inventarisBaru->setIdKamar($id_kamar);
                $inventarisBaru->setNamaBarang($nama_barang);
                $inventarisBaru->setKondisiBarang($kondisi_barang);

                $this->inventarisDao->addInventaris($inventarisBaru);
            }
        }
    }
}