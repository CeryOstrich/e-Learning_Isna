<?php
/**
 * views/guru/koreksi_kuis.php
 */
Auth::requireRole('guru');
$db = Database::getInstance();

$jm_id = $_GET['jm_id'] ?? $_POST['jm_id'] ?? 0;
$item_id = $_GET['id'] ?? $_POST['id'] ?? 0;
$siswa_id = $_GET['siswa_id'] ?? $_POST['siswa_id'] ?? 0;
$action = $_GET['action'] ?? '';

$item = $db->queryOne("SELECT * FROM modul_item WHERE id=?", 'i', [$item_id]);

// === HANDLER SIMPAN NILAI ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'save_koreksi') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $nilai_essay = $_POST['nilai_essay'] ?? [];
    
    // Update poin di kuis_jawaban
    foreach ($nilai_essay as $soal_id => $poin) {
        $poin = (float) $poin;
        $db->execute("UPDATE kuis_jawaban SET poin_didapat=? WHERE item_id=? AND siswa_id=? AND soal_id=?", 'diii', [$poin, $item_id, $siswa_id, $soal_id]);
    }
    
    // Hitung ulang total
    $q_total = $db->queryOne("SELECT SUM(poin_didapat) as total FROM kuis_jawaban WHERE item_id=? AND siswa_id=?", 'ii', [$item_id, $siswa_id]);
    $total_baru = $q_total['total'] ?? 0;
    
    $db->execute("UPDATE kuis_hasil SET skor=? WHERE item_id=? AND siswa_id=?", 'dii', [$total_baru, $item_id, $siswa_id]);
    
    setFlash('success', "Nilai berhasil disimpan. Total skor siswa: " . round($total_baru));
    header("Location: " . BASE_URL . "/index.php?page=g_koreksi_kuis&id=$item_id&jm_id=$jm_id");
    exit;
}

$pageTitle = 'Koreksi Kuis';
ob_start();
?>

<div class="mb-4">
    <a href="?page=g_course&jm_id=<?= $jm_id ?>" class="btn btn-outline btn-sm"><i class='bx bx-arrow-back'></i> Kembali ke Silabus</a>
</div>

