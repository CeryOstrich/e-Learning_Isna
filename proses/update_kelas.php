<?php
include "../database.php";

$id = $_POST['id'];
$nama_kelas = $_POST['nama_kelas'];
$fasilitas = $_POST['fasilitas'];

mysqli_query($conn, "UPDATE kelas SET nama_kelas='$nama_kelas', fasilitas='$fasilitas' WHERE id='$id'");

header("Location: ../dashboard.php?page=kelas");
exit();
?>