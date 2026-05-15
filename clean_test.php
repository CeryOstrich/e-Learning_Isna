<?php
require_once __DIR__ . '/bootstrap.php';
$db = Database::getInstance();
$db->execute("DELETE FROM users WHERE nis_nip='999888777'");
echo "Cleaned up.";
