<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /animal_php_myadmin/animal_php_myadmin/Login");
    exit();
}

require_once __DIR__ . '/../../../config/env.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController = new UserController();
    
    if (isset($_FILES['avatar_file'])) {
        $uploadDir = __DIR__ . '/../../../images/';
        
        if ($_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
            $safeName = generateSafeFilename($_FILES['avatar_file']['name']);
            move_uploaded_file($_FILES['avatar_file']['tmp_name'], $uploadDir . $safeName);
            
            if (isset($_SESSION['user_id'])) {
                $userController->updateUserAvatar($_SESSION['user_id'], $safeName);
                $_SESSION['avatar'] = $safeName;
                header("Location: " . $base . "/Profile?success=" . urlencode(__('profile_avatar_success')));
                exit();
            } else {
                header("Location: " . $base . "/Profile?error=" . urlencode(__('profile_err_user_id')));
                exit();
            }
        } else {
            header("Location: " . $base . "/Profile?error=" . urlencode(__('profile_err_upload')));
            exit();
        }
    } elseif (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $current = $_POST['current_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];
        
        if ($new !== $confirm) {
            header("Location: " . $base . "/Profile?error=" . urlencode(__('profile_err_confirm_pwd')));
            exit();
        } else {
            if ($userController->updatePassword($_SESSION['user_id'], $current, $new)) {
                header("Location: " . $base . "/Profile?success=" . urlencode(__('profile_pwd_success')));
                exit();
            } else {
                header("Location: " . $base . "/Profile?error=" . urlencode(__('profile_err_current_pwd')));
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
    <title><?= __('profile_page_title') ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base ?>/css/client/users.css">
</head>
<body class="profile-page-body">
<?php include __DIR__ . '/../header.php'; ?>

<div class="profile-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="profile-card">
                    <div class="profile-header">
                        <h2><i class="fa-solid fa-id-badge me-2 text-primary"></i><?= __('profile_your_profile') ?></h2>
                    </div>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-custom alert-success-custom mb-4"><i class="fa-solid fa-circle-check me-3 fs-5"></i> <?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-custom alert-danger-custom mb-4"><i class="fa-solid fa-circle-exclamation me-3 fs-5"></i> <?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <div class="row">
                        <!-- Left Column: Avatar & Basic Info -->
                        <div class="col-md-4 text-center border-end position-relative z-1">
                            <form action="<?= $base ?>/Profile" method="POST" enctype="multipart/form-data" id="avatarForm">
                                <div class="avatar-wrapper" onclick="document.getElementById('avatar_file_input').click()">
                                    <?php 
                                    $profileAvatar = isset($_SESSION['avatar']) && !empty($_SESSION['avatar']) ? $base . '/images/' . htmlspecialchars($_SESSION['avatar']) : null;
                                    if ($profileAvatar): 
                                    ?>
                                        <img src="<?= $profileAvatar ?>" alt="Avatar" class="avatar-preview">
                                    <?php else: ?>
                                        <div class="avatar-placeholder">
                                            <?= strtoupper(mb_substr($_SESSION['username'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="avatar-upload-overlay">
                                        <i class="fa-solid fa-camera"></i>
                                        <span><?= __('profile_change_avatar') ?></span>
                                    </div>
                                </div>
                                <input type="file" id="avatar_file_input" name="avatar_file" accept="image/*" required onchange="document.getElementById('avatarForm').submit()">
                            </form>
                            
                            <div class="username-title mt-3"><?= htmlspecialchars($_SESSION['username']) ?></div>
                            <div class="mt-2">
                                <?php if (isset($_SESSION['roles']) && !empty($_SESSION['roles'])): ?>
                                    <?php foreach ($_SESSION['roles'] as $role): ?>
                                        <span class="role-badge mx-1"><?= htmlspecialchars($role) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic small"><?= __('profile_default_role') ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Right Column: Settings -->
                        <div class="col-md-8 right-column position-relative z-1">
                            
                            <div class="section-title">
                                <i class="fa-solid fa-user-shield me-2"></i> <?= __('profile_account_info') ?>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small"><?= __('form_username') ?></label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['username']) ?>" disabled>
                            </div>
                            
                            <div class="section-title mt-5">
                                <i class="fa-solid fa-lock me-2"></i> <?= __('profile_change_pwd') ?>
                            </div>
                            
                            <form action="<?= $base ?>/Profile" method="POST">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted small"><?= __('profile_current_pwd') ?></label>
                                    <div class="input-group">
                                        <input type="password" name="current_password" class="form-control pwd-input" placeholder="<?= __('profile_placeholder_current_pwd') ?>" required>
                                        <button class="btn btn-toggle-pwd toggle-pwd" type="button"><i class="fa-regular fa-eye"></i></button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted small"><?= __('profile_new_pwd') ?></label>
                                    <div class="input-group">
                                        <input type="password" name="new_password" class="form-control pwd-input" placeholder="<?= __('profile_placeholder_new_pwd') ?>" required>
                                        <button class="btn btn-toggle-pwd toggle-pwd" type="button"><i class="fa-regular fa-eye"></i></button>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted small"><?= __('profile_confirm_new_pwd') ?></label>
                                    <div class="input-group">
                                        <input type="password" name="confirm_password" class="form-control pwd-input" placeholder="<?= __('profile_placeholder_confirm_pwd') ?>" required>
                                        <button class="btn btn-toggle-pwd toggle-pwd" type="button"><i class="fa-regular fa-eye"></i></button>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary-gradient"><i class="fa-solid fa-check-circle me-2"></i> <?= __('btn_save_changes') ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer style="padding: 20px; text-align: center; background: transparent; color: #64748b; font-size: 14px;">
    <p class="mb-0">&copy; 2026 NEKOPARA. All rights reserved.</p>
</footer>

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
