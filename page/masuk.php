<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/masukk.css">
    <link rel="shortcut icon" href="../asset/favicon.png" type="image/x-icon">
    <title>Masuk</title>
</head>

<body>
    <div class="login-container">
        <img src="../asset/lapakkaca-logo.png" alt="Logo">

        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php elseif (isset($_GET['success'])): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
        
        <form action="../proses-masuk.php" method="post">
            <input type="text" name="username_or_email" placeholder="Username atau Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <a class="forget" href="">Lupa password?</a> <br>
            <button type="submit">Masuk</button> <br>
            <a class="daftar" href="daftar.php" target="_blank">Daftar</a> <br>
            <br>
        </form>
    </div>
</body>

</html>