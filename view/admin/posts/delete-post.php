<?php
require_once '../../../controller/PostController.php';
require_once '../../../config/env.php'; // Load $base từ .env

session_start();

if (!isset($_SESSION['roles']) || !in_array('ADMIN', $_SESSION['roles'])) {
    header('Location: ' . $base . '/admin/posts?error=Unauthorized');
    exit();
}

if (isset($_GET['id'])) {
    $postId         = intval($_GET['id']);
    $postController = new PostController();
    try {
        $postController->deletePost($postId);
        header('Location: ' . $base . '/admin/posts?success=Xoá+bài+viết+thành+công');
    } catch (Exception $e) {
        header('Location: ' . $base . '/admin/posts?error=' . urlencode($e->getMessage()));
    }
} else {
    header('Location: ' . $base . '/admin/posts?error=Invalid+post+ID');
}
exit();
?>