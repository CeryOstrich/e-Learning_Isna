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
    <h2 style="color: #333; margin-bottom: 20px;">🎯 Buat Kuis / Ulangan Baru</h2>
    
    <form action="proses/tambah_kuis.php" method="POST">
        <!-- ID Kelas dikirim secara otomatis dan tersembunyi -->
        <input type="hidden" name="kelas_id" value="<?= $id_kelas ?>">
        
        <div style="display: flex; gap: 15px; margin-bottom: 15px; align-items: center;">
            <div style="flex: 1;">
                <label style="font-weight: 600; color: #555;">Kuis Untuk Kelas:</label>
                <!-- Hanya tampilkan nama teksnya saja -->
                <div style="padding: 11px 15px; background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd; font-weight: bold; color: #e74c3c; margin-top: 5px;">
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
        
        <input type="text" name="judul" placeholder="Judul Kuis (Contoh: Ulangan Harian Bab 1)..." required style="width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #ccc; outline: none; box-sizing: border-box;">
        
        <textarea name="deskripsi" placeholder="Ketik instruksi kuis (Contoh: Kerjakan dengan jujur, waktu 60 menit)..." required style="width: 100%; height: 100px; padding: 12px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #ccc; outline: none; box-sizing: border-box; resize: vertical;"></textarea>
        
        <!-- Tambahan Link Kuis (Bisa untuk Google Form, Quizizz, dll jika belum ada sistem CBT sendiri) -->
        <input type="url" name="link_kuis" placeholder="Link Kuis (Opsional, misal link Google Form / Quizizz)..." style="width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #ccc; outline: none; box-sizing: border-box;">

        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 15px;">
            <div>
                <label style="font-weight: 600; color: #555; margin-right: 10px;">Batas Waktu (Deadline):</label>
                <input type="datetime-local" name="deadline" required style="padding: 10px; border-radius: 8px; border: 1px solid #ccc; outline: none;">
            </div>
            <button type="submit" style="background: #e74c3c; color: white; padding: 12px 30px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 15px;">
                Posting Kuis
            </button>
        </div>
    </form>
</div>

<!-- TABEL KUIS AKTIF KHUSUS KELAS INI -->
<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    <h3 style="color: #333; margin-bottom: 15px;">📋 Daftar Kuis Aktif - Kelas <?= $nama_kelas ?></h3>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd; text-align: left; background: #f8f9fa;">
                <th style="padding: 15px;">Mata Pelajaran</th>
                <th style="padding: 15px;">Judul Kuis</th>
                <th style="padding: 15px;">Batas Waktu</th>
                <th style="padding: 15px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil kuis yang HANYA untuk kelas yang sedang dibuka
            $q_query = mysqli_query($conn, "SELECT q.*, m.nama_mapel FROM kuis q JOIN mapel m ON q.mapel_id = m.id WHERE q.kelas_id = '$id_kelas' ORDER BY q.id DESC");
            
            if($q_query && mysqli_num_rows($q_query) > 0) {
                while($q = mysqli_fetch_assoc($q_query)) {
                    $deadline = date('d M Y, H:i', strtotime($q['deadline']));
            ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 15px; font-weight: 600; color: #7367f0;"><?= $q['nama_mapel'] ?></td>
                <td style="padding: 15px; color: #444; font-weight: 500;"><?= $q['judul'] ?></td>
                <td style="padding: 15px; color: #e74c3c; font-size: 14px;"><i class='bx bx-time'></i> <?= $deadline ?></td>
               <td style="padding: 15px; text-align: center;">
    <!-- Tombol Baru untuk Input Pilihan Ganda -->
    <a href="dashboard.php?page=kelola_soal_guru&id_kuis=<?= $q['id'] ?>&id_kelas=<?= $id_kelas ?>" style="background: #3498db; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold; display: inline-block; margin-bottom: 5px;"><i class='bx bx-list-plus'></i> Kelola Soal PG</a><br>
    
    <a href="dashboard.php?page=nilai_guru&id_kuis=<?= $q['id'] ?>&id_kelas=<?= $id_kelas ?>" style="background: #28c76f; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold; display: inline-block;">Lihat Nilai</a>
    
    <a href="proses/hapus_kuis.php?id=<?= $q['id'] ?>&id_kelas=<?= $id_kelas ?>" onclick="return confirm('Hapus kuis ini?')" style="background: #ea5455; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold; margin-left: 5px; display: inline-block;">Hapus</a>
</td>
            </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center; padding:30px; color:#999; font-style: italic;'>Belum ada kuis yang diposting untuk kelas ini.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>