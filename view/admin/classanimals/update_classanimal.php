<?php
require_once '../../../controller/ClassAnimalController.php';

$classAnimalController = new ClassAnimalController();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $classId = intval($_GET['id']);
    $classanimal = $classAnimalController->getClassAnimalById($classId);
    if (!$classanimal) {
        die("Class not found.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $classId = intval($_POST['id']);
    $data = [
        'name' => $_POST['name'],
        'info' => $_POST['info'],
        'background_video' => $_POST['current_background_video'] ?? ''
    ];

    $uploadDir = __DIR__ . '/../../../images/';
    
    if (isset($_FILES['bg_file']) && $_FILES['bg_file']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['bg_file']['name']);
        move_uploaded_file($_FILES['bg_file']['tmp_name'], $uploadDir . 'ClassAnimal/' . $fileName);
        $data['background_video'] = $fileName;
    }

    $result = $classAnimalController->updateClassAnimal($classId, $data);

    if ($result !== false) {
        require_once __DIR__ . '/../../../config/env.php';
        header("Location: " . $base . "/admin/classanimals?success=Class Animal updated successfully");
    } else {
        $error = "Failed to update.";
    }
    exit();
}

$mediaName = htmlspecialchars($classanimal['background_video'] ?? '');
$ext = strtolower(pathinfo($mediaName, PATHINFO_EXTENSION));
$isVideo = in_array($ext, ['mp4', 'webm', 'ogg']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class Animal</title>
</head>
<body>
    <?php include '../../headerAdmin.php'; ?>
    <div class="page-header">
        <h1><i class="fa-solid fa-pen-to-square" style="color:var(--accent-teal);margin-right:10px;font-size:20px;"></i>Chỉnh sửa lớp động vật</h1>
        <div class="breadcrumb-text">NEKOPARA <span>›</span> Admin <span>›</span> Lớp động vật <span>›</span> Chỉnh sửa</div>
    </div>

    <div class="card" style="padding: 30px; max-width: 800px; margin: 0 auto 30px;">
        <form action="<?= $base ?>/admin/classanimals/edit" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($classanimal['id_class']) ?>">
            
            <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">Thông tin lớp</h4>
            
            <div class="mb-3">
                <label for="name" class="form-label font-weight-bold">Tên lớp:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($classanimal['name']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="info" class="form-label font-weight-bold">Thông tin giới thiệu:</label>
                <textarea class="form-control" id="info" name="info" rows="6" required><?= htmlspecialchars($classanimal['info']) ?></textarea>
            </div>
            
            <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-top: 30px; margin-bottom: 20px;">Đa phương tiện</h4>
            
            <div class="mb-3">
                <label class="form-label font-weight-bold">File Ảnh/Video nền hiện tại:</label><br>
                <?php if(!empty($classanimal['background_video'])): ?>
                    <div class="text-center mt-2 p-3 mb-3" style="border: 1px solid #eee; border-radius: 8px; background: #fff; max-width: 400px;">
                        <?php if($isVideo): ?>
                            <video src="<?= $base ?>/images/ClassAnimal/<?= $mediaName ?>" controls style="max-width: 100%; max-height: 200px; border-radius: 8px;"></video>
                        <?php else: ?>
                            <img src="<?= $base ?>/images/ClassAnimal/<?= $mediaName ?>" style="max-width: 100%; max-height: 200px; border-radius: 8px; object-fit: contain;">
                        <?php endif; ?>
                        <div class="mt-2 text-muted" style="font-size: 0.9em;">File: <code><?= $mediaName ?></code></div>
                    </div>
                <?php else: ?>
                    <span class="text-muted">Chưa có file</span><br><br>
                <?php endif; ?>
                
                <input type="hidden" name="current_background_video" value="<?= htmlspecialchars($classanimal['background_video'] ?? '') ?>">
                <label class="form-label mt-2">Chọn file mới để thay thế (tuỳ chọn):</label>
                <input type="file" class="form-control" name="bg_file" accept="image/*,video/*">
                <small class="text-muted">Tải lên hình ảnh hoặc video để làm background cho trang chủ của lớp này.</small>
            </div>
            
            <div class="mt-4" style="text-align: right; border-top: 1px solid #ddd; padding-top: 20px;">
                <a href="<?= $base ?>/admin/classanimals" class="btn btn-secondary" style="margin-right: 10px;">Huỷ</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk" style="margin-right:5px;"></i>Cập nhật</button>
            </div>
        </form>
    </div>
    <?php include '../../footerAdmin.php'; ?>
</body>
</html>
