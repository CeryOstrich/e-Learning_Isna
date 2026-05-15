-- ============================================================
-- PENTING: Jalankan script ini di phpMyAdmin untuk:
-- 1. Mengisi NIS/NIP default bagi user yang belum punya
-- 2. Membuat akun admin bisa login
-- 3. Melihat NIS/NIP semua user yang ada
-- ============================================================

-- Langkah 1: Lihat semua user dan status NIS/NIP-nya
SELECT id, nama, role, nis_nip, is_active FROM users ORDER BY role;

-- Langkah 2: Isi NIS/NIP otomatis untuk user yang masih kosong
-- Format: role singkat + ID (misal: ADMIN001, GURU002, SISWA003)
UPDATE users 
SET nis_nip = CONCAT(
    CASE role 
        WHEN 'admin' THEN 'ADMIN'
        WHEN 'guru'  THEN 'GURU'
        WHEN 'siswa' THEN 'SISWA'
        ELSE 'USER'
    END, 
    LPAD(id, 3, '0')
)
WHERE nis_nip IS NULL OR nis_nip = '';

-- Langkah 3: Verifikasi hasil — lihat NIS/NIP yang baru diisi
SELECT id, nama, role, nis_nip FROM users ORDER BY role;

-- ============================================================
-- Setelah menjalankan script ini:
-- - Admin login dengan NIS/NIP: ADMIN001 (ganti angka sesuai ID admin)
-- - Password: sama seperti sebelumnya (tidak berubah)
-- ============================================================
