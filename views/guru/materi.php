<?php
/**
 * views/guru/materi.php — Kelola Materi Ajar
 */
Auth::requireRole('guru');
$db  = Database::getInstance();
$uid = $_SESSION['user_id'];

// Filter kelas yang dipilih guru
$selKelas = (int)($_GET['kelas_id'] ?? 0);

// Ambil semua jadwal mengajar guru (untuk dropdown & filter)
$jadwals = $db->queryAll(
    "SELECT jm.id, k.id AS kelas_id, k.nama_kelas, m.nama_mapel, m.kode_mapel
     FROM jadwal_mengajar jm
     JOIN kelas k ON k.id = jm.kelas_id
     JOIN mapel m ON m.id = jm.mapel_id
     JOIN tahun_ajaran ta ON ta.id = jm.tahun_ajaran_id
     WHERE jm.guru_id = ? AND ta.is_aktif = 1
     ORDER BY k.nama_kelas, m.nama_mapel",
    'i', [$uid]
);

// Filter jadwal berdasarkan kelas terpilih
$filteredJadwals = $selKelas
    ? array_filter($jadwals, fn($j) => $j['kelas_id'] == $selKelas)
    : $jadwals;

$jmIds = array_column(array_values($filteredJadwals), 'id');

// Ambil materi berdasarkan jadwal mengajar yang dimiliki guru ini
$materis = [];
if ($jmIds) {
    $in  = implode(',', array_fill(0, count($jmIds), '?'));
    $materis = $db->queryAll(
        "SELECT mt.*, jm.id AS jm_id, k.nama_kelas, m.nama_mapel
         FROM materi mt
         JOIN jadwal_mengajar jm ON jm.id = mt.jadwal_mengajar_id
         JOIN kelas k ON k.id = jm.kelas_id
         JOIN mapel m ON m.id = jm.mapel_id
         WHERE mt.jadwal_mengajar_id IN ($in)
         ORDER BY mt.created_at DESC",
        str_repeat('i', count($jmIds)), $jmIds
    );
}

$pageTitle = 'Kelola Materi';
ob_start();
?>

<div class="card-header mb-4" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
    <h2 style="font-size:1.1rem;font-weight:700;">📄 Materi Pembelajaran</h2>
    <button class="btn btn-primary" onclick="openModal('modal-materi')">+ Upload Materi</button>
</div>

<!-- Filter Kelas -->
<div class="card mb-4">
    <form method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <input type="hidden" name="page" value="g_materi">
        <select name="kelas_id" class="form-control" style="max-width:220px;" onchange="this.form.submit()">
            <option value="">Semua Kelas</option>
            <?php
            $kelasUniq = [];
            foreach ($jadwals as $j) {
                if (!isset($kelasUniq[$j['kelas_id']])) {
                    $kelasUniq[$j['kelas_id']] = $j['nama_kelas'];
                    echo "<option value='{$j['kelas_id']}'" . ($selKelas == $j['kelas_id'] ? ' selected' : '') . ">{$j['nama_kelas']}</option>";
                }
            }
            ?>
        </select>
        <?php if ($selKelas): ?><a href="?page=g_materi" class="btn btn-outline">Reset</a><?php endif; ?>
    </form>
</div>

<!-- Daftar Materi -->
<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>#</th><th>Judul Materi</th><th>Kelas</th><th>Mapel</th><th>Tanggal</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php if (empty($materis)): ?>
            <tr><td colspan="6" class="text-center text-muted" style="padding:30px;">Belum ada materi diunggah.</td></tr>
            <?php else: ?>
            <?php foreach ($materis as $i => $m): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td>
                    <div style="font-weight:600;"><?= e($m['judul']) ?></div>
                    <?php if ($m['deskripsi']): ?>
                    <div style="font-size:0.78rem;color:var(--text-muted);"><?= e(substr($m['deskripsi'],0,60)) ?>...</div>
                    <?php endif; ?>
                </td>
                <td><?= e($m['nama_kelas']) ?></td>
                <td><?= e($m['nama_mapel']) ?></td>
                <td style="font-size:0.8rem;color:var(--text-muted);"><?= date('d M Y', strtotime($m['created_at'])) ?></td>
                <td>
                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                        <?php if ($m['file_path']): ?>
                        <a href="<?= BASE_URL ?>/modules/guru/download_materi.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-outline">⬇ Unduh</a>
                        <?php endif; ?>
                        <?php if ($m['link_eksternal']): ?>
                        <a href="<?= e($m['link_eksternal']) ?>" target="_blank" class="btn btn-sm btn-outline">🔗 Link</a>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>/modules/guru/materi_handler.php?action=hapus&id=<?= $m['id'] ?>"
                           class="btn btn-sm btn-danger" data-confirm="Hapus materi ini?">🗑</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Upload Materi -->
<div id="modal-materi" class="modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:520px;">
        <div class="modal-header">
            <h3>Upload Materi Baru</h3>
            <button onclick="closeModal('modal-materi')" class="modal-close">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/modules/guru/materi_handler.php" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="action" value="tambah">
            <div class="form-group">
                <label>Kelas & Mata Pelajaran *</label>
                <select name="jadwal_mengajar_id" class="form-control" required>
                    <option value="">— Pilih Kelas & Mapel —</option>
                    <?php foreach ($jadwals as $j): ?>
                    <option value="<?= $j['id'] ?>"><?= e($j['nama_kelas']) ?> — <?= e($j['nama_mapel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Judul Materi *</label>
                <input type="text" name="judul" class="form-control" required placeholder="Contoh: Bab 3 — Operasi Aljabar">
            </div>
            <div class="form-group">
                <label>Deskripsi <span style="color:var(--text-muted);">(Opsional)</span></label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat materi..."></textarea>
            </div>
            <div class="form-group">
                <label>Upload File <span style="color:var(--text-muted);">(PDF/DOCX/PPT — Maks 10MB)</span></label>
                <input type="file" name="file_materi" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx">
            </div>
            <div class="form-group">
                <label>Atau Link Eksternal <span style="color:var(--text-muted);">(YouTube, GDrive)</span></label>
                <input type="url" name="link_eksternal" class="form-control" placeholder="https://...">
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:10px;">
                <button type="button" onclick="closeModal('modal-materi')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Upload Materi</button>
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
