<?php
include "../database.php";

$kelas_id = $_POST['kelas_id'];
$mapel_id = $_POST['mapel_id'];
$tanggal  = $_POST['tanggal'];
$siswa_ids = $_POST['siswa_id'];

foreach($siswa_ids as $siswa_id) {
    $key = "status_$siswa_id";
    $status = $_POST[$key] ?? 'alpa';
    
    // Insert or Update
    $existing = mysqli_query($conn,"SELECT id FROM absensi WHERE siswa_id='$siswa_id' AND kelas_id='$kelas_id' AND mapel_id='$mapel_id' AND tanggal='$tanggal'");
    if(mysqli_num_rows($existing) > 0) {
        mysqli_query($conn,"UPDATE absensi SET status='$status' WHERE siswa_id='$siswa_id' AND kelas_id='$kelas_id' AND mapel_id='$mapel_id' AND tanggal='$tanggal'");
    } else {
        mysqli_query($conn,"INSERT INTO absensi (siswa_id,kelas_id,mapel_id,tanggal,status) VALUES ('$siswa_id','$kelas_id','$mapel_id','$tanggal','$status')");
    }
}

header("Location: ../dashboard.php?page=absensi&kelas_id=$kelas_id&mapel_id=$mapel_id&tanggal=$tanggal");
exit;
?>
