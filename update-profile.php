<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./page/masuk.php?login_required");
    exit();
}

$id_user = $_SESSION['user_id'];

$long_name = $_POST['long_name'];
$nomor_telpon = $_POST['nomor_telpon'];
$gender = $_POST['gender'];

$uploadFileDir = './uploads/';
$profile_picture_updated = false;

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
    $fileName = $_FILES['profile_picture']['name'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedfileExtensions = array('jpg', 'jpeg', 'png');
    if (in_array($fileExtension, $allowedfileExtensions)) {

        $newFileName = $id_user . '.' . $fileExtension;
        $dest_path = $uploadFileDir . $newFileName;


        $sql_old_pic = "SELECT profile_picture FROM users WHERE id_user = ?";
        $stmt_old_pic = $conn->prepare($sql_old_pic);
        $stmt_old_pic->bind_param("i", $id_user);
        $stmt_old_pic->execute();
        $result_old_pic = $stmt_old_pic->get_result();
        $user = $result_old_pic->fetch_assoc();

        if ($user['profile_picture'] && file_exists($uploadFileDir . $user['profile_picture'])) {
            unlink($uploadFileDir . $user['profile_picture']);
        }
        $stmt_old_pic->close();

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $profile_picture_updated = true;
        }
    }
}

if ($profile_picture_updated) {
    $sql_update = "UPDATE users SET long_name=?, nomor_telpon=?, gender=?, profile_picture=? WHERE id_user=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssi", $long_name, $nomor_telpon, $gender, $newFileName, $id_user);
} else {
    $sql_update = "UPDATE users SET long_name=?, nomor_telpon=?, gender=? WHERE id_user=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssi", $long_name, $nomor_telpon, $gender, $id_user);
}

$stmt_update->execute();
$stmt_update->close();
$conn->close();

header("Location: ./page/user.php?update_success");
exit();
