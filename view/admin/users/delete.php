<?php
require_once '../../../controller/UserController.php';
require_once '../../../config/env.php'; // Load $base từ .env

session_start();

// Chỉ ADMIN mới được xoá
if (!isset($_SESSION['roles']) || !in_array('ADMIN', $_SESSION['roles'])) {
    header("Location: " . $base . "/admin/users?error=Unauthorized+access");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: " . $base . "/admin/users?error=Missing+user+ID");
    exit();
}

$userId         = $_GET['id'];
$userController = new UserController();

if ($userController->deleteUser($userId)) {
    header("Location: " . $base . "/admin/users?success=Xoá+tài+khoản+thành+công");
} else {
    header("Location: " . $base . "/admin/users?error=Xoá+tài+khoản+thất+bại");
}
exit();
?>