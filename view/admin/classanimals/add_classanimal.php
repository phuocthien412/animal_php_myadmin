<?php
require_once __DIR__ . '/../../../config/env.php';
require_once __DIR__ . '/../components/file_uploader.php';
$authController = new UserController();
$authController->authorize('ADMIN', '/Login');


$classAnimalController = new ClassAnimalController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'info' => $_POST['info'],
        'background_video' => ''
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

    $result = $classAnimalController->createClassAnimal($data);

    if ($result !== false) {
        require_once __DIR__ . '/../../../config/env.php';
        header("Location: " . $base . "/admin/classanimals?success=" . urlencode(__('msg_classanimal_add_success')));
    } else {
        $error = "Failed to add.";
    }
    exit();
}
?>

<?php include '../../headerAdmin.php'; ?>
    <div class="page-header">
        <h1><i class="fa-solid fa-plus" style="color:var(--accent-teal);margin-right:10px;font-size:20px;"></i><?= __('admin_classanimal_add_title') ?></h1>
        <div class="breadcrumb-text">NEKOPARA <span>›</span> <?= __('admin') ?> <span>›</span> <?= __('admin_classanimals') ?> <span>›</span> <?= __('btn_add_classanimal') ?></div>
    </div>

    <div class="card" style="padding: 30px; max-width: 800px; margin: 0 auto 30px;">
        <form action="<?= $base ?>/admin/classanimals/add" method="POST" enctype="multipart/form-data">
            
            <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;"><?= __('admin_class_info') ?></h4>
            
            <div class="mb-3">
                <label for="name" class="form-label font-weight-bold"><?= __('table_class_name') ?>:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <div class="mb-3">
                <label for="info" class="form-label font-weight-bold"><?= __('admin_class_intro') ?>:</label>
                <textarea class="form-control" id="info" name="info" rows="6" required></textarea>
            </div>
            
            <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-top: 30px; margin-bottom: 20px;"><?= __('admin_media') ?></h4>
            
            <div class="mb-3">
                <?php renderFileUploader('bg_file', 'bg_file', __('admin_choose_new_media'), '', 'ClassAnimal', 'image/*,video/*'); ?>
            </div>
            
            <div class="mt-4" style="text-align: right; border-top: 1px solid #ddd; padding-top: 20px;">
                <a href="<?= $base ?>/admin/classanimals" class="btn btn-secondary" style="margin-right: 10px;"><?= __('btn_cancel') ?></a>
                <button type="submit" class="btn btn-primary" data-confirm="<?= htmlspecialchars(__('confirm_add_classanimal'), ENT_QUOTES) ?>" data-confirm-title="<?= htmlspecialchars(__('confirm_add_classanimal_title'), ENT_QUOTES) ?>" data-confirm-type="success"><i class="fa-solid fa-floppy-disk" style="margin-right:5px;"></i><?= __('btn_add_new') ?></button>
            </div>
        </form>
    </div>
    <?php include '../../footerAdmin.php'; ?>
