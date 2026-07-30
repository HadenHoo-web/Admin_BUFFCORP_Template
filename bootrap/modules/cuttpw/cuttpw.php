<?php
global $template, $root_path, $languageid;

$action = mosGetParam($_REQUEST, 'mode', 'dashboard');

$template->assign_vars(array(
    'ROOT' => $root_path,
    'funname' => 'cuttpw/cuttpw',
    'LANGUAGEID' => $languageid,
));

switch ($action) {
    case 'dashboard':
    case 'list':
        mosCuttpwDashboard();
        break;

    default:
        mosInvalidURL();
        exit;
}

function cuttpwFetchStats()
{
    if (!defined('CUTTPW_API_URL') || !defined('CUTTPW_API_TOKEN')) {
        return array(false, 'Chưa cấu hình CUTTPW_API_URL / CUTTPW_API_TOKEN', array());
    }

    $separator = (strpos(CUTTPW_API_URL, '?') === false) ? '?' : '&';
    $url = CUTTPW_API_URL . $separator . 'token=' . urlencode(CUTTPW_API_TOKEN);

    $body = false;

    if (function_exists('curl_init')) {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $body = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($body === false) {
            return array(false, 'Không gọi được API Cutt.pw: ' . $error, array());
        }

        if ($httpCode >= 400) {
            return array(false, 'API Cutt.pw trả HTTP ' . $httpCode, array());
        }
    } else {
        $context = stream_context_create(array(
            'http' => array(
                'timeout' => 10
            )
        ));

        $body = @file_get_contents($url, false, $context);

        if ($body === false) {
            return array(false, 'Server chưa bật curl và file_get_contents không gọi được API', array());
        }
    }

    $data = json_decode($body, true);

    if (!is_array($data)) {
        return array(false, 'API trả dữ liệu không phải JSON hợp lệ', array());
    }

    if (empty($data['success'])) {
        return array(false, 'Dữ liệu API không hợp lệ hoặc token sai', array());
    }

    return array(true, '', $data);
}

function mosCuttpwDashboard()
{
    global $template;

    list($ok, $message, $data) = cuttpwFetchStats();

    $stats = array();

    if (isset($data['stats']) && is_array($data['stats'])) {
        $stats = $data['stats'];
    }

    $totalUsers = isset($stats['total_users']) ? $stats['total_users'] : 0;
    $activeUsers = isset($stats['active_users']) ? $stats['active_users'] : 0;
    $deactiveUsers = isset($stats['deactive_users']) ? $stats['deactive_users'] : 0;
    $totalLinks = isset($stats['total_links']) ? $stats['total_links'] : 0;
    $totalClicks = isset($stats['total_clicks']) ? $stats['total_clicks'] : 0;
    $generatedAt = isset($data['generated_at']) ? $data['generated_at'] : '';

    $template->assign_vars(array(
        'STATUS_CLASS' => $ok ? 'ok' : 'error',
        'STATUS_TEXT' => $ok ? 'Đã kết nối API Cutt.pw' : $message,

        'TOTAL_USERS' => number_format((int) $totalUsers),
        'ACTIVE_USERS' => number_format((int) $activeUsers),
        'DEACTIVE_USERS' => number_format((int) $deactiveUsers),
        'TOTAL_LINKS' => number_format((int) $totalLinks),
        'TOTAL_CLICKS' => number_format((int) $totalClicks),

        'GENERATED_AT' => htmlspecialchars((string) $generatedAt, ENT_QUOTES, 'UTF-8')
    ));

    $template->set_filenames_new(array(
        'cuttpw' => 'cuttpw/cuttpw_dashboard.tpl'
    ));

    $template->pparse('cuttpw');
}