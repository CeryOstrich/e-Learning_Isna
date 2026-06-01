<?php
// 1. Tangkap ID Kelas dari URL (misal: detail_kelas.php?id=3)
$id_kelas_detail = isset($_GET['id']) ? $_GET['id'] : 0;

if ($id_kelas_detail == 0) {
    echo "<div class='card'><h2>ID Kelas tidak valid!</h2></div>";
    exit;
}

// 2. Ambil Data Detail Kelas
$q_kelas = mysqli_query($conn, "SELECT * FROM kelas WHERE id = '$id_kelas_detail'");
$d_kelas = mysqli_fetch_assoc($q_kelas);

if (!$d_kelas) {
    echo "<div class='card'><h2>Data kelas tidak ditemukan!</h2></div>";
    exit;
}
?>

<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px;">
    <h2 style="color: #333; margin-bottom: 20px;">🏫 Detail Kelas: <?= $d_kelas['nama_kelas'] ?></h2>

    <!-- Bagian Daftar Siswa -->
    <div style="margin-top: 20px;">
        <h3 style="color: #555; margin-bottom: 15px;"><i class='bx bxs-group'></i> Daftar Siswa</h3>
        <table style="width: 100%; border-collapse: collapse; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.02);">
            <thead>
                <tr style="background: #f8f9fa; text-align: left; border-bottom: 2px solid #eee;">
                    <th style="padding: 15px; width: 50px;">No</th>
                    <th style="padding: 15px;">Nama Siswa</th>
                    <th style="padding: 15px;">Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                // KUNCI SINKRONISASI: Mencari user dengan role 'siswa' yang id_kelas-nya sama
                $query_siswa = mysqli_query($conn, "SELECT * FROM users WHERE role = 'siswa' AND id_kelas = '$id_kelas_detail' ORDER BY nama ASC");

                if (mysqli_num_rows($query_siswa) > 0) {
                    while ($s = mysqli_fetch_assoc($query_siswa)) {
                ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px;"><?= $no++; ?></td>
                    <td style="padding: 15px; font-weight: 500; color: #444;"><?= $s['nama']; ?></td>
                    <td style="padding: 15px; color: #777;"><?= $s['email']; ?></td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align:center; padding:30px; color:#999; font-style: italic;'>Belum ada siswa yang terdaftar di kelas ini.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">

    <!-- Bagian Fasilitas Kelas -->
    <div style="margin-top: 20px;">
        <h3 style="color: #555; margin-bottom: 15px;"><i class='bx bxs-wrench'></i> Fasilitas Kelas</h3>
        <div style="padding: 20px; background: #fdfdfd; border-left: 5px solid #7367f0; border-radius: 8px;">
            <?php 
            // Fix Error Deprecated trim() dengan null coalescing (?? '')
            $fasilitas = $d_kelas['fasilitas'] ?? ''; 
            if (!empty(trim($fasilitas))) {
                echo nl2br(htmlspecialchars($fasilitas));
            } else {
                echo "<span style='color: #999; font-style: italic;'>Belum ada fasilitas yang ditambahkan untuk kelas ini.</span>";
            }
            ?>
        </div>
    </div>

    <div style="margin-top: 30px;">
        <a href="dashboard.php?page=kelas" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">
            <i class='bx bx-arrow-back'></i> Kembali
        </a>
    </div>
</div>