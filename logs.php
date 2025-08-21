<?php
// Simple log viewer - Add basic authentication for security
$username = 'admin';
$password = 'Vikash@1212'; // Change this!

if (!isset($_SERVER['PHP_AUTH_USER']) || 
    $_SERVER['PHP_AUTH_USER'] !== $username || 
    $_SERVER['PHP_AUTH_PW'] !== $password) {
    header('WWW-Authenticate: Basic realm="Log Viewer"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Access denied';
    exit;
}

$log_dir = 'application/logs/';
$files = glob($log_dir . 'log-*.php');
rsort($files); // Latest first

echo '<h2>System Logs</h2>';
echo '<style>body{font-family:Arial;} pre{background:#f5f5f5;padding:10px;overflow:auto;max-height:500px;}</style>';

foreach ($files as $file) {
    $filename = basename($file);
    $size = filesize($file);
    echo '<h3>' . $filename . ' (' . number_format($size) . ' bytes)</h3>';
    
    if (isset($_GET['view']) && $_GET['view'] === $filename) {
        $content = file_get_contents($file);
        $content = str_replace('<?php defined(\'BASEPATH\') OR exit(\'No direct script access allowed\'); ?>', '', $content);
        echo '<pre>' . htmlspecialchars($content) . '</pre>';
        echo '<hr>';
    } else {
        echo '<a href="?view=' . urlencode($filename) . '">View Content</a><br><br>';
    }
}
?>
