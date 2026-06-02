<?php

$commentController = new CommentController();
$commentController->authorize('ADMIN', '/Login');

if (isset($_GET['id'])) {
    $commentId = intval($_GET['id']);
    try {
        $commentController->deleteComment($commentId);
        $commentController->redirect('/admin/comments', 'msg_delete_comment_success', 'success');
    } catch (Exception $e) {
        $commentController->redirect('/admin/comments', $e->getMessage(), 'error');
    }
} else {
    $commentController->redirect('/admin/comments', 'msg_invalid_comment_id', 'error');
}
?>