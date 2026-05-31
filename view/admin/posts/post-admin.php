<?php
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error   = isset($_GET['error'])   ? $_GET['error']   : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>NEKOPARA — Quản lý bài viết</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
include '../../headerAdmin.php';

require_once '../../../controller/PostController.php';
require_once '../../../controller/UserController.php';

$postController = new PostController();
$userController = new UserController();
$posts   = $postController->getAllPosts();
$isAdmin = isset($_SESSION['roles']) && in_array('ADMIN', $_SESSION['roles']);
?>

<div class="page-header">
    <h1><i class="fa-solid fa-newspaper" style="color:var(--accent-orange);margin-right:10px;font-size:20px;"></i>Quản lý bài viết</h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> Admin <span>›</span> Bài viết</div>
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
            <div class="card-title"><i class="fa-solid fa-newspaper" style="color:var(--accent-orange);margin-right:8px;"></i>Danh sách bài viết</div>
            <div class="card-subtitle">Tổng cộng <?= count($posts) ?> bài viết trong cộng đồng</div>
        </div>
    </div>
    <div class="table-toolbar">
        <div class="table-search">
            <i class="fa-solid fa-search"></i>
            <input type="text" id="postSearch" placeholder="Tìm tiêu đề bài viết..." />
        </div>
    </div>
    <div class="table-responsive-wrap">
        <table class="admin-table" id="postsTable">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Ảnh bìa</th>
                    <th>Tiêu đề</th>
                    <th>Tác giả</th>
                    <th>Ngày đăng</th>
                    <?php if ($isAdmin): ?><th>Hành động</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($posts)): ?>
                <tr><td colspan="6">
                    <div class="empty-state">
                        <i class="fa-solid fa-newspaper"></i>
                        <p>Chưa có bài viết nào</p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($posts as $post): ?>
                <tr>
                    <td><span style="font-size:12px;color:var(--text-muted);font-weight:500;">#<?= htmlspecialchars($post['id_post']) ?></span></td>
                    <td class="animal-img-cell">
                        <?php if (!empty($post['image'])): ?>
                        <img src="<?= $base ?>/images/<?= htmlspecialchars($post['image']) ?>"
                             alt="Post" style="width:60px;height:44px;object-fit:cover;border-radius:8px;">
                        <?php else: ?>
                        <div style="width:60px;height:44px;background:var(--border-light);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><strong style="font-size:13.5px;"><?= htmlspecialchars($post['title']) ?></strong></td>
                    <td>
                        <?php $username = $userController->getUsernameById($post['user_id']); ?>
                        <div class="user-cell" style="gap:7px;">
                            <div class="user-initials" style="width:28px;height:28px;font-size:11px;">
                                <?= strtoupper(mb_substr($username ?? 'U', 0, 1)) ?>
                            </div>
                            <?= htmlspecialchars($username ?? 'Unknown') ?>
                        </div>
                    </td>
                    <td style="font-size:13px;color:var(--text-muted);">
                        <i class="fa-regular fa-calendar" style="margin-right:4px;"></i>
                        <?= htmlspecialchars($post['date'] ?? '—') ?>
                    </td>
                    <?php if ($isAdmin): ?>
                    <td>
                        <div class="action-btns">
                            <a href="<?= $base ?>/admin/posts/detail/<?= urlencode($post['id_post']) ?>"
                               class="action-btn view" title="Xem bài viết">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="<?= $base ?>/view/admin/posts/delete-post.php?id=<?= urlencode($post['id_post']) ?>"
                               class="action-btn delete" title="Xoá bài viết"
                               onclick="return confirm('Bạn có chắc muốn xoá bài viết này?')">
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

<script>
document.getElementById('postSearch')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#postsTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>

<?php include '../../footerAdmin.php'; ?>
</body>
</html>