<?php
session_start();
include "../config/database.php";

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$id = $_GET['id'];
$id_kuis = $_GET['id_kuis'];
$id_kelas = $_GET['id_kelas'];

$query = "DELETE FROM kuis_soal_v2 WHERE id = '$id'";

if (mysqli_query($conn, $query)) {
    echo "<script>
        alert('Soal berhasil dihapus!');
        window.location.href = '../dashboard.php?page=kelola_soal_guru&id_kuis=$id_kuis&id_kelas=$id_kelas';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus soal.');
        window.history.back();
    </script>";
}
?>
