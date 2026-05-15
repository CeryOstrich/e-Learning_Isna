<?php
// Tangkap ID Kelas tempat siswa sedang berada
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : 0;

if ($id_kelas == 0) {
    echo "<div class='card'><h2 style='color:red;'>Akses ditolak! Silakan masuk melalui ruang kelas di Dashboard.</h2></div>";
    exit;
}

$q_kelas = mysqli_query($conn, "SELECT nama_kelas FROM kelas WHERE id = '$id_kelas'");
$nama_kelas = mysqli_fetch_assoc($q_kelas)['nama_kelas'] ?? 'Tidak diketahui';
?>

<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    <h2 style="color: #333; margin-bottom: 5px;">📖 Materi Pelajaran</h2>
    <p style="color: #777; margin-bottom: 20px;">Berikut adalah daftar materi yang diberikan guru untuk kelas <b><?= $nama_kelas ?></b>.</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <?php
        // INI KUNCI SINKRONISASINYA: WHERE mat.kelas_id = '$id_kelas'
        $mat_query = mysqli_query($conn, "SELECT mat.*, m.nama_mapel FROM materi mat JOIN mapel m ON mat.mapel_id = m.id WHERE mat.kelas_id = '$id_kelas' ORDER BY mat.id DESC");
        
        if($mat_query && mysqli_num_rows($mat_query) > 0) {
            while($mat = mysqli_fetch_assoc($mat_query)) {
                $tanggal = isset($mat['created_at']) ? date('d M Y', strtotime($mat['created_at'])) : date('d M Y');
        ?>
        <div style="border: 1px solid #eee; border-radius: 12px; padding: 20px; background: #fdfdfd; transition: 0.3s; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                <span style="background: #e0dcfc; color: #7367f0; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;"><?= $mat['nama_mapel'] ?></span>
                <span style="color: #999; font-size: 12px;"><i class='bx bx-calendar'></i> <?= $tanggal ?></span>
            </div>
            <h3 style="margin: 0 0 10px 0; color: #444; font-size: 18px;"><?= $mat['judul'] ?></h3>
            <p style="color: #666; font-size: 14px; margin-bottom: 15px; line-height: 1.5;"><?= nl2br($mat['deskripsi']) ?></p>
            
            <div style="display: flex; gap: 10px;">
                <?php if(!empty($mat['file_materi'])) { ?>
                    <a href="uploads/materi/<?= $mat['file_materi'] ?>" target="_blank" style="flex:1; text-align:center; background: #7367f0; color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: bold;"><i class='bx bxs-download'></i> Download File</a>
                <?php } ?>
                
                <?php if(!empty($mat['link_video'])) { ?>
                    <a href="<?= $mat['link_video'] ?>" target="_blank" style="flex:1; text-align:center; background: #ff4757; color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: bold;"><i class='bx bxl-youtube'></i> Tonton Video</a>
                <?php } ?>
            </div>
        </div>
        <?php 
            }
        } else {
            echo "<div style='grid-column: 1 / -1; text-align:center; padding:40px; background:#f9f9f9; border-radius:10px; color:#999;'>Hore! Belum ada materi baru dari guru.</div>";
        }
        ?>
    </div>
</div>