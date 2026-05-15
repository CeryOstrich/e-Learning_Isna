<?php
/**
 * Auth.php — Helper fungsi autentikasi & otorisasi.
 * Mengelola session, cek login, cek role, dan dual-role wali kelas.
 */

class Auth
{
    /**
     * Mulai session dengan konfigurasi yang aman.
     * Wajib dipanggil di paling atas setiap file entry point.
     */
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,          // Tutup browser = session mati
                'path'     => '/',
                'secure'   => false,      // Set true jika pakai HTTPS
                'httponly' => true,        // Cegah akses cookie via JavaScript
                'samesite' => 'Strict',   // Proteksi CSRF
            ]);
            session_start();
        }
    }

    /**
     * Cek apakah user sudah login.
     * Jika belum, redirect ke halaman login.
     */
    public static function requireLogin(): void
    {
        self::startSession();
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }
    }

    /**
     * Cek apakah user memiliki role tertentu.
     * Jika tidak, redirect ke halaman 403.
     *
     * @param string|array $roles Role yang diizinkan ('admin', 'guru', 'siswa' atau array campuran)
     */
    public static function requireRole(string|array $roles): void
    {
        self::requireLogin();
        $allowed = (array) $roles;
        if (!in_array($_SESSION['role'], $allowed, true)) {
            http_response_code(403);
            include ROOT_PATH . '/views/errors/403.php';
            exit;
        }
    }

    /**
     * Kembalikan data user yang sedang login dari session.
     */
    public static function user(): array|null
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Cek apakah guru yang login adalah Wali Kelas pada TA aktif.
     * Hasilnya disimpan di session agar tidak query berulang.
     *
     * @return array|null  Data wali_kelas jika ya, null jika tidak
     */
    public static function getWaliKelas(): array|null
    {
        // Jika sudah dicek sebelumnya, ambil dari cache session
        if (isset($_SESSION['is_wali_kelas'])) {
            return $_SESSION['wali_kelas_data'] ?? null;
        }

        if (($_SESSION['role'] ?? '') !== 'guru') {
            $_SESSION['is_wali_kelas']   = false;
            $_SESSION['wali_kelas_data'] = null;
            return null;
        }

        $db = Database::getInstance();
        $wk = $db->queryOne(
            "SELECT wk.*, k.nama_kelas, ta.nama AS nama_ta
             FROM wali_kelas wk
             JOIN kelas k ON k.id = wk.kelas_id
             JOIN tahun_ajaran ta ON ta.id = wk.tahun_ajaran_id
             WHERE wk.guru_id = ? AND ta.is_aktif = 1
             LIMIT 1",
            'i',
            [$_SESSION['user_id']]
        );

        $_SESSION['is_wali_kelas']   = ($wk !== null);
        $_SESSION['wali_kelas_data'] = $wk;

        return $wk;
    }

    /**
     * Simpan data user ke session setelah login berhasil.
     */
    public static function setSession(array $user): void
    {
        session_regenerate_id(true); // Cegah Session Fixation Attack

        $_SESSION['user_id']        = $user['id'];
        $_SESSION['role']           = $user['role'];
        $_SESSION['nama']           = $user['nama'];
        $_SESSION['foto_profil']    = $user['foto_profil'] ?? null;
        $_SESSION['user']           = $user;

        // Reset cache wali kelas agar di-query ulang
        unset($_SESSION['is_wali_kelas'], $_SESSION['wali_kelas_data']);
    }

    /**
     * Hapus session dan redirect ke login (Logout).
     */
    public static function logout(): void
    {
        self::startSession();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        header('Location: ' . BASE_URL . '/index.php?page=login');
        exit;
    }
}
