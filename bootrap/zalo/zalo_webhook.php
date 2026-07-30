<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// TRẢ 200 NGAY
http_response_code(200);
echo 'OK';

// đẩy response sớm
if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
}

// ===== LOG PATH (CỐ ĐỊNH – KHÔNG TẠO FOLDER) =====
$logFile = __DIR__ . '/logs/zalo_send.log';

// TEST GHI LOG TRƯỚC
file_put_contents(
    $logFile,
    date('Y-m-d H:i:s') . " | WEBHOOK HIT" . PHP_EOL,
    FILE_APPEND
);

// LẤY BODY
$raw = file_get_contents('php://input');

file_put_contents(
    $logFile,
    date('Y-m-d H:i:s') . " | RAW | " . $raw . PHP_EOL,
    FILE_APPEND
);

// PARSE JSON
$data = json_decode($raw, true);

if (isset($data['sender']['id'])) {
    file_put_contents(
        $logFile,
        date('Y-m-d H:i:s') .
        " | USER | zalo_user_id=" . $data['sender']['id'] .
        PHP_EOL,
        FILE_APPEND
    );
}