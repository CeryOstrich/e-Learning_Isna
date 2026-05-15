<?php
/**
 * modules/auth/login_handler.php
 * Handler POST untuk proses login menggunakan NIS/NIP.
 */

require_once __DIR__ . '/../../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

header('Content-Type: application/json');

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($_POST['admin_code']) && (empty($username) || empty($password))) {
    echo json_encode(['success' => false, 'message' => 'Username dan password wajib diisi.']);
    exit;
}

// ── Proses Login Admin via Kode Unik ──────────────────────────────────────────
if (!empty($_POST['admin_code'])) {
    $adminCode = trim($_POST['admin_code']);
    $db = Database::getInstance();

    $admin = $db->queryOne(
        "SELECT id, nama, password, role, foto_profil, is_active
         FROM users WHERE role = 'admin' AND is_active = 1 LIMIT 1"
    );

    if (!$admin || !password_verify($adminCode, $admin['password'])) {
        echo json_encode(['success' => false, 'message' => 'Kode unik admin salah.']);
        exit;
    }

    Auth::setSession($admin);
    $db->execute("UPDATE users SET last_login = NOW() WHERE id = ?", 'i', [$admin['id']]);

    echo json_encode([
        'success'  => true,
        'message'  => 'Login admin berhasil!',
        'redirect' => BASE_URL . '/index.php?page=dashboard'
    ]);
    exit;
}

// ── Brute-force protection ────────────────────────────────────────────────────
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts']     = 0;
    $_SESSION['login_locked_until'] = 0;
}

if ($_SESSION['login_locked_until'] > time()) {
    $sisa = ceil(($_SESSION['login_locked_until'] - time()) / 60);
    echo json_encode([
        'success' => false,
        'message' => "Terlalu banyak percobaan login. Coba lagi dalam {$sisa} menit."
    ]);
    exit;
}

// ── Query user berdasarkan NIS/NIP ────────────────────────────────────────────
$db   = Database::getInstance();
$user = $db->queryOne(
    "SELECT id, nama, password, role, foto_profil, is_active
     FROM users WHERE nis_nip = ? LIMIT 1",
    's',
    [$username]
);

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['login_attempts']++;

    if ($_SESSION['login_attempts'] >= 5) {
        $_SESSION['login_locked_until'] = time() + (10 * 60);
        $_SESSION['login_attempts']     = 0;
        echo json_encode([
            'success' => false,
            'message' => 'Terlalu banyak percobaan gagal. Akun dikunci 10 menit.'
        ]);
        exit;
    }

    $sisa_coba = 5 - $_SESSION['login_attempts'];
    echo json_encode([
        'success' => false,
        'message' => "NIS/NIP atau password salah. Sisa percobaan: {$sisa_coba}x."
    ]);
    exit;
}

if (!$user['is_active']) {
    echo json_encode([
        'success' => false,
        'message' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.'
    ]);
    exit;
}

$_SESSION['login_attempts']     = 0;
$_SESSION['login_locked_until'] = 0;

Auth::setSession($user);

$db->execute("UPDATE users SET last_login = NOW() WHERE id = ?", 'i', [$user['id']]);

echo json_encode([
    'success'  => true,
    'message'  => 'Login berhasil! Mengalihkan...',
    'redirect' => BASE_URL . '/index.php?page=dashboard'
]);
exit;
