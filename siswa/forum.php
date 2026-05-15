<!-- SISWA: Forum Diskusi -->
<div class="card">
    <h2>💬 Forum Diskusi</h2>
    <p>Tanyakan hal-hal seputar pelajaran atau ikuti diskusi yang ada.</p>
    <form action="proses/tambah_thread_forum.php" method="POST">
        <select name="kelas_id" required>
            <?php $kq=mysqli_query($conn,"SELECT * FROM kelas"); while($k=mysqli_fetch_assoc($kq)) echo "<option value='{$k['id']}'>{$k['nama_kelas']}</option>"; ?>
        </select>
        <select name="mapel_id" required>
            <?php $mq=mysqli_query($conn,"SELECT * FROM mapel"); while($m=mysqli_fetch_assoc($mq)) echo "<option value='{$m['id']}'>{$m['nama_mapel']}</option>"; ?>
        </select>
        <input type="text" name="judul" placeholder="Topik Diskusi" required>
        <textarea name="isi" placeholder="Isi pertanyaan..." style="width:100%;padding:10px;border-radius:8px;border:1px solid #ddd;" required></textarea>
        <button type="submit" class="btn btn-add">Buat Diskusi Baru</button>
    </form>
</div>
<div class="card">
    <?php
    $fq=mysqli_query($conn,"SELECT f.*,u.nama as nama_user,m.nama_mapel FROM forum_diskusi f JOIN users u ON f.user_id=u.id JOIN mapel m ON f.mapel_id=m.id ORDER BY f.created_at DESC");
    while($f=mysqli_fetch_assoc($fq)):
        $rcount=mysqli_num_rows(mysqli_query($conn,"SELECT id FROM balasan_forum WHERE forum_id='{$f['id']}'"));
    ?>
    <div style="border:1px solid rgba(0,0,0,0.08);border-radius:12px;padding:15px;margin-bottom:12px;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <strong><?=$f['judul']?></strong><br>
            <small style="color:#888;">👤 <?=$f['nama_user']?> &bull; 📚 <?=$f['nama_mapel']?></small>
        </div>
        <a href="dashboard.php?page=detail_forum_siswa&id=<?=$f['id']?>" class="btn btn-edit">💬 <?=$rcount?> Balasan</a>
    </div>
    <?php endwhile; ?>
</div>
