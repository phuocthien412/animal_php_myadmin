<?php
require_once __DIR__ . '/../../../config/env.php';
$authController = new UserController();
$authController->authorize('ADMIN', '/Login');

if (!isset($base)) {
    require_once __DIR__ . '/../../../config/env.php';
}

if (!isset($all_notifications)) {
    $all_notifications = [];
}

include __DIR__ . '/../../headerAdmin.php';
?>

<div class="page-header">
    <h1><i class="fa-regular fa-bell" style="color:var(--green-primary);margin-right:10px;font-size:20px;"></i><?= __('admin_notifications') ?></h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> <?= __('admin_panel') ?> <span>›</span> <?= __('admin_notifications_center') ?></div>
</div>

<section style="background: linear-gradient(135deg, #ffffff 0%, hsl(145, 55%, 98%) 100%); border: 1px solid var(--border-light); border-radius: 18px; box-shadow: var(--card-shadow); padding: 18px 20px; margin-bottom: 18px;">
    <div style="display:flex; gap:16px; align-items:flex-start; justify-content:space-between; flex-wrap:wrap;">
        <div style="min-width: min(100%, 420px);">
            <div style="font-size:12px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--green-primary); margin-bottom:8px;"><?= __('admin_notifications_live_feed') ?></div>
            <h2 style="font-family:var(--font-display); font-size:22px; line-height:1.2; margin:0 0 8px; color:var(--text-primary);"><?= __('admin_notifications_subtitle') ?></h2>
            <p style="margin:0; color:var(--text-secondary); max-width:62ch;"><?= __('admin_notifications_description') ?></p>
        </div>
        <a href="<?= $base ?>/admin/dashboard" class="notification-panel-link" style="display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border:1px solid var(--border-medium); border-radius:999px; background:#fff; text-decoration:none;">
            <i class="fa-solid fa-gauge-high"></i>
            <?= __('admin_notifications_back_dash') ?>
        </a>
    </div>
</section>

<section style="background:#fff; border:1px solid var(--border-light); border-radius:18px; box-shadow:var(--card-shadow); overflow:hidden;">
    <div style="padding:16px 18px; border-bottom:1px solid var(--border-light); display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div>
            <div style="font-size:14px; font-weight:700; color:var(--text-primary);"><?= __('admin_notifications_list_title') ?></div>
            <div style="font-size:12px; color:var(--text-muted); margin-top:3px;"><?= sprintf(__('admin_notifications_recent_items'), count($all_notifications)) ?></div>
        </div>
        <div style="font-size:12px; color:var(--text-muted);"><?= __('admin_notifications_showing_data') ?></div>
    </div>

    <div id="notification-list-container" style="max-height: 72vh; overflow:auto;">
        <?php if (empty($all_notifications)): ?>
            <div style="padding:28px 18px; text-align:center; color:var(--text-muted);">
                <div style="font-size:18px; font-weight:700; color:var(--text-primary); margin-bottom:6px;"><?= __('admin_notifications_empty') ?></div>
                <div><?= __('admin_notifications_empty_desc') ?></div>
            </div>
        <?php else: ?>
            <?php foreach ($all_notifications as $notification): ?>
                <?php
                    $notificationType = $notification['type'] ?? 'general';
                    $notificationLink = $base . ($notification['link'] ?? '/admin/notifications/');
                    $notificationIcon = 'fa-bell';
                    if ($notificationType === 'post') {
                        $notificationIcon = 'fa-newspaper';
                    } elseif ($notificationType === 'comment') {
                        $notificationIcon = 'fa-comments';
                    } elseif ($notificationType === 'animal') {
                        $notificationIcon = 'fa-dragon';
                    } elseif ($notificationType === 'classanimal') {
                        $notificationIcon = 'fa-layer-group';
                    } elseif ($notificationType === 'user') {
                        $notificationIcon = 'fa-users';
                    } elseif ($notificationType === 'role') {
                        $notificationIcon = 'fa-user-shield';
                    }
                ?>
                <a href="<?php echo htmlspecialchars($notificationLink); ?>" class="notification-item" data-notification-key="<?php echo htmlspecialchars(($notificationType ?? '') . ':' . ($notification['id'] ?? '')); ?>" style="display:flex; gap:12px; align-items:flex-start; padding:14px 18px; text-decoration:none; border-bottom:1px solid var(--border-light);">
                    <span class="notification-icon notification-<?php echo htmlspecialchars($notificationType); ?>">
                        <i class="fa-solid <?php echo htmlspecialchars($notificationIcon); ?>"></i>
                    </span>
                    <span class="notification-content" style="flex:1; min-width:0;">
                        <strong class="notification-title"><?php echo htmlspecialchars($notification['title'] ?? __('admin_notifications')); ?></strong>
                        <span class="notification-message"><?php echo htmlspecialchars($notification['message'] ?? $notification['action'] ?? ''); ?></span>
                        <small style="color:var(--text-muted); margin-top:4px; display:block;"><?php echo htmlspecialchars(!empty($notification['created_at']) ? date('d/m/Y H:i', strtotime($notification['created_at'])) : ''); ?></small>
                    </span>
                    <span class="badge" style="background:<?php echo $notificationType === 'post' ? 'var(--accent-blue)' : 'var(--accent-orange)'; ?>; color:#fff; border-radius:999px; padding:6px 10px; font-size:11px; font-weight:700; flex-shrink:0;">
                        <?php echo htmlspecialchars(__('notif_type_' . $notificationType)); ?>
                    </span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../../footerAdmin.php'; ?>
