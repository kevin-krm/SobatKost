<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SobatKost</title>
    <link rel="stylesheet" href="public/css/login.css">
</head>
<body>
<div class="login-container">
    <div class="login-header">
        <h2>Lupa Password</h2>
        <p>Atur ulang kata sandi akun Anda</p>
    </div>

    <?php if (isset($_SESSION['error'])) : ?>
        <div class="error-message">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="index.php?url=forgot-password/submit-email">

        <div class="form-group">
            <label>Alamat E-mail</label>
            <input
                    type="email"
                    name="email"
                    placeholder="Masukkan e-mail terdaftar"
                    required
            >
            <div class="info-text">
                ℹ️ E-mail ini akan digunakan untuk mengirimkan kode verifikasi OTP. Silahkan masukkan e-mail yang terkait dengan akun SobatKost Anda.
            </div>
        </div>

        <div class="btn-group">
            <a href="index.php?url=login" class="cancel-btn">
                Batalkan
            </a>
            <button type="submit" class="forgot-btn">
                Kirim OTP
            </button>
        </div>

    </form>

    <div class="footer-text">
        © 2026 SobatKost Management System
    </div>

</div>
</body>
</html>