<div class="card mb-4">
    <div class="card-header"><span class="card-title">Koreksi: <?= e($item['judul']) ?></span></div>
    
    <?php if ($siswa_id): 
        $siswa = $db->queryOne("SELECT nama FROM users WHERE id=?", 'i', [$siswa_id]);
        $soalList = $db->queryAll("SELECT * FROM kuis_soal WHERE item_id=? ORDER BY urutan ASC", 'i', [$item_id]);
    ?>
        <div style="margin-bottom:20px; font-size:1.1rem;">
            Siswa: <strong><?= e($siswa['nama']) ?></strong>
            <a href="?page=g_koreksi_kuis&id=<?= $item_id ?>&jm_id=<?= $jm_id ?>" style="float:right; font-size:0.9rem;" class="btn btn-outline btn-sm">Kembali ke Daftar Siswa</a>
        </div>
        
        <form action="?page=g_koreksi_kuis&action=save_koreksi" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="jm_id" value="<?= $jm_id ?>">
            <input type="hidden" name="id" value="<?= $item_id ?>">
            <input type="hidden" name="siswa_id" value="<?= $siswa_id ?>">
            
            <?php 
            $total_pg = 0;
            foreach ($soalList as $idx => $s): 
                $jawab = $db->queryOne("SELECT * FROM kuis_jawaban WHERE item_id=? AND siswa_id=? AND soal_id=?", 'iii', [$item_id, $siswa_id, $s['id']]);
                
                $border_color = "#e2e8f0";
                if ($s['tipe'] === 'pg' && $jawab) {
                    $border_color = $jawab['is_benar'] ? "#bbf7d0" : "#fecaca";
                }
            ?>
            <div style="border:1px solid <?= $border_color ?>; border-radius:8px; padding:15px; margin-bottom:15px; background:var(--bg);">
                <div style="font-weight:600; margin-bottom:10px;">
                    <?= $idx+1 ?>. <?= nl2br(e($s['pertanyaan'])) ?>
                </div>
                
                <?php if ($s['tipe'] === 'pg'): 
                    $poin_didapat = $jawab ? $jawab['poin_didapat'] : 0;
                    $total_pg += $poin_didapat;
                    $opsi = $db->queryAll("SELECT * FROM kuis_opsi WHERE soal_id=?", 'i', [$s['id']]);
                ?>
                    <ul style="list-style:none; padding:0; margin:0 0 10px 0;">
                        <?php foreach($opsi as $o): 
                            $is_selected = ($jawab && $jawab['opsi_id'] == $o['id']);
                            $bg = "transparent";
                            $icon = "<i class='bx bx-circle'></i>";
                            if ($o['is_benar']) {
                                $bg = "#dcfce7";
                                $icon = "<i class='bx bxs-check-circle text-success'></i> Kunci:";
                            } elseif ($is_selected) {
                                $bg = "#fee2e2";
                                $icon = "<i class='bx bxs-x-circle text-danger'></i> Siswa (Salah):";
                            }
                            if ($is_selected && $o['is_benar']) {
                                $bg = "#bbf7d0";
                                $icon = "<i class='bx bxs-check-circle text-success'></i> Siswa (Benar):";
                            }
                        ?>
                        <li style="padding:6px 10px; margin-bottom:4px; border-radius:4px; background:<?= $bg ?>;">
                            <?= $icon ?> <?= e($o['teks']) ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <div style="color:var(--primary); font-weight:bold;">Poin Didapat: <?= $poin_didapat ?> / <?= $s['poin_maksimal'] ?></div>
                
                <?php else: 
                    $poin_essay = $jawab ? $jawab['poin_didapat'] : 0;
                ?>
                    <div style="background:#fff; border:1px solid #cbd5e1; padding:12px; border-radius:6px; margin-bottom:10px; font-style:italic; color:#475569;">
                        Jawaban Siswa:<br>
                        "<?= nl2br(e($jawab['jawaban_teks'] ?? 'Tidak menjawab.')) ?>"
                    </div>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <label style="font-weight:bold; color:#ea580c;">Beri Nilai Essay (Maks <?= $s['poin_maksimal'] ?>):</label>
                        <input type="number" name="nilai_essay[<?= $s['id'] ?>]" class="form-control" style="width:100px;" value="<?= $poin_essay ?>" max="<?= $s['poin_maksimal'] ?>" min="0" step="0.5" required>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            
            <div style="display:flex; justify-content:space-between; align-items:center; border-top:2px solid #e2e8f0; padding-top:20px; margin-top:20px;">
                <div style="font-size:1.1rem; font-weight:bold; color:#475569;">Total Poin PG: <span class="text-primary"><?= $total_pg ?></span></div>
                <button type="submit" class="btn btn-success btn-lg"><i class='bx bx-save'></i> Simpan Semua Nilai</button>
            </div>
        </form>

    <?php else: 
        $hasilList = $db->queryAll(
            "SELECT kh.*, u.nama, u.nis_nip 
             FROM kuis_hasil kh 
             JOIN users u ON u.id = kh.siswa_id 
             WHERE kh.item_id=? 
             ORDER BY kh.diselesaikan_pada DESC", 
            'i', [$item_id]
        );
    ?>
        <?php if(empty($hasilList)): ?>
            <div class="alert alert-info show">Belum ada siswa yang mengerjakan kuis ini.</div>
        <?php else: ?>
            <table style="width:100%; border-collapse:collapse; margin-top:15px;">
                <thead>
                    <tr style="background:var(--bg); text-align:left;">
                        <th style="padding:12px; border-bottom:2px solid var(--border);">Nama Siswa</th>
                        <th style="padding:12px; border-bottom:2px solid var(--border);">Waktu Pengumpulan</th>
                        <th style="padding:12px; border-bottom:2px solid var(--border);">Total Skor</th>
                        <th style="padding:12px; border-bottom:2px solid var(--border);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($hasilList as $h): ?>
                    <tr>
                        <td style="padding:12px; border-bottom:1px solid var(--border); font-weight:600;"><?= e($h['nama']) ?></td>
                        <td style="padding:12px; border-bottom:1px solid var(--border); font-size:0.9rem; color:#666;"><?= date('d M Y, H:i', strtotime($h['diselesaikan_pada'])) ?></td>
                        <td style="padding:12px; border-bottom:1px solid var(--border); font-weight:bold; color:var(--primary); font-size:1.1rem;"><?= round($h['skor']) ?></td>
                        <td style="padding:12px; border-bottom:1px solid var(--border);">
                            <a href="?page=g_koreksi_kuis&id=<?= $item_id ?>&jm_id=<?= $jm_id ?>&siswa_id=<?= $h['siswa_id'] ?>" class="btn btn-primary btn-sm">
                                <i class='bx bx-check-shield'></i> Periksa / Koreksi Essay
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
