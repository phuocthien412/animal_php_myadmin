<?php
/**
 * Base URL helper — tự động detect đường dẫn gốc của project.
 * File này được include bởi headerAdmin.php và login.php.
 * Sau khi include: $base = '/animal_php_myadmin/animal_php_myadmin' (ví dụ)
 */
if (!defined('BASE_URL')) {
    // Lấy DOCUMENT_ROOT và đường dẫn thực của project root (thư mục chứa .htaccess)
    $docRoot    = rtrim(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])), '/');
    // config/ nằm trong project root, nên đi lên 1 cấp
    $projectDir = rtrim(str_replace('\\', '/', realpath(__DIR__ . '/..')), '/');
    // Tính basePath tương đối
    $basePath   = str_replace($docRoot, '', $projectDir);
    $basePath   = rtrim($basePath, '/');

    define('BASE_URL', $basePath);
}

// Biến ngắn gọn để dùng trong views
$base = BASE_URL;
?>
