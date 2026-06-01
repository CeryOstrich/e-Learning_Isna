<?php
/**
 * bootstrap.php — Entry point utama yang me-load semua dependensi inti.
 * Di-include oleh index.php dan semua file router module.
 */

// 1. Load konstanta aplikasi
require_once __DIR__ . '/config/app.php';

// 2. Load semua kelas inti
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/helpers.php';
require_once __DIR__ . '/core/Gamifikasi.php';

// 2b. Auto-migration gamifikasi sudah selesai dan dihapus

// 3. Mulai session yang aman
Auth::startSession();
