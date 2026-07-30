<?php
	$debug_mode		= false;
	
	$product_name	= "HK CMS";
	$version		= "Version 1.0";
	$website		= "https://bopda.net/";
	$developedBy	= "HK Viet Nam";
	$webTitle		= "";
	$webmasterEmail	= "webmaster@hkvietnam.com";
	$copyRights		= "";
	$developed		= "Developed by HK Viet Nam";
	
	$dbms			= 'mysql';
	$dbhost			= 'localhost';
	$dbname			= 'adbuff_1';
	$dbuser			= 'adbuff_1';
	$dbpasswd 		= 'dQ8nrOPmfh';
	
	$topTemplateName	= "default.tpl";
	$mainTemplateName	= "default.tpl";
	$skin				= "default";
	$theme				= "default.css";

	define('RECAPTCHA_SITE_KEY','6LemKWUsAAAAAIRGJfIe4he6HS9QOO4efHJBMoTv');
	define('RECAPTCHA_SECRET_KEY','6LemKWUsAAAAAJNkcIUyS5mQvF-sTeN9XXkLuir0');
	define('CUTTPW_API_URL', 'https://cutt.pw/api/admin-stats');
	define('CUTTPW_API_TOKEN', '3a48bc1c0a86d2fce2b8ecc7b6186ceff0d90ef8f9663135ec07f81fc2ed8989');

	define('GETPASS_API_URL', 'https://getpass.top/api/adbuffseo-category-web.php');
	define('GETPASS_API_TOKEN', 'bfd8d62e9e1df39307cf8a93ba0fc0eb8827af954ace3ff77ceea690cb1b1838');

	define('GETPASS_EXACT_API_URL', 'https://getpass.top/api/adbuffseo-category-web-exact.php');
	define('GETPASS_EXACT_API_TOKEN', 'bfd8d62e9e1df39307cf8a93ba0fc0eb8827af954ace3ff77ceea690cb1b1838');

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
