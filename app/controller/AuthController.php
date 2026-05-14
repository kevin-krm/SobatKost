<?php
require_once APP_PATH . '/dao/PenggunaDao.php';

class AuthController
{
    private $penggunaDao;

    public function __construct()
    {
        $this->penggunaDao = new PenggunaDao();
    }

    public function login()
    {
        if (isset($_SESSION['user'])) {

            // Penyewa
            if ($_SESSION['user']['id_peran'] == 3) {
                header('Location: http://localhost/SobatKost/user');
                exit;
            }
            // Owner & Penjaga
            header('Location: index.php?url=home');
            exit;
        }
        require_once APP_PATH . '/view/auth/login.php';
    }

    public function loginProcess()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $user = $this->penggunaDao->login($email);

        if (!$user) {
            $_SESSION['error'] = 'Email tidak ditemukan';
            header('Location: index.php?url=login');
            exit;
        }

        if (!password_verify($password, $user['kata_sandi'])) {
            $_SESSION['error'] = 'Password salah';
            header('Location: index.php?url=login');
            exit;
        }

        $_SESSION['user'] = [
            'id' => $user['id_pengguna'],
            'nama' => $user['nama_lengkap'],
            'email' => $user['email'],
            'id_peran' => $user['id_peran'],
            'role' => $user['nama_peran']
        ];

        // Penyewa
        if ($user['id_peran'] == 3) {
            header('Location: http://localhost/SobatKost/user');
            exit;
        }
        // Owner & Penjaga
        header('Location: index.php?url=home');
        exit;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: index.php?url=login');
        exit;
    }
}