<?php
require_once __DIR__ . '/../../config/env.php';


if (isset($_GET['id_post'])) {
    $id_post = $_GET['id_post'];

    $postController = new PostController();
    $postController->deletePost($id_post);
}

header("Location: list_posts.php");
exit();
?>