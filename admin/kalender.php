<!-- ADMIN: Kalender Kegiatan -->
<div style="display:grid;grid-template-columns:1fr 1.2fr;gap:20px;flex-wrap:wrap;" class="dual-col">

<div class="card">
    <h2>📅 Tambah Kegiatan</h2>
    <form action="proses/tambah_kalender.php" method="POST">
        <label>Nama Kegiatan:</label>
        <input type="text" name="judul" placeholder="Contoh: UTS Semester 1" required>
        <label>Tanggal Mulai:</label>
        <input type="date" name="tanggal_mulai" required>
        <label>Tanggal Selesai:</label>
        <input type="date" name="tanggal_selesai" required>
        <label>Keterangan (opsional):</label>
        <textarea name="keterangan" style="width:100%;padding:8px;border-radius:8px;border:1px solid #ddd;"></textarea>
        <label>Warna Penanda:</label>
        <div style="display:flex;gap:8px;margin:8px 0;">
            <?php foreach(['#3498db'=>'Biru','#27ae60'=>'Hijau','#e74c3c'=>'Merah','#f39c12'=>'Oranye','#9b59b6'=>'Ungu'] as $hex=>$name): ?>
            <label style="cursor:pointer;display:flex;gap:4px;align-items:center;">
                <input type="radio" name="warna" value="<?=$hex?>" <?=$hex=='#3498db'?'checked':''?>>
                <span style="display:inline-block;width:20px;height:20px;border-radius:50%;background:<?=$hex?>"></span>
            </label>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-add" style="margin-top:8px;">Simpan Kegiatan</button>
    </form>
</div>

<div class="card">
    <h3>Agenda Mendatang</h3>
    <div id="calendarView" style="margin-bottom:15px;"></div>
    <?php
    $kq = mysqli_query($conn, "SELECT * FROM kalender ORDER BY tanggal_mulai ASC");
    while($k = mysqli_fetch_assoc($kq)):
        $is_past = strtotime($k['tanggal_selesai']) < time();
    ?>
    <div style="border-left:5px solid <?=$k['warna']?>;padding:10px 15px;margin-bottom:10px;border-radius:0 8px 8px 0;background:rgba(0,0,0,0.02);<?=$is_past?'opacity:0.5':''?>">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <strong style="font-size:15px;"><?=$k['judul']?></strong><br>
                <small style="color:#888;">📅 <?=date('d M Y',strtotime($k['tanggal_mulai']))?> — <?=date('d M Y',strtotime($k['tanggal_selesai']))?></small>
                <?php if($k['keterangan']): ?><br><small><?=$k['keterangan']?></small><?php endif; ?>
            </div>
            <a href="proses/hapus_kalender.php?id=<?=$k['id']?>" class="btn btn-delete" onclick="return confirm('Hapus kegiatan?')" style="padding:6px 12px;">✕</a>
        </div>
    </div>
    <?php endwhile; ?>
</div>

</div>
