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
    <h2 style="color: #333; margin-bottom: 5px;">📹 Jadwal Live Class</h2>
    <p style="color: #777; margin-bottom: 20px;">Berikut adalah sesi tatap muka virtual (Zoom/Google Meet) untuk kelas <b><?= $nama_kelas ?></b>.</p>

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd; text-align: left; background: #f8f9fa;">
                <th style="padding: 15px;">Mata Pelajaran</th>
                <th style="padding: 15px;">Topik / Judul Sesi</th>
                <th style="padding: 15px;">Waktu Pelaksanaan</th>
                <th style="padding: 15px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // KUNCI SINKRONISASI: Filter pakai kelas_id dan urutkan pakai waktu_mulai yang benar
            $lc_query = mysqli_query($conn, "SELECT lc.*, m.nama_mapel FROM live_class lc JOIN mapel m ON lc.mapel_id = m.id WHERE lc.kelas_id = '$id_kelas' ORDER BY lc.waktu_mulai DESC");

            if($lc_query && mysqli_num_rows($lc_query) > 0) {
                while($lc = mysqli_fetch_assoc($lc_query)) {
                    $waktu = date('d M Y, H:i', strtotime($lc['waktu_mulai']));
            ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 15px; font-weight: 600; color: #7367f0;"><?= $lc['nama_mapel'] ?></td>
                <td style="padding: 15px; color: #444; font-weight: 500;"><?= $lc['judul'] ?></td>
                <td style="padding: 15px; color: #e74c3c; font-size: 14px;"><i class='bx bx-time-five'></i> <?= $waktu ?></td>
                <td style="padding: 15px; text-align: center;">
                    <a href="<?= $lc['link_vicon'] ?>" target="_blank" style="background: #3498db; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold; display: inline-block;"><i class='bx bxs-video'></i> Ikuti Kelas</a>
                </td>
            </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='4' style='text-align:center; padding:40px; color:#999; font-style: italic;'>Belum ada jadwal Live Class untuk kelas ini.</td></tr>";
            } 
            ?>
        </tbody>
    </table>
</div>