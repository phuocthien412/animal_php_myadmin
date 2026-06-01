import os

base_dir = r'c:\laragon\www\animal_php_myadmin\animal_php_myadmin\view\admin'
for root, dirs, files in os.walk(base_dir):
    for file in files:
        if file.endswith('.php'):
            path = os.path.join(root, file)
            with open(path, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Remove all require_once containing 'controller'
            lines = content.split('\n')
            new_lines = []
            has_auth = False
            for line in lines:
                if 'require_once' in line and 'controller' in line.lower():
                    continue
                if 'authorize' in line and 'ADMIN' in line:
                    has_auth = True
                new_lines.append(line)
            
            # Reconstruct content
            content = '\n'.join(new_lines)
            
            # If not authorized, add authorization at the top
            if not has_auth and '<?php' in content:
                # Find depth to config/env.php
                rel_path = os.path.relpath(path, base_dir)
                depth = rel_path.count(os.sep) + 2
                env_path = '/'.join(['..'] * depth) + '/config/env.php'
                
                auth_code = f"""
require_once __DIR__ . '/{env_path}';
$authController = new UserController();
$authController->authorize('ADMIN', '/Home');
"""
                content = content.replace('<?php', '<?php' + auth_code, 1)
                
            with open(path, 'w', encoding='utf-8') as f:
                f.write(content)
