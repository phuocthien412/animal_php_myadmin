<?php
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error   = isset($_GET['error'])   ? $_GET['error']   : '';
require_once __DIR__ . '/../../../config/env.php';
$base = BASE_URL;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>NEKOPARA — <?= __('admin_users') ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
// Header (sidebar + topbar + $base defined)
include '../../headerAdmin.php';

// Controllers
require_once '../../../controller/UserController.php';
$userController = new UserController();

// Fetch data
$allUsers = $userController->getAllUsersWithRoles();
$perPage = 10;
$currentPage = max(1, (int)($_GET['page'] ?? 1));
$totalUsers = count($allUsers);
$totalPages = max(1, (int)ceil($totalUsers / $perPage));
$currentPage = min($currentPage, $totalPages);
$offset = ($currentPage - 1) * $perPage;
$users = array_slice($allUsers, $offset, $perPage);
$isAdmin = isset($_SESSION['roles']) && in_array('ADMIN', $_SESSION['roles']);
?>

<!-- ===================== PAGE HEADER ===================== -->
<div class="page-header">
    <h1><i class="fa-solid fa-users-gear" style="color:var(--green-primary);margin-right:10px;font-size:20px;"></i><?= __('admin_manage_users') ?></h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> <?= __('admin') ?> <span>›</span> <?= __('admin_system') ?> <span>›</span> <?= __('admin_users') ?></div>
</div>

<?php if ($success): ?>
    <div class="alert-admin success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert-admin danger"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- ===================== USER TABLE ===================== -->
<div class="card table-card" style="margin: 0 20px;">
    <div class="card-header">
        <div>
            <div class="card-title"><i class="fa-solid fa-users" style="color:var(--green-primary);margin-right:8px;"></i><?= __('admin_user_list') ?></div>
            <div class="card-subtitle"><?= __('admin_user_desc') ?> <?= $totalUsers ?> <?= mb_strtolower(__('admin_users')) ?></div>
        </div>
    </div>
    <div class="table-toolbar">
        <div class="table-search">
            <i class="fa-solid fa-search"></i>
            <input type="text" id="tableSearchInput" placeholder="<?= __('admin_search_user') ?>" />
        </div>
    </div>
    <div class="table-responsive-wrap">
        <table class="admin-table" id="usersTable">
            <thead>
                <tr>
                    <th>#<?= __('table_id') ?></th>
                    <th><?= __('table_user') ?></th>
                    <th><?= __('table_email') ?></th>
                    <th><?= __('table_role') ?></th>
                    <?php if ($isAdmin): ?><th><?= __('table_action') ?></th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr><td colspan="5">
                    <div class="empty-state">
                        <i class="fa-solid fa-users-slash"></i>
                        <p><?= __('admin_no_users') ?></p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><span style="font-size:12px;color:var(--text-muted);font-weight:500;">#<?= htmlspecialchars($user['id']) ?></span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-initials"><?= strtoupper(mb_substr($user['username'], 0, 1)) ?></div>
                                <span class="user-name-text"><?= htmlspecialchars($user['username']) ?></span>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <?php foreach ($user['roles'] as $role): ?>
                                <?php $rc = in_array(strtolower($role), ['admin','user','mod']) ? strtolower($role) : 'default'; ?>
                                <span class="role-badge <?= $rc ?>"><?= htmlspecialchars($role) ?></span>
                            <?php endforeach; ?>
                            <?php if (empty($user['roles'])): ?><span class="role-badge default">—</span><?php endif; ?>
                        </td>
                        <?php if ($isAdmin): ?>
                        <td>
                            <div class="action-btns">
                                <a href="<?= $base ?>/admin/users/editRole/<?= urlencode($user['id']) ?>"
                                   class="action-btn role" title="<?= __('btn_edit_role') ?>">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </a>
                                <a href="<?= $base ?>/admin/users/delete/<?= urlencode($user['id']) ?>"
                                   class="action-btn delete" title="<?= __('btn_delete_user') ?>"
                                   onclick="return confirm('<?= __('confirm_delete_user') ?>')">
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
                <a class="page-link" href="<?= $base ?>/admin/users?page=<?= max(1, $currentPage - 1) ?>"><?= __('pagination_prev') ?></a>
            </li>
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="<?= $base ?>/admin/users?page=<?= $p ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $base ?>/admin/users?page=<?= min($totalPages, $currentPage + 1) ?>"><?= __('pagination_next') ?></a>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>

<script>
document.getElementById('tableSearchInput')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>

<?php include '../../footerAdmin.php'; ?>
</body>
</html>