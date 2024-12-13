<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: masuk.php?login_required");
    exit();
}

$id_user = $_SESSION['user_id'];

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_penerima = $_POST['nama_penerima'];
    $nomor_telpon = $_POST['nomor_telpon'];
    $kecamatan = $_POST['kecamatan'];
    $kota = $_POST['kota'];
    $provinsi = $_POST['provinsi'];
    $kode_pos = $_POST['kode_pos'];
    $nama_jalan = $_POST['nama_jalan'];
    $patokan = $_POST['patokan'];
    $status_alamat = $_POST['status_alamat'];
    $stmt = $conn->prepare("INSERT INTO alamat (id_user, nama_penerima, nomor_telpon, kecamatan, kota, provinsi, kode_pos, nama_jalan, patokan, status_alamat) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssss", $id_user, $nama_penerima, $nomor_telpon, $kecamatan, $kota, $provinsi, $kode_pos, $nama_jalan, $patokan, $status_alamat);

    if ($stmt->execute()) {
        header("Location: user.php?alamat_added=true");
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
}

$sql_profile = "SELECT * FROM users WHERE id_user = ?";
$stmt_profile = $conn->prepare($sql_profile);
$stmt_profile->bind_param("i", $id_user);
$stmt_profile->execute();
$result_profile = $stmt_profile->get_result();
$user_profile = $result_profile->fetch_assoc();
$stmt_profile->close();

$sql_alamat = "SELECT * FROM alamat WHERE id_user = ?";
$stmt_alamat = $conn->prepare($sql_alamat);
$stmt_alamat->bind_param("i", $id_user);
$stmt_alamat->execute();
$result_alamat = $stmt_alamat->get_result();
$alamat_id = $result_alamat->fetch_all(MYSQLI_ASSOC);
$stmt_alamat->close();

$sql_pembelian = "SELECT p.name AS nama_produk, 
                         p.image AS foto_produk, 
                         pb.quantity, 
                         pb.total_pembayaran, 
                         pb.status_pembelian, 
                         pb.waktu_pembelian 
                  FROM pembelian pb 
                  JOIN products p ON pb.id_product = p.id_product 
                  WHERE pb.id_user = ?";
$stmt_pembelian = $conn->prepare($sql_pembelian);
$stmt_pembelian->bind_param("i", $id_user);
$stmt_pembelian->execute();
$result_pembelian = $stmt_pembelian->get_result();
$pembelian_id = $result_pembelian->fetch_all(MYSQLI_ASSOC);
$stmt_pembelian->close();

$conn->close();

