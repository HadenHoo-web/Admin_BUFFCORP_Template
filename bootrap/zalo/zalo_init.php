<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/services/ZaloTokenManager.php';
$config = include __DIR__ . '/config/zalo.php';

// log để debug
$logFile = __DIR__ . '/logs/zalo_init.log';
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set('Asia/Ho_Chi_Minh');
}
function zalo_log($file, $msg) {
    @file_put_contents($file, date('Y-m-d H:i:s') . " | " . $msg . PHP_EOL, FILE_APPEND);
}

if (empty($_GET['code'])) {
    zalo_log($logFile, "MISSING_CODE | query=" . json_encode($_GET));
    die('❌ Missing code');
}

$tm  = new ZaloTokenManager($config);
zalo_log($logFile, "EXCHANGE_START | code=" . $_GET['code']);

$res = $tm->exchangeCode($_GET['code']);
zalo_log($logFile, "EXCHANGE_DONE | res=" . json_encode($res, JSON_UNESCAPED_UNICODE));

echo '<pre>';
print_r($res);
echo '</pre>';