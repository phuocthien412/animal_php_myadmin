<?php
spl_autoload_register(function ($class_name) {
    // List of directories to scan for classes
    $directories = [
        __DIR__ . '/../controller/',
        __DIR__ . '/../model/'
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