$profile_picture = $user_profile['profile_picture'] ? '../uploads/' . htmlspecialchars($user_profile['profile_picture']) : '../asset/default-pic.png';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan</title>
    <link rel="stylesheet" href="../css/user.css">
    <link rel="shortcut icon" href="../asset/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <header>
        <div class="header-container">
            <div class="logo" onclick="window.location.href='index.php'">
                <img src="../asset/lapakkaca-logo2.png" alt="Lapak Kaca Logo" />
            </div>
        </div>
    </header>
    <div class="container">
        <div class="sidebar">
            <div class="profile">
                <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="profile-pic">
                <h2><?php echo htmlspecialchars($user_profile['long_name']); ?></h2>
            </div>
            <ul class="menu">
                <li data-section="profile" onclick="showContent('profile')">Profil Saya</li>
                <li data-section="orders" onclick="showContent('orders')">Pesanan</li>
                <li data-section="alamat" onclick="showContent('alamat')">Alamat</li>
                <li data-section="riwayat" onclick="showContent('riwayat')">Riwayat Pesanan</li>
            </ul>
            <div class="logout">
                <button onclick="window.location.href='../logout.php'">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Logout</span>
                </button>
            </div>
        </div>
        <div class="content">
            <div id="profile" class="content-section active update-form">
                <h2>Profil Saya</h2>
                <p>Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun
                </p>
                <form action="../update-profile.php" method="POST" enctype="multipart/form-data">
                    <div class="form-container">
                        <div class="tabel-profile">
                            <table>
                                <tr>
                                    <th>Username</th>
                                    <td><?php echo htmlspecialchars($user_profile['username']); ?></td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td><input type="text" name="long_name" size="50" value="<?php echo htmlspecialchars($user_profile['long_name']); ?>"></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>
                                        <?php
                                        $email = htmlspecialchars($user_profile['email']);
                                        $email_parts = explode("@", $email);
                                        $email_name = $email_parts[0];
                                        $email_domain = $email_parts[1];
                                        $email_masked = substr($email_name, 0, 2) . str_repeat("*", strlen($email_name) - 2) . "@" . $email_domain;
                                        echo $email_masked;
                                        ?><a href=""> <i class="bi bi-pencil-square"></i> Ubah</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nomor Telepon</th>
                                    <td>
                                        <input type="text" name="nomor_telpon" value="<?php echo htmlspecialchars($user_profile['nomor_telpon']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>
                                        <input type="radio" id="male" name="gender" value="Laki-laki" <?php echo $user_profile['gender'] === 'Laki-laki' ? 'checked' : ''; ?>>
                                        <label for="male">Laki-laki</label>

                                        <input type="radio" id="female" name="gender" value="Perempuan" <?php echo $user_profile['gender'] === 'Perempuan' ? 'checked' : ''; ?>>
                                        <label for="female">Perempuan</label>
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <button type="submit" name="update_profile">Simpan</button>
                        </div>
                        <div class="update-pic-profile">
                            <img id="previewImage" src="<?php echo $profile_picture; ?>" alt="Profile Picture">
                            <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                        </div>
                </form>
            </div>
        </div>

        <!-- Area Pesanan -->

        <div id="orders" class="content-section active orders">
            <h2>Pesanan Saya</h2>
            <p class="informasi">Status pesanan Anda ditampilkan disini</p>
            <?php if (!empty($pembelian_id)): ?>
                <div class="garis"></div>
                <div class="desc-produk">
                    <?php foreach ($pembelian_id as $index => $pembelian): ?>
                        <div class="order-flex">
                            <div class="gambar">
                                <img src="../asset/gambar-produk/<?= htmlspecialchars($pembelian['foto_produk']); ?>" alt="<?= htmlspecialchars($pembelian['nama_produk']); ?>">
                            </div>
                            <div class="ket-produk">
                                <h2><?= htmlspecialchars($pembelian['nama_produk']); ?></h2>
                                <p class="quantity">x<?= htmlspecialchars($pembelian['quantity']); ?></p>
                                <p class="harga">Rp<?= number_format($pembelian['total_pembayaran'], 0, ',', '.'); ?></p>
                            </div>
                            <div class="info-order">
                                <p><?= htmlspecialchars($pembelian['status_pembelian']); ?></p>
                                <p class="waktu"><?= htmlspecialchars($pembelian['waktu_pembelian']); ?></p>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Anda belum memiliki pesanan.</p>
            <?php endif; ?>
        </div>

        <!-- Area Alamat -->

        <div id="alamat" class="content-section active">
            <div class="header-section">
                <h2>Daftar Alamat</h2>
                <button onclick="openModal()"> <i class="bi bi-house-add-fill"></i> Tambahkan Alamat</button>
            </div>
            <?php
            if (isset($_GET['alamat_deleted']) && $_GET['alamat_deleted'] == 'true') {
                echo "<p style='color: green; font-weight: bold; font-size: 10pt; margin-bottom: 20px;'>Alamat berhasil dihapus.</p>";
            }
            ?>
            <?php if (!empty($alamat_id)): ?>
                <?php foreach ($alamat_id as $alamat): ?>
                    <div class="alamat-card">
                        <p><?= htmlspecialchars($alamat['status_alamat']) ?></p>
                        <div class="nama-telpon">
                            <p class="a1"><?= htmlspecialchars($alamat['nama_penerima']) ?></p>
                            <p class="a2"><?= htmlspecialchars($alamat['nomor_telpon']) ?></p>
                        </div>
                        <p><?= htmlspecialchars($alamat['nama_jalan']) ?></p>
                        <div class="kecamatan-kodepos">
                            <p><?= htmlspecialchars($alamat['kecamatan']) ?><span>,</span></p>
                            <p><?= htmlspecialchars($alamat['kota']) ?><span>,</span></p>
                            <p><?= htmlspecialchars($alamat['provinsi']) ?><span>,</span></p>
                            <p> <span>Kode Pos</span> <?= htmlspecialchars($alamat['kode_pos']) ?></p>
                        </div>
                        <p><span>(</span><?= htmlspecialchars($alamat['patokan']) ?><span>)</span></p>

                        <form action="../hapus-alamat.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?');">
                            <input type="hidden" name="id_alamat" value="<?= $alamat['id_alamat'] ?>">
                            <button type="submit"> <i class="bi bi-trash3-fill"></i> Hapus</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="kosong">
                    <p>Anda belum menambahkan alamat.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- area input alamat -->

        <div id="alamatModal" class="modal">
            <div class="modal-content">
                <div class="modal-head">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2>Buat Alamat Baru</h2>
                </div>
                <div class="modal-body">
                    <form action="user.php" method="POST" id="alamat-form">
                        <div class="form-group status-alamat">
                            <div class="label-alamat">
                                <span>Tandai Sebagai:</span>
                            </div>
                            <div class="radio-group">
                                <input type="radio" id="rumah" name="status_alamat" value="Rumah" required>
                                <label for="rumah" class="radio-label">Rumah</label>

                                <input type="radio" id="kantor" name="status_alamat" value="Kantor" required>
                                <label for="kantor" class="radio-label">Kantor</label>
                            </div>
                        </div>
                        <div class="form-group nama floating-label">
                            <input type="text" id="nama_penerima" name="nama_penerima" placeholder="Nama Lengkap Penerima" required>
                            <label for="nama_penerima">Nama Lengkap Penerima</label>
                        </div>

                        <div class="form-group telepon floating-label">
                            <input type="number" id="nomor_telepon" name="nomor_telpon" placeholder="No. Telepon" required>
                            <label for="nomor_telepon">No. Telepon</label>
                        </div>

                        <div class="form-group kecamatan floating-label">
                            <input type="text" id="kecamatan" name="kecamatan" placeholder="Kecamatan" required>
                            <label for="kecamatan">Kecamatan</label>
                        </div>

                        <div class="form-group kota floating-label">
                            <input type="text" id="kota" name="kota" placeholder="Kota / Kabupaten" required>
                            <label for="kota">Kota atau Kabupaten</label>
                        </div>

                        <div class="form-group provinsi floating-label">
                            <input type="text" id="provinsi" name="provinsi" placeholder="Provinsi" required>
                            <label for="provinsi">Provinsi</label>
                        </div>

                        <div class="form-group kodepos floating-label">
                            <input type="text" id="kode_pos" name="kode_pos" placeholder="Kode Pos" required>
                            <label for="kode_pos">Kode Pos</label>
                        </div>

                        <div class="form-group jalan floating-label">
                            <textarea type="text" id="nama_jalan" name="nama_jalan" rows="6" placeholder="Kelurahan / Nama Jalan, Gedung, No. Rumah" required></textarea>
                            <label for="nama_jalan">Kelurahan atau Nama Jalan, Gedung, No. Rumah</label>
                        </div>

                        <div class="form-group patokan floating-label">
                            <textarea id="patokan" rows="1" name="patokan" placeholder="Detail Lainnya (Blok / Patokan)"></textarea>
                            <label for="patokan">Patokan</label>
                        </div>
                    </form>
                </div>
                <div class="modal-bottom">
                    <button type="submit" form="alamat-form" onclick="openNotifModal()">Simpan</button>
                </div>
            </div>
        </div>
        <div id="notifModal" class="modal">
            <div class="modal-content pesan">
                <i class="bi bi-check-circle-fill"></i>
                <p>Alamat berhasil ditambahkan</p>
            </div>
        </div>
        <div id="riwayat" class="content-section active">
            <h2>Riwayat Pesanan</h2>
            <p>Lorem ipsum dolor sit amet.</p>
        </div>
    </div>
    </div>
    <footer>
        <div class="footer-container">
            <div class="footer-detail">
                <h4>Tentang Kami</h4><br>
                <p>
                    Toko Etalase kami menyediakan berbagai macam etalase dengan
                    kualitas kaca terbaik yang dapat memenuhi kebutuhan bisnis
                    Anda. Kami melayani pengiriman ke seluruh daerah di Pulau
                    Jawa dengan harga yang sudah termasuk ongkos kirim, sehingga
                    Anda tidak perlu khawatir dengan biaya tambahan. Proses
                    pemesanan dimulai dengan DP (Down Payment) terlebih dahulu,
                    dan kami juga menyediakan opsi COD (Cash on Delivery) dengan
                    syarat foto dokumen seperti KTP atau identitas lain sebagai
                    jaminan. Pengiriman dilakukan dalam 1 hari setelah proses
                    produksi selesai, yang memakan waktu sekitar 7 hari kerja.
                    Anda juga dapat melakukan request desain sesuai kebutuhan
                    dengan menghubungi penjual atau tim pemasaran kami.
                </p>
            </div>
            <div class="footer-link">
                <h5>Informasi Lain</h5><br>
                <ul>
                    <li><a href="#">Cara Pesan</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat dan Ketentuan</a></li>
                </ul>
            </div>
            <div class="footer-maps">
                <h5>Lokasi</h5>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d247.92814559913492!2d107.24697305339133!3d-6.15079330363097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6981f07273ae9b%3A0x307b6b922cdcbe56!2sCV%20etalase%20glass!5e0!3m2!1sid!2sid!4v1729956571040!5m2!1sid!2sid"
                    width="300"
                    height="200"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy">
                </iframe>
            </div>
            <div class="footer-social">
                <h5>Ikuti Kami</h5><br>
                <ul class="linkSosmed">
                    <li><a href="#"><i class="bi bi-facebook"></i></a></li>
                    <li><a href="#"><i class="bi bi-instagram"></i></a></li>
                    <li><a href="#"><i class="bi bi-twitter"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="hakcipta">
            <p>&copy; 2024 <a href="">Lapak Kaca.</a> All Rights Reserved.</p>
        </div>
    </footer>
    <script src="../js/user.js"></script>

</html>