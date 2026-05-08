<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<div class="login-container">
    <h2>Login SobatKost</h2>

    <?php if (isset($_SESSION['error'])) : ?>
        <p style="color:red;">
            <?= $_SESSION['error']; ?>
        </p>
        <?php unset($_SESSION['error']); endif; ?>

    <form method="POST" action="index.php?url=login/process">
        <div>
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div>
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>