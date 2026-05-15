<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}
include "../database.php";

$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$bio  = mysqli_real_escape_string($conn, $_POST['bio']);
$id   = $_SESSION['id'];

$update_fields = "nama='$nama', bio='$bio'";

// Handle password change
if(!empty($_POST['password'])) {
    $pw = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $update_fields .= ", password='$pw'";
}

// Handle photo upload
if(!empty($_FILES['foto_profil']['name'])) {
    $target_dir = "../uploads/profil/";
    if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    
    $allowed_ext = ['jpg', 'jpeg', 'png'];
    $file_ext = strtolower(pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION));
    
    if(in_array($file_ext, $allowed_ext)) {
        $fname = uniqid('foto_') . "." . $file_ext;
        if(move_uploaded_file($_FILES['foto_profil']['tmp_name'], $target_dir . $fname)) {
            $update_fields .= ", foto_profil='$fname'";
        }
    } else {
        echo "<script>alert('Format foto tidak valid. Hanya JPG dan PNG yang diizinkan!'); window.history.back();</script>";
        exit;
    }
}

mysqli_query($conn, "UPDATE users SET $update_fields WHERE id='$id'");
$_SESSION['nama'] = $nama; // Update displayed name

header("Location: ../dashboard.php?page=profil_guru");
exit;
?>
