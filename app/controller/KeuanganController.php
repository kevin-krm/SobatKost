<?php
require_once APP_PATH . '/dao/BiayaOperasionalDao.php';

class KeuanganController {
    public function index() {
        $dao = new BiayaOperasionalDao();

        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $biayaList = $dao->getBiayaPage($limit, $offset);
        $totalData = $dao->countBiaya();
        $totalPage = ceil($totalData / $limit);

        $contentView = APP_PATH . '/view/keuangan/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function store() {
        $kategori_biaya = $_POST['kategori_biaya'];
        $jumlah_biaya = $_POST['jumlah_biaya'];
        $keterangan = $_POST['keterangan'];

        $biaya = new BiayaOperasional(null, $kategori_biaya, $jumlah_biaya, null, $keterangan);
        $dao = new BiayaOperasionalDao();
        $dao->insertBiaya($biaya);

        header("Location: /SobatKost/index.php?url=keuangan");
        exit;
    }

    public function create() {
        $contentView = APP_PATH . '/view/keuangan/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function edit($id) {
        $dao = new BiayaOperasionalDao();
        $biaya = $dao->getBiayaById($id);

        if (!$biaya) {
            echo "<h3>Data biaya operasional tidak ditemukan</h3>";
            exit;
        }

        $contentView = APP_PATH . '/view/keuangan/edit.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function update($id) {
        $kategori_biaya = $_POST['kategori_biaya'];
        $jumlah_biaya = $_POST['jumlah_biaya'];
        $tanggal_pengeluaran = $_POST['tanggal_pengeluaran'];
        $keterangan = $_POST['keterangan'];

        $biaya = new BiayaOperasional($id, $kategori_biaya, $jumlah_biaya, $tanggal_pengeluaran, $keterangan);
        $dao = new BiayaOperasionalDao();
        $dao->updateBiaya($biaya);

        header("Location: /SobatKost/index.php?url=keuangan");
        exit;
    }

    public function delete($id) {
        $dao = new BiayaOperasionalDao();
        $dao->deleteBiaya($id);
        header("Location: /SobatKost/index.php?url=keuangan");
        exit;
    }
}
?>
