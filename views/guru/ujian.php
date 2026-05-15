<?php
/**
 * views/guru/ujian.php — Modul CBT: Daftar Ujian & Bank Soal
 */
Auth::requireRole('guru');
$db  = Database::getInstance();
$uid = $_SESSION['user_id'];

// Ambil semua ujian milik guru ini
$ujians = $db->queryAll(
    "SELECT u.*, jm.id AS jm_id, k.nama_kelas, m.nama_mapel,
            (SELECT COUNT(*) FROM soal s WHERE s.ujian_id = u.id) AS jml_soal,
            (SELECT COUNT(*) FROM sesi_ujian su WHERE su.ujian_id = u.id) AS jml_peserta
     FROM ujian u
     JOIN jadwal_mengajar jm ON jm.id = u.jadwal_mengajar_id
     JOIN kelas k ON k.id = jm.kelas_id
     JOIN mapel m ON m.id = jm.mapel_id
     JOIN tahun_ajaran ta ON ta.id = jm.tahun_ajaran_id
     WHERE jm.guru_id = ? AND ta.is_aktif = 1
     ORDER BY u.created_at DESC",
    'i', [$uid]
);

// Jadwal untuk dropdown form
$jadwals = $db->queryAll(
    "SELECT jm.id, k.nama_kelas, m.nama_mapel FROM jadwal_mengajar jm
     JOIN kelas k ON k.id=jm.kelas_id JOIN mapel m ON m.id=jm.mapel_id
     JOIN tahun_ajaran ta ON ta.id=jm.tahun_ajaran_id
     WHERE jm.guru_id=? AND ta.is_aktif=1 ORDER BY k.nama_kelas",
    'i', [$uid]
);

$pageTitle = 'Ujian CBT';
ob_start();
?>

<div class="card-header mb-4" style="display:flex;justify-content:space-between;align-items:center;">
    <h2 style="font-size:1.1rem;font-weight:700;">📋 Manajemen Ujian CBT</h2>
    <button class="btn btn-primary" onclick="openModal('modal-ujian')">+ Buat Ujian Baru</button>
</div>

<div style="display:grid;gap:14px;">
<?php if (empty($ujians)): ?>
<div class="card text-center" style="padding:50px;">
    <div style="font-size:48px;margin-bottom:12px;">📭</div>
    <h3>Belum ada ujian</h3>
    <p class="text-muted">Klik "Buat Ujian Baru" untuk mulai membuat soal.</p>
</div>
<?php else: ?>
<?php foreach ($ujians as $u):
    $statusColor = ['draft'=>'neutral','aktif'=>'success','selesai'=>'danger'][$u['status']] ?? 'neutral';
?>
<div class="card" style="padding:20px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                <span class="badge badge-<?= $statusColor ?>"><?= ucfirst(e($u['status'])) ?></span>
                <span style="font-size:0.78rem;color:var(--text-muted);"><?= e($u['tipe']) ?></span>
            </div>
            <h3 style="font-size:1rem;font-weight:700;"><?= e($u['judul']) ?></h3>
            <p style="font-size:0.82rem;color:var(--text-muted);margin-top:4px;">
                🏫 <?= e($u['nama_kelas']) ?> | 📚 <?= e($u['nama_mapel']) ?>
            </p>
        </div>
        <div style="text-align:right;">
            <div style="font-size:0.82rem;color:var(--text-muted);">
                ⏱ <?= $u['durasi_menit'] ?> menit &nbsp;|&nbsp;
                📝 <?= $u['jml_soal'] ?> soal &nbsp;|&nbsp;
                👥 <?= $u['jml_peserta'] ?> peserta
            </div>
            <?php if ($u['waktu_mulai']): ?>
            <div style="font-size:0.78rem;color:var(--text-muted);margin-top:4px;">
                🕐 <?= date('d M Y H:i', strtotime($u['waktu_mulai'])) ?> — <?= date('H:i', strtotime($u['waktu_selesai'])) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;border-top:1px solid var(--border);padding-top:14px;">
        <a href="?page=g_soal&ujian_id=<?= $u['id'] ?>" class="btn btn-sm btn-outline">✏️ Kelola Soal</a>
        <?php if ($u['status'] === 'draft'): ?>
        <a href="<?= BASE_URL ?>/modules/guru/ujian_handler.php?action=aktifkan&id=<?= $u['id'] ?>"
           class="btn btn-sm btn-success" data-confirm="Aktifkan ujian ini? Siswa akan bisa mulai mengerjakan.">▶ Aktifkan</a>
        <?php elseif ($u['status'] === 'aktif'): ?>
        <a href="<?= BASE_URL ?>/modules/guru/ujian_handler.php?action=selesaikan&id=<?= $u['id'] ?>"
           class="btn btn-sm btn-warning" data-confirm="Tutup ujian ini?">⏹ Selesaikan</a>
        <?php endif; ?>
        <?php if ($u['jml_peserta'] > 0): ?>
        <a href="?page=g_nilai&ujian_id=<?= $u['id'] ?>" class="btn btn-sm btn-primary">📊 Lihat Nilai</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/modules/guru/ujian_handler.php?action=hapus&id=<?= $u['id'] ?>"
           class="btn btn-sm btn-danger" data-confirm="Hapus ujian beserta semua soalnya?">🗑 Hapus</a>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>

