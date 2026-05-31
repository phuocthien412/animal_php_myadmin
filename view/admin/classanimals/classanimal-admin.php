<?php
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error   = isset($_GET['error'])   ? $_GET['error']   : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>NEKOPARA — Lớp động vật</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
include '../../headerAdmin.php';

require_once '../../../controller/ClassAnimalController.php';
$classAnimalController = new ClassAnimalController();
$classAnimals = $classAnimalController->getAllClassAnimals();
$isAdmin      = isset($_SESSION['roles']) && in_array('ADMIN', $_SESSION['roles']);
?>

<div class="page-header">
    <h1><i class="fa-solid fa-layer-group" style="color:var(--accent-teal);margin-right:10px;font-size:20px;"></i>Lớp động vật</h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> Admin <span>›</span> Lớp động vật</div>
</div>

<?php if ($success): ?>
    <div class="alert-admin success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert-admin danger"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card table-card">
    <div class="card-header">
        <div>
            <div class="card-title"><i class="fa-solid fa-layer-group" style="color:var(--accent-teal);margin-right:8px;"></i>Danh sách lớp động vật</div>
            <div class="card-subtitle">Tổng cộng <?= count($classAnimals) ?> lớp phân loại</div>
        </div>
    </div>
    <div class="table-responsive-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Ảnh / Video</th>
                    <th>Tên lớp</th>
                    <th>Thông tin</th>
                    <?php if ($isAdmin): ?><th>Hành động</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($classAnimals)): ?>
                <tr><td colspan="4">
                    <div class="empty-state">
                        <i class="fa-solid fa-layer-group"></i>
                        <p>Chưa có lớp động vật nào</p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($classAnimals as $cls): ?>
                <tr>
                    <td><span style="font-size:12px;color:var(--text-muted);font-weight:500;">#<?= htmlspecialchars($cls['id_class']) ?></span></td>
                    <td>
                        <?php 
                        $mediaName = htmlspecialchars($cls['background_video'] ?? '');
                        $ext = strtolower(pathinfo($mediaName, PATHINFO_EXTENSION));
                        $isVideo = in_array($ext, ['mp4', 'webm', 'ogg']);
                        if(!empty($mediaName)): 
                            if($isVideo): ?>
                                <video src="<?= $base ?>/images/ClassAnimal/<?= $mediaName ?>" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;" muted></video>
                            <?php else: ?>
                                <img src="<?= $base ?>/images/ClassAnimal/<?= $mediaName ?>" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <?php endif;
                        else: ?>
                            <span class="text-muted" style="font-size:12px;">Trống</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($cls['name']) ?></strong></td>
                    <td style="max-width:350px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:13px;color:var(--text-secondary);">
                        <?= htmlspecialchars(substr($cls['info'] ?? '', 0, 100)) ?>...
                    </td>
                    <?php if ($isAdmin): ?>
                    <td>
                        <div class="action-btns">
                            <a href="<?= $base ?>/admin/classanimals/detail/<?= urlencode($cls['id_class']) ?>"
                               class="action-btn view" title="Xem lớp">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="<?= $base ?>/admin/classanimals/edit/<?= urlencode($cls['id_class']) ?>"
                               class="action-btn edit" title="Chỉnh sửa">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../footerAdmin.php'; ?>
</body>
</html>