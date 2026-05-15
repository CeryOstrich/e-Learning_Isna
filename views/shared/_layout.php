<?php
/**
 * views/shared/_layout.php
 * Master layout yang membungkus semua halaman dashboard.
 *
 * Cara pakai di setiap view:
 *   $pageTitle = 'Judul Halaman';
 *   ob_start();
 *   // ... konten halaman ...
 *   $content = ob_get_clean();
 *   include ROOT_PATH . '/views/shared/_layout.php';
 */

Auth::requireLogin();

$role = $_SESSION['role'];
$nama = $_SESSION['nama'] ?? 'User';
$foto = $_SESSION['foto_profil'] ?? null;
$fotoUrl = $foto
    ? BASE_URL . '/uploads/profil/' . $foto
    : 'https://ui-avatars.com/api/?name=' . urlencode($nama) . '&background=1a3a6b&color=fff&bold=true&size=80';

$currentPage = $_GET['page'] ?? 'dashboard';

// ── Ambil jumlah notif belum dibaca ────────────────────────────────────────────
$db = Database::getInstance();
$notifCount = (int)($db->queryOne(
    "SELECT COUNT(*) AS c FROM notifikasi WHERE user_id = ? AND dibaca = 0",
    'i', [$_SESSION['user_id']]
)['c'] ?? 0);

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Dashboard') ?> — <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/main.css">
</head>
<body class="<?= $role ?>-theme" id="body">

<!-- ════════════════════ SIDEBAR ════════════════════ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <div class="brand-icon-sm">🕌</div>
            <div class="sidebar-title">
                <strong><?= APP_NAME ?></strong>
                <span><?= ucfirst($role) ?></span>
            </div>
        </div>
        <button class="sidebar-close-btn" id="sidebarCloseBtn" title="Tutup sidebar">
            <i class='bx bx-x'></i>
        </button>
    </div>

    <!-- Profil singkat di sidebar -->
    <div class="sidebar-profile">
        <img src="<?= $fotoUrl ?>" alt="Foto <?= e($nama) ?>">
        <div>
            <div class="sp-name"><?= e($nama) ?></div>
            <div class="sp-role badge-<?= $role ?>"><?= ucfirst($role) ?></div>
            <?php if ($role === 'siswa'):
                $sidebarXP = Gamifikasi::getStats($_SESSION['user_id']);
            ?>
            <div class="sp-xp-mini">
                <span class="sp-xp-lv">⚡ Lv.<?= $sidebarXP['level'] ?></span>
                <div class="sp-xp-bar"><div class="sp-xp-fill" style="width:<?= $sidebarXP['xp_progress_persen'] ?>%"></div></div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <nav class="sidebar-nav">
        <!-- ── Navigasi berdasarkan ROLE ───────────────────── -->
        <?php if ($role === 'admin'): ?>
            <div class="nav-label">Utama</div>
            <a href="?page=dashboard"         class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                <i class='bx bxs-dashboard'></i><span>Dashboard</span>
            </a>

            <div class="nav-label">Master Data</div>
            <a href="?page=a_users"           class="<?= $currentPage === 'a_users' ? 'active' : '' ?>">
                <i class='bx bxs-group'></i><span>Pengguna</span>
            </a>
            <a href="?page=a_kelas"           class="<?= $currentPage === 'a_kelas' ? 'active' : '' ?>">
                <i class='bx bxs-school'></i><span>Kelas</span>
            </a>
            <a href="?page=a_mapel"           class="<?= $currentPage === 'a_mapel' ? 'active' : '' ?>">
                <i class='bx bxs-book-open'></i><span>Mata Pelajaran</span>
            </a>
            <a href="?page=a_tahun_ajaran"    class="<?= $currentPage === 'a_tahun_ajaran' ? 'active' : '' ?>">
                <i class='bx bxs-calendar'></i><span>Tahun Ajaran</span>
            </a>

            <div class="nav-label">Manajemen</div>
            <a href="?page=a_penugasan_guru" class="<?= $currentPage === 'a_penugasan_guru' ? 'active' : '' ?>">
                <i class='bx bxs-time-five'></i><span>Penugasan Guru</span>
            </a>
            <a href="?page=a_pengumuman"      class="<?= $currentPage === 'a_pengumuman' ? 'active' : '' ?>">
                <i class='bx bxs-megaphone'></i><span>Pengumuman</span>
            </a>
            <a href="?page=a_laporan"         class="<?= $currentPage === 'a_laporan' ? 'active' : '' ?>">
                <i class='bx bxs-report'></i><span>Laporan</span>
            </a>

            <div class="nav-label">Gamifikasi</div>
            <a href="?page=a_leaderboard"     class="<?= $currentPage === 'a_leaderboard' ? 'active' : '' ?>">
                <i class='bx bxs-trophy'></i><span>Leaderboard Global</span>
            </a>

        <?php elseif ($role === 'guru'): ?>
            <div class="nav-label">Utama</div>
            <a href="?page=dashboard"         class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                <i class='bx bxs-dashboard'></i><span>Dashboard</span>
            </a>

            <div class="nav-label">LMS / E-Learning</div>
            <a href="?page=g_course"          class="<?= $currentPage === 'g_course' ? 'active' : '' ?>">
                <i class='bx bxs-book-content'></i><span>Modul & Silabus</span>
            </a>

            <a href="?page=g_forum"           class="<?= $currentPage === 'g_forum' ? 'active' : '' ?>">
                <i class='bx bxs-chat'></i><span>Forum Diskusi</span>
            </a>

            <div class="nav-label">Gamifikasi</div>
            <a href="?page=g_leaderboard"     class="<?= $currentPage === 'g_leaderboard' ? 'active' : '' ?>">
                <i class='bx bxs-trophy'></i><span>Leaderboard Kelas</span>
            </a>

        <?php elseif ($role === 'siswa'): ?>
            <div class="nav-label">Utama</div>
            <a href="?page=dashboard"         class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                <i class='bx bxs-dashboard'></i><span>Dashboard</span>
            </a>

            <div class="nav-label">E-Learning</div>
            <a href="?page=s_course"          class="<?= $currentPage === 's_course' ? 'active' : '' ?>">
                <i class='bx bxs-book-reader'></i><span>Mulai Belajar</span>
            </a>

            <a href="?page=s_forum"           class="<?= $currentPage === 's_forum' ? 'active' : '' ?>">
                <i class='bx bxs-chat'></i><span>Forum Diskusi</span>
            </a>

            <div class="nav-label">Gamifikasi</div>
            <a href="?page=s_leaderboard"     class="<?= $currentPage === 's_leaderboard' ? 'active' : '' ?>">
                <i class='bx bxs-trophy'></i><span>Leaderboard</span>
            </a>
        <?php endif; ?>

        <!-- Shared — semua role -->
        <div class="nav-label">Akun</div>
        <a href="?page=profil" class="<?= $currentPage === 'profil' ? 'active' : '' ?>">
            <i class='bx bxs-user-circle'></i><span>Profil Saya</span>
        </a>
        <a href="?page=logout" id="btnLogout">
            <i class='bx bx-log-out'></i><span>Keluar</span>
        </a>
    </nav>
