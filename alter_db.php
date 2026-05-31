<?php
require_once __DIR__ . '/config/config.php';
$database = new Database();
$db = $database->getConnection();
try {
    $db->exec("ALTER TABLE user ADD COLUMN avatar VARCHAR(255) DEFAULT NULL;");
    echo "Column avatar added successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
