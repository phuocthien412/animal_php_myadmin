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
// Redirect nếu đã đăng nhập
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

    <style>
    /* ============================================================
       RESET & BASE
       ============================================================ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --forest-deep:   hsl(145, 40%, 12%);
        --forest-mid:    hsl(150, 45%, 22%);
        --forest-light:  hsl(152, 50%, 35%);
        --teal:          hsl(168, 60%, 38%);
        --sky:           hsl(195, 55%, 48%);
        --earth:         hsl(28,  40%, 55%);
        --cream:         hsl(55,  30%, 97%);
        --glass-bg:      rgba(255,255,255,0.13);
        --glass-border:  rgba(255,255,255,0.25);
        --text-dark:     hsl(145, 20%, 15%);
        --text-mid:      hsl(145, 10%, 40%);
    }

    html, body {
        height: 100%;
        font-family: 'Inter', sans-serif;
        overflow-x: hidden;
    }

    /* ============================================================
       LAYOUT
       ============================================================ */
    .login-page {
        min-height: 100vh;
        display: grid;
        grid-template-columns: 1fr 480px;
    }

    /* ============================================================
       LEFT — HERO PANEL
       ============================================================ */
    .hero-panel {
        position: relative;
        overflow: hidden;
        background: linear-gradient(
            160deg,
            var(--forest-deep)   0%,
            var(--forest-mid)   35%,
            var(--teal)         70%,
            var(--sky)         100%
        );
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px 80px;
    }

    /* Texture overlay */
    .hero-panel::before {
        content: '';
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse 60% 80% at 20% 100%, rgba(40,160,90,.25) 0%, transparent 60%),
            radial-gradient(ellipse 50% 60% at 80% 20%,  rgba(30,120,160,.20) 0%, transparent 55%);
        pointer-events: none;
    }

    /* Animated bubbles */
    .bubble {
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
        animation: floatUp linear infinite;
    }
    @keyframes floatUp {
        0%   { transform: translateY(0) scale(1); opacity: 0.6; }
        100% { transform: translateY(-110vh) scale(1.1); opacity: 0; }
    }

    /* Leaf particles */
    .leaf {
        position: absolute;
        opacity: 0.18;
        animation: leafDrift linear infinite;
        font-size: 24px;
        color: #a8e6b0;
    }
    @keyframes leafDrift {
        0%   { transform: translateY(-60px) rotate(0deg) translateX(0); opacity: 0; }
        10%  { opacity: .18; }
        90%  { opacity: .15; }
        100% { transform: translateY(110vh) rotate(360deg) translateX(40px); opacity: 0; }
    }

    /* Hero content */
    .hero-content { position: relative; z-index: 2; }

    .hero-logo {
        display: flex; align-items: center; gap: 14px;
        margin-bottom: 40px;
    }
    .hero-logo-icon {
        width: 52px; height: 52px;
        background: rgba(255,255,255,.15);
        border: 1.5px solid rgba(255,255,255,.3);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; color: #fff;
        backdrop-filter: blur(8px);
    }
    .hero-logo-name {
        font-family: 'Be Vietnam Pro', sans-serif;
        font-size: 24px; font-weight: 800;
        color: #fff; letter-spacing: .5px;
    }
    .hero-logo-tagline {
        font-size: 11px; color: rgba(255,255,255,.6);
        letter-spacing: 1.5px; text-transform: uppercase;
        margin-top: 2px;
    }

    .hero-headline {
        font-family: 'Be Vietnam Pro', sans-serif;
        font-size: clamp(32px, 4vw, 52px);
        font-weight: 800;
        color: #fff;
        line-height: 1.25;
        margin-bottom: 16px;
    }
    .hero-headline em {
        font-style: normal;
        background: linear-gradient(90deg, hsl(152,80%,72%), hsl(168,80%,78%));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-desc {
        font-family: 'Inter', sans-serif;
        font-size: 16px;
        color: rgba(255,255,255,.8);
        line-height: 1.65;
        max-width: 420px;
        margin-bottom: 40px;
    }

    .hero-stats {
        display: flex; gap: 28px;
    }
    .hero-stat { }
    .hero-stat-val {
        font-family: 'Be Vietnam Pro', sans-serif;
        font-size: 26px; font-weight: 700; color: #fff;
    }
    .hero-stat-lbl {
        font-size: 11.5px; color: rgba(255,255,255,.55);
        margin-top: 1px;
    }

    /* ============================================================
       RIGHT — FORM PANEL
       ============================================================ */
    .form-panel {
        background: var(--cream);
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 48px 44px;
        position: relative;
    }

    .form-panel::before {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(40,160,90,.04) 0%, transparent 40%);
        pointer-events: none;
    }

    .form-inner { position: relative; z-index: 1; }

    .form-header { margin-bottom: 32px; }
    .form-greeting {
        font-size: 13px; font-weight: 500;
        color: var(--forest-light);
        letter-spacing: .3px;
        margin-bottom: 6px;
        display: flex; align-items: center; gap: 6px;
    }
    .form-title {
        font-family: 'Be Vietnam Pro', sans-serif;
        font-size: 32px; font-weight: 800;
        color: var(--text-dark);
        line-height: 1.25;
    }
    .form-subtitle {
        font-size: 14px; color: var(--text-mid);
        margin-top: 6px;
    }

    /* Error alert */
    .form-alert {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 14px;
        background: hsl(355,75%,96%);
        border: 1px solid hsl(355,75%,85%);
        border-radius: 10px;
        color: hsl(355,65%,42%);
        font-size: 13.5px;
        margin-bottom: 20px;
        animation: slideIn .25s ease;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Field group */
    .field-group { margin-bottom: 18px; }

    .field-label {
        display: block;
        font-size: 13px; font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 7px;
    }

    .field-wrap {
        position: relative;
        display: flex; align-items: center;
    }
    .field-icon {
        position: absolute; left: 14px;
        color: var(--text-mid); font-size: 15px;
        pointer-events: none;
        transition: color .2s;
    }
    .field-wrap:focus-within .field-icon {
        color: var(--forest-light);
    }

    .field-input {
        width: 100%;
        padding: 11px 14px 11px 42px;
        border: 1.5px solid hsl(145, 15%, 82%);
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        color: var(--text-dark);
        background: #fff;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .field-input:focus {
        border-color: var(--forest-light);
        box-shadow: 0 0 0 3.5px rgba(40,160,90,.12);
    }
    .field-input::placeholder { color: hsl(145, 10%, 65%); }

    .field-toggle {
        position: absolute; right: 13px;
        background: none; border: none; cursor: pointer;
        color: var(--text-mid); font-size: 15px; padding: 4px;
        transition: color .2s;
    }
    .field-toggle:hover { color: var(--forest-light); }

    /* Submit button */
    .btn-login {
        width: 100%;
        padding: 13px;
        background: linear-gradient(135deg, var(--forest-light), var(--teal));
        color: #fff;
        border: none; border-radius: 12px;
        font-family: 'Be Vietnam Pro', sans-serif;
        font-size: 16px; font-weight: 700;
        cursor: pointer;
        margin-top: 8px;
        box-shadow: 0 4px 18px rgba(40,160,90,.32);
        transition: all .22s ease;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        letter-spacing: .2px;
    }
    .btn-login:hover {
        background: linear-gradient(135deg, var(--forest-mid), var(--forest-light));
        box-shadow: 0 6px 24px rgba(40,160,90,.42);
        transform: translateY(-1px);
    }
    .btn-login:active { transform: translateY(0); }

    /* Links */
    .form-links {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 20px;
        font-size: 13px;
    }
    .form-link {
        color: var(--forest-light);
        text-decoration: none; font-weight: 500;
        transition: color .15s;
    }
    .form-link:hover { color: var(--forest-mid); text-decoration: underline; }

    /* Divider */
    .form-divider {
        display: flex; align-items: center; gap: 14px;
        margin: 24px 0;
    }
    .form-divider hr {
        flex: 1; border: none;
        border-top: 1px solid hsl(145,15%,86%);
    }
    .form-divider span {
        font-size: 12px; color: var(--text-mid);
        white-space: nowrap;
    }

    /* Register CTA */
    .register-cta {
        text-align: center;
        font-size: 13.5px; color: var(--text-mid);
    }
    .register-cta a {
        color: var(--forest-light); font-weight: 600;
        text-decoration: none;
    }
    .register-cta a:hover { text-decoration: underline; }

    /* Footer note */
    .form-footnote {
        margin-top: 32px;
        text-align: center;
        font-size: 11.5px; color: hsl(145,10%,65%);
    }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width: 860px) {
        .login-page { grid-template-columns: 1fr; }
        .hero-panel { padding: 32px 28px 28px; min-height: 240px; justify-content: flex-start; }
        .hero-stats { display: none; }
        .hero-headline { font-size: 26px; }
        .hero-desc { display: none; }
        .form-panel { padding: 32px 24px; }
    }
    </style>
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
