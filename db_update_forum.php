<?php
require_once __DIR__ . '/config/database.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 1. Add parent_id to forum_reply if it doesn't exist
$check_col = $conn->query("SHOW COLUMNS FROM forum_reply LIKE 'parent_id'");
if ($check_col->num_rows == 0) {
    $conn->query("ALTER TABLE forum_reply ADD COLUMN parent_id INT(11) NULL DEFAULT NULL AFTER thread_id");
    $conn->query("ALTER TABLE forum_reply ADD CONSTRAINT fk_reply_parent FOREIGN KEY (parent_id) REFERENCES forum_reply(id) ON DELETE CASCADE");
    echo "Kolom parent_id ditambahkan.\n";
} else {
    echo "Kolom parent_id sudah ada.\n";
}

// 2. Create forum_reaction table
$conn->query("CREATE TABLE IF NOT EXISTS `forum_reaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reply_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reaction_type` varchar(20) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_reaction` (`reply_id`,`user_id`),
  CONSTRAINT `fk_reaction_reply` FOREIGN KEY (`reply_id`) REFERENCES `forum_reply` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reaction_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo "Tabel forum_reaction siap.\n";
?>
