<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: masuk.php?login_required");
    exit();
}


$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = $_POST['product_id'];
    $quantity = 1;

    $check_cart = "SELECT * FROM cart WHERE id_product = ? AND id_user = ?";
    $stmt = $conn->prepare($check_cart);
    $stmt->bind_param("ii", $product_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $update_cart = "UPDATE cart SET quantity = quantity + 1 WHERE id_product = ? AND id_user = ?";
        $stmt = $conn->prepare($update_cart);
        $stmt->bind_param("ii", $product_id, $user_id);
        $stmt->execute();
    } else {
        $insert_cart = "INSERT INTO cart (id_product, id_user, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_cart);
        $stmt->bind_param("iii", $product_id, $user_id, $quantity);
        $stmt->execute();
    }

    header("Location: cart.php?added=true");
    exit();
}



$cart_query = "SELECT cart.id_cart AS cart_id, products.name, products.price, cart.quantity, products.image 
               FROM cart 
               JOIN products ON cart.id_product = products.id_product 
               WHERE cart.id_user = ?";
$stmt = $conn->prepare($cart_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="shortcut icon" href="../asset/favicon.png" type="image/x-icon">

</head>

<body>
    <header>
        <div class="header-container">
            <div class="logo" onclick="window.location.href='index.php'">
                <img src="../asset/lapakkaca-logo.png" />
                <h2>Keranjang Belanja</h2>
            </div>
        </div>
    </header>
    <div class="section">
        <?php if ($cart_result->num_rows > 0): ?>
            <form action="../hapus.php" method="POST" id="cartForm">
                <table>
                    <thead>
                        <tr>
                            <th>Pilih</th>
                            <th></th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Kuantitas</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $cart_result->fetch_assoc()): ?>
                            <tr>
                                <td><input type="checkbox" name="product[]" value="<?php echo $row['cart_id']; ?>"
                                        data-price="<?php echo $row['price'] * $row['quantity']; ?>" onclick="calculateTotal()">
                                </td>
                                <td>
                                    <img src=" ../asset/gambar-produk/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="cartImage">
                                </td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo number_format($row['price'], 0, '.', '.'); ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo number_format($row['price'] * $row['quantity'], 0, '.', '.'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="checkout-container">
                    <p>Total: <span id="total-harga" class="totalHarga">IDR 0</span></p>
                    <div class="buttonAksi">
                        <button type="submit" class="checkout-btn">Checkout</button>
                        <button type="submit" formaction="../hapus.php" formmethod="POST" class="delete-btn" onclick="return confirmDeletion();">Hapus</button>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="cartKosong">
                <p>Keranjang Anda kosong.</p>
                <img src="../asset/bingung.png" alt="Kosong"> <br>
                <button class="backToMenu" onclick="goBackToMenu()">Lihat Produk</button>
            </div>
        <?php endif; ?>
    </div>
</body>
<script src="../js/cart.js"></script>

</html>