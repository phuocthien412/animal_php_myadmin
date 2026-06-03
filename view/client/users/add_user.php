<?php
require_once '../../../config/env.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base = BASE_URL;
$error = '';

$animalController = new AnimalController();
$userController = new UserController();
$totalAnimals = count($animalController->getAllAnimals());
$totalUsers = count($userController->getAllUsers());

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'email' => $_POST['email'],
        'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
        'phone' => $_POST['phone'],
        'provider' => '', // Set provider to blank
        'username' => $_POST['username'],
        'roles' => [2] // '2' is the ID for the 'user' role
    ];

    $result = $userController->createUser($data);

    if ($result === 'duplicate_email') {
        $error = __('reg_err_email');
    } elseif ($result === 'duplicate_phone') {
        $error = __('reg_err_phone');
    } elseif ($result === 'duplicate_username') {
        $error = __('reg_err_username');
    } else {
        // Set session variables for instant login
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['username'] = $result['username'];
        $_SESSION['roles'] = $result['roles'];
        header("Location: " . $base . "/Home");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('reg_title') ?> - NEKOPARA</title>

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
                Bắt đầu hành trình<br>
                <em>khám phá thế giới động vật</em>
            </h1>

            <p class="hero-desc">
                Tạo tài khoản miễn phí để tham gia cộng đồng Nekopara. Chia sẻ, học hỏi và đóng góp vào kho tàng kiến thức sinh thái lớn nhất.
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
                    <i class="fa-solid fa-seedling"></i>
                    Chào mừng bạn mới
                </div>
                <h2 class="form-title"><?= __('reg_title') ?></h2>
                <p class="form-subtitle">Điền thông tin bên dưới để tạo tài khoản mới.</p>
            </div>

            <!-- Error message -->
            <?php if ($error): ?>
                <div class="form-alert">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="<?= $base ?>/Register" method="POST" id="registerForm">

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
                            placeholder="Tên đăng nhập của bạn"
                            required
                        >
                    </div>
                </div>

                <!-- Email -->
                <div class="field-group">
                    <label class="field-label" for="email"><?= __('form_email') ?></label>
                    <div class="field-wrap">
                        <span class="field-icon"><i class="fa-regular fa-envelope"></i></span>
                        <input
                            class="field-input"
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Địa chỉ Email"
                            required
                        >
                    </div>
                </div>

                <!-- Phone -->
                <div class="field-group">
                    <label class="field-label" for="phone"><?= __('form_phone') ?></label>
                    <div class="field-wrap">
                        <span class="field-icon"><i class="fa-solid fa-phone"></i></span>
                        <input
                            class="field-input"
                            type="tel"
                            id="phone"
                            name="phone"
                            placeholder="Số điện thoại (Tuỳ chọn)"
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
                            placeholder="Mật khẩu bảo mật"
                            required
                        >
                        <button type="button" class="field-toggle" id="pwToggle" title="Hiển thị mật khẩu">
                            <i class="fa-regular fa-eye" id="pwToggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-login" id="registerBtn">
                    <i class="fa-solid fa-user-plus"></i>
                    Tạo tài khoản
                </button>
            </form>

            <div class="form-links">
                <span></span>
                <a href="<?= $base ?>/Login" class="form-link">
                    Đã có tài khoản? Đăng nhập ngay
                </a>
            </div>

            <div class="form-divider">
                <hr><span>Hoặc</span><hr>
            </div>

            <div class="register-cta">
                Về trang chủ: <a href="<?= $base ?>/Home">Khám phá Nekopara</a>
            </div>

            <p class="form-footnote">
                Khi tạo tài khoản, bạn đồng ý với 
                <a href="#" style="color:var(--forest-light);">Điều khoản dịch vụ</a>
                và <a href="#" style="color:var(--forest-light);">Chính sách bảo mật</a> của chúng tôi.
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
document.getElementById('registerForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('registerBtn');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...';
    btn.disabled = true;
});
</script>
</body>
</html>