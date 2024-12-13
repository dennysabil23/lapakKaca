<?php
include 'koneksi.php';

function generateUsername($conn, $long_name) {
    // Mengonversi nama lengkap menjadi username tanpa spasi dan huruf kecil
    $username = strtolower(str_replace(' ', '', $long_name));
    $originalUsername = $username; // Menyimpan username asli

    // Tambahkan angka acak jika username sudah ada
    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $i = 1;
    while (true) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            // Username belum ada, gunakan username ini
            break;
        } else {
            // Username sudah ada, tambahkan angka dan coba lagi
            $username = $originalUsername . rand(100, 999);
        }
    }
    $stmt->close();
    return $username;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $long_name = $_POST['long_name'];
    $username = generateUsername($conn, $long_name); 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $gender = $_POST['gender'];
    $pekerjaan = $_POST['pekerjaan'];
    $umur = $_POST['umur'];
    $nomor_telpon = $_POST['nomor_telpon'];

    $profile_picture = $_FILES['profile_picture']['name'];
    $target_dir = "./uploads/";
    $target_file = $target_dir . basename($profile_picture);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $check = getimagesize($_FILES['profile_picture']['tmp_name']);
        if ($check !== false) {
            if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                if ($_FILES['profile_picture']['size'] < 2000000) {
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                        $sql = "INSERT INTO users (email, username, long_name, password, gender, pekerjaan, umur, profile_picture, role, nomor_telpon) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'user', ?)";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssssssiss", $email, $username, $long_name, $password, $gender, $pekerjaan, $umur, $profile_picture, $nomor_telpon);

                        if ($stmt->execute()) {
                            header("Location: ./page/index.php?success=Pendaftaran berhasil!");
                            exit();
                        } else {
                            header("Location: ./page/daftar.php?error=Gagal menyimpan data ke database! Error: " . $stmt->error);
                            exit();
                        }
                    } else {
                        header("Location: ./page/daftar.php?error=Gagal mengupload gambar!");
                        exit();
                    }
                } else {
                    header("Location: ./page/daftar.php?error=Ukuran file terlalu besar! Maksimal 2MB.");
                    exit();
                }
            } else {
                header("Location: ./page/daftar.php?error=Format file tidak didukung! Hanya JPG, JPEG, PNG, dan GIF.");
                exit();
            }
        } else {
            header("Location: ./page/daftar.php?error=File bukan gambar!");
            exit();
        }
    } else {
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE   => 'File terlalu besar sesuai pengaturan server.',
            UPLOAD_ERR_FORM_SIZE  => 'File terlalu besar sesuai form HTML.',
            UPLOAD_ERR_PARTIAL    => 'File hanya ter-upload sebagian.',
            UPLOAD_ERR_NO_FILE    => 'Tidak ada file yang di-upload.',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder sementara hilang.',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk.',
            UPLOAD_ERR_EXTENSION  => 'Upload file dihentikan oleh ekstensi PHP.'
        ];
        $error_message = $upload_errors[$_FILES['profile_picture']['error']] ?? 'Kesalahan saat mengupload file.';
        header("Location: ./page/daftar.php?error=$error_message");
        exit();
    }
}

$conn->close();
?>
