<?php
session_start();
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
    $fname = time() . "_" . basename($_FILES['foto_profil']['name']);
    if(move_uploaded_file($_FILES['foto_profil']['tmp_name'], $target_dir . $fname)) {
        $update_fields .= ", foto_profil='$fname'";
    }
}

mysqli_query($conn, "UPDATE users SET $update_fields WHERE id='$id'");
$_SESSION['email'] = $nama; // Update displayed name

header("Location: ../dashboard.php?page=profil_guru");
exit;
?>
