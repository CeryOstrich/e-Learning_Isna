<!-- SISWA: Kumpulkan Tugas -->
<?php
$tid = $_GET['id'];
$tugas = mysqli_fetch_assoc(mysqli_query($conn,"SELECT t.*,m.nama_mapel FROM tugas t JOIN mapel m ON t.mapel_id=m.id WHERE t.id='$tid'"));
?>
<div class="card">
    <a href="dashboard.php?page=tugas_siswa" class="btn" style="margin-bottom:12px;border:1px solid #ddd;color:#333;">← Kembali ke Tugas</a>
    <h2>📤 Kumpulkan Tugas</h2>
    <p><strong><?=$tugas['judul']?></strong> &bull; <?=$tugas['nama_mapel']?></p>
    <p><?=$tugas['deskripsi']?></p>
    <p style="color:red;font-weight:bold;">⏰ Deadline: <?=date('d M Y H:i',strtotime($tugas['deadline']))?></p>
</div>
<div class="card">
    <form action="proses/kumpulkan_tugas.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="tugas_id" value="<?=$tid?>">
        <label>Upload File Jawaban (PDF/DOC/JPG):</label>
        <input type="file" name="file_tugas" required>
        <label>Catatan (opsional):</label>
        <textarea name="catatan" placeholder="Catatan untuk guru..." style="width:100%;padding:10px;border-radius:8px;border:1px solid #ddd;"></textarea>
        <button type="submit" class="btn btn-add" style="margin-top:10px;">📤 Kumpulkan Tugas</button>
    </form>
</div>
