<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../page/masuk.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil daftar pengguna yang pernah mengirim pesan ke admin
$query_users = "SELECT DISTINCT u.id_user, u.username FROM users u
                JOIN chat c ON u.id_user = c.id_sender
                WHERE c.id_receiver = '$user_id'";
$result_users = mysqli_query($conn, $query_users);
$users = mysqli_fetch_all($result_users, MYSQLI_ASSOC);

// Tentukan pengguna yang dipilih
$receiver_id = $_GET['receiver_id'] ?? ($users[0]['id_user'] ?? null);
if (!$receiver_id) {
    echo "Tidak ada pengguna untuk diajak chat.";
    exit;
}

// Ambil pesan antara admin dan pengguna yang dipilih
$query_messages = "SELECT * FROM chat WHERE (id_sender = '$user_id' AND id_receiver = '$receiver_id') 
                   OR (id_sender = '$receiver_id' AND id_receiver = '$user_id') 
                   ORDER BY created_at ASC";
$result_messages = mysqli_query($conn, $query_messages);
$messages = mysqli_fetch_all($result_messages, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Admin</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../css/chat.css">
</head>
<body>
<div class="chat-container">
    <div class="chat-header">
        <div class="header-title">Admin - Lapakaca</div>
        <div class="user-list">
            <select id="userSelector">
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id_user'] ?>" <?= ($user['id_user'] == $receiver_id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($user['username']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="chat-messages" id="chatBox">
        <?php foreach ($messages as $message): ?>
            <div class="chat-message <?= ($message['id_sender'] == $user_id) ? 'user' : 'admin' ?>">
                <div class="message-content">
                    <?= htmlspecialchars($message['pesan']) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="chat-footer">
        <input type="text" id="messageInput" placeholder="Ketik pesan...">
        <button id="sendButton">Kirim</button>
    </div>
</div>

<script>
$(document).ready(function() {
    let lastMessageCount = 0;

    function scrollToBottom() {
        var chatBox = $('#chatBox');
        chatBox.scrollTop(chatBox.prop("scrollHeight"));
    }

    $('#sendButton').click(function() {
        var message = $('#messageInput').val();
        if (message.trim() !== '') {
            $.ajax({
                url: 'send_message.php',
                method: 'POST',
                data: {
                    message: message,
                    receiver_id: <?= $receiver_id ?>
                },
                success: function() {
                    $('#messageInput').val('');
                    loadMessages();
                }
            });
        }
    });

    $('#userSelector').change(function() {
        const selectedUser = $(this).val();
        window.location.href = '?receiver_id=' + selectedUser;
    });

    function loadMessages() {
        $.ajax({
            url: 'load_messages.php',
            method: 'GET',
            data: { receiver_id: <?= $receiver_id ?> },
            success: function(response) {
                const currentMessageCount = (response.match(/<div class="message/g) || []).length;
                if (currentMessageCount > lastMessageCount) {
                    $('#chatBox').html(response);
                    scrollToBottom(); 
                    lastMessageCount = currentMessageCount;
                } else {
                    $('#chatBox').html(response);
                }
            }
        });
    }

    setInterval(loadMessages, 4000);

    scrollToBottom();
});
</script>

</body>
</html>
