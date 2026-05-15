<?php
/**
 * views/siswa/dashboard.php — Dashboard Siswa (LMS Mode)
 */
Auth::requireRole('siswa');
$db  = Database::getInstance();
$uid = $_SESSION['user_id'];

// ── Gamifikasi: Ambil stats XP siswa ──────────────────────────────────────
$gStats      = Gamifikasi::getStats($uid);
$badgeBaru   = $_SESSION['badge_baru'] ?? [];
unset($_SESSION['badge_baru']);

// Kelas aktif siswa
$kelasSiswa = $db->queryOne(
    "SELECT ks.*, k.nama_kelas, k.tingkat FROM kelas_siswa ks
     JOIN kelas k ON k.id=ks.kelas_id
     JOIN tahun_ajaran ta ON ta.id=k.tahun_ajaran_id
     WHERE ks.user_id=? AND ta.is_aktif=1 LIMIT 1",
    'i', [$uid]
);

$kelas_id = $kelasSiswa['kelas_id'] ?? 0;

// Statistik Progress Belajar
$totalItem = 0;
$selesaiItem = 0;
$materiTerbaru = [];

if ($kelas_id) {
    // Total item (materi + kuis) di semua mapel kelas ini
    $totalItem = $db->queryOne(
        "SELECT COUNT(mi.id) c FROM modul_item mi
         JOIN modul m ON m.id = mi.modul_id
         JOIN jadwal_mengajar jm ON jm.id = m.jadwal_mengajar_id
         WHERE jm.kelas_id = ?",
        'i', [$kelas_id]
    )['c'] ?? 0;

    // Selesai (progress_materi + kuis_hasil)
    $selesaiMateri = $db->queryOne(
        "SELECT COUNT(pm.id) c FROM progress_materi pm
         JOIN modul_item mi ON mi.id = pm.item_id
         JOIN modul m ON m.id = mi.modul_id
         JOIN jadwal_mengajar jm ON jm.id = m.jadwal_mengajar_id
         WHERE jm.kelas_id = ? AND pm.siswa_id = ?",
        'ii', [$kelas_id, $uid]
    )['c'] ?? 0;

    $selesaiKuis = $db->queryOne(
        "SELECT COUNT(kh.id) c FROM kuis_hasil kh
         JOIN modul_item mi ON mi.id = kh.item_id
         JOIN modul m ON m.id = mi.modul_id
         JOIN jadwal_mengajar jm ON jm.id = m.jadwal_mengajar_id
         WHERE jm.kelas_id = ? AND kh.siswa_id = ?",
        'ii', [$kelas_id, $uid]
    )['c'] ?? 0;

    $selesaiItem = $selesaiMateri + $selesaiKuis;

    $materiTerbaru = $db->queryAll(
        "SELECT mi.judul, mi.created_at, map.nama_mapel, jm.id as jm_id FROM modul_item mi
         JOIN modul m ON m.id = mi.modul_id
         JOIN jadwal_mengajar jm ON jm.id = m.jadwal_mengajar_id
         JOIN mapel map ON map.id = jm.mapel_id
         WHERE jm.kelas_id = ? AND mi.tipe = 'materi'
         ORDER BY mi.created_at DESC LIMIT 5",
        'i', [$kelas_id]
    );
}

$progressPersen = $totalItem > 0 ? round(($selesaiItem / $totalItem) * 100) : 0;

$pageTitle = 'Dashboard Siswa';
ob_start();
?>

<div style="margin-bottom:20px;">
    <h2 style="font-size:1.4rem;font-weight:700;">Halo, <?= e(explode(' ', $_SESSION['nama'])[0]) ?>! 👋</h2>
    <p style="color:var(--text-muted);margin-top:4px;">
        <?= $kelasSiswa ? '🏫 Kelas ' . e($kelasSiswa['nama_kelas']) : '⚠️ Belum terdaftar di kelas manapun.' ?>
    </p>
</div>

<!-- ══ BADGE BARU TOAST (dari session) ════════════════════════════════════ -->
<?php if (!empty($badgeBaru)): foreach ($badgeBaru as $badge): ?>
<div class="badge-toast" id="badgeToast">
    <div class="badge-toast-icon"><?= $badge['ikon'] ?></div>
    <div>
        <div style="font-weight:700; font-size:0.95rem;">Badge Baru Didapat! 🎉</div>
        <div style="font-size:0.85rem; opacity:0.9;"><?= e($badge['nama']) ?> — <?= e($badge['desc']) ?></div>
    </div>
    <button onclick="this.parentElement.remove()" style="background:none;border:none;color:white;cursor:pointer;font-size:18px;margin-left:auto;">×</button>
</div>
<?php endforeach; endif; ?>

