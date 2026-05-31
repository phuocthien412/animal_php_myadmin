<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /animal_php_myadmin/animal_php_myadmin/Login");
    exit();
}

require_once '../../config/env.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar_file'])) {
    $uploadDir = __DIR__ . '/../../images/';
    
    if ($_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
        $fileName = time() . '_' . basename($_FILES['avatar_file']['name']);
        move_uploaded_file($_FILES['avatar_file']['tmp_name'], $uploadDir . $fileName);
        
        require_once '../../controller/UserController.php';
        $userController = new UserController();
        if (isset($_SESSION['user_id'])) {
            $userController->updateUserAvatar($_SESSION['user_id'], $fileName);
            $_SESSION['avatar'] = $fileName;
            header("Location: " . $base . "/admin/profile?success=" . urlencode("Cập nhật avatar thành công!"));
            exit();
        } else {
            header("Location: " . $base . "/admin/profile?error=" . urlencode("Lỗi: Không tìm thấy ID người dùng."));
            exit();
        }
    } else {
        header("Location: " . $base . "/admin/profile?error=" . urlencode("Lỗi upload ảnh!"));
        exit();
    }
}

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>NEKOPARA — Hồ sơ cá nhân</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php include '../headerAdmin.php'; ?>

<div class="page-header">
    <h1><i class="fa-solid fa-id-badge" style="color:var(--green-primary);margin-right:10px;font-size:20px;"></i>Hồ sơ cá nhân</h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> Admin <span>›</span> Hồ sơ</div>
</div>

<?php if ($success): ?>
    <div class="alert-admin success" style="margin: 0 20px 20px;"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert-admin danger" style="margin: 0 20px 20px;"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

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
                <p class="text-muted" style="margin-bottom: 15px;"><?= htmlspecialchars($_SESSION['email'] ?? 'Không có email') ?></p>
                
                <div style="margin-bottom: 20px;">
                    <?php if (isset($_SESSION['roles'])): ?>
                        <?php foreach ($_SESSION['roles'] as $role): ?>
                            <span class="badge bg-success" style="font-size: 14px; margin-right: 5px;"><?= htmlspecialchars($role) ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <form action="<?= $base ?>/admin/profile" method="POST" enctype="multipart/form-data" style="border-top: 1px solid #ddd; padding-top: 20px; text-align: left;">
                    <label class="form-label font-weight-bold" style="font-size: 14px;">Thay đổi Avatar</label>
                    <input type="file" class="form-control mb-2" name="avatar_file" accept="image/*" required style="font-size: 14px;">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fa-solid fa-upload"></i> Cập nhật ảnh</button>
                </form>
            </div>
        </div>

        <!-- Details / Settings -->
        <div class="col-md-8">
            <div class="card" style="padding: 30px;">
                <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">Thông tin chi tiết</h4>
                
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Tên đăng nhập</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['username']) ?>" disabled>
                    <small class="text-muted">Tên đăng nhập không thể thay đổi.</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Email</label>
                    <input type="email" class="form-control" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" disabled>
                    <small class="text-muted">Tính năng đổi email hiện đang tạm khoá.</small>
                </div>

                <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-top: 30px; margin-bottom: 20px;">Đổi mật khẩu</h4>
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Mật khẩu hiện tại</label>
                    <input type="password" class="form-control" placeholder="Nhập mật khẩu hiện tại" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Mật khẩu mới</label>
                    <input type="password" class="form-control" placeholder="Nhập mật khẩu mới" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Nhập lại mật khẩu mới</label>
                    <input type="password" class="form-control" placeholder="Xác nhận mật khẩu mới" disabled>
                </div>
                <button type="button" class="btn btn-primary" onclick="alert('Tính năng đổi mật khẩu đang được phát triển!')"><i class="fa-solid fa-key"></i> Cập nhật mật khẩu</button>
            </div>
        </div>
    </div>
</div>

<?php include '../footerAdmin.php'; ?>
</body>
</html>
