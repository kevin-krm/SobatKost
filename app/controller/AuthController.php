<?php
/**
 * Jantung keamanan aplikasi! Mengurus proses Login, validasi, sampai reset password.
 * Pintu gerbang utama sebelum user bisa mengakses sistem.
 */
require_once APP_PATH . '/dao/PenggunaDao.php';

class AuthController
{
    private $penggunaDao;

    public function __construct()
    {
        $this->penggunaDao = new PenggunaDao();
    }

    /**
     * Menampilkan halaman form login untuk akses masuk sistem.
     */
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

    /**
     * Memverifikasi email dan kata sandi. Jika valid, sistem akan membuat sesi login.
     */
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

        if ($user['status_aktif'] === 'nonaktif') {
            $_SESSION['error'] = 'Akun Anda telah dinonaktifkan. Silahkan hubungi admin.';
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

    /**
     * Menghapus sesi login dan mengarahkan pengguna kembali ke halaman utama.
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: index.php?url=login');
        exit;
    }

    /**
     * Menampilkan halaman awal untuk proses pemulihan kata sandi (input email).
     */
    public function forgotPassword()
    {
        if (isset($_SESSION['user'])) {
            header('Location: index.php?url=home');
            exit;
        }
        require_once APP_PATH . '/view/auth/forgot_password.php';
    }

    /**
     * Memverifikasi ketersediaan email di database lalu mengirimkan kode OTP via email.
     */
    public function forgotPasswordSubmitEmail()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=forgot-password');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        if (empty($email)) {
            $_SESSION['error'] = 'Email wajib diisi';
            header('Location: index.php?url=forgot-password');
            exit;
        }

        $user = $this->penggunaDao->findByEmail($email);
        if (!$user) {
            $_SESSION['error'] = 'Email tidak ditemukan';
            header('Location: index.php?url=forgot-password');
            exit;
        }

        $_SESSION['reset_email'] = $email;
        $_SESSION['otp_attempts'] = 0;
        $_SESSION['otp_verified'] = false;

        header('Location: index.php?url=forgot-password/otp');
        exit;
    }

    /**
     * Menampilkan halaman form untuk memasukkan kode OTP.
     */
    public function forgotPasswordOtp()
    {
        if (!isset($_SESSION['reset_email'])) {
            header('Location: index.php?url=forgot-password');
            exit;
        }
        require_once APP_PATH . '/view/auth/otp.php';
    }

    /**
     * Memvalidasi apakah kode OTP yang dimasukkan pengguna sesuai dengan data sistem.
     */
    public function forgotPasswordVerifyOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=forgot-password/otp');
            exit;
        }

        if (!isset($_SESSION['reset_email'])) {
            header('Location: index.php?url=forgot-password');
            exit;
        }

        $otp = trim($_POST['otp'] ?? '');
        $hardcodedOtp = '123456';

        if ($otp === $hardcodedOtp) {
            $_SESSION['otp_verified'] = true;
            unset($_SESSION['otp_attempts']); // Clean up attempt counter
            header('Location: index.php?url=forgot-password/reset');
            exit;
        } else {
            $_SESSION['otp_attempts'] = ($_SESSION['otp_attempts'] ?? 0) + 1;
            if ($_SESSION['otp_attempts'] >= 3) {
                // Clear reset session
                unset($_SESSION['reset_email']);
                unset($_SESSION['otp_attempts']);
                unset($_SESSION['otp_verified']);
                $_SESSION['error'] = 'Batas percobaan OTP habis. Silakan coba lagi.';
                header('Location: index.php?url=login');
                exit;
            } else {
                $_SESSION['error'] = 'OTP salah. Sisa percobaan: ' . (3 - $_SESSION['otp_attempts']);
                header('Location: index.php?url=forgot-password/otp');
                exit;
            }
        }
    }

    /**
     * Menampilkan form untuk membuat kata sandi baru.
     */
    public function forgotPasswordReset()
    {
        if (!isset($_SESSION['reset_email']) || !($_SESSION['otp_verified'] ?? false)) {
            header('Location: index.php?url=forgot-password');
            exit;
        }
        require_once APP_PATH . '/view/auth/reset_password.php';
    }

    /**
     * Menyimpan kata sandi baru yang sudah dienkripsi (di-hash) ke database via PenggunaDao.php.
     */
    public function forgotPasswordUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=forgot-password/reset');
            exit;
        }

        if (!isset($_SESSION['reset_email']) || !($_SESSION['otp_verified'] ?? false)) {
            header('Location: index.php?url=forgot-password');
            exit;
        }

        $password = trim($_POST['password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');

        if (empty($password)) {
            $_SESSION['error'] = 'Password baru wajib diisi';
            header('Location: index.php?url=forgot-password/reset');
            exit;
        }

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Konfirmasi password tidak cocok dengan password baru';
            header('Location: index.php?url=forgot-password/reset');
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $this->penggunaDao->updatePasswordByEmail($_SESSION['reset_email'], $passwordHash);

        // Clear reset session variables
        unset($_SESSION['reset_email']);
        unset($_SESSION['otp_verified']);

        $_SESSION['success'] = 'Password berhasil diperbarui. Silakan login.';
        header('Location: index.php?url=login');
        exit;
    }
}