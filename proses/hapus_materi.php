<?php
include "../database.php";

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM materi WHERE id='$id'");

header("Location: ../dashboard.php?page=materi_guru");
exit;
?>
