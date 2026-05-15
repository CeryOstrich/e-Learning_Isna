<div class="card">
    <h2>📊 Laporan Registrasi</h2>
    <p>Daftar seluruh pengguna yang terdaftar di sistem.</p>
</div>

<div class="card">
    <table>
        <tr>
            <th>ID</th><th>Nama</th><th>Email</th><th>Role</th>
        </tr>
        <?php
        $data = mysqli_query($conn, "SELECT * FROM users ORDER BY role ASC");
        while($u = mysqli_fetch_assoc($data)){
        ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= $u['nama'] ?></td>
            <td><?= $u['email'] ?></td>
            <td><span class="badge <?= $u['role'] ?>"><?= $u['role'] ?></span></td>
        </tr>
        <?php } ?>
    </table>
</div>