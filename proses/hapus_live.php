<?php
session_start();
include "../config/database.php";

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$id = $_GET['id'];
$id_kelas = $_GET['id_kelas'];

$query = "DELETE FROM live_class WHERE id = '$id'";

if (mysqli_query($conn, $query)) {
    echo "<script>
        alert('Live Class berhasil dihapus!');
        window.location.href = '../dashboard.php?page=live_class_guru&id_kelas=$id_kelas';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus Live Class.');
        window.history.back();
    </script>";
}
?>
