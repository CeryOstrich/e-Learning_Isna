<?php
require_once __DIR__ . '/database.php';
$res = mysqli_query($conn, "SHOW FULL TABLES IN isnu_db WHERE TABLE_TYPE LIKE 'VIEW'");
while($r = mysqli_fetch_row($res)) {
    print_r($r);
}
?>
