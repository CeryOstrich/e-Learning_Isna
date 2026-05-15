<?php
/**
 * modules/file_server.php
 * Menyajikan file dari folder uploads/ dengan aman.
 * Hanya user yang sudah login yang bisa mengakses file.
 */

require_once __DIR__ . '/../bootstrap.php';

// Hanya user yang sudah login
Auth::requireLogin();

$type   = $_GET['type'] ?? '';   // 'materi' | 'tugas' | 'profil'
$file   = $_GET['file'] ?? '';
$inline = $_GET['inline'] ?? '0'; // '1' = tampilkan di browser, '0' = force download

// Whitelist tipe direktori yang diizinkan
$dirMap = [
    'materi' => UPLOAD_MATERI,
    'tugas'  => UPLOAD_TUGAS,
    'profil' => UPLOAD_PROFIL,
];

if (!array_key_exists($type, $dirMap)) {
    http_response_code(400);
    die('Tipe file tidak valid.');
}

// Sanitasi nama file: larang traversal (../ dll)
$file = basename($file);
if (empty($file)) {
    http_response_code(400);
    die('Nama file tidak valid.');
}

$fullPath = rtrim($dirMap[$type], '/') . '/' . $file;

if (!file_exists($fullPath) || !is_file($fullPath)) {
    http_response_code(404);
    die('File tidak ditemukan.');
}

// Deteksi MIME type secara akurat
$finfo    = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($fullPath);

// Tentukan apakah ditampilkan inline atau di-download
$disposition = ($inline === '1') ? 'inline' : 'attachment';

// Kirim header
header('Content-Type: ' . $mimeType);
header('Content-Disposition: ' . $disposition . '; filename="' . rawurlencode($file) . '"');
header('Content-Length: ' . filesize($fullPath));
header('Cache-Control: private, max-age=3600');
header('X-Content-Type-Options: nosniff');

// Kirim isi file
readfile($fullPath);
exit;
