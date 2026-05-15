<?php
session_start();
include '../database.php'; 

// 1. Cek login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}
// Ambil ID dari session 'id' karena kita sudah menambahkannya di Login_Page.php
$id_siswa = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : null;

if ($id_siswa && $id_kelas) {
    // 3. Update kolom id_kelas di tabel users
    // Pastikan nama tabel kamu 'users' atau sesuaikan jika berbeda
    $query = "UPDATE users SET id_kelas = '$id_kelas' WHERE id = '$id_siswa'";
    $update = mysqli_query($conn, $query);

    if ($update) {
        // Berhasil! Langsung arahkan ke materi
        header("Location: ../dashboard.php?page=materi_siswa&id_kelas=$id_kelas");
        exit;
    } else {
        echo "Gagal update database: " . mysqli_error($conn);
    }
} else {
    echo "Gagal: ID Siswa atau ID Kelas tidak ditemukan. Pastikan Anda sudah login.";
}
?>