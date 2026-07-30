<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$config = include __DIR__ . '/config/zalo.php';
require_once __DIR__ . '/services/ZaloTokenManager.php';

$code = $_GET['code'] ?? '';
if (!$code) {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'error' => 'Missing code'
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

$tm = new ZaloTokenManager($config);
$res = $tm->exchangeCode($code);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);