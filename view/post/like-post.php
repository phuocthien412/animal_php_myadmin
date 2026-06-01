<?php
require_once __DIR__ . '/../../config/env.php';


header('Content-Type: application/json');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);
    
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $postController = new PostController();
        $is_liked = $postController->toggleLike($post_id, $user_id);
        $like_count = $postController->getLikeCount($post_id);
        
        echo json_encode(['success' => true, 'is_liked' => $is_liked, 'like_count' => $like_count]);
    } else {
        echo json_encode(['error' => 'Vui lòng đăng nhập để thích bài viết']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
