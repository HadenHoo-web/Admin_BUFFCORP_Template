<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/keytraffic',
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
		
		$sql = "SELECT tbl_keytraffics.*, tbl_tels.tel_name FROM tbl_keytraffics LEFT JOIN tbl_tels ON tbl_keytraffics.tel_id = tbl_tels.`tel_id` ORDER BY tbl_keytraffics.priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'	=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'		=>  $order,
				'keytraffic_id'	 =>	$row['keytraffic_id'],
				'keytraffic_name'   =>	$row['keytraffic_name'],
				'pass'   		 =>	$row['pass'],
				'ghichu'   	   =>	$row['ghichu'],
				'tel_name'	 =>	$row['tel_name'],
				'active' 	   =>	($row['active'] == 1) ? '' : 'none',
				'up'		   =>	($order == 1) ? ' display: none;' : '',
				'down'		 =>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'common_lists/keytraffic/keytraffic_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$keytraffic_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($keytraffic_id != 0)
		{	$sql = "select * from tbl_keytraffics where keytraffic_id = $keytraffic_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'keytraffic_id'	=>	$keytraffic_id,
					'keytraffic_name'	=>	$row['keytraffic_name'],
					'pass'		  =>	$row['pass'],
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
			'share' => 'common_lists/keytraffic/keytraffic_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$keytraffic_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($keytraffic_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $keytraffic_id, $direction, "tbl_keytraffics", "keytraffic_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$keytraffic_id 	  = mosGetParam( $_REQUEST, 'id', '0');
		$keytraffic_name	= mosGetParam( $_REQUEST, 'keytraffic_name', '');
		$pass		  = mosGetParam( $_REQUEST, 'pass', '');
		$ghichu		= mosGetParam( $_REQUEST, 'ghichu', '');
		$tel_id		= mosGetParam( $_REQUEST, 'tel_id', '');
		$active		= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($keytraffic_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($keytraffic_id == '0')
		{	
			if (checkDuplicate("tbl_keytraffics", array('keytraffic_name' => $keytraffic_name), "keytraffic_name",0,false,""))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_keytraffics", "priority", "");
			$sql = "insert into tbl_keytraffics (keytraffic_name, pass, ghichu, active, priority, language_id, tel_id) values ('$keytraffic_name', '$pass', '$ghichu', $active, $priority, $languageid, $tel_id)";	
		} else
			{ 
			if (checkDuplicate("tbl_keytraffics", array('keytraffic_name' => $keytraffic_name), "keytraffic_name",0,false,"keytraffic_id != $keytraffic_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_keytraffics set keytraffic_name ='$keytraffic_name', pass = '$pass', ghichu = '$ghichu', active = $active, tel_id = '$tel_id' where keytraffic_id = $keytraffic_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$keytraffic_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($keytraffic_id == 0)
		{	mosInvalidURL();
			exit;
		}	
  if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_keytraffics", "keytraffic_id", $keytraffic_id);
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
			'keytraffic_name' 	 =>	mosGetParam( $_REQUEST, 'keytraffic_name', ''),
			'pass' 		   =>	mosGetParam( $_REQUEST, 'pass', ''),	
			'ghichu' 		 =>	mosGetParam( $_REQUEST, 'ghichu', ''),		
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'keytraffic_id'	   =>	$id,
		));
		$template->set_filenames_new(array(
			'keytraffic' => 'common_lists/keytraffic/keytraffic_info.tpl')
		);
		
		$template->pparse('keytraffic');	
	}
?>