<?php
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : 0;

if ($id_kelas == 0) {
    echo "<div class='card'><h2 style='color:red;'>Silakan pilih kelas terlebih dahulu dari Dashboard.</h2></div>";
    exit;
}

$q_kelas = mysqli_query($conn, "SELECT nama_kelas FROM kelas WHERE id = '$id_kelas'");
$d_kelas = mysqli_fetch_assoc($q_kelas);
$nama_kelas = $d_kelas ? $d_kelas['nama_kelas'] : 'Tidak diketahui';
?>

<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px;">
    <h2 style="color: #333; margin-bottom: 20px;">💬 Buat Topik Diskusi Baru</h2>
    
    <form action="proses/tambah_forum.php" method="POST">
        <input type="hidden" name="kelas_id" value="<?= $id_kelas ?>">
        
        <div style="display: flex; gap: 15px; margin-bottom: 15px; align-items: center;">
            <div style="flex: 1;">
                <label style="font-weight: 600; color: #555;">Forum Untuk Kelas:</label>
                <div style="padding: 11px 15px; background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd; font-weight: bold; color: #3498db; margin-top: 5px;">
                    <?= $nama_kelas ?>
                </div>
            </div>
            
            <div style="flex: 1;">
                <label style="font-weight: 600; color: #555;">Mapel:</label>
                <select name="mapel_id" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ccc; outline: none; margin-top: 5px; background: white;">
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
        
        <input type="text" name="judul" placeholder="Topik Diskusi (Contoh: Tanya Jawab Bab 1)..." required style="width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #ccc; outline: none; box-sizing: border-box;">
        
        <textarea name="deskripsi" placeholder="Tuliskan pertanyaan pemantik diskusi di sini..." required style="width: 100%; height: 100px; padding: 12px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #ccc; outline: none; box-sizing: border-box; resize: vertical;"></textarea>
        
        <div style="border-top: 1px solid #eee; padding-top: 15px; text-align: right;">
            <button type="submit" style="background: #3498db; color: white; padding: 12px 30px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 15px;">
                Kirim Topik Diskusi
            </button>
        </div>
    </form>
</div>

<!-- TABEL FORUM -->
<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    <h3 style="color: #333; margin-bottom: 15px;">🗣️ Daftar Diskusi - <?= $nama_kelas ?></h3>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd; text-align: left; background: #f8f9fa;">
                <th style="padding: 15px;">Mapel</th>
                <th style="padding: 15px;">Topik Diskusi</th>
                <th style="padding: 15px;">Tanggal</th>
                <th style="padding: 15px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $f_query = mysqli_query($conn, "SELECT f.*, m.nama_mapel FROM forum f JOIN mapel m ON f.mapel_id = m.id WHERE f.kelas_id = '$id_kelas' ORDER BY f.id DESC");
            
            if($f_query && mysqli_num_rows($f_query) > 0) {
                while($f = mysqli_fetch_assoc($f_query)) {
                    $tanggal = date('d M Y', strtotime($f['created_at']));
            ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 15px; font-weight: 600; color: #7367f0;"><?= $f['nama_mapel'] ?></td>
                <td style="padding: 15px; color: #444; font-weight: 500;"><?= $f['judul'] ?></td>
                <td style="padding: 15px; color: #888; font-size: 14px;"><?= $tanggal ?></td>
                <td style="padding: 15px; text-align: center;">
                    <a href="dashboard.php?page=detail_forum_guru&id_forum=<?= $f['id'] ?>&id_kelas=<?= $id_kelas ?>" style="background: #28c76f; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold; display: inline-block;">Buka Diskusi</a>
                    <a href="proses/hapus_forum.php?id=<?= $f['id'] ?>&id_kelas=<?= $id_kelas ?>" onclick="return confirm('Hapus forum ini?')" style="background: #ea5455; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold; margin-left: 5px; display: inline-block;">Hapus</a>
                </td>
            </tr>
            <?php } } else {
                echo "<tr><td colspan='4' style='text-align:center; padding:30px; color:#999; font-style: italic;'>Belum ada topik diskusi.</td></tr>";
            } ?>
        </tbody>
    </table>
</div>