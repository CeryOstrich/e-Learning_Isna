<?php
session_start();
include "../config/database.php";

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$kelas_id = $_POST['kelas_id'];
$mapel_id = $_POST['mapel_id'];
$judul = $_POST['judul'];
$waktu_mulai = $_POST['waktu_mulai'];
$link_vicon = $_POST['link_vicon'];

$query = "INSERT INTO live_class (kelas_id, mapel_id, judul, waktu_mulai, link_vicon) VALUES ('$kelas_id', '$mapel_id', '$judul', '$waktu_mulai', '$link_vicon')";

if (mysqli_query($conn, $query)) {
    echo "<script>
        alert('Jadwal Live Class berhasil ditambahkan!');
        window.location.href = '../dashboard.php?page=live_class_guru&id_kelas=$kelas_id';
    </script>";
} else {
    echo "<script>
        alert('Gagal menambahkan Live Class.');
        window.history.back();
    </script>";
}
?>
