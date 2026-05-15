<?php
/**
 * views/guru/dashboard.php — Dashboard Guru (LMS Mode)
 * Menampilkan ringkasan course dan progress pengajaran.
 */
Auth::requireRole('guru');
$db    = Database::getInstance();
$uid   = $_SESSION['user_id'];

// Statistik LMS
$totalModul = $db->queryOne(
    "SELECT COUNT(*) c FROM modul m 
     JOIN jadwal_mengajar jm ON jm.id = m.jadwal_mengajar_id
     WHERE jm.guru_id = ?",
    'i', [$uid]
)['c'] ?? 0;

$totalMateri = $db->queryOne(
    "SELECT COUNT(*) c FROM modul_item mi
     JOIN modul m ON m.id = mi.modul_id
     JOIN jadwal_mengajar jm ON jm.id = m.jadwal_mengajar_id
     WHERE jm.guru_id = ? AND mi.tipe = 'materi'",
    'i', [$uid]
)['c'] ?? 0;

$totalKuis = $db->queryOne(
    "SELECT COUNT(*) c FROM modul_item mi
     JOIN modul m ON m.id = mi.modul_id
     JOIN jadwal_mengajar jm ON jm.id = m.jadwal_mengajar_id
     WHERE jm.guru_id = ? AND mi.tipe = 'kuis'",
    'i', [$uid]
)['c'] ?? 0;

// Penugasan Guru (Jadwal Mengajar tanpa jam)
$jadwals = $db->queryAll(
    "SELECT jm.id AS jm_id, k.nama_kelas, m.nama_mapel
     FROM jadwal_mengajar jm
     JOIN kelas k ON k.id = jm.kelas_id
     JOIN mapel m ON m.id = jm.mapel_id
     JOIN tahun_ajaran ta ON ta.id = jm.tahun_ajaran_id
     WHERE jm.guru_id = ? AND ta.is_aktif = 1
     ORDER BY k.nama_kelas, m.nama_mapel",
    'i', [$uid]
);

$pageTitle = 'Dashboard Guru';
ob_start();
?>

<!-- Sapaan -->
<div style="margin-bottom:24px;">
    <h2 style="font-size:1.4rem;font-weight:700;">Selamat Datang, <?= e(explode(' ', $_SESSION['nama'])[0]) ?>! 👋</h2>
    <p style="color:var(--text-muted);margin-top:4px;">Anda masuk sebagai pengajar pada sistem E-Learning MTs.</p>
</div>

<!-- Statistik LMS Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:30px;">
    <div class="card" style="border-left:4px solid var(--primary);padding:20px;">
        <div style="font-size:0.8rem;color:var(--text-muted);text-transform:uppercase;font-weight:600;">Total Modul</div>
        <div style="font-size:1.8rem;font-weight:700;margin-top:5px;"><?= $totalModul ?></div>
    </div>
    <div class="card" style="border-left:4px solid var(--success);padding:20px;">
        <div style="font-size:0.8rem;color:var(--text-muted);text-transform:uppercase;font-weight:600;">Materi Bacaan</div>
        <div style="font-size:1.8rem;font-weight:700;margin-top:5px;"><?= $totalMateri ?></div>
    </div>
    <div class="card" style="border-left:4px solid var(--info);padding:20px;">
        <div style="font-size:0.8rem;color:var(--text-muted);text-transform:uppercase;font-weight:600;">Kuis Interaktif</div>
        <div style="font-size:1.8rem;font-weight:700;margin-top:5px;"><?= $totalKuis ?></div>
    </div>
</div>

<h3 class="mb-4">Kelas & Mata Pelajaran Saya</h3>

<?php if (empty($jadwals)): ?>
<div class="card text-center" style="padding:50px;">
    <div style="font-size:48px;margin-bottom:12px;">📭</div>
    <h3>Belum Ada Penugasan Guru</h3>
    <p class="text-muted" style="margin-top:8px;">Hubungi Admin untuk menetapkan kelas dan mata pelajaran Anda.</p>
</div>
<?php else: ?>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px;">
    <?php foreach ($jadwals as $j): ?>
    <div class="card" style="display:flex;flex-direction:column;justify-content:space-between;gap:15px;padding:20px;border-top:4px solid var(--primary);">
        <div>
            <div style="font-weight:700;font-size:1.1rem;line-height:1.3;margin-bottom:4px;"><?= e($j['nama_mapel']) ?></div>
            <div style="color:var(--text-muted);font-size:0.9rem;"><i class='bx bxs-graduation'></i> Kelas <?= e($j['nama_kelas']) ?></div>
        </div>
        <div style="margin-top:auto;">
            <a href="?page=g_course&jm_id=<?= $j['jm_id'] ?>" class="btn btn-primary btn-sm" style="display:block;text-align:center;width:100%;padding:10px;"><i class='bx bxs-book-content'></i> Kelola Modul Kelas</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
