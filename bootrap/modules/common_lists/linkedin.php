<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/linkedin',
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
		
		$sql = "SELECT tbl_linkedins.*, tbl_tels.tel_name, tbl_emails.email_name FROM (tbl_linkedins LEFT JOIN tbl_tels ON tbl_linkedins.tel_id = tbl_tels.`tel_id`) LEFT JOIN tbl_emails ON tbl_linkedins.email_id = tbl_emails.`email_id` ORDER BY tbl_linkedins.priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'	=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'		=>  $order,
				'linkedin_id'	 =>	$row['linkedin_id'],
				'linkedin_name'   =>	$row['linkedin_name'],
				'pass'   		 =>	$row['pass'],
				'ghichu'   	   =>	$row['ghichu'],
				'tel_name'	 =>	$row['tel_name'],
				'email_name'   =>	$row['email_name'],
				'ghichu'   	   =>	$row['ghichu'],
				'active' 	   =>	($row['active'] == 1) ? '' : 'none',
				'up'		   =>	($order == 1) ? ' display: none;' : '',
				'down'		 =>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'common_lists/linkedin/linkedin_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$linkedin_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($linkedin_id != 0)
		{	$sql = "select * from tbl_linkedins where linkedin_id = $linkedin_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'linkedin_id'	   =>	$linkedin_id,
					'linkedin_name'	 =>	$row['linkedin_name'],
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
			'share' => 'common_lists/linkedin/linkedin_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$linkedin_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($linkedin_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $linkedin_id, $direction, "tbl_linkedins", "linkedin_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$linkedin_id 	   = mosGetParam( $_REQUEST, 'id', '0');
		$linkedin_name	 = mosGetParam( $_REQUEST, 'linkedin_name', '');
		$pass		  = mosGetParam( $_REQUEST, 'pass', '');
		$ghichu		= mosGetParam( $_REQUEST, 'ghichu', '');
		$tel_id		= mosGetParam( $_REQUEST, 'tel_id', '');
		$email_id	  = mosGetParam( $_REQUEST, 'email_id', '');
		$active		= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($linkedin_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($linkedin_id == '0')
		{	
			if (checkDuplicate("tbl_linkedins", array('linkedin_name' => $linkedin_name), "linkedin_name",0,false,""))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_linkedins", "priority", "");
			$sql = "insert into tbl_linkedins (linkedin_name, pass, ghichu, active, priority, language_id, tel_id, email_id) values ('$linkedin_name', '$pass', '$ghichu', $active, $priority, $languageid, $tel_id, '$email_id')";	
		} else
			{ 
			if (checkDuplicate("tbl_linkedins", array('linkedin_name' => $linkedin_name), "linkedin_name",0,false,"linkedin_id != $linkedin_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_linkedins set linkedin_name ='$linkedin_name', pass = '$pass', ghichu = '$ghichu', active = $active, tel_id = '$tel_id', email_id = '$email_id' where linkedin_id = $linkedin_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$linkedin_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($linkedin_id == 0)
		{	mosInvalidURL();
			exit;
		}	
  if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_linkedins", "linkedin_id", $linkedin_id);
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
			'linkedin_name' 	 =>	mosGetParam( $_REQUEST, 'linkedin_name', ''),
			'pass' 		   =>	mosGetParam( $_REQUEST, 'pass', ''),	
			'ghichu' 		 =>	mosGetParam( $_REQUEST, 'ghichu', ''),		
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'linkedin_id'	   =>	$id,
		));
		$template->set_filenames_new(array(
			'linkedin' => 'common_lists/linkedin/linkedin_info.tpl')
		);
		
		$template->pparse('linkedin');	
	}
?>