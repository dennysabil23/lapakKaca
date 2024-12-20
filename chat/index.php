<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../page/masuk.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

if ($user_role == 'admin') {
    $query = "SELECT id_user, username FROM users WHERE role != 'admin'";
    $result = mysqli_query($conn, $query);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $receiver_id = $_GET['user_id'] ?? ($users[0]['id_user'] ?? null);
} else {
    $query = "SELECT id_user FROM users WHERE role = 'admin' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $receiver_id = $row['id_user'] ?? null;

    if (!$receiver_id) {
        echo "Admin tidak tersedia untuk diajak chat.";
        exit;
    }
}

$query = "SELECT * FROM chat WHERE (id_sender = '$user_id' AND id_receiver = '$receiver_id') 
          OR (id_sender = '$receiver_id' AND id_receiver = '$user_id') 
          ORDER BY created_at ASC";
$result = mysqli_query($conn, $query);
$messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../css/chat.css">
</head>
<header>
    <div class="header-container">
        <div class="logo" onclick="window.location.href='../page/index.php'">
            <img src="../asset/lapakkaca-logo.png" />
            <h2>Chating</h2>
        </div>
    </div>
</header>

<body>
    <?php if ($user_role == 'admin'): ?>
        <div class="sidebar">
            <div class="chat-header">
                <div class="header-title">
                    <span>Daftar User</span>
                </div>
            </div>
            <div class="user-list">
                <?php foreach ($users as $user): ?>
                    <a href="?user_id=<?= $user['id_user'] ?>"
                        class="<?= $receiver_id == $user['id_user'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($user['username']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="chat-container">
        <div class="chat-header">
            <div class="header-title">
                <?php
                if ($user_role == 'admin') {
                    $selectedUserName = '';
                    foreach ($users as $user) {
                        if ($user['id_user'] == $receiver_id) {
                            $selectedUserName = $user['username'];
                            break;
                        }
                    }
                    echo htmlspecialchars($selectedUserName);
                } else {
                    echo "Admin Lapakaca";
                }
                ?>
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

            function loadMessages() {
                $.ajax({
                    url: 'load_messages.php',
                    method: 'GET',
                    data: {
                        receiver_id: <?= $receiver_id ?>
                    },
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