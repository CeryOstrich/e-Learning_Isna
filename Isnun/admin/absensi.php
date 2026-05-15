<!-- ADMIN: Absensi Siswa -->
<div class="card">
    <h2>✅ Kelola Absensi Siswa</h2>
    <form method="GET" action="dashboard.php" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
        <input type="hidden" name="page" value="absensi">
        <div>
            <label>Kelas:</label>
            <select name="kelas_id">
                <option value="">-- Pilih Kelas --</option>
                <?php $kq=mysqli_query($conn,"SELECT * FROM kelas"); while($k=mysqli_fetch_assoc($kq)) echo "<option value='{$k['id']}' ".($_GET['kelas_id']??''==$k['id']?'selected':'').">{$k['nama_kelas']}</option>"; ?>
            </select>
        </div>
        <div>
            <label>Mapel:</label>
            <select name="mapel_id">
                <option value="">-- Pilih Mapel --</option>
                <?php $mq=mysqli_query($conn,"SELECT * FROM mapel"); while($m=mysqli_fetch_assoc($mq)) echo "<option value='{$m['id']}'>{$m['nama_mapel']}</option>"; ?>
            </select>
        </div>
        <div>
            <label>Tanggal:</label>
            <input type="date" name="tanggal" value="<?= $_GET['tanggal'] ?? date('Y-m-d') ?>">
        </div>
        <button type="submit" class="btn btn-main">Filter</button>
    </form>
</div>

<?php if(!empty($_GET['kelas_id']) && !empty($_GET['mapel_id'])): 
    $kelas_id = $_GET['kelas_id'];
    $mapel_id = $_GET['mapel_id'];
    $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
?>
<div class="card">
    <h3>Daftar Absensi — <?= date('d M Y', strtotime($tanggal)) ?></h3>
    <form action="proses/simpan_absensi.php" method="POST">
        <input type="hidden" name="kelas_id" value="<?=$kelas_id?>">
        <input type="hidden" name="mapel_id" value="<?=$mapel_id?>">
        <input type="hidden" name="tanggal" value="<?=$tanggal?>">
        <table>
            <tr><th>#</th><th>Nama Siswa</th><th>Status Kehadiran</th></tr>
            <?php
            $no=1;
            $sq = mysqli_query($conn, "SELECT * FROM users WHERE role='siswa' ORDER BY nama ASC");
            while($s = mysqli_fetch_assoc($sq)):
                $existing = mysqli_fetch_assoc(mysqli_query($conn,"SELECT status FROM absensi WHERE siswa_id='{$s['id']}' AND kelas_id='$kelas_id' AND mapel_id='$mapel_id' AND tanggal='$tanggal'"));
                $status = $existing['status'] ?? 'hadir';
            ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$s['nama']?></td>
                <td>
                    <input type="hidden" name="siswa_id[]" value="<?=$s['id']?>">
                    <?php foreach(['hadir'=>'✅ Hadir','izin'=>'📋 Izin','sakit'=>'🤒 Sakit','alpa'=>'❌ Alpa'] as $val=>$label): ?>
                    <label style="margin-right:12px;cursor:pointer;">
                        <input type="radio" name="status_<?=$s['id']?>" value="<?=$val?>" <?=$status==$val?'checked':''?>>
                        <?=$label?>
                    </label>
                    <?php endforeach; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <button type="submit" class="btn btn-add" style="margin-top:15px;">💾 Simpan Absensi</button>
    </form>
</div>

<div class="card">
    <h3>Rekap Absensi Bulan Ini</h3>
    <table>
        <tr><th>Siswa</th><th>Hadir</th><th>Izin</th><th>Sakit</th><th>Alpa</th></tr>
        <?php
        $sq = mysqli_query($conn, "SELECT * FROM users WHERE role='siswa' ORDER BY nama ASC");
        while($s = mysqli_fetch_assoc($sq)):
            $h = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as c FROM absensi WHERE siswa_id='{$s['id']}' AND kelas_id='$kelas_id' AND status='hadir' AND MONTH(tanggal)=MONTH(NOW())"))['c'];
            $i = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as c FROM absensi WHERE siswa_id='{$s['id']}' AND kelas_id='$kelas_id' AND status='izin' AND MONTH(tanggal)=MONTH(NOW())"))['c'];
            $sk= mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as c FROM absensi WHERE siswa_id='{$s['id']}' AND kelas_id='$kelas_id' AND status='sakit' AND MONTH(tanggal)=MONTH(NOW())"))['c'];
            $a = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as c FROM absensi WHERE siswa_id='{$s['id']}' AND kelas_id='$kelas_id' AND status='alpa' AND MONTH(tanggal)=MONTH(NOW())"))['c'];
        ?>
        <tr>
            <td><?=$s['nama']?></td>
            <td style="color:#27ae60;font-weight:bold;"><?=$h?></td>
            <td style="color:#3498db;"><?=$i?></td>
            <td style="color:#f39c12;"><?=$sk?></td>
            <td style="color:#e74c3c;font-weight:bold;"><?=$a?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php endif; ?>
