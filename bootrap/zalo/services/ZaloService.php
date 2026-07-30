<?php

require_once __DIR__ . '/ZaloTokenManager.php';

class ZaloService
{
    private $config;
    private $logFile;
    private $tokenManager;

    public function __construct()
    {
        // services/ -> ../config/
        $this->config = include __DIR__ . '/../config/zalo.php';

        // services/ -> ../logs/
        $this->logFile = __DIR__ . '/../logs/zalo_send.log';
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0775, true);
        }

        $this->tokenManager = new ZaloTokenManager($this->config);
    }

    public function sendText($zaloUserId, $message)
    {
        if (!function_exists('curl_init')) {
            $this->log(['step' => 'ERROR', 'message' => 'PHP cURL extension is not available']);
            return ['error' => 'PHP cURL extension is not available'];
        }

        $this->log([
            'step' => 'INPUT',
            'zalo_user_id' => $zaloUserId,
            'message' => $message
        ]);

        $accessToken = $this->tokenManager->getAccessToken();
        if (!$accessToken) {
            $this->log(['step' => 'ERROR', 'message' => 'NO_ACCESS_TOKEN']);
            return false;
        }

        $payload = [
            'recipient' => ['user_id' => $zaloUserId],
            'message'   => ['text' => $message]
        ];

        $ch = curl_init($this->config['api_send']);
        if (!$ch) {
            $this->log(['step' => 'ERROR', 'message' => 'Unable to initialize cURL']);
            return ['error' => 'Unable to initialize cURL'];
        }
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'access_token: ' . $accessToken
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        $this->log([
            'step' => 'RESPONSE',
            'response_raw' => $response,
            'response' => $result,
            'curl_error' => $error
        ]);

        return $result;
    }

    private function log($data)
    {
        // ✅ Không tạo folder tự động nữa. Folder logs phải tồn tại sẵn.
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0775, true);
        }
        @file_put_contents(
            $this->logFile,
            date('Y-m-d H:i:s') . ' | ' . json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND
        );
    }
}
