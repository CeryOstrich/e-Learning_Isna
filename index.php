<?php
/**
 * index.php — Front Controller utama.
 * Semua request masuk melalui sini dan di-routing ke view yang tepat.
 */

require_once __DIR__ . '/bootstrap.php';

// ============================================================
// ROUTING: tentukan halaman yang akan ditampilkan
// ============================================================
$page = $_GET['page'] ?? 'dashboard';

// Halaman login & logout (publik)
if ($page === 'logout') {
    Auth::logout();
}

if ($page === 'login') {
    include __DIR__ . '/views/auth/login.php';
    exit;
}

// ============================================================
// SEMUA HALAMAN DI BAWAH INI BUTUH LOGIN
// ============================================================
Auth::requireLogin();

$role = $_SESSION['role'];

// Peta halaman: 'nama_page' => ['role_diizinkan', 'path/view.php']
$viewMap = [
    // ── SHARED (semua role bisa akses) ─────────────────────
    'profil' => ['any', 'shared/profil.php'],
    'notifikasi' => ['any', 'shared/notifikasi.php'],

    // ── DASHBOARD (tiap role punya file sendiri) ────────────
    'dashboard' => [$role, "$role/dashboard.php"],

    // ── ADMIN ───────────────────────────────────────────────
    'a_users' => ['admin', 'admin/users.php'],
    'a_kelas' => ['admin', 'admin/kelas.php'],
    'a_kelas_detail' => ['admin', 'admin/kelas_detail.php'],
    'a_mapel' => ['admin', 'admin/mapel.php'],
    'a_tahun_ajaran' => ['admin', 'admin/tahun_ajaran.php'],
    'a_penugasan_guru' => ['admin', 'admin/jadwal_mengajar.php'],
    'a_pengumuman' => ['admin', 'admin/pengumuman.php'],
    'a_laporan' => ['admin', 'admin/laporan.php'],
    'a_wali_kelas' => ['admin', 'admin/wali_kelas.php'],
    'a_leaderboard' => ['admin', 'admin/leaderboard.php'], // ✔ Gamifikasi Admin

    // ── GURU ────────────────────────────────────────────────
    'g_course' => ['guru', 'guru/course_manager.php'],
    'g_builder_materi' => ['guru', 'guru/builder_materi.php'],
    'g_builder_kuis' => ['guru', 'guru/builder_kuis.php'],
    'g_forum' => ['guru', 'guru/forum.php'],
    'g_tugas' => ['guru', 'guru/tugas.php'],
    'g_tugas_koreksi' => ['guru', 'guru/tugas_koreksi.php'],
    'g_koreksi_kuis' => ['guru', 'guru/koreksi_kuis.php'],
    'g_nilai' => ['guru', 'guru/nilai.php'],
    'g_leaderboard' => ['guru', 'guru/leaderboard.php'],  // ✔ Gamifikasi Guru

    // ── SISWA ───────────────────────────────────────────────
    's_course' => ['siswa', 'siswa/my_courses.php'],
    's_belajar' => ['siswa', 'siswa/belajar.php'],
    's_forum' => ['siswa', 'siswa/forum.php'],
    's_tugas' => ['siswa', 'siswa/tugas.php'],
    's_ujian' => ['siswa', 'siswa/ujian.php'],
    's_ujian_kerjakan' => ['siswa', 'siswa/ujian_kerjakan.php'],
    's_leaderboard' => ['siswa', 'siswa/leaderboard.php'],  // ✔ Gamifikasi
];

// Resolve routing
if (array_key_exists($page, $viewMap)) {
    [$allowedRole, $viewFile] = $viewMap[$page];

    // Cek akses role
    if ($allowedRole !== 'any' && $allowedRole !== $role) {
        http_response_code(403);
        include __DIR__ . '/views/errors/403.php';
        exit;
    }

    $viewPath = __DIR__ . '/views/' . $viewFile;
    if (file_exists($viewPath)) {
        include $viewPath;
    } else {
        http_response_code(404);
        include __DIR__ . '/views/errors/404.php';
    }
} else {
    // Halaman tidak dikenali → tampilkan dashboard sesuai role
    header('Location: ' . BASE_URL . '/index.php?page=dashboard');
    exit;
}