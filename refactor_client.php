<?php
$base_dir = __DIR__ . '/view';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base_dir));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        
        // Skip admin files as they are already processed
        if (strpos($path, DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR) !== false) {
            continue;
        }

        $content = file_get_contents($path);
        $lines = explode("\n", $content);
        $new_lines = [];
        $modified = false;
        $has_env = false;
        
        foreach ($lines as $line) {
            if (stripos($line, 'require_once') !== false && stripos($line, 'controller') !== false) {
                $modified = true;
                continue;
            }
            if (stripos($line, 'config/env.php') !== false) {
                $has_env = true;
            }
            $new_lines[] = $line;
        }
        
        if ($modified) {
            $content = implode("\n", $new_lines);
            
            if (!$has_env && strpos($content, '<?php') !== false) {
                $rel_path = str_replace($base_dir . DIRECTORY_SEPARATOR, '', $path);
                $depth = substr_count($rel_path, DIRECTORY_SEPARATOR) + 1;
                $env_path = str_repeat('../', $depth) . 'config/env.php';
                
                $auth_code = "\nrequire_once __DIR__ . '/$env_path';\n";
                $content = preg_replace('/<\?php/', '<?php' . $auth_code, $content, 1);
            }
            
            file_put_contents($path, $content);
            echo "Processed $path\n";
        }
    }
}
echo "Done.\n";
