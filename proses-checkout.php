<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./page/masuk.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
$alamat_id = isset($_POST['alamat_id']) ? intval($_POST['alamat_id']) : 0;
$total_pembayaran = isset($_POST['total_pembayaran']) ? intval($_POST['total_pembayaran']) : 0;
$metode_pembayaran = isset($_POST['metode_pembayaran']) ? $_POST['metode_pembayaran'] : '';

if ($product_id <= 0 || $alamat_id <= 0 || $total_pembayaran <= 0 || $quantity <= 0 || empty($metode_pembayaran)) {
    die("Data tidak valid. Pastikan semua data sudah benar.");
}

$status_pembelian = 'Pesanan sedang di proses.';
$waktu_pembelian = date('Y-m-d H:i:s');

$sql = "INSERT INTO pembelian (id_user, id_product, id_alamat, quantity, total_pembayaran, metode_pembayaran, status_pembelian, waktu_pembelian) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error pada statement: " . $conn->error);
}

$stmt->bind_param("iiiiisss", $user_id, $product_id, $alamat_id, $quantity, $total_pembayaran, $metode_pembayaran, $status_pembelian, $waktu_pembelian);

if ($stmt->execute()) {
    header("Location: ./page/user.php?section=orders");
    exit();
} else {
    error_log("Kesalahan database: " . $stmt->error);
    die("Terjadi kesalahan, silakan coba lagi nanti.");
}

$stmt->close();
$conn->close();
?>
