<?php
session_start();
include "../database.php";

$guru_id  = $_SESSION['id'] ?? 1;
$kelas_id = $_POST['kelas_id'];
$judul    = mysqli_real_escape_string($conn, $_POST['judul']);
$link     = mysqli_real_escape_string($conn, $_POST['link']);
$jadwal   = $_POST['jadwal'];

mysqli_query($conn, "INSERT INTO live_class (guru_id,kelas_id,judul,link,jadwal) VALUES ('$guru_id','$kelas_id','$judul','$link','$jadwal')");

header("Location: ../dashboard.php?page=live_class_guru");
exit;
?>
