<?php
/**
 * Env Loader — đọc file .env ở project root
 * Dùng: require_once __DIR__ . '/../config/env.php';
 *       $base = env('BASE_URL'); // hoặc dùng $_ENV['BASE_URL']
 */

function loadEnv(string $path): void {
    if (!file_exists($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Bỏ comment
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;

        // Parse KEY=VALUE
        if (!str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);

        // Bỏ dấu nháy bao quanh nếu có
        $value = trim($value, '"\'');

        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key]    = $value;
            $_SERVER[$key] = $value;
            putenv("$key=$value");
        }
    }
}

/**
 * Lấy giá trị env, có giá trị mặc định nếu không tìm thấy.
 */
function env(string $key, string $default = ''): string {
    return $_ENV[$key] ?? getenv($key) ?: $default;
}

// Load .env từ project root (thư mục cha của config/)
loadEnv(__DIR__ . '/../.env');

// Tạo $base toàn cục
$base = rtrim(env('BASE_URL', ''), '/');
define('BASE_URL', $base);

// Load i18n
require_once __DIR__ . '/../lib/i18n.php';

// Load Autoloader
require_once __DIR__ . '/autoload.php';

/**
 * Generates a secure, unique filename according to Senior Developer standards
 */
function generateSafeFilename(string $fileName): string {
    $pathInfo = pathinfo($fileName);
    $extension = isset($pathInfo['extension']) ? strtolower($pathInfo['extension']) : '';
    $filenameWithoutExt = isset($pathInfo['filename']) ? $pathInfo['filename'] : '';

    // Sanitize the filename: replace non-alphanumeric characters with hyphens
    $sanitized = preg_replace('/[^a-zA-Z0-9_\-]/', '-', $filenameWithoutExt);
    $sanitized = preg_replace('/-+/', '-', $sanitized);
    $sanitized = trim($sanitized, '-');

    if ($sanitized === '') {
        $sanitized = 'file';
    }

    // Append unique cryptographically secure random suffix to prevent collisions
    try {
        $suffix = bin2hex(random_bytes(6));
    } catch (Exception $e) {
        $suffix = uniqid();
    }
    
    $uniqueName = $sanitized . '_' . $suffix;
    
    if ($extension !== '') {
        $uniqueName .= '.' . $extension;
    }
    
    return $uniqueName;
}

