<?php
$baseDir = __DIR__ . '/images/';
$filesToMove = ['idle.gif', 'test.gif', 'trailer1.gif'];

foreach ($filesToMove as $file) {
    if (file_exists($baseDir . 'ClassAnimal/' . $file)) {
        rename($baseDir . 'ClassAnimal/' . $file, $baseDir . $file);
        echo "Moved $file back to images/<br>";
    }
}
echo "Done.";
