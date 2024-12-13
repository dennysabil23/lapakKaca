<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: masuk.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($product_id === 0) {
    die("ID produk tidak ditemukan.");
}

// Ambil produk
$sql = "SELECT * FROM products WHERE id_product = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Produk tidak ditemukan.");
}

$harga_produk = $product['price'];
$biaya_layanan = 50000;
$total_harga = $harga_produk * $quantity;
$total_biaya_layanan = $biaya_layanan * $quantity;
$total_pembayaran = $total_harga + $total_biaya_layanan;

$selected_alamat_id = isset($_POST['selected_alamat_id']) ? intval($_POST['selected_alamat_id']) : 0;
if ($selected_alamat_id > 0) {
    $sql_alamat = "SELECT * FROM alamat WHERE id_user = ? AND id_alamat = ?";
    $stmt_alamat = $conn->prepare($sql_alamat);
    $stmt_alamat->bind_param("ii", $user_id, $selected_alamat_id);
} else {
    $sql_alamat = "SELECT * FROM alamat WHERE id_user = ? LIMIT 1";
    $stmt_alamat = $conn->prepare($sql_alamat);
    $stmt_alamat->bind_param("i", $user_id);
}
$stmt_alamat->execute();
$selected_alamat = $stmt_alamat->get_result()->fetch_assoc();

$sql_all_alamat = "SELECT * FROM alamat WHERE id_user = ?";
$stmt_all_alamat = $conn->prepare($sql_all_alamat);
$stmt_all_alamat->bind_param("i", $user_id);
$stmt_all_alamat->execute();
$all_alamat = $stmt_all_alamat->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../asset/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/checkout.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Checkout</title>
</head>

