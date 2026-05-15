<?php
include "../database.php";

$id = $_POST['id'];
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$role = $_POST['role'];

// Only update password if provided
if (!empty($_POST['password'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $query = "UPDATE users SET nama='$nama', email='$email', role='$role', password='$password' WHERE id='$id'";
} else {
    $query = "UPDATE users SET nama='$nama', email='$email', role='$role' WHERE id='$id'";
}

mysqli_query($conn, $query);

header("Location: ../dashboard.php?page=user");
exit;
?>
