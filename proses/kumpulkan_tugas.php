<?php
session_start();
include "../database.php";

$siswa_id = $_SESSION['id'] ?? 1;
$tugas_id = $_POST['tugas_id'];
$catatan  = mysqli_real_escape_string($conn, $_POST['catatan']);

$target_dir = "../uploads/tugas/";
if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);

$file_name = time() . "_" . basename($_FILES['file_tugas']['name']);
if(move_uploaded_file($_FILES['file_tugas']['tmp_name'], $target_dir . $file_name)) {
    // Check if already submitted (prevent duplicate)
    $existing = mysqli_query($conn,"SELECT id FROM pengumpulan_tugas WHERE tugas_id='$tugas_id' AND siswa_id='$siswa_id'");
    if(mysqli_num_rows($existing) > 0) {
        // Update instead
        mysqli_query($conn,"UPDATE pengumpulan_tugas SET file_tugas='$file_name', catatan='$catatan', dikumpulkan_pada=NOW() WHERE tugas_id='$tugas_id' AND siswa_id='$siswa_id'");
    } else {
        mysqli_query($conn,"INSERT INTO pengumpulan_tugas (tugas_id,siswa_id,file_tugas,catatan) VALUES ('$tugas_id','$siswa_id','$file_name','$catatan')");
    }
}

header("Location: ../dashboard.php?page=tugas_siswa");
exit;
?>
