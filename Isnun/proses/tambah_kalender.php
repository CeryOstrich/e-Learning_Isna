<?php
include "../database.php";

$judul = mysqli_real_escape_string($conn, $_POST['judul']);
$tanggal_mulai = $_POST['tanggal_mulai'];
$tanggal_selesai = $_POST['tanggal_selesai'];
$keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
$warna = $_POST['warna'];

mysqli_query($conn, "INSERT INTO kalender (judul, tanggal_mulai, tanggal_selesai, keterangan, warna) VALUES ('$judul','$tanggal_mulai','$tanggal_selesai','$keterangan','$warna')");

header("Location: ../dashboard.php?page=kalender");
exit;
?>
