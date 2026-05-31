<?php

require_once '../../controller/PostController.php';
require_once '../../controller/CommentController.php';
require_once '../../controller/UserController.php';

// Get the post ID from the URL
$post_id = $_GET['id'];

// Initialize controllers
$postController = new PostController();
$commentController = new CommentController();
$userController = new UserController();

// Fetch the post details
$post = $postController->getPostById($post_id);
$post['username'] = $userController->getUsernameById($post['user_id']); // Fetch the username for the post

// Fetch comments for the post
$comments = $commentController->getCommentsByPostId($post_id);
foreach ($comments as $key => $comment) {
    $comments[$key]['username'] = $userController->getUsernameById($comment['user_id']); // Fetch the username for each comment
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Post</title>
    <link rel="stylesheet" href="/animal_php/lib/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/animal_php/css/mystyle.css" />
</head>

<body>
    <?php include '../header.php'; ?>
    <section class="Post" style="padding-top: 30px !important; padding-bottom: 30px !important;">
        <div class="container-fluid px-4 px-lg-5">
            <div class="row align-items-stretch">
                <!-- Post Details -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                <style>
                    .safe-card {
                        transform: none !important;
                        direction: ltr !important;
                        transition: none !important;
                    }
                    .glass-card {
                        background: rgba(255, 255, 255, 0.2) !important;
                        backdrop-filter: blur(20px);
                        -webkit-backdrop-filter: blur(20px);
                        border: 1px solid rgba(255, 255, 255, 0.3) !important;
                        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3) !important;
                        color: white !important;
                    }
                    .glass-card .text-dark, .glass-card .text-muted {
                        color: white !important;
                    }
                    .glass-card .bg-white, .glass-card .bg-light {
                        background: transparent !important;
                    }
                    .glass-card .border-bottom, .glass-card hr {
                        border-color: rgba(255, 255, 255, 0.3) !important;
                    }
                    .glass-card .card-header {
                        background: rgba(255, 255, 255, 0.1) !important;
                        border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
                    }
                    .glass-card-comment {
                        background: rgba(255, 255, 255, 0.15) !important;
                        backdrop-filter: blur(10px);
                        border: 1px solid rgba(255, 255, 255, 0.2) !important;
                        color: white !important;
                    }
                    .glass-card-comment .text-dark, .glass-card-comment .text-muted {
                        color: #f8f9fa !important;
                    }
                    .glass-input {
                        background: rgba(255, 255, 255, 0.2) !important;
                        border: 1px solid rgba(255, 255, 255, 0.3) !important;
                        color: white !important;
                    }
                    .glass-input::placeholder {
                        color: rgba(255, 255, 255, 0.8) !important;
                    }
                </style>
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden safe-card glass-card h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center p-3 bg-white border-bottom flex-shrink-0">
                        <div class="d-flex align-items-center">
                            <img src="/animal_php/view/design/Footer/nekoparalogo.png" class="rounded-circle shadow-sm"
                                style="height:50px;width:50px;object-fit:cover; border: 2px solid #f8f9fa;">
                            <span class="fw-bold text-dark ms-3 fs-5"><?= htmlspecialchars($post['username']) ?></span>
                        </div>
                        <div class="text-muted small">
                            <i class="far fa-clock me-1"></i> <?= htmlspecialchars($post['date']) ?>
                        </div>
                    </div>
                    <div class="flex-grow-1" style="min-height: 300px; max-height: 500px;">
                        <img src="<?= $base ?>/images/<?= htmlspecialchars($post['image']) ?>" class="w-100 h-100"
                            style="object-fit: cover;">
                    </div>
                    <div class="card-body bg-transparent p-4 flex-shrink-0">
                        <p class="card-text text-white fs-5 mb-4 fw-medium" style="line-height: 1.6; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);"><?= htmlspecialchars($post['title']) ?></p>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="<?= $base ?>/Posts" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm" style="color: #333;">
                                <i class="fas fa-arrow-left me-2"></i> Trở về Cộng đồng
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="col-lg-6">
                <div class="card shadow border-0 rounded-4 overflow-hidden safe-card glass-card" style="height: 100%; display: flex; flex-direction: column;">
                    <div class="card-header p-3 border-0">
                        <h4 class="mb-0 fw-bold"><i class="far fa-comments me-2"></i> Bình luận</h4>
                    </div>
                    <div id="commentsWrapper" class="card-body bg-transparent" style="flex-grow: 1; overflow-y: auto; max-height: 550px;">
                        <?php foreach ($comments as $key => $comment): ?>
                            <div class="d-flex mb-4">
                                <img src="/animal_php/view/design/Footer/nekoparalogo.png" class="rounded-circle shadow-sm me-3" style="height: 50px; width: 50px; object-fit: cover;">
                                <div class="glass-card-comment p-3 rounded-4 shadow-sm w-100 position-relative">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold text-white"><?= htmlspecialchars($comment['username']) ?></span>
                                        <small class="text-light"><i class="far fa-clock me-1"></i> <?= htmlspecialchars($comment['date_time']) ?></small>
                                    </div>
                                    <p class="mb-0 text-white"><?= htmlspecialchars($comment['chat_data']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Add Comment Form -->
                    <div class="card-footer bg-transparent border-top p-3 shadow-sm" style="border-color: rgba(255,255,255,0.2) !important;">
                        <form action="<?= $base ?>/view/post/add-comment.php" method="POST" class="needs-validation m-0" novalidate>
                            <input type="hidden" name="post_id" value="<?= htmlspecialchars($post_id) ?>" />
                            <div class="input-group">
                                <textarea class="form-control glass-input rounded-pill-start border-end-0 px-4 py-2" name="chatData" required rows="1" placeholder="Viết bình luận của bạn..." style="resize: none; border-top-left-radius: 25px; border-bottom-left-radius: 25px;"></textarea>
                                <button type="submit" class="btn btn-light px-4 fw-bold" style="border-top-right-radius: 25px; border-bottom-right-radius: 25px; color: #333;">
                                    <i class="fas fa-paper-plane me-1"></i> Gửi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    const postId = <?= json_encode($post_id) ?>; // Pass the post ID to JavaScript

    // Function to fetch and update comments
    function fetchComments() {
    fetch(`<?= $base ?>/view/post/fetch-comments.php?post_id=${postId}`)
        .then(response => response.json())
        .then(comments => {
            const commentsWrapper = document.getElementById('commentsWrapper');
            commentsWrapper.innerHTML = ''; // Clear existing comments

            comments.forEach(comment => {
                const commentHtml = `
                    <div class="d-flex mb-4">
                        <img src="/animal_php/view/design/Footer/nekoparalogo.png" class="rounded-circle shadow-sm me-3" style="height: 50px; width: 50px; object-fit: cover;">
                        <div class="glass-card-comment p-3 rounded-4 shadow-sm w-100 position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-white">${comment.username}</span>
                                <small class="text-light"><i class="far fa-clock me-1"></i> ${comment.date_time}</small>
                            </div>
                            <p class="mb-0 text-white">${comment.chat_data}</p>
                        </div>
                    </div>
                `;
                commentsWrapper.innerHTML += commentHtml;
            });

            // Scroll to the bottom of the comments section
            commentsWrapper.scrollTop = commentsWrapper.scrollHeight;
        })
        .catch(error => console.error('Error fetching comments:', error));
}

    // Fetch comments every 5 seconds
    setInterval(fetchComments, 500);

    // Fetch comments on page load
    fetchComments();
</script>
<script>
    const commentForm = document.querySelector('form.needs-validation');

    commentForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(commentForm);

        fetch('<?= $base ?>/view/post/add-comment.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    commentForm.reset(); // Clear the form
                    fetchComments(); // Refresh comments
                } else {
                    alert(data.error || 'Failed to add comment');
                }
            })
            .catch(error => console.error('Error adding comment:', error));
    });
</script>
</html>