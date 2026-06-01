<?php
require_once '../../../controller/CommentController.php';
require_once '../../../config/env.php'; // Load $base từ .env

session_start();

if (!isset($_SESSION['roles']) || !in_array('ADMIN', $_SESSION['roles'])) {
    header('Location: ' . $base . '/admin/comments?error=' . urlencode(__('msg_unauthorized')));
    exit();
}

if (isset($_GET['id'])) {
    $commentId         = intval($_GET['id']);
    $commentController = new CommentController();
    try {
        $commentController->deleteComment($commentId);
        header('Location: ' . $base . '/admin/comments?success=' . urlencode(__('msg_delete_comment_success')));
    } catch (Exception $e) {
        header('Location: ' . $base . '/admin/comments?error=' . urlencode($e->getMessage()));
    }
} else {
    header('Location: ' . $base . '/admin/comments?error=' . urlencode(__('msg_invalid_comment_id')));
}
exit();
?>