<?php
/**
 * views/guru/builder_kuis.php
 */
Auth::requireRole('guru');
$db = Database::getInstance();

$jm_id = $_GET['jm_id'] ?? $_POST['jm_id'] ?? 0;
$modul_id = $_POST['modul_id'] ?? $_GET['modul_id'] ?? 0;
$id = $_GET['id'] ?? $_POST['id'] ?? 0;

$action = $_GET['action'] ?? '';

// === HANDLER ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if ($action === 'save_kuis') {
        $judul = trim($_POST['judul'] ?? '');
        $durasi = (int)($_POST['durasi_menit'] ?? 0);
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        
        if ($id) {
            $db->execute("UPDATE modul_item SET judul=?, durasi_menit=?, isi_teks=? WHERE id=?", 'sisi', [$judul, $durasi, $deskripsi, $id]);
            setFlash('success', 'Pengaturan kuis diupdate.');
        } else {
            $urutan = $db->queryOne("SELECT MAX(urutan) as m FROM modul_item WHERE modul_id=?", 'i', [$modul_id])['m'] ?? 0;
            $db->execute(
                "INSERT INTO modul_item (modul_id, tipe, judul, isi_teks, durasi_menit, urutan) VALUES (?, 'kuis', ?, ?, ?, ?)",
                'issii', [$modul_id, $judul, $deskripsi, $durasi, $urutan+1]
            );
            $id = $db->getConn()->insert_id;
            setFlash('success', 'Kuis berhasil dibuat. Silakan tambah soal.');
        }
        header("Location: " . BASE_URL . "/index.php?page=g_builder_kuis&id=$id&jm_id=$jm_id");
        exit;
    }
    elseif ($action === 'add_soal') {
        $tipe = $_POST['tipe'] ?? 'pg';
        $pertanyaan = trim($_POST['pertanyaan'] ?? '');
        $poin_maksimal = (int)($_POST['poin_maksimal'] ?? 10);
        
        $opsi = $_POST['opsi'] ?? [];
        $benar = (int)($_POST['benar'] ?? 0);
        
        if ($pertanyaan) {
            $urutan = $db->queryOne("SELECT MAX(urutan) as m FROM kuis_soal WHERE item_id=?", 'i', [$id])['m'] ?? 0;
            $db->execute("INSERT INTO kuis_soal (item_id, pertanyaan, urutan, tipe, poin_maksimal) VALUES (?, ?, ?, ?, ?)", 'isisi', [$id, $pertanyaan, $urutan+1, $tipe, $poin_maksimal]);
            $soal_id = $db->getConn()->insert_id;
            
            if ($tipe === 'pg') {
                foreach ($opsi as $idx => $teks) {
                    $is_benar = ($idx == $benar) ? 1 : 0;
                    $db->execute("INSERT INTO kuis_opsi (soal_id, teks, is_benar) VALUES (?, ?, ?)", 'isi', [$soal_id, trim($teks), $is_benar]);
                }
            }
            setFlash('success', 'Soal berhasil ditambahkan.');
        }
        header("Location: " . BASE_URL . "/index.php?page=g_builder_kuis&id=$id&jm_id=$jm_id");
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'delete_soal') {
    $soal_id = $_GET['soal_id'] ?? 0;
    // Verifikasi kepemilikan soal
    $cek = $db->queryOne("SELECT ks.id FROM kuis_soal ks JOIN modul_item mi ON mi.id=ks.item_id JOIN modul m ON m.id=mi.modul_id JOIN jadwal_mengajar jm ON jm.id=m.jadwal_mengajar_id WHERE ks.id=? AND jm.guru_id=?", 'ii', [$soal_id, $_SESSION['user_id']]);
    if ($cek) {
        $db->execute("DELETE FROM kuis_soal WHERE id=?", 'i', [$soal_id]);
        setFlash('success', 'Soal berhasil dihapus.');
    }
    header("Location: " . BASE_URL . "/index.php?page=g_builder_kuis&id=$id&jm_id=$jm_id");
    exit;
}
// === END HANDLER ===

$item = null;
$soalList = [];
if ($id) {
    $item = $db->queryOne("SELECT * FROM modul_item WHERE id=?", 'i', [$id]);
    $soalList = $db->queryAll("SELECT * FROM kuis_soal WHERE item_id=? ORDER BY urutan ASC", 'i', [$id]);
    
    foreach ($soalList as &$s) {
        $s['opsi'] = $db->queryAll("SELECT * FROM kuis_opsi WHERE soal_id=? ORDER BY id ASC", 'i', [$s['id']]);
    }
}

$pageTitle = $id ? 'Kelola Kuis' : 'Buat Kuis Baru';
ob_start();
?>

<div class="mb-4">
    <a href="?page=g_course&jm_id=<?= $jm_id ?>" class="btn btn-outline btn-sm"><i class='bx bx-arrow-back'></i> Kembali ke Silabus</a>
</div>

