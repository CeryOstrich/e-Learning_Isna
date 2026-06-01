<?php
include "../database.php";
$id = $_GET['id'];
mysqli_query($conn, "UPDATE notifikasi SET dibaca=1 WHERE id='$id'");
echo "ok";
?>
