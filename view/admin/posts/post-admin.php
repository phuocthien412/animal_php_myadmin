<?php
require_once __DIR__ . '/../../../config/env.php';
$authController = new UserController();
$authController->authorize('ADMIN', '/Home');

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error   = isset($_GET['error'])   ? $_GET['error']   : '';
require_once __DIR__ . '/../../../config/env.php';
$base = BASE_URL;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>NEKOPARA — <?= __('admin_manage_posts') ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
include '../../headerAdmin.php';


$postController = new PostController();
$userController = new UserController();
$allPosts = $postController->getAllPosts();
$perPage = 10;
$currentPage = max(1, (int)($_GET['page'] ?? 1));
$totalPosts = count($allPosts);
$totalPages = max(1, (int)ceil($totalPosts / $perPage));
$currentPage = min($currentPage, $totalPages);
$offset = ($currentPage - 1) * $perPage;
$posts = array_slice($allPosts, $offset, $perPage);
$isAdmin = isset($_SESSION['roles']) && in_array('ADMIN', $_SESSION['roles']);
?>

<div class="page-header">
    <h1><i class="fa-solid fa-newspaper" style="color:var(--accent-orange);margin-right:10px;font-size:20px;"></i><?= __('admin_manage_posts') ?></h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> <?= __('admin') ?> <span>›</span> <?= __('admin_posts') ?></div>
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
            <div class="card-title"><i class="fa-solid fa-newspaper" style="color:var(--accent-orange);margin-right:8px;"></i><?= __('admin_post_list') ?></div>
            <div class="card-subtitle"><?= sprintf(__('admin_post_desc'), $totalPosts) ?></div>
        </div>
    </div>
    <div class="table-toolbar">
        <div class="table-search">
            <i class="fa-solid fa-search"></i>
            <input type="text" id="postSearch" placeholder="<?= __('admin_search_post') ?>" />
        </div>
    </div>
    <div class="table-responsive-wrap">
        <table class="admin-table" id="postsTable">
            <thead>
                <tr>
                    <th>#<?= __('table_id') ?></th>
                    <th><?= __('table_cover_image') ?></th>
                    <th><?= __('table_title') ?></th>
                    <th><?= __('table_author') ?></th>
                    <th><?= __('table_date_posted') ?></th>
                    <?php if ($isAdmin): ?><th><?= __('table_action') ?></th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($posts)): ?>
                <tr><td colspan="6">
                    <div class="empty-state">
                        <i class="fa-solid fa-newspaper"></i>
                        <p><?= __('admin_no_posts') ?></p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($posts as $post): ?>
                <tr onclick="if(window.getSelection().toString().length === 0 && !event.target.closest('.action-btns')) window.location='<?= $base ?>/admin/posts/detail/<?= urlencode($post['id_post']) ?>'" style="cursor: pointer;" title="Nhấn vào để xem chi tiết">
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

                            <a href="<?= $base ?>/view/admin/posts/delete-post.php?id=<?= urlencode($post['id_post']) ?>"
                               class="action-btn delete" title="<?= __('btn_delete_post') ?>"
                               onclick="return confirm('<?= __('confirm_delete_post') ?>')">
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
                <a class="page-link" href="<?= $base ?>/admin/posts?page=<?= max(1, $currentPage - 1) ?>"><?= __('pagination_prev') ?></a>
            </li>
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="<?= $base ?>/admin/posts?page=<?= $p ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $base ?>/admin/posts?page=<?= min($totalPages, $currentPage + 1) ?>"><?= __('pagination_next') ?></a>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>

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