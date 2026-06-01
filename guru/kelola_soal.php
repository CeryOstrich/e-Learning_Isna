<?php
$id_kuis = isset($_GET['id_kuis']) ? $_GET['id_kuis'] : 0;
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : 0;

if ($id_kuis == 0 || $id_kelas == 0) {
    echo "<div class='card'><h2 style='color:red;'>Akses Tidak Valid.</h2></div>";
    exit;
}

$q_kuis = mysqli_query($conn, "SELECT judul FROM kuis WHERE id = '$id_kuis'");
$d_kuis = mysqli_fetch_assoc($q_kuis);
$judul_kuis = $d_kuis ? $d_kuis['judul'] : 'Tidak diketahui';

$q_soal = mysqli_query($conn, "SELECT * FROM kuis_soal_v2 WHERE kuis_id = '$id_kuis' ORDER BY id ASC");
?>

<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #333; margin: 0;">📝 Kelola Soal: <?= $judul_kuis ?></h2>
        <a href="dashboard.php?page=quiz_guru&id_kelas=<?= $id_kelas ?>" style="background: #ccc; color: #333; padding: 8px 15px; border-radius: 8px; text-decoration: none; font-weight: bold;">Kembali</a>
    </div>

    <!-- Pilihan Tambah Soal -->
    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
        <button onclick="document.getElementById('form-pg').style.display='block'; document.getElementById('form-essay').style.display='none';" style="background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">+ Tambah Pilihan Ganda</button>
        <button onclick="document.getElementById('form-essay').style.display='block'; document.getElementById('form-pg').style.display='none';" style="background: #e67e22; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">+ Tambah Essay</button>
    </div>

    <!-- Form Pilihan Ganda -->
    <div id="form-pg" style="display: none; background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #ddd;">
        <h3 style="margin-bottom: 15px; color: #3498db;">Tambah Soal Pilihan Ganda</h3>
        <form action="proses/tambah_soal.php" method="POST">
            <input type="hidden" name="kuis_id" value="<?= $id_kuis ?>">
            <input type="hidden" name="kelas_id" value="<?= $id_kelas ?>">
            <input type="hidden" name="tipe" value="pg">
            
            <textarea name="pertanyaan" placeholder="Ketik pertanyaan di sini..." required style="width: 100%; height: 80px; padding: 10px; margin-bottom: 10px; border-radius: 8px; border: 1px solid #ccc;"></textarea>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                <input type="text" name="opsi_a" placeholder="Opsi A" required style="padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                <input type="text" name="opsi_b" placeholder="Opsi B" required style="padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                <input type="text" name="opsi_c" placeholder="Opsi C" required style="padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                <input type="text" name="opsi_d" placeholder="Opsi D" required style="padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
            </div>

            <div style="display: flex; gap: 15px; align-items: center; margin-bottom: 15px;">
                <label style="font-weight: bold; color: #555;">Kunci Jawaban:</label>
                <select name="kunci" required style="padding: 10px; border-radius: 8px; border: 1px solid #ccc; background: white;">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
                
                <label style="font-weight: bold; color: #555; margin-left: 20px;">Poin:</label>
                <input type="number" name="poin" value="10" required style="padding: 10px; border-radius: 8px; border: 1px solid #ccc; width: 80px;">
            </div>
            
            <button type="submit" style="background: #2ecc71; color: white; padding: 10px 25px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">Simpan Soal PG</button>
        </form>
    </div>

    <!-- Form Essay -->
    <div id="form-essay" style="display: none; background: #fff3e0; padding: 20px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #ffe0b2;">
        <h3 style="margin-bottom: 15px; color: #e67e22;">Tambah Soal Essay</h3>
        <form action="proses/tambah_soal.php" method="POST">
            <input type="hidden" name="kuis_id" value="<?= $id_kuis ?>">
            <input type="hidden" name="kelas_id" value="<?= $id_kelas ?>">
            <input type="hidden" name="tipe" value="essay">
            
            <textarea name="pertanyaan" placeholder="Ketik pertanyaan essay di sini..." required style="width: 100%; height: 80px; padding: 10px; margin-bottom: 10px; border-radius: 8px; border: 1px solid #ccc;"></textarea>
            
            <div style="display: flex; gap: 15px; align-items: center; margin-bottom: 15px;">
                <label style="font-weight: bold; color: #555;">Poin Maksimal:</label>
                <input type="number" name="poin" value="20" required style="padding: 10px; border-radius: 8px; border: 1px solid #ccc; width: 80px;">
            </div>
            
            <button type="submit" style="background: #2ecc71; color: white; padding: 10px 25px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">Simpan Soal Essay</button>
        </form>
    </div>

    <!-- Daftar Soal -->
    <h3 style="color: #333; margin-bottom: 15px;">Daftar Soal</h3>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd; background: #f8f9fa; text-align: left;">
                <th style="padding: 15px; width: 50px;">No</th>
                <th style="padding: 15px; width: 80px;">Tipe</th>
                <th style="padding: 15px;">Pertanyaan</th>
                <th style="padding: 15px; width: 80px;">Poin</th>
                <th style="padding: 15px; width: 100px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if(mysqli_num_rows($q_soal) > 0) {
                while($s = mysqli_fetch_assoc($q_soal)) {
            ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 15px; color: #777;"><?= $no++ ?></td>
                <td style="padding: 15px;">
                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; color: white; background: <?= $s['tipe'] == 'pg' ? '#3498db' : '#e67e22' ?>;">
                        <?= strtoupper($s['tipe']) ?>
                    </span>
                </td>
                <td style="padding: 15px; color: #444;">
                    <?= nl2br($s['pertanyaan']) ?>
                    <?php
                    // Jika PG, tampilkan opsi dan kunci jawaban
                    if($s['tipe'] == 'pg') {
                        $soal_id = $s['id'];
                        $q_opsi = mysqli_query($conn, "SELECT * FROM kuis_opsi_v2 WHERE soal_id = '$soal_id'");
                        echo "<ul style='margin-top: 10px; margin-bottom: 0; padding-left: 20px; font-size: 13px; color: #666;'>";
                        $huruf = 'A';
                        while($o = mysqli_fetch_assoc($q_opsi)) {
                            $is_kunci = $o['is_benar'] == 1 ? "<strong style='color:#2ecc71;'>(Kunci)</strong>" : "";
                            echo "<li>{$huruf}. {$o['teks_opsi']} $is_kunci</li>";
                            $huruf++;
                        }
                        echo "</ul>";
                    }
                    ?>
                </td>
                <td style="padding: 15px; font-weight: bold; color: #e74c3c;"><?= $s['poin_maksimal'] ?></td>
                <td style="padding: 15px; text-align: center;">
                    <a href="proses/hapus_soal.php?id=<?= $s['id'] ?>&id_kuis=<?= $id_kuis ?>&id_kelas=<?= $id_kelas ?>" onclick="return confirm('Hapus soal ini?')" style="background: #ea5455; color: white; padding: 6px 12px; border-radius: 6px; font-size: 12px; text-decoration: none; font-weight: bold;">Hapus</a>
                </td>
            </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='5' style='text-align: center; padding: 30px; color: #999; font-style: italic;'>Belum ada soal.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
