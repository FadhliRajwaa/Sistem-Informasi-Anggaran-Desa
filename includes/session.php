<?php
// Custom session handler menggunakan MySQL agar cocok untuk lingkungan serverless (Vercel)
// Tabel: sessions (id PK, data BLOB, expires INT UNSIGNED)

require_once __DIR__ . '/db.php';

class DbSessionHandler implements SessionHandlerInterface {
    private $conn;
    private $table = 'sessions';

    public function __construct($mysqli) {
        $this->conn = $mysqli;
        $this->ensureTable();
    }

    private function ensureTable() {
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id` varchar(128) NOT NULL,
            `data` blob,
            `expires` int unsigned NOT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_expires` (`expires`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
        @$this->conn->query($sql);
    }

    public function open(string $savePath, string $sessionName): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

    public function read(string $id): string|false {
        $now = time();
        $stmt = $this->conn->prepare("SELECT data FROM {$this->table} WHERE id=? AND expires > ? LIMIT 1");
        if (!$stmt) return '';
        $stmt->bind_param('si', $id, $now);
        $stmt->execute();
        $data = '';
        $stmt->bind_result($data);
        if ($stmt->fetch()) {
            $stmt->close();
            return $data ?? '';
        }
        $stmt->close();
        return '';
    }

    public function write(string $id, string $data): bool {
        $expires = time() + (int)ini_get('session.gc_maxlifetime');
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (id, data, expires) VALUES (?,?,?) ON DUPLICATE KEY UPDATE data=VALUES(data), expires=VALUES(expires)");
        if (!$stmt) return false;
        $stmt->bind_param('ssi', $id, $data, $expires);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function destroy(string $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=?");
        if (!$stmt) return false;
        $stmt->bind_param('s', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function gc(int $maxlifetime): int|false {
        $now = time();
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE expires < ?");
        if (!$stmt) return false;
        $stmt->bind_param('i', $now);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok ? 1 : false;
    }
}

// Daftarkan handler hanya sekali
if (!defined('DB_SESSION_REGISTERED')) {
    define('DB_SESSION_REGISTERED', true);

    $handler = new DbSessionHandler($conn);
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_set_save_handler($handler, true);

        // Cookie setting yang aman
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (getenv('SESSION_COOKIE_SECURE') === '1');
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        session_name('TDSESSID');
        session_start();
    }
}
