<?php
include 'koneksi.php';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = $_POST['username_or_email'];
    $password = $_POST['password'];

   
    $sql = "SELECT * FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username_or_email, $username_or_email); 
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                echo "<script>
                        window.open('./admin/dashboard.php', '_blank');
                      </script>";
            } else {
                echo "<script>
                        window.location.href = './page/index.php';
                      </script>";
            }
            exit();
        } else {
            header("Location: ./page/masuk.php?error=Password salah!");
            exit();
        }
    } else {
        header("Location: ./page/masuk.php?error=User tidak ditemukan!");
        exit();
    }
}

$conn->close();
