<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$post['likes_count'] = $postController->getLikeCount($post_id);
$post['is_liked'] = $postController->isLikedByUser($post_id, $user_id);

// Fetch comments for the post
$comments = $commentController->getCommentsByPostId($post_id);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
foreach ($comments as $key => $comment) {
    $comments[$key]['username'] = $userController->getUsernameById($comment['user_id']); // Fetch the username for each comment
    $comments[$key]['likes_count'] = $commentController->getLikeCount($comment['id_cmt']);
    $comments[$key]['is_liked'] = $commentController->isLikedByUser($comment['id_cmt'], $user_id);
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
        <div class="container-fluid px-4 px-lg-5" style="max-width: 1400px; padding-top: 40px; padding-bottom: 40px;">
            <div class="row align-items-stretch g-4">
                <!-- Post Details -->
                <div class="col-lg-7 mb-4 mb-lg-0">
                <style>
                    /* Premium Glassmorphism */
                    .premium-glass {
                        background: rgba(15, 23, 42, 0.6) !important; /* Dark slate with opacity */
                        backdrop-filter: blur(24px) saturate(150%);
                        -webkit-backdrop-filter: blur(24px) saturate(150%);
                        border: 1px solid rgba(255, 255, 255, 0.08) !important;
                        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
                        color: #f8fafc !important;
                        transition: transform 0.3s ease, box-shadow 0.3s ease;
                    }
                    
                    /* Custom Scrollbar for comments */
                    #commentsWrapper::-webkit-scrollbar {
                        width: 6px;
                    }
                    #commentsWrapper::-webkit-scrollbar-track {
                        background: transparent;
                    }
                    #commentsWrapper::-webkit-scrollbar-thumb {
                        background: rgba(255, 255, 255, 0.2);
                        border-radius: 10px;
                    }
                    #commentsWrapper::-webkit-scrollbar-thumb:hover {
                        background: rgba(255, 255, 255, 0.4);
                    }

                    /* Premium Input */
                    .premium-input {
                        background: rgba(255, 255, 255, 0.05) !important;
                        border: 1px solid rgba(255, 255, 255, 0.1) !important;
                        color: white !important;
                        border-radius: 30px !important;
                        padding: 14px 24px;
                        font-size: 0.95rem;
                        transition: all 0.3s ease;
                    }
                    .premium-input:focus {
                        background: rgba(255, 255, 255, 0.1) !important;
                        border-color: rgba(255, 255, 255, 0.3) !important;
                        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.05) !important;
                        outline: none;
                    }
                    .premium-input::placeholder {
                        color: rgba(255, 255, 255, 0.4) !important;
                    }
                    
                    /* Submit Button */
                    .btn-submit-premium {
                        background: #3b82f6;
                        color: white;
                        border: none;
                        border-radius: 50%;
                        width: 40px;
                        height: 40px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        transition: all 0.2s ease;
                    }
                    .btn-submit-premium:hover {
                        background: #2563eb;
                        transform: scale(1.05);
                    }
                </style>
                
                <div class="card premium-glass border-0 rounded-4 overflow-hidden h-100 d-flex flex-column">
                    <!-- Image section -->
                    <div class="position-relative">
                        <img src="<?= $base ?>/images/<?= htmlspecialchars($post['image']) ?>" class="w-100" style="height: 400px; object-fit: cover;">
                        
                        <!-- Floating Author Badge -->
                        <div class="position-absolute top-0 start-0 m-4 d-flex align-items-center px-3 py-2 rounded-pill shadow-sm" 
                             style="background: rgba(0,0,0,0.5); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.15);">
                            <img src="/animal_php/images/Footer/nekoparalogo.png" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover; border: 1px solid rgba(255,255,255,0.5);">
                            <span class="ms-2 fw-bold text-white small" style="letter-spacing: 0.5px;"><?= htmlspecialchars($post['username']) ?></span>
                        </div>
                        
                        <!-- Floating Date Badge -->
                        <div class="position-absolute top-0 end-0 m-4 d-flex align-items-center px-3 py-2 rounded-pill shadow-sm" 
                             style="background: rgba(0,0,0,0.5); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.15);">
                            <small class="text-white"><i class="far fa-clock me-2 opacity-75"></i><?= htmlspecialchars($post['date']) ?></small>
                        </div>
                        
                        <!-- Gradient Overlay at bottom of image for smooth transition -->
                        <div class="position-absolute bottom-0 start-0 w-100" style="height: 100px; background: linear-gradient(to bottom, transparent, rgba(15,23,42,0.6));"></div>
                    </div>
                    
                    <div class="card-body p-4 p-lg-5 flex-grow-1 d-flex flex-column justify-content-between position-relative" style="margin-top: -30px; z-index: 2;">
                        <div>
                            <h3 class="card-title fw-bold text-white mb-4" style="line-height: 1.6; font-family: 'Be Vietnam Pro', sans-serif; letter-spacing: 0.5px;">
                                <?= htmlspecialchars($post['title']) ?>
                            </h3>
                        </div>
                        
                        <div class="mt-5 pt-4 border-top d-flex justify-content-between align-items-center" style="border-color: rgba(255,255,255,0.1) !important;">
                            <a href="<?= $base ?>/Posts" class="btn btn-light rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center" style="color: #0f172a; transition: all 0.3s ease;" onmouseover="this.style.transform='translateX(-5px)'" onmouseout="this.style.transform='translateX(0)'">
                                <i class="fas fa-arrow-left me-2"></i> Trá»Ÿ vá»
                            </a>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn <?= $post['is_liked'] ? 'btn-primary' : 'btn-outline-light' ?> rounded-pill px-4 py-2 fw-bold me-3 shadow-sm btn-like-post" data-post-id="<?= $post['id_post'] ?>" style="transition: all 0.3s ease;">
                                    <i class="fas fa-thumbs-up me-2"></i> ThÃ­ch <span class="post-like-count ms-1" style="display: <?= $post['likes_count'] > 0 ? 'inline' : 'none' ?>">(<?= $post['likes_count'] ?>)</span>
                                </button>
                                <div class="dropdown d-inline-block">
                                    <button type="button" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold shadow-sm" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-share me-2"></i> Chia sáº»
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow border-0" style="background: rgba(15,23,42,0.9); backdrop-filter: blur(10px); border-radius: 12px; z-index: 1050;">
                                        <li><a class="dropdown-item d-flex align-items-center btn-copy-link" href="#" data-url="<?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>"><i class="fas fa-link text-white-50 me-3 fs-5"></i> Sao chÃ©p liÃªn káº¿t</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="col-lg-5">
                <div class="card premium-glass border-0 rounded-4 overflow-hidden" style="height: 100%; display: flex; flex-direction: column;">
                    <div class="card-header bg-transparent p-4 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                        <h5 class="mb-0 fw-bold d-flex align-items-center text-white" style="font-family: 'Be Vietnam Pro', sans-serif; letter-spacing: 0.5px;">
                            <i class="far fa-comments me-3 text-primary fs-4"></i> BÃ¬nh luáº­n
                            <span class="badge bg-primary rounded-pill ms-auto px-3" style="font-size: 0.8rem;"><?= count($comments) ?></span>
                        </h5>
                    </div>
                    
                    <div id="commentsWrapper" class="card-body p-4" style="flex-grow: 1; overflow-y: auto; max-height: 550px;">
                        <!-- inner content -->
                            <?php if (empty($comments)): ?>
                                <div class="text-center py-5">
                                    <i class="far fa-comment-dots fs-1 mb-3" style="color: rgba(255,255,255,0.2);"></i>
                                    <p class="text-white-50">ChÆ°a cÃ³ bÃ¬nh luáº­n nÃ o. HÃ£y lÃ  ngÆ°á»i Ä‘áº§u tiÃªn!</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($comments as $key => $comment): ?>
                                    <div class="d-flex mb-3">
                                        <!-- Avatar -->
                                        <img src="/animal_php/images/Footer/nekoparalogo.png" class="rounded-circle shadow-sm me-2 flex-shrink-0" style="height: 40px; width: 40px; object-fit: cover;">
                                        
                                        <!-- Comment Content -->
                                        <div class="d-flex flex-column align-items-start" style="max-width: 85%;">
                                            <!-- Bubble -->
                                            <div class="p-2 px-3 shadow-sm" style="background-color: rgba(255,255,255,0.12); display: inline-block; border-radius: 18px;">
                                                <div class="fw-bold text-white mb-1" style="font-size: 0.9rem;"><?= htmlspecialchars($comment['username']) ?></div>
                                                <div class="text-white" style="font-size: 0.95rem; line-height: 1.4; word-wrap: break-word;"><?= htmlspecialchars($comment['chat_data']) ?></div>
                                            </div>
                                            <!-- Actions/Date below bubble -->
                                            <div class="d-flex align-items-center mt-1 ms-2" style="font-size: 0.75rem;">
                                                <span class="fw-bold me-3 btn-like <?= $comment['is_liked'] ? 'text-primary' : 'text-white-50' ?>" data-comment-id="<?= $comment['id_cmt'] ?>" style="cursor: pointer; transition: 0.2s;">
                                                    <i class="fas fa-thumbs-up me-1"></i> <span class="like-count" style="display: <?= $comment['likes_count'] > 0 ? 'inline' : 'none' ?>"><?= $comment['likes_count'] ?></span>
                                                </span>
                                                <span class="text-white-50 me-3"><i class="far fa-clock me-1"></i><?= htmlspecialchars($comment['date_time']) ?></span>
                                                
                                                <div class="dropdown d-inline-block">
                                                    <button type="button" class="btn btn-link p-0 text-white-50 ms-1 border-0 shadow-none text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                                                        <i class="fas fa-share"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow border-0" style="background: rgba(15,23,42,0.9); backdrop-filter: blur(10px); border-radius: 12px; z-index: 1050;">
                                                        <li><a class="dropdown-item d-flex align-items-center btn-copy-link" href="#" data-url="<?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>"><i class="fas fa-link text-white-50 me-3 fs-5"></i> Sao chÃ©p liÃªn káº¿t</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </div>

                    <!-- Add Comment Form -->
                    <div class="card-footer bg-transparent border-top p-4" style="border-color: rgba(255,255,255,0.08) !important;">
                        <form id="commentForm" action="<?= $base ?>/view/post/add-comment.php" method="POST" class="m-0">
                            <input type="hidden" name="post_id" value="<?= htmlspecialchars($post_id) ?>" />
                            <div class="position-relative">
                                <input type="text" class="form-control premium-input w-100" name="chatData" required placeholder="Viáº¿t bÃ¬nh luáº­n cá»§a báº¡n..." autocomplete="off">
                                <button type="submit" class="btn-submit-premium position-absolute top-50 end-0 translate-middle-y me-2 shadow-sm" title="Gá»­i">
                                    <i class="fas fa-paper-plane" style="margin-left: -2px;"></i>
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
                    <div class="d-flex mb-3">
                        <!-- Avatar -->
                        <img src="/animal_php/images/Footer/nekoparalogo.png" class="rounded-circle shadow-sm me-2 flex-shrink-0" style="height: 40px; width: 40px; object-fit: cover;">
                        
                        <!-- Comment Content -->
                        <div class="d-flex flex-column align-items-start" style="max-width: 85%;">
                            <!-- Bubble -->
                            <div class="p-2 px-3 shadow-sm" style="background-color: rgba(255,255,255,0.12); display: inline-block; border-radius: 18px;">
                                <div class="fw-bold text-white mb-1" style="font-size: 0.9rem;">${comment.username}</div>
                                <div class="text-white" style="font-size: 0.95rem; line-height: 1.4; word-wrap: break-word;">${comment.chat_data}</div>
                            </div>
                            <!-- Actions/Date below bubble -->
                            <div class="d-flex align-items-center mt-1 ms-2" style="font-size: 0.75rem;">
                                <span class="fw-bold me-3 btn-like ${comment.is_liked ? 'text-primary' : 'text-white-50'}" data-comment-id="${comment.id_cmt}" style="cursor: pointer; transition: 0.2s;">
                                    <i class="fas fa-thumbs-up me-1"></i> <span class="like-count" style="display: ${comment.likes_count > 0 ? 'inline' : 'none'}">${comment.likes_count}</span>
                                </span>
                                <span class="text-white-50 me-3"><i class="far fa-clock me-1"></i>${comment.date_time}</span>
                                
                                <div class="dropdown d-inline-block">
                                    <button type="button" class="btn btn-link p-0 text-white-50 ms-1 border-0 shadow-none text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                                        <i class="fas fa-share"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow border-0" style="background: rgba(15,23,42,0.9); backdrop-filter: blur(10px); border-radius: 12px; z-index: 1050;">
                                        <li><a class="dropdown-item d-flex align-items-center btn-copy-link" href="#" data-url="<?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>"><i class="fas fa-link text-white-50 me-3 fs-5"></i> Sao chÃ©p liÃªn káº¿t</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                commentsWrapper.innerHTML += commentHtml;
            });

            // Auto scroll ONLY if the user is already at the bottom to avoid hijacking scroll
            if(commentsWrapper.scrollTop + commentsWrapper.clientHeight >= commentsWrapper.scrollHeight - 50) {
                commentsWrapper.scrollTop = commentsWrapper.scrollHeight;
            }
        })
        .catch(error => console.error('Error fetching comments:', error));
}

    // Fetch comments every 5 seconds (Wait, let's make it 3000ms instead of 500ms to save CPU)
    // setInterval(fetchComments, 3000); // Disabled to allow interactive UI elements to persist

    // Fetch comments on page load
    fetchComments();
