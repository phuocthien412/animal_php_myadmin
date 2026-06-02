<?php
require_once __DIR__ . '/../../../config/env.php';
$authController = new UserController();
$authController->authorize('ADMIN', '/Login');

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



<div class="card table-card">
    <div class="card-header">
        <div>
            <div class="card-title"><i class="fa-solid fa-layer-group" style="color:var(--accent-teal);margin-right:8px;"></i><?= __('admin_classanimal_list') ?></div>
            <div class="card-subtitle"><?= sprintf(__('admin_classanimal_desc'), $totalClassAnimals) ?></div>
        </div>
        <?php if ($isAdmin): ?>
        <div class="card-tools">
            <a href="<?= $base ?>/admin/classanimals/add" class="btn btn-primary" style="padding: 8px 16px; border-radius: 6px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-plus"></i><?= __('btn_add_classanimal') ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
    <div class="table-responsive-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#<?= __('table_id') ?></th>
                    <th><?= __('table_media') ?></th>
                    <th><?= __('table_class_name') ?></th>
                    <th><?= __('table_animal_count') ?></th>
                    <th><?= __('table_info') ?></th>
                    <?php if ($isAdmin): ?><th><?= __('table_action') ?></th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($classAnimals)): ?>
                <tr><td colspan="6">
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
                    <td>
                        <?php 
                        $animalCount = $classAnimalController->getAnimalsCountByClassId($cls['id_class']);
                        ?>
                        <span class="badge rounded-pill <?php echo $animalCount > 0 ? 'bg-primary' : 'bg-secondary'; ?>" style="font-size: 12px; font-weight: 600; padding: 6px 12px;">
                            <?= $animalCount ?>
                        </span>
                    </td>
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
                            <?php if ($animalCount > 0): ?>
                                <button type="button" class="action-btn delete" style="opacity: 0.5; cursor: not-allowed;" onclick="event.stopPropagation(); showToast('<?= htmlspecialchars(__('msg_classanimal_has_animals'), ENT_QUOTES) ?>', 'danger');" title="<?= __('action_delete') ?? 'Xoá' ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            <?php else: ?>
                                <a href="<?= $base ?>/admin/classanimals/delete/<?= urlencode($cls['id_class']) ?>"
                                   class="action-btn delete" title="<?= __('action_delete') ?? 'Xoá' ?>"
                                   data-confirm="<?= htmlspecialchars(__('confirm_delete_classanimal'), ENT_QUOTES) ?>"
                                   data-confirm-title="<?= htmlspecialchars(__('action_delete') ?? 'Xóa', ENT_QUOTES) ?>"
                                   data-confirm-type="danger">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            <?php endif; ?>
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