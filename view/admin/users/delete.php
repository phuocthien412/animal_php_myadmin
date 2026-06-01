<?php
require_once '../../../controller/UserController.php';
require_once '../../../config/env.php'; // Load $base từ .env

session_start();

// Chỉ ADMIN mới được xoá
if (!isset($_SESSION['roles']) || !in_array('ADMIN', $_SESSION['roles'])) {
    header("Location: " . $base . "/admin/users?error=" . urlencode(__('msg_unauthorized')));
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: " . $base . "/admin/users?error=" . urlencode(__('msg_missing_user_id')));
    exit();
}

$userId         = $_GET['id'];
$userController = new UserController();

if ($userController->deleteUser($userId)) {
    header("Location: " . $base . "/admin/users?success=" . urlencode(__('msg_delete_user_success')));
} else {
    header("Location: " . $base . "/admin/users?error=" . urlencode(__('msg_delete_user_fail')));
}
exit();
?>