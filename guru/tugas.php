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

<div class="card">
    <h2 style="color: var(--text); margin-bottom: 20px;">📝 Buat Tugas Baru</h2>
    
    <form action="proses/tambah_tugas.php" method="POST">
        <!-- ID Kelas dikirim secara otomatis dan tersembunyi -->
        <input type="hidden" name="kelas_id" value="<?= $id_kelas ?>">
        
        <div style="display: flex; gap: 15px; margin-bottom: 15px; align-items: center;">
            <div style="flex: 1;">
                <label style="font-weight: 600; color: var(--text-muted);">Tugas Untuk Kelas:</label>
                <div style="padding: 11px 15px; background: var(--neutral-bg); border-radius: 8px; border: 1px solid var(--border); font-weight: bold; color: var(--main); margin-top: 5px;">
                    <?= $nama_kelas ?>
                </div>
            </div>
            
            <div style="flex: 1;">
                <label style="font-weight: 600; color: var(--text-muted);">Mapel:</label>
                <select name="mapel_id" required>
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
        
        <input type="text" name="judul" placeholder="Judul Tugas..." required>
        
        <textarea name="deskripsi" placeholder="Ketik deskripsi atau instruksi tugas di sini..." required style="width: 100%; height: 120px; padding: 12px; margin-bottom: 15px; border-radius: 8px; border: 1px solid var(--border); background: var(--card-bg); color: var(--text); outline: none; box-sizing: border-box; resize: vertical;"></textarea>
        
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 15px;">
            <div>
                <label style="font-weight: 600; color: var(--text-muted); margin-right: 10px;">Batas Waktu (Deadline):</label>
                <input type="datetime-local" name="deadline" required style="width: auto;">
            </div>
            <button type="submit" class="btn btn-main">
                Posting Tugas
            </button>
        </div>
    </form>
</div>

<!-- TABEL TUGAS AKTIF KHUSUS KELAS INI -->
<div class="card">
    <h3 style="color: var(--text); margin-bottom: 15px;">📋 Tugas Aktif & Pengecekan - Kelas <?= $nama_kelas ?></h3>
    <table>
        <thead>
            <tr>
                <th>Mata Pelajaran</th>
                <th>Judul Tugas</th>
                <th>Batas Waktu</th>
                <th style="text-align: center;">Aksi</th>
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
            <tr>
                <td style="font-weight: 600; color: var(--main);"><?= $t['nama_mapel'] ?></td>
                <td style="font-weight: 500;"><?= $t['judul'] ?></td>
                <td style="color: var(--danger-text); font-size: 14px;"><i class='bx bx-time'></i> <?= $deadline ?></td>
                <td style="text-align: center;">
                    <a href="dashboard.php?page=cek_tugas&id_tugas=<?= $t['id'] ?>&id_kelas=<?= $id_kelas ?>" class="btn btn-edit">Cek Jawaban</a>
                    
                    <a href="proses/hapus_tugas.php?id=<?= $t['id'] ?>&id_kelas=<?= $id_kelas ?>" onclick="return confirm('Hapus tugas ini?')" class="btn btn-delete" style="margin-left: 5px;">Hapus</a>
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