<div class="card">
    <h2>👥 Kelola User</h2>
    <form action="proses/tambah_user.php" method="POST">
        <input name="nama" placeholder="Nama" required>
        <input name="email" placeholder="Email" required>
        <input name="password" placeholder="Password" required>
        <select name="role">
            <option>siswa</option>
            <option>guru</option>
            <option>admin</option>
        </select>
        <button type="submit" class="btn btn-add">Tambah</button>
    </form>
</div>

<div class="card">
    <table>
        <tr>
            <th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th>
        </tr>
        <?php
        $data = mysqli_query($conn, "SELECT * FROM users");
        while($d = mysqli_fetch_assoc($data)){
        ?>
        <tr>
            <td><?= $d['nama'] ?></td>
            <td><?= $d['email'] ?></td>
            <td><?= $d['role'] ?></td>
            <td>
                <a href="dashboard.php?page=edit_user&id=<?= $d['id'] ?>" class="btn btn-edit">Edit</a>
                <a href="proses/hapus_user.php?id=<?= $d['id'] ?>" class="btn btn-delete" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>