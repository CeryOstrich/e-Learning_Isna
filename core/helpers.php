<?php
/**
 * helpers.php — Fungsi-fungsi utilitas global.
 * Di-include oleh bootstrap.php sehingga tersedia di seluruh proyek.
 */

/**
 * Sanitasi output untuk mencegah XSS.
 * Selalu gunakan fungsi ini saat menampilkan data dari user/database ke HTML.
 */
function e(string|null $str): string
{
    return htmlspecialchars((string) $str, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect ke URL tertentu dan hentikan eksekusi.
 */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/**
 * Redirect ke URL relatif dari BASE_URL.
 */
function redirectTo(string $path): void
{
    redirect(BASE_URL . '/' . ltrim($path, '/'));
}

/**
 * Simpan pesan flash ke session (tampil sekali, lalu hilang).
 *
 * @param string $type  'success' | 'error' | 'warning' | 'info'
 * @param string $msg   Isi pesan
 */
function setFlash(string $type, string $msg): void
{
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

/**
 * Ambil dan hapus pesan flash dari session.
 * Kembalikan null jika tidak ada.
 */
function getFlash(): array|null
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Validasi & upload file dengan aman.
 *
 * @param array  $file       Elemen dari $_FILES['nama_input']
 * @param string $destDir    Direktori tujuan (gunakan konstanta UPLOAD_*)
 * @param array  $allowedExt Array ekstensi yang diizinkan
 * @return string|false      Nama file jika berhasil, false jika gagal
 */
function uploadFile(array $file, string $destDir, array $allowedExt = ALLOWED_FILE_EXT): string|false
{
    if ($file['error'] !== UPLOAD_ERR_OK) return false;
    if ($file['size'] > MAX_FILE_SIZE) return false;

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) return false;

    // Verifikasi MIME type (anti spoof ekstensi)
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    $allowedMimes = [
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx'=> 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx'=> 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'xls' => 'application/vnd.ms-excel',
        'xlsx'=> 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'jpg' => 'image/jpeg',
        'jpeg'=> 'image/jpeg',
        'png' => 'image/png',
        'webp'=> 'image/webp',
    ];

    if (isset($allowedMimes[$ext]) && $mimeType !== $allowedMimes[$ext]) {
        return false;
    }

    // Nama file: hash unik agar tidak bisa ditebak/overwrite
    if (!is_dir($destDir)) mkdir($destDir, 0755, true);
    $newName = bin2hex(random_bytes(16)) . '.' . $ext;
    $destPath = rtrim($destDir, '/') . '/' . $newName;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) return false;

    return $newName;
}

/**
 * Format tanggal ke format Indonesia (contoh: "Selasa, 06 Mei 2026").
 */
function formatTanggal(string $date): string
{
    $hari  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
               'Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($date);
    return $hari[date('w', $ts)] . ', ' . date('d', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

/**
 * Hitung nilai akhir rapor (formula standar MTs Kemenag):
 * 30% Harian + 30% UTS + 40% UAS
 */
function hitungNilaiAkhir(float $harian, float $uts, float $uas): float
{
    return round(($harian * 0.30) + ($uts * 0.30) + ($uas * 0.40), 2);
}

/**
 * Konversi nilai angka ke predikat huruf.
 */
function nilaiKePredikat(float $nilai): string
{
    if ($nilai >= 90) return 'A';
    if ($nilai >= 75) return 'B';
    if ($nilai >= 60) return 'C';
    return 'D';
}

/**
 * Generate CSRF Token dan simpan ke session.
 * Gunakan di setiap form.
 */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifikasi CSRF Token dari form POST.
 * Jika tidak valid, hentikan eksekusi.
 */
function verifyCsrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        die('Token tidak valid. Silakan refresh halaman dan coba lagi.');
    }
    // Regenerate token setelah terpakai
    unset($_SESSION['csrf_token']);
}
