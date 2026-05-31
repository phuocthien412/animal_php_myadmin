<?php
session_start();
require_once __DIR__ . '/../config/env.php'; // Load BASE_URL từ .env → $base
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NEKOPARA — Admin Panel</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= $base ?>/view/design/Home/logo.png" />

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
        <div class="sidebar-brand-icon">
            <i class="fa-solid fa-paw"></i>
        </div>
        <div class="sidebar-brand-text">
            <span class="sidebar-brand-name">NEKOPARA</span>
            <span class="sidebar-brand-sub">Admin Console</span>
        </div>
    </a>

    <!-- Nav -->
    <nav class="sidebar-nav">

        <div class="sidebar-section-label">Tổng quan</div>

        <a href="<?= $base ?>/admin/users"
           class="sidebar-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false && strpos($_SERVER['REQUEST_URI'], 'editRole') === false && strpos($_SERVER['REQUEST_URI'], 'delete') === false) ? 'active' : ''; ?>">
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

        <div class="sidebar-section-label">Hệ thống</div>

        <?php if (isset($_SESSION['roles']) && in_array('ADMIN', $_SESSION['roles'])): ?>
        <a href="<?= $base ?>/admin/users"
           class="sidebar-item">
            <span class="si-icon"><i class="fa-solid fa-users-gear"></i></span>
            Tài khoản
        </a>
        <?php endif; ?>

        <a href="<?= $base ?>/Home" class="sidebar-item">
            <span class="si-icon"><i class="fa-solid fa-house"></i></span>
            Trang chủ
        </a>

    </nav>

    <!-- Footer / User -->
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <?php echo isset($_SESSION['username']) ? strtoupper(mb_substr($_SESSION['username'], 0, 1)) : 'A'; ?>
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">
                    <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?>
                </div>
                <div class="sidebar-user-role">
                    <?php echo isset($_SESSION['roles']) && in_array('ADMIN', $_SESSION['roles']) ? 'Administrator' : 'Manager'; ?>
                </div>
            </div>
            <form action="<?= $base ?>/view/user/logout.php" method="post">
                <button class="sidebar-logout-btn" type="submit" title="Đăng xuất">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>

</aside>

<!-- ===================== TOP BAR ===================== -->
<div class="admin-topbar">
    <button class="topbar-toggle" id="sidebarToggle">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="topbar-title" id="topbarTitle">
        Dashboard <span>/ Quản trị</span>
    </div>

    <div class="topbar-actions">
        <button class="topbar-btn" title="Thông báo">
            <i class="fa-regular fa-bell"></i>
        </button>
        <div class="topbar-user-pill">
            <div class="topbar-user-av">
                <?php echo isset($_SESSION['username']) ? strtoupper(mb_substr($_SESSION['username'], 0, 1)) : 'A'; ?>
            </div>
            <span class="topbar-user-name">
                <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?>
            </span>
        </div>
    </div>
</div>

<!-- ===================== MAIN CONTENT STARTS ===================== -->
<main class="admin-main">

<script>
// Sidebar toggle (mobile)
const sidebar  = document.getElementById('adminSidebar');
const overlay  = document.getElementById('sidebarOverlay');
const toggle   = document.getElementById('sidebarToggle');

if (toggle) toggle.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
});
if (overlay) overlay.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
});

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