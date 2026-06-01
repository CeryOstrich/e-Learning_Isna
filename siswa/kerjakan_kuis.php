<?php
$id_kuis = isset($_GET['id_kuis']) ? $_GET['id_kuis'] : 0;
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : 0;
$siswa_id = $_SESSION['id'];

if ($id_kuis == 0 || $id_kelas == 0) {
    echo "<div class='card'><h2 style='color:red;'>Akses Tidak Valid.</h2></div>";
    exit;
}

// Cek apakah siswa sudah mengerjakan
$q_cek = mysqli_query($conn, "SELECT id FROM kuis_nilai WHERE kuis_id = '$id_kuis' AND siswa_id = '$siswa_id'");
if (mysqli_num_rows($q_cek) > 0) {
    echo "<div class='card' style='padding: 30px; text-align: center;'>
            <h2 style='color: #2ecc71;'>Anda sudah mengerjakan kuis ini! 🎉</h2>
            <p style='color: #777;'>Silakan periksa nilai Anda di menu Nilai.</p>
            <a href='dashboard.php?page=quiz_siswa&id_kelas=$id_kelas' style='display: inline-block; background: #3498db; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-top: 15px;'>Kembali ke Daftar Kuis</a>
          </div>";
    exit;
}

$q_kuis = mysqli_query($conn, "SELECT judul, deskripsi, deadline FROM kuis WHERE id = '$id_kuis'");
$d_kuis = mysqli_fetch_assoc($q_kuis);

$q_soal = mysqli_query($conn, "SELECT * FROM kuis_soal_v2 WHERE kuis_id = '$id_kuis' ORDER BY urutan ASC, id ASC");
?>

<div class="card" style="padding: 25px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px;">
    <div style="border-bottom: 2px solid #f1f2f6; padding-bottom: 15px; margin-bottom: 20px;">
        <h2 style="color: #333; margin: 0 0 10px 0;">📝 <?= $d_kuis['judul'] ?></h2>
        <p style="color: #666; margin: 0; font-size: 14px; background: #fdf2f2; padding: 10px; border-radius: 8px; border: 1px solid #fbd5d5;"><?= nl2br($d_kuis['deskripsi']) ?></p>
        <p style="color: #e74c3c; margin-top: 10px; font-weight: bold;"><i class='bx bx-time'></i> Batas Waktu: <?= date('d M Y, H:i', strtotime($d_kuis['deadline'])) ?></p>
    </div>

    <form action="proses/submit_jawaban_kuis.php" method="POST" id="form-kuis">
        <input type="hidden" name="kuis_id" value="<?= $id_kuis ?>">
        <input type="hidden" name="kelas_id" value="<?= $id_kelas ?>">
        
        <?php
        $no = 1;
        if(mysqli_num_rows($q_soal) > 0) {
            while($s = mysqli_fetch_assoc($q_soal)) {
                $soal_id = $s['id'];
        ?>
        <div style="margin-bottom: 25px; padding: 20px; background: #f8f9fa; border-radius: 10px; border: 1px solid #e2e8f0;">
            <p style="font-weight: 600; color: #333; margin-bottom: 15px; font-size: 15px;">
                <?= $no++ ?>. <?= nl2br($s['pertanyaan']) ?>
                <span style="float: right; color: #e67e22; font-size: 13px;">(Poin Maks: <?= $s['poin_maksimal'] ?>)</span>
            </p>
            
            <?php if($s['tipe'] == 'pg') { 
                $q_opsi = mysqli_query($conn, "SELECT * FROM kuis_opsi_v2 WHERE soal_id = '$soal_id'");
                while($o = mysqli_fetch_assoc($q_opsi)) {
            ?>
                <label style="display: block; margin-bottom: 10px; cursor: pointer; padding: 10px; border: 1px solid #ccc; border-radius: 8px; background: white; transition: 0.2s;">
                    <input type="radio" name="jawaban[<?= $soal_id ?>]" value="<?= $o['id'] ?>" required style="margin-right: 10px; width: auto;">
                    <?= $o['teks_opsi'] ?>
                </label>
            <?php 
                } 
            } else { 
            ?>
                <textarea name="jawaban_essay[<?= $soal_id ?>]" required placeholder="Ketik jawaban essay Anda di sini..." style="width: 100%; height: 120px; padding: 15px; border-radius: 8px; border: 1px solid #ccc; font-family: inherit; resize: vertical; box-sizing: border-box;"></textarea>
            <?php } ?>
        </div>
        <?php 
            } 
        } else {
            echo "<p style='text-align:center; color:#999;'>Soal belum ditambahkan oleh guru.</p>";
        }
        ?>

        <?php if(mysqli_num_rows($q_soal) > 0) { ?>
        <div style="text-align: right; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengumpulkan kuis ini? Pastikan semua soal telah terjawab.')" style="background: #2ecc71; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-weight: bold; font-size: 16px; cursor: pointer; box-shadow: 0 4px 6px rgba(46, 204, 113, 0.2);">
                📤 Kumpulkan Kuis
            </button>
        </div>
        <?php } ?>
    </form>
</div>
