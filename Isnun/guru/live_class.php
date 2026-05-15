<?php
// Tangkap ID Kelas dari URL
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : 0;

if ($id_kelas == 0) {
    echo "<div class='card'><h2 style='color:red;'>Silakan pilih kelas terlebih dahulu dari Dashboard.</h2></div>";
    exit;
}

// Ambil nama kelas untuk ditampilkan di teks informasi
$q_kelas = mysqli_query($conn, "SELECT nama_kelas FROM kelas WHERE id = '$id_kelas'");
$d_kelas = mysqli_fetch_assoc($q_kelas);
$nama_kelas = $d_kelas ? $d_kelas['nama_kelas'] : 'Tidak diketahui';
?>

<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px;">
    <!-- Header Live Class -->
    <div style="margin-bottom: 20px;">
        <h2 style="color: #333; margin-bottom: 5px;">📡 Live Class</h2>
        <p style="color: #777;">Jadwalkan kelas langsung dan bagikan link Zoom / Google Meet kepada siswa.</p>
    </div>

    <hr style="border: 0; border-top: 1px solid #eaeaea; margin: 20px 0;">

    <h3 style="color: #333; margin-bottom: 20px;">📹 Buat Jadwal Live Class</h3>
    
    <form action="proses/tambah_live.php" method="POST">
        <!-- ID Kelas dikirim secara otomatis dan tersembunyi -->
        <input type="hidden" name="kelas_id" value="<?= $id_kelas ?>">
        
        <div style="display: flex; gap: 15px; margin-bottom: 15px; align-items: center;">
            <div style="flex: 1;">
                <label style="font-weight: 600; color: #555; display: block; margin-bottom: 5px;">Live Class Untuk Kelas:</label>
                <!-- Teks kelas otomatis dari database, TIDAK BISA DIUBAH (Hanya Info) -->
                <div style="padding: 12px 15px; background: #fdf2f2; border-radius: 8px; border: 1px solid #fbd5d5; font-weight: bold; color: #e74c3c;">
                    <?= $nama_kelas ?>
                </div>
            </div>
            
            <div style="flex: 1;">
                <label style="font-weight: 600; color: #555; display: block; margin-bottom: 5px;">Mapel:</label>
                <select name="mapel_id" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ccc; outline: none; background: white; cursor: pointer;">
                    <option value="">-- Pilih Mapel --</option>
                    <?php
                    $m_query = mysqli_query($conn, "SELECT * FROM mapel");
                    while($m = mysqli_fetch_assoc($m_query)) {
                        echo "<option value='{$m['id']}'>{$m['nama_mapel']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        
        <input type="text" name="judul" placeholder="Topik Pembahasan (Contoh: Bedah Soal Matematika)..." required style="width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #ccc; outline: none; box-sizing: border-box;">
        
        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <label style="font-weight: 600; color: #555; display: block; margin-bottom: 5px;">Waktu Mulai:</label>
                <input type="datetime-local" name="waktu_mulai" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; outline: none; box-sizing: border-box;">
            </div>
            <div style="flex: 1;">
                <label style="font-weight: 600; color: #555; display: block; margin-bottom: 5px;">Link Vicon (Zoom/GMeet):</label>
                <input type="url" name="link_vicon" placeholder="https://meet.google.com/..." required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ccc; outline: none; box-sizing: border-box;">
            </div>
        </div>

        <div style="border-top: 1px solid #eee; padding-top: 15px; text-align: right;">
            <button type="submit" style="background: #e74c3c; color: white; padding: 12px 30px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 15px;">
                🔴 Buat Live Class
            </button>
        </div>
    </form>
</div>

<!-- TABEL LIVE CLASS -->
<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    <h3 style="color: #333; margin-bottom: 15px;">📅 Jadwal Live Class - <?= $nama_kelas ?></h3>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd; text-align: left; background: #f8f9fa;">
                <th style="padding: 15px;">Mata Pelajaran</th>
                <th style="padding: 15px;">Topik</th>
                <th style="padding: 15px;">Waktu</th>
                <th style="padding: 15px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil jadwal yang HANYA untuk kelas yang sedang dibuka
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
                    <a href="<?= $lc['link_vicon'] ?>" target="_blank" style="background: #3498db; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold; display: inline-block;">Buka Link</a>
                    <a href="proses/hapus_live.php?id=<?= $lc['id'] ?>&id_kelas=<?= $id_kelas ?>" onclick="return confirm('Hapus jadwal ini?')" style="background: #ea5455; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold; margin-left: 5px; display: inline-block;">Hapus</a>
                </td>
            </tr>
            <?php } } else {
                echo "<tr><td colspan='4' style='text-align:center; padding:30px; color:#999; font-style: italic;'>Belum ada jadwal Live Class untuk kelas ini.</td></tr>";
            } ?>
        </tbody>
    </table>
</div>