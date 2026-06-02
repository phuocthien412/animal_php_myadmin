<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle language switch
if (isset($_GET['lang']) && in_array($_GET['lang'], ['vi', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Set default language
$lang = $_SESSION['lang'] ?? 'vi';

// Load language array
$langFile = __DIR__ . "/../config/lang/{$lang}.php";
if (file_exists($langFile)) {
    global $i18n_strings;
    $i18n_strings = require $langFile;
} else {
    global $i18n_strings;
    $i18n_strings = [];
}

/**
 * Get translated string by key
 */
function __(string $key): string {
    global $i18n_strings;
    if (isset($i18n_strings[$key])) {
        return $i18n_strings[$key];
    }

    $lang = $_SESSION['lang'] ?? 'vi';
    if ($lang === 'en') {
        if ($key === '[Đã ẩn bởi quản trị]') {
            return '[Hidden by admin]';
        }

        // Static translations map for notification parts
        $staticMap = [
            'Đã tạo' => 'Created',
            'Đã xoá' => 'Deleted',
            'Đã cập nhật' => 'Updated',
            'Đã thêm ảnh' => 'Added image',
            'Đã ẩn' => 'Hidden',
            'Đã hiện' => 'Shown',
            'Đã cập nhật vai trò' => 'Updated role',
            'Đã cập nhật ảnh' => 'Updated image',
            'Đã đổi mật khẩu' => 'Changed password',

            'Động vật mới' => 'New Animal',
            'Ảnh động vật đã xoá' => 'Deleted Animal Image',
            'Ảnh động vật mới' => 'New Animal Image',
            'Động vật đã xoá' => 'Deleted Animal',
            'Động vật đã cập nhật' => 'Updated Animal',
            'Lớp động vật mới' => 'New Animal Class',
            'Lớp động vật đã cập nhật' => 'Updated Animal Class',
            'Lớp động vật đã xoá' => 'Deleted Animal Class',
            'Bình luận mới' => 'New Comment',
            'Bình luận đã cập nhật' => 'Updated Comment',
            'Bình luận đã xoá' => 'Deleted Comment',
            'Xoá bình luận theo người dùng' => 'Delete Comments by User',
            'Bình luận đã ẩn' => 'Hidden Comment',
            'Bình luận đã hiện' => 'Shown Comment',
            'Bài viết mới' => 'New Post',
            'Bài viết đã cập nhật' => 'Updated Post',
            'Bài viết đã xoá' => 'Deleted Post',
            'Vai trò mới' => 'New Role',
            'Vai trò đã cập nhật' => 'Updated Role',
            'Vai trò đã xoá' => 'Deleted Role',
            'Người dùng mới' => 'New User',
            'Người dùng đã cập nhật' => 'Updated User',
            'Người dùng đã xoá' => 'Deleted User',
            'Vai trò người dùng đã cập nhật' => 'Updated User Role',
            'Ảnh đại diện đã cập nhật' => 'Updated Avatar Image',
            'Mật khẩu người dùng đã đổi' => 'Changed User Password',
        ];

        if (isset($staticMap[$key])) {
            return $staticMap[$key];
        }

        // Dynamic regex translation for messages
        $patterns = [
            '/^Vừa thêm loài "(.*)"$/u' => 'Added species "$1"',
            '/^Vừa xoá loài "(.*)"$/u' => 'Deleted species "$1"',
            '/^Vừa cập nhật loài "(.*)"$/u' => 'Updated species "$1"',
            '/^Vừa xoá một ảnh phụ của động vật #(.*)$/u' => 'Deleted a gallery image of animal #$1',
            '/^Vừa thêm ảnh phụ cho động vật #(.*)$/u' => 'Added a gallery image for animal #$1',
            '/^Vừa thêm lớp "(.*)"$/u' => 'Added class "$1"',
            '/^Vừa cập nhật lớp "(.*)"$/u' => 'Updated class "$1"',
            '/^Vừa xoá lớp "(.*)"$/u' => 'Deleted class "$1"',
            '/^Vừa có bình luận mới$/u' => 'New comment added',
            '/^Vừa cập nhật bình luận #(.*)$/u' => 'Updated comment #$1',
            '/^Vừa xoá bình luận #(.*)$/u' => 'Deleted comment #$1',
            '/^Vừa xoá (\d+) bình luận của người dùng #(.*)$/u' => 'Deleted $1 comments of user #$2',
            '/^Vừa ẩn bình luận #(.*)$/u' => 'Hidden comment #$1',
            '/^Vừa hiện lại bình luận #(.*)$/u' => 'Unhidden comment #$1',
            '/^Vừa có bình luận mới cho bài viết #(.*)$/u' => 'New comment on post #$1',
            '/^Vừa thêm bài viết "(.*)"$/u' => 'Added post "$1"',
            '/^Vừa cập nhật bài viết "(.*)"$/u' => 'Updated post "$1"',
            '/^Vừa xoá bài viết "(.*)"$/u' => 'Deleted post "$1"',
            '/^Vừa thêm vai trò "(.*)"$/u' => 'Added role "$1"',
            '/^Vừa cập nhật vai trò "(.*)"$/u' => 'Updated role "$1"',
            '/^Vừa xoá vai trò "(.*)"$/u' => 'Deleted role "$1"',
            '/^Vừa thêm tài khoản "(.*)"$/u' => 'Added account "$1"',
            '/^Vừa cập nhật tài khoản "(.*)"$/u' => 'Updated account "$1"',
            '/^Vừa xoá tài khoản "(.*)"$/u' => 'Deleted account "$1"',
            '/^Vừa cập nhật vai trò cho "(.*)"$/u' => 'Updated role for "$1"',
            '/^Vừa cập nhật avatar cho người dùng #(.*)$/u' => 'Updated avatar for user #$1',
            '/^Vừa đổi mật khẩu cho người dùng #(.*)$/u' => 'Changed password for user #$1',
        ];

        foreach ($patterns as $pattern => $replacement) {
            $replaced = preg_replace($pattern, $replacement, $key);
            if ($replaced !== null && $replaced !== $key) {
                return $replaced;
            }
        }
    }

    return $key;
}
