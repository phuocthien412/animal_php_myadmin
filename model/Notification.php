<?php
require_once __DIR__ . '/../config/config.php';

class Notification {
    private static ?PDO $db = null;

    private static function connection(): ?PDO {
        if (self::$db instanceof PDO) {
            return self::$db;
        }

        try {
            $database = new Database();
            self::$db = $database->getConnection();
            if (self::$db instanceof PDO) {
                self::ensureTable();
            }
        } catch (Throwable $e) {
            self::$db = null;
        }

        return self::$db;
    }

    private static function ensureSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
    }

    public static function ensureTable(): void {
        $db = self::$db;
        if (!$db instanceof PDO) {
            return;
        }

        try {
            $db->exec("CREATE TABLE IF NOT EXISTS notifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                type VARCHAR(50) NOT NULL,
                action VARCHAR(100) NOT NULL,
                title VARCHAR(255) NOT NULL,
                message TEXT NULL,
                link VARCHAR(255) NULL,
                target_type VARCHAR(50) NULL,
                target_id VARCHAR(50) NULL,
                actor_id INT NULL,
                actor_name VARCHAR(255) NULL,
                actor_roles VARCHAR(255) NULL,
                meta_json LONGTEXT NULL,
                is_read TINYINT(1) NOT NULL DEFAULT 0,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_notifications_created_at (created_at),
                INDEX idx_notifications_is_read (is_read)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } catch (Throwable $e) {
            // ignore table creation issues so main action still succeeds
        }
    }

    public static function record(array $data): bool {
        $db = self::connection();
        if (!$db instanceof PDO) {
            return false;
        }

        self::ensureSession();

        $actorId = $data['actor_id'] ?? ($_SESSION['user_id'] ?? null);
        $actorName = $data['actor_name'] ?? ($_SESSION['username'] ?? 'Hệ thống');
        $actorRoles = $data['actor_roles'] ?? ((isset($_SESSION['roles']) && is_array($_SESSION['roles'])) ? implode(',', $_SESSION['roles']) : null);
        $meta = $data['meta'] ?? [];

        $payload = [
            'type' => $data['type'] ?? 'general',
            'action' => $data['action'] ?? 'Đã cập nhật',
            'title' => $data['title'] ?? 'Thông báo hệ thống',
            'message' => $data['message'] ?? '',
            'link' => $data['link'] ?? '/admin/notifications/',
            'target_type' => $data['target_type'] ?? null,
            'target_id' => isset($data['target_id']) ? (string)$data['target_id'] : null,
            'actor_id' => $actorId !== null ? (int)$actorId : null,
            'actor_name' => $actorName,
            'actor_roles' => $actorRoles,
            'meta_json' => !empty($meta) ? json_encode($meta, JSON_UNESCAPED_UNICODE) : null,
        ];

        try {
            $stmt = $db->prepare("INSERT INTO notifications
                (type, action, title, message, link, target_type, target_id, actor_id, actor_name, actor_roles, meta_json, is_read, created_at)
                VALUES
                (:type, :action, :title, :message, :link, :target_type, :target_id, :actor_id, :actor_name, :actor_roles, :meta_json, 0, NOW())");
            return $stmt->execute($payload);
        } catch (Throwable $e) {
            return false;
        }
    }

    public static function getRecent(int $limit = 5): array {
        $db = self::connection();
        if (!$db instanceof PDO) {
            return [];
        }

        $limit = max(1, $limit);
        try {
            $stmt = $db->query("SELECT * FROM notifications ORDER BY id DESC LIMIT " . (int)$limit);
            return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (Throwable $e) {
            return [];
        }
    }

    public static function getAll(int $limit = 100): array {
        return self::getRecent($limit);
    }

    public static function getUnreadCount(): int {
        $db = self::connection();
        if (!$db instanceof PDO) {
            return 0;
        }

        try {
            $stmt = $db->query("SELECT COUNT(*) FROM notifications WHERE is_read = 0");
            return (int)($stmt ? $stmt->fetchColumn() : 0);
        } catch (Throwable $e) {
            return 0;
        }
    }

    public static function markAllAsRead(): void {
        $db = self::connection();
        if (!$db instanceof PDO) {
            return;
        }

        try {
            $db->exec("UPDATE notifications SET is_read = 1 WHERE is_read = 0");
        } catch (Throwable $e) {
            // ignore
        }
    }
}
?>