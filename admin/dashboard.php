<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: ../page/masuk.php');
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    echo "<p>Akses ditolak. Anda bukan admin.</p>";
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
            <ul class="menu">
                <li><a href="#" data-section="add-product" class="menu-link active">Tambah Barang</a></li>
                <li><a href="#" data-section="product-list" class="menu-link">Daftar Barang</a></li>
                <li><a href="#" data-section="manage-shipping" class="menu-link">Atur Status Pengiriman</a></li>
                <li><a href="#" data-section="all-users" class="menu-link">Lihat Semua User</a></li>
                <li><a href="#" data-section="chat" class="menu-link">Chat dengan User</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>

            <!--  -->

        </nav>

        <main class="main-content">
            <!-- Tambah Barang -->
            <section id="add-product" class="section active">
                <h2>Tambah Barang</h2>
                <form action="add_product.php" method="POST" enctype="multipart/form-data">
                    <label for="name">Nama Barang</label>
                    <input type="text" name="name" id="name" required>

                    <label for="price">Harga</label>
                    <input type="number" name="price" id="price" required>

                    <label for="image">Gambar</label>
                    <input type="file" name="image" id="image" required>

                    <button type="submit" class="btn">Tambah Barang</button>
                </form>
            </section>

            <!-- Daftar Barang -->
            <section id="product-list" class="section">
                <h2>Daftar Barang</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Spesifikasi</th>
                            <th>Gambar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include '../koneksi.php';
                        $query = "SELECT p.name AS nama_barang, p.price, s.ukuran, s.jenis_kaca, 
                                s.bahan_rangka, s.rak, s.warna, p.image
                                FROM products AS p
                                LEFT JOIN spek_produk AS s ON p.id_product = s.id_product";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['nama_barang']}</td>
                                    <td>Rp " . number_format($row['price'], 0, ',', '.') . "</td>
                                    <td>
                                        <ul>
                                            <li>Ukuran: {$row['ukuran']}</li>
                                            <li>Jenis Kaca: {$row['jenis_kaca']}</li>
                                            <li>Bahan Rangka: {$row['bahan_rangka']}</li>
                                            <li>Rak: {$row['rak']}</li>
                                            <li>Warna: {$row['warna']}</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <img src='../asset/gambar-produk/" . htmlspecialchars($row['image']) . "' 
                                            alt='" . htmlspecialchars($row['nama_barang']) . "' 
                                            style='width: 100px; height: auto; border-radius: 4px;'>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Tidak ada barang yang tersedia.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <!-- Atur Status Pengiriman -->
            <section id="manage-shipping" class="section">
                <h2>Atur Status Pengiriman</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID Pengiriman</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM pengiriman";
                        $result = $conn->query($query);
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id_pengiriman']}</td>
                                    <td>{$row['id_alamat']}</td>
                                    <td>{$row['status_pengiriman']}</td>
                                    <td>
                                        <form action='update_shipping.php' method='POST'>
                                            <input type='hidden' name='id_pengiriman' value='{$row['id_pengiriman']}'>
                                            <select name='status_pengiriman'>
                                                <option value='diproses'>Diproses</option>
                                                <option value='dikirim'>Dikirim</option>
                                                <option value='selesai'>Selesai</option>
                                                <option value='dibatalkan'>Dibatalkan</option>
                                            </select>
                                            <button type='submit' class='btn'>Update</button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <!-- Semua User -->
            <section id="all-users" class="section">
                <h2>Semua User</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID User</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM users";
                        $result = $conn->query($query);
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id_user']}</td>
                                    <td>{$row['username']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['role']}</td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <!-- Chat dengan User -->
            <section id="chat" class="section">
                <h2>Chat dengan User</h2>
                <form action="send_message.php" method="POST">
                    <label for="user">Pilih User</label>
                    <select name="id_user" id="user">
                        <?php
                        $query = "SELECT id_user, username FROM users";
                        $result = $conn->query($query);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id_user']}'>{$row['username']}</option>";
                        }
                        ?>
                    </select>

                    <label for="message">Pesan</label>
                    <textarea name="message" id="message" rows="4" required></textarea>

                    <button type="submit" class="btn">Kirim Pesan</button>
                </form>
            </section>
        </main>
    </div>
    <script src="../js/admin.js"></script>
</body>

</html>