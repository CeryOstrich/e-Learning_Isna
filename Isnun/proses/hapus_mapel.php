<?php
include "../database.php";

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM mapel WHERE id='$id'");

header("Location: ../dashboard.php?page=mapel");
exit;
?>
