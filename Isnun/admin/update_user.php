<?php
include "../database.php";

$id=$_POST['id'];
$nama=$_POST['nama'];
$email=$_POST['email'];
$role=$_POST['role'];

mysqli_query($conn,"UPDATE users SET nama='$nama', email='$email', role='$role' WHERE id=$id");

header("Location: ../admin/user.php");