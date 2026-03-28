<?php
require_once APP_PATH . '/dao/PenggunaDao.php';

class PenggunaController
{
    public function index()
    {
        $dao = new PenggunaDao();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $penggunaList = $dao->getPenggunaPage($limit,$offset);
        $totalData = $dao->countPengguna();
        $totalPage = ceil($totalData / $limit);
        $contentView = APP_PATH . '/view/pengguna/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function create() {
        $contentView = APP_PATH . '/view/pengguna/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function store(){
        $nama = $_POST['nama_lengkap'];
        $telp = $_POST['nomor_telepon'];
        $email = $_POST['email'];
        $password = $_POST['kata_sandi'];
        $role = $_POST['id_peran'];
        $fotoPath = null;
        if(!empty($_FILES['foto_ktp']['name'])){
            $targetDir = "public/img/ktp/";
            $extension = pathinfo($_FILES['foto_ktp']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid("ktp_") . "." . $extension;
            move_uploaded_file(
                $_FILES['foto_ktp']['tmp_name'],
                $targetDir . $fileName
            );
            $fotoPath = $targetDir . $fileName;
        }
        $dao = new PenggunaDao();
        $dao->insertPengguna(
            $role,
            $nama,
            $telp,
            $email,
            $password,
            $fotoPath
        );
        header("Location: /SobatKost/pengguna");
    }

    public function edit($id)
    {
        $dao = new PenggunaDao();
        $pengguna = $dao->getPenggunaById($id);
        if(!$pengguna){
            echo "<h3>Data pengguna tidak ditemukan</h3>";
            exit;
        }
        $contentView = APP_PATH . '/view/pengguna/edit.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = $_POST['nama_lengkap'];
            $email = $_POST['email'];
            $peran = $_POST['id_peran'];
            $password = $_POST['kata_sandi'] ?? null;
            $dao = new PenggunaDao();
            $dao->updatePengguna($id, $peran, $nama, $email, $password);
            header("Location: /SobatKost/pengguna");
            exit();
        }
    }

    public function delete($id)
    {
        $dao = new PenggunaDao();
        $pengguna = $dao->getPenggunaById($id);
        if(!$pengguna){
            echo "Data tidak ditemukan";
            exit;
        }
        $dao->deletePengguna($id);
        header("Location: /SobatKost/pengguna");
    }
}


