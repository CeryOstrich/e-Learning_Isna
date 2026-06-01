<?php
session_start();
include "../database.php";

$admin_id = $_SESSION['id'] ?? 1;
$judul    = mysqli_real_escape_string($conn, $_POST['judul']);
$isi      = mysqli_real_escape_string($conn, $_POST['isi']);
$target   = $_POST['target'];

mysqli_query($conn, "INSERT INTO pengumuman (admin_id, judul, isi, target) VALUES ('$admin_id','$judul','$isi','$target')");

// Kirim notifikasi ke semua user yang dituju
$where = '';
if($target === 'guru') $where = "WHERE role='guru'";
elseif($target === 'siswa') $where = "WHERE role='siswa'";
$users = mysqli_query($conn, "SELECT id FROM users $where");
while($u = mysqli_fetch_assoc($users)) {
    $pesan = mysqli_real_escape_string($conn, "📢 Pengumuman Baru: $judul");
    mysqli_query($conn, "INSERT INTO notifikasi (user_id, pesan, link) VALUES ('{$u['id']}','$pesan','dashboard.php?page=pengumuman')");
}

header("Location: ../dashboard.php?page=pengumuman");
exit;
?>
