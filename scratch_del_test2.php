<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/database.php';

mysqli_begin_transaction($conn);
$result = mysqli_query($conn, "SELECT id, role FROM users WHERE role = 'siswa' LIMIT 1");
if ($row = mysqli_fetch_assoc($result)) {
    $id = $row['id'];
    $del = mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");
    if (!$del) {
        echo "Error deleting siswa $id: " . mysqli_error($conn) . "\n";
    } else {
        echo "Successfully deleted siswa $id.\n";
    }
}

$result2 = mysqli_query($conn, "SELECT id, role FROM users WHERE role = 'guru' LIMIT 1");
if ($row = mysqli_fetch_assoc($result2)) {
    $id = $row['id'];
    $del = mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");
    if (!$del) {
        echo "Error deleting guru $id: " . mysqli_error($conn) . "\n";
    } else {
        echo "Successfully deleted guru $id.\n";
    }
}

mysqli_rollback($conn);
?>
