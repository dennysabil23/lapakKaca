<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo 'Session expired';
    exit;
}

$user_id = $_SESSION['user_id'];
$receiver_id = $_GET['receiver_id'] ?? null;

if (!$receiver_id) {
    echo 'Receiver ID missing';
    exit;
}

$query = "SELECT * FROM chat 
          WHERE (id_sender = '$user_id' AND id_receiver = '$receiver_id') 
             OR (id_sender = '$receiver_id' AND id_receiver = '$user_id') 
          ORDER BY created_at ASC";
$result = mysqli_query($conn, $query);

while ($message = mysqli_fetch_assoc($result)) {
    $class = ($message['id_sender'] == $user_id) ? 'chat-message user' : 'chat-message admin';
    echo "<div class=\"$class\">";
    echo "<div class=\"message-content\">".htmlspecialchars($message['pesan'])."</div>";
    echo "</div>";
}
?>
