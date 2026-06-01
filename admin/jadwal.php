<div class="card">
    <h2>📅 Kelola Jadwal Pelajaran</h2>
    <form action="proses/tambah_jadwal.php" method="POST">
        <label>Pilih Kelas:</label>
        <select name="kelas_id" required>
            <option value="">-- Pilih Kelas --</option>
            <?php
            $k_query = mysqli_query($conn, "SELECT * FROM kelas");
            while($k = mysqli_fetch_assoc($k_query)) {
                echo "<option value='".$k['id']."'>".$k['nama_kelas']."</option>";
            }
            ?>
        </select>

        <label>Pilih Mapel:</label>
        <select name="mapel_id" required>
            <option value="">-- Pilih Mapel --</option>
            <?php
            $m_query = mysqli_query($conn, "SELECT * FROM mapel");
            while($m = mysqli_fetch_assoc($m_query)) {
                echo "<option value='".$m['id']."'>".$m['nama_mapel']."</option>";
            }
            ?>
        </select>

        <input type="text" name="hari" placeholder="Contoh: Senin" required>
        <input type="text" name="jam" placeholder="Contoh: 08:00 - 10:00" required>
        
        <button type="submit" class="btn btn-add">Tambah Jadwal</button>
    </form>
</div>

<div class="card">
    <table>
        <tr>
            <th>No</th>
            <th>Kelas</th>
            <th>Mata Pelajaran</th>
            <th>Hari</th>
            <th>Jam</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        $j_query = mysqli_query($conn, "SELECT jadwal.*, kelas.nama_kelas, mapel.nama_mapel 
                                      FROM jadwal 
                                      JOIN kelas ON jadwal.kelas_id = kelas.id 
                                      JOIN mapel ON jadwal.mapel_id = mapel.id 
                                      ORDER BY hari ASC");
        while($j = mysqli_fetch_assoc($j_query)){
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $j['nama_kelas'] ?></td>
            <td><?= $j['nama_mapel'] ?></td>
            <td><?= $j['hari'] ?></td>
            <td><?= $j['jam'] ?></td>
            <td>
                <a href="proses/hapus_jadwal.php?id=<?= $j['id'] ?>" class="btn btn-delete" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>