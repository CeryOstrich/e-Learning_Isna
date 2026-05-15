<?php
include "../database.php";
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM pengumuman WHERE id='$id'");
header("Location: ../dashboard.php?page=pengumuman");
exit;
?>
