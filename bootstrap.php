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

// 2b. Auto-migration gamifikasi (aman dijalankan berulang)
require_once __DIR__ . '/db_migrate_gamifikasi.php';

// 3. Mulai session yang aman
Auth::startSession();
