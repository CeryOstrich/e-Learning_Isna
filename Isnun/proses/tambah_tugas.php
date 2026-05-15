<?php
session_start();
include "../database.php";

$kelas_id = $_POST['kelas_id'];
$mapel_id = $_POST['mapel_id'];
$judul = mysqli_real_escape_string($conn, $_POST['judul']);
$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
$deadline = $_POST['deadline'];
$guru_id = $_SESSION['id'] ?? 1; // Fallback to 1 if session id isn't set, ideally we use $_SESSION['id']

// Note: No file upload logic here for tasks creations initially, keeping it simple
$query = "INSERT INTO tugas (guru_id, kelas_id, mapel_id, judul, deskripsi, deadline) 
          VALUES ('$guru_id', '$kelas_id', '$mapel_id', '$judul', '$deskripsi', '$deadline')";
mysqli_query($conn, $query);

header("Location: ../dashboard.php?page=tugas_guru");
exit;
?>
