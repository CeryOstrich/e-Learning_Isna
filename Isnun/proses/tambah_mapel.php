<?php
include "../database.php";

$nama = mysqli_real_escape_string($conn, $_POST['nama_mapel']);
mysqli_query($conn, "INSERT INTO mapel (nama_mapel) VALUES ('$nama')");

header("Location: ../dashboard.php?page=mapel");
exit;
?>
