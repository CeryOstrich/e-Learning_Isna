<?php
session_start();
include "../config/database.php";

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$kuis_id = $_POST['kuis_id'];
$siswa_id = $_POST['siswa_id'];
$kelas_id = $_POST['kelas_id'];

// Simpan poin essay
if(isset($_POST['nilai_essay']) && is_array($_POST['nilai_essay'])) {
    foreach($_POST['nilai_essay'] as $soal_id => $poin) {
        $poin = (float) $poin;
        mysqli_query($conn, "UPDATE kuis_jawaban_siswa SET poin_didapat = '$poin' WHERE kuis_id = '$kuis_id' AND siswa_id = '$siswa_id' AND soal_id = '$soal_id'");
    }
}

// Hitung ulang total nilai
$q_total = mysqli_query($conn, "SELECT SUM(poin_didapat) as total FROM kuis_jawaban_siswa WHERE kuis_id = '$kuis_id' AND siswa_id = '$siswa_id'");
$d_total = mysqli_fetch_assoc($q_total);
$total_baru = $d_total['total'] ? $d_total['total'] : 0;

mysqli_query($conn, "UPDATE kuis_nilai SET total_nilai = '$total_baru' WHERE kuis_id = '$kuis_id' AND siswa_id = '$siswa_id'");

echo "<script>
    alert('Nilai essay berhasil disimpan. Total nilai siswa sekarang adalah $total_baru.');
    window.location.href = '../dashboard.php?page=nilai_guru&id_kuis=$kuis_id&id_kelas=$kelas_id';
</script>";
?>
