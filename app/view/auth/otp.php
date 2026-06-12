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
        <h2>Verifikasi OTP</h2>
        <p>Konfirmasi identitas Anda</p>
    </div>

    <div class="otp-info">
        Silahkan cek OTP yang dikirimkan ke e-mail:<br>
        <strong><?= htmlspecialchars($_SESSION['reset_email'] ?? ''); ?></strong>
    </div>

    <?php if (isset($_SESSION['error'])) : ?>
        <div class="error-message">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="index.php?url=forgot-password/verify-otp">

        <div class="form-group">
            <label style="text-align: center; display: block;">Masukkan Kode OTP</label>
            <div class="otp-input-container">
                <input
                        type="text"
                        name="otp"
                        class="otp-input"
                        placeholder="••••••"
                        maxlength="6"
                        required
                        autocomplete="off"
                        pattern="[0-9]{6}"
                        title="Kode OTP harus berupa 6 digit angka"
                >
            </div>
            <p style="text-align: center; font-size: 11px; color: #95a5a6; margin-top: -10px;">
                (Hint: gunakan kode <strong>123456</strong>)
            </p>
        </div>

        <button type="submit" class="otp-btn">
            Verifikasi Kode
        </button>

    </form>

    <div class="resend-container">
        Tidak menerima kode? 
        <a href="#" class="resend-link" onclick="alert('Kode OTP baru telah dikirimkan ke e-mail Anda.'); return false;">
            Kirim ulang kode OTP
        </a>
    </div>

    <a href="index.php?url=login" class="cancel-link">
        Kembali ke Login
    </a>

    <div class="footer-text">
        © 2026 SobatKost Management System
    </div>

</div>
</body>
</html>
