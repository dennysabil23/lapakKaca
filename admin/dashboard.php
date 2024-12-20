<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../page/masuk.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Lapak Kaca</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <div class="dashboard-container">
        <nav class="sidebar">
            <h1 class="brand">Lapak Kaca</h1>
            <div class="menu">
                <a href="dashboard.php">Dashboard</a>
                <a href="manage_shipping.php">Atur Status Pengiriman</a>
                <a href="all_users.php">Lihat Semua User</a>
                <a href="chat.php">Chat dengan User</a>
                <a href="../logout.php">Logout</a>
            </div>
        </nav>
        <main class="main-content">
            <h1>Selamat datang, Admin!</h1>
            <p>Silakan pilih menu di sidebar untuk mengelola sistem.</p>
            <div class="card">
                <a href="add_product.php">Tambah Barang</a>
                <a href="manage_shipping.php">Atur Status Pengiriman</a>
                <a href="all_users.php">Lihat Semua User</a>
                <a href="../chat/index.php">Chat dengan User</a>
            </div>
        </main>
    </div>
</body>

</html>