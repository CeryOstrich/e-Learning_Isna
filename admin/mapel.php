<div class="card">
    <h2>📚 Mata Pelajaran</h2>
    <form action="proses/tambah_mapel.php" method="POST">
        <input name="nama_mapel" placeholder="Nama Mapel" required>
        <button type="submit" class="btn btn-add">Tambah</button>
    </form>
</div>

<div class="card">
    <table>
        <tr>
            <th>ID</th>
            <th>Mata Pelajaran</th>
            <th>Aksi</th>
        </tr>
        <?php
        $data = mysqli_query($conn, "SELECT * FROM mapel");
        while($m = mysqli_fetch_assoc($data)){
        ?>
        <tr>
            <td><?= $m['id'] ?></td>
            <td><?= $m['nama_mapel'] ?></td>
            <td>
                <a href="proses/hapus_mapel.php?id=<?= $m['id'] ?>" class="btn btn-delete" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>