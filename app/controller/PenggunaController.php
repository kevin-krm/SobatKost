<?php
require_once APP_PATH . '/dao/PenggunaDao.php';
require_once APP_PATH . '/middleware/AuthMiddleware.php';

class PenggunaController
{
    public function __construct()
    {
        $this->penggunaDao = new PenggunaDao();
    }

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
        $email = $_POST['email'];
        $existingEmail = $this->penggunaDao->findByEmail($email);

        if ($existingEmail) {
            $error = "Email sudah digunakan.";
            $contentView = APP_PATH . '/view/pengguna/create.php';
            require_once APP_PATH . '/view/index.php';
            return;
        }

        $nama = $_POST['nama_lengkap'];
        $telp = $_POST['nomor_telepon'];
        $password = password_hash(
            $_POST['kata_sandi'],
            PASSWORD_DEFAULT
        );
        $role = $_POST['id_peran'];
        $statusAktif = $_POST['status_aktif'] ?? 'aktif';

        $fotoPath = null;

        if (!empty($_FILES['foto_ktp']['name']))
        {
            $targetDir = "public/img/data_img/ktp/";

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $extension = strtolower(
                pathinfo(
                    $_FILES['foto_ktp']['name'],
                    PATHINFO_EXTENSION
                )
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
            $fotoPath,
            $statusAktif
        );

        $dao = new PenggunaDao();
        $dao->insertPengguna($pengguna);

        header("Location: /SobatKost/pengguna");
        exit;
    }

    public function detail($id)
    {
        $dao = new PenggunaDao();
        $pengguna = $dao->getPenggunaById($id);

        if (!$pengguna) {
            echo "<h3>Data pengguna tidak ditemukan</h3>";
            exit;
        }
        $contentView = APP_PATH . '/view/pengguna/detail.php';
        require_once APP_PATH . '/view/index.php';
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
        $email = $_POST['email'];
        $existingEmail = $this->penggunaDao->findByEmail($email);

        if ($existingEmail && $existingEmail['id_pengguna'] != $id) {
            $error = "Email sudah digunakan.";
            $dao = new PenggunaDao();
            $pengguna = $dao->getPenggunaById($id);
            $contentView = APP_PATH . '/view/pengguna/edit.php';
            require_once APP_PATH . '/view/index.php';
            return;
        }

        $dao = new PenggunaDao();
        $penggunaLama = $dao->getPenggunaById($id);
        $password = $penggunaLama->getPassword();

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
                pathinfo(
                    $_FILES['foto_ktp']['name'],
                    PATHINFO_EXTENSION
                )
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

        $statusAktif = $_POST['status_aktif'] ?? $penggunaLama->getStatusAktif();

        $pengguna = new Pengguna(
            $id,
            $_POST['id_peran'],
            $_POST['nama_lengkap'],
            $_POST['nomor_telepon'],
            $_POST['email'],
            $password,
            null,
            $fotoPath,
            $statusAktif
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

        try {
            $dao->deletePengguna($id);

        } catch (PDOException $e) {
            $_SESSION['error'] =
                'Pengguna tidak dapat dihapus karena masih digunakan pada data lain.';
        }
        header("Location: /SobatKost/pengguna");
        exit;
    }
}