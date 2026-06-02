<?php
session_start();
require_once '../../../config/env.php'; // Load $base từ .env
$base = BASE_URL;
session_unset();
session_destroy();

header('Location: ' . $base . '/Login');
exit();