<?php
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/components/file_uploader.php';
require_once __DIR__ . '/components/file_validator.php';
$authController = new UserController();
$authController->authorize('ADMIN', '/Login');

if (!isset($_SESSION['username'])) {
    header("Location: /animal_php_myadmin/animal_php_myadmin/Login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController = new UserController();

    if (isset($_FILES['avatar_file'])) {
        $uploadDir = __DIR__ . '/../../images/';
        
        if ($_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
            // Tái sử dụng file_validator để kiểm tra dung lượng tệp
            $validationResult = validateUploadedFiles($_FILES, 10 * 1024 * 1024, true);
            if ($validationResult !== true) {
                header("Location: " . $base . "/admin/profile?error=" . urlencode($validationResult));
                exit();
            }

            $safeName = generateSafeFilename($_FILES['avatar_file']['name']);
            move_uploaded_file($_FILES['avatar_file']['tmp_name'], $uploadDir . $safeName);
            
            if (isset($_SESSION['user_id'])) {
                $userController->updateUserAvatar($_SESSION['user_id'], $safeName);
                $_SESSION['avatar'] = $safeName;
                header("Location: " . $base . "/admin/profile?success=" . urlencode(__('profile_update_success')));
                exit();
            } else {
                header("Location: " . $base . "/admin/profile?error=" . urlencode(__('profile_error_no_id')));
                exit();
            }
        } else {
            header("Location: " . $base . "/admin/profile?error=" . urlencode(__('profile_error_upload')));
            exit();
        }
    } elseif (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $current = $_POST['current_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];
        
        if ($new !== $confirm) {
            header("Location: " . $base . "/admin/profile?error=" . urlencode(__('profile_error_password_mismatch')));
            exit();
        } else {
            if ($userController->updatePassword($_SESSION['user_id'], $current, $new)) {
                header("Location: " . $base . "/admin/profile?success=" . urlencode(__('profile_password_success')));
                exit();
            } else {
                header("Location: " . $base . "/admin/profile?error=" . urlencode(__('profile_error_wrong_password')));
                exit();
            }
        }
    }
}

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>NEKOPARA — <?= __('profile_title') ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php include '../headerAdmin.php'; ?>

<div class="page-header">
    <h1><i class="fa-solid fa-id-badge" style="color:var(--green-primary);margin-right:10px;font-size:20px;"></i><?= __('profile_title') ?></h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> <?= __('admin') ?> <span>›</span> <?= __('profile') ?></div>
</div>



<div class="container-fluid" style="padding: 0 20px;">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card text-center" style="padding: 30px;">
                <?php 
                $profileAvatar = isset($_SESSION['avatar']) && !empty($_SESSION['avatar']) ? $base . '/images/' . htmlspecialchars($_SESSION['avatar']) : null;
                if ($profileAvatar): 
                ?>
                    <img src="<?= $profileAvatar ?>" alt="Avatar" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #eee; margin: 0 auto 20px;">
                <?php else: ?>
                    <div style="width: 120px; height: 120px; background: var(--green-primary); color: white; border-radius: 50%; font-size: 50px; font-weight: bold; line-height: 120px; margin: 0 auto 20px;">
                        <?= strtoupper(mb_substr($_SESSION['username'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                
                <h3 style="margin-bottom: 5px;"><?= htmlspecialchars($_SESSION['username']) ?></h3>
                <p class="text-muted" style="margin-bottom: 15px;"><?= htmlspecialchars($_SESSION['email'] ?? __('profile_no_email')) ?></p>
                
                <div style="margin-bottom: 20px;">
                    <?php if (isset($_SESSION['roles'])): ?>
                        <?php foreach ($_SESSION['roles'] as $role): ?>
                            <span class="badge bg-success" style="font-size: 14px; margin-right: 5px;"><?= htmlspecialchars($role) ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <form action="<?= $base ?>/admin/profile" method="POST" enctype="multipart/form-data" style="border-top: 1px solid #ddd; padding-top: 20px; text-align: left;">
                    <?php renderFileUploader('avatar_file', 'avatar_file', __('form_change_avatar'), '', '', 'image/*', false, true); ?>
                    <button type="submit" class="btn btn-primary btn-sm w-100" data-confirm="Bạn có chắc chắn muốn thay đổi ảnh đại diện?" data-confirm-title="Cập nhật Avatar" data-confirm-type="success"><i class="fa-solid fa-upload"></i> <?= __('form_upload_image') ?></button>
                </form>
            </div>
        </div>

        <!-- Details / Settings -->
        <div class="col-md-8">
            <div class="card" style="padding: 30px;">
                <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;"><?= __('profile_details') ?></h4>
                
                <div class="mb-3">
                    <label class="form-label font-weight-bold"><?= __('form_username') ?></label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['username']) ?>" disabled>
                    <small class="text-muted"><?= __('profile_username_fixed') ?></small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label font-weight-bold"><?= __('form_email') ?></label>
                    <input type="email" class="form-control" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" disabled>
                    <small class="text-muted"><?= __('profile_email_locked') ?></small>
                </div>

                <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-top: 30px; margin-bottom: 20px;"><?= __('profile_change_password') ?></h4>
                <form action="<?= $base ?>/admin/profile" method="POST">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold"><?= __('form_current_password') ?></label>
                        <div class="input-group">
                            <input type="password" name="current_password" class="form-control pwd-input" placeholder="<?= __('form_current_password') ?>" required>
                            <button class="btn btn-outline-secondary toggle-pwd" type="button"><i class="fa-regular fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold"><?= __('form_new_password') ?></label>
                        <div class="input-group">
                            <input type="password" name="new_password" class="form-control pwd-input" placeholder="<?= __('form_new_password') ?>" required>
                            <button class="btn btn-outline-secondary toggle-pwd" type="button"><i class="fa-regular fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold"><?= __('form_confirm_password') ?></label>
                        <div class="input-group">
                            <input type="password" name="confirm_password" class="form-control pwd-input" placeholder="<?= __('form_confirm_password') ?>" required>
                            <button class="btn btn-outline-secondary toggle-pwd" type="button"><i class="fa-regular fa-eye"></i></button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" data-confirm="Bạn có chắc chắn muốn thay đổi mật khẩu?" data-confirm-title="Đổi mật khẩu" data-confirm-type="warning"><i class="fa-solid fa-key"></i> <?= __('profile_save_changes') ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../footerAdmin.php'; ?>

<script>
document.querySelectorAll('.toggle-pwd').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.previousElementSibling;
        const icon = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});
</script>
</body>
</html>
