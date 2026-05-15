<?php
include "database.php";

$nama     = $_POST['nama'];
$email    = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role     = $_POST['role'];

// CEK EMAIL SUDAH ADA ATAU BELUM
$cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

if (mysqli_num_rows($cek) > 0) {
    echo "❌ Email sudah terdaftar!";
    exit;
}

// SIMPAN
mysqli_query($conn, "INSERT INTO users (nama, email, password, role) 
VALUES ('$nama', '$email', '$password', '$role')");

echo "✅ Pendaftaran berhasil! Silakan login.";
?>