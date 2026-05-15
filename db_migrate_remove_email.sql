-- ============================================================
-- Script untuk menghapus kolom email dari tabel users
-- Jalankan query ini di phpMyAdmin atau MySQL client
-- ============================================================

-- 1. Pastikan NIS/NIP semua user sudah terisi sebelum menjalankan ini
--    (cek dengan query berikut):
SELECT id, nama, role, nis_nip FROM users WHERE nis_nip IS NULL OR nis_nip = '';

-- 2. Hapus kolom email dari tabel users
ALTER TABLE users DROP COLUMN email;

-- 3. Pastikan kolom nis_nip UNIQUE dan NOT NULL (login identifier)
ALTER TABLE users MODIFY COLUMN nis_nip VARCHAR(30) NOT NULL;
ALTER TABLE users ADD UNIQUE INDEX idx_nis_nip (nis_nip);

-- ============================================================
-- Selesai! Kolom email sudah dihapus dari database.
-- Login sekarang menggunakan NIS/NIP + Password.
-- ============================================================
