<?php
session_start();
include 'koneksi.php';

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['product'])) {
    $product = $_POST['product'];
    $placeholders = implode(',', array_fill(0, count($product), '?'));
    $delete_cart = "DELETE FROM cart WHERE id_cart IN ($placeholders)";

    $stmt = $conn->prepare($delete_cart);
    $stmt->bind_param(str_repeat('i', count($product)), ...$product);

    if ($stmt->execute()) {
        echo "Item berhasil dihapus dari keranjang!";
    } else {
        echo "Error: " . $stmt->error;
    }

    header("Location: ./page/cart.php");
    exit();
} else {
    header("Location: ./page/cart.php");
    exit();
}
