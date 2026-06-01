<?php
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : 0;

if ($id_kelas == 0) {
    echo "<div class='card'><h2 style='color:red;'>Akses ditolak! Silakan masuk melalui ruang kelas di Dashboard.</h2></div>";
    exit;
}

$q_kelas = mysqli_query($conn, "SELECT nama_kelas FROM kelas WHERE id = '$id_kelas'");
$nama_kelas = mysqli_fetch_assoc($q_kelas)['nama_kelas'] ?? 'Tidak diketahui';
?>

<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    <h2 style="color: #333; margin-bottom: 5px;">📝 Tugas Kelas</h2>
    <p style="color: #777; margin-bottom: 20px;">Kerjakan tugas yang diberikan guru untuk kelas <b><?= $nama_kelas ?></b> sebelum batas waktu (deadline) berakhir.</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <?php
        // KUNCI SINKRONISASI TUGAS
        $t_query = mysqli_query($conn, "SELECT t.*, m.nama_mapel FROM tugas t JOIN mapel m ON t.mapel_id = m.id WHERE t.kelas_id = '$id_kelas' ORDER BY t.deadline ASC");
        
        if($t_query && mysqli_num_rows($t_query) > 0) {
            while($t = mysqli_fetch_assoc($t_query)) {
                $deadline = date('d M Y, H:i', strtotime($t['deadline']));
        ?>
        <div style="border: 1px solid #ffeaa7; border-radius: 12px; padding: 20px; background: #fffdf5; transition: 0.3s; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                <span style="background: #fdcb6e; color: #2d3436; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;"><?= $t['nama_mapel'] ?></span>
                <span style="color: #d63031; font-size: 12px; font-weight: bold; background: #ff767520; padding: 4px 8px; border-radius: 6px;"><i class='bx bx-time'></i> <?= $deadline ?></span>
            </div>
            <h3 style="margin: 0 0 10px 0; color: #444; font-size: 18px;"><?= $t['judul'] ?></h3>
            <p style="color: #666; font-size: 14px; margin-bottom: 15px; line-height: 1.5;"><?= nl2br($t['deskripsi']) ?></p>
            
            <a href="dashboard.php?page=kumpul_tugas&id_tugas=<?= $t['id'] ?>&id_kelas=<?= $id_kelas ?>" style="display: block; text-align:center; background: #00b894; color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: bold;"><i class='bx bx-upload'></i> Kumpulkan Tugas</a>
        </div>
        <?php 
            }
        } else {
            echo "<div style='grid-column: 1 / -1; text-align:center; padding:40px; background:#f9f9f9; border-radius:10px; color:#999;'>Tidak ada tugas yang aktif saat ini. Bebas tugas! 🎉</div>";
        }
        ?>
    </div>
</div>