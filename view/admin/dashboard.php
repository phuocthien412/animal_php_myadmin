<?php
require_once __DIR__ . '/../../config/env.php';
$authController = new UserController();
$authController->authorize('ADMIN', '/Home');

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error   = isset($_GET['error'])   ? $_GET['error']   : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>NEKOPARA — Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
// Header (sidebar + topbar + $base defined)
include '../headerAdmin.php';
// $base now available from base_url.php included in headerAdmin

// Controllers

$userController        = new UserController();
$animalController      = new AnimalController();
$postController        = new PostController();
$commentController     = new CommentController();
$classAnimalController = new ClassAnimalController();

// Fetch data
$users    = $userController->getAllUsersWithRoles();
$animals  = $animalController->getAllAnimals();
$posts    = $postController->getAllPosts();
$comments = $commentController->getAllComments();
$classes  = $classAnimalController->getAllClassAnimals();

// Stats
$totalUsers    = count($users);
$totalAnimals  = count($animals);
$totalPosts    = count($posts);
$totalComments = count($comments);

// Role distribution for pie chart
$roleCount = ['ADMIN' => 0, 'USER' => 0, 'OTHER' => 0];
foreach ($users as $u) {
    $roles = array_map('strtoupper', $u['roles']);
    if (in_array('ADMIN', $roles))       $roleCount['ADMIN']++;
    elseif (in_array('USER', $roles))    $roleCount['USER']++;
    else                                  $roleCount['OTHER']++;
}

// Animals per class for bar chart
$animalPerClass = [];
foreach ($animals as $a) {
    $cid = $a['classanimals_id'] ?? 'N/A';
    $animalPerClass[$cid] = ($animalPerClass[$cid] ?? 0) + 1;
}
arsort($animalPerClass);

// Class names map
$classMap = [];
foreach ($classes as $c) {
    $classMap[$c['id_class']] = $c['name'] ?? ('Lớp ' . $c['id_class']);
}

$chartLabels = [];
$chartData   = [];
foreach ($animalPerClass as $cid => $cnt) {
    $chartLabels[] = $classMap[$cid] ?? ('Lớp #' . $cid);
    $chartData[]   = $cnt;
}
?>

<!-- ===================== PAGE HEADER ===================== -->
<div class="page-header">
    <h1><i class="fa-solid fa-gauge-high" style="color:var(--green-primary);margin-right:10px;font-size:20px;"></i><?= __('admin_dashboard') ?> — <?= __('admin_panel') ?></h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> Admin <span>›</span> <?= __('admin_overview') ?></div>
</div>

<?php if ($success): ?>
    <div class="alert-admin success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert-admin danger"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- ===================== STAT CARDS ===================== -->
<div class="stats-grid">
    <div class="stat-card" style="--stat-color: hsl(152,55%,30%);">
        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
        <div class="stat-body">
            <div class="stat-value"><?= $totalUsers ?></div>
            <div class="stat-label"><?= __('dash_total_users') ?></div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-trend-up"></i> <?= __('admin_system') ?></div>
        </div>
    </div>
    <div class="stat-card" style="--stat-color: hsl(213,90%,55%);">
        <div class="stat-icon"><i class="fa-solid fa-dragon"></i></div>
        <div class="stat-body">
            <div class="stat-value"><?= $totalAnimals ?></div>
            <div class="stat-label"><?= __('admin_animals') ?></div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-trend-up"></i> <?= sprintf(__('dash_in_classes'), count($classes)) ?></div>
        </div>
    </div>
    <div class="stat-card" style="--stat-color: hsl(32,90%,55%);">
        <div class="stat-icon"><i class="fa-solid fa-newspaper"></i></div>
        <div class="stat-body">
            <div class="stat-value"><?= $totalPosts ?></div>
            <div class="stat-label"><?= __('admin_posts') ?></div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-trend-up"></i> <?= __('community') ?></div>
        </div>
    </div>
    <div class="stat-card" style="--stat-color: hsl(265,70%,55%);">
        <div class="stat-icon"><i class="fa-solid fa-comments"></i></div>
        <div class="stat-body">
            <div class="stat-value"><?= $totalComments ?></div>
            <div class="stat-label"><?= __('admin_comments') ?></div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-trend-up"></i> <?= __('dash_interactions') ?></div>
        </div>
    </div>
</div>

<!-- ===================== CHARTS ROW ===================== -->
<div class="chart-row">
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title"><i class="fa-solid fa-chart-bar" style="color:var(--green-primary);margin-right:8px;"></i><?= __('dash_animal_by_class') ?></div>
                <div class="card-subtitle"><?= __('dash_animal_by_class_desc') ?></div>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-wrap"><canvas id="chartAnimalByClass"></canvas></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title"><i class="fa-solid fa-chart-pie" style="color:var(--accent-purple);margin-right:8px;"></i><?= __('dash_user_distribution') ?></div>
                <div class="card-subtitle"><?= __('dash_user_role_ratio') ?></div>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-wrap" style="height:200px;"><canvas id="chartUserRoles"></canvas></div>
            <ul class="mini-list" style="margin-top:16px;">
                <li class="mini-list-item">
                    <span class="mini-dot" style="background:hsl(265,70%,55%);"></span>
                    <div class="mini-info"><div class="mini-label">Admin</div></div>
                    <div class="mini-value"><?= $roleCount['ADMIN'] ?></div>
                </li>
                <li class="mini-list-item">
                    <span class="mini-dot" style="background:hsl(152,55%,35%);"></span>
                    <div class="mini-info"><div class="mini-label">User</div></div>
                    <div class="mini-value"><?= $roleCount['USER'] ?></div>
                </li>
                <li class="mini-list-item">
                    <span class="mini-dot" style="background:hsl(32,90%,55%);"></span>
                    <div class="mini-info"><div class="mini-label"><?= __('dash_other') ?></div></div>
                    <div class="mini-value"><?= $roleCount['OTHER'] ?></div>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- ===================== CHARTS JS ===================== -->
<script>
(function() {
    const ctx = document.getElementById('chartAnimalByClass');
    if (!ctx) return;
    const labels = <?= json_encode(array_values($chartLabels)) ?>;
    const data   = <?= json_encode(array_values($chartData)) ?>;
    const colors = ['hsl(152,55%,35%)','hsl(168,60%,40%)','hsl(175,65%,42%)','hsl(190,60%,45%)','hsl(213,90%,55%)','hsl(230,80%,60%)','hsl(265,70%,58%)','hsl(32,90%,58%)'];
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Số lượng',
                data,
                backgroundColor: labels.map((_, i) => colors[i % colors.length] + 'CC'),
                borderColor:     labels.map((_, i) => colors[i % colors.length]),
                borderWidth: 2, borderRadius: 8, borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,.05)' } },
                x: { grid: { display: false } }
            }
        }
    });
})();

(function() {
    const ctx = document.getElementById('chartUserRoles');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Admin', 'User', '<?= __('dash_other') ?>'],
            datasets: [{
                data: [<?= $roleCount['ADMIN'] ?>, <?= $roleCount['USER'] ?>, <?= $roleCount['OTHER'] ?>],
                backgroundColor: ['hsl(265,70%,58%)','hsl(152,55%,38%)','hsl(32,90%,58%)'],
                borderWidth: 3, borderColor: '#fff', hoverOffset: 6,
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '68%', plugins: { legend: { display: false } } }
    });
})();
</script>

<?php include '../footerAdmin.php'; ?>
</body>
</html>
