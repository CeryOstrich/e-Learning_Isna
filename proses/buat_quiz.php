<?php
session_start();
include "../database.php";

$guru_id    = $_SESSION['id'] ?? 1;
$kelas_id   = $_POST['kelas_id'];
$mapel_id   = $_POST['mapel_id'];
$judul      = mysqli_real_escape_string($conn, $_POST['judul']);
$durasi     = (int)$_POST['durasi_menit'];

// Insert quiz header
mysqli_query($conn, "INSERT INTO quiz (guru_id,kelas_id,mapel_id,judul,durasi_menit) VALUES ('$guru_id','$kelas_id','$mapel_id','$judul','$durasi')");
$quiz_id = mysqli_insert_id($conn);

// Insert questions
$pertanyaans   = $_POST['pertanyaan'];
$opsi_a_arr    = $_POST['opsi_a'];
$opsi_b_arr    = $_POST['opsi_b'];
$opsi_c_arr    = $_POST['opsi_c'];
$opsi_d_arr    = $_POST['opsi_d'];
$jwb_benar_arr = $_POST['jawaban_benar'];

foreach($pertanyaans as $i => $p) {
    $p  = mysqli_real_escape_string($conn, $p);
    $a  = mysqli_real_escape_string($conn, $opsi_a_arr[$i]);
    $b  = mysqli_real_escape_string($conn, $opsi_b_arr[$i]);
    $c  = mysqli_real_escape_string($conn, $opsi_c_arr[$i]);
    $d  = mysqli_real_escape_string($conn, $opsi_d_arr[$i]);
    $jb = $jwb_benar_arr[$i];
    mysqli_query($conn, "INSERT INTO pertanyaan_quiz (quiz_id,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar) VALUES ('$quiz_id','$p','$a','$b','$c','$d','$jb')");
}

header("Location: ../dashboard.php?page=quiz_guru");
exit;
?>
