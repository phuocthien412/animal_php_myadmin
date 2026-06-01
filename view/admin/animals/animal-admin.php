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
    <title><?= __('admin_animals_title') ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
include '../../headerAdmin.php';

$animalController = new AnimalController();
$allAnimals  = $animalController->getAllAnimals();
$perPage = 10;
$currentPage = max(1, (int)($_GET['page'] ?? 1));
$totalAnimals = count($allAnimals);
$totalPages = max(1, (int)ceil($totalAnimals / $perPage));
$currentPage = min($currentPage, $totalPages);
$offset = ($currentPage - 1) * $perPage;
$animals = array_slice($allAnimals, $offset, $perPage);
$isAdmin  = isset($_SESSION['roles']) && in_array('ADMIN', $_SESSION['roles']);
?>

<div class="page-header">
    <h1><i class="fa-solid fa-dragon" style="color:var(--green-primary);margin-right:10px;font-size:20px;"></i><?= __('admin_animals_manage') ?></h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> <?= __('admin_panel') ?> <span>›</span> <?= __('admin_animals') ?></div>
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
            <div class="card-title"><i class="fa-solid fa-dragon" style="color:var(--green-primary);margin-right:8px;"></i><?= __('admin_animals_list') ?></div>
            <div class="card-subtitle"><?= sprintf(__('admin_animals_total_desc'), $totalAnimals) ?></div>
        </div>
    </div>
    <div class="table-toolbar">
        <div class="table-search">
            <i class="fa-solid fa-search"></i>
            <input type="text" id="animalSearch" placeholder="<?= __('admin_animals_search_placeholder') ?>" />
        </div>
        <div class="table-actions">
            <?php if ($isAdmin): ?>
            <a href="<?= $base ?>/admin/animals/add" class="btn-admin btn-admin-primary">
                <i class="fa-solid fa-plus"></i> <?= __('dash_add_animal') ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="table-responsive-wrap">
        <table class="admin-table" id="animalsTable">
            <thead>
                <tr>
                    <th style="width: 5%">#<?= __('table_id') ?></th>
                    <th style="width: 10%"><?= __('table_avatar') ?></th>
                    <th style="width: 20%"><?= __('table_animal_name') ?></th>
                    <th style="width: 15%"><?= __('table_class') ?></th>
                    <th style="width: 35%"><?= __('table_introduction') ?></th>
                    <?php if ($isAdmin): ?><th style="width: 15%"><?= __('table_action') ?></th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($animals)): ?>
                <tr><td colspan="6">
                    <div class="empty-state">
                        <i class="fa-solid fa-dragon"></i>
                        <p><?= __('admin_animals_empty') ?></p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($animals as $animal): ?>
                <tr>
                    <td><span style="font-size:12px;color:var(--text-muted);font-weight:500;">#<?= htmlspecialchars($animal['id_animal']) ?></span></td>
                    <td class="animal-img-cell">
                        <img src="<?= $base ?>/images/Animal/Avatar/<?= htmlspecialchars($animal['avatar']) ?>" alt="<?= htmlspecialchars($animal['name']) ?>">
                    </td>
                    <td><strong><?= htmlspecialchars($animal['name']) ?></strong></td>
                    <td><span class="role-badge default"><?= __('admin_animals_class_label') ?><?= htmlspecialchars($animal['classanimals_id']) ?></span></td>
                    <td style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:13px;color:var(--text-secondary);">
                        <?= htmlspecialchars(substr($animal['gioi_thieu_text'] ?? '', 0, 80)) ?>...
                    </td>
                    <?php if ($isAdmin): ?>
                    <td>
                        <div class="action-btns">
                            <a href="<?= $base ?>/admin/animals/detail/<?= urlencode($animal['id_animal']) ?>"
                               class="action-btn view" title="<?= __('action_view_details') ?>">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="<?= $base ?>/admin/animals/edit/<?= urlencode($animal['id_animal']) ?>"
                               class="action-btn edit" title="<?= __('action_edit') ?>">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="<?= $base ?>/admin/animals/delete/<?= urlencode($animal['id_animal']) ?>"
                               class="action-btn delete" title="<?= __('action_delete') ?>"
                               onclick="return confirm('<?= htmlspecialchars(__('confirm_delete_animal'), ENT_QUOTES) ?>')">
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

<?php if ($totalPages > 1): ?>
<div class="admin-pagination">
    <nav aria-label="Pagination">
        <ul class="pagination">
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $base ?>/admin/animals?page=<?= max(1, $currentPage - 1) ?>"><?= __('pagination_prev') ?></a>
            </li>
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="<?= $base ?>/admin/animals?page=<?= $p ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $base ?>/admin/animals?page=<?= min($totalPages, $currentPage + 1) ?>"><?= __('pagination_next') ?></a>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>

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