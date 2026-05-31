<?php
require_once '../../../controller/AnimalController.php';

if (!isset($_GET['id'])) {
    echo "Invalid animal ID.";
    exit();
}

$animalController = new AnimalController();
$animal = $animalController->getAnimalById($_GET['id']);
$animalImages = $animalController->getAnimalImagesById($_GET['id']);

if (!$animal) {
    echo "Animal not found.";
    exit();
}

$classAnimalInfo = $animalController->getClassAnimalInfoById($animal['classanimals_id']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEKOPARA — Chi tiết động vật</title>
</head>
<body class="admin-body">
    <?php include '../../headerAdmin.php'; ?>
    <div class="page-header" style="padding-left: 20px;">
        <h1><i class="fa-solid fa-eye" style="color:var(--green-primary);margin-right:10px;font-size:20px;"></i>Chi tiết động vật</h1>
        <div class="breadcrumb-text">NEKOPARA <span>›</span> Admin <span>›</span> Động vật <span>›</span> Chi tiết</div>
    </div>
    
    <div class="card" style="margin: 20px; padding: 20px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: var(--green-primary); margin: 0;">
                <?= htmlspecialchars($animal['name']) ?>
                <span class="badge bg-secondary" style="font-size: 14px; vertical-align: middle;">
                    Lớp: <?= htmlspecialchars($classAnimalInfo['name'] ?? 'Không rõ') ?>
                </span>
            </h2>
            <div>
                <a href="<?= $base ?>/admin/animals" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
                <a href="<?= $base ?>/admin/animals/edit/<?= urlencode($animal['id_animal']) ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-pen"></i> Chỉnh sửa</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <h4>Giới thiệu</h4>
                <div class="p-3 mb-3" style="background-color: var(--bg-light); border-radius: 8px;">
                    <?= nl2br(htmlspecialchars($animal['gioi_thieu_text'])) ?>
                </div>

                <h4>Ngoại hình</h4>
                <div class="p-3 mb-3" style="background-color: var(--bg-light); border-radius: 8px;">
                    <?= nl2br(htmlspecialchars($animal['ngoai_hinh_text'])) ?>
                </div>

                <h4>Nơi sinh sống</h4>
                <div class="p-3 mb-3" style="background-color: var(--bg-light); border-radius: 8px;">
                    <?= nl2br(htmlspecialchars($animal['noi_sinh_song_text'])) ?>
                </div>
            </div>

            <div class="col-md-6">
                <h4>Hình ảnh hiển thị</h4>
                <div class="row">
                    <!-- Avatar -->
                    <div class="col-md-6 mb-4 text-center">
                        <p class="font-weight-bold text-muted mb-1">Ảnh đại diện (Avatar)</p>
                        <?php if (!empty($animal['avatar'])): ?>
                            <img src="<?= $base ?>/images/Animal/Avatar/<?= htmlspecialchars($animal['avatar']) ?>" style="max-width: 100%; max-height: 250px; border-radius: 8px; border: 1px solid #ddd; object-fit: contain;" alt="Avatar">
                        <?php else: ?>
                            <div class="text-muted p-3 border rounded">Chưa có ảnh</div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- 3D QR Image -->
                    <div class="col-md-6 mb-4 text-center">
                        <p class="font-weight-bold text-muted mb-1">Mã QR 3D</p>
                        <?php if (!empty($animal['imgqr3d'])): ?>
                            <img src="<?= $base ?>/images/Animal/3DQR/<?= htmlspecialchars($animal['imgqr3d']) ?>" style="max-width: 100%; max-height: 250px; border-radius: 8px; border: 1px solid #ddd; object-fit: contain;" alt="QR 3D">
                        <?php else: ?>
                            <div class="text-muted p-3 border rounded">Chưa có ảnh QR</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Nơi sinh sống Image -->
                <div class="mb-4 text-center">
                    <p class="font-weight-bold text-muted mb-1">Ảnh nơi sinh sống</p>
                    <?php if (!empty($animal['noi_sinh_song_image'])): ?>
                        <img src="<?= $base ?>/images/Animal/NoiSinhSong/<?= htmlspecialchars($animal['noi_sinh_song_image']) ?>" style="max-width: 100%; max-height: 350px; border-radius: 8px; border: 1px solid #ddd; object-fit: cover;" alt="Nơi sinh sống">
                    <?php else: ?>
                        <div class="text-muted p-3 border rounded">Chưa có ảnh</div>
                    <?php endif; ?>
                </div>

                <!-- Slide Images -->
                <div class="mb-4">
                    <p class="font-weight-bold text-muted mb-2">Ảnh động vật (Bộ sưu tập)</p>
                    <div class="d-flex flex-wrap" style="gap: 10px;">
                        <?php if (count($animalImages) > 0): ?>
                            <?php foreach ($animalImages as $image): ?>
                                <img src="<?= $base ?>/images/Animal/ListImage/<?= htmlspecialchars($image['animalimage']) ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;" alt="Slide Image">
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-muted w-100 p-3 border rounded text-center">Chưa có bộ sưu tập ảnh</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../../footerAdmin.php'; ?>
</body>
</html>
