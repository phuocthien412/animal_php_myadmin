<?php
require_once __DIR__ . '/../../../config/env.php';

header('Content-Type: application/json');
error_reporting(0);
ob_clean();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the post ID from the request
$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if ($post_id > 0) {
    $commentController = new CommentController();
    $userController = new UserController();

    // Fetch comments for the post
    $comments = $commentController->getCommentsByPostId($post_id);
    if (!is_array($comments)) {
        $comments = [];
    }

    // Add usernames, likes, and translation to the comments
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    foreach ($comments as $key => $comment) {
        $comments[$key]['username'] = $userController->getUsernameById($comment['user_id']);
        $comments[$key]['likes_count'] = $commentController->getLikeCount($comment['id_cmt']);
        $comments[$key]['is_liked'] = $commentController->isLikedByUser($comment['id_cmt'], $user_id);
        $comments[$key]['chat_data'] = __($comment['chat_data']);
    }

    // Return comments as JSON
    echo json_encode($comments);
} else {
    echo json_encode(['error' => 'Invalid post ID']);
}
?>