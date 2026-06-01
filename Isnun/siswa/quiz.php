<?php
// 1. Tangkap ID Kelas dari URL (Hasil pilihan siswa di dashboard)
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : 0;

if ($id_kelas == 0) {
    echo "<div class='card'><h2 style='color:red;'>Akses ditolak! Pilih kelas dari Dashboard.</h2></div>";
    exit;
}

// Ambil nama kelas untuk info
$q_kelas = mysqli_query($conn, "SELECT nama_kelas FROM kelas WHERE id = '$id_kelas'");
$d_kelas = mysqli_fetch_assoc($q_kelas);
$nama_kelas = $d_kelas['nama_kelas'] ?? 'Tidak diketahui';
?>

<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    <h2 style="color: #333; margin-bottom: 5px;">📝 Kuis Online</h2>
    <p style="color: #777; margin-bottom: 20px;">Daftar kuis aktif untuk kelas <b><?= $nama_kelas ?></b>. Kerjakan sebelum batas waktu habis!</p>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd; text-align: left; background: #f8f9fa;">
                <th style="padding: 15px;">Mata Pelajaran</th>
                <th style="padding: 15px;">Judul Kuis</th>
                <th style="padding: 15px;">Batas Waktu</th>
                <th style="padding: 15px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // 2. KUNCI SINKRONISASI: Ambil kuis yang kelas_id-nya sesuai dengan pilihan siswa
            $q_kuis = mysqli_query($conn, "SELECT k.*, m.nama_mapel 
                                         FROM kuis k 
                                         JOIN mapel m ON k.mapel_id = m.id 
                                         WHERE k.kelas_id = '$id_kelas' 
                                         ORDER BY k.id DESC");

            if(mysqli_num_rows($q_kuis) > 0) {
                while($k = mysqli_fetch_assoc($q_kuis)) {
                    $deadline = date('d M Y, H:i', strtotime($k['deadline']));
            ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 15px; font-weight: 600; color: #7367f0;"><?= $k['nama_mapel'] ?></td>
                <td style="padding: 15px; color: #444; font-weight: 500;"><?= $k['judul'] ?></td>
                <td style="padding: 15px; color: #e74c3c; font-size: 14px;"><i class='bx bx-timer'></i> <?= $deadline ?></td>
                <td style="padding: 15px; text-align: center;">
                    <!-- Cek apakah pakai Link luar atau sistem Pilihan Ganda mandiri -->
                    <?php if(!empty($k['link_kuis'])) { ?>
                        <a href="<?= $k['link_kuis'] ?>" target="_blank" style="background: #3498db; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold;">Kerjakan (G-Form)</a>
                    <?php } else { ?>
                        <a href="dashboard.php?page=kerjakan_kuis&id_kuis=<?= $k['id'] ?>&id_kelas=<?= $id_kelas ?>" style="background: #7367f0; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold;">Mulai Kuis PG</a>
                    <?php } ?>
                </td>
            </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='4' style='text-align:center; padding:40px; color:#999; font-style: italic;'>Belum ada kuis yang tersedia untuk kelas ini.</td></tr>";
            } 
            ?>
        </tbody>
    </table>
</div>