</script>
<script>
    const commentForm = document.getElementById('commentForm');

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

    // Add UI interactivity for Like Button and Copy Link
    document.addEventListener('click', function(e) {
        // Handle Copy Link
        const copyBtn = e.target.closest('.btn-copy-link');
        if (copyBtn) {
            e.preventDefault();
            navigator.clipboard.writeText(copyBtn.getAttribute('data-url')).then(() => {
                alert('ÄÃ£ sao chÃ©p liÃªn káº¿t!');
            });
        }

        // Handle Like Button
        const likeBtn = e.target.closest('.btn-like');
        if(likeBtn) {
            const commentId = likeBtn.getAttribute('data-comment-id');
            const formData = new FormData();
            formData.append('comment_id', commentId);

            fetch('<?= $base ?>/view/post/like-comment.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    if (data.is_liked) {
                        likeBtn.classList.remove('text-white-50');
                        likeBtn.classList.add('text-primary');
                    } else {
                        likeBtn.classList.remove('text-primary');
                        likeBtn.classList.add('text-white-50');
                    }
                    const countSpan = likeBtn.querySelector('.like-count');
                    if (data.like_count > 0) {
                        countSpan.style.display = 'inline';
                        countSpan.innerText = data.like_count;
                    } else {
                        countSpan.style.display = 'none';
                    }
                } else {
                    alert(data.error);
                }
            })
            .catch(err => console.error(err));
        }

        // Handle Post Like Button
        const postLikeBtn = e.target.closest('.btn-like-post');
        if (postLikeBtn) {
            const postId = postLikeBtn.getAttribute('data-post-id');
            const formData = new FormData();
            formData.append('post_id', postId);

            fetch('<?= $base ?>/view/post/like-post.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    if (data.is_liked) {
                        postLikeBtn.classList.remove('btn-outline-light');
                        postLikeBtn.classList.add('btn-primary');
                    } else {
                        postLikeBtn.classList.remove('btn-primary');
                        postLikeBtn.classList.add('btn-outline-light');
                    }
                    const countSpan = postLikeBtn.querySelector('.post-like-count');
                    if (data.like_count > 0) {
                        countSpan.style.display = 'inline';
                        countSpan.innerText = '(' + data.like_count + ')';
                    } else {
                        countSpan.style.display = 'none';
                    }
                } else {
                    alert(data.error);
                }
            })
            .catch(err => console.error(err));
        }
    });
</script>
</html>
