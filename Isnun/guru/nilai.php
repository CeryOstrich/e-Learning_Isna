<?php
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : 0;

if ($id_kelas == 0) {
    echo "<div class='card'><h2 style='color:red;'>Silakan pilih kelas terlebih dahulu dari Dashboard.</h2></div>";
    exit;
}

$q_kelas = mysqli_query($conn, "SELECT nama_kelas FROM kelas WHERE id = '$id_kelas'");
$d_kelas = mysqli_fetch_assoc($q_kelas);
$nama_kelas = $d_kelas ? $d_kelas['nama_kelas'] : 'Tidak diketahui';

// Ambil siswa yang terdaftar di kelas ini
$query_siswa = mysqli_query($conn, "SELECT * FROM users WHERE id_kelas = '$id_kelas' AND role = 'siswa' ORDER BY nama ASC");
?>

<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #333; margin: 0;">📊 Rekap Nilai Siswa</h2>
        <div style="padding: 8px 15px; background: #e1fcef; border-radius: 8px; border: 1px solid #28c76f; font-weight: bold; color: #28c76f;">
            Kelas: <?= $nama_kelas ?>
        </div>
    </div>
    
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
                        // Note: Kolom angka 0 di bawah ini bisa kamu ganti dengan query asli 
                        // ke tabel nilai_tugas dan nilai_kuis nantinya.
                ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px; text-align: center; color: #888;"><?= $no++; ?></td>
                    <td style="padding: 15px; font-weight: 600; color: #444;"><?= $s['nama']; ?></td>
                    <td style="padding: 15px; text-align: center; font-weight: bold; color: #3498db;">0</td>
                    <td style="padding: 15px; text-align: center; font-weight: bold; color: #e74c3c;">0.00</td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="dashboard.php?page=input_nilai&id_siswa=<?= $s['id']; ?>&id_kelas=<?= $id_kelas ?>" style="background: #7367f0; color: white; padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold;">Detail / Input Nilai</a>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; padding: 40px; color: #999; font-style: italic;'>Belum ada siswa yang dimasukkan ke kelas ini oleh Admin.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>