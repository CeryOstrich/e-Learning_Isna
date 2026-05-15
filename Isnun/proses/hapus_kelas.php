<?php
include "../database.php";

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM kelas WHERE id='$id'");

header("Location: ../dashboard.php?page=kelas");
exit;
?>
