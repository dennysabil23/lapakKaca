<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_alamat = $_POST['id_alamat'];

    $stmt = $conn->prepare("DELETE FROM alamat WHERE id_alamat = ?");
    $stmt->bind_param("i", $id_alamat);

    if ($stmt->execute()) {
        header("Location: ./page/user.php?alamat_deleted=true");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>
\
<html>
    <a href="./"></a>
</html>
