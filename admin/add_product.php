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
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <div class="dashboard-container">
        <nav class="sidebar">
            <h1 class="brand">Lapak Kaca</h1>
            <div class="menu">
                <a href="index.php">Dashboard</a>
                <a href="manage_shipping.php">Atur Status Pengiriman</a>
                <a href="all_users.php">Lihat Semua User</a>
                <a href="chat.php">Chat dengan User</a>
                <a href="../logout.php">Logout</a>
            </div>
        </nav>

        <main class="t-barang-main-content">
            <h2>Tambah Barang</h2>
            <form action="process_add_product.php" method="POST" enctype="multipart/form-data">
                <div class="input-barang">
                    <div class="nama-harga-gambar">
                        <label for="name">Nama Barang</label>
                        <input type="text" name="name" id="name" required>

                        <label for="price">Harga</label>
                        <input type="number" name="price" id="price" required>

                        <label for="image">Gambar</label>
                        <input type="file" name="image" id="image" required>
                    </div>
                    <div class="spesifikasi">
                        <h3>Spesifikasi Produk</h3>

                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" required></textarea>

                        <label for="ukuran">Ukuran</label>
                        <input type="text" name="ukuran" id="ukuran">

                        <label for="jenis_kaca">Jenis Kaca</label>
                        <input type="text" name="jenis_kaca" id="jenis_kaca">

                        <label for="bahan_rangka">Bahan Rangka</label>
                        <input type="text" name="bahan_rangka" id="bahan_rangka">

                        <label for="rak">Rak</label>
                        <input type="text" name="rak" id="rak">

                        <label for="pintu">Pintu</label>
                        <input type="text" name="pintu" id="pintu">

                        <label for="warna">Warna</label>
                        <input type="text" name="warna" id="warna">

                        <label for="berat">Berat</label>
                        <input type="text" name="berat" id="berat">

                        <label for="garansi">Garansi</label>
                        <input type="text" name="garansi" id="garansi">

                        <label for="roda">Roda</label>
                        <input type="text" name="roda" id="roda">
                    </div>
                </div>

                <button type="submit" class="btn">Tambah Barang</button>
            </form>
        </main>
    </div>
</body>

</html>