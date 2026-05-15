<?php
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : 0;
$id_kuis = isset($_GET['id_kuis']) ? $_GET['id_kuis'] : 0;

if ($id_kelas == 0) {
    echo "<div class='card'><h2 style='color:red;'>Silakan pilih kelas terlebih dahulu dari Dashboard.</h2></div>";
    exit;
}

$q_kelas = mysqli_query($conn, "SELECT nama_kelas FROM kelas WHERE id = '$id_kelas'");
$d_kelas = mysqli_fetch_assoc($q_kelas);
$nama_kelas = $d_kelas ? $d_kelas['nama_kelas'] : 'Tidak diketahui';

$query_siswa = mysqli_query($conn, "SELECT id, nama FROM users WHERE id_kelas = '$id_kelas' AND role = 'siswa' ORDER BY nama ASC");
?>

<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #333; margin: 0;">📊 Rekap Nilai Siswa</h2>
        <div style="padding: 8px 15px; background: #e1fcef; border-radius: 8px; border: 1px solid #28c76f; font-weight: bold; color: #28c76f;">
            Kelas: <?= $nama_kelas ?>
        </div>
    </div>
    
    <?php if($id_kuis > 0): 
        $q_kuis = mysqli_query($conn, "SELECT judul FROM kuis WHERE id = '$id_kuis'");
        $d_kuis = mysqli_fetch_assoc($q_kuis);
    ?>
    <p style="color: #777; margin-bottom: 20px;">Menampilkan nilai untuk kuis: <b><?= $d_kuis['judul'] ?></b></p>
    <a href="dashboard.php?page=quiz_guru&id_kelas=<?= $id_kelas ?>" style="display: inline-block; background: #ccc; color: #333; padding: 8px 15px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-bottom: 15px;">Kembali ke Daftar Kuis</a>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: linear-gradient(45deg, #2ecc71, #28c76f); color: white; text-align: left;">
                    <th style="padding: 15px; border-top-left-radius: 10px; width: 50px; text-align: center;">No</th>
                    <th style="padding: 15px;">Nama Siswa</th>
                    <th style="padding: 15px; text-align: center;">Status</th>
                    <th style="padding: 15px; text-align: center;">Total Nilai</th>
                    <th style="padding: 15px; border-top-right-radius: 10px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if (mysqli_num_rows($query_siswa) > 0) {
                    while($s = mysqli_fetch_assoc($query_siswa)) { 
                        $siswa_id = $s['id'];
                        $q_nilai = mysqli_query($conn, "SELECT * FROM kuis_nilai WHERE kuis_id = '$id_kuis' AND siswa_id = '$siswa_id'");
                        $d_nilai = mysqli_fetch_assoc($q_nilai);
                ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px; text-align: center; color: #888;"><?= $no++; ?></td>
                    <td style="padding: 15px; font-weight: 600; color: #444;"><?= $s['nama']; ?></td>
                    <td style="padding: 15px; text-align: center;">
                        <?= $d_nilai ? "<span style='color: #2ecc71; font-weight: bold;'>Selesai</span>" : "<span style='color: #e74c3c;'>Belum</span>" ?>
                    </td>
                    <td style="padding: 15px; text-align: center; font-weight: bold; color: #3498db;">
                        <?= $d_nilai ? $d_nilai['total_nilai'] : "0" ?>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <?php if($d_nilai): ?>
                            <a href="dashboard.php?page=detail_nilai_kuis&id_kuis=<?= $id_kuis ?>&id_siswa=<?= $siswa_id ?>&id_kelas=<?= $id_kelas ?>" style="background: #7367f0; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold;">Koreksi Essay</a>
                        <?php else: ?>
                            <span style="color: #ccc;">Belum ada data</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; padding: 40px; color: #999; font-style: italic;'>Belum ada siswa yang dimasukkan ke kelas ini.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php else: ?>
    <p style="color: #777; margin-bottom: 20px;">Tabel di bawah ini menampilkan daftar siswa di kelas <b><?= $nama_kelas ?></b>. Anda dapat memasukkan atau melihat nilai kuis/tugas mereka di sini.</p>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: linear-gradient(45deg, #2ecc71, #28c76f); color: white; text-align: left;">
                    <th style="padding: 15px; border-top-left-radius: 10px; width: 50px; text-align: center;">No</th>
                    <th style="padding: 15px;">Nama Siswa</th>
                    <th style="padding: 15px; text-align: center;">Total Tugas Selesai</th>
                    <th style="padding: 15px; text-align: center;">Rata-Rata Nilai Kuis</th>
                    <th style="padding: 15px; border-top-right-radius: 10px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if (mysqli_num_rows($query_siswa) > 0) {
                    while($s = mysqli_fetch_assoc($query_siswa)) { 
                        $siswa_id = $s['id'];
                        // Hitung rata-rata kuis
                        $q_avg = mysqli_query($conn, "SELECT AVG(total_nilai) as avg_nilai FROM kuis_nilai kn JOIN kuis k ON kn.kuis_id = k.id WHERE kn.siswa_id = '$siswa_id' AND k.kelas_id = '$id_kelas'");
                        $d_avg = mysqli_fetch_assoc($q_avg);
                        $rata = $d_avg['avg_nilai'] ? number_format($d_avg['avg_nilai'], 2) : "0.00";
                ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px; text-align: center; color: #888;"><?= $no++; ?></td>
                    <td style="padding: 15px; font-weight: 600; color: #444;"><?= $s['nama']; ?></td>
                    <td style="padding: 15px; text-align: center; font-weight: bold; color: #3498db;">0</td>
                    <td style="padding: 15px; text-align: center; font-weight: bold; color: #e74c3c;"><?= $rata ?></td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="dashboard.php?page=input_nilai&id_siswa=<?= $siswa_id ?>&id_kelas=<?= $id_kelas ?>" style="background: #7367f0; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold;">Detail / Input Nilai</a>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; padding: 40px; color: #999; font-style: italic;'>Belum ada siswa yang dimasukkan ke kelas ini.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>