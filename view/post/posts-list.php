<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/env.php';

require_once '../../controller/PostController.php';
require_once '../../controller/UserController.php';

$postController = new PostController();
$userController = new UserController();

// Get the logged-in user's ID
$loggedInUserId = $_SESSION['user_id'];

// Check if "Show My Posts" is enabled
$showOnlyMyPosts = isset($_GET['showOnlyMyPosts']) && $_GET['showOnlyMyPosts'] === 'true';

if ($showOnlyMyPosts) {
    // Filter posts where the user_id matches the logged-in user's ID
    $posts = array_filter($postController->getAllPosts(), function ($post) use ($loggedInUserId) {
        return $post['user_id'] === $loggedInUserId;
    });
} else {
    // Fetch all posts
    $posts = $postController->getAllPosts();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community</title>
    <link rel="stylesheet" href="<?= $base ?>/lib/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $base ?>/css/mystyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php
    if (!isset($_SESSION['user_id'])) {
        // Redirect to the login page using JavaScript
        echo '<script>window.location.href = "' . $base . '/Login";</script>';
        exit();
    }
    
    include '../header.php';
    ?>
    <section layout:fragment="content" style="padding: 0;">
        <section class="Post">
            <div class="hero-container" style="width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; padding: 0;">
                <img src="<?= $base ?>/images/ClassAnimal/Background/Background.jpg" alt="Background" class="classbg" />
                <div class="hero-overlay">
                    <h1 class="display-3 fw-bold text-white text-center" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Community</h1>
                    <h4 class="text-white text-center mt-3 fw-medium" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Hãy cùng nhau chia sẻ những trải nghiệm của bản thân về thế giới động vật phong phú</h4>
                </div>
            </div>
            <div class="container mt-5">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
                    <div class="form-check mb-3 mb-md-0 d-flex align-items-center">
                        <input class="form-check-input me-3 shadow-sm" type="checkbox" value="" id="showOnlyMyPosts"
                            <?= isset($_GET['showOnlyMyPosts']) && $_GET['showOnlyMyPosts'] === 'true' ? 'checked' : '' ?>
                            onchange="window.location.href = '<?= $base ?>/Posts?showOnlyMyPosts=' + this.checked;"
                            style="transform: scale(1.6); cursor: pointer; border-color: rgba(255,255,255,0.5);">
                        <label class="form-check-label text-white fw-bold mb-0" for="showOnlyMyPosts"
                            style="font-size: 1.2rem; cursor: pointer; letter-spacing: 0.5px; text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">
                            Hiển thị bài viết của tôi
                        </label>
                    </div>
                    <div>
                        <a id="mbtn" class="btn text-white fw-bold rounded-pill shadow-lg px-4 py-2" style="background: linear-gradient(135deg, #f59e0b, #d97706); cursor: pointer; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.2);" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 10px 20px rgba(245,158,11,0.4)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)';">
                            <i class="fas fa-pen me-2"></i> Tạo bài viết
                        </a>
                    </div>
                </div>

                <!-- The Modal -->
                <div id="modalDialog" class="modal">
                    <div class="modal-content animate-top"
                        style="background-image: url('/animal_php/view/design/Explore/bg.png');object-fit: cover; border-radius: 20px;">
                        <div class="modal-header border-0">
                            <h4 class="modal-title fw-bold" style="color:white; text-shadow: 1px 1px 2px black;">Đăng bài viết mới</h4>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 1; text-shadow: 1px 1px 2px black;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="<?= $base ?>/view/post/add.php" method="post" enctype="multipart/form-data">
                                <div class="form-group mb-4">
                                    <label class="fw-bold mb-2" style="color:white; text-shadow: 1px 1px 2px black;">Tiêu đề bài viết:</label>
                                    <input type="text" name="title" class="form-control rounded-pill px-4" required placeholder="Nhập tiêu đề..." />
                                </div>
                                <div class="form-group mb-4">
                                    <label class="fw-bold mb-2" style="color:white; text-shadow: 1px 1px 2px black;">Tải lên hình ảnh:</label>
                                    <input type="file" class="form-control rounded-pill px-4" id="imageFile" name="imageFile" accept="image/*" required onchange="previewImage(event)" />
                                </div>
                                <div class="text-center mb-4">
                                    <img id="imagePreview" class="img-fluid rounded shadow" alt="Image Preview" style="display: none; max-height: 250px;" />
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill w-100 fw-bold">Thêm bài viết</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row g-4 justify-content-center">
                    <?php foreach ($posts as $post): ?>
                        <?php
                        // Fetch the username for the current post's user_id
                        $username = $userController->getUsernameById($post['user_id']);
                        ?>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <style>
                                .post-card-link {
                                    display: block; 
                                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                                    transform: none !important;
                                }
                                .post-card-link:hover {
                                    transform: translateY(-5px) !important;
                                }
                                .post-card-link .card {
                                    transform: none !important;
                                    direction: ltr !important;
                                }
                                .premium-glass-card {
                                    background: rgba(15, 23, 42, 0.6) !important;
                                    backdrop-filter: blur(24px) saturate(150%);
                                    -webkit-backdrop-filter: blur(24px) saturate(150%);
                                    border: 1px solid rgba(255, 255, 255, 0.08) !important;
                                    color: #f8fafc !important;
                                    border-radius: 20px;
                                }
                            </style>
                            <a href="<?= $base ?>/posts/detail/<?= htmlspecialchars($post['id_post']) ?>" class="text-decoration-none post-card-link">
                                <div class="card h-100 shadow-lg border-0 premium-glass-card overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                                        <div class="d-flex align-items-center">
                                            <img src="/animal_php/view/design/Footer/nekoparalogo.png" class="rounded-circle shadow-sm" style="height:40px;width:40px;object-fit:cover; border: 2px solid rgba(255,255,255,0.2);">
                                            <span class="fw-bold text-white ms-3 fs-6" style="letter-spacing: 0.5px;"><?= htmlspecialchars($username) ?></span>
                                        </div>
                                        <div class="text-white-50 small">
                                            <i class="far fa-clock me-1"></i> <?= htmlspecialchars($post['date']) ?>
                                        </div>
                                    </div>
                                    <div class="position-relative overflow-hidden" style="height: 250px;">
                                        <img src="/animal_php/images/<?= htmlspecialchars($post['image']) ?>" class="w-100 h-100" style="object-fit: cover; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-white fw-bold mb-0 text-truncate" style="line-height: 1.5; font-family: 'Be Vietnam Pro', sans-serif;"><?= htmlspecialchars($post['title']) ?></h5>
                                    </div>
                                    <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center p-3" style="border-color: rgba(255,255,255,0.08) !important;">
                                        <span class="text-white-50"><i class="far fa-comment-dots me-2"></i></span>
                                        <span class="text-white fw-bold px-4 py-2 rounded-pill" style="background: linear-gradient(135deg, #3b82f6, #2563eb); box-shadow: 0 4px 15px rgba(59,130,246,0.4); font-size: 0.9rem;">Thảo luận ngay <i class="fas fa-arrow-right ms-2 fs-6"></i></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <script>
            function previewImage(event) {
                var input = event.target;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('imagePreview').src = e.target.result;
                        document.getElementById('imagePreview').style.display = 'block';
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            /*
             * Modal popup
             */
            // Get the modal
            var modal = $('#modalDialog');

            // Get the button that opens the modal
            var btn = $("#mbtn");

            // Get the element that closes the modal
            var span = $(".close");

            $(document).ready(function() {
                // When the user clicks the button, open the modal
                btn.on('click', function() {
                    modal.show();
                });

                // When the user clicks on (x), close the modal
                span.on('click', function() {
                    modal.hide();
                });
            });

            $('body').bind('click', function(e) {
                if ($(e.target).hasClass("modal")) {
                    modal.hide();
                }
            });
        </script>
    </section>
    <?php include '../footer.php'; ?>
</body>

</html>