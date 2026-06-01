<?php
// Tangkap ID Kelas dari URL
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : 0;

// Ambil info nama kelas dari database
$query_kelas = mysqli_query($conn, "SELECT * FROM kelas WHERE id = '$id_kelas'");
$kelas = mysqli_fetch_assoc($query_kelas);

if (!$kelas) {
    echo "<div class='card'><h2 style='color:red;'>Kelas tidak ditemukan!</h2></div>";
    exit;
}
?>

<div class="card" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="color: #333; margin: 0;">🚪 Ruang Kelas: <?= $kelas['nama_kelas'] ?></h2>
        <p style="color: #777; margin-top: 5px;">Pilih aktivitas belajar untuk kelas ini.</p>
    </div>
    <a href="dashboard.php?page=home" class="btn" style="background: #6c757d; color: white;">⬅️ Kembali ke Dashboard</a>
</div>

<!-- GRID MENU KELAS -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
    
    <!-- Modul Materi -->
    <a href="dashboard.php?page=materi_siswa&id_kelas=<?= $id_kelas ?>" style="text-decoration: none;">
        <div class="card-stat bg-purple" style="padding: 25px; border-radius: 15px;">
            <div><h3 style="font-size: 22px;">📖 Materi</h3><p>Bahan Ajar & Video</p></div>
        </div>
    </a>

    <!-- Modul Tugas -->
    <a href="dashboard.php?page=tugas_siswa&id_kelas=<?= $id_kelas ?>" style="text-decoration: none;">
        <div class="card-stat bg-orange" style="padding: 25px; border-radius: 15px;">
            <div><h3 style="font-size: 22px;">📝 Tugas</h3><p>Kumpulkan Tugas</p></div>
        </div>
    </a>

    <!-- Modul Kuis -->
    <a href="dashboard.php?page=quiz_siswa&id_kelas=<?= $id_kelas ?>" style="text-decoration: none;">
        <div class="card-stat bg-pink" style="padding: 25px; border-radius: 15px;">
            <div><h3 style="font-size: 22px;">🎯 Kuis</h3><p>Kerjakan Ujian</p></div>
        </div>
    </a>

    <!-- Modul Live Class -->
    <a href="dashboard.php?page=live_class_siswa&id_kelas=<?= $id_kelas ?>" style="text-decoration: none;">
        <div class="card-stat" style="background: linear-gradient(45deg, #e74c3c, #ff7675); padding: 25px; border-radius: 15px;">
            <div><h3 style="font-size: 22px;">📹 Live Class</h3><p>Tatap Muka Virtual</p></div>
        </div>
    </a>

    <!-- Modul Forum -->
    <a href="dashboard.php?page=forum_siswa&id_kelas=<?= $id_kelas ?>" style="text-decoration: none;">
        <div class="card-stat" style="background: linear-gradient(45deg, #3498db, #6dd5ed); padding: 25px; border-radius: 15px;">
            <div><h3 style="font-size: 22px;">💬 Forum</h3><p>Diskusi Kelas</p></div>
        </div>
    </a>

    <!-- Modul Jadwal -->
    <a href="dashboard.php?page=jadwal_siswa&id_kelas=<?= $id_kelas ?>" style="text-decoration: none;">
        <div class="card-stat" style="background: linear-gradient(45deg, #2ecc71, #55efc4); padding: 25px; border-radius: 15px;">
            <div><h3 style="font-size: 22px;">📅 Jadwal</h3><p>Jadwal Pelajaran</p></div>
        </div>
    </a>

</div>