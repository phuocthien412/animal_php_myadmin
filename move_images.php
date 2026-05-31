<?php
$baseDir = __DIR__ . '/images/';
$files = scandir($baseDir);
$movedCount = 0;

foreach ($files as $file) {
    if ($file === '.' || $file === '..' || is_dir($baseDir . $file)) {
        continue;
    }
    
    $lowerFile = strtolower($file);
    $targetFolder = null;
    
    if (strpos($lowerFile, 'avatar') !== false) {
        $targetFolder = 'Animal/Avatar';
    } elseif (strpos($lowerFile, 'nss') !== false) {
        $targetFolder = 'Animal/NoiSinhSong';
    } elseif (strpos($lowerFile, 'qr') !== false) {
        $targetFolder = 'Animal/3DQR';
    } elseif (strpos($lowerFile, 'list') !== false) {
        $targetFolder = 'Animal/ListImage';
    } elseif (strpos($lowerFile, '.gif') !== false || strpos($lowerFile, 'dongvat') !== false || strpos($lowerFile, 'ca.gif') !== false || strpos($lowerFile, 'chim.gif') !== false) {
        $targetFolder = 'ClassAnimal';
    } else {
        $targetFolder = 'Animal'; // default to Animal
    }
    
    if ($targetFolder) {
        if (!is_dir($baseDir . $targetFolder)) {
            mkdir($baseDir . $targetFolder, 0777, true);
        }
        rename($baseDir . $file, $baseDir . $targetFolder . '/' . $file);
        $movedCount++;
    }
}

echo "Moved $movedCount files successfully.";
