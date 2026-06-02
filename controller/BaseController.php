<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../lib/i18n.php';

abstract class BaseController {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Helper for successful JSON API responses
     */
    protected function jsonSuccess($data = null, $message = '') {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'data' => $data,
            'message' => $message
        ]);
        exit();
    }

    /**
     * Helper for failed JSON API responses
     */
    protected function jsonError($message = '', $statusCode = 400) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
        exit();
    }

    /**
     * Helper to verify if the user has a specific role, redirecting on failure
     */
     public function authorize($role, $redirectPath = '/Login', $errorMessageKey = 'msg_unauthorized') {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['roles']) || !in_array($role, $_SESSION['roles'])) {
            header('Location: ' . BASE_URL . $redirectPath);
            exit();
        }
    }

    /**
     * Redirect with a translated flash message
     */
    public function redirect(string $path, string $messageKey, string $type = 'success') {
        $message = __($messageKey);
        header('Location: ' . BASE_URL . $path . '?' . $type . '=' . urlencode($message));
        exit();
    }
}
?>
