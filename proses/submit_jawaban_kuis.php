<?php
session_start();
include "../config/database.php";

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$siswa_id = $_SESSION['id'];
$kuis_id = $_POST['kuis_id'];
$kelas_id = $_POST['kelas_id'];

// Cek apakah sudah mengerjakan
$cek = mysqli_query($conn, "SELECT id FROM kuis_nilai WHERE kuis_id = '$kuis_id' AND siswa_id = '$siswa_id'");
if(mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Anda sudah mengerjakan kuis ini!'); window.location.href='../dashboard.php?page=quiz_siswa&id_kelas=$kelas_id';</script>";
    exit;
}

$total_nilai_pg = 0;

// Proses Jawaban PG
if(isset($_POST['jawaban']) && is_array($_POST['jawaban'])) {
    foreach($_POST['jawaban'] as $soal_id => $opsi_id) {
        $poin_didapat = 0;
        
        // Ambil poin maksimal dan cek jawaban benar
        $q_soal = mysqli_query($conn, "SELECT poin_maksimal FROM kuis_soal_v2 WHERE id = '$soal_id'");
        $d_soal = mysqli_fetch_assoc($q_soal);
        $poin_maks = $d_soal['poin_maksimal'];
        
        $q_opsi = mysqli_query($conn, "SELECT is_benar FROM kuis_opsi_v2 WHERE id = '$opsi_id'");
        $d_opsi = mysqli_fetch_assoc($q_opsi);
        
        if($d_opsi && $d_opsi['is_benar'] == 1) {
            $poin_didapat = $poin_maks;
            $total_nilai_pg += $poin_maks;
        }
        
        mysqli_query($conn, "INSERT INTO kuis_jawaban_siswa (kuis_id, siswa_id, soal_id, opsi_id, poin_didapat) 
                             VALUES ('$kuis_id', '$siswa_id', '$soal_id', '$opsi_id', '$poin_didapat')");
    }
}

// Proses Jawaban Essay
if(isset($_POST['jawaban_essay']) && is_array($_POST['jawaban_essay'])) {
    foreach($_POST['jawaban_essay'] as $soal_id => $jawaban_teks) {
        $jawaban_teks = mysqli_real_escape_string($conn, $jawaban_teks);
        
        // Essay belum dinilai (poin = 0 default)
        mysqli_query($conn, "INSERT INTO kuis_jawaban_siswa (kuis_id, siswa_id, soal_id, jawaban_teks, poin_didapat) 
                             VALUES ('$kuis_id', '$siswa_id', '$soal_id', '$jawaban_teks', 0)");
    }
}

// Simpan Total Nilai (Sementara hanya dari PG, Essay akan ditambah guru nanti)
mysqli_query($conn, "INSERT INTO kuis_nilai (kuis_id, siswa_id, total_nilai) VALUES ('$kuis_id', '$siswa_id', '$total_nilai_pg')");

echo "<script>
    alert('Kuis berhasil dikumpulkan! Nilai Pilihan Ganda Anda adalah $total_nilai_pg. Essay (jika ada) menunggu penilaian guru.');
    window.location.href = '../dashboard.php?page=quiz_siswa&id_kelas=$kelas_id';
</script>";
?>
