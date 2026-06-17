<?php
require_once APP_PATH . '/dao/PenggunaDao.php';

class UserController
{
    private $penggunaDao;

    public function __construct()
    {
        $this->penggunaDao = new PenggunaDao();
    }

    /**
     * Menampilkan halaman khusus mengenai informasi profil layanan kost beserta kontak pemilik properti.
     */
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

    /**
     * Memfasilitasi pengguna untuk melakukan pembaruan kata sandi mereka secara mandiri setelah tahap login.
     */
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

    /**
     * Menampilkan halaman pengaturan akun untuk mengganti alamat email yang digunakan pengguna.
     */
    public function changeEmail()
    {
        $id_pengguna = $_SESSION['user']['id'];
        $pengguna = $this->penggunaDao->getPenggunaById($id_pengguna);

        if (!$pengguna) {
            echo "<h3>Data pengguna tidak ditemukan</h3>";
            exit;
        }

        $contentView = APP_PATH . '/view/user/about/change_email.php';
        require_once APP_PATH . '/view/user/index.php';
    }

    /**
     * Memproses penyimpanan perubahan alamat email pengguna ke dalam sistem database.
     */
    public function updateEmail()
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

        $email_baru = trim($_POST['email_baru'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email_baru) || empty($password)) {
            $_SESSION['error'] = 'Semua field harus diisi!';
            header("Location: /SobatKost/index.php?url=user/about/changeEmail");
            exit;
        }

        // Verify password
        if (!password_verify($password, $pengguna->getPassword())) {
            $_SESSION['error'] = 'Password salah!';
            header("Location: /SobatKost/index.php?url=user/about/changeEmail");
            exit;
        }

        // Check if email already exists for another user
        $existingEmail = $this->penggunaDao->findByEmail($email_baru);
        if ($existingEmail && $existingEmail['id_pengguna'] != $id_pengguna) {
            $_SESSION['error'] = 'Email sudah digunakan oleh akun lain!';
            header("Location: /SobatKost/index.php?url=user/about/changeEmail");
            exit;
        }

        // Check if new email is identical to current email
        if ($email_baru === $pengguna->getEmail()) {
            $_SESSION['error'] = 'Email baru tidak boleh sama dengan email lama!';
            header("Location: /SobatKost/index.php?url=user/about/changeEmail");
            exit;
        }

        // Update email in DB
        $updatedPengguna = new Pengguna(
            $pengguna->getId(),
            $pengguna->getIdPeran(),
            $pengguna->getNamaLengkap(),
            $pengguna->getNomorTelepon(),
            $email_baru,
            $pengguna->getPassword(),
            $pengguna->getCreatedAt(),
            $pengguna->getFotoKtp(),
            $pengguna->getStatusAktif()
        );

        $this->penggunaDao->updatePengguna($updatedPengguna);

        // Clear session and logout user
        session_unset();
        session_destroy();

        // Start new session to store success message for the login view
        session_start();
        $_SESSION['success'] = 'Email berhasil diperbarui secara mandiri. Silakan login kembali dengan email baru Anda.';
        header("Location: /SobatKost/index.php?url=login");
        exit;
    }
}
