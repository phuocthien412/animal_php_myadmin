<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/env.php'; // Load BASE_URL từ .env → $base
require_once __DIR__ . '/../model/Notification.php';

$adminNotifications = Notification::getRecent(5);
$adminNotificationCount = Notification::getUnreadCount();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NEKOPARA — Admin Panel</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?= $base ?>/css/admin.css" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="admin-body">

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ===================== SIDEBAR ===================== -->
<aside class="admin-sidebar" id="adminSidebar">

    <!-- Brand -->
    <a href="<?= $base ?>/Home" class="sidebar-brand">
        <div class="sidebar-brand-text">
            <span class="sidebar-brand-name">NEKOPARA</span>
            <span class="sidebar-brand-sub">Admin Console</span>
        </div>
    </a>

    <!-- Nav -->
    <nav class="sidebar-nav">

        <div class="sidebar-section-label">Tổng quan</div>

        <a href="<?= $base ?>/admin/dashboard"
           class="sidebar-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false) ? 'active' : ''; ?>">
            <span class="si-icon"><i class="fa-solid fa-gauge-high"></i></span>
            Dashboard
        </a>

        <div class="sidebar-section-label">Quản lý nội dung</div>

        <a href="<?= $base ?>/admin/animals"
           class="sidebar-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/animals') !== false) ? 'active' : ''; ?>">
            <span class="si-icon"><i class="fa-solid fa-dragon"></i></span>
            Động vật
        </a>

        <a href="<?= $base ?>/admin/classanimals"
           class="sidebar-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/classanimals') !== false) ? 'active' : ''; ?>">
            <span class="si-icon"><i class="fa-solid fa-layer-group"></i></span>
            Lớp động vật
        </a>

        <a href="<?= $base ?>/admin/posts"
           class="sidebar-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/posts') !== false) ? 'active' : ''; ?>">
            <span class="si-icon"><i class="fa-solid fa-newspaper"></i></span>
            Bài viết
        </a>

        <a href="<?= $base ?>/admin/comments"
           class="sidebar-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/comments') !== false) ? 'active' : ''; ?>">
            <span class="si-icon"><i class="fa-solid fa-comments"></i></span>
            Bình luận
        </a>

        <a href="<?= $base ?>/admin/notifications"
            class="sidebar-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/notifications') !== false) ? 'active' : ''; ?>">
            <span class="si-icon"><i class="fa-solid fa-bell"></i></span>
            Thông báo
        </a>

        <div class="sidebar-section-label">Hệ thống</div>

        <?php if (isset($_SESSION['roles']) && in_array('ADMIN', $_SESSION['roles'])): ?>
        <a href="<?= $base ?>/admin/users"
           class="sidebar-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false) ? 'active' : ''; ?>">
            <span class="si-icon"><i class="fa-solid fa-users-gear"></i></span>
            Tài khoản
        </a>
        <?php endif; ?>

    </nav>

    <!-- Footer / User -->
</div>
    <!-- End of Sidebar -->

</aside>

