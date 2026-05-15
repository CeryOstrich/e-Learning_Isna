<?php
require_once __DIR__ . '/database.php';
$res = mysqli_query($conn, "SHOW TABLES");
$tables = [];
while($r = mysqli_fetch_row($res)) {
    $tables[] = $r[0];
}
echo "Tables: " . implode(", ", $tables) . "\n";

$res2 = mysqli_query($conn, "SELECT * FROM daftar_siswa LIMIT 1");
if (!$res2) {
    echo "Error querying daftar_siswa: " . mysqli_error($conn) . "\n";
} else {
    echo "daftar_siswa exists.\n";
}
?>
