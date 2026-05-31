<?php
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error   = isset($_GET['error'])   ? $_GET['error']   : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>NEKOPARA — Tài khoản</title>
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
$users = $userController->getAllUsersWithRoles();
$isAdmin = isset($_SESSION['roles']) && in_array('ADMIN', $_SESSION['roles']);
?>

<!-- ===================== PAGE HEADER ===================== -->
<div class="page-header">
    <h1><i class="fa-solid fa-users-gear" style="color:var(--green-primary);margin-right:10px;font-size:20px;"></i>Quản lý Tài khoản</h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> Admin <span>›</span> Hệ thống <span>›</span> Tài khoản</div>
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
            <div class="card-title"><i class="fa-solid fa-users" style="color:var(--green-primary);margin-right:8px;"></i>Danh sách tài khoản</div>
            <div class="card-subtitle">Quản lý người dùng và phân quyền</div>
        </div>
    </div>
    <div class="table-toolbar">
        <div class="table-search">
            <i class="fa-solid fa-search"></i>
            <input type="text" id="tableSearchInput" placeholder="Tìm tên, email..." />
        </div>
    </div>
    <div class="table-responsive-wrap">
        <table class="admin-table" id="usersTable">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Người dùng</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <?php if ($isAdmin): ?><th>Hành động</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr><td colspan="5">
                    <div class="empty-state">
                        <i class="fa-solid fa-users-slash"></i>
                        <p>Chưa có người dùng nào</p>
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
                                   class="action-btn role" title="Chỉnh quyền">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </a>
                                <a href="<?= $base ?>/admin/users/delete/<?= urlencode($user['id']) ?>"
                                   class="action-btn delete" title="Xoá tài khoản"
                                   onclick="return confirm('Bạn có chắc muốn xoá tài khoản này?')">
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