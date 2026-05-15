<?php
/**
 * Database.php — Singleton wrapper untuk koneksi MySQLi.
 * Cara pakai: $db = Database::getInstance();
 *             $stmt = $db->prepare("SELECT ...");
 */

require_once ROOT_PATH . '/config/database.php';

class Database
{
    private static ?Database $instance = null;
    private mysqli $conn;

    private function __construct()
    {
        // Membuat koneksi MySQLi
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            // Jangan tampilkan detail error ke user di production
            error_log('Database connection failed: ' . $this->conn->connect_error);
            http_response_code(503);
            die('Layanan tidak tersedia. Silakan coba lagi nanti.');
        }

        // Set charset untuk mendukung karakter Arab & Unicode
        $this->conn->set_charset(DB_CHARSET);
    }

    /** Mengembalikan satu-satunya instance Database (Singleton Pattern) */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /** Akses langsung ke objek MySQLi */
    public function getConn(): mysqli
    {
        return $this->conn;
    }

    /**
     * Prepared Statement helper — mencegah SQL Injection.
     * Contoh: $stmt = $db->prepare("SELECT * FROM users WHERE email = ?", 's', [$email]);
     *
     * @param string $sql    Query dengan placeholder (?)
     * @param string $types  Tipe data: 's'=string, 'i'=integer, 'd'=double, 'b'=blob
     * @param array  $params Array nilai sesuai urutan placeholder
     */
    public function prepare(string $sql, string $types = '', array $params = []): mysqli_stmt|false
    {
        $stmt = $this->conn->prepare($sql);

        if ($stmt && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        return $stmt;
    }

    /**
     * Eksekusi SELECT dan kembalikan semua baris sebagai array asosiatif.
     */
    public function queryAll(string $sql, string $types = '', array $params = []): array
    {
        $stmt = $this->prepare($sql, $types, $params);
        if (!$stmt) return [];
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Eksekusi SELECT dan kembalikan hanya satu baris (LIMIT 1).
     */
    public function queryOne(string $sql, string $types = '', array $params = []): array|null
    {
        $stmt = $this->prepare($sql, $types, $params);
        if (!$stmt) return null;
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

    /**
     * Eksekusi INSERT/UPDATE/DELETE. Kembalikan ID terakhir untuk INSERT.
     */
    public function execute(string $sql, string $types = '', array $params = []): int|bool
    {
        $stmt = $this->prepare($sql, $types, $params);
        if (!$stmt) return false;
        $ok = $stmt->execute();
        return $ok ? ($this->conn->insert_id ?: true) : false;
    }

    // Cegah clone & unserialize (Singleton)
    private function __clone() {}
    public function __wakeup() { throw new \Exception("Singleton tidak bisa di-unserialize."); }
}
