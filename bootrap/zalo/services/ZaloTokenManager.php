<?php

class ZaloTokenManager
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;

        $dir = dirname($this->config['token_store']);
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
    }

    public function getAccessToken()
    {
        $token = $this->readToken();

        // còn hạn
        if (!empty($token['access_token']) && !empty($token['expires_at'])) {
            if (time() < ($token['expires_at'] - 120)) {
                return $token['access_token'];
            }
        }

        // refresh token
        if (!empty($token['refresh_token'])) {
            $newToken = $this->requestToken([
                'app_id' => $this->config['app_id'],
                'grant_type' => 'refresh_token',
                'refresh_token' => $token['refresh_token'],
            ]);

            if (!empty($newToken['access_token'])) {
                $this->writeToken($this->normalizeToken($newToken));
                return $newToken['access_token'];
            }
        }

        return null;
    }

    public function exchangeCode($code)
    {
        $token = $this->requestToken([
            'app_id' => $this->config['app_id'],
            'grant_type' => 'authorization_code',
            'code' => $code,
        ]);

        if (!empty($token['access_token'])) {
            $this->writeToken($this->normalizeToken($token));
        }

        return $token;
    }

    private function requestToken($data)
    {
        if (!function_exists('curl_init')) {
            return array('_curl_error' => 'PHP cURL extension is not available');
        }

        $ch = curl_init($this->config['oauth_token_url']);
        if (!$ch) {
            return array('_curl_error' => 'Unable to initialize cURL');
        }

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'secret_key: ' . $this->config['secret_key'],
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        $json = json_decode($response, true);
        if (!is_array($json)) {
            $json = [];
        }

        if ($error) {
            $json['_curl_error'] = $error;
        }

        return $json;
    }

    private function normalizeToken($token)
    {
        $expiresIn = isset($token['expires_in']) ? intval($token['expires_in']) : 3600;

        return [
            'access_token'  => isset($token['access_token']) ? $token['access_token'] : null,
            'refresh_token' => isset($token['refresh_token']) ? $token['refresh_token'] : null,
            'expires_at'    => time() + $expiresIn,
            'raw'           => $token,
        ];
    }

    private function readToken()
    {
        if (!file_exists($this->config['token_store'])) {
            return [];
        }

        $data = json_decode(file_get_contents($this->config['token_store']), true);
        return is_array($data) ? $data : [];
    }

    private function writeToken($token)
    {
        $dir = dirname($this->config['token_store']);
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        @file_put_contents(
            $this->config['token_store'],
            json_encode($token, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }
}
