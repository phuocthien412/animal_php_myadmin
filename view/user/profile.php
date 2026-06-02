<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /animal_php_myadmin/animal_php_myadmin/Login");
    exit();
}

require_once __DIR__ . '/../../config/env.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController = new UserController();
    
    if (isset($_FILES['avatar_file'])) {
        $uploadDir = __DIR__ . '/../../images/';
        
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
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f6f8fb 0%, #e9edf3 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .profile-container {
            flex: 1;
            padding: 60px 15px;
            display: flex;
            align-items: center;
        }
        .profile-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            padding: 40px;
            border: 1px solid rgba(255,255,255,0.8);
            position: relative;
            overflow: hidden;
        }
        /* Decorative Blob */
        .profile-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
            border-radius: 50%;
            opacity: 0.1;
            z-index: 0;
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }
        .profile-header h2 {
            font-weight: 700;
            color: #2b3452;
            font-size: 28px;
        }
        
        /* Avatar Section */
        .avatar-wrapper {
            position: relative;
            width: 160px;
            height: 160px;
            margin: 0 auto 20px;
            border-radius: 50%;
            padding: 5px;
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
            box-shadow: 0 10px 25px rgba(13, 110, 253, 0.2);
            transition: transform 0.3s ease;
        }
        .avatar-wrapper:hover {
            transform: translateY(-5px);
        }
        .avatar-preview, .avatar-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            background: #fff;
        }
        .avatar-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0d6efd;
            color: white;
            font-size: 60px;
            font-weight: bold;
        }
        
        /* Avatar Upload Overlay */
        .avatar-upload-overlay {
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border-radius: 50%;
            background: rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
            backdrop-filter: blur(2px);
        }
        .avatar-wrapper:hover .avatar-upload-overlay {
            opacity: 1;
        }
        .avatar-upload-overlay i {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .avatar-upload-overlay span {
            font-size: 12px;
            font-weight: 600;
        }
        /* Hidden file input */
        #avatar_file_input {
            display: none;
        }
        
        .username-title {
            font-weight: 700;
            color: #2b3452;
            font-size: 22px;
            margin-bottom: 5px;
        }
        .role-badge {
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        /* Form Styles */
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
            margin-left: 15px;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            color: #334155;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }
        .input-group-text {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        .btn-toggle-pwd {
            border-top-right-radius: 10px !important;
            border-bottom-right-radius: 10px !important;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            color: #64748b;
        }
        .btn-toggle-pwd:hover {
            background-color: #e2e8f0;
            color: #334155;
        }
        
        .btn-primary-gradient {
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
            color: white;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            width: 100%;
        }
        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3);
            color: white;
        }
        
        /* Custom Alert */
        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        .alert-success-custom {
            background-color: #d1fae5;
            color: #065f46;
        }
        .alert-danger-custom {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .right-column {
            padding-left: 40px;
        }
        
        @media (max-width: 768px) {
            .profile-card {
                padding: 25px;
            }
            .right-column {
                padding-left: 15px;
                margin-top: 30px;
                border-top: 1px solid #e2e8f0;
                padding-top: 30px;
            }
            .border-end {
                border-right: none !important;
            }
        }
    </style>
</head>
<body>
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
