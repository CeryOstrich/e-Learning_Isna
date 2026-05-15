<?php
include "../database.php";
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM quiz WHERE id='$id'");
header("Location: ../dashboard.php?page=quiz_guru");
exit;
?>
