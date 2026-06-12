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
        <h2>Password Baru</h2>
        <p>Silakan buat password baru Anda</p>
    </div>

    <?php if (isset($_SESSION['error'])) : ?>
        <div class="error-message">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="index.php?url=forgot-password/update" onsubmit="return validateForm();">

        <div class="form-group">
            <label>Password Baru</label>
            <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password baru"
                    required
                    onkeyup="checkPasswordMatch();"
            >
        </div>

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Ulangi password baru"
                    required
                    onkeyup="checkPasswordMatch();"
            >
            <div id="match-message" class="validation-msg"></div>
        </div>

        <button type="submit" class="reset-btn">
            Perbarui Password
        </button>

    </form>

    <a href="index.php?url=login" class="cancel-link" onclick="return confirm('Apakah Anda yakin ingin membatalkan reset password?');">
        Batal dan ke Login
    </a>

    <div class="footer-text">
        © 2026 SobatKost Management System
    </div>

</div>
<script src="public/js/auth.js"></script>
</body>
</html>
