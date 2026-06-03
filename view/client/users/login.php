<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Load BASE_URL từ .env → $base
require_once __DIR__ . '/../../../config/env.php';

$animalController = new AnimalController();
$userController = new UserController();
$totalAnimals = count($animalController->getAllAnimals());
$totalUsers = count($userController->getAllUsers());
if (isset($_SESSION['username'])) {
    header('Location: ' . $base . '/Home');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('login_page_title') ?></title>
    <meta name="description" content="<?= __('login_meta_desc') ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Be+Vietnam+Pro:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />

    <!-- Client Users Styles -->
    <link rel="stylesheet" href="<?= $base ?>/css/client/users.css">
</head>
<body>
<div class="login-page">

    <!-- ===== LEFT: HERO ===== -->
    <div class="hero-panel">

        <!-- Bubbles -->
        <div class="bubble" style="width:180px;height:180px;left:10%;top:60%;animation-duration:18s;animation-delay:-4s;"></div>
        <div class="bubble" style="width:90px;height:90px;left:60%;top:75%;animation-duration:12s;animation-delay:-8s;"></div>
        <div class="bubble" style="width:130px;height:130px;left:40%;top:50%;animation-duration:22s;animation-delay:-2s;"></div>
        <div class="bubble" style="width:60px;height:60px;left:80%;top:40%;animation-duration:14s;animation-delay:-10s;"></div>

        <!-- Leaf particles -->
        <span class="leaf" style="left:12%;animation-duration:14s;animation-delay:0s;">🍃</span>
        <span class="leaf" style="left:35%;animation-duration:18s;animation-delay:-5s;">🌿</span>
        <span class="leaf" style="left:55%;animation-duration:12s;animation-delay:-9s;">🍃</span>
        <span class="leaf" style="left:75%;animation-duration:20s;animation-delay:-3s;">🌱</span>
        <span class="leaf" style="left:88%;animation-duration:16s;animation-delay:-7s;">🍃</span>

        <div class="hero-content">
            <div class="hero-logo">
                <div class="hero-logo-icon">
                    <i class="fa-solid fa-paw"></i>
                </div>
                <div>
                    <div class="hero-logo-name">NEKOPARA</div>
                    <div class="hero-logo-tagline">Love animal · Love life</div>
                </div>
            </div>

            <h1 class="hero-headline">
                <?= __('hero_headline_1') ?><br>
                <em><?= __('hero_headline_2') ?></em>
            </h1>

            <p class="hero-desc">
                <?= __('hero_desc') ?>
            </p>

            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-val"><?= $totalAnimals ?></div>
                    <div class="hero-stat-lbl"><?= __('hero_stat_animals') ?></div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-val"><?= $totalUsers ?></div>
                    <div class="hero-stat-lbl"><?= __('hero_stat_users') ?></div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-val">AI</div>
                    <div class="hero-stat-lbl"><?= __('hero_stat_ai') ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== RIGHT: FORM ===== -->
    <div class="form-panel">
        <div class="form-inner">

            <div class="form-header">
                <div class="form-greeting">
                    <i class="fa-solid fa-leaf"></i>
                    <?= __('login_greeting') ?>
                </div>
                <h2 class="form-title"><?= __('login_title') ?></h2>
                <p class="form-subtitle"><?= __('login_subtitle') ?></p>
            </div>

            <!-- Error message -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="form-alert">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="<?= $base ?>/view/client/users/login_process.php" method="POST" id="loginForm">

                <!-- Username -->
                <div class="field-group">
                    <label class="field-label" for="username"><?= __('form_username') ?></label>
                    <div class="field-wrap">
                        <span class="field-icon"><i class="fa-regular fa-user"></i></span>
                        <input
                            class="field-input"
                            type="text"
                            id="username"
                            name="username"
                            placeholder="<?= __('login_placeholder_username') ?>"
                            autocomplete="username"
                            required
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="field-group">
                    <label class="field-label" for="password"><?= __('form_password') ?></label>
                    <div class="field-wrap">
                        <span class="field-icon"><i class="fa-solid fa-lock"></i></span>
                        <input
                            class="field-input"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="<?= __('login_placeholder_password') ?>"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="field-toggle" id="pwToggle" title="<?= __('login_toggle_password') ?>">
                            <i class="fa-regular fa-eye" id="pwToggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-login" id="loginBtn">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <?= __('login_title') ?>
                </button>
            </form>

            <div class="form-links">
                <span></span>
                <a href="<?= $base ?>/Register" class="form-link">
                    <?= __('login_no_account') ?>
                </a>
            </div>

            <div class="form-divider">
                <hr><span><?= __('login_or_continue_with') ?></span><hr>
            </div>

            <div class="register-cta">
                <?= __('login_guest') ?> <a href="<?= $base ?>/Home"><?= __('login_go_home') ?></a>
            </div>

            <p class="form-footnote">
                <?= __('login_terms_prefix') ?>
                <a href="#" style="color:var(--forest-light);"><?= __('login_terms') ?></a>
                <?= __('login_terms_and') ?> <a href="#" style="color:var(--forest-light);"><?= __('login_privacy') ?></a>.
            </p>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
const pwInput  = document.getElementById('password');
const pwToggle = document.getElementById('pwToggle');
const pwIcon   = document.getElementById('pwToggleIcon');

pwToggle?.addEventListener('click', () => {
    const isText = pwInput.type === 'text';
    pwInput.type = isText ? 'password' : 'text';
    pwIcon.className = isText ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
});

// Loading state on submit
document.getElementById('loginForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <?= __('login_processing') ?>';
    btn.disabled = true;
});
</script>
</body>
</html>