<!-- ===================== TOP BAR ===================== -->
<div class="admin-topbar">
    <button class="topbar-toggle" id="sidebarToggle">
        <i class="fa-solid fa-bars"></i>
    </button>

    <button class="topbar-toggle topbar-collapse-toggle" id="sidebarCollapseToggle" title="Ẩn/hiện thanh bên" aria-label="Ẩn/hiện thanh bên">
        <i class="fa-solid fa-angles-left"></i>
    </button>

    <div class="topbar-title" id="topbarTitle">
        Dashboard <span>/ Quản trị</span>
    </div>

    <div class="topbar-actions" style="display: flex; align-items: center;">
        <a href="<?= $base ?>/Home" class="topbar-btn topbar-home-btn" title="Về trang chủ" aria-label="Về trang chủ">
            <i class="fa-solid fa-house"></i>
        </a>

        <div class="topbar-notification-wrap">
            <button class="topbar-btn topbar-notification-btn" id="notificationToggle" title="Thông báo" aria-label="Thông báo" aria-expanded="false" aria-controls="adminNotificationPanel">
                <i class="fa-regular fa-bell"></i>
                <?php if ($adminNotificationCount > 0): ?>
                    <span class="badge-dot"></span>
                    <span class="topbar-badge"><?= $adminNotificationCount ?></span>
                <?php endif; ?>
            </button>

            <div class="notification-panel" id="adminNotificationPanel" hidden>
                <div class="notification-panel-header">
                    <div>
                        <div class="notification-panel-title">Thông báo</div>
                            <div class="notification-panel-subtitle"><?= $adminNotificationCount ?> hành động gần nhất</div>
                    </div>
                            <a href="<?= $base ?>/admin/notifications/" class="notification-panel-link">Xem tất cả</a>
                </div>

                <div class="notification-panel-list">
                    <?php if (!empty($adminNotifications)): ?>
                        <?php foreach ($adminNotifications as $notification): ?>
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
                            <a class="notification-item" href="<?= htmlspecialchars($notificationLink) ?>" style="display:flex !important; align-items:flex-start !important; gap:12px !important; width:100%; box-sizing:border-box; white-space:normal !important;">
                                <span class="notification-icon notification-<?= htmlspecialchars($notificationType) ?>" style="flex:0 0 36px;">
                                    <i class="fa-solid <?= htmlspecialchars($notificationIcon) ?>"></i>
                                </span>
                                <span class="notification-content" style="flex:1; min-width:0; display:flex !important; flex-direction:column !important; align-items:flex-start; gap:2px;">
                                    <span class="notification-title" style="display:block; line-height:1.25;"><?= htmlspecialchars($notification['title']) ?></span>
                                    <span class="notification-action" style="display:block; line-height:1.35; white-space:normal; word-break:break-word;"><?= htmlspecialchars($notification['action'] ?? $notification['message'] ?? $notification['title']) ?></span>
                                    <span class="notification-time" style="display:block; line-height:1.3; white-space:nowrap;">
                                        <?= htmlspecialchars(!empty($notification['created_at']) ? date('d/m/Y H:i', strtotime($notification['created_at'])) : '') ?>
                                    </span>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="notification-empty">Chưa có thông báo mới.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <a href="<?= $base ?>/admin/profile" class="topbar-user" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
            <?php 
                $headerAvatar = isset($_SESSION['avatar']) && !empty($_SESSION['avatar']) ? $base . '/images/' . htmlspecialchars($_SESSION['avatar']) : null;
                if ($headerAvatar): 
            ?>
                <img src="<?= $headerAvatar ?>" alt="Avatar" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">
            <?php else: ?>
                <div style="width: 36px; height: 36px; background: var(--green-primary); color: white; border-radius: 50%; text-align: center; line-height: 36px; font-weight: bold; font-size: 15px;">
                    <?= isset($_SESSION['username']) ? strtoupper(mb_substr($_SESSION['username'], 0, 1)) : 'A' ?>
                </div>
            <?php endif; ?>
            <span style="margin-left: 10px; font-weight: 500; display: none; /* Hide text on mobile if needed, but normally show it */"><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin' ?></span>
        </a>
    </div>
</div>

<!-- ===================== MAIN CONTENT STARTS ===================== -->
<main class="admin-main">

<script>
// Sidebar toggle (mobile)
const sidebar  = document.getElementById('adminSidebar');
const overlay  = document.getElementById('sidebarOverlay');
const toggle   = document.getElementById('sidebarToggle');
const collapseToggle = document.getElementById('sidebarCollapseToggle');
const notificationToggle = document.getElementById('notificationToggle');
const notificationPanel = document.getElementById('adminNotificationPanel');

if (toggle) toggle.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
});
if (overlay) overlay.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
});

if (collapseToggle) collapseToggle.addEventListener('click', () => {
    document.body.classList.toggle('sidebar-collapsed');
    const collapsed = document.body.classList.contains('sidebar-collapsed');
    collapseToggle.innerHTML = `<i class="fa-solid ${collapsed ? 'fa-angles-right' : 'fa-angles-left'}"></i>`;
});

if (notificationToggle && notificationPanel) {
    notificationToggle.addEventListener('click', (event) => {
        event.stopPropagation();
        const isOpen = !notificationPanel.hasAttribute('hidden');
        notificationPanel.hidden = isOpen;
        notificationToggle.setAttribute('aria-expanded', String(!isOpen));
    });

    document.addEventListener('click', (event) => {
            if (!notificationPanel.contains(event.target) && !notificationToggle.contains(event.target)) {
                notificationPanel.hidden = true;
                notificationToggle.setAttribute('aria-expanded', 'false');
            }
    });
}

// Dynamic topbar title từ active sidebar item
document.addEventListener('DOMContentLoaded', () => {
    const active = document.querySelector('.sidebar-item.active');
    const tb = document.getElementById('topbarTitle');
    if (active && tb) {
        const txt = active.textContent.trim();
        tb.innerHTML = txt + ' <span>/ Quản trị</span>';
    }
});
</script>