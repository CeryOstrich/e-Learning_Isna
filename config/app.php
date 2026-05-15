<?php
/**
 * Konstanta umum aplikasi.
 */

define('APP_NAME',    'E-Learning MTs');
define('APP_VERSION', '1.0.0');
define('BASE_URL',    'http://localhost/Isnun'); // Sesuaikan dengan domain Anda

// Path absolut dari root proyek
define('ROOT_PATH',   dirname(__DIR__));
define('UPLOAD_MATERI', ROOT_PATH . '/uploads/materi/');
define('UPLOAD_TUGAS',  ROOT_PATH . '/uploads/tugas/');
define('UPLOAD_PROFIL', ROOT_PATH . '/uploads/profil/');

// Ekstensi file yang diizinkan
define('ALLOWED_DOC_EXT',  ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx']);
define('ALLOWED_IMG_EXT',  ['jpg', 'jpeg', 'png', 'webp']);
define('ALLOWED_FILE_EXT', array_merge(ALLOWED_DOC_EXT, ALLOWED_IMG_EXT));

// Ukuran file maks upload (10 MB)
define('MAX_FILE_SIZE', 10 * 1024 * 1024);

// Timezone
date_default_timezone_set('Asia/Makassar');
