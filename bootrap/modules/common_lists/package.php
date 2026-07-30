<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/package',
		'LANGUAGEID'=> $languageid,
	));		
	switch( $action )
	{	
		case 'list'			:	mosList(); break;
		case 'info'			:	mosInfo(); break;
		case 'up'			:  	mosMove('up'); break;
		case 'down' 		:  	mosMove('down'); break;
		case 'save'			:	mosSave(); break;
		case 'delete'		:	mosDelete(); break;
	
		default:
			mosInvalidURL();
			exit;
	}
function mosList()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		
		$sql = "select * from tbl_packages where language_id = $languageid order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'package_id'			=>	$row['package_id'],
				'package_name'			=>	$row['package_name'],
				'price'			=>	$row['price'],
				'ghichu'			=>	$row['ghichu'],
				'active' 		=>	($row['active'] == 1) ? '' : 'none',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'common_lists/package/package_list.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$package_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($package_id != 0)
		{	$sql = "select * from tbl_packages where package_id = $package_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'package_id'	=>	$package_id,
					'package_name'	=>	$row['package_name'],
					'price'			=>	$row['price'],
					'ghichu'	=>	$row['ghichu'],
					'active'	=>	($row['active'] == 1) ? 'checked' : '',
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'active'		=>	'checked' ,
				'allow'		=> 'hidden',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'common_lists/package/package_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$package_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($package_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $package_id, $direction, "tbl_packages", "package_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$package_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$package_name	= mosGetParam( $_REQUEST, 'package_name', '');
		$price			= mosGetParam( $_REQUEST, 'price', '0');
		$ghichu	= mosGetParam( $_REQUEST, 'ghichu', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($package_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($package_id == '0')
		{	
			if (checkDuplicate("tbl_packages", array('package_name' => $package_name), "package_name",0,false,""))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_packages", "priority", "");
			$sql = "insert into tbl_packages (package_name, price, ghichu, active, priority, language_id) values ('$package_name', '$price', '$ghichu', $active, $priority, $languageid)";
		} else
			{ 
			if (checkDuplicate("tbl_packages", array('package_name' => $package_name), "package_name",0,false,"package_id != $package_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_packages set package_name ='$package_name', price = '$price', ghichu = '$ghichu', active = $active where package_id = $package_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$package_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($package_id == 0)
		{	mosInvalidURL();
			exit;
		}	
    if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_packages", "package_id", $package_id);
		}else
			{
				$template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
			}
		mosList();
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;				
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$template->assign_vars(array(
			'package_name' 	=>	mosGetParam( $_REQUEST, 'package_name', ''),
			'price'		=>	mosGetParam( $_REQUEST, 'price', '0'),
			'ghichu' 	=>	mosGetParam( $_REQUEST, 'ghichu', ''),			
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'package_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'package' => 'common_lists/package/package_info.html')
		);
		
		$template->pparse('package');
	}
?>