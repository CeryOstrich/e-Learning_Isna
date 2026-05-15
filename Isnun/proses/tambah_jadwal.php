<?php
include "../database.php";

$kelas_id = $_POST['kelas_id'];
$mapel_id = $_POST['mapel_id'];
$hari = mysqli_real_escape_string($conn, $_POST['hari']);
$jam = mysqli_real_escape_string($conn, $_POST['jam']);

$query = "INSERT INTO jadwal (kelas_id, mapel_id, hari, jam) VALUES ('$kelas_id', '$mapel_id', '$hari', '$jam')";
mysqli_query($conn, $query);

header("Location: ../dashboard.php?page=jadwal");
exit;
?>
