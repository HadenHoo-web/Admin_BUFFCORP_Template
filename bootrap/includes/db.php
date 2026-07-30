<?php
// ✅ Cho cron/CLI chạy
if (!defined('_CMS_')) {
    if (PHP_SAPI === 'cli') {
        define('_CMS_', true);
    } else {
        die('HACKING_ATTEMPT');
    }
}

// config.php phải được include trước file này để có $dbhost, $dbuser, $dbpasswd, $dbname...
// Nếu vì lý do nào đó chưa include, bạn có thể bật dòng dưới:
// require_once dirname(__DIR__) . '/config.php';

// ✅ đảm bảo có dbms, mặc định mysql
if (!isset($dbms) || !$dbms) {
    $dbms = 'mysql';
}

// ✅ include driver theo path tuyệt đối (không lệch khi chạy cron)
// ưu tiên: /bootrap/includes/db/mysql.php
$driverFile = __DIR__ . '/db/' . $dbms . '.php';

// fallback: /bootrap/db/mysql.php
if (!file_exists($driverFile)) {
    $driverFile = dirname(__DIR__) . '/db/' . $dbms . '.php';
}

if (!file_exists($driverFile)) {
    die('DB driver not found: ' . $driverFile);
}

require_once $driverFile;

// ✅ tạo kết nối
$db = new sql_db($dbhost, $dbuser, $dbpasswd, $dbname, false);
if (!$db->db_connect_id) {
    die(defined('DATABASE_CONNECT_ERROR') ? DATABASE_CONNECT_ERROR : 'Database connection error');
}
?>
