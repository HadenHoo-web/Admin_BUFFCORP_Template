<?php
// common.php không thể chạy độc lập, nó chỉ được include từ các tập tin trong dự án

// ✅ Fix: Chặn web gọi trực tiếp, nhưng cho phép cron/CLI chạy
if (!defined('_CMS_')) {
    if (PHP_SAPI === 'cli') {
        define('_CMS_', true);
    } else {
        die('HACKING_ATTEMPT');
    }
}

// Không báo lỗi đối với trường hợp Biến chưa khởi động
error_reporting(E_ERROR | E_WARNING | E_PARSE);

function cms_log_runtime_error($type, $message, $file = '', $line = '')
{
    $logDir = __DIR__ . '/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0775, true);
    }
    $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $remoteAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    $entry = '[' . date('Y-m-d H:i:s') . ']'
        . ' type=' . $type
        . ' ip=' . $remoteAddr
        . ' uri=' . $requestUri
        . ' file=' . $file
        . ' line=' . $line
        . ' message=' . str_replace(array("\r", "\n"), ' ', $message)
        . PHP_EOL;
    @file_put_contents($logDir . '/php_errors.log', $entry, FILE_APPEND);
}

ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    cms_log_runtime_error($severity, $message, $file, $line);
    return true;
});

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
        cms_log_runtime_error($error['type'], $error['message'], $error['file'], $error['line']);
    }
});

// ✅ Fix: PHP 7+ remove set_magic_quotes_runtime()
if (function_exists('set_magic_quotes_runtime')) {
    @set_magic_quotes_runtime(0);
}

// ✅ Fix: PHP 7+ remove get_magic_quotes_gpc()
$mq = false;
if (function_exists('get_magic_quotes_gpc')) {
    $mq = get_magic_quotes_gpc();
}

// Nếu magic_quotes_gpc off thì addslashes
if (!$mq) {
    // PHP 5.x legacy vars
    if (isset($HTTP_GET_VARS))   addslashedToArray($HTTP_GET_VARS);
    if (isset($HTTP_POST_VARS))  addslashedToArray($HTTP_POST_VARS);
    if (isset($HTTP_COOKIE_VARS))addslashedToArray($HTTP_COOKIE_VARS);

    // PHP hiện đại
    if (isset($_GET))    addslashedToArray($_GET);
    if (isset($_POST))   addslashedToArray($_POST);
    if (isset($_COOKIE)) addslashedToArray($_COOKIE);
}

// Include các thư viện hàm cơ sở vào hệ thống
include('config.php');
include('includes/template.php');
include('includes/db.php');
include('includes/library.php');

// Lấy địa chỉ IP của người dùng
if (getenv('HTTP_X_FORWARDED_FOR') != '') {
    $client_ip = (!empty($HTTP_SERVER_VARS['REMOTE_ADDR']))
        ? $HTTP_SERVER_VARS['REMOTE_ADDR']
        : ((!empty($HTTP_ENV_VARS['REMOTE_ADDR']))
            ? $HTTP_ENV_VARS['REMOTE_ADDR']
            : (isset($REMOTE_ADDR) ? $REMOTE_ADDR : ''));

    if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", getenv('HTTP_X_FORWARDED_FOR'), $ip_list)) {
        $private_ip = array(
            '/^0\./',
            '/^127\.0\.0\.1/',
            '/^192\.168\..*/',
            '/^172\.16\..*/',
            '/^10..*/',
            '/^224..*/',
            '/^240..*/'
        );
        $client_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
    }
} else {
    $client_ip = (!empty($HTTP_SERVER_VARS['REMOTE_ADDR']))
        ? $HTTP_SERVER_VARS['REMOTE_ADDR']
        : ((!empty($HTTP_ENV_VARS['REMOTE_ADDR']))
            ? $HTTP_ENV_VARS['REMOTE_ADDR']
            : (isset($REMOTE_ADDR) ? $REMOTE_ADDR : ''));
}

$user_ip = encode_ip($client_ip);
$demsao  = $client_ip . " " . date('mdHis');


// -----------------------------------------------------------------------------
// Dùng hàm addslashes vào các biến nếu magic_quotes_gpc ở trạng thái off
// Điều này có ý nghĩa về việc bảo mật, nó ngăn chặn người dùng ngắt câu lệnh SQL ra để chèn các
// đoạn lệnh SQL không hợp lệ vào
function addslashedToArray(&$Arr)
{
    if (is_array($Arr)) {
        foreach ($Arr as $k => $v) {
            if (is_array($v)) {
                addslashedToArray($Arr[$k]);
            } else {
                $Arr[$k] = addslashes($v);
            }
        }
    }
}

/*
function encode_ip($dotquad_ip)
{	$ip_sep = explode('.', $dotquad_ip);
	return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
}

function decode_ip($int_ip)
{	$hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
	return hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
}

define( "_MOS_NOTRIM", 0x0001 );
define( "_MOS_ALLOWHTML", 0x0002 );
function mosGetParam( &$arr, $name, $def=null, $mask=0 ) {
	$return = null;
	if (isset($arr[$name]) && (trim($arr[$name]) != '')) {
		if (is_string( $arr[$name] )) {
			if (!($mask&_MOS_NOTRIM)) {
				$arr[$name] = trim( $arr[$name] );
			}
			if (!($mask&_MOS_ALLOWHTML)) {
				$arr[$name] = strip_tags( $arr[$name] );
			}
			if (!get_magic_quotes_gpc()) {
				$arr[$name] = addslashes( $arr[$name] );
			}
		}
		return $arr[$name];
	} else {
		return $def;
	}
}
*/

function doModules($moduleName, $moduleTemplate = "default.tpl")
{
    global $langPath, $topTemplateName, $template, $skin;
    if (!isset($template)) $template = new Template();
    if (!isset($langPath)) $langPath = 'english';
    $template->set_filenames(array(
        $moduleName => "templates/$skin/$langPath/$moduleName/$moduleTemplate"
    ));
    $template->assign_vars(array(
        'IMGPATH' => "images/$moduleName"
    ));
    $template->pparse($moduleName);
}
?>
