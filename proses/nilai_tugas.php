<?php
session_start();
include "../database.php";

$pengumpulan_id = mysqli_real_escape_string($conn, $_POST['pengumpulan_id']);
$nilai          = (int)$_POST['nilai'];
$feedback       = mysqli_real_escape_string($conn, $_POST['feedback_guru']);

mysqli_query($conn, "UPDATE pengumpulan_tugas SET nilai='$nilai', feedback_guru='$feedback' WHERE id='$pengumpulan_id'");

$tugas_id = mysqli_real_escape_string($conn, $_POST['tugas_id']);
header("Location: ../dashboard.php?page=cek_tugas&id=$tugas_id");
exit;
?>
