<?php
require_once __DIR__ . '/../../../config/env.php';
$authController = new UserController();
$authController->authorize('ADMIN', '/Login');


// Fetch the list of class animals
$classAnimalController = new ClassAnimalController();
$classAnimals = $classAnimalController->getAllClassAnimals();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Giới hạn dung lượng tệp tối đa 10MB
    $maxSize = 10 * 1024 * 1024;
    // Tái sử dụng file_validator để kiểm tra dung lượng tệp
    require_once __DIR__ . '/../components/file_validator.php';
    validateUploadedFiles($_FILES, 10 * 1024 * 1024);


    // Generate safe, unique names for uploaded files according to Senior Developer standards
    $avatarName = !empty($_FILES['avatar']['name']) ? generateSafeFilename($_FILES['avatar']['name']) : '';
    $nssName = !empty($_FILES['noi_sinh_song_image']['name']) ? generateSafeFilename($_FILES['noi_sinh_song_image']['name']) : '';
    $qrName = !empty($_FILES['imgqr3d']['name']) ? generateSafeFilename($_FILES['imgqr3d']['name']) : '';

    $data = [
        'name' => $_POST['name'],
        'gioi_thieu_text' => $_POST['gioi_thieu_text'],
        'ngoai_hinh_text' => $_POST['ngoai_hinh_text'],
        'noi_sinh_song_text' => $_POST['noi_sinh_song_text'],
        'avatar' => $avatarName,
        'noi_sinh_song_image' => $nssName,
        'imgqr3d' => $qrName,
        'classanimals_id' => $_POST['classanimals_id'] // Save the selected class animal ID
    ];

    // Handle file uploads
    $uploadDir = '../../../images/';
    if ($avatarName !== '') {
        move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . 'Animal/Avatar/' . $avatarName);
    }
    if ($nssName !== '') {
        move_uploaded_file($_FILES['noi_sinh_song_image']['tmp_name'], $uploadDir . 'Animal/NoiSinhSong/' . $nssName);
    }
    if ($qrName !== '') {
        move_uploaded_file($_FILES['imgqr3d']['tmp_name'], $uploadDir . 'Animal/3DQR/' . $qrName);
    }

    // Add the animal to the database
    $animalController = new AnimalController();
    $animalId = $animalController->createAnimal($data); // Get the ID of the newly created animal

    // Handle multiple images from a single input file for the list_image table
    $listAnimalController = new ListAnimalController();
    if (!empty($_FILES['list_images']['name'][0])) {
        foreach ($_FILES['list_images']['name'] as $key => $originalName) {
            $tmpName = $_FILES['list_images']['tmp_name'][$key];
            if ($_FILES['list_images']['error'][$key] === UPLOAD_ERR_OK) {
                $safeName = generateSafeFilename($originalName);
                $imagePath = $uploadDir . 'Animal/ListImage/' . $safeName;
                if (move_uploaded_file($tmpName, $imagePath)) {
                    $listAnimalController->addImage([
                        'animalimage' => $safeName, // Store only the safe image name
                        'animals_id' => $animalId   // Use the correct animal ID
                    ]);
                }
            }
        }
    }

    // Redirect to the animal list page after successful submission
    header("Location: " . $base . "/admin/animals?success=" . urlencode(__('msg_add_animal_success') ?? 'Thêm động vật thành công'));
    exit();
}
?>

<?php
require_once __DIR__ . '/../components/file_uploader.php';
?>
<?php include '../../headerAdmin.php'; ?>
    <div class="form-container">
        <h2><?= __('admin_animals_add_title') ?? 'Thêm động vật' ?></h2>
        
        <form id="addAnimalForm" action="<?= $base ?>/admin/animals/add" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name"><?= __('form_animal_name') ?> <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="gioi_thieu_text"><?= __('form_animal_intro') ?> <span class="text-danger">*</span></label>
                <textarea class="form-control" id="gioi_thieu_text" name="gioi_thieu_text" required></textarea>
            </div>
            <div class="form-group">
                <label for="ngoai_hinh_text"><?= __('form_animal_appearance') ?> <span class="text-danger">*</span></label>
                <textarea class="form-control" id="ngoai_hinh_text" name="ngoai_hinh_text" required></textarea>
            </div>
            <div class="form-group">
                <label for="noi_sinh_song_text"><?= __('form_animal_habitat') ?> <span class="text-danger">*</span></label>
                <textarea class="form-control" id="noi_sinh_song_text" name="noi_sinh_song_text" required></textarea>
            </div>
            <?php renderFileUploader('avatar', 'avatar', __('form_animal_avatar'), '', '', 'image/*', false, true); ?>

            <?php renderFileUploader('noi_sinh_song_image', 'noi_sinh_song_image', __('form_animal_habitat_map'), '', '', 'image/*', false, true); ?>

            <?php renderFileUploader('imgqr3d', 'imgqr3d', __('form_animal_qr3d'), '', '', 'image/*', false, true); ?>

            <?php renderFileUploader('list_images', 'list_images[]', __('form_animal_gallery'), '', '', 'image/*', true, false); ?>
            <div class="form-group">
                <label for="classanimals_id"><?= __('form_animal_class') ?> <span class="text-danger">*</span></label>
                <select class="form-control" id="classanimals_id" name="classanimals_id" required>
                    <option value="" disabled selected><?= __('form_animal_class_select') ?></option>
                    <?php foreach ($classAnimals as $classAnimal): ?>
                        <option value="<?= htmlspecialchars($classAnimal['id_class']) ?>">
                            <?= htmlspecialchars($classAnimal['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="border-top: 1px solid #ddd; padding-top: 20px; text-align: right; margin-bottom: 30px;">
                <a href="<?= $base ?>/admin/animals" class="btn btn-secondary" style="margin-right: 10px;"><?= __('btn_cancel') ?></a>
                <button type="submit" class="btn btn-primary" data-confirm="<?= htmlspecialchars(__('confirm_add_animal'), ENT_QUOTES) ?>" data-confirm-title="<?= htmlspecialchars(__('confirm_add_animal_title'), ENT_QUOTES) ?>" data-confirm-type="success"><i class="fa-solid fa-floppy-disk" style="margin-right:5px;"></i><?= __('btn_add_new') ?></button>
            </div>
        </form>
    </div>
<?php include '../../footerAdmin.php'; ?>