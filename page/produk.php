<?php
include '../koneksi.php';


if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id) {
    $sql = "SELECT * FROM products WHERE id_product = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Produk tidak ditemukan.";
        exit;
    }

    $sql_spek = "SELECT * FROM spek_produk WHERE id_product = ?";
    $stmt_spek = $conn->prepare($sql_spek);
    $stmt_spek->bind_param("i", $product_id);
    $stmt_spek->execute();
    $result_spek = $stmt_spek->get_result();

    if ($result_spek->num_rows > 0) {
        $spek_produk = $result_spek->fetch_assoc();
    } else {
        echo "Spesifikasi tidak ditemukan.";
        exit;
    }
} else {
    echo "ID produk tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../gambar/DyanafLogo2.png" type="image/x-icon">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="../css/produk.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="shortcut icon" href="../asset/favicon.png" type="image/x-icon">
</head>

<body>
    <header>
        <div class="header-container">
            <div class="logo" onclick="window.location.href='index.php'">
                <img src="../asset/lapakkaca-logo2.png" alt="Logo" />
                <div class="garis"></div>
            </div>
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        </div>
    </header>
    <div class="container">
        <div class="product-row">
            <div class="product-image">
                <img src="../asset/gambar-produk/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="product-details">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <div class="ratingMarket">
                    <i class="bi bi-star-fill rating"></i>
                    <span class="rating-star">0/5</span>
                    <span class="terjual">89 Terjual</span>
                    <span>67</span>
                    <span class="nilai">Penilaian</span>
                </div>
                <h3>Rp. <?php echo number_format($product['price'], 0, ',', '.'); ?></h3>
                <div class="desc-info">
                    <i class="bi bi-clock-fill"></i>
                    <p>Estimasi Tiba 3 Hari</p>
                    <i class="bi bi-box-seam-fill"></i>
                    <p>Garansi 100% Kerusakan</p>
                </div>
                <div class="quantity-selector">
                    <span>Kuantitas</span>
                    <button type="button" id="decrease" class="quantity-button">-</button>
                    <input type="number" name="quantity" class="quantity" id="quantity" value="1" min="1" readonly>
                    <button type="button" id="increase" class="quantity-button">+</button>
                </div>
                <div class="buyer">
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id_product']); ?>">
                        <input type="hidden" name="quantity" id="cart_quantity" value="1"> <!-- Hidden input for quantity -->
                        <button class="ToCart btn-cart" type="submit">
                            <i class="bi bi-cart-plus"></i><span>Masukkan Keranjang</span>
                        </button>
                    </form>

                    <form action="checkout.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id_product']); ?>">
                        <input type="hidden" name="quantity" id="checkout_quantity" value="1">
                        <button class="ToCart btn-buy" type="submit" onclick="updateQuantity('checkout_quantity')">
                            <i class="bi bi-bag"></i><span>Beli Sekarang</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="more">
        <div class="deskripsi">
            <div class="desk-produk">
                <h3>Deskripsi</h3>
                <p><?php echo htmlspecialchars($spek_produk['deskripsi']); ?></p>
            </div>
            <div class="spesifikasi">
                <table>
                    <h3>Spesifikasi</h3>
                    <tr>
                        <th>Ukuran</th>
                        <td><?php echo htmlspecialchars($spek_produk['ukuran']); ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Kaca</th>
                        <td><?php echo htmlspecialchars($spek_produk['jenis_kaca']); ?></td>
                    </tr>
                    <tr>
                        <th>Bahan Rangka</th>
                        <td><?php echo htmlspecialchars($spek_produk['bahan_rangka']); ?></td>
                    </tr>
                    <tr>
                        <th>Rak</th>
                        <td><?php echo htmlspecialchars($spek_produk['rak']); ?></td>
                    </tr>
                    <tr>
                        <th>Pintu</th>
                        <td><?php echo htmlspecialchars($spek_produk['pintu']); ?></td>
                    </tr>
                    <tr>
                        <th>Warna</th>
                        <td><?php echo htmlspecialchars($spek_produk['warna']); ?></td>
                    </tr>
                    <tr>
                        <th>Berat</th>
                        <td><?php echo htmlspecialchars($spek_produk['berat']); ?></td>
                    </tr>
                    <tr>
                        <th>Garansi</th>
                        <td><?php echo htmlspecialchars($spek_produk['garansi']); ?></td>
                    </tr>
                    <tr>
                        <th>Roda</th>
                        <td><?php echo htmlspecialchars($spek_produk['roda']); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="batas"></div>
        <div class="toko">
            <h2> CV. Etalase Intan Glass</h2>
            <span>Berdiri sejak 2021</span>
            <p>
                CV. Etalase Intan Glass adalah perusahaan yang bergerak di bidang penyediaan dan pemasangan kaca etalase berkualitas tinggi untuk berbagai kebutuhan. Dengan komitmen pada kepuasan pelanggan, perusahaan ini menghadirkan produk-produk yang memenuhi standar keamanan dan estetika, baik untuk keperluan bisnis, perkantoran, maupun perumahan. Mengedepankan inovasi dan keahlian, CV. Etalase Intan Glass berupaya menjadi mitra terpercaya dalam setiap proyek yang melibatkan solusi kaca.
            </p>
        </div>
    </div>
</body>
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
<script src="../js/detail-produk.js"></script>

</html>

<?php $conn->close(); ?>