<?php
/**
 * reset_admin.php
 * Script untuk mereset password admin yang lupa.
 * PENTING: Hapus file ini setelah digunakan!
 */

require_once __DIR__ . '/bootstrap.php';

$db = Database::getInstance();

// Cari user admin
$admin = $db->queryOne("SELECT id, nama FROM users WHERE role = 'admin' LIMIT 1");

if (!$admin) {
    die("<h3>Error: Tidak ada akun dengan role 'admin' di database!</h3>");
}

// Set password baru (misal: admin123)
$password_baru = 'admin123';
$password_hash = password_hash($password_baru, PASSWORD_DEFAULT);

// Update ke database
$db->execute("UPDATE users SET password = ? WHERE id = ?", 'si', [$password_hash, $admin['id']]);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password Admin</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; text-align: center; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; }
        h2 { color: #16a34a; }
        .box { background: white; padding: 20px; border-radius: 8px; margin-top: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .kode { font-size: 24px; font-weight: bold; color: #1a3a6b; background: #f0f4ff; padding: 10px; border-radius: 6px; display: inline-block; margin: 10px 0; letter-spacing: 2px;}
        .warning { margin-top: 25px; font-size: 14px; color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <h2>✅ Berhasil! Password Admin Direset</h2>
    
    <div class="box">
        <p>Akun atas nama: <strong><?= htmlspecialchars($admin['nama']) ?></strong></p>
        <p>Password Anda sekarang (juga berlaku sebagai Kode Unik):</p>
        <div class="kode"><?= $password_baru ?></div>
        
        <br>
        <a href="index.php?page=login" style="display:inline-block; margin-top:15px; padding:10px 20px; background:#1a3a6b; color:white; text-decoration:none; border-radius:6px; font-weight:bold;">Pergi ke Halaman Login</a>
    </div>

    <div class="warning">
        ⚠️ PENTING: Segera hapus file "reset_admin.php" dari folder proyek Anda setelah berhasil login untuk alasan keamanan!
    </div>
</body>
</html>
