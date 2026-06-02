<?php
require_once __DIR__ . '/../../../config/env.php';


header('Content-Type: application/json');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {
    $comment_id = intval($_POST['comment_id']);
    
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $commentController = new CommentController();
        $is_liked = $commentController->toggleLike($comment_id, $user_id);
        $like_count = $commentController->getLikeCount($comment_id);
        
        echo json_encode(['success' => true, 'is_liked' => $is_liked, 'like_count' => $like_count]);
    } else {
        echo json_encode(['error' => 'Vui lòng đăng nhập để thích bình luận']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
