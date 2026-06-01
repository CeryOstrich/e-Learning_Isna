<?php
include "../database.php";

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM jadwal WHERE id='$id'");

header("Location: ../dashboard.php?page=jadwal");
exit;
?>
