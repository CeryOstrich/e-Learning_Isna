<?php
/**
 * views/guru/leaderboard.php — Papan Peringkat Kelas untuk Guru
 */
Auth::requireRole('guru');
$db  = Database::getInstance();
$uid = $_SESSION['user_id'];

$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1");
$ta_id = $taAktif['id'] ?? 0;

// Ambil kelas yang diajar guru ini
$kelasList = $db->queryAll(
    "SELECT DISTINCT k.id, k.nama_kelas, k.tingkat 
     FROM jadwal_mengajar jm
     JOIN kelas k ON k.id = jm.kelas_id
     WHERE jm.guru_id = ? AND jm.tahun_ajaran_id = ?
     ORDER BY k.tingkat, k.nama_kelas",
    'ii', [$uid, $ta_id]
);

$kelas_id = isset($_GET['kelas_id']) ? (int)$_GET['kelas_id'] : (isset($kelasList[0]) ? $kelasList[0]['id'] : 0);

$board = [];
if ($kelas_id) {
    $board = Gamifikasi::getLeaderboard($kelas_id);
}

$pageTitle = '🏆 Leaderboard Kelas';
ob_start();
?>

<style>
/* Gunakan style podium dan rank table dari siswa/leaderboard.php */
.podium-wrap { display: flex; justify-content: center; align-items: flex-end; gap: 20px; margin-bottom: 36px; padding: 10px 0 0; }
.podium-item { display: flex; flex-direction: column; align-items: center; gap: 8px; position: relative; }
.podium-avatar { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 4px solid #e2e8f0; box-shadow: 0 4px 14px rgba(0,0,0,0.12); }
.podium-rank-1 .podium-avatar { border-color: #f59e0b; width: 84px; height: 84px; }
.podium-rank-2 .podium-avatar { border-color: #94a3b8; }
.podium-rank-3 .podium-avatar { border-color: #d97706; }
.podium-name { font-size: 0.82rem; font-weight: 700; text-align: center; max-width: 90px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text); }
.podium-xp { font-size: 0.75rem; color: var(--text-muted); }
.podium-block { width: 80px; border-radius: 10px 10px 0 0; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-weight: 800; color: white; }
.podium-rank-1 .podium-block { height: 110px; background: linear-gradient(135deg, #f59e0b, #fbbf24); width: 90px; }
.podium-rank-2 .podium-block { height: 80px;  background: linear-gradient(135deg, #64748b, #94a3b8); }
.podium-rank-3 .podium-block { height: 60px;  background: linear-gradient(135deg, #d97706, #f59e0b); }
.podium-crown { position: absolute; top: -22px; font-size: 1.5rem; animation: float 2.5s ease-in-out infinite; }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }

.rank-row { display: flex; align-items: center; gap: 14px; padding: 12px 16px; border-radius: 10px; transition: background 0.2s; }
.rank-row:hover { background: var(--surface-2); }
.rank-num { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0; background: var(--surface-2); color: var(--text-muted); }
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
.lv-badge { display: inline-flex; align-items: center; gap: 4px; background: var(--primary); color: white; font-size: 0.7rem; font-weight: 700; padding: 2px 8px; border-radius: 99px; }
.mini-xp-bar { height: 6px; background: var(--border); border-radius: 99px; overflow: hidden; margin-top: 4px; }
.mini-xp-bar-fill { height: 100%; background: linear-gradient(90deg, #1a3a6b, #3b82f6); border-radius: 99px; transition: width 1s ease; }
</style>

<div style="margin-bottom:24px;">
    <h2 style="font-size:1.4rem; font-weight:800;">🏆 Pantau Peringkat Kelas</h2>
    <p style="color:var(--text-muted); font-size:0.9rem; margin-top:4px;">Lihat perkembangan poin XP dan keaktifan murid di kelas yang Anda ajar.</p>
</div>

<?php if(empty($kelasList)): ?>
    <div class="alert alert-info show">Anda belum memiliki jadwal mengajar di Tahun Ajaran ini.</div>
<?php else: ?>
    <!-- Pilih Kelas -->
    <div class="card mb-6">
        <div class="card-header mb-0"><span class="card-title">🏫 Pilih Kelas</span></div>
        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:15px;">
            <?php foreach($kelasList as $k): ?>
                <a href="?page=g_leaderboard&kelas_id=<?= $k['id'] ?>" class="btn <?= $kelas_id == $k['id'] ? 'btn-primary' : 'btn-outline' ?>">
                    Kelas <?= e($k['nama_kelas']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (empty($board)): ?>
        <div class="card" style="text-align:center; padding:60px 20px;">
            <div style="font-size:48px; margin-bottom:12px;">🏅</div>
            <h3>Belum Ada Data</h3>
            <p class="text-muted" style="margin-top:8px;">Siswa di kelas ini belum memiliki XP gamifikasi.</p>
        </div>
    <?php else: ?>
        <!-- PODIUM TOP 3 -->
        <div class="card mb-6">
            <div style="text-align:center; margin-bottom:20px;">
                <span style="font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-muted);">Top 3 Peraih XP Terbanyak</span>
            </div>

            <div class="podium-wrap">
                <?php
                $podiumOrder = [];
                if (isset($board[1])) $podiumOrder[] = ['data' => $board[1], 'rank_class' => 'podium-rank-2', 'label' => '2'];
                if (isset($board[0])) $podiumOrder[] = ['data' => $board[0], 'rank_class' => 'podium-rank-1', 'label' => '1'];
                if (isset($board[2])) $podiumOrder[] = ['data' => $board[2], 'rank_class' => 'podium-rank-3', 'label' => '3'];

                foreach ($podiumOrder as $p):
                    $pd = $p['data'];
                ?>
                <div class="podium-item <?= $p['rank_class'] ?>">
                    <?php if ($p['label'] === '1'): ?>
                        <div class="podium-crown">👑</div>
                    <?php endif; ?>
                    <img src="<?= e($pd['foto_url']) ?>" alt="<?= e($pd['nama']) ?>" class="podium-avatar">
                    <div class="podium-name" title="<?= e($pd['nama']) ?>"><?= e(explode(' ', $pd['nama'])[0]) ?></div>
                    <div class="podium-xp"><?= number_format($pd['total_xp']) ?> XP</div>
                    <div class="podium-block"><?= $p['label'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- TABEL RANKING LENGKAP -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">📋 Daftar Peringkat Lengkap</span>
                <span style="font-size:0.8rem; color:var(--text-muted);"><?= count($board) ?> siswa terdaftar</span>
            </div>

            <div style="display:flex; flex-direction:column; gap:4px;">
                <?php foreach ($board as $r):
                    $rankClass = $r['rank'] === 1 ? 'top-1' : ($r['rank'] === 2 ? 'top-2' : ($r['rank'] === 3 ? 'top-3' : ''));
                    $stats   = Gamifikasi::getStats((int)$r['id']);
                ?>
                <div class="rank-row">
                    <div class="rank-num <?= $rankClass ?>">#<?= $r['rank'] ?></div>
                    <img src="<?= e($r['foto_url']) ?>" alt="<?= e($r['nama']) ?>" class="rank-avatar">
                    <div class="rank-info">
                        <div class="rn"><?= e($r['nama']) ?></div>
                        <div class="rl">
                            <span class="lv-badge">⚡ Lv. <?= $r['level'] ?> — <?= Gamifikasi::getNamaLevel($r['level']) ?></span>
                            &nbsp;🏅 <?= $r['jumlah_badge'] ?> badge
                        </div>
                        <div class="mini-xp-bar" style="width:120px; margin-top:5px;">
                            <div class="mini-xp-bar-fill" style="width:<?= $stats['xp_progress_persen'] ?>%"></div>
                        </div>
                    </div>
                    <div class="rank-xp">
                        <div class="xv"><?= number_format($r['total_xp']) ?></div>
                        <div class="xb">XP</div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

<?php endif; ?>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
