<?php
require_once __DIR__ . '/config/database.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Generate hash baru yang valid dari server Anda
$admin_pass = password_hash('Admin@12345', PASSWORD_BCRYPT);
$guru_pass  = password_hash('Guru@12345', PASSWORD_BCRYPT);
$siswa_pass = password_hash('Siswa@12345', PASSWORD_BCRYPT);

// Update langsung ke database isnu_db
$conn->query("UPDATE users SET password='$admin_pass', login_attempts=0, login_locked_until=0 WHERE email='admin@mts.sch.id'");
$conn->query("UPDATE users SET password='$guru_pass', login_attempts=0, login_locked_until=0 WHERE email='guru@mts.sch.id'");
$conn->query("UPDATE users SET password='$siswa_pass', login_attempts=0, login_locked_until=0 WHERE email='siswa@mts.sch.id'");

// Reset status login_locked (jika ada batasan limit)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['login_attempts'] = 0;
$_SESSION['login_locked_until'] = 0;

echo "✅ Berhasil! Password untuk Admin, Guru, dan Siswa telah direset ulang ke default dan akun telah di-unlock.";
