<?php
// Hubungkan ke database (Sesuaikan nama file koneksi-mu, misalnya koneksi.php atau config.php)
// Pastikan path-nya benar karena file ini ada di dalam folder 'proses'
include '../database.php'; 

// Tangkap data dari form guru/quiz.php
$kelas_id = $_POST['kelas_id'];
$mapel_id = $_POST['mapel_id'];
$judul = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];
$link_kuis = $_POST['link_kuis']; // Opsional
$deadline = $_POST['deadline'];

// Masukkan data ke database
$query = "INSERT INTO kuis (kelas_id, mapel_id, judul, deskripsi, link_kuis, deadline) 
          VALUES ('$kelas_id', '$mapel_id', '$judul', '$deskripsi', '$link_kuis', '$deadline')";

$simpan = mysqli_query($conn, $query);

if ($simpan) {
    // Jika berhasil, kembali ke halaman kuis dengan membawa ID kelas yang sama
    echo "<script>
            alert('Wadah Kuis berhasil dibuat! Silakan kelola soal pilihan gandanya.');
            window.location.href = '../dashboard.php?page=quiz_guru&id_kelas=$kelas_id';
          </script>";
} else {
    // Jika gagal
    echo "<script>
            alert('Ups, gagal menyimpan kuis. Periksa database!');
            window.history.back();
          </script>";
}
?>