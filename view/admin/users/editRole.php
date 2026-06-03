<?php
require_once __DIR__ . '/../../../config/env.php';
$authController = new UserController();
$authController->authorize('ADMIN', '/Login');

require_once '../../../config/env.php'; // Load $base từ .env

// session already started in headerAdmin
if (session_status() === PHP_SESSION_NONE) session_start();

// Auth check
if (!isset($_SESSION['roles']) || !in_array('ADMIN', $_SESSION['roles'])) {
    header("Location: " . $base . "/admin/users?error=" . urlencode(__('msg_unauthorized')));
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: " . $base . "/admin/users?error=" . urlencode(__('msg_missing_user_id')));
    exit();
}

$userId         = $_GET['id'];
$userController = new UserController();
$roleController = new RoleController();

$user  = $userController->getUserById($userId);
$roles = $roleController->getAllRoles();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedRoles = $_POST['roles'] ?? [];
    if ($userController->updateUserRoles($userId, $selectedRoles)) {
        header("Location: " . $base . "/admin/users?success=" . urlencode(__('msg_update_role_success')));
    } else {
        header("Location: " . $base . "/admin/users?error=" . urlencode(__('msg_update_fail')));
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>NEKOPARA — <?= __('admin_edit_user_role') ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $base ?>/css/admin/users.css">
</head>
<body>
<?php include '../../headerAdmin.php'; ?>

<div class="page-header">
    <h1><i class="fa-solid fa-shield-halved" style="color:var(--accent-purple);margin-right:10px;font-size:20px;"></i><?= __('admin_edit_user_role') ?></h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> <?= __('admin') ?> <span>›</span> <?= __('admin_users') ?> <span>›</span> <?= __('admin_edit_role') ?></div>
</div>

<div class="card role-form-card">
    <div class="card-header">
        <div>
            <div class="card-title">
                <i class="fa-solid fa-user-shield" style="color:var(--accent-purple);margin-right:8px;"></i>
                <?= htmlspecialchars($user['username']) ?>
            </div>
            <div class="card-subtitle"><?= __('admin_edit_role_desc') ?></div>
        </div>
    </div>
    <div class="card-body">
        <form action="<?= $base ?>/admin/users/editRole/<?= urlencode($userId) ?>" method="POST">
            <div class="role-option-wrap">
                <?php foreach ($roles as $role): ?>
                <label class="role-option">
                    <input
                        type="checkbox"
                        name="roles[]"
                        value="<?= htmlspecialchars($role['id']) ?>"
                        <?= in_array($role['name'], $user['roles']) ? 'checked' : '' ?>
                    >
                    <div>
                        <div class="role-option-name"><?= htmlspecialchars($role['name']) ?></div>
                        <div style="font-size:12px;color:var(--text-muted);">ID: <?= htmlspecialchars($role['id']) ?></div>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>

            <div style="display:flex;gap:10px;margin-top:24px;">
                <button type="submit" class="btn-admin btn-admin-primary" data-confirm="Xác nhận thay đổi quyền của người dùng này?" data-confirm-title="Lưu thay đổi" data-confirm-type="warning">
                    <i class="fa-solid fa-check"></i> <?= __('btn_save_role') ?>
                </button>
                <a href="<?= $base ?>/admin/users" class="btn-admin btn-admin-outline">
                    <i class="fa-solid fa-arrow-left"></i> <?= __('btn_cancel') ?>
                </a>
            </div>
        </form>
    </div>
</div>

<?php include '../../footerAdmin.php'; ?>
</body>
</html>