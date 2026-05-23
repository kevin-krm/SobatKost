<?php
require_once APP_PATH . '/dao/InventarisDao.php';
require_once APP_PATH . '/dao/KamarDao.php';

class InventarisController {
    public function index() {
        $dao = new InventarisDao();

        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $inventarisList = $dao->getInventarisPage($limit, $offset);
        $totalData = $dao->countInventaris();
        $totalPage = ceil($totalData / $limit);

        $contentView = APP_PATH . '/view/inventaris/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function store() {
        $id_kamar = $_POST['id_kamar'];
        $nama_barang = $_POST['nama_barang'];
        $kondisi = $_POST['kondisi_barang'];

        $inventaris = new Inventaris(null, $id_kamar, $nama_barang, $kondisi);
        $dao = new InventarisDao();
        $dao->insertInventaris($inventaris);

        header("Location: /SobatKost/index.php?url=inventaris");
        exit;
    }

    public function updateStatus($id) {
        if (isset($_POST['kondisi_barang'])) {
            $kondisi = $_POST['kondisi_barang'];
            $dao = new InventarisDao();
            $dao->updateKondisiBarang($id, $kondisi);
        }
        header("Location: /SobatKost/index.php?url=inventaris");
        exit;
    }

    public function create() {
        $kamarDao = new KamarDao();
        $kamarList = $kamarDao->getKamarPage(100, 0);

        $contentView = APP_PATH . '/view/inventaris/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function edit($id) {
        $dao = new InventarisDao();
        $inventaris = $dao->getInventarisById($id);

        if (!$inventaris) {
            echo "<h3>Data inventaris tidak ditemukan</h3>";
            exit;
        }

        $kamarDao = new KamarDao();
        $kamarList = $kamarDao->getKamarPage(100, 0);

        $contentView = APP_PATH . '/view/inventaris/edit.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function update($id) {
        $id_kamar = $_POST['id_kamar'];
        $nama_barang = $_POST['nama_barang'];
        $kondisi = $_POST['kondisi_barang'];

        $inventaris = new Inventaris($id, $id_kamar, $nama_barang, $kondisi);
        $dao = new InventarisDao();
        $dao->updateInventaris($inventaris);

        header("Location: /SobatKost/index.php?url=inventaris");
        exit;
    }

    public function delete($id) {
        $dao = new InventarisDao();
        $dao->deleteInventaris($id);
        header("Location: /SobatKost/index.php?url=inventaris");
        exit;
    }
}
?>