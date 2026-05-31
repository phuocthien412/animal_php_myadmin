<?php
require_once '../../../controller/ClassAnimalController.php';

$classAnimalController = new ClassAnimalController();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $classId = intval($_GET['id']);
    $classanimal = $classAnimalController->getClassAnimalById($classId);
    if (!$classanimal) {
        die("Class not found.");
    }
} else {
    die("Invalid request.");
}

$mediaName = htmlspecialchars($classanimal['background_video']);
$ext = strtolower(pathinfo($mediaName, PATHINFO_EXTENSION));
$isVideo = in_array($ext, ['mp4', 'webm', 'ogg']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEKOPARA — Chi tiết lớp động vật</title>
</head>
<body>
    <?php include '../../headerAdmin.php'; ?>
    <div class="page-header">
        <h1><i class="fa-solid fa-eye" style="color:var(--accent-teal);margin-right:10px;font-size:20px;"></i>Chi tiết lớp động vật</h1>
        <div class="breadcrumb-text">NEKOPARA <span>›</span> Admin <span>›</span> Lớp động vật <span>›</span> Chi tiết</div>
    </div>
    
    <div class="card" style="margin: 0 20px 20px; padding: 30px;">
        <div class="row">
            <div class="col-md-6 mb-4">
                <h3 class="mt-3 mb-2" style="color: var(--accent-teal);">
                    <?= htmlspecialchars($classanimal['name']) ?>
                    <span class="badge bg-secondary" style="font-size: 14px; vertical-align: middle;">#ID: <?= htmlspecialchars($classanimal['id_class']) ?></span>
                </h3>
                
                <h5 class="mt-4" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;">Thông tin chung</h5>
                <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #eee; margin-top: 15px; line-height: 1.6;">
                    <?= nl2br(htmlspecialchars($classanimal['info'])) ?>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <h5 class="mt-4" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;">Đa phương tiện (Ảnh / Video nền)</h5>
                
                <div class="text-center mt-3 p-3" style="border: 1px solid #eee; border-radius: 8px; background: #fff;">
                    <?php if(!empty($classanimal['background_video'])): ?>
                        <?php if($isVideo): ?>
                            <video src="<?= $base ?>/images/ClassAnimal/<?= $mediaName ?>" controls style="max-width: 100%; max-height: 300px; border-radius: 8px;"></video>
                        <?php else: ?>
                            <img src="<?= $base ?>/images/ClassAnimal/<?= $mediaName ?>" style="max-width: 100%; max-height: 300px; border-radius: 8px; object-fit: contain;">
                        <?php endif; ?>
                        <div class="mt-2 text-muted" style="font-size: 0.9em;">File: <code><?= $mediaName ?></code></div>
                    <?php else: ?>
                        <div class="text-muted p-4">Chưa có đa phương tiện</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="mt-4" style="text-align: right; border-top: 1px solid #ddd; padding-top: 20px;">
            <a href="<?= $base ?>/admin/classanimals" class="btn btn-secondary" style="margin-right: 10px;"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
            <a href="<?= $base ?>/admin/classanimals/edit/<?= urlencode($classanimal['id_class']) ?>" class="btn btn-primary"><i class="fa-solid fa-pen"></i> Chỉnh sửa</a>
        </div>
    </div>
    <?php include '../../footerAdmin.php'; ?>
</body>
</html>
