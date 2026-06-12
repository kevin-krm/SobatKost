<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SobatKost Login</title>
    <link rel="stylesheet" href="public/css/login.css">
</head>
<body>
<div class="login-container">
    <div class="login-header">
        <h2>SobatKost Login</h2>
        <p>Silahkan masuk untuk melanjutkan</p>
    </div>

    <?php if (isset($_SESSION['error'])) : ?>
        <div class="error-message">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])) : ?>
        <div class="success-message" style="background: #e6f9ed; color: #2ecc71; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #c2f0d0;">
            <?= $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form method="POST" action="index.php?url=login/process">

        <div class="form-group">
            <label>Email</label>
            <input
                    type="email"
                    name="email"
                    placeholder="Masukkan email"
                    required
            >
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input
                    type="password"
                    class="form-control"
                    name="password"
                    required
            >
            <div style="text-align: right; margin-top: 8px;">
                <a href="index.php?url=forgot-password" style="color: #4F46E5; text-decoration: none; font-size: 13px; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#3730A3'" onmouseout="this.style.color='#4F46E5'">Lupa Password?</a>
            </div>
        </div>

        <button type="submit" class="login-btn">
            Login
        </button>

    </form>

    <div class="login-help" style="margin-top: 20px; font-size: 13px; color: #7f8c8d; text-align: center; line-height: 1.5;">
        Jika Anda mengalami kesulitan login (lupa password, dll) silakan hubungi email admin <a href="mailto:jonathan@sobatkost.com" style="color: #4F46E5; text-decoration: none; font-weight: bold;">jonathan@sobatkost.com</a>
    </div>

    <div class="footer-text">
        © 2026 SobatKost Management System
    </div>

</div>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>