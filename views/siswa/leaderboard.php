<?php
/**
 * views/siswa/leaderboard.php — Papan Peringkat Gamifikasi
 */
Auth::requireRole('siswa');
$db  = Database::getInstance();
$uid = $_SESSION['user_id'];

// Kelas siswa aktif
$kelasSiswa = $db->queryOne(
    "SELECT ks.kelas_id, k.nama_kelas FROM kelas_siswa ks
     JOIN kelas k ON k.id = ks.kelas_id
     JOIN tahun_ajaran ta ON ta.id = k.tahun_ajaran_id
     WHERE ks.user_id = ? AND ta.is_aktif = 1 LIMIT 1",
    'i', [$uid]
);
$kelas_id = $kelasSiswa['kelas_id'] ?? 0;

// Mode: per kelas atau global
$mode     = isset($_GET['mode']) && $_GET['mode'] === 'global' ? 'global' : 'kelas';
$board    = $mode === 'global'
    ? Gamifikasi::getLeaderboard(0)
    : Gamifikasi::getLeaderboard($kelas_id);

// Posisi siswa sendiri di leaderboard
$posisiSaya = null;
foreach ($board as $r) {
    if ($r['id'] == $uid) { $posisiSaya = $r; break; }
}

$pageTitle = '🏆 Leaderboard';
ob_start();
?>

<style>
/* ── Podium ──────────────────────────────────────────────────────────────── */
.podium-wrap {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    gap: 20px;
    margin-bottom: 36px;
    padding: 10px 0 0;
}

.podium-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    position: relative;
}

.podium-avatar {
    width: 70px; height: 70px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #e2e8f0;
    box-shadow: 0 4px 14px rgba(0,0,0,0.12);
}

