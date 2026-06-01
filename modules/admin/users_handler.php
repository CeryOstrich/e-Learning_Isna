<?php
/**
 * modules/admin/users_handler.php — Handler aksi CRUD user
 * Login menggunakan NIS/NIP (tanpa email).
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('admin');

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$db     = Database::getInstance();

switch ($action) {

    // ── Tambah User Baru ─────────────────────────────────
    case 'tambah':
        verifyCsrf();
        $nama    = trim($_POST['nama']    ?? '');
        $nis_nip = trim($_POST['nis_nip'] ?? '');
        $no_hp   = trim($_POST['no_hp'] ?? '');
        $pass    = $_POST['password']     ?? '';
        $role    = $_POST['role']         ?? 'siswa';

        if (!$nama || !$nis_nip || !$pass) {
            setFlash('error', 'Nama, NIS/NIP, dan password wajib diisi.');
            redirectTo('index.php?page=a_users');
        }

        if (!in_array($role, ['admin','guru','siswa'])) {
            setFlash('error', 'Role tidak valid.');
            redirectTo('index.php?page=a_users');
        }

        // Cek duplikasi NIS/NIP
        $cek = $db->queryOne("SELECT id FROM users WHERE nis_nip = ?", 's', [$nis_nip]);
        if ($cek) {
            setFlash('error', "NIS/NIP '$nis_nip' sudah terdaftar.");
            redirectTo('index.php?page=a_users');
        }

        $hash = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);
        $db->execute(
            "INSERT INTO users (nama, nis_nip, no_hp, password, role) VALUES (?,?,?,?,?)",
            'sssss', [$nama, $nis_nip, $no_hp, $hash, $role]
        );

        setFlash('success', "User '$nama' berhasil ditambahkan.");
        redirectTo('index.php?page=a_users');
        break;

    // ── Edit User ─────────────────────────────────────────
    case 'edit':
        verifyCsrf();
        $id      = (int)($_POST['id']     ?? 0);
        $nama    = trim($_POST['nama']    ?? '');
        $nis_nip = trim($_POST['nis_nip'] ?? '');
        $no_hp   = trim($_POST['no_hp'] ?? '');
        $role    = $_POST['role']         ?? 'siswa';
        $pass    = $_POST['password']     ?? '';

        if (!$id || !$nama || !$nis_nip) {
            setFlash('error', 'Nama dan NIS/NIP wajib diisi.');
            redirectTo('index.php?page=a_users');
        }

        // Cek duplikasi NIS/NIP (kecuali milik user ini sendiri)
        $cek = $db->queryOne("SELECT id FROM users WHERE nis_nip = ? AND id != ?", 'si', [$nis_nip, $id]);
        if ($cek) {
            setFlash('error', "NIS/NIP '$nis_nip' sudah digunakan user lain.");
            redirectTo('index.php?page=a_users');
        }

        if (!empty($pass)) {
            $hash = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);
            $db->execute(
                "UPDATE users SET nama=?, nis_nip=?, no_hp=?, role=?, password=? WHERE id=?",
                'sssssi', [$nama, $nis_nip, $no_hp, $role, $hash, $id]
            );
        } else {
            $db->execute(
                "UPDATE users SET nama=?, nis_nip=?, no_hp=?, role=? WHERE id=?",
                'ssssi', [$nama, $nis_nip, $no_hp, $role, $id]
            );
        }

        setFlash('success', "Data user '$nama' berhasil diperbarui.");
        redirectTo('index.php?page=a_users');
        break;

    // ── Toggle Aktif/Nonaktif ─────────────────────────────
    case 'toggle':
        $id = (int)($_GET['id'] ?? 0);
        if (!$id || $id == $_SESSION['user_id']) {
            setFlash('error', 'Tidak dapat mengubah status akun Anda sendiri.');
            redirectTo('index.php?page=a_users');
        }

        $user = $db->queryOne("SELECT is_active, nama FROM users WHERE id = ?", 'i', [$id]);
        if (!$user) {
            setFlash('error', 'User tidak ditemukan.');
            redirectTo('index.php?page=a_users');
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        $db->execute("UPDATE users SET is_active = ? WHERE id = ?", 'ii', [$newStatus, $id]);

        $statusLabel = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
        setFlash('success', "Akun '{$user['nama']}' berhasil $statusLabel.");
        redirectTo('index.php?page=a_users');
        break;

    // ── Hapus User ─────────────────────────────────────────
    case 'hapus':
        $id = (int)($_GET['id'] ?? 0);
        if (!$id || $id == $_SESSION['user_id']) {
            setFlash('error', 'Tidak dapat menghapus akun Anda sendiri.');
            redirectTo('index.php?page=a_users');
        }

        $user = $db->queryOne("SELECT nama FROM users WHERE id = ?", 'i', [$id]);
        if (!$user) {
            setFlash('error', 'User tidak ditemukan.');
            redirectTo('index.php?page=a_users');
        }

        $db->execute("DELETE FROM users WHERE id = ?", 'i', [$id]);
        
        setFlash('success', "Akun '{$user['nama']}' beserta seluruh datanya berhasil dihapus permanen.");
        redirectTo('index.php?page=a_users');
        break;

    default:
        redirectTo('index.php?page=a_users');
}
