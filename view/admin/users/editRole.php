<?php
require_once '../../../controller/UserController.php';
require_once '../../../controller/RoleController.php';
require_once '../../../config/env.php'; // Load $base từ .env

// session already started in headerAdmin
if (session_status() === PHP_SESSION_NONE) session_start();

// Auth check
if (!isset($_SESSION['roles']) || !in_array('ADMIN', $_SESSION['roles'])) {
    header("Location: " . $base . "/admin/users?error=Unauthorized+access");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: " . $base . "/admin/users?error=Missing+user+ID");
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
        header("Location: " . $base . "/admin/users?success=Cập+nhật+quyền+thành+công");
    } else {
        header("Location: " . $base . "/admin/users?error=Cập+nhật+thất+bại");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>NEKOPARA — Chỉnh quyền người dùng</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .role-form-card { max-width: 520px; }
        .role-option-wrap {
            display: flex; flex-direction: column; gap: 10px; margin-top: 8px;
        }
        .role-option {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 16px;
            border: 1.5px solid var(--border-medium);
            border-radius: 10px;
            cursor: pointer; transition: all .15s;
        }
        .role-option:has(input:checked) {
            border-color: var(--green-primary);
            background: var(--green-light);
        }
        .role-option input[type="checkbox"] {
            width: 17px; height: 17px; cursor: pointer;
            accent-color: var(--green-primary);
        }
        .role-option-name { font-size: 14px; font-weight: 600; }
    </style>
</head>
<body>
<?php include '../../headerAdmin.php'; ?>

<div class="page-header">
    <h1><i class="fa-solid fa-shield-halved" style="color:var(--accent-purple);margin-right:10px;font-size:20px;"></i>Chỉnh quyền người dùng</h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> Admin <span>›</span> Tài khoản <span>›</span> Chỉnh quyền</div>
</div>

<div class="card role-form-card">
    <div class="card-header">
        <div>
            <div class="card-title">
                <i class="fa-solid fa-user-shield" style="color:var(--accent-purple);margin-right:8px;"></i>
                <?= htmlspecialchars($user['username']) ?>
            </div>
            <div class="card-subtitle">Chọn các quyền bạn muốn cấp cho tài khoản này</div>
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
                <button type="submit" class="btn-admin btn-admin-primary">
                    <i class="fa-solid fa-check"></i> Lưu quyền
                </button>
                <a href="<?= $base ?>/admin/users" class="btn-admin btn-admin-outline">
                    <i class="fa-solid fa-arrow-left"></i> Huỷ
                </a>
            </div>
        </form>
    </div>
</div>

<?php include '../../footerAdmin.php'; ?>
</body>
</html>