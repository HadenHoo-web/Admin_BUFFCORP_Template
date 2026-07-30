<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/face',
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
	$cond    = '';
	$cond   .= ($member_id)?' and tbl_faces.member_id = '.$member_id:'';
	$sql = "SELECT tbl_faces.*, tbl_tels.tel_name, tbl_emails.email_name, tbl_member.fullname FROM ((tbl_faces LEFT JOIN tbl_tels ON tbl_faces.tel_id = tbl_tels.`tel_id`) LEFT JOIN tbl_emails ON tbl_faces.email_id = tbl_emails.`email_id`) LEFT JOIN tbl_member ON tbl_faces.member_id = tbl_member.`member_id` where 1 $cond ORDER BY tbl_faces.face_id DESC";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order = 0;
	while( $row = $db->sql_fetchrow($result) ){	
		$order = $order + 1;					
		$template->assign_block_vars('list', array(
			'className'	=>  ($order % 2 == 1) ? 'alt' : 'inv',
			'order'		=>  $order,
			'face_id'	 =>	$row['face_id'],
			'face_name'   =>	$row['face_name'],
			'face_url'   =>	$row['face_url'],
			'pass'   		 =>	$row['pass'],
			'ghichu'   	   =>	$row['ghichu'],
			'tel_name'	 =>	$row['tel_name'],
			'email_name'   =>	$row['email_name'],
			'member_name'	=>	$row['fullname'],
        	'created_by'  => $row['created_by'],
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
		'share' => 'common_lists/face/face_list.html')
	);
	$template->pparse('share');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo(){	
	global $db, $root_path, $skin, $languageid, $template;
	$face_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
	$cond = 'and active = 1';
    $sql = "select * from tbl_member where 1 $cond";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while ( $row = $db->sql_fetchrow($result) ){
		$template->assign_block_vars('member_list', array(
			'member_id'	  =>	$row['member_id'],
			'member_name'	=>	$row['fullname'],
		));
	}
	if ($face_id != 0){	
		$sql = "select * from tbl_faces where face_id = $face_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) ){	
			$template->assign_vars(array(
				'face_id'	   =>	$face_id,
				'face_name'	 =>	$row['face_name'],
				'face_url'	 =>	$row['face_url'],
				'pass'		  =>	$row['pass'],
				'ghichu'		=>	$row['ghichu'],
				'tel_id'		=>	($row['tel_id'])?$row['tel_id']:0,
				'email_id'	  =>	($row['email_id'])?$row['email_id']:0,
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
				'email_id'  => '0',
				'member_id'  => '0',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'common_lists/face/face_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$face_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($face_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $face_id, $direction, "tbl_faces", "face_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave(){		
	global $db, $root_path, $skin, $languageid, $template;	
	$face_id 	   = mosGetParam( $_REQUEST, 'id', '0');
	$face_name	 = mosGetParam( $_REQUEST, 'face_name', '');
	$face_url	 = mosGetParam( $_REQUEST, 'face_url', '');
	$pass		  = mosGetParam( $_REQUEST, 'pass', '');
	$ghichu		= mosGetParam( $_REQUEST, 'ghichu', '');
	$tel_id		= mosGetParam( $_REQUEST, 'tel_id', '');
	$email_id	  = mosGetParam( $_REQUEST, 'email_id', '');
	$active		= mosGetParam( $_REQUEST, 'active', 0);
    $member_id			= mosGetParam( $_REQUEST, 'member_id', 0);	
	if ($face_id == ''){	
		mosInvalidURL();
		exit;
	}	
	if ($face_id == '0'){	
		if (checkDuplicate("tbl_faces", array('face_url' => $face_url), "face_url",0,false,"")){	
			reShowPage( DUPLICATE_ENTRY );
			exit;
		}
		$priority = mosGetPriority("tbl_faces", "priority", "");
		$sql = "insert into tbl_faces (face_name, face_url, pass, ghichu, active, priority, language_id, tel_id, email_id, created_date, last_modified, created_by, modified_by, member_id) values ('$face_name', '$face_url', '$pass', '$ghichu', $active, $priority, $languageid, $tel_id, '$email_id', now(), now(), '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "', '".$_SESSION["login_id"]."')";	
	}else{ 
		if (checkDuplicate("tbl_faces", array('face_url' => $face_url), "face_url",0,false,"face_id != $face_id")){	
			reShowPage( DUPLICATE_ENTRY );
			exit;
		}
		$sql = "update tbl_faces set face_name ='$face_name', face_url = '$face_url', pass = '$pass', ghichu = '$ghichu', active = $active, tel_id = '$tel_id', email_id = '$email_id', member_id = '$member_id', last_modified = now(), modified_by = '".$_SESSION['membername']."' where face_id = $face_id";
	}
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
	mosList();
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$face_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($face_id == 0)
		{	mosInvalidURL();
			exit;
		}	
  if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_faces", "face_id", $face_id);
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
			'face_name' 	 =>	mosGetParam( $_REQUEST, 'face_name', ''),
			'face_url' 	 =>	mosGetParam( $_REQUEST, 'face_url', ''),
			'pass' 		   =>	mosGetParam( $_REQUEST, 'pass', ''),	
			'ghichu' 		 =>	mosGetParam( $_REQUEST, 'ghichu', ''),		
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'face_id'	   =>	$id,
		));
		$template->set_filenames_new(array(
			'face' => 'common_lists/face/face_info.html')
		);
		
		$template->pparse('face');	
	}
?>