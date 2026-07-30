<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/fan',
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
		
		$sql = "SELECT tbl_fans.*, tbl_tels.tel_name FROM tbl_fans LEFT JOIN tbl_tels ON tbl_fans.tel_id = tbl_tels.`tel_id` ORDER BY tbl_fans.like_num DESC, tbl_fans.priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'	=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'		=>  $order,
				'fan_id'	   =>	$row['fan_id'],
				'fan_name'     =>	$row['fan_name'],
				'pass'   		 =>	$row['pass'],
				'like_num'   	 =>	$row['like_num'],
				'ghichu'   	   =>	$row['ghichu'],
				'tel_name'	 =>	$row['tel_name'],
				'active' 	   =>	($row['active'] == 1) ? '' : 'none',
				'up'		   =>	($order == 1) ? ' display: none;' : '',
				'down'		 =>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'common_lists/fan/fan_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$fan_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($fan_id != 0)
		{	$sql = "select * from tbl_fans where fan_id = $fan_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'fan_id'		=>	$fan_id,
					'fan_name'	  =>	$row['fan_name'],
					'pass'		  =>	$row['pass'],
					'like_num'	  =>	$row['like_num'],
					'ghichu'		=>	$row['ghichu'],
					'tel_id'		=>	($row['tel_id'])?$row['tel_id']:0,
					'active'	    =>	($row['active'] == 1) ? 'checked' : '',
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'active'	=> 'checked' ,
				'allow'	 => 'hidden',
				'tel_id'	=> '0',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'common_lists/fan/fan_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$fan_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($fan_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $fan_id, $direction, "tbl_fans", "fan_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$fan_id 	  = mosGetParam( $_REQUEST, 'id', '0');
		$fan_name	= mosGetParam( $_REQUEST, 'fan_name', '');
		$pass		  = mosGetParam( $_REQUEST, 'pass', '');
		$like_num	  = mosGetParam( $_REQUEST, 'like_num', '');
		$ghichu		= mosGetParam( $_REQUEST, 'ghichu', '');
		$tel_id		= mosGetParam( $_REQUEST, 'tel_id', '');
		$active		= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($fan_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($fan_id == '0')
		{	
			if (checkDuplicate("tbl_fans", array('fan_name' => $fan_name), "fan_name",0,false,""))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_fans", "priority", "");
			$sql = "insert into tbl_fans (fan_name, pass, like_num, ghichu, active, priority, language_id, tel_id) values ('$fan_name', '$pass', '$like_num', '$ghichu', $active, $priority, $languageid, $tel_id)";	
		} else
			{ 
			if (checkDuplicate("tbl_fans", array('fan_name' => $fan_name), "fan_name",0,false,"fan_id != $fan_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_fans set fan_name ='$fan_name', pass = '$pass', like_num = '$like_num', ghichu = '$ghichu', active = $active, tel_id = '$tel_id' where fan_id = $fan_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$fan_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($fan_id == 0)
		{	mosInvalidURL();
			exit;
		}	
    if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_fans", "fan_id", $fan_id);
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
			'fan_name' 	   =>	mosGetParam( $_REQUEST, 'fan_name', ''),
			'pass' 		   =>	mosGetParam( $_REQUEST, 'pass', ''),	
			'like_num' 	   =>	mosGetParam( $_REQUEST, 'like_num', ''),
			'ghichu' 		 =>	mosGetParam( $_REQUEST, 'ghichu', ''),		
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'fan_id'	     =>	$id,
		));
		$template->set_filenames_new(array(
			'fan' => 'common_lists/fan/fan_info.tpl')
		);
		
		$template->pparse('fan');	
	}
?>