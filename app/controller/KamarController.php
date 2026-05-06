<?php
require_once APP_PATH . '/dao/KamarDao.php';

class KamarController
{
    public function index()
    {
        $dao = new KamarDao();

        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $kamarList = $dao->getKamarPage($limit, $offset);
        $totalData = $dao->countKamar();
        $totalPage = ceil($totalData / $limit);

        $contentView = APP_PATH . '/view/kamar/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function create()
    {
        $contentView = APP_PATH . '/view/kamar/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function store()
    {
        $nomor = $_POST['nomor_kamar'];
        $tipe = $_POST['tipe_kamar'];
        $harga = $_POST['harga_dasar'];

        $kamar = new Kamar(
            null,
            $nomor,
            $tipe,
            "Tersedia",        // default
            $harga
        );

        $dao = new KamarDao();
        $dao->insertKamar($kamar);

        header("Location: /SobatKost/kamar");
        exit;
    }

    public function edit($id)
    {
        $dao = new KamarDao();
        $kamar = $dao->getKamarById($id);

        if (!$kamar) {
            echo "<h3>Data kamar tidak ditemukan</h3>";
            exit;
        }

        $contentView = APP_PATH . '/view/kamar/edit.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function update($id)
    {
        $kamar = new Kamar(
            $id,
            $_POST['nomor_kamar'],
            $_POST['tipe_kamar'],
            $_POST['status_kamar'],
            $_POST['harga_dasar']
        );

        $dao = new KamarDao();
        $dao->updateKamar($kamar);

        header("Location: /SobatKost/kamar");
        exit;
    }

    public function delete($id)
    {
        $dao = new KamarDao();
        $kamar = $dao->getKamarById($id);

        if (!$kamar) {
            echo "Data tidak ditemukan";
            exit;
        }

        $dao->deleteKamar($id);

        header("Location: /SobatKost/kamar");
        exit;
    }
}