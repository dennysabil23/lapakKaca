<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo 'Session expired';
    exit;
}

$user_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'] ?? null;
$message = $_POST['message'] ?? '';

if (!$receiver_id || !$message) {
    echo 'Invalid request';
    exit;
}

$message = htmlspecialchars($message);
$query = "INSERT INTO chat (id_sender, id_receiver, pesan, created_at) 
          VALUES ('$user_id', '$receiver_id', '$message', NOW())";
if (!mysqli_query($conn, $query)) {
    echo 'Failed to send message';
}
?>
