<?php

$userController = new UserController();
$userController->authorize('ADMIN', '/Login');

if (!isset($_GET['id'])) {
    $userController->redirect('/admin/users', 'msg_missing_user_id', 'error');
}

$userId = $_GET['id'];

if ($userController->deleteUser($userId)) {
    $userController->redirect('/admin/users', 'msg_delete_user_success', 'success');
} else {
    $userController->redirect('/admin/users', 'msg_delete_user_fail', 'error');
}
?>