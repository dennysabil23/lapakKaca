<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pembelian = $_POST['id_pembelian'];

    $stmt = $conn->prepare("DELETE FROM pembelian WHERE id_pembelian = ?");
    $stmt->bind_param("i", $id_pembelian);

    if ($stmt->execute()) {
        header("Location: ./page/user.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>
