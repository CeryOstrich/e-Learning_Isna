<?php
/**
 * views/admin/dashboard.php — Dashboard Admin (LMS Mode)
 */
Auth::requireRole('admin');
$db = Database::getInstance();

// ── Statistik utama ───────────────────────────────────────
$totalSiswa  = $db->queryOne("SELECT COUNT(*) c FROM users WHERE role='siswa' AND is_active=1")['c'] ?? 0;
$totalGuru   = $db->queryOne("SELECT COUNT(*) c FROM users WHERE role='guru'  AND is_active=1")['c'] ?? 0;
$totalKelas  = $db->queryOne("SELECT COUNT(*) c FROM kelas k JOIN tahun_ajaran ta ON ta.id=k.tahun_ajaran_id WHERE ta.is_aktif=1")['c'] ?? 0;
$totalMapel  = $db->queryOne("SELECT COUNT(*) c FROM mapel")['c'] ?? 0;

// ── Tahun Ajaran aktif ───────────────────────────────────
$taAktif = $db->queryOne("SELECT * FROM tahun_ajaran WHERE is_aktif=1 LIMIT 1");

// ── Guru tanpa penugasan ─────────
$guruTanpaJadwal = [];
if ($taAktif) {
    $guruTanpaJadwal = $db->queryAll(
        "SELECT u.nama FROM users u
         WHERE u.role='guru' AND u.is_active=1
         AND u.id NOT IN (SELECT DISTINCT guru_id FROM jadwal_mengajar WHERE tahun_ajaran_id=?)
         LIMIT 5",
        'i', [$taAktif['id']]
    );
}

// ── 5 user terbaru ────────────────────────────────────────
$userTerbaru = $db->queryAll(
    "SELECT nama, role, created_at FROM users ORDER BY created_at DESC LIMIT 5"
);

$pageTitle = 'Dashboard Admin';
ob_start();
?>

<div class="stat-grid mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;">👨‍🎓</div>
        <div>
            <div class="stat-value"><?= $totalSiswa ?></div>
            <div class="stat-label">Total Siswa Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;">👨‍🏫</div>
        <div>
            <div class="stat-value"><?= $totalGuru ?></div>
            <div class="stat-label">Total Guru Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;">🏫</div>
        <div>
            <div class="stat-value"><?= $totalKelas ?></div>
            <div class="stat-label">Kelas (TA Aktif)</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fce7f3;">📚</div>
        <div>
            <div class="stat-value"><?= $totalMapel ?></div>
            <div class="stat-label">Mata Pelajaran</div>
        </div>
    </div>
</div>

<!-- Info TA Aktif -->
<div class="card mb-6" style="border-left:4px solid var(--primary);">
    <div style="display:flex;align-items:center;gap:12px;">
        <span style="font-size:28px;">📅</span>
        <div>
            <div style="font-size:0.8rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;">Tahun Ajaran Aktif</div>
            <div style="font-size:1.2rem;font-weight:700;color:var(--primary);">
                <?= $taAktif ? e($taAktif['nama']) : '<span style="color:var(--danger)">Belum ada TA aktif!</span>' ?>
            </div>
        </div>
        <a href="?page=a_tahun_ajaran" class="btn btn-outline btn-sm" style="margin-left:auto;">Kelola TA</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;flex-wrap:wrap;">

    <!-- Guru belum ada penugasan -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">⚠️ Guru Belum Ditugaskan</span>
            <a href="?page=a_penugasan_guru" class="btn btn-sm btn-primary">+ Atur Penugasan</a>
        </div>
        <?php if (empty($guruTanpaJadwal)): ?>
        <p style="color:var(--success);font-size:0.875rem;">✅ Semua guru sudah memiliki penugasan kelas.</p>
        <?php else: ?>
        <ul style="list-style:none;font-size:0.875rem;">
            <?php foreach ($guruTanpaJadwal as $g): ?>
            <li style="padding:7px 0;border-bottom:1px solid var(--border);">👤 <?= e($g['nama']) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>

    <!-- User terbaru -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">🆕 User Terbaru</span>
            <a href="?page=a_users" class="btn btn-sm btn-outline">Lihat Semua</a>
        </div>
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Nama</th><th>Role</th><th>Terdaftar</th></tr></thead>
                <tbody>
                <?php foreach ($userTerbaru as $u): ?>
                <tr>
                    <td><?= e($u['nama']) ?></td>
                    <td><span class="badge badge-<?= $u['role'] === 'admin' ? 'danger' : ($u['role'] === 'guru' ? 'success' : 'info') ?>"><?= e($u['role']) ?></span></td>
                    <td style="color:var(--text-muted);font-size:0.8rem;"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Akses Cepat -->
<div class="card mt-6">
    <div class="card-header"><span class="card-title">⚡ Akses Cepat</span></div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;">
        <?php
        $shortcuts = [
            ['?page=a_users',          '👥', 'Kelola User'],
            ['?page=a_kelas',          '🏫', 'Kelola Kelas'],
            ['?page=a_penugasan_guru', '👨‍🏫', 'Penugasan Guru'],
            ['?page=a_pengumuman',     '📢', 'Pengumuman'],
            ['?page=a_laporan',        '📊', 'Laporan'],
        ];
        foreach ($shortcuts as [$url, $ico, $label]): ?>
        <a href="<?= $url ?>" style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:18px;background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius);text-decoration:none;color:var(--text);font-size:0.85rem;font-weight:600;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
            <span style="font-size:28px;"><?= $ico ?></span>
            <?= $label ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/views/shared/_layout.php';
