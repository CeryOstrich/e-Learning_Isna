<!-- ADMIN: Pengumuman -->
<div class="card">
    <h2>📢 Buat Pengumuman</h2>
    <form action="proses/tambah_pengumuman.php" method="POST">
        <label>Judul Pengumuman:</label>
        <input type="text" name="judul" placeholder="Judul pengumuman..." required>
        <label>Isi Pengumuman:</label>
        <textarea name="isi" placeholder="Tulis isi pengumuman di sini..." required style="width:100%;padding:10px;border-radius:8px;border:1px solid #ddd;min-height:100px;margin:5px 0;"></textarea>
        <label>Tujuan / Target:</label>
        <select name="target" required>
            <option value="semua">🌐 Semua Pengguna</option>
            <option value="guru">👨‍🏫 Guru Saja</option>
            <option value="siswa">🎓 Siswa Saja</option>
        </select>
        <button type="submit" class="btn btn-add" style="margin-top:10px;">📤 Posting Pengumuman</button>
    </form>
</div>

<div class="card">
    <h3>Daftar Pengumuman Aktif</h3>
    <table>
        <tr><th>Judul</th><th>Target</th><th>Tanggal</th><th>Aksi</th></tr>
        <?php
        $pq = mysqli_query($conn, "SELECT * FROM pengumuman ORDER BY id DESC");
        while($p = mysqli_fetch_assoc($pq)):
            $target_label = ['semua'=>'🌐 Semua','guru'=>'👨‍🏫 Guru','siswa'=>'🎓 Siswa'];
        ?>
        <tr>
            <td>
                <strong><?= $p['judul'] ?></strong><br>
                <small style="color:#888;"><?= mb_substr($p['isi'], 0, 80) ?>...</small>
            </td>
            <td><span class="badge" style="background:#3498db;color:white;padding:4px 10px;border-radius:20px;"><?= $target_label[$p['target']] ?></span></td>
            <td><?= date('d M Y', strtotime($p['created_at'])) ?></td>
            <td><a href="proses/hapus_pengumuman.php?id=<?= $p['id'] ?>" class="btn btn-delete" onclick="return confirm('Hapus pengumuman ini?')">Hapus</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
