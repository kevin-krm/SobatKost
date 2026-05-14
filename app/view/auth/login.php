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
            >
        </div>

        <button type="submit" class="login-btn">
            Login
        </button>

    </form>

    <div class="footer-text">
        © 2026 SobatKost Management System
    </div>

</div>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>