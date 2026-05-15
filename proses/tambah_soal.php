<?php
session_start();
include "../config/database.php";

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$kuis_id = $_POST['kuis_id'];
$kelas_id = $_POST['kelas_id'];
$tipe = $_POST['tipe'];
$pertanyaan = mysqli_real_escape_string($conn, $_POST['pertanyaan']);
$poin = $_POST['poin'];

if ($tipe == 'pg') {
    // Insert Soal PG
    mysqli_query($conn, "INSERT INTO kuis_soal_v2 (kuis_id, tipe, pertanyaan, poin_maksimal) VALUES ('$kuis_id', 'pg', '$pertanyaan', '$poin')");
    $soal_id = mysqli_insert_id($conn);
    
    // Insert Opsi
    $kunci = $_POST['kunci'];
    $opsi = [
        'A' => mysqli_real_escape_string($conn, $_POST['opsi_a']),
        'B' => mysqli_real_escape_string($conn, $_POST['opsi_b']),
        'C' => mysqli_real_escape_string($conn, $_POST['opsi_c']),
        'D' => mysqli_real_escape_string($conn, $_POST['opsi_d'])
    ];
    
    foreach ($opsi as $huruf => $teks) {
        $is_benar = ($huruf == $kunci) ? 1 : 0;
        mysqli_query($conn, "INSERT INTO kuis_opsi_v2 (soal_id, teks_opsi, is_benar) VALUES ('$soal_id', '$teks', '$is_benar')");
    }
} else {
    // Insert Soal Essay
    mysqli_query($conn, "INSERT INTO kuis_soal_v2 (kuis_id, tipe, pertanyaan, poin_maksimal) VALUES ('$kuis_id', 'essay', '$pertanyaan', '$poin')");
}

header("Location: ../dashboard.php?page=kelola_soal_guru&id_kuis=$kuis_id&id_kelas=$kelas_id");
exit;
?>
