<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/env.php';
?>
<!-- Header Styles and Links -->
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
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
            width: 45px;
            margin: 0 20px;
            background: #fff;
            border-radius: 25px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .input-box.open {
            width: 300px;
            background: #fff;
        }

        .input-box input {
            position: absolute;
            height: 100%;
            width: 100%;
            border-radius: 25px;
            background: transparent;
            padding: 0 45px 0 20px;
            border: none;
            outline: none;
            opacity: 0;
            transition: opacity 0.3s ease;
            font-size: 14px;
        }

        .input-box.open input {
            opacity: 1;
        }

        .input-box .icon {
            position: absolute;
            right: 0;
            top: 0;
            width: 45px;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            cursor: pointer;
            z-index: 2;
            color: #0d6efd;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        
        .input-box:hover .icon {
            transform: scale(1.1);
        }
        
        .input-box.open .icon {
            right: 15px;
        }

        /* Active Header Styling */
        .textheader {
            padding: 8px 16px !important;
            border-radius: 20px;
            transition: all 0.3s ease;
            text-decoration: none !important;
            color: #333 !important;
            text-shadow: none !important;
        }
        
        .textheader:hover {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd !important;
        }

        .textheader.active {
            background-color: #0d6efd !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.4);
        }
    </style>
    
    <header>
        <nav class="navbar navbar-expand-sm navbar-toggleable-sm navbar-light border-bottom box-shadow" style="background-color:#F7F7F7;">
            <div class="container-fluid">
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
                            <a class="nav-link textheader <?php echo (strpos($_SERVER['REQUEST_URI'], '/Home') !== false || $_SERVER['REQUEST_URI'] == '/animal_php/') ? 'active' : ''; ?>"
                                href="<?= $base ?>/Home">Trang chủ</a>
                        </li>
                        <li class="nav-item" style="margin-left:10px">
                            <a class="nav-link textheader <?php echo (strpos($_SERVER['REQUEST_URI'], '/ClassAnimal') !== false || strpos($_SERVER['REQUEST_URI'], '/animal/') !== false) ? 'active' : ''; ?>"
                                href="<?= $base ?>/ClassAnimal">Các lớp động vật</a>
                        </li>
                        <li class="nav-item" style="margin-left:10px">
                            <a class="nav-link textheader <?php echo (strpos($_SERVER['REQUEST_URI'], '/FindAnimal') !== false) ? 'active' : ''; ?>"
                                href="<?= $base ?>/FindAnimal">Tìm kiếm bằng hình ảnh</a>
                        </li>
                        <li class="nav-item" style="margin-left:10px">
                            <a class="nav-link textheader <?php echo (strpos($_SERVER['REQUEST_URI'], '/Posts') !== false) ? 'active' : ''; ?>"
                                href="<?= $base ?>/Posts">Cộng đồng</a>
                        </li>
                        <?php if (isset($_SESSION['roles']) && in_array('ADMIN', $_SESSION['roles'])): ?>
                            <li class="nav-item" style="margin-left:10px">
                                <a class="nav-link textheader <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin') !== false) ? 'active' : ''; ?>"
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
                    <input type="text" name="searchQuery" id="searchTerm" placeholder="Tìm kiếm động vật...">
                    <span class="icon" title="Tìm kiếm">
                        <i class="fas fa-search search-icon"></i>
                    </span>
                </form>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        var inputBox = $(".input-box");
                        var searchIcon = $(".icon");
                        
                        // Toggle search bar on click
                        searchIcon.on("click", function(e) {
                            if (!inputBox.hasClass("open")) {
                                e.preventDefault(); // Prevent default action when opening
                                inputBox.addClass("open");
                                $("#searchTerm").focus();
                            } else {
                                // If already open and input is empty, just close it
                                if ($("#searchTerm").val().trim() === '') {
                                    e.preventDefault();
                                    inputBox.removeClass("open");
                                } else {
                                    // Submit the form!
                                    $('#searchForm').submit();
                                }
                            }
                        });
                        
                        // Close search bar when clicking outside
                        $(document).on("click", function(e) {
                            if (!$(e.target).closest(".input-box").length) {
                                inputBox.removeClass("open");
                            }
                        });

                           $('#searchForm').submit(function(event) {
                               var searchTerm = $('#searchTerm').val();
                               if (searchTerm.trim() === '') {
                                   event.preventDefault(); // Ngăn chặn gửi form mặc định
                                   console.log('Vui lòng nhập từ khóa tìm kiếm!');
                               }
                           });
                    });
                </script>

            </div>
        </nav>

    </header>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
