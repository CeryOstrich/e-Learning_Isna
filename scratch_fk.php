<?php
require_once __DIR__ . '/database.php';
$res = mysqli_query($conn, "SELECT CONSTRAINT_NAME, TABLE_NAME, DELETE_RULE FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE REFERENCED_TABLE_NAME = 'users' AND CONSTRAINT_SCHEMA = 'isnu_db'");
while($r = mysqli_fetch_assoc($res)) {
    print_r($r);
}
?>
