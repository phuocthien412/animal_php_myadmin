<?php
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error   = isset($_GET['error'])   ? $_GET['error']   : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>NEKOPARA — Quản lý động vật</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
include '../../headerAdmin.php';

require_once '../../../controller/AnimalController.php';
$animalController = new AnimalController();
$animals  = $animalController->getAllAnimals();
$isAdmin  = isset($_SESSION['roles']) && in_array('ADMIN', $_SESSION['roles']);
?>

<div class="page-header">
    <h1><i class="fa-solid fa-dragon" style="color:var(--green-primary);margin-right:10px;font-size:20px;"></i>Quản lý động vật</h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> Admin <span>›</span> Động vật</div>
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
            <div class="card-title"><i class="fa-solid fa-dragon" style="color:var(--green-primary);margin-right:8px;"></i>Danh sách động vật</div>
            <div class="card-subtitle">Tổng cộng <?= count($animals) ?> động vật trong hệ thống</div>
        </div>
    </div>
    <div class="table-toolbar">
        <div class="table-search">
            <i class="fa-solid fa-search"></i>
            <input type="text" id="animalSearch" placeholder="Tìm tên động vật..." />
        </div>
        <div class="table-actions">
            <?php if ($isAdmin): ?>
            <a href="<?= $base ?>/admin/animals/add" class="btn-admin btn-admin-primary">
                <i class="fa-solid fa-plus"></i> Thêm động vật
            </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="table-responsive-wrap">
        <table class="admin-table" id="animalsTable">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Ảnh đại diện</th>
                    <th>Tên động vật</th>
                    <th>Lớp</th>
                    <th>Giới thiệu</th>
                    <?php if ($isAdmin): ?><th>Hành động</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($animals)): ?>
                <tr><td colspan="6">
                    <div class="empty-state">
                        <i class="fa-solid fa-dragon"></i>
                        <p>Chưa có động vật nào</p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($animals as $animal): ?>
                <tr>
                    <td><span style="font-size:12px;color:var(--text-muted);font-weight:500;">#<?= htmlspecialchars($animal['id_animal']) ?></span></td>
                    <td class="animal-img-cell">
                        <img src="/animal_php_myadmin/images/<?= htmlspecialchars($animal['avatar']) ?>" alt="<?= htmlspecialchars($animal['name']) ?>">
                    </td>
                    <td><strong><?= htmlspecialchars($animal['name']) ?></strong></td>
                    <td><span class="role-badge default">Lớp #<?= htmlspecialchars($animal['classanimals_id']) ?></span></td>
                    <td style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:13px;color:var(--text-secondary);">
                        <?= htmlspecialchars(substr($animal['gioi_thieu_text'] ?? '', 0, 80)) ?>...
                    </td>
                    <?php if ($isAdmin): ?>
                    <td>
                        <div class="action-btns">
                            <a href="<?= $base ?>/animal/detail/<?= urlencode($animal['id_animal']) ?>"
                               class="action-btn view" title="Xem chi tiết">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="<?= $base ?>/admin/animals/edit/<?= urlencode($animal['id_animal']) ?>"
                               class="action-btn edit" title="Chỉnh sửa">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="<?= $base ?>/admin/animals/delete/<?= urlencode($animal['id_animal']) ?>"
                               class="action-btn delete" title="Xoá"
                               onclick="return confirm('Bạn có chắc muốn xoá động vật này?')">
                                <i class="fa-solid fa-trash"></i>
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

<script>
document.getElementById('animalSearch')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#animalsTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>

<?php include '../../footerAdmin.php'; ?>
</body>
</html>