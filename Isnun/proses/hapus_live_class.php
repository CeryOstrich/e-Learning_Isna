<?php
include "../database.php";
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM live_class WHERE id='$id'");
header("Location: ../dashboard.php?page=live_class_guru");
exit;
?>
