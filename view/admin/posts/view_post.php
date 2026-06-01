<?php
require_once __DIR__ . '/../../../config/env.php';
$authController = new UserController();
$authController->authorize('ADMIN', '/Home');

require_once '../../../config/env.php';

$base = BASE_URL;

$postController = new PostController();
$userController = new UserController();
$commentController = new CommentController();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $postId = intval($_GET['id']);
    $post = $postController->getPostById($postId);
    if (!$post) {
        die("Post not found.");
    }
    $username = $userController->getUsernameById($post['user_id']);
} else {
    die("Invalid request.");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEKOPARA — <?= __('admin_post_detail') ?></title>
    <style>
        .detail-card {
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
        }
        .comments-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        .comment-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px;
            background: #fff;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }
        .comment-card .meta {
            font-weight: 600;
            margin-bottom: 6px;
        }
        .comment-card .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-left: auto;
            justify-content: flex-end;
        }
        .comment-card .actions form {
            margin: 0;
        }
        @media (max-width: 992px) {
            .comments-grid {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 576px) {
            .detail-card {
                padding: 16px;
            }
            .comment-card {
                flex-direction: column;
            }
            .comment-card .actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>
    <?php include '../../headerAdmin.php'; ?>
    <div class="page-header">
        <h1><i class="fa-solid fa-eye" style="color:var(--accent-orange);margin-right:10px;font-size:20px;"></i><?= __('admin_post_detail') ?></h1>
        <div class="breadcrumb-text">NEKOPARA <span>›</span> <?= __('admin') ?> <span>›</span> <?= __('admin_posts') ?> <span>›</span> <?= __('admin_detail') ?></div>
    </div>
    
    <div class="card detail-card">
        <h2 class="mt-2"><?= htmlspecialchars($post['title']) ?></h2>
        
        <div class="mb-4" style="color: var(--text-muted); font-size: 14px;">
            <i class="fa-solid fa-user"></i> <?= __('admin_posted_by') ?>: <strong><?= htmlspecialchars($username ?? 'Unknown') ?></strong>
            <span style="margin: 0 10px;">|</span>
            <i class="fa-regular fa-calendar"></i> <?= __('table_date_posted') ?>: <?= htmlspecialchars($post['date'] ?? '—') ?>
        </div>

        <?php if (!empty($post['image'])): ?>
            <div class="text-center mb-4">
                <img src="<?= $base ?>/images/<?= htmlspecialchars($post['image']) ?>" alt="Post Image" style="max-width: 100%; border-radius: 10px; max-height: 400px; object-fit: cover;">
            </div>
        <?php endif; ?>

        <div class="post-content" style="font-size: 16px; line-height: 1.6; color: var(--text-primary);">
            <?= nl2br(htmlspecialchars($post['content'] ?? '')) ?>
        </div>
        
        <!-- Comments panel -->
        <div class="mt-4" style="border-top:1px solid var(--border-light); padding-top:16px;">
            <h4><?= __('admin_comments') ?></h4>
            <form method="post" action="<?= $base ?>/view/admin/posts/comment-action.php" style="margin-bottom:12px;">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="post_id" value="<?= intval($post['id_post']) ?>">
                <input type="hidden" name="redirect" value="<?= htmlspecialchars($base . '/admin/posts/detail/' . intval($post['id_post'])) ?>">
                <div class="mb-2 d-flex gap-2">
                    <input name="chat_data" class="form-control" placeholder="<?= __('admin_write_comment') ?>" required>
                    <button class="btn btn-primary" type="submit"><?= __('btn_add') ?></button>
                </div>
            </form>
            <?php $comments = $commentController->getCommentsByPostId($post['id_post']); ?>
            <?php if (empty($comments)): ?>
                <div class="text-muted"><?= __('admin_no_comments') ?></div>
            <?php else: ?>
                <div class="comments-grid">
                <?php foreach ($comments as $c): ?>
                    <?php $cUser = $userController->getUserById($c['user_id']); ?>
                    <div class="comment-card">
                        <div style="flex:1">
                            <div class="meta"><?= htmlspecialchars($cUser['username'] ?? 'Unknown') ?> <small class="text-muted">• <?= htmlspecialchars($c['date_time']) ?></small></div>
                            <?php $isHidden = isset($c['hidden']) && intval($c['hidden']) === 1; ?>
                            <?php $hasOrig = $isHidden && !empty($c['orig_chat_data']); ?>
                            <div class="mt-2 comment-content" style="white-space:pre-wrap;">
                                <?= nl2br(htmlspecialchars($c['chat_data'])) ?>
                            </div>
                            <?php if ($isHidden): ?>
                                <div class="text-warning small mt-2">(<?= __('admin_hidden_by_admin') ?>)</div>
                                <div class="orig-content d-none"><?= nl2br(htmlspecialchars($c['orig_chat_data'] ?? '')) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="actions">
                            <?php if ($isHidden && $hasOrig): ?>
                                <button class="btn btn-sm btn-outline-primary toggle-hidden" type="button" title="<?= __('btn_temp_show') ?>"><i class="fa-solid fa-eye"></i></button>
                                <form method="post" action="<?= $base ?>/view/admin/posts/comment-action.php" style="display:inline-block; margin-left:6px;">
                                    <input type="hidden" name="action" value="unhide">
                                    <input type="hidden" name="id_cmt" value="<?= intval($c['id_cmt']) ?>">
                                    <input type="hidden" name="redirect" value="<?= htmlspecialchars($base . '/admin/posts/detail/' . intval($post['id_post'])) ?>">
                                    <button class="btn btn-sm btn-success" type="submit" title="<?= __('btn_unhide') ?>"><i class="fa-solid fa-check"></i></button>
                                </form>
                            <?php elseif ($isHidden): ?>
                                <form method="post" action="<?= $base ?>/view/admin/posts/comment-action.php" style="display:inline-block;">
                                    <input type="hidden" name="action" value="unhide">
                                    <input type="hidden" name="id_cmt" value="<?= intval($c['id_cmt']) ?>">
                                    <input type="hidden" name="redirect" value="<?= htmlspecialchars($base . '/admin/posts/detail/' . intval($post['id_post'])) ?>">
                                    <button class="btn btn-sm btn-success" type="submit" title="<?= __('btn_unhide') ?>"><i class="fa-solid fa-check"></i></button>
                                </form>
                            <?php else: ?>
                                <form method="post" action="<?= $base ?>/view/admin/posts/comment-action.php">
                                    <input type="hidden" name="action" value="hide">
                                    <input type="hidden" name="id_cmt" value="<?= intval($c['id_cmt']) ?>">
                                    <input type="hidden" name="redirect" value="<?= htmlspecialchars($base . '/admin/posts/detail/' . intval($post['id_post'])) ?>">
                                    <button class="btn btn-sm btn-warning" type="submit" title="<?= __('btn_hide') ?>"><i class="fa-solid fa-eye-slash"></i></button>
                                </form>
                            <?php endif; ?>
                            <form method="post" action="<?= $base ?>/view/admin/posts/comment-action.php">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id_cmt" value="<?= intval($c['id_cmt']) ?>">
                                <input type="hidden" name="redirect" value="<?= htmlspecialchars($base . '/admin/posts/detail/' . intval($post['id_post'])) ?>">
                                <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('<?= __('confirm_delete_comment') ?>')" title="<?= __('btn_delete') ?>"><i class="fa-solid fa-trash"></i></button>
                            </form>
                            <form method="post" action="<?= $base ?>/view/admin/posts/comment-action.php">
                                <input type="hidden" name="action" value="bulk_delete_user">
                                <input type="hidden" name="user_id" value="<?= intval($c['user_id']) ?>">
                                <input type="hidden" name="redirect" value="<?= htmlspecialchars($base . '/admin/posts/detail/' . intval($post['id_post'])) ?>">
                                <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('<?= __('confirm_delete_all_comments') ?>')" title="<?= __('btn_delete_all') ?>"><i class="fa-solid fa-ban"></i></button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <script>
            // Toggle show hidden comment content client-side
            document.addEventListener('click', function(e){
                if (e.target && e.target.classList.contains('toggle-hidden')){
                    var btn = e.target;
                    var card = btn.closest('.comment-card');
                    var content = card.querySelector('.comment-content');
                    var orig = card.querySelector('.orig-content');
                    if (!orig) return;
                    if (orig.classList.contains('d-none')) {
                        // store current placeholder so we can restore it
                        card.dataset.placeholder = content.innerHTML;
                        content.innerHTML = orig.innerHTML;
                        orig.classList.remove('d-none');
                        btn.textContent = '<?= __('btn_temp_hide') ?>';
                    } else {
                        // restore placeholder
                        content.innerHTML = card.dataset.placeholder || '[<?= __('admin_hidden_by_admin') ?>]';
                        orig.classList.add('d-none');
                        btn.textContent = '<?= __('btn_temp_show') ?>';
                    }
                }
            });
        </script>

        <div class="mt-4" style="text-align: right; border-top: 1px solid var(--border-light); padding-top: 15px;">
            <a href="<?= $base ?>/admin/posts" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> <?= __('btn_back_to_list') ?></a>
        </div>
    </div>
    <?php include '../../footerAdmin.php'; ?>
</body>
</html>
