<!-- GURU: Detail Forum Thread & Balasan -->
<?php
$fid = $_GET['id'];
$thread = mysqli_fetch_assoc(mysqli_query($conn,"SELECT f.*,u.nama as nama_user,k.nama_kelas,m.nama_mapel FROM forum_diskusi f JOIN users u ON f.user_id=u.id JOIN kelas k ON f.kelas_id=k.id JOIN mapel m ON f.mapel_id=m.id WHERE f.id='$fid'"));
?>
<div class="card">
    <a href="dashboard.php?page=forum_guru" class="btn" style="margin-bottom:15px;border:1px solid #ddd;color:#333;">← Kembali ke Forum</a>
    <h2>💬 <?=$thread['judul']?></h2>
    <small>👤 <?=$thread['nama_user']?> &bull; 📚 <?=$thread['nama_mapel']?> (<?=$thread['nama_kelas']?>)</small>
    <p style="margin-top:12px;padding:15px;background:rgba(0,0,0,0.03);border-radius:10px;"><?=$thread['isi']?></p>
</div>

<div class="card">
    <h3>Balasan</h3>
    <?php
    $bq=mysqli_query($conn,"SELECT b.*,u.nama as nama_user FROM balasan_forum b JOIN users u ON b.user_id=u.id WHERE b.forum_id='$fid' ORDER BY b.created_at ASC");
    while($b=mysqli_fetch_assoc($bq)):?>
    <div style="border-left:4px solid var(--main);padding:10px 15px;margin-bottom:10px;border-radius:0 8px 8px 0;background:rgba(0,0,0,0.02);">
        <strong><?=$b['nama_user']?></strong> <small style="color:#888;"><?=date('d M Y H:i',strtotime($b['created_at']))?></small>
        <p style="margin-top:5px;"><?=$b['isi']?></p>
    </div>
    <?php endwhile; ?>

    <form action="proses/tambah_balasan_forum.php" method="POST" style="margin-top:20px;">
        <input type="hidden" name="forum_id" value="<?=$fid?>">
        <textarea name="isi" placeholder="Tulis balasan Anda..." style="width:100%;padding:10px;border-radius:8px;border:1px solid #ddd;min-height:80px;" required></textarea>
        <button type="submit" class="btn btn-add" style="margin-top:8px;">Kirim Balasan</button>
    </form>
</div>
