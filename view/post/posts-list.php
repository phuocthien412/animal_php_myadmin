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
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 bg-dark bg-opacity-50 p-4 rounded-4 shadow-sm" style="backdrop-filter: blur(10px);">
                    <div class="form-check mb-3 mb-md-0 d-flex align-items-center">
                        <input class="form-check-input me-3" type="checkbox" value="" id="showOnlyMyPosts"
                            <?= isset($_GET['showOnlyMyPosts']) && $_GET['showOnlyMyPosts'] === 'true' ? 'checked' : '' ?>
                            onchange="window.location.href = '<?= $base ?>/Posts?showOnlyMyPosts=' + this.checked;"
                            style="transform: scale(1.8); cursor: pointer;">
                        <label class="form-check-label text-white fw-bold" for="showOnlyMyPosts"
                            style="font-size: 1.5rem; cursor: pointer; text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">
                            Hiển thị bài viết của tôi
                        </label>
                    </div>
                    <div>
                        <a id="mbtn" class="btn btn-warning btn-lg fw-bold rounded-pill shadow px-4 text-dark" style="cursor: pointer;">
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
                                    transition: transform 0.3s ease;
                                    transform: none !important;
                                }
                                .post-card-link:hover {
                                    transform: translateY(-5px) !important;
                                }
                                .post-card-link .card {
                                    transform: none !important;
                                    direction: ltr !important;
                                }
                            </style>
                            <a href="<?= $base ?>/posts/detail/<?= htmlspecialchars($post['id_post']) ?>" class="text-decoration-none post-card-link">
                                <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-white border-bottom">
                                        <div class="d-flex align-items-center">
                                            <img src="/animal_php/view/design/Footer/nekoparalogo.png" class="rounded-circle shadow-sm" style="height:45px;width:45px;object-fit:cover; border: 2px solid #f8f9fa;">
                                            <span class="fw-bold text-dark ms-3 fs-5"><?= htmlspecialchars($username) ?></span>
                                        </div>
                                        <div class="text-muted small">
                                            <i class="far fa-clock me-1"></i> <?= htmlspecialchars($post['date']) ?>
                                        </div>
                                    </div>
                                    <img src="/animal_php/images/<?= htmlspecialchars($post['image']) ?>" class="card-img-top" style="height: 300px; object-fit: cover;">
                                    <div class="card-body bg-white">
                                        <h5 class="card-title text-dark fw-bold mb-0 text-truncate" style="line-height: 1.5;"><?= htmlspecialchars($post['title']) ?></h5>
                                    </div>
                                    <div class="card-footer bg-light border-0 d-flex justify-content-between align-items-center p-3">
                                        <span class="text-muted"><i class="far fa-comment-dots me-2"></i></span>
                                        <span class="text-primary fw-bold px-3 py-1 rounded-pill" style="background: rgba(0,123,255,0.1);"><i class="fas fa-heart me-1 text-danger"></i> Thảo luận ngay</span>
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