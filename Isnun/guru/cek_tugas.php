<!-- GURU: Cek Pengumpulan Tugas & Beri Nilai -->
<?php
$tugas_id = $_GET['id'];
$tugas = mysqli_fetch_assoc(mysqli_query($conn,"SELECT t.*,m.nama_mapel,k.nama_kelas FROM tugas t JOIN mapel m ON t.mapel_id=m.id JOIN kelas k ON t.kelas_id=k.id WHERE t.id='$tugas_id'"));
?>

<div class="card">
    <a href="dashboard.php?page=tugas_guru" class="btn" style="border:1px solid #ddd;color:#333;margin-bottom:12px;">← Kembali ke Tugas</a>
    <h2>✅ Penilaian Tugas</h2>
    <p><strong><?=$tugas['judul']?></strong> &bull; <?=$tugas['nama_mapel']?> (<?=$tugas['nama_kelas']?>) &bull; Deadline: <span style="color:red;"><?=date('d M Y H:i',strtotime($tugas['deadline']))?></span></p>
</div>

<div class="card">
    <h3>Daftar Pengumpulan Siswa</h3>
    <table>
        <tr><th>Siswa (ID)</th><th>File Tugas</th><th>Dikumpulkan</th><th>Nilai</th><th>Feedback</th><th>Aksi</th></tr>
        <?php
        $pq=mysqli_query($conn,"SELECT p.*, u.nama FROM pengumpulan_tugas p JOIN users u ON p.siswa_id=u.id WHERE p.tugas_id='$tugas_id' ORDER BY p.dikumpulkan_pada ASC");
        while($p=mysqli_fetch_assoc($pq)):?>
        <tr>
            <td><?=$p['nama']?></td>
            <td><a href="uploads/tugas/<?=$p['file_tugas']?>" target="_blank" class="btn btn-edit">📄 Lihat</a></td>
            <td><?=date('d M Y H:i',strtotime($p['dikumpulkan_pada']))?></td>
            <td><?= $p['nilai'] !== null ? "<strong>{$p['nilai']}</strong>" : '<span style="color:#aaa;">Belum</span>' ?></td>
            <td><?= $p['feedback_guru'] ?? '-' ?></td>
            <td>
                <form action="proses/nilai_tugas.php" method="POST" style="display:flex;gap:6px;flex-wrap:wrap;">
                    <input type="hidden" name="pengumpulan_id" value="<?=$p['id']?>">
                    <input type="hidden" name="tugas_id" value="<?=$tugas_id?>">
                    <input type="number" name="nilai" min="0" max="100" value="<?=$p['nilai']?>" placeholder="Nilai" style="width:70px;padding:6px;">
                    <input type="text" name="feedback_guru" value="<?=$p['feedback_guru']?>" placeholder="Catatan...">
                    <button type="submit" class="btn btn-add" style("padding:6px 12px;">Simpan</button>
                </form>
            </td>
        </tr>
        <?php endwhile;?>
    </table>
</div>