<!-- ══ XP CARD ════════════════════════════════════════════════════════════ -->
<div class="xp-card mb-6">
    <div class="xp-card-left">
        <div class="xp-level-circle">
            <span class="xp-lv-num"><?= $gStats['level'] ?></span>
            <span class="xp-lv-txt">LV</span>
        </div>
        <div>
            <div class="xp-level-name"><?= Gamifikasi::getNamaLevel($gStats['level']) ?></div>
            <div class="xp-total"><?= number_format($gStats['total_xp']) ?> XP Total</div>
        </div>
    </div>
    <div class="xp-card-right">
        <div class="xp-bar-label">
            <span>Progress ke Level <?= $gStats['level'] + 1 ?></span>
            <span><?= $gStats['xp_di_level'] ?> / <?= $gStats['xp_btn_level'] ?> XP</span>
        </div>
        <div class="xp-bar-track">
            <div class="xp-bar-fill" style="width:<?= $gStats['xp_progress_persen'] ?>%"></div>
        </div>
        <div style="display:flex; gap:8px; margin-top:12px; flex-wrap:wrap;">
            <?php if (empty($gStats['badges'])): ?>
                <span style="font-size:0.8rem; color:rgba(255,255,255,0.7);">Belum ada badge. Mulai beraktivitas!</span>
            <?php else: foreach (array_slice($gStats['badges'], 0, 5) as $b):
                $info = Gamifikasi::BADGES[$b['badge_slug']] ?? null;
                if (!$info) continue;
            ?>
                <span class="xp-badge-chip" title="<?= e($info['desc']) ?>"><?= $info['ikon'] ?> <?= e($info['nama']) ?></span>
            <?php endforeach; endif; ?>
            <?php if (count($gStats['badges']) > 5): ?>
                <span class="xp-badge-chip">+<?= count($gStats['badges']) - 5 ?> lainnya</span>
            <?php endif; ?>
        </div>
    </div>
    <a href="?page=s_leaderboard" class="xp-lb-btn">🏆 Lihat Papan Peringkat</a>
</div>

<?php if (!$kelasSiswa): ?>
<div class="card text-center" style="padding:50px;">
    <div style="font-size:48px;margin-bottom:12px;">🏫</div>
    <h3>Anda Belum Terdaftar di Kelas</h3>
    <p class="text-muted" style="margin-top:8px;">Hubungi Admin untuk mendaftarkan Anda ke kelas yang sesuai.</p>
</div>
<?php else: ?>

<!-- Progress Card -->
<div class="card mb-6" style="background:linear-gradient(135deg, #1a3a6b, #3b82f6); color:white; border:none;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
        <span style="font-weight:600; font-size:1.1rem;">Progress Belajar Keseluruhan</span>
        <span style="font-size:1.5rem; font-weight:800;"><?= $progressPersen ?>%</span>
    </div>
    <div style="width:100%; height:12px; background:rgba(255,255,255,0.2); border-radius:10px; overflow:hidden; margin-bottom:10px;">
        <div style="width:<?= $progressPersen ?>%; height:100%; background:white; border-radius:10px;"></div>
    </div>
    <div style="font-size:0.85rem; opacity:0.9;">
        Anda telah menyelesaikan <strong><?= $selesaiItem ?></strong> dari total <strong><?= $totalItem ?></strong> modul pembelajaran.
    </div>
</div>

<div style="display:grid; grid-template-columns: 1.5fr 1fr; gap:20px;">
    
    <!-- Materi Terbaru -->
    <div class="card">
        <div class="card-header mb-4" style="display:flex; justify-content:space-between; align-items:center;">
            <span class="card-title">📚 Materi Terbaru</span>
            <a href="?page=s_course" class="btn btn-outline btn-sm">Lihat Semua Mapel</a>
        </div>
        <?php if (empty($materiTerbaru)): ?>
            <p class="text-muted p-4 text-center">Belum ada materi pembelajaran.</p>
        <?php else: ?>
            <div style="display:flex; flex-direction:column; gap:15px;">
                <?php foreach ($materiTerbaru as $mt): ?>
                <div style="padding:12px; background:var(--bg); border-radius:8px; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-weight:600;"><?= e($mt['judul']) ?></div>
                        <div style="font-size:0.8rem; color:var(--text-muted);"><?= e($mt['nama_mapel']) ?> • <?= date('d M Y', strtotime($mt['created_at'])) ?></div>
                    </div>
                    <a href="?page=s_belajar&jm_id=<?= $mt['jm_id'] ?>" class="btn btn-sm btn-primary">Buka</a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Akses Cepat -->
    <div class="card">
        <div class="card-header mb-4"><span class="card-title">⚡ Menu Cepat</span></div>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
            <a href="?page=s_course" class="btn btn-outline" style="padding:20px; text-align:center; display:flex; flex-direction:column; gap:8px;">
                <i class='bx bxs-book-reader' style="font-size:2rem;"></i>
                <span style="font-size:0.85rem;">Mulai Belajar</span>
            </a>
            <a href="?page=s_forum" class="btn btn-outline" style="padding:20px; text-align:center; display:flex; flex-direction:column; gap:8px;">
                <i class='bx bxs-chat' style="font-size:2rem;"></i>
                <span style="font-size:0.85rem;">Forum Diskusi</span>
            </a>
            <a href="?page=profil" class="btn btn-outline" style="padding:20px; text-align:center; display:flex; flex-direction:column; gap:8px; grid-column: span 2;">
                <i class='bx bxs-user-circle' style="font-size:2rem;"></i>
                <span style="font-size:0.85rem;">Profil Saya</span>
            </a>
        </div>
    </div>

</div>

<?php endif; ?>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
