<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}
include "../database.php";

$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = mysqli_real_escape_string($conn, $_POST['role']);
$no_wa = mysqli_real_escape_string($conn, $_POST['no_wa']);
// Cek duplikasi email
$cek = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
if(mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Email sudah terdaftar!'); window.location='../dashboard.php?page=user';</script>";
    exit;
}

$query = "INSERT INTO users (nama, email, password, role, no_wa) VALUES ('$nama', '$email', '$password', '$role', '$no_wa')";
mysqli_query($conn, $query);

header("Location: ../dashboard.php?page=user");
exit;
?>
