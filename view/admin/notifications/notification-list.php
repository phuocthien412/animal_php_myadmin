<?php
if (!isset($base)) {
    require_once __DIR__ . '/../../../config/env.php';
}

if (!isset($all_notifications)) {
    $all_notifications = [];
}

include __DIR__ . '/../../headerAdmin.php';
?>

<div class="page-header">
    <h1><i class="fa-regular fa-bell" style="color:var(--green-primary);margin-right:10px;font-size:20px;"></i>Thông báo</h1>
    <div class="breadcrumb-text">NEKOPARA <span>›</span> Admin <span>›</span> Trung tâm thông báo</div>
</div>

<section style="background: linear-gradient(135deg, #ffffff 0%, hsl(145, 55%, 98%) 100%); border: 1px solid var(--border-light); border-radius: 18px; box-shadow: var(--card-shadow); padding: 18px 20px; margin-bottom: 18px;">
    <div style="display:flex; gap:16px; align-items:flex-start; justify-content:space-between; flex-wrap:wrap;">
        <div style="min-width: min(100%, 420px);">
            <div style="font-size:12px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--green-primary); margin-bottom:8px;">Live feed</div>
            <h2 style="font-family:var(--font-display); font-size:22px; line-height:1.2; margin:0 0 8px; color:var(--text-primary);">Theo dõi bài viết và bình luận mới ở một nơi</h2>
            <p style="margin:0; color:var(--text-secondary); max-width:62ch;">Trang này tổng hợp các thông báo quản trị gần nhất, tự động cập nhật qua SSE và giữ nguyên liên kết đến nội dung gốc.</p>
        </div>
        <a href="<?= $base ?>/admin/dashboard" class="notification-panel-link" style="display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border:1px solid var(--border-medium); border-radius:999px; background:#fff; text-decoration:none;">
            <i class="fa-solid fa-gauge-high"></i>
            Về dashboard
        </a>
    </div>
</section>

<section style="background:#fff; border:1px solid var(--border-light); border-radius:18px; box-shadow:var(--card-shadow); overflow:hidden;">
    <div style="padding:16px 18px; border-bottom:1px solid var(--border-light); display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div>
            <div style="font-size:14px; font-weight:700; color:var(--text-primary);">Danh sách thông báo</div>
            <div style="font-size:12px; color:var(--text-muted); margin-top:3px;"><?php echo count($all_notifications); ?> mục gần nhất</div>
        </div>
        <div style="font-size:12px; color:var(--text-muted);">Đang hiển thị dữ liệu hiện có</div>
    </div>

    <div id="notification-list-container" style="max-height: 72vh; overflow:auto;">
        <?php if (empty($all_notifications)): ?>
            <div style="padding:28px 18px; text-align:center; color:var(--text-muted);">
                <div style="font-size:18px; font-weight:700; color:var(--text-primary); margin-bottom:6px;">Chưa có thông báo nào</div>
                <div>Những bài viết hoặc bình luận mới sẽ xuất hiện ở đây.</div>
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
                        <strong class="notification-title"><?php echo htmlspecialchars($notification['title'] ?? 'Thông báo'); ?></strong>
                        <span class="notification-message"><?php echo htmlspecialchars($notification['message'] ?? $notification['action'] ?? ''); ?></span>
                        <small style="color:var(--text-muted); margin-top:4px; display:block;"><?php echo htmlspecialchars(!empty($notification['created_at']) ? date('d/m/Y H:i', strtotime($notification['created_at'])) : ''); ?></small>
                    </span>
                    <span class="badge" style="background:<?php echo $notificationType === 'post' ? 'var(--accent-blue)' : 'var(--accent-orange)'; ?>; color:#fff; border-radius:999px; padding:6px 10px; font-size:11px; font-weight:700; flex-shrink:0;">
                        <?php echo htmlspecialchars(ucfirst($notificationType)); ?>
                    </span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../../footerAdmin.php'; ?>
