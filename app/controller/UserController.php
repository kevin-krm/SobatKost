<?php
require_once APP_PATH . '/dao/PenggunaDao.php';

class UserController
{
    private $penggunaDao;

    public function __construct()
    {
        $this->penggunaDao = new PenggunaDao();
    }

    public function about()
    {
        $id_pengguna = $_SESSION['user']['id'];
        $pengguna = $this->penggunaDao->getPenggunaById($id_pengguna);

        if (!$pengguna) {
            echo "<h3>Data pengguna tidak ditemukan</h3>";
            exit;
        }

        $contentView = APP_PATH . '/view/user/about/index.php';
        require_once APP_PATH . '/view/user/index.php';
    }

    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /SobatKost/index.php?url=user/about");
            exit;
        }

        $id_pengguna = $_SESSION['user']['id'];
        $pengguna = $this->penggunaDao->getPenggunaById($id_pengguna);

        if (!$pengguna) {
            $_SESSION['error'] = 'Pengguna tidak ditemukan';
            header("Location: /SobatKost/index.php?url=user/about");
            exit;
        }

        $password_lama = $_POST['password_lama'];
        $password_baru = $_POST['password_baru'];
        $konfirmasi_password = $_POST['konfirmasi_password'];

        // Verify old password
        if (!password_verify($password_lama, $pengguna->getPassword())) {
            $_SESSION['error'] = 'Password lama salah!';
            header("Location: /SobatKost/index.php?url=user/about");
            exit;
        }

        // Check password length
        if (strlen($password_baru) < 4) {
            $_SESSION['error'] = 'Password baru minimal harus 4 karakter!';
            header("Location: /SobatKost/index.php?url=user/about");
            exit;
        }

        // Match new passwords
        if ($password_baru !== $konfirmasi_password) {
            $_SESSION['error'] = 'Konfirmasi password baru tidak cocok!';
            header("Location: /SobatKost/index.php?url=user/about");
            exit;
        }

        // Hash new password and update
        $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
        
        $updatedPengguna = new Pengguna(
            $pengguna->getId(),
            $pengguna->getIdPeran(),
            $pengguna->getNamaLengkap(),
            $pengguna->getNomorTelepon(),
            $pengguna->getEmail(),
            $hashed_password,
            $pengguna->getCreatedAt(),
            $pengguna->getFotoKtp(),
            $pengguna->getStatusAktif()
        );

        $this->penggunaDao->updatePengguna($updatedPengguna);

        $_SESSION['success'] = 'Password Anda berhasil diperbarui!';
        header("Location: /SobatKost/index.php?url=user/about");
        exit;
    }
}
