<?php
require_once APP_PATH . '/dao/PenggunaDao.php';

class PenggunaController
{
    public function index()
    {
        $dao = new PenggunaDao();

        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $penggunaList = $dao->getPenggunaPage($limit, $offset);
        $totalData = $dao->countPengguna();
        $totalPage = ceil($totalData / $limit);

        $contentView = APP_PATH . '/view/pengguna/index.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function create()
    {
        $contentView = APP_PATH . '/view/pengguna/create.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function store()
    {
        $nama = $_POST['nama_lengkap'];
        $telp = $_POST['nomor_telepon'];
        $email = $_POST['email'];
        $password = $_POST['kata_sandi'];
        $role = $_POST['id_peran'];

        $fotoPath = null;

        if (!empty($_FILES['foto_ktp']['name']))
        {
            $targetDir = "public/img/data_img/ktp/";

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $extension = strtolower(
                pathinfo($_FILES['foto_ktp']['name'], PATHINFO_EXTENSION)
            );

            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($extension, $allowed)) {
                die("Format file tidak valid");
            }

            $fileName = uniqid("ktp_") . "." . $extension;

            move_uploaded_file(
                $_FILES['foto_ktp']['tmp_name'],
                $targetDir . $fileName
            );

            $fotoPath = $targetDir . $fileName;
        }

        $pengguna = new Pengguna(
            null,
            $role,
            $nama,
            $telp,
            $email,
            $password,
            null,
            $fotoPath
        );

        $dao = new PenggunaDao();
        $dao->insertPengguna($pengguna);

        header("Location: /SobatKost/pengguna");
        exit;
    }

    public function edit($id)
    {
        $dao = new PenggunaDao();
        $pengguna = $dao->getPenggunaById($id);

        if (!$pengguna) {
            echo "<h3>Data pengguna tidak ditemukan</h3>";
            exit;
        }

        $contentView = APP_PATH . '/view/pengguna/edit.php';
        require_once APP_PATH . '/view/index.php';
    }

    public function update($id)
    {
        $dao = new PenggunaDao();
        $penggunaLama = $dao->getPenggunaById($id);

        $password = !empty($_POST['kata_sandi'])
            ? $_POST['kata_sandi']
            : $penggunaLama->getPassword();

        $fotoPath = $penggunaLama->getFotoKtp();

        if (!empty($_FILES['foto_ktp']['name']))
        {
            $targetDir = "public/img/data_img/ktp/";

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            if (!empty($fotoPath) && file_exists($fotoPath)) {
                unlink($fotoPath);
            }

            $extension = strtolower(
                pathinfo($_FILES['foto_ktp']['name'], PATHINFO_EXTENSION)
            );

            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($extension, $allowed)) {
                die("Format file tidak valid");
            }

            $fileName = "ktp_" . $id . "." . $extension;

            move_uploaded_file(
                $_FILES['foto_ktp']['tmp_name'],
                $targetDir . $fileName
            );

            $fotoPath = $targetDir . $fileName;
        }

        $pengguna = new Pengguna(
            $id,
            $_POST['id_peran'],
            $_POST['nama_lengkap'],
            $_POST['nomor_telepon'],
            $_POST['email'],
            $password,
            null,
            $fotoPath
        );

        $dao->updatePengguna($pengguna);

        header("Location: /SobatKost/pengguna");
        exit;
    }

    public function delete($id)
    {
        $dao = new PenggunaDao();
        $pengguna = $dao->getPenggunaById($id);

        if (!$pengguna) {
            echo "Data tidak ditemukan";
            exit;
        }

        $dao->deletePengguna($id);

        header("Location: /SobatKost/pengguna");
        exit;
    }
}