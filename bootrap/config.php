<?php
	function cms_env($name, $default = '')
	{
		$value = getenv($name);
		return ($value === false || $value === '') ? $default : $value;
	}

	$cmsLocalConfig = array();
	$cmsLocalConfigFile = __DIR__ . '/config.local.php';
	if (file_exists($cmsLocalConfigFile)) {
		$loadedLocalConfig = include $cmsLocalConfigFile;
		if (is_array($loadedLocalConfig)) {
			$cmsLocalConfig = $loadedLocalConfig;
		}
	}

	function cms_config($key, $default = '')
	{
		global $cmsLocalConfig;
		$value = getenv($key);
		if ($value !== false && $value !== '') {
			return $value;
		}
		return array_key_exists($key, $cmsLocalConfig) ? $cmsLocalConfig[$key] : $default;
	}

	$debug_mode		= false;
	
	$product_name	= "HK CMS";
	$version		= "Version 1.0";
	$website		= "https://bopda.net/";
	$developedBy	= "HK Viet Nam";
	$webTitle		= "";
	$webmasterEmail	= "webmaster@hkvietnam.com";
	$copyRights		= "";
	$developed		= "Developed by HK Viet Nam";
	
	$dbms			= cms_config('DB_DRIVER', 'mysql');
	$dbhost			= cms_config('DB_HOST', 'localhost');
	$dbname			= cms_config('DB_NAME', 'admin_buffcorp');
	$dbuser			= cms_config('DB_USER', 'root');
	$dbpasswd 		= cms_config('DB_PASSWORD', '');
	
	$topTemplateName	= "default.tpl";
	$mainTemplateName	= "default.tpl";
	$skin				= "default";
	$theme				= "default.css";

	define('RECAPTCHA_SITE_KEY', cms_config('RECAPTCHA_SITE_KEY', ''));
	define('RECAPTCHA_SECRET_KEY', cms_config('RECAPTCHA_SECRET_KEY', ''));
	define('CUTTPW_API_URL', cms_config('CUTTPW_API_URL', 'https://cutt.pw/api/admin-stats'));
	define('CUTTPW_API_TOKEN', cms_config('CUTTPW_API_TOKEN', ''));

	define('GETPASS_API_URL', cms_config('GETPASS_API_URL', 'https://getpass.top/api/adbuffseo-category-web.php'));
	define('GETPASS_API_TOKEN', cms_config('GETPASS_API_TOKEN', ''));

	define('GETPASS_EXACT_API_URL', cms_config('GETPASS_EXACT_API_URL', 'https://getpass.top/api/adbuffseo-category-web-exact.php'));
	define('GETPASS_EXACT_API_TOKEN', cms_config('GETPASS_EXACT_API_TOKEN', ''));

	$legacyMessageConstants = array(
		'CANT_NOT_DELETE',
		'COPY_SUCCESS',
		'CRITICAL_ERROR',
		'CRITICAL_MESSAGE',
		'DATABASE_BUSY',
		'DATABASE_CONNECT_ERROR',
		'DELETED_SUCCESS',
		'DELETE_ERROR',
		'DELETE_SUCCESS',
		'DUPLICATE_ENTRY',
		'DUPLICATE_ENTRY_WEBSITE_NAME',
		'DUPLICATE_TEL',
		'EMPTY_CATEGORY',
		'EMPTY_LIST',
		'GENERAL_ERROR',
		'GENERAL_MESSAGE',
		'ID_NOTFOUND',
		'INVALID_PARAMETER',
		'LOGIN_INVALID',
		'MOVE_SUCCESS',
		'NONE_EMPTY_DELETED',
		'NONE_EMPTY_ERROR',
		'NOT_DELETE_DEFAULT',
		'NOT_EXISTS_CATEGORY',
		'OLD_PASSWORD_ERROR',
		'PASSWORD_SAVED_SUCCESS',
		'PERMISSION_SAVED_SUCCESS',
		'PROCESS_SUCCESS',
		'ROLLBACK_FAILED',
		'ROLLBACK_SUCCESS',
		'SAVE_FAILED',
		'SAVE_SUCCESS',
		'SEND_SUCCESS',
		'SERVER_BUSY',
		'SUBMIT_FAILED',
		'SUBMIT_SUCCESS',
		'UPDATE_SUCCESS',
		'UPLOAD_ERROR',
		'WRITE_TO_FILE_ERROR',
	);
	foreach ($legacyMessageConstants as $legacyMessageConstant) {
		if (!defined($legacyMessageConstant)) {
			define($legacyMessageConstant, $legacyMessageConstant);
		}
	}
	if (!defined('_DATE_FORMAT_LC')) {
		define('_DATE_FORMAT_LC', '%Y-%m-%d');
	}
	?>
