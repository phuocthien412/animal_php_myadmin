<?php
require_once __DIR__ . '/../../../config/env.php';
$authController = new UserController();
$authController->authorize('ADMIN', '/Home');

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error   = isset($_GET['error'])   ? $_GET['error']   : '';
require_once __DIR__ . '/../../../config/env.php';
$base = BASE_URL;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>NEKOPARA — <?= __('admin_classanimals') ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
include '../../headerAdmin.php';

$classAnimalController = new ClassAnimalController();
$allClassAnimals = $classAnimalController->getAllClassAnimals();
$perPage = 10;
$currentPage = max(1, (int)($_GET['page'] ?? 1));
$totalClassAnimals = count($allClassAnimals);
$totalPages = max(1, (int)ceil($totalClassAnimals / $perPage));
$currentPage = min($currentPage, $totalPages);
$offset = ($currentPage - 1) * $perPage;
$classAnimals = array_slice($allClassAnimals, $offset, $perPage);
$isAdmin      = isset($_SESSION['roles']) && in_array('ADMIN', $_SESSION['roles']);
?>

<div class="page-header">
    <h1><i class="fa-solid fa-layer-group" style="color:var(--accent-teal);margin-right:10px;font-size:20px;"></i><?= __('admin_classanimals') ?></h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> <?= __('admin') ?> <span>›</span> <?= __('admin_classanimals') ?></div>
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
            <div class="card-title"><i class="fa-solid fa-layer-group" style="color:var(--accent-teal);margin-right:8px;"></i><?= __('admin_classanimal_list') ?></div>
            <div class="card-subtitle"><?= sprintf(__('admin_classanimal_desc'), $totalClassAnimals) ?></div>
        </div>
    </div>
    <div class="table-responsive-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#<?= __('table_id') ?></th>
                    <th><?= __('table_media') ?></th>
                    <th><?= __('table_class_name') ?></th>
                    <th><?= __('table_info') ?></th>
                    <?php if ($isAdmin): ?><th><?= __('table_action') ?></th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($classAnimals)): ?>
                <tr><td colspan="4">
                    <div class="empty-state">
                        <i class="fa-solid fa-layer-group"></i>
                        <p><?= __('admin_no_classanimals') ?></p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($classAnimals as $cls): ?>
                <tr onclick="if(window.getSelection().toString().length === 0 && !event.target.closest('.action-btns')) window.location='<?= $base ?>/admin/classanimals/detail/<?= urlencode($cls['id_class']) ?>'" style="cursor: pointer;" title="Nhấn vào để xem chi tiết">
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
                            <span class="text-muted" style="font-size:12px;"><?= __('empty') ?></span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($cls['name']) ?></strong></td>
                    <td style="max-width:350px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:13px;color:var(--text-secondary);">
                        <?= htmlspecialchars(substr($cls['info'] ?? '', 0, 100)) ?>...
                    </td>
                    <?php if ($isAdmin): ?>
                    <td>
                        <div class="action-btns">

                            <a href="<?= $base ?>/admin/classanimals/edit/<?= urlencode($cls['id_class']) ?>"
                               class="action-btn edit" title="<?= __('btn_edit') ?>">
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

<?php if ($totalPages > 1): ?>
<div class="admin-pagination">
    <nav aria-label="Pagination">
        <ul class="pagination">
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $base ?>/admin/classanimals?page=<?= max(1, $currentPage - 1) ?>"><?= __('pagination_prev') ?></a>
            </li>
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="<?= $base ?>/admin/classanimals?page=<?= $p ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $base ?>/admin/classanimals?page=<?= min($totalPages, $currentPage + 1) ?>"><?= __('pagination_next') ?></a>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>

<?php include '../../footerAdmin.php'; ?>
</body>
</html>