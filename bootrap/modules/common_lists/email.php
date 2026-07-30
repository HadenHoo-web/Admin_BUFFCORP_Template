<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/email',
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
function mosList(){	
	global $db, $root_path, $skin, $languageid, $template;
	$member_id	= mosGetParam( $_REQUEST, 'member_id1', $_SESSION["login_id"] );
	$cond = '';
	$cond = (strtolower($_SESSION['membername'])=="administrator")?' and active = 1':' and active = 1'; 
    $sql = "select * from tbl_member where 1 $cond order by member_id";
    if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
    while( $row = $db->sql_fetchrow($result) ){					
		$template->assign_block_vars('member_list', array(
			'member_id'	  =>	$row['member_id'],
			'member_name' =>	$row['fullname'],
		));	
    }
	$cond = "";
	$cond .= ($member_id)?' and tbl_emails.member_id = '.$member_id:'';
	$sql = "SELECT tbl_emails.*, tbl_tels.tel_name, tbl_member.fullname FROM (tbl_emails LEFT JOIN tbl_tels ON tbl_emails.tel_id = tbl_tels.`tel_id`) left join tbl_member on tbl_emails.member_id = tbl_member.member_id WHERE 1 $cond ORDER BY tbl_emails.email_id DESC";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order = 0;
	while( $row = $db->sql_fetchrow($result) ){
		$order = $order + 1;					
		$sql1 = "SELECT count(*) as num_map from tbl_maps where email_id = ".$row['email_id'];
		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		if( $row1 = $db->sql_fetchrow($result1) )
		$template->assign_block_vars('list', array(
			'className'	=>  ($order % 2 == 1) ? 'alt' : 'inv',
			'order'		=>  $order,
			'email_id'	 =>	$row['email_id'],
			'email_name'   =>	$row['email_name'],
			'pass'   		 =>	$row['pass'],
			'pass_smtp'   	=>	$row['pass_smtp'],
			'ghichu'   	   =>	$row['ghichu'],
			'tel_name'	 =>	$row['tel_name'],
			'fullname'	  =>	$row['fullname'],
			'active' 	   =>	($row['active'] == 1) ? '' : 'none',
			'up'		   =>	($order == 1) ? ' display: none;' : '',
			'down'		 =>	($order == $num_row) ? ' display: none;' : '',		
		));	
	}
	$template->assign_vars(array(
		'member_id'  => $member_id,
		'allow_member'	=>	(strtolower($_SESSION['membername'])=="administrator" || $_SESSION["login_id"] == 38)?'':'none'
	));
	$template->set_filenames_new(array(
		'share' => 'common_lists/email/email_list.html')
	);
	$template->pparse('share');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$email_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($email_id != 0)
		{	$sql = "select * from tbl_emails where email_id = $email_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'email_id'	  =>	$email_id,
					'email_name'	=>	$row['email_name'],
					'pass'		  =>	$row['pass'],
					'pass_smtp'	 =>	$row['pass_smtp'],
					'ghichu'		=>	$row['ghichu'],
					'tel_id'		=>	($row['tel_id'])?$row['tel_id']:0,
					'member_id'	  =>	($row['member_id'])?$row['member_id']:0,
					'active'	    =>	($row['active'] == 1) ? 'checked' : '',
					'created_date'   => $row['created_date'],
					'created_by'	 => $row['created_by'],
					'last_modified'  => $row['last_modified'],
					'modified_by'	=> $row['modified_by'],
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'active'	=> 'checked' ,
				'allow'	 => 'hidden',
				'tel_id'	=> '0',
				'member_id'	=> '0',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'common_lists/email/email_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$email_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($email_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $email_id, $direction, "tbl_emails", "email_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$email_id 	  = mosGetParam( $_REQUEST, 'id', '0');
		$email_name	= mosGetParam( $_REQUEST, 'email_name', '');
		$pass		  = mosGetParam( $_REQUEST, 'pass', '');
		$pass_smtp	 = mosGetParam( $_REQUEST, 'pass_smtp', '');
		$ghichu		= mosGetParam( $_REQUEST, 'ghichu', '');
		$tel_id		= mosGetParam( $_REQUEST, 'tel_id', '');
		$member_id		= mosGetParam( $_REQUEST, 'member_id', '');
		$active		= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($email_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($email_id == '0')
		{	
			if (checkDuplicate("tbl_emails", array('email_name' => $email_name), "email_name",0,false,""))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_emails", "priority", "");
			$sql = "insert into tbl_emails (email_name, pass, pass_smtp, ghichu, active, priority, language_id, tel_id, created_date, last_modified, created_by, modified_by, member_id) values ('$email_name', '$pass', '$pass_smtp', '$ghichu', $active, $priority, $languageid, $tel_id, now(), now(), '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "', '$member_id')";	
		}else{ 
			if (checkDuplicate("tbl_emails", array('email_name' => $email_name), "email_name",0,false,"email_id != $email_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_emails set email_name ='$email_name', pass = '$pass', pass_smtp = '$pass_smtp', ghichu = '$ghichu', active = $active, tel_id = '$tel_id', last_modified = now(), modified_by = '".$_SESSION['membername']."', member_id = '$member_id' where email_id = $email_id";
		}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$email_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($email_id == 0)
		{	mosInvalidURL();
			exit;
		}
    
    if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_emails", "email_id", $email_id);
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
			'email_name' 	 =>	mosGetParam( $_REQUEST, 'email_name', ''),
			'pass' 		   =>	mosGetParam( $_REQUEST, 'pass', ''),	
			'pass_smtp' 	  =>	mosGetParam( $_REQUEST, 'pass_smtp', ''),
			'ghichu' 		 =>	mosGetParam( $_REQUEST, 'ghichu', ''),		
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'email_id'	   =>	$id,
		));
		$template->set_filenames_new(array(
			'email' => 'common_lists/email/email_info.html')
		);
		
		$template->pparse('email');	
	}
?>