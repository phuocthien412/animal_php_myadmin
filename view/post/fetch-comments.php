<?php
require_once '../../controller/CommentController.php';
require_once '../../controller/UserController.php';

header('Content-Type: application/json');

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

    // Add usernames and likes to the comments
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    foreach ($comments as $key => $comment) {
        $comments[$key]['username'] = $userController->getUsernameById($comment['user_id']);
        $comments[$key]['likes_count'] = $commentController->getLikeCount($comment['id_cmt']);
        $comments[$key]['is_liked'] = $commentController->isLikedByUser($comment['id_cmt'], $user_id);
    }

    // Return comments as JSON
    echo json_encode($comments);
} else {
    echo json_encode(['error' => 'Invalid post ID']);
}
?>