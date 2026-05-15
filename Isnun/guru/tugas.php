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
    <h2 style="color: #333; margin-bottom: 20px;">📝 Buat Tugas Baru</h2>
    
    <form action="proses/tambah_tugas.php" method="POST">
        <!-- ID Kelas dikirim secara otomatis dan tersembunyi -->
        <input type="hidden" name="kelas_id" value="<?= $id_kelas ?>">
        
        <div style="display: flex; gap: 15px; margin-bottom: 15px; align-items: center;">
            <div style="flex: 1;">
                <label style="font-weight: 600; color: #555;">Tugas Untuk Kelas:</label>
                <!-- Hanya tampilkan nama teksnya saja (tidak bisa diubah) -->
                <div style="padding: 11px 15px; background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd; font-weight: bold; color: #7367f0; margin-top: 5px;">
                    <?= $nama_kelas ?>
                </div>
            </div>
            
            <div style="flex: 1;">
                <label style="font-weight: 600; color: #555;">Mapel:</label>
                <select name="mapel_id" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ccc; outline: none; margin-top: 5px; background: white; cursor: pointer;">
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
        
        <input type="text" name="judul" placeholder="Judul Tugas..." required style="width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #ccc; outline: none; box-sizing: border-box;">
        
        <textarea name="deskripsi" placeholder="Ketik deskripsi atau instruksi tugas di sini..." required style="width: 100%; height: 120px; padding: 12px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #ccc; outline: none; box-sizing: border-box; resize: vertical;"></textarea>
        
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 15px;">
            <div>
                <label style="font-weight: 600; color: #555; margin-right: 10px;">Batas Waktu (Deadline):</label>
                <input type="datetime-local" name="deadline" required style="padding: 10px; border-radius: 8px; border: 1px solid #ccc; outline: none;">
            </div>
            <button type="submit" style="background: #28c76f; color: white; padding: 12px 30px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 15px;">
                Posting Tugas
            </button>
        </div>
    </form>
</div>

<!-- TABEL TUGAS AKTIF KHUSUS KELAS INI -->
<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    <h3 style="color: #333; margin-bottom: 15px;">📋 Tugas Aktif & Pengecekan - Kelas <?= $nama_kelas ?></h3>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd; text-align: left; background: #f8f9fa;">
                <th style="padding: 15px;">Mata Pelajaran</th>
                <th style="padding: 15px;">Judul Tugas</th>
                <th style="padding: 15px;">Batas Waktu</th>
                <th style="padding: 15px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil tugas yang HANYA untuk kelas yang sedang dibuka
            $t_query = mysqli_query($conn, "SELECT t.*, m.nama_mapel FROM tugas t JOIN mapel m ON t.mapel_id = m.id WHERE t.kelas_id = '$id_kelas' ORDER BY t.id DESC");
            
            if(mysqli_num_rows($t_query) > 0) {
                while($t = mysqli_fetch_assoc($t_query)) {
                    $deadline = date('d M Y, H:i', strtotime($t['deadline']));
            ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 15px; font-weight: 600; color: #7367f0;"><?= $t['nama_mapel'] ?></td>
                <td style="padding: 15px; color: #444; font-weight: 500;"><?= $t['judul'] ?></td>
                <td style="padding: 15px; color: #e74c3c; font-size: 14px;"><i class='bx bx-time'></i> <?= $deadline ?></td>
                <td style="padding: 15px; text-align: center;">
                    <a href="dashboard.php?page=cek_tugas&id_tugas=<?= $t['id'] ?>&id_kelas=<?= $id_kelas ?>" style="background: #ff9f43; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold; display: inline-block;">Cek Jawaban</a>
                    
                    <a href="proses/hapus_tugas.php?id=<?= $t['id'] ?>&id_kelas=<?= $id_kelas ?>" onclick="return confirm('Hapus tugas ini?')" style="background: #ea5455; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold; margin-left: 5px; display: inline-block;">Hapus</a>
                </td>
            </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center; padding:30px; color:#999; font-style: italic;'>Belum ada tugas yang diposting untuk kelas ini.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>