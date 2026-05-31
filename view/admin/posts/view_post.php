<?php
require_once '../../../controller/PostController.php';
require_once '../../../controller/UserController.php';

$postController = new PostController();
$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $postId = intval($_GET['id']);
    $post = $postController->getPostById($postId);
    if (!$post) {
        die("Post not found.");
    }
    $username = $userController->getUsernameById($post['user_id']);
} else {
    die("Invalid request.");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEKOPARA — Chi tiết bài viết</title>
</head>
<body>
    <?php include '../../headerAdmin.php'; ?>
    <div class="page-header">
        <h1><i class="fa-solid fa-eye" style="color:var(--accent-orange);margin-right:10px;font-size:20px;"></i>Chi tiết bài viết</h1>
        <div class="breadcrumb-text">NEKOPARA <span>›</span> Admin <span>›</span> Bài viết <span>›</span> Chi tiết</div>
    </div>
    
    <div class="card" style="padding: 20px; max-width: 900px; margin: 0 auto;">
        <h2 class="mt-2"><?= htmlspecialchars($post['title']) ?></h2>
        
        <div class="mb-4" style="color: var(--text-muted); font-size: 14px;">
            <i class="fa-solid fa-user"></i> Đăng bởi: <strong><?= htmlspecialchars($username ?? 'Unknown') ?></strong>
            <span style="margin: 0 10px;">|</span>
            <i class="fa-regular fa-calendar"></i> Ngày đăng: <?= htmlspecialchars($post['date'] ?? '—') ?>
        </div>

        <?php if (!empty($post['image'])): ?>
            <div class="text-center mb-4">
                <img src="<?= $base ?>/images/<?= htmlspecialchars($post['image']) ?>" alt="Post Image" style="max-width: 100%; border-radius: 10px; max-height: 400px; object-fit: cover;">
            </div>
        <?php endif; ?>

        <div class="post-content" style="font-size: 16px; line-height: 1.6; color: var(--text-primary);">
            <?= nl2br(htmlspecialchars($post['content'] ?? '')) ?>
        </div>
        
        <div class="mt-5" style="text-align: right; border-top: 1px solid var(--border-light); padding-top: 15px;">
            <a href="<?= $base ?>/admin/posts" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Quay lại danh sách</a>
        </div>
    </div>
    <?php include '../../footerAdmin.php'; ?>
</body>
</html>