.podium-rank-1 .podium-avatar { border-color: #f59e0b; width: 84px; height: 84px; }
.podium-rank-2 .podium-avatar { border-color: #94a3b8; }
.podium-rank-3 .podium-avatar { border-color: #d97706; }

.podium-name {
    font-size: 0.82rem;
    font-weight: 700;
    text-align: center;
    max-width: 90px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: var(--text);
}

.podium-xp {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.podium-block {
    width: 80px;
    border-radius: 10px 10px 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    font-weight: 800;
    color: white;
}

.podium-rank-1 .podium-block { height: 110px; background: linear-gradient(135deg, #f59e0b, #fbbf24); width: 90px; }
.podium-rank-2 .podium-block { height: 80px;  background: linear-gradient(135deg, #64748b, #94a3b8); }
.podium-rank-3 .podium-block { height: 60px;  background: linear-gradient(135deg, #d97706, #f59e0b); }

.podium-crown {
    position: absolute;
    top: -22px;
    font-size: 1.5rem;
    animation: float 2.5s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-5px); }
}

/* ── Tabel Ranking ───────────────────────────────────────────────────────── */
.rank-row { display: flex; align-items: center; gap: 14px; padding: 12px 16px; border-radius: 10px; transition: background 0.2s; }
.rank-row:hover { background: var(--surface-2); }
.rank-row.is-me  { background: rgba(26,58,107,0.07); border: 1.5px solid var(--primary); }

.rank-num {
    width: 32px; height: 32px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 0.85rem;
    flex-shrink: 0;
    background: var(--surface-2);
    color: var(--text-muted);
}

.rank-num.top-1 { background: linear-gradient(135deg,#f59e0b,#fbbf24); color:white; }
.rank-num.top-2 { background: linear-gradient(135deg,#64748b,#94a3b8); color:white; }
.rank-num.top-3 { background: linear-gradient(135deg,#d97706,#f59e0b); color:white; }

.rank-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit:cover; flex-shrink:0; }

.rank-info { flex: 1; min-width: 0; }
.rank-info .rn  { font-weight: 600; font-size: 0.9rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.rank-info .rl  { font-size: 0.75rem; color: var(--text-muted); }

.rank-xp { text-align: right; flex-shrink: 0; }
.rank-xp .xv  { font-weight: 700; font-size: 0.95rem; color: var(--primary); }
.rank-xp .xb  { font-size: 0.72rem; color: var(--text-muted); }

/* ── XP Bar ──────────────────────────────────────────────────────────────── */
.mini-xp-bar { height: 6px; background: var(--border); border-radius: 99px; overflow: hidden; margin-top: 4px; }
.mini-xp-bar-fill { height: 100%; background: linear-gradient(90deg, #1a3a6b, #3b82f6); border-radius: 99px; transition: width 1s ease; }

/* ── Level Badge ─────────────────────────────────────────────────────────── */
.lv-badge {
    display: inline-flex; align-items: center; gap: 4px;
    background: var(--primary); color: white;
    font-size: 0.7rem; font-weight: 700;
    padding: 2px 8px; border-radius: 99px;
}

/* ── Mode Tabs ───────────────────────────────────────────────────────────── */
.mode-tabs { display: flex; gap: 8px; margin-bottom: 24px; }
.mode-tab  {
    padding: 8px 20px; border-radius: 99px; font-size: 0.85rem; font-weight: 600;
    border: 1.5px solid var(--border); color: var(--text-muted); text-decoration: none;
    transition: all 0.2s;
}
.mode-tab.active { background: var(--primary); color: white; border-color: var(--primary); }
</style>

<!-- ── Header & Mode Tabs ─────────────────────────────────────── -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="font-size:1.4rem; font-weight:800;">🏆 Papan Peringkat</h2>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:4px;">Bersaing dengan teman sekelas dan raih posisi terbaik!</p>
    </div>
    <?php if ($posisiSaya): ?>
    <div style="background:var(--surface); border:1.5px solid var(--primary); border-radius:12px; padding:10px 18px; text-align:center;">
        <div style="font-size:0.75rem; color:var(--text-muted);">Posisi Kamu</div>
        <div style="font-size:1.8rem; font-weight:800; color:var(--primary);">#<?= $posisiSaya['rank'] ?></div>
        <div style="font-size:0.8rem; color:var(--text-muted);"><?= $posisiSaya['total_xp'] ?> XP</div>
    </div>
    <?php endif; ?>
</div>

<div class="mode-tabs">
    <a href="?page=s_leaderboard&mode=kelas" class="mode-tab <?= $mode === 'kelas' ? 'active' : '' ?>">
        🏫 Per Kelas<?= $kelasSiswa ? ' (' . e($kelasSiswa['nama_kelas']) . ')' : '' ?>
    </a>
    <a href="?page=s_leaderboard&mode=global" class="mode-tab <?= $mode === 'global' ? 'active' : '' ?>">
        🌐 Global
    </a>
</div>

<?php if (empty($board)): ?>
<div class="card" style="text-align:center; padding:60px 20px;">
    <div style="font-size:48px; margin-bottom:12px;">🏅</div>
    <h3>Belum Ada Data</h3>
    <p class="text-muted" style="margin-top:8px;">Mulai belajar untuk masuk leaderboard!</p>
</div>

<?php else: ?>

<!-- ── PODIUM TOP 3 ─────────────────────────────────────────────── -->
<div class="card mb-6">
    <div style="text-align:center; margin-bottom:20px;">
        <span style="font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-muted);">Top 3 Peraih XP Terbanyak</span>
    </div>

    <div class="podium-wrap">
        <?php
        // Susun urutan tampilan podium: #2, #1, #3
        $podiumOrder = [];
        if (isset($board[1])) $podiumOrder[] = ['data' => $board[1], 'rank_class' => 'podium-rank-2', 'label' => '2'];
        if (isset($board[0])) $podiumOrder[] = ['data' => $board[0], 'rank_class' => 'podium-rank-1', 'label' => '1'];
        if (isset($board[2])) $podiumOrder[] = ['data' => $board[2], 'rank_class' => 'podium-rank-3', 'label' => '3'];

        foreach ($podiumOrder as $p):
            $pd = $p['data'];
            $isMe = ($pd['id'] == $uid);
        ?>
        <div class="podium-item <?= $p['rank_class'] ?>">
            <?php if ($p['label'] === '1'): ?>
                <div class="podium-crown">👑</div>
            <?php endif; ?>
            <img src="<?= e($pd['foto_url']) ?>" alt="<?= e($pd['nama']) ?>" class="podium-avatar">
            <div class="podium-name" title="<?= e($pd['nama']) ?>"><?= e(explode(' ', $pd['nama'])[0]) ?><?= $isMe ? ' (Kamu)' : '' ?></div>
            <div class="podium-xp"><?= number_format($pd['total_xp']) ?> XP</div>
            <div class="podium-block"><?= $p['label'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ── TABEL RANKING LENGKAP ─────────────────────────────────────── -->
<div class="card">
    <div class="card-header">
        <span class="card-title">📋 Daftar Peringkat Lengkap</span>
        <span style="font-size:0.8rem; color:var(--text-muted);"><?= count($board) ?> siswa terdaftar</span>
    </div>

    <div style="display:flex; flex-direction:column; gap:4px;">
        <?php foreach ($board as $r):
            $isMe    = ($r['id'] == $uid);
            $rankClass = $r['rank'] === 1 ? 'top-1' : ($r['rank'] === 2 ? 'top-2' : ($r['rank'] === 3 ? 'top-3' : ''));
            $stats   = Gamifikasi::getStats((int)$r['id']);
        ?>
        <div class="rank-row <?= $isMe ? 'is-me' : '' ?>">
            <!-- Nomor Rank -->
            <div class="rank-num <?= $rankClass ?>">#<?= $r['rank'] ?></div>

            <!-- Avatar -->
            <img src="<?= e($r['foto_url']) ?>" alt="<?= e($r['nama']) ?>" class="rank-avatar">

            <!-- Nama & Level -->
            <div class="rank-info">
                <div class="rn">
                    <?= e($r['nama']) ?>
                    <?php if ($isMe): ?>
                        <span style="font-size:0.72rem; background:#dbeafe; color:#1e40af; border-radius:99px; padding:1px 6px; margin-left:4px;">Kamu</span>
                    <?php endif; ?>
                </div>
                <div class="rl">
                    <span class="lv-badge">⚡ Lv. <?= $r['level'] ?> — <?= Gamifikasi::getNamaLevel($r['level']) ?></span>
                    &nbsp;🏅 <?= $r['jumlah_badge'] ?> badge
                </div>
                <!-- Mini XP Bar -->
                <div class="mini-xp-bar" style="width:120px; margin-top:5px;">
                    <div class="mini-xp-bar-fill" style="width:<?= $stats['xp_progress_persen'] ?>%"></div>
                </div>
            </div>

            <!-- XP -->
            <div class="rank-xp">
                <div class="xv"><?= number_format($r['total_xp']) ?></div>
                <div class="xb">XP</div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php endif; ?>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
