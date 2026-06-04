<?php
require_once __DIR__ . '/../../../config/env.php';
require_once __DIR__ . '/../components/file_uploader.php';
$authController = new UserController();
$authController->authorize('ADMIN', '/Login');


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
        'background_video' => $_POST['current_bg_file'] ?? ''
    ];

    $uploadDir = __DIR__ . '/../../../images/';
    
    // Giới hạn dung lượng tệp tối đa 10MB
    require_once __DIR__ . '/../components/file_validator.php';
    validateUploadedFiles($_FILES, 10 * 1024 * 1024);

    if (isset($_FILES['bg_file']) && $_FILES['bg_file']['error'] === UPLOAD_ERR_OK) {
        $safeName = generateSafeFilename($_FILES['bg_file']['name']);
        move_uploaded_file($_FILES['bg_file']['tmp_name'], $uploadDir . 'ClassAnimal/' . $safeName);
        $data['background_video'] = $safeName;
    }

    $result = $classAnimalController->updateClassAnimal($classId, $data);

    if ($result !== false) {
        require_once __DIR__ . '/../../../config/env.php';
        header("Location: " . $base . "/admin/classanimals?success=" . urlencode(__('msg_update_classanimal_success')));
    } else {
        $error = "Failed to update.";
    }
    exit();
}

$mediaName = htmlspecialchars($classanimal['background_video'] ?? '');
$ext = strtolower(pathinfo($mediaName, PATHINFO_EXTENSION));
$isVideo = in_array($ext, ['mp4', 'webm', 'ogg']);
?>

<?php include '../../headerAdmin.php'; ?>
    <div class="page-header">
        <h1><i class="fa-solid fa-pen-to-square" style="color:var(--accent-teal);margin-right:10px;font-size:20px;"></i><?= __('admin_classanimal_edit') ?></h1>
        <div class="breadcrumb-text">NEKOPARA <span>›</span> <?= __('admin') ?> <span>›</span> <?= __('admin_classanimals') ?> <span>›</span> <?= __('btn_edit') ?></div>
    </div>

    <div class="card" style="padding: 30px; max-width: 800px; margin: 0 auto 30px;">
        <form action="<?= $base ?>/admin/classanimals/edit" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($classanimal['id_class']) ?>">
            
            <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;"><?= __('admin_class_info') ?></h4>
            
            <div class="mb-3">
                <label for="name" class="form-label font-weight-bold"><?= __('table_class_name') ?> <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($classanimal['name']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="info" class="form-label font-weight-bold"><?= __('admin_class_intro') ?> <span class="text-danger">*</span></label>
                <textarea class="form-control" id="info" name="info" rows="6" required><?= htmlspecialchars($classanimal['info']) ?></textarea>
            </div>
            
            <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-top: 30px; margin-bottom: 20px;"><?= __('admin_media') ?></h4>
            
            <div class="mb-3">
                <?php renderFileUploader('bg_file', 'bg_file', __('admin_choose_new_media'), $classanimal['background_video'] ?? '', 'ClassAnimal', 'image/*,video/*'); ?>
            </div>
            
            <div class="mt-4" style="text-align: right; border-top: 1px solid #ddd; padding-top: 20px;">
                <a href="<?= $base ?>/admin/classanimals" class="btn btn-secondary" style="margin-right: 10px;"><?= __('btn_cancel') ?></a>
                <button type="submit" class="btn btn-primary" data-confirm="Bạn có chắc chắn muốn cập nhật thông tin lớp động vật này?" data-confirm-title="Xác nhận cập nhật" data-confirm-type="success"><i class="fa-solid fa-floppy-disk" style="margin-right:5px;"></i><?= __('btn_update') ?></button>
            </div>
        </form>
    </div>
    <?php include '../../footerAdmin.php'; ?>
