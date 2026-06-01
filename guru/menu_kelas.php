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
        <h2 style="color: #333; margin: 0;">👨‍🏫 Ruang Mengajar: <?= $kelas['nama_kelas'] ?></h2>
        <p style="color: #777; margin-top: 5px;">Kelola aktivitas belajar khusus untuk kelas ini.</p>
    </div>
    <a href="dashboard.php?page=home" class="btn" style="background: #6c757d; color: white;">⬅️ Kembali ke Dashboard</a>
</div>

<!-- GRID MENU KELAS GURU -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
    
    <!-- Modul Materi -->
    <a href="dashboard.php?page=materi_guru&id_kelas=<?= $id_kelas ?>" style="text-decoration: none;">
        <div class="card-stat bg-purple" style="padding: 25px; border-radius: 15px;">
            <div><h3 style="font-size: 22px;">📖 Kelola Materi</h3><p>Upload Bahan Ajar</p></div>
        </div>
    </a>

    <!-- Modul Tugas -->
    <a href="dashboard.php?page=tugas_guru&id_kelas=<?= $id_kelas ?>" style="text-decoration: none;">
        <div class="card-stat bg-orange" style="padding: 25px; border-radius: 15px;">
            <div><h3 style="font-size: 22px;">📝 Kelola Tugas</h3><p>Buat & Periksa Tugas</p></div>
        </div>
    </a>

    <!-- Modul Kuis -->
    <a href="dashboard.php?page=quiz_guru&id_kelas=<?= $id_kelas ?>" style="text-decoration: none;">
        <div class="card-stat bg-pink" style="padding: 25px; border-radius: 15px;">
            <div><h3 style="font-size: 22px;">🎯 Buat Kuis</h3><p>Siapkan Ujian/Ulangan</p></div>
        </div>
    </a>

    <!-- Modul Live Class -->
    <a href="dashboard.php?page=live_class_guru&id_kelas=<?= $id_kelas ?>" style="text-decoration: none;">
        <div class="card-stat" style="background: linear-gradient(45deg, #e74c3c, #ff7675); padding: 25px; border-radius: 15px;">
            <div><h3 style="font-size: 22px;">📹 Live Class</h3><p>Mulai Tatap Muka Virtual</p></div>
        </div>
    </a>

    <!-- Modul Forum -->
    <a href="dashboard.php?page=forum_guru&id_kelas=<?= $id_kelas ?>" style="text-decoration: none;">
        <div class="card-stat" style="background: linear-gradient(45deg, #3498db, #6dd5ed); padding: 25px; border-radius: 15px;">
            <div><h3 style="font-size: 22px;">💬 Forum Diskusi</h3><p>Tanya Jawab dengan Siswa</p></div>
        </div>
    </a>

    <!-- Modul Nilai (Khusus Kelas Ini) -->
    <a href="dashboard.php?page=nilai_guru&id_kelas=<?= $id_kelas ?>" style="text-decoration: none;">
        <div class="card-stat" style="background: linear-gradient(45deg, #2ecc71, #55efc4); padding: 25px; border-radius: 15px;">
            <div><h3 style="font-size: 22px;">📊 Rekap Nilai</h3><p>Nilai Siswa Kelas Ini</p></div>
        </div>
    </a>

</div>