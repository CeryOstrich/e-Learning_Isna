<?php
session_start();
include "../database.php";

$judul = mysqli_real_escape_string($conn, $_POST['judul']);

// File Upload Logic
$target_dir = "../uploads/materi/";
// Create dir if not exists (in case mkdir failed)
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$file_name = time() . "_" . basename($_FILES["file_materi"]["name"]);
$target_file = $target_dir . $file_name;

if (move_uploaded_file($_FILES["file_materi"]["tmp_name"], $target_file)) {
    // Database Insertion
    $query = "INSERT INTO materi (judul, file) VALUES ('$judul', '$file_name')";
    mysqli_query($conn, $query);
}

// Redirect back to Guru dashboard
header("Location: ../dashboard.php?page=materi_guru");
exit;
?>
