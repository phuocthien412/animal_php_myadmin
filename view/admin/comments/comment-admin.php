<?php
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error   = isset($_GET['error'])   ? $_GET['error']   : '';
require_once __DIR__ . '/../../../config/env.php';
$base = BASE_URL;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>NEKOPARA — Quản lý bình luận</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
include '../../headerAdmin.php';

require_once '../../../controller/CommentController.php';
require_once '../../../controller/UserController.php';
require_once '../../../controller/PostController.php';

$commentController = new CommentController();
$userController    = new UserController();
$postController    = new PostController();
$allComments = $commentController->getAllComments();
$perPage = 10;
$currentPage = max(1, (int)($_GET['page'] ?? 1));
$totalComments = count($allComments);
$totalPages = max(1, (int)ceil($totalComments / $perPage));
$currentPage = min($currentPage, $totalPages);
$offset = ($currentPage - 1) * $perPage;
$comments = array_slice($allComments, $offset, $perPage);
$isAdmin  = isset($_SESSION['roles']) && in_array('ADMIN', $_SESSION['roles']);
?>

<div class="page-header">
    <h1><i class="fa-solid fa-comments" style="color:var(--accent-purple);margin-right:10px;font-size:20px;"></i>Quản lý bình luận</h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> Admin <span>›</span> Bình luận</div>
</div>

<?php if ($success): ?>
    <div class="alert-admin success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert-admin danger"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card table-card">
    <div class="card-header">
        <div>
            <div class="card-title"><i class="fa-solid fa-comments" style="color:var(--accent-purple);margin-right:8px;"></i>Danh sách bình luận</div>
            <div class="card-subtitle">Tổng cộng <?= $totalComments ?> bình luận trong hệ thống</div>
        </div>
    </div>
    <div class="table-toolbar">
        <div class="table-search">
            <i class="fa-solid fa-search"></i>
            <input type="text" id="commentSearch" placeholder="Tìm nội dung bình luận..." />
        </div>
    </div>
    <div class="table-responsive-wrap">
        <table class="admin-table" id="commentsTable">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Người bình luận</th>
                    <th>Bài viết #</th>
                    <th>Nội dung</th>
                    <th>Thời gian</th>
                    <?php if ($isAdmin): ?><th>Hành động</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($comments)): ?>
                <tr><td colspan="6">
                    <div class="empty-state">
                        <i class="fa-solid fa-comments-slash"></i>
                        <p>Chưa có bình luận nào</p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><span style="font-size:12px;color:var(--text-muted);font-weight:500;">#<?= htmlspecialchars($comment['id_cmt']) ?></span></td>
                    <td>
                        <?php $uname = $userController->getUsernameById($comment['user_id']); ?>
                        <div class="user-cell" style="gap:7px;">
                            <div class="user-initials" style="width:28px;height:28px;font-size:11px;">
                                <?= strtoupper(mb_substr($uname ?? 'U', 0, 1)) ?>
                            </div>
                            <?= htmlspecialchars($uname ?? '#'.$comment['user_id']) ?>
                        </div>
                    </td>
                    <td>
                        <span class="role-badge default">Bài #<?= htmlspecialchars($comment['post_id']) ?></span>
                    </td>
                    <td style="max-width:260px;">
                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:13px;">
                            <?= htmlspecialchars($comment['chat_data']) ?>
                        </div>
                    </td>
                    <td style="font-size:13px;color:var(--text-muted);white-space:nowrap;">
                        <i class="fa-regular fa-clock" style="margin-right:4px;"></i>
                        <?= htmlspecialchars($comment['date_time'] ?? '—') ?>
                    </td>
                    <?php if ($isAdmin): ?>
                    <td>
                        <div class="action-btns">
                            <a href="<?= $base ?>/view/admin/comments/delete-comment.php?id=<?= urlencode($comment['id_cmt']) ?>"
                               class="action-btn delete" title="Xoá bình luận"
                               onclick="return confirm('Bạn có chắc muốn xoá bình luận này?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($totalPages > 1): ?>
<div class="admin-pagination">
    <nav aria-label="Pagination">
        <ul class="pagination">
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $base ?>/admin/comments?page=<?= max(1, $currentPage - 1) ?>">Trước</a>
            </li>
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="<?= $base ?>/admin/comments?page=<?= $p ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $base ?>/admin/comments?page=<?= min($totalPages, $currentPage + 1) ?>">Sau</a>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>

<script>
document.getElementById('commentSearch')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#commentsTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>

<?php include '../../footerAdmin.php'; ?>
</body>
</html>