<?php

$postController = new PostController();
$postController->authorize('ADMIN', '/admin/posts');

if (isset($_GET['id'])) {
    $postId = intval($_GET['id']);
    try {
        $postController->deletePost($postId);
        $postController->redirect('/admin/posts', 'msg_delete_post_success', 'success');
    } catch (Exception $e) {
        $postController->redirect('/admin/posts', $e->getMessage(), 'error');
    }
} else {
    $postController->redirect('/admin/posts', 'msg_invalid_post_id', 'error');
}
?>