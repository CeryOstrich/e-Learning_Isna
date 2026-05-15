<?php
include "../database.php";
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM kalender WHERE id='$id'");
header("Location: ../dashboard.php?page=kalender");
exit;
?>
