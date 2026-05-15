<?php
// File sementara — hapus setelah digunakan!
// Buka di browser: http://localhost/Isnun/generate_hash.php

$passwords = [
    'Admin@12345'  => 'admin',
    'Guru@12345'   => 'guru',
    'Siswa@12345'  => 'siswa',
];

echo '<pre style="font-family:monospace;font-size:14px;padding:20px;">';
echo "=== HASH YANG BENAR UNTUK DIPAKAI DI SQL ===\n\n";

foreach ($passwords as $plain => $role) {
    $hash = password_hash($plain, PASSWORD_BCRYPT, ['cost' => 12]);
    echo "Role   : $role\n";
    echo "Plain  : $plain\n";
    echo "Hash   : $hash\n";
    echo "Verify : " . (password_verify($plain, $hash) ? "✅ VALID" : "❌ INVALID") . "\n";
    echo "\n";
}

// Langsung cetak query INSERT siap pakai
echo "\n=== QUERY SQL SIAP PAKAI ===\n\n";
echo "INSERT INTO \`users\` (\`nama\`, \`email\`, \`password\`, \`role\`, \`is_active\`) VALUES\n";

$rows = [];
$data = [
    ['Administrator', 'admin@mts.sch.id', 'Admin@12345', 'admin'],
    ['Guru Contoh',   'guru@mts.sch.id',  'Guru@12345',  'guru'],
    ['Siswa Contoh',  'siswa@mts.sch.id', 'Siswa@12345', 'siswa'],
];

foreach ($data as $d) {
    $hash = password_hash($d[2], PASSWORD_BCRYPT, ['cost' => 12]);
    $rows[] = "('{$d[0]}', '{$d[1]}', '{$hash}', '{$d[3]}', 1)";
}

echo implode(",\n", $rows) . ";\n";

echo '</pre>';
echo '<p style="color:red;font-weight:bold;padding:20px;">⚠️ HAPUS FILE INI SETELAH SELESAI: generate_hash.php</p>';
