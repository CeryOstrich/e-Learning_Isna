<?php
session_start();
include "../database.php";

$user_id  = $_SESSION['id'] ?? 1;
$kelas_id = $_POST['kelas_id'];
$mapel_id = $_POST['mapel_id'];
$judul    = mysqli_real_escape_string($conn, $_POST['judul']);
$isi      = mysqli_real_escape_string($conn, $_POST['isi']);

mysqli_query($conn, "INSERT INTO forum_diskusi (user_id,kelas_id,mapel_id,judul,isi) VALUES ('$user_id','$kelas_id','$mapel_id','$judul','$isi')");

// Redirect based on role
$role = $_SESSION['role'];
$page = ($role == 'guru') ? 'forum_guru' : 'forum_siswa';
header("Location: ../dashboard.php?page=$page");
exit;
?>
