<?php
require_once __DIR__ . '/../../../config/env.php';
$authController = new UserController();
$authController->authorize('ADMIN', '/Home');

require_once '../../../config/env.php';

$base = BASE_URL;

if (session_status() === PHP_SESSION_NONE) session_start();

$commentController = new CommentController();
$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$action = $_POST['action'] ?? '';
try {
    if ($action === 'hide' && !empty($_POST['id_cmt'])) {
        $id = intval($_POST['id_cmt']);
        $c = $commentController->getCommentById($id);
        if (!$c) throw new Exception('Comment not found');
        $ok = $commentController->hideComment($id);
        $msg = $ok ? 'Đã ẩn bình luận' : 'Không thể ẩn bình luận';
    } elseif ($action === 'unhide' && !empty($_POST['id_cmt'])) {
        $id = intval($_POST['id_cmt']);
        $ok = $commentController->unhideComment($id);
        $msg = $ok ? 'Đã bỏ ẩn bình luận' : 'Không thể bỏ ẩn bình luận';
    } elseif ($action === 'delete' && !empty($_POST['id_cmt'])) {
        $id = intval($_POST['id_cmt']);
        $commentController->deleteComment($id);
        $msg = 'Đã xoá bình luận';
    } elseif ($action === 'bulk_delete_user' && !empty($_POST['user_id'])) {
        $uid = intval($_POST['user_id']);
        $deleted = $commentController->deleteCommentsByUserId($uid);
        $msg = "Đã xoá $deleted bình luận của người dùng";
    } elseif ($action === 'add' && !empty($_POST['post_id']) && isset($_POST['chat_data'])) {
        $post_id = intval($_POST['post_id']);
        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) throw new Exception('Không có user đang đăng nhập');
        $chat = trim($_POST['chat_data']);
        $commentController->addComment($post_id, $user_id, $chat);
        $msg = 'Đã thêm bình luận';
    } else {
        throw new Exception('Yêu cầu không hợp lệ');
    }
} catch (Exception $ex) {
    $msg = 'Lỗi: ' . $ex->getMessage();
}

$redirect = $_POST['redirect'] ?? ($base . '/admin/posts');
header('Location: ' . $redirect . (strpos($redirect, '?') === false ? '?' : '&') . 'msg=' . urlencode($msg));
exit();

?>
