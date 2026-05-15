<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/database.php';

$constraints_to_fix = [
    ['table' => 'presences', 'constraint' => 'presences_user_id_foreign'],
    ['table' => 'permissions', 'constraint' => 'permissions_user_id_foreign'],
    ['table' => 'surats', 'constraint' => 'surats_user_id_foreign'],
    ['table' => 'riwayat_surats', 'constraint' => 'riwayat_surats_ibfk_3']
];

foreach ($constraints_to_fix as $c) {
    $table = $c['table'];
    $constraint = $c['constraint'];
    
    // Drop existing constraint
    $drop = mysqli_query($conn, "ALTER TABLE `$table` DROP FOREIGN KEY `$constraint`");
    if (!$drop) {
        echo "Error dropping $constraint from $table: " . mysqli_error($conn) . "<br>";
    } else {
        echo "Dropped $constraint from $table.<br>";
        
        // Re-add with CASCADE
        $add = mysqli_query($conn, "ALTER TABLE `$table` ADD CONSTRAINT `$constraint` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE");
        if (!$add) {
            echo "Error adding $constraint to $table: " . mysqli_error($conn) . "<br>";
        } else {
            echo "Added $constraint to $table with CASCADE.<br>";
        }
    }
}
?>