<body>
    <header>
        <div class="header-container">
            <div class="logo" onclick="window.location.href='index.php'">
                <img src="../asset/lapakkaca-logo.png" />
                <h2>Checkout</h2>
            </div>
        </div>
    </header>

    <section>
        <div class="area-checkout">
            <div class="amplop"></div>
            <div class="set-alamat">
                <h2>Alamat Pengiriman</h2>
                <?php if ($selected_alamat): ?>
                    <p class="label-alamat"><?= htmlspecialchars($selected_alamat['status_alamat']) ?></p>
                    <div class="flex-alamat">
                        <div class="alamat-detail">
                            <div class="nama-telpon">
                                <p class="a2">(+62) <?= htmlspecialchars(substr($selected_alamat['nomor_telpon'], 1)) ?></p>
                                <p class="a1"><?= htmlspecialchars($selected_alamat['nama_penerima']) ?></p>
                            </div>
                            <div class="garis"></div>
                            <p><?= htmlspecialchars($selected_alamat['nama_jalan']) ?></p>
                            <div class="kecamatan-kodepos">
                                <p><?= htmlspecialchars($selected_alamat['kecamatan']) ?><span>,</span></p>
                                <p><?= htmlspecialchars($selected_alamat['kota']) ?><span>,</span></p>
                                <p><?= htmlspecialchars($selected_alamat['provinsi']) ?><span>,</span></p>
                                <p><span>Kode Pos</span> <?= htmlspecialchars($selected_alamat['kode_pos']) ?></p>
                            </div>
                        </div>
                        <button id="ubahAlamatBtn" type="button">Ubah Alamat</button>
                    </div>
                    <p><?= htmlspecialchars($selected_alamat['patokan']) ?><span></span></p>
                <?php else: ?>
                    <p>Anda belum menambahkan alamat pengiriman.</p><br>
                    <a href='user.php?section=alamat' class='btn-add-alamat'>Tambahkan Alamat</a>
                <?php endif; ?>
            </div>

            <div class="alamat-popup" id="popupAlamat">
                <h2>Pilih Alamat</h2>
                <?php if ($all_alamat->num_rows > 0): ?>
                    <?php while ($alamat = $all_alamat->fetch_assoc()): ?>
                        <form method="post" class="alamat-card">
                            <div class="alamat-flex">
                                <div class="pilih-alamat">
                                    <p class="status-alamat"><?= htmlspecialchars($alamat['status_alamat']) ?></p>
                                    <div class="nama-telpon">
                                        <p class="a1">
                                             <?= htmlspecialchars($alamat['nama_penerima']) ?>
                                        </p>
                                        <p class="a2">
                                            <?= htmlspecialchars($alamat['nomor_telpon']) ?>
                                        </p>
                                        <p class="a3">
                                            <span>(</span><?= htmlspecialchars($alamat['patokan']) ?><span>)</span>
                                        </p>
                                    </div>
                                    <p><?= htmlspecialchars($alamat['nama_jalan']) ?></p>
                                    <div class="kecamatan-kodepos">
                                        <p>
                                            <?= htmlspecialchars($alamat['kecamatan']) ?><span>,</span>
                                        </p>
                                        <p>
                                            <?= htmlspecialchars($alamat['kota']) ?><span>,</span>
                                        </p>
                                        <p>
                                            <?= htmlspecialchars($alamat['provinsi']) ?><span>,</span>
                                        </p>
                                        <p>
                                            <span>Kode Pos</span> <?= htmlspecialchars($alamat['kode_pos']) ?>
                                        </p>
                                    </div>

                                    <input type="hidden" name="selected_alamat_id" value="<?= $alamat['id_alamat'] ?>">
                                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                    <input type="hidden" name="quantity" value="<?= $quantity ?>">
                                </div>
                                <div class="alamat-btn">
                                    <button type="submit">Pilih</button>
                                </div>
                            </div>
                        </form>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Tidak ada alamat yang tersedia.</p>
                <?php endif; ?>
            </div>

            <div class="produk">
                <div class="product-image">
                    <img src="../asset/gambar-produk/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="produk-detail">
                    <div class="detail-container">
                        <p class="nama-produk"><?= htmlspecialchars($product['name']); ?></p>
                        <table class="detail-table">
                            <thead>
                                <tr>
                                    <th>Harga</th>
                                    <th>Kuantitas</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Rp <?= number_format($harga_produk, 0, ',', '.'); ?></td>
                                    <td><?= htmlspecialchars($quantity); ?></td>
                                    <td>Rp <?= number_format($total_harga, 0, ',', '.'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="pembayaran">
                <h2>Metode Pembayaran</h2>
                <div class="detail-pembayaran">
                    <form action="../proses-checkout.php" method="post">
                        <div class="radio-group">
                            <input type="radio" id="cod" name="metode_pembayaran" value="COD" required>
                            <label for="cod" class="radio-label">COD</label>
                            <input type="radio" id="dana" name="metode_pembayaran" value="DANA" required>
                            <label for="dana" class="radio-label">DANA</label>
                        </div>
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id); ?>">
                        <input type="hidden" name="quantity" value="<?= htmlspecialchars($quantity); ?>">
                        <input type="hidden" name="alamat_id" value="<?= htmlspecialchars($selected_alamat['id_alamat'] ?? 0); ?>">
                        <input type="hidden" name="total_pembayaran" value="<?= htmlspecialchars($total_pembayaran); ?>">
                        <div class="checkout">
                            <div class="checkout-text">
                                <div class="checkout-text">
                                    <table>
                                        <tr>
                                            <td class="td1">Subtotal untuk Produk</td>
                                            <td><?= number_format($total_harga, 0, ',', '.'); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="td2">Biaya Pengiriman</td>
                                            <td class="free">Rp. 100.000</td>
                                        </tr>
                                        <tr>
                                            <td class="td3">Biaya Layanan</td>
                                            <td><?= number_format($total_biaya_layanan, 0, ',', '.'); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="td4">Total Pembayaran</td>
                                            <td class="total-pembayaran"><?= number_format($total_pembayaran, 0, ',', '.'); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="checkout-btn">
                                <button>Checkout</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="../js/checkout.js"></script>
    </section>
</body>

</html>