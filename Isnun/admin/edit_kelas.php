<?php
// Pastikan tidak ada include koneksi di sini ya!
$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM kelas WHERE id = '$id'");
$k = mysqli_fetch_assoc($data);
?>

<div class="card">
    <h2 style="color: #333; margin-bottom: 20px;">✏️ Edit Kelas</h2>
    
    <form action="proses/update_kelas.php" method="POST">
        <input type="hidden" name="id" value="<?= $k['id'] ?>">
        
        <label style="font-weight: 600; color: #555;">Nama Kelas:</label>
        <input name="nama_kelas" value="<?= $k['nama_kelas'] ?>" required style="width: 100%; padding: 12px; margin: 8px 0 20px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; outline: none;">
        
        <label style="font-weight: 600; color: #555;">Fasilitas:</label>
        <textarea name="fasilitas" style="width: 100%; height: 100px; padding: 12px; margin: 8px 0 20px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; outline: none;"><?= $k['fasilitas'] ?></textarea>
        
        <div style="display: flex; gap: 10px; margin-top: 10px;">
            <button type="submit" style="background: #28c76f; color: white; padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 14px;">
                💾 Simpan Perubahan
            </button>
            <a href="dashboard.php?page=kelas" style="background: #ea5455; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px; display: flex; align-items: center;">
                ❌ Batal
            </a>
        </div>
    </form>
</div>