<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../page/masuk.php');
    exit();
}

require '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image = $_FILES['image']['name'];
    $target_dir = "../asset/gambar-produk/";
    $target_file = $target_dir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $name = $_POST['name'];
        $price = $_POST['price'];

        $query = "INSERT INTO products (name, price, image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sis", $name, $price, $image);
        $stmt->execute();

        $id_product = $stmt->insert_id;

        $deskripsi = $_POST['deskripsi'];
        $ukuran = $_POST['ukuran'];
        $jenis_kaca = $_POST['jenis_kaca'];
        $bahan_rangka = $_POST['bahan_rangka'];
        $rak = $_POST['rak'];
        $pintu = $_POST['pintu'];
        $warna = $_POST['warna'];
        $berat = $_POST['berat'];
        $garansi = $_POST['garansi'];
        $roda = $_POST['roda'];

        $query_spek = "INSERT INTO spek_produk (id_product, deskripsi, ukuran, jenis_kaca, bahan_rangka, rak, pintu, warna, berat, garansi, roda) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_spek = $conn->prepare($query_spek);
        $stmt_spek->bind_param("issssssssss", $id_product, $deskripsi, $ukuran, $jenis_kaca, $bahan_rangka, $rak, $pintu, $warna, $berat, $garansi, $roda);
        $stmt_spek->execute();

        header('Location: dashboard.php?message=Product added successfully');
    } else {
        echo "Sorry, there was an error uploading the file.";
    }
}
?>