<div style="display:flex; gap:20px; align-items:flex-start; flex-wrap:wrap;">
    
    <!-- PENGATURAN KUIS -->
    <div class="card" style="flex:1; min-width:300px;">
        <div class="card-header"><span class="card-title">⚙️ Pengaturan Kuis</span></div>
        <form action="?page=g_builder_kuis&action=save_kuis&id=<?= $id ?>&jm_id=<?= $jm_id ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="modul_id" value="<?= $modul_id ?>">
            
            <div class="form-group">
                <label>Judul Kuis</label>
                <input type="text" name="judul" class="form-control" value="<?= e($item['judul'] ?? '') ?>" placeholder="Misal: Kuis Bab 1" required>
            </div>
            <div class="form-group">
                <label>Durasi (Menit)</label>
                <input type="number" name="durasi_menit" class="form-control" value="<?= e($item['durasi_menit'] ?? 15) ?>" required>
            </div>
            <div class="form-group">
                <label>Instruksi (Opsional)</label>
                <textarea name="deskripsi" class="form-control" rows="3"><?= e($item['isi_teks'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Simpan Pengaturan</button>
        </form>
    </div>

    <!-- DAFTAR SOAL -->
    <?php if($id): ?>
    <div class="card" style="flex:2; min-width:400px; border-top: 4px solid var(--success);">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <span class="card-title">📝 Daftar Soal (<?= count($soalList) ?>)</span>
            <button class="btn btn-success btn-sm" onclick="showModal('addSoalModal')">+ Tambah Soal</button>
        </div>
        
        <?php if(empty($soalList)): ?>
            <div class="alert alert-info show">Belum ada soal untuk kuis ini.</div>
        <?php else: ?>
            <div style="display:flex; flex-direction:column; gap:15px;">
                <?php foreach($soalList as $idx => $soal): ?>
                <div style="padding:15px; border:1px solid var(--border); border-radius:8px; background:var(--bg);">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px;">
                        <div>
                            <strong style="font-size:1.1rem; line-height:1.4;"><?= $idx+1 ?>. <?= nl2br(e($soal['pertanyaan'])) ?></strong>
                            <div style="font-size:0.8rem; color:#666; margin-top:4px;">
                                Tipe: <span style="text-transform:uppercase; font-weight:bold; color:var(--primary);"><?= $soal['tipe'] ?></span> | Poin Maks: <?= $soal['poin_maksimal'] ?>
                            </div>
                        </div>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= BASE_URL ?>/index.php?page=g_builder_kuis&action=delete_soal&id=<?= $id ?>&jm_id=<?= $jm_id ?>&soal_id=<?= $soal['id'] ?>')"><i class='bx bx-trash'></i></button>
                    </div>
                    
                    <?php if($soal['tipe'] === 'pg'): ?>
                    <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:5px;">
                        <?php foreach($soal['opsi'] as $o): ?>
                        <li style="padding:8px 12px; border-radius:4px; <?= $o['is_benar'] ? 'background:#d4edda; color:#155724; border:1px solid #c3e6cb;' : 'background:var(--card); border:1px solid var(--border);' ?>">
                            <?= $o['is_benar'] ? '<i class="bx bx-check-circle"></i> ' : '<i class="bx bx-circle"></i> ' ?>
                            <?= e($o['teks']) ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                        <div style="padding:8px 12px; border-radius:4px; background:#f8f9fa; border:1px solid #ddd; font-style:italic; color:#666;">
                            Kolom Jawaban Essay untuk Siswa
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>

<!-- Modal Tambah Soal -->
<?php if($id): ?>
<div id="addSoalModal" class="modal">
    <div class="modal-content" style="max-width:600px;">
        <h3 class="mb-4">Tambah Soal Baru</h3>
        <form action="?page=g_builder_kuis&action=add_soal&id=<?= $id ?>&jm_id=<?= $jm_id ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            
            <div class="form-group">
                <label>Tipe Soal</label>
                <select name="tipe" class="form-control" onchange="toggleTipeSoal(this.value)">
                    <option value="pg">Pilihan Ganda</option>
                    <option value="essay">Essay</option>
                </select>
            </div>

            <div class="form-group">
                <label>Pertanyaan</label>
                <textarea name="pertanyaan" class="form-control" rows="3" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Poin Maksimal</label>
                <input type="number" name="poin_maksimal" class="form-control" value="10" required>
            </div>

            <div id="pg-options">
                <label class="mb-2" style="display:block;">Pilihan Jawaban (Pilih radio button untuk jawaban benar)</label>
                <?php for($i=0; $i<4; $i++): ?>
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                    <input type="radio" name="benar" value="<?= $i ?>" <?= $i==0?'checked':'' ?> style="transform:scale(1.5);">
                    <input type="text" name="opsi[<?= $i ?>]" class="form-control" placeholder="Pilihan <?= chr(65+$i) ?>" style="flex:1;">
                </div>
                <?php endfor; ?>
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                <button type="button" class="btn btn-outline" onclick="hideModal('addSoalModal')">Batal</button>
                <button type="submit" class="btn btn-success">Simpan Soal</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleTipeSoal(tipe) {
    const pgOptions = document.getElementById('pg-options');
    if (tipe === 'essay') {
        pgOptions.style.display = 'none';
        pgOptions.querySelectorAll('input[type="text"]').forEach(el => el.removeAttribute('required'));
    } else {
        pgOptions.style.display = 'block';
        pgOptions.querySelectorAll('input[type="text"]').forEach(el => el.setAttribute('required', 'required'));
    }
}
// Init form requirements
toggleTipeSoal('pg');
</script>
<?php endif; ?>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
