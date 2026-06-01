<?php
$base_dir = __DIR__ . '/view/admin';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base_dir));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        
        $lines = explode("\n", $content);
        $new_lines = [];
        $has_auth = false;
        
        foreach ($lines as $line) {
            // Remove require_once for controller
            if (stripos($line, 'require_once') !== false && stripos($line, 'controller') !== false) {
                continue;
            }
            if (strpos($line, 'authorize') !== false && strpos($line, 'ADMIN') !== false) {
                $has_auth = true;
            }
            $new_lines[] = $line;
        }
        
        $content = implode("\n", $new_lines);
        
        if (!$has_auth && strpos($content, '<?php') !== false) {
            // Calculate depth
            $rel_path = str_replace($base_dir . DIRECTORY_SEPARATOR, '', $path);
            $depth = substr_count($rel_path, DIRECTORY_SEPARATOR) + 2;
            $env_path = str_repeat('../', $depth) . 'config/env.php';
            
            $auth_code = "\nrequire_once __DIR__ . '/$env_path';\n";
            $auth_code .= "\$authController = new UserController();\n";
            $auth_code .= "\$authController->authorize('ADMIN', '/Home');\n";
            
            $content = preg_replace('/<\?php/', '<?php' . $auth_code, $content, 1);
        }
        
        file_put_contents($path, $content);
        echo "Processed $path\n";
    }
}
echo "Done.\n";
