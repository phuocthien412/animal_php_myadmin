<?php
require_once __DIR__ . '/../../../config/env.php';
$authController = new UserController();
$authController->authorize('ADMIN', '/Login');


$animalController = new AnimalController();
$classAnimalController = new ClassAnimalController();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $animalId = intval($_GET['id']);
    $animal = $animalController->getAnimalById($animalId);
    $animalImages = $animalController->getAnimalImagesById($animalId);
    $classAnimals = $classAnimalController->getAllClassAnimals();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $animalId = intval($_POST['id']);
    $data = [
        'name' => $_POST['name'],
        'gioi_thieu_text' => $_POST['gioi_thieu_text'],
        'ngoai_hinh_text' => $_POST['ngoai_hinh_text'],
        'noi_sinh_song_text' => $_POST['noi_sinh_song_text'],
        'classanimals_id' => $_POST['classanimals_id'],
        'avatar' => $_POST['current_avatar'] ?? '',
        'noi_sinh_song_image' => $_POST['current_noi_sinh_song_image'] ?? '',
        'imgqr3d' => $_POST['current_imgqr3d'] ?? ''
    ];

    $uploadDir = __DIR__ . '/../../../images/';
    
    if (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['avatar_file']['name']);
        move_uploaded_file($_FILES['avatar_file']['tmp_name'], $uploadDir . 'Animal/Avatar/' . $fileName);
        $data['avatar'] = $fileName;
    }
    
    if (isset($_FILES['nss_file']) && $_FILES['nss_file']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['nss_file']['name']);
        move_uploaded_file($_FILES['nss_file']['tmp_name'], $uploadDir . 'Animal/NoiSinhSong/' . $fileName);
        $data['noi_sinh_song_image'] = $fileName;
    }

    if (isset($_FILES['qr_file']) && $_FILES['qr_file']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['qr_file']['name']);
        move_uploaded_file($_FILES['qr_file']['tmp_name'], $uploadDir . 'Animal/3DQR/' . $fileName);
        $data['imgqr3d'] = $fileName;
    }

    // Handle deleting slide images
    if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
        foreach ($_POST['delete_images'] as $imageId) {
            $animalController->deleteAnimalImage(intval($imageId));
        }
    }

    // Handle adding new slide images
    if (isset($_FILES['new_slide_images']) && is_array($_FILES['new_slide_images']['name'])) {
        $count = count($_FILES['new_slide_images']['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['new_slide_images']['error'][$i] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['new_slide_images']['name'][$i]);
                move_uploaded_file($_FILES['new_slide_images']['tmp_name'][$i], $uploadDir . 'Animal/ListImage/' . $fileName);
                $animalController->addAnimalImage($animalId, $fileName);
            }
        }
    }

    $result = $animalController->updateAnimal($animalId, $data);

    if ($result !== false) {
        require_once __DIR__ . '/../../../config/env.php';
        header("Location: " . $base . "/admin/animals?success=" . urlencode(__('msg_update_animal_success')));
    } else {
        echo "Failed to update the animal.";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa động vật</title>
</head>
<body>
    <?php include '../../headerAdmin.php'; ?>
    
    <div class="page-header">
        <h1><i class="fa-solid fa-pen-to-square" style="color:var(--green-primary);margin-right:10px;font-size:20px;"></i>Chỉnh sửa động vật</h1>
        <div class="breadcrumb-text">NEKOPARA <span>›</span> Admin <span>›</span> Động vật <span>›</span> Chỉnh sửa</div>
    </div>

    <div class="card" style="margin: 0 20px 20px; padding: 20px;">
        <form action="<?= $base ?>/admin/animals/edit" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($animal['id_animal']) ?>">
            
            <div class="row">
                <!-- Cột trái: Văn bản -->
                <div class="col-md-6 mb-4">
                    <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">Thông tin chung</h4>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label font-weight-bold">Tên động vật:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($animal['name']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="classanimals_id" class="form-label font-weight-bold">Lớp động vật:</label>
                        <select class="form-select" id="classanimals_id" name="classanimals_id" required>
                            <?php foreach ($classAnimals as $classAnimal): ?>
                                <option value="<?= htmlspecialchars($classAnimal['id_class']) ?>" <?= $classAnimal['id_class'] == $animal['classanimals_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($classAnimal['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="gioi_thieu_text" class="form-label font-weight-bold">Giới thiệu:</label>
                        <textarea class="form-control" id="gioi_thieu_text" name="gioi_thieu_text" rows="4" required><?= htmlspecialchars($animal['gioi_thieu_text']) ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="ngoai_hinh_text" class="form-label font-weight-bold">Ngoại hình:</label>
                        <textarea class="form-control" id="ngoai_hinh_text" name="ngoai_hinh_text" rows="4" required><?= htmlspecialchars($animal['ngoai_hinh_text']) ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="noi_sinh_song_text" class="form-label font-weight-bold">Nơi sinh sống:</label>
                        <textarea class="form-control" id="noi_sinh_song_text" name="noi_sinh_song_text" rows="4" required><?= htmlspecialchars($animal['noi_sinh_song_text']) ?></textarea>
                    </div>
                </div>

                <!-- Cột phải: Hình ảnh -->
                <div class="col-md-6 mb-4">
                    <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">Hình ảnh</h4>
                    
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Ảnh đại diện (Avatar):</label><br>
                        <?php if(!empty($animal['avatar'])): ?>
                            <img src="<?= $base ?>/images/Animal/Avatar/<?= htmlspecialchars($animal['avatar']) ?>" style="height: 100px; border-radius: 5px; border: 1px solid #ddd;" class="mb-2"><br>
                        <?php endif; ?>
                        <input type="hidden" name="current_avatar" value="<?= htmlspecialchars($animal['avatar'] ?? '') ?>">
                        <input type="file" class="form-control" name="avatar_file" accept="image/*">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Mã QR 3D:</label><br>
                        <?php if(!empty($animal['imgqr3d'])): ?>
                            <img src="<?= $base ?>/images/Animal/3DQR/<?= htmlspecialchars($animal['imgqr3d']) ?>" style="height: 100px; border-radius: 5px; border: 1px solid #ddd;" class="mb-2"><br>
                        <?php endif; ?>
                        <input type="hidden" name="current_imgqr3d" value="<?= htmlspecialchars($animal['imgqr3d'] ?? '') ?>">
                        <input type="file" class="form-control" name="qr_file" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Ảnh nơi sinh sống (Bản đồ):</label><br>
                        <?php if(!empty($animal['noi_sinh_song_image'])): ?>
                            <img src="<?= $base ?>/images/Animal/NoiSinhSong/<?= htmlspecialchars($animal['noi_sinh_song_image']) ?>" style="height: 120px; border-radius: 5px; border: 1px solid #ddd;" class="mb-2"><br>
                        <?php endif; ?>
                        <input type="hidden" name="current_noi_sinh_song_image" value="<?= htmlspecialchars($animal['noi_sinh_song_image'] ?? '') ?>">
                        <input type="file" class="form-control" name="nss_file" accept="image/*">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label font-weight-bold">Bộ sưu tập ảnh (Đánh dấu để xóa):</label>
                        <div class="d-flex flex-wrap" style="gap: 15px; background: #f9f9f9; padding: 15px; border-radius: 5px; border: 1px solid #eee;">
                            <?php 
                            $existingImages = $animalController->getAnimalImagesById($animalId);
                            if(empty($existingImages) && isset($animalImages)) $existingImages = $animalImages;
                            if (!empty($existingImages)):
                                foreach ($existingImages as $img): 
                            ?>
                                <div class="text-center" style="border: 1px solid #ddd; padding: 5px; border-radius: 5px; background: #fff;">
                                    <img src="<?= $base ?>/images/Animal/ListImage/<?= htmlspecialchars($img['animalimage']) ?>" style="height: 80px; width: 80px; object-fit: cover; border-radius: 3px;" class="mb-1 d-block"><br>
                                    <input type="checkbox" name="delete_images[]" value="<?= $img['id'] ?>" id="del_img_<?= $img['id'] ?>">
                                    <label for="del_img_<?= $img['id'] ?>" class="text-danger" style="font-size: 0.85em; cursor:pointer; margin: 0;">Xóa</label>
                                </div>
                            <?php 
                                endforeach; 
                            else:
                            ?>
                                <span class="text-muted" style="font-size: 0.9em;">Chưa có ảnh trong bộ sưu tập</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Thêm ảnh mới vào bộ sưu tập:</label>
                        <input type="file" class="form-control" name="new_slide_images[]" multiple accept="image/*">
                        <small class="text-muted">Có thể chọn nhiều file ảnh cùng lúc.</small>
                    </div>
                </div>
            </div>

            <div style="border-top: 1px solid #ddd; padding-top: 20px; text-align: right;">
                <a href="<?= $base ?>/admin/animals" class="btn btn-secondary" style="margin-right: 10px;">Huỷ</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk" style="margin-right:5px;"></i>Cập nhật</button>
            </div>
        </form>
    </div>
    
    <?php include '../../footerAdmin.php'; ?>
</body>
</html>