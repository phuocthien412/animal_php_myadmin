<?php
require_once __DIR__ . '/../../../config/env.php';
$authController = new UserController();
$authController->authorize('ADMIN', '/Login');

$base = BASE_URL;

if (session_status() === PHP_SESSION_NONE) session_start();

$commentController = new CommentController();
$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$action = $_POST['action'] ?? '';
$redirect = $_POST['redirect'] ?? ($base . '/admin/posts');

function customRedirect($url, $messageKey, $type = 'success', $replacement = null) {
    $msg = __($messageKey);
    if ($replacement !== null) {
        $msg = sprintf($msg, $replacement);
    }
    header('Location: ' . $url . (strpos($url, '?') === false ? '?' : '&') . $type . '=' . urlencode($msg));
    exit();
}

try {
    if ($action === 'hide' && !empty($_POST['id_cmt'])) {
        $id = intval($_POST['id_cmt']);
        $c = $commentController->getCommentById($id);
        if (!$c) throw new Exception('msg_invalid_comment_id');
        $ok = $commentController->hideComment($id);
        if ($ok) {
            customRedirect($redirect, 'msg_hide_comment_success', 'success');
        } else {
            customRedirect($redirect, 'msg_hide_comment_fail', 'error');
        }
    } elseif ($action === 'unhide' && !empty($_POST['id_cmt'])) {
        $id = intval($_POST['id_cmt']);
        $c = $commentController->getCommentById($id);
        if (!$c) throw new Exception('msg_invalid_comment_id');
        $ok = $commentController->unhideComment($id);
        if ($ok) {
            customRedirect($redirect, 'msg_unhide_comment_success', 'success');
        } else {
            customRedirect($redirect, 'msg_unhide_comment_fail', 'error');
        }
    } elseif ($action === 'delete' && !empty($_POST['id_cmt'])) {
        $id = intval($_POST['id_cmt']);
        $commentController->deleteComment($id);
        customRedirect($redirect, 'msg_delete_comment_success', 'success');
    } elseif ($action === 'bulk_delete_user' && !empty($_POST['user_id'])) {
        $uid = intval($_POST['user_id']);
        $deleted = $commentController->deleteCommentsByUserId($uid);
        customRedirect($redirect, 'msg_bulk_delete_comment_success', 'success', $deleted);
    } elseif ($action === 'add' && !empty($_POST['post_id']) && isset($_POST['chat_data'])) {
        $post_id = intval($_POST['post_id']);
        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) throw new Exception('msg_no_logged_in_user');
        $chat = trim($_POST['chat_data']);
        $commentController->addComment($post_id, $user_id, $chat);
        customRedirect($redirect, 'msg_add_comment_success', 'success');
    } else {
        throw new Exception('msg_invalid_request');
    }
} catch (Exception $ex) {
    customRedirect($redirect, $ex->getMessage(), 'error');
}
?>
