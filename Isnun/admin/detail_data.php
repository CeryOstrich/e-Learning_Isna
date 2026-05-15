<?php 
include "template.php"; 
include "../database.php"; 

$type = $_GET['type']; // Mengambil tipe (siswa atau guru)

if ($type == 'siswa') {
    $judul = "Data Seluruh Siswa Sekolah";
    $query = mysqli_query($conn, "SELECT nama_siswa AS nama, kelas AS info FROM daftar_siswa");
    $kolom_info = "Kelas";
} else {
    $judul = "Data Seluruh Guru Sekolah";
    $query = mysqli_query($conn, "SELECT nama_guru AS nama, mapel AS info FROM daftar_guru");
    $kolom_info = "Mata Pelajaran";
}
?>

<div class="card">
    <h2>📋 <?= $judul; ?></h2>
    <a href="../dashboard.php" class="btn btn-edit" style="margin-top:10px; display:inline-block;">⬅ Kembali ke Dashboard</a>
</div>

<div class="card">
    <table border="1" width="100%" cellpadding="10">
        <thead>
            <tr style="background: #f4f4f4;">
                <th>No</th>
                <th>Nama Lengkap</th>
                <th><?= $kolom_info; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($row = mysqli_fetch_assoc($query)){ 
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['info']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>