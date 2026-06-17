<?php
/**
 * Mengurus pendataan barang-barang yang ada di kamar (AC, kasur, lemari, dll).
 */
require_once APP_PATH . '/dao/InventarisDao.php';
require_once APP_PATH . '/dao/KamarDao.php';

class InventarisController {
    /**
     * Menampilkan halaman daftar barang-barang inventaris.
     * Relasi: Memanggil getInventarisPage() dari InventarisDao.php untuk data berhalaman.
     */
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

    /**
     * Menangkap data form tambah barang lalu memerintahkan InventarisDao.php menyimpannya ke database.
     */
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

    /**
     * Jalur cepat untuk sekadar mengubah kondisi barang
     */
    public function updateStatus($id) {
        if (isset($_POST['kondisi_barang'])) {
            $kondisi = $_POST['kondisi_barang'];
            $dao = new InventarisDao();
            $dao->updateKondisiBarang($id, $kondisi);
        }
        header("Location: /SobatKost/index.php?url=inventaris");
        exit;
    }

    /**
     * Menampilkan form untuk mencatat barang baru.
     * Relasi: Memanggil KamarDao.php untuk menampilkan pilihan kamar mana yang mau diisi barang.
     */
    public function create() {
        $kamarDao = new KamarDao();
        $kamarList = $kamarDao->getKamarPage(100, 0);

        $contentView = APP_PATH . '/view/inventaris/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    /**
     * Menampilkan form edit barang. Menarik data barang lewat getInventarisById() di InventarisDao.php.
     */
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

    /**
     * Menangkap data perubahan barang lalu menimpanya ke database via updateInventaris() di InventarisDao.php.
     */
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

    /**
     * Mengeksekusi penghapusan barang dari sistem.
     */
    public function delete($id) {
        $dao = new InventarisDao();
        $dao->deleteInventaris($id);
        header("Location: /SobatKost/index.php?url=inventaris");
        exit;
    }
}
?>