<?php
	define('_CMS_', true);
	global $menu_ID, $root_path, $admintool_path, $skin;
	$root_path 	= '../'; 
	$admintool_path = '';
	
	if (!(isset($_REQUEST['option'])))
		$mainTemplate = 'index.php';
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