<?php
/**
 * views/guru/soal.php — Bank Soal per Ujian (Pilgan + Esai)
 */
Auth::requireRole('guru');
$db    = Database::getInstance();
$uid   = $_SESSION['user_id'];
$ujian_id = (int)($_GET['ujian_id'] ?? 0);

// Verifikasi ujian milik guru ini
$ujian = $db->queryOne(
    "SELECT u.*, k.nama_kelas, m.nama_mapel
     FROM ujian u
     JOIN jadwal_mengajar jm ON jm.id=u.jadwal_mengajar_id
     JOIN kelas k ON k.id=jm.kelas_id JOIN mapel m ON m.id=jm.mapel_id
     WHERE u.id=? AND jm.guru_id=?",
    'ii', [$ujian_id, $uid]
);

if (!$ujian) {
    setFlash('error', 'Ujian tidak ditemukan atau bukan milik Anda.');
    redirectTo('index.php?page=g_ujian');
}

$soals = $db->queryAll("SELECT * FROM soal WHERE ujian_id=? ORDER BY nomor, id", 'i', [$ujian_id]);

$pageTitle = 'Bank Soal: ' . $ujian['judul'];
ob_start();
?>

<div style="margin-bottom:16px;">
    <a href="?page=g_ujian" style="color:var(--text-muted);font-size:0.875rem;">← Kembali ke Daftar Ujian</a>
</div>

<div class="card mb-4" style="border-left:4px solid var(--primary);">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;">
        <div>
            <h2 style="font-size:1.1rem;font-weight:700;"><?= e($ujian['judul']) ?></h2>
            <p style="font-size:0.82rem;color:var(--text-muted);">
                <?= e($ujian['nama_kelas']) ?> | <?= e($ujian['nama_mapel']) ?> |
                ⏱ <?= $ujian['durasi_menit'] ?> menit | 📝 <?= count($soals) ?> soal
            </p>
        </div>
        <div style="display:flex;gap:8px;">
            <button class="btn btn-success" onclick="openModal('modal-soal-pilgan')">+ Tambah Pilgan</button>
            <button class="btn btn-primary" onclick="openModal('modal-soal-esai')">+ Tambah Esai</button>
        </div>
    </div>
</div>

<!-- Daftar Soal -->
<div style="display:grid;gap:12px;">
<?php if (empty($soals)): ?>
<div class="card text-center" style="padding:40px;">
    <div style="font-size:40px;margin-bottom:10px;">📝</div>
    <p>Belum ada soal. Tambahkan soal pilihan ganda atau esai.</p>
</div>
<?php else: ?>
<?php foreach ($soals as $i => $s): ?>
<div class="card" style="padding:18px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
        <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <span style="background:var(--primary);color:white;width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:700;flex-shrink:0;"><?= $i+1 ?></span>
                <span class="badge <?= $s['tipe_soal']==='pilgan' ? 'badge-info' : 'badge-warning' ?>"><?= $s['tipe_soal'] === 'pilgan' ? 'Pilihan Ganda' : 'Esai' ?></span>
                <span style="font-size:0.78rem;color:var(--text-muted);">Bobot: <?= $s['bobot'] ?> poin</span>
            </div>
            <p style="font-size:0.9rem;margin-bottom:8px;"><?= e($s['pertanyaan']) ?></p>
            <?php if ($s['tipe_soal'] === 'pilgan'): ?>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px;font-size:0.82rem;">
                <?php foreach (['a','b','c','d'] as $opt): ?>
                <?php if ($s["opsi_$opt"]): ?>
                <div style="padding:5px 10px;border-radius:6px;<?= $s['jawaban_benar']===$opt ? 'background:var(--success-bg);color:var(--success);font-weight:600;' : '' ?>">
                    <?= strtoupper($opt) ?>. <?= e($s["opsi_$opt"]) ?>
                    <?= $s['jawaban_benar']===$opt ? ' ✓' : '' ?>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <a href="<?= BASE_URL ?>/modules/guru/soal_handler.php?action=hapus&id=<?= $s['id'] ?>&ujian_id=<?= $ujian_id ?>"
           class="btn btn-sm btn-danger" data-confirm="Hapus soal ini?">🗑</a>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>

<!-- Modal Tambah Pilgan -->
<div id="modal-soal-pilgan" class="modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:580px;">
        <div class="modal-header">
            <h3>Tambah Soal Pilihan Ganda</h3>
            <button onclick="closeModal('modal-soal-pilgan')" class="modal-close">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/modules/guru/soal_handler.php">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="action" value="tambah">
            <input type="hidden" name="ujian_id" value="<?= $ujian_id ?>">
            <input type="hidden" name="tipe_soal" value="pilgan">
            <div class="form-group">
                <label>Pertanyaan *</label>
                <textarea name="pertanyaan" class="form-control" rows="3" required placeholder="Tuliskan pertanyaan..."></textarea>
            </div>
            <?php foreach (['a','b','c','d'] as $opt): ?>
            <div class="form-group">
                <label>Opsi <?= strtoupper($opt) ?> <?= $opt==='a'||$opt==='b' ? '*' : '' ?></label>
                <input type="text" name="opsi_<?= $opt ?>" class="form-control" <?= $opt==='a'||$opt==='b' ? 'required' : '' ?>>
            </div>
            <?php endforeach; ?>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label>Jawaban Benar *</label>
                    <select name="jawaban_benar" class="form-control" required>
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Bobot Poin</label>
                    <input type="number" name="bobot" class="form-control" value="1" min="1" max="10">
                </div>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:10px;">
                <button type="button" onclick="closeModal('modal-soal-pilgan')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-success">Simpan Soal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Esai -->
<div id="modal-soal-esai" class="modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:520px;">
        <div class="modal-header">
            <h3>Tambah Soal Esai</h3>
            <button onclick="closeModal('modal-soal-esai')" class="modal-close">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/modules/guru/soal_handler.php">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="action" value="tambah">
            <input type="hidden" name="ujian_id" value="<?= $ujian_id ?>">
            <input type="hidden" name="tipe_soal" value="esai">
            <div class="form-group">
                <label>Pertanyaan / Soal Esai *</label>
                <textarea name="pertanyaan" class="form-control" rows="4" required placeholder="Tuliskan soal esai..."></textarea>
            </div>
            <div class="form-group">
                <label>Bobot Poin Maksimal</label>
                <input type="number" name="bobot" class="form-control" value="10" min="1" max="100">
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:10px;">
                <button type="button" onclick="closeModal('modal-soal-esai')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Soal</button>
            </div>
        </form>
    </div>
</div>

<style>
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:500;display:flex;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(4px);}
.modal-box{background:var(--surface);border-radius:var(--radius-lg);padding:28px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow-lg);animation:slideUp .25s ease;}
.modal-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
.modal-close{background:none;border:none;font-size:22px;cursor:pointer;color:var(--text-muted);}
textarea.form-control{resize:vertical;}
</style>
<script>
function openModal(id){document.getElementById(id).style.display='flex';}
function closeModal(id){document.getElementById(id).style.display='none';}
document.querySelectorAll('.modal-overlay').forEach(m=>{m.addEventListener('click',e=>{if(e.target===m)m.style.display='none';});});
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