<!-- Modal Buat Ujian -->
<div id="modal-ujian" class="modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:540px;">
        <div class="modal-header">
            <h3>Buat Ujian Baru</h3>
            <button onclick="closeModal('modal-ujian')" class="modal-close">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/modules/guru/ujian_handler.php">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="action" value="buat">
            <div class="form-group">
                <label>Kelas & Mata Pelajaran *</label>
                <select name="jadwal_mengajar_id" class="form-control" required>
                    <option value="">— Pilih —</option>
                    <?php foreach ($jadwals as $j): ?>
                    <option value="<?= $j['id'] ?>"><?= e($j['nama_kelas']) ?> — <?= e($j['nama_mapel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Judul Ujian *</label>
                <input type="text" name="judul" class="form-control" required placeholder="Ulangan Harian Bab 1">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label>Tipe</label>
                    <select name="tipe" class="form-control">
                        <option value="kuis">Kuis</option>
                        <option value="ulangan_harian">Ulangan Harian</option>
                        <option value="uts">UTS</option>
                        <option value="uas">UAS</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Durasi (menit) *</label>
                    <input type="number" name="durasi_menit" class="form-control" value="60" min="5" max="240" required>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label>Waktu Mulai</label>
                    <input type="datetime-local" name="waktu_mulai" class="form-control">
                </div>
                <div class="form-group">
                    <label>Waktu Selesai</label>
                    <input type="datetime-local" name="waktu_selesai" class="form-control">
                </div>
            </div>
            <div style="display:flex;gap:12px;">
                <label style="display:flex;align-items:center;gap:8px;font-size:0.875rem;cursor:pointer;">
                    <input type="checkbox" name="acak_soal" value="1" checked> Acak Urutan Soal
                </label>
                <label style="display:flex;align-items:center;gap:8px;font-size:0.875rem;cursor:pointer;">
                    <input type="checkbox" name="tampil_hasil" value="1"> Tampilkan Skor Langsung
                </label>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button type="button" onclick="closeModal('modal-ujian')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Buat & Kelola Soal</button>
            </div>
        </form>
    </div>
</div>

<style>
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:500;display:flex;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(4px);}
.modal-box{background:var(--surface);border-radius:var(--radius-lg);padding:28px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow-lg);animation:slideUp .25s ease;}
.modal-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
.modal-close{background:none;border:none;font-size:22px;cursor:pointer;color:var(--text-muted);}
</style>
<script>
function openModal(id){document.getElementById(id).style.display='flex';}
function closeModal(id){document.getElementById(id).style.display='none';}
document.querySelectorAll('.modal-overlay').forEach(m=>{m.addEventListener('click',e=>{if(e.target===m)m.style.display='none';});});
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
