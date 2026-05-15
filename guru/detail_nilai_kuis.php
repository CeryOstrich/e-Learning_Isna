<?php
$id_kuis = isset($_GET['id_kuis']) ? $_GET['id_kuis'] : 0;
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : 0;
$id_siswa = isset($_GET['id_siswa']) ? $_GET['id_siswa'] : 0;

if ($id_kuis == 0 || $id_kelas == 0 || $id_siswa == 0) {
    echo "<div class='card'><h2 style='color:red;'>Akses Tidak Valid.</h2></div>";
    exit;
}

$q_siswa = mysqli_query($conn, "SELECT nama FROM users WHERE id = '$id_siswa'");
$nama_siswa = mysqli_fetch_assoc($q_siswa)['nama'];

$q_kuis = mysqli_query($conn, "SELECT judul FROM kuis WHERE id = '$id_kuis'");
$judul_kuis = mysqli_fetch_assoc($q_kuis)['judul'];

$q_soal = mysqli_query($conn, "SELECT * FROM kuis_soal_v2 WHERE kuis_id = '$id_kuis' ORDER BY urutan ASC, id ASC");
?>

<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #333; margin: 0;">✍️ Koreksi Kuis: <?= $judul_kuis ?></h2>
        <a href="dashboard.php?page=nilai_guru&id_kuis=<?= $id_kuis ?>&id_kelas=<?= $id_kelas ?>" style="background: #ccc; color: #333; padding: 8px 15px; border-radius: 8px; text-decoration: none; font-weight: bold;">Kembali</a>
    </div>
    
    <p style="font-size: 16px; color: #555;">Siswa: <strong style="color: #2ecc71;"><?= $nama_siswa ?></strong></p>

    <form action="proses/simpan_nilai_essay.php" method="POST">
        <input type="hidden" name="kuis_id" value="<?= $id_kuis ?>">
        <input type="hidden" name="siswa_id" value="<?= $id_siswa ?>">
        <input type="hidden" name="kelas_id" value="<?= $id_kelas ?>">

        <?php
        $no = 1;
        $total_pg_didapat = 0;
        
        while($s = mysqli_fetch_assoc($q_soal)) {
            $soal_id = $s['id'];
            
            // Ambil jawaban siswa
            $q_jawab = mysqli_query($conn, "SELECT * FROM kuis_jawaban_siswa WHERE soal_id = '$soal_id' AND siswa_id = '$id_siswa'");
            $d_jawab = mysqli_fetch_assoc($q_jawab);
        ?>
        <div style="margin-bottom: 20px; padding: 15px; border: 1px solid #eee; border-radius: 8px; background: #f9f9f9;">
            <p style="font-weight: bold; margin-bottom: 10px; color: #333;">
                <?= $no++ ?>. <?= nl2br($s['pertanyaan']) ?>
            </p>
            
            <?php if($s['tipe'] == 'pg') { 
                // Hitung dan tampilkan jawaban PG
                $opsi_siswa = $d_jawab ? $d_jawab['opsi_id'] : null;
                $poin_didapat = $d_jawab ? $d_jawab['poin_didapat'] : 0;
                $total_pg_didapat += $poin_didapat;
                
                $q_opsi = mysqli_query($conn, "SELECT * FROM kuis_opsi_v2 WHERE soal_id = '$soal_id'");
                echo "<ul style='list-style: none; padding-left: 0;'>";
                while($o = mysqli_fetch_assoc($q_opsi)) {
                    $color = "#555";
                    $bold = "normal";
                    $mark = "";
                    
                    if($o['is_benar'] == 1) {
                        $color = "#27ae60"; // Hijau untuk kunci jawaban
                        $bold = "bold";
                        $mark = "✓ (Kunci Jawaban)";
                    }
                    if($o['id'] == $opsi_siswa) {
                        if($o['is_benar'] == 1) {
                            $mark .= " ➔ Jawaban Siswa (Benar)";
                        } else {
                            $color = "#e74c3c"; // Merah jika salah
                            $bold = "bold";
                            $mark = " ➔ Jawaban Siswa (Salah)";
                        }
                    }
                    echo "<li style='color: $color; font-weight: $bold; margin-bottom: 5px; border-radius: 5px; padding: 5px; border: 1px solid #ddd; background: #fff;'>{$o['teks_opsi']} $mark</li>";
                }
                echo "</ul>";
                echo "<p style='color: #2980b9; font-weight: bold;'>Poin Didapat: $poin_didapat / {$s['poin_maksimal']}</p>";
                
            } else { 
                // Form penilaian essay
                $teks_siswa = $d_jawab ? $d_jawab['jawaban_teks'] : "Tidak menjawab.";
                $poin_essay = $d_jawab ? $d_jawab['poin_didapat'] : 0;
            ?>
                <div style="background: #fff; padding: 10px; border-radius: 5px; border: 1px solid #ddd; color: #444; margin-bottom: 10px; font-style: italic;">
                    "<?= nl2br(htmlspecialchars($teks_siswa)) ?>"
                </div>
                
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label style="font-weight: bold; color: #e67e22;">Beri Nilai (Maks <?= $s['poin_maksimal'] ?>):</label>
                    <input type="number" name="nilai_essay[<?= $soal_id ?>]" value="<?= $poin_essay ?>" max="<?= $s['poin_maksimal'] ?>" min="0" required style="padding: 8px; border-radius: 5px; border: 1px solid #ccc; width: 80px;">
                </div>
            <?php } ?>
        </div>
        <?php } ?>

        <div style="text-align: right; margin-top: 20px; border-top: 2px solid #eee; padding-top: 20px;">
            <p style="font-size: 16px; font-weight: bold; color: #555;">Poin PG Sementara: <span style="color: #2980b9;"><?= $total_pg_didapat ?></span></p>
            <button type="submit" style="background: #2ecc71; color: white; padding: 12px 25px; border: none; border-radius: 8px; font-weight: bold; font-size: 15px; cursor: pointer;">💾 Simpan & Hitung Total Nilai</button>
        </div>
    </form>
</div>
