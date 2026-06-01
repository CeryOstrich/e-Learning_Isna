<div class="card">
    <h2>📅 Jadwal Pelajaran Saya</h2>
    <p>Berikut adalah jadwal pelajaran Anda berdasarkan kelas Anda.</p>
</div>

<div class="card">
    <table>
        <tr>
            <th>Hari</th>
            <th>Jam</th>
            <th>Mata Pelajaran</th>
        </tr>
        <?php
        // TODO: Ensure $user_kelas_id is fetched from session or db
        // For now, hardcode to demonstration or fetch all
        $j_query = mysqli_query($conn, "SELECT jadwal.*, mapel.nama_mapel 
                                      FROM jadwal 
                                      JOIN mapel ON jadwal.mapel_id = mapel.id 
                                      ORDER BY hari ASC");
        while($j = mysqli_fetch_assoc($j_query)){
        ?>
        <tr>
            <td><?= $j['hari'] ?></td>
            <td><?= $j['jam'] ?></td>
            <td><?= $j['nama_mapel'] ?></td>
        </tr>
        <?php } ?>
    </table>
</div>
