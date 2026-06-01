<?php
session_start();
include "../database.php";

$user_id  = $_SESSION['id'] ?? 1;
$forum_id = $_POST['forum_id'];
$isi      = mysqli_real_escape_string($conn, $_POST['isi']);

mysqli_query($conn, "INSERT INTO balasan_forum (forum_id,user_id,isi) VALUES ('$forum_id','$user_id','$isi')");

$role = $_SESSION['role'];
$page = ($role == 'guru') ? 'detail_forum_guru' : 'detail_forum_siswa';
header("Location: ../dashboard.php?page=$page&id=$forum_id");
exit;
?>
