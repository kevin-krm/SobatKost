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
        // Nanti dikirim ke View [cite: 68]
    }
}