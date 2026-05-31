<?php
session_start();
require_once '../../controller/UserController.php';
require_once '../../config/env.php'; // Load $base từ .env

$userController = new UserController();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$user = $userController->loginUser($username, $password);

if ($user) {
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['roles']    = $user['roles'];
    header('Location: ' . $base . '/Home');
} else {
    $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không đúng!';
    header('Location: ' . $base . '/Login');
}
exit();
?>