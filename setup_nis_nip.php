<?php
/**
 * setup_nis_nip.php
 * Script SATU KALI untuk mengisi NIS/NIP default semua user.
 * Hapus file ini setelah dijalankan!
 * 
 * Jalankan: http://localhost/Isnun/setup_nis_nip.php
 */

require_once __DIR__ . '/bootstrap.php';

$db = Database::getInstance();

// Ambil semua user yang NIS/NIP-nya kosong
$users = $db->queryAll("SELECT id, nama, role, nis_nip FROM users ORDER BY role, id");

$updated = 0;
$skipped = 0;
$results = [];

foreach ($users as $u) {
    if (!empty($u['nis_nip'])) {
        $results[] = ['nama' => $u['nama'], 'role' => $u['role'], 'nis_nip' => $u['nis_nip'], 'status' => 'sudah ada'];
        $skipped++;
        continue;
    }

    // Generate NIS/NIP: prefix role + ID 3 digit
    $prefix = match($u['role']) {
        'admin' => 'ADMIN',
        'guru'  => 'GURU',
        'siswa' => 'SISWA',
        default => 'USER',
    };
    $newNisNip = $prefix . str_pad($u['id'], 3, '0', STR_PAD_LEFT);

    $db->execute("UPDATE users SET nis_nip = ? WHERE id = ?", 'si', [$newNisNip, $u['id']]);
    $results[] = ['nama' => $u['nama'], 'role' => $u['role'], 'nis_nip' => $newNisNip, 'status' => 'diupdate'];
    $updated++;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Setup NIS/NIP</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; background: #f5f5f5; }
        h1 { color: #1a3a6b; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px 14px; border: 1px solid #ddd; text-align: left; }
        th { background: #1a3a6b; color: white; }
        .diupdate { background: #dcfce7; }
        .sudah { background: #f0f4ff; }
        .summary { padding: 15px; background: #1a3a6b; color: white; border-radius: 8px; margin-bottom: 20px; }
        .warning { padding: 15px; background: #fee2e2; border-radius: 8px; margin-top: 20px; border: 1px solid #dc2626; color: #dc2626; }
    </style>
</head>
<body>
    <h1>🔧 Setup NIS/NIP Pengguna</h1>
    
    <div class="summary">
        <strong><?= $updated ?> user diupdate</strong> | <?= $skipped ?> user sudah memiliki NIS/NIP
    </div>

    <table>
        <thead>
            <tr><th>Nama</th><th>Role</th><th>NIS/NIP (Login)</th><th>Status</th></tr>
        </thead>
        <tbody>
            <?php foreach ($results as $r): ?>
            <tr class="<?= $r['status'] === 'diupdate' ? 'diupdate' : 'sudah' ?>">
                <td><?= htmlspecialchars($r['nama']) ?></td>
                <td><?= htmlspecialchars($r['role']) ?></td>
                <td><strong><?= htmlspecialchars($r['nis_nip']) ?></strong></td>
                <td><?= $r['status'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="warning">
        ⚠️ <strong>PENTING:</strong> Hapus file <code>setup_nis_nip.php</code> setelah selesai menggunakan ini!
        <br>Gunakan NIS/NIP di atas untuk login. Password tidak berubah.
    </div>
</body>
</html>
