<?php
session_start();
require_once '../../../config/env.php'; // Load $base từ .env

session_unset();
session_destroy();

header('Location: ' . $base . '/Login');
exit();
?>