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
    return $i18n_strings[$key] ?? $key;
}
