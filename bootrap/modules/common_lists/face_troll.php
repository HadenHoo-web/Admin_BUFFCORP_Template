<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/face_troll',
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
		
		$sql = "SELECT tbl_face_trolls.*, tbl_tels.tel_name, tbl_emails.email_name FROM (tbl_face_trolls LEFT JOIN tbl_tels ON tbl_face_trolls.tel_id = tbl_tels.`tel_id`) LEFT JOIN tbl_emails ON tbl_face_trolls.email_id = tbl_emails.`email_id` ORDER BY tbl_face_trolls.priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'	=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'		=>  $order,
				'face_troll_id'	 =>	$row['face_troll_id'],
				'face_troll_name'   =>	$row['face_troll_name'],
				'pass'   		 =>	$row['pass'],
				'ghichu'   	   =>	$row['ghichu'],
				'tel_name'	 =>	$row['tel_name'],
				'email_name'   =>	$row['email_name'],
				'active' 	   =>	($row['active'] == 1) ? '' : 'none',
				'up'		   =>	($order == 1) ? ' display: none;' : '',
				'down'		 =>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'common_lists/face_troll/face_troll_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$face_troll_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($face_troll_id != 0)
		{	$sql = "select * from tbl_face_trolls where face_troll_id = $face_troll_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'face_troll_id'	   =>	$face_troll_id,
					'face_troll_name'	 =>	$row['face_troll_name'],
					'pass'		  =>	$row['pass'],
					'ghichu'		=>	$row['ghichu'],
					'tel_id'		=>	($row['tel_id'])?$row['tel_id']:0,
					'email_id'	  =>	($row['email_id'])?$row['email_id']:0,
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
				'email_id'  => '0',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'common_lists/face_troll/face_troll_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$face_troll_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($face_troll_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $face_troll_id, $direction, "tbl_face_trolls", "face_troll_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$face_troll_id 	   = mosGetParam( $_REQUEST, 'id', '0');
		$face_troll_name	 = mosGetParam( $_REQUEST, 'face_troll_name', '');
		$pass		  = mosGetParam( $_REQUEST, 'pass', '');
		$ghichu		= mosGetParam( $_REQUEST, 'ghichu', '');
		$tel_id		= mosGetParam( $_REQUEST, 'tel_id', '');
		$email_id	  = mosGetParam( $_REQUEST, 'email_id', '');
		$active		= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($face_troll_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($face_troll_id == '0')
		{	
			if (checkDuplicate("tbl_face_trolls", array('face_troll_name' => $face_troll_name), "face_troll_name",0,false,""))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_face_trolls", "priority", "");
			$sql = "insert into tbl_face_trolls (face_troll_name, pass, ghichu, active, priority, language_id, tel_id, email_id) values ('$face_troll_name', '$pass', '$ghichu', $active, $priority, $languageid, $tel_id, '$email_id')";	
		} else
			{ 
			if (checkDuplicate("tbl_face_trolls", array('face_troll_name' => $face_troll_name), "face_troll_name",0,false,"face_troll_id != $face_troll_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_face_trolls set face_troll_name ='$face_troll_name', pass = '$pass', ghichu = '$ghichu', active = $active, tel_id = '$tel_id', email_id = '$email_id' where face_troll_id = $face_troll_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$face_troll_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($face_troll_id == 0)
		{	mosInvalidURL();
			exit;
		}	
  if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_face_trolls", "face_troll_id", $face_troll_id);
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
			'face_troll_name' 	 =>	mosGetParam( $_REQUEST, 'face_troll_name', ''),
			'pass' 		   =>	mosGetParam( $_REQUEST, 'pass', ''),	
			'ghichu' 		 =>	mosGetParam( $_REQUEST, 'ghichu', ''),		
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'face_troll_id'	   =>	$id,
		));
		$template->set_filenames_new(array(
			'face_troll' => 'common_lists/face_troll/face_troll_info.tpl')
		);
		
		$template->pparse('face_troll');	
	}
?>