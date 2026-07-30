<?php
$localConfig = array();
$localConfigFile = __DIR__ . '/zalo.local.php';
if (file_exists($localConfigFile)) {
    $loadedLocalConfig = include $localConfigFile;
    if (is_array($loadedLocalConfig)) {
        $localConfig = $loadedLocalConfig;
    }
}

$config = function ($key, $default = '') use ($localConfig) {
    $value = getenv('ZALO_' . strtoupper($key));
    if ($value !== false && $value !== '') {
        return $value;
    }
    return array_key_exists($key, $localConfig) ? $localConfig[$key] : $default;
};

return [
    'oa_id' => $config('oa_id'),
    'app_id' => $config('app_id'),
    'secret_key' => $config('secret_key'),
    'api_send' => 'https://openapi.zalo.me/v3.0/oa/message/cs',
    'oauth_token_url' => 'https://oauth.zaloapp.com/v4/oa/access_token',
    'token_store' => __DIR__ . '/../storage/zalo_token.json',
];
