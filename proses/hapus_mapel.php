<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}
include "../database.php";

$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM mapel WHERE id='$id'");

header("Location: ../dashboard.php?page=mapel");
exit;
?>
