<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/env.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>NEKOPARA</title>
    <!-- Favicon removed -->
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= $base ?>/images/About/logo.png">
    
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base ?>/lib/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= $base ?>/css/mystyle.css?v=<?= time() ?>" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .textheader {
            color: #333;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .textheader:hover {
            background-color: #e9ecef;
            color: #007bff;
        }

        .textheader.active {
            background-color: #007bff;
            color: white;
        }

        /* Thêm style cho navbar */
        .navbar {
            padding: 15px 0;
            background-color: #F7F7F7 !important;
        }

        .navbar-brand img {
            height: 80px;
            width: auto;
        }

        .nav-item {
            margin: 0 5px;
        }

        /* Style cho form tìm kiếm */
        .input-box {
            position: relative;
            height: 40px;
            max-width: 300px;
            margin: 0 20px;
            background: #fff;
            border-radius: 25px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .input-box input {
            position: absolute;
            height: 100%;
            width: 100%;
            border-radius: 25px;
            background: #fff;
            padding: 0 50px 0 20px;
            border: none;
            outline: none;
        }

        .input-box .icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-sm navbar-toggleable-sm navbar-light border-bottom box-shadow mb-3" style="background-color:#F7F7F7;">
            <div class="container-fluid" style="margin-left:100px">
                <a class="navbar-brand" href="<?= $base ?>/Home">
                    <div class="logo">
                        <img src="<?= $base ?>/images/Header/logo.png" width="120px" height="80px">
                    </div>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".navbar-collapse" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-collapse collapse d-sm-inline-flex justify-content-between">
                    <ul class="navbar-nav flex-grow-1">
                        <li class="nav-item">
                            <a class="textheader <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>"
                                href="<?= $base ?>/Home">Trang chủ</a>
                        </li>
                        <li class="nav-item" style="margin-left:10px">
                            <a class="textheader <?php echo (strpos($_SERVER['PHP_SELF'], 'list_classanimals.php') !== false) ? 'active' : ''; ?>"
                                href="<?= $base ?>/ClassAnimal">Các lớp động vật</a>
                        </li>
                        <li class="nav-item" style="margin-left:10px">
                            <a class="textheader <?php echo (basename($_SERVER['PHP_SELF']) == 'findanimal.php') ? 'active' : ''; ?>"
                                href="<?= $base ?>/FindAnimal">Tìm kiếm bằng hình ảnh</a>
                        </li>
                        <li class="nav-item" style="margin-left:10px">
                            <a class="textheader <?php echo (basename($_SERVER['PHP_SELF']) == 'posts.php') ? 'active' : ''; ?>"
                                href="<?= $base ?>/Posts">Cộng đồng</a>
                        </li>
                        <?php if (isset($_SESSION['roles']) && in_array('ADMIN', $_SESSION['roles'])): ?>
                            <li class="nav-item" style="margin-left:10px">
                                <a class="textheader <?php echo (strpos($_SERVER['PHP_SELF'], 'classanimal/admin.php') !== false) ? 'active' : ''; ?>"
                                    href="<?= $base ?>/admin/users">Quản trị</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                        <li class="nav-item d-flex align-items-center">
                            <?php if (isset($_SESSION['username'])): ?>
                                <span class="navbar-text">
                                    Xin chào, <span class="fw-bold"><?php echo htmlspecialchars($_SESSION['username']); ?></span>

                                </span>
                                <form action="<?= $base ?>/view/user/logout.php" method="post" class="ms-3">
                                    <button class="btn btn-outline-danger" type="submit">Đăng xuất</button>
                                </form>
                            <?php else: ?>
                                <a class="btn btn-outline-primary" href="<?= $base ?>/Login">Đăng nhập</a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
                <form action="<?= $base ?>/search/" method="get" class="input-box" id="searchForm">
                    <input type="text" name="searchQuery" id="searchTerm" placeholder="Tìm kiếm động vật..." class="form-control">
                    <span class="icon" title="Tìm kiếm">
                        <i class="fas fa-search search-icon"></i>
                    </span>
                    <i class="fas fa-times close-icon"></i>
                </form>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#searchForm').submit(function(event) {
                            event.preventDefault(); // Ngăn chặn gửi form mặc định

                            var searchTerm = $('#searchTerm').val(); // Lấy giá trị từ ô nhập liệu

                            // Kiểm tra xem từ khóa tìm kiếm có tồn tại không trước khi gửi form
                            if (searchTerm.trim() !== '') {
                                $(this).unbind('submit').submit(); // Gá»­i form
                            } else {
                                // Xử lý khi không có từ khóa tìm kiếm
                                // Ví dụ: Hiển thị thông báo lỗi
                                console.log('Vui lòng nhập từ khóa tìm kiếm!');
                            }
                        });
                    });
                </script>

            </div>
        </nav>

    </header>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        let inputBox = document.querySelector(".input-box"),
            searchIcon = document.querySelector(".icon"),
            closeIcon = document.querySelector(".close-icon");
        searchIcon.addEventListener("click", () => inputBox.classList.add("open"));
        closeIcon.addEventListener("click", () => inputBox.classList.remove("open"));
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
