<?php
	define('_CMS_', true);
	global $menu_ID, $root_path, $admintool_path, $skin;
	$root_path 	= '../'; 
	$admintool_path = '';
	
	if (!(isset($_REQUEST['option'])))
	{
		session_name('admintool');
		session_start();
		$isAdminDashboardUser = (
			(isset($_SESSION["loginname"]) && strtolower($_SESSION["loginname"]) == 'administrator')
			|| (isset($_SESSION["membername"]) && strtolower($_SESSION["membername"]) == 'administrator')
			|| (isset($_SESSION["login_id"]) && (int)$_SESSION["login_id"] == 71)
		);
		session_write_close();
		$mainTemplate = $isAdminDashboardUser ? 'modules/common_lists/admin_dashboard.php' : 'modules/common_lists/giaoviec.php';
		if (!$isAdminDashboardUser) $_REQUEST['mode'] = 'list';
	}
	else
	{	if (!file_exists('modules/'.$_REQUEST['option'].'.php'))
		{	include('common.php');
			mosInvalidURL("arr404.tpl");
			exit;
		}
		else
		{	
			$mainTemplate = 'modules/'.$_REQUEST['option'].'.php';
		}
	}
	include('mainpage.php');
?>