</aside>

<!-- Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ════════════════════ MAIN CONTENT ════════════════════ -->
<div class="main-wrapper" id="mainWrapper">

    <!-- ── Topbar ─────────────────────────────────────────── -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="menu-toggle" id="menuToggle" title="Toggle Sidebar">
                <i class='bx bx-menu'></i>
            </button>
            <div class="breadcrumb-area">
                <span class="page-title"><?= e($pageTitle ?? 'Dashboard') ?></span>
            </div>
        </div>

        <div class="topbar-right">
            <!-- Dark Mode Toggle -->
            <button class="icon-btn" id="darkToggle" title="Ganti Tema">
                <i class='bx bx-moon' id="darkIcon"></i>
            </button>

            <!-- Notifikasi -->
            <div class="notif-wrapper" id="notifWrapper">
                <button class="icon-btn" id="notifBtn" title="Notifikasi">
                    <i class='bx bxs-bell'></i>
                    <?php if ($notifCount > 0): ?>
                    <span class="notif-badge"><?= $notifCount ?></span>
                    <?php endif; ?>
                </button>

                <!-- Dropdown Notifikasi -->
                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-header">
                        <strong>🔔 Notifikasi</strong>
                        <?php if ($notifCount > 0): ?>
                        <a href="#" id="markAllRead" class="notif-mark-all">Tandai semua dibaca</a>
                        <?php endif; ?>
                    </div>
                    <div class="notif-list" id="notifList">
                        <!-- Diisi via AJAX oleh main.js -->
                        <div class="notif-loading"><i class='bx bx-loader-alt bx-spin'></i> Memuat...</div>
                    </div>
                </div>
            </div>

            <!-- Avatar / Profil -->
            <a href="?page=profil" class="topbar-avatar" title="Profil Saya">
                <img src="<?= $fotoUrl ?>" alt="<?= e($nama) ?>">
                <span class="topbar-name"><?= e(explode(' ', $nama)[0]) ?></span>
            </a>
        </div>
    </header>

    <!-- ── Flash Message ──────────────────────────────────── -->
    <?php if ($flash): ?>
    <div class="flash-message flash-<?= $flash['type'] ?>" id="flashMsg" role="alert">
        <i class='bx <?= $flash['type'] === 'success' ? 'bx-check-circle' : 'bx-error-circle' ?>'></i>
        <?= e($flash['msg']) ?>
        <button onclick="this.parentElement.remove()" class="flash-close">&times;</button>
    </div>
    <?php endif; ?>

    <!-- ── Konten Halaman ─────────────────────────────────── -->
    <main class="page-content">
        <?= $content ?? '' ?>
    </main>

    <footer class="page-footer">
        <span><?= APP_NAME ?> v<?= APP_VERSION ?> &copy; <?= date('Y') ?></span>
    </footer>
</div>

<script>const BASE_URL = '<?= BASE_URL ?>';</script>
<script src="<?= BASE_URL ?>/public/assets/js/main.js"></script>
</body>
</html>
