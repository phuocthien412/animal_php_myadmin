<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../config/env.php';
require_once __DIR__ . '/../../../controller/PostController.php';

$base = BASE_URL;

if (!isset($_SESSION['user_id'])) {
    header("Location: $base/Login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $user_id = $_SESSION['user_id'];
    $date = date('Y-m-d H:i:s');
    
    // Validate inputs
    if (empty($title)) {
        header("Location: $base/Posts?error=" . urlencode("Vui lòng nhập tiêu đề bài viết."));
        exit();
    }
    
    $imagePath = '';
    
    // Handle image upload
    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../../images/Posts/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = time() . '_' . basename($_FILES['imageFile']['name']);
        $targetFile = $uploadDir . $fileName;
        
        // Allowed file types
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['imageFile']['tmp_name']);
        
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['imageFile']['tmp_name'], $targetFile)) {
                $imagePath = 'Posts/' . $fileName;
            } else {
                header("Location: $base/Posts?error=" . urlencode("Có lỗi xảy ra khi tải ảnh lên."));
                exit();
            }
        } else {
            header("Location: $base/Posts?error=" . urlencode("Định dạng ảnh không hợp lệ."));
            exit();
        }
    } else {
        header("Location: $base/Posts?error=" . urlencode("Vui lòng chọn ảnh cho bài viết."));
        exit();
    }
    
    if ($title && $imagePath) {
        $postController = new PostController();
        try {
            $postController->createPost([
                'date' => $date,
                'image' => $imagePath,
                'title' => $title,
                'user_id' => $user_id
            ]);
            
            // Redirect back with success message
            header("Location: $base/Posts?success=" . urlencode("Bài viết của bạn đã được đăng thành công."));
            exit();
        } catch (Exception $e) {
            header("Location: $base/Posts?error=" . urlencode("Đã có lỗi hệ thống: " . $e->getMessage()));
            exit();
        }
    }
} else {
    // If accessed directly without POST, redirect to Posts
    header("Location: $base/Posts");
    exit();
}
?>
