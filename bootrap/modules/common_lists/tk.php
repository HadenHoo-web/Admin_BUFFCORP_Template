<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/tk',
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
	//$member_id	= mosGetParam( $_REQUEST, 'member_id1', $_SESSION["login_id"] );
    $member_id	= mosGetParam( $_REQUEST, 'member_id1', '0' );
    if($member_id == 0 && $_SESSION["login_id"] != 1) $member_id = $_SESSION["login_id"];
    //if ( $member_id == 1 )$member_id = 0;
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
	$cond   .= ($member_id)?' and (tbl_tks.member_id = '.$member_id.' or tbl_tks.share_user_id like "%'.$member_id.'%")':'';
	$sql = "SELECT tbl_tks.*, tbl_website.website_name, tbl_tk_type.tk_type_name, tbl_tels.tel_name, tbl_emails.email_name, tbl_member.fullname FROM ((((tbl_tks LEFT JOIN tbl_tels ON tbl_tks.tel_id = tbl_tels.`tel_id`) LEFT JOIN tbl_emails ON tbl_tks.email_id = tbl_emails.`email_id`) LEFT JOIN tbl_member ON tbl_tks.member_id = tbl_member.`member_id`) LEFT JOIN tbl_website ON tbl_tks.website_id = tbl_website.`website_id`) LEFT JOIN tbl_tk_type ON tbl_tks.tk_type_id = tbl_tk_type.`tk_type_id` where 1 $cond ORDER BY tbl_tks.website_id, tbl_tks.member_id, tbl_tks.tk_type_id, tbl_tks.tk_id DESC";
    if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order = 0;
	while( $row = $db->sql_fetchrow($result) ){	
		$order = $order + 1;					
		$template->assign_block_vars('list', array(
			'className'	=>  ($order % 2 == 1) ? 'alt' : 'inv',
			'order'		=>  $order,
			'tk_id'	 =>	$row['tk_id'],
			'tk_name'   =>	$row['tk_name'],
            'alias'		=>	$row['alias'],
			'tk_url'   =>	$row['tk_url'],
			'pass'   		 =>	$row['pass'],
			'ghichu'   	   =>	$row['ghichu'],
			'tel_name'	 =>	$row['tel_name'],
			'email_name'   =>	$row['email_name'],
			'member_name'	=>	$row['fullname'],
            'share_user_id'	=>	$row['share_user_id'],
            'website_name' =>	$row['website_name'],
            'tk_type_name' =>	$row['tk_type_name'],
            'is_owner'      =>	($row['member_id'] == $member_id or $_SESSION["login_id"] == 1)?"":"none",
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
		'share' => 'common_lists/tk/tk_list.html')
	);
	$template->pparse('share');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo(){	
	global $db, $root_path, $skin, $languageid, $template;
	$tk_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
	$sql = "select * from tbl_member where active = 1 order by case when member_id = 1 then 0 else 1 end, fullname";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while ( $row = $db->sql_fetchrow($result) ){
		$template->assign_block_vars('member_list', array(
			'member_id'	  =>	$row['member_id'],
			'member_name'	=>	$row['fullname'],
		));
	}

	$cond = 'and active = 1 and member_id not in (1, 2, 62, 73, 81)';
    $sql = "select * from tbl_member where 1 $cond order by fullname";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while ( $row = $db->sql_fetchrow($result) ){
		$template->assign_block_vars('share_member_note', array(
			'member_id'	  =>	$row['member_id'],
			'member_name'	=>	$row['fullname'],
		));
	}
	if ($tk_id != 0){	
		$sql = "select * from tbl_tks where tk_id = $tk_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) ){	
			$template->assign_vars(array(
				'tk_id'	   =>	$tk_id,
				'tk_name'	 =>	$row['tk_name'],
                'alias'	 =>	$row['alias'],
				'tk_url'	 =>	$row['tk_url'],
				'pass'		  =>	$row['pass'],
				'ghichu'		=>	$row['ghichu'],
				'tel_id'		=>	($row['tel_id'])?$row['tel_id']:0,
				'email_id'	  =>	($row['email_id'])?$row['email_id']:0,
				'member_id'	  =>	($row['member_id'])?$row['member_id']:0,
                'website_id'	  =>	($row['website_id'])?$row['website_id']:0,
                'tk_type_id'	  =>	$row['tk_type_id'],
                'share_user_id' =>	$row['share_user_id'],
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
                'website_id'  => '0',
                'tk_type_id'  => '0',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'common_lists/tk/tk_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$tk_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($tk_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $tk_id, $direction, "tbl_tks", "tk_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave(){		
	global $db, $root_path, $skin, $languageid, $template;	
	$tk_id 	   = mosGetParam( $_REQUEST, 'id', '0');
	$tk_name	 = mosGetParam( $_REQUEST, 'tk_name', '');
    $alias 	 = mosGetParam( $_REQUEST, 'alias', '');
	$tk_url	 = mosGetParam( $_REQUEST, 'tk_url', '');
	$pass		  = mosGetParam( $_REQUEST, 'pass', '');
	$ghichu		= mosGetParam( $_REQUEST, 'ghichu', '');
	$tel_id		= mosGetParam( $_REQUEST, 'tel_id', '');
	$email_id	  = mosGetParam( $_REQUEST, 'email_id', '');
	$active		= mosGetParam( $_REQUEST, 'active', 0);
    $member_id			= mosGetParam( $_REQUEST, 'member_id', 0);
    $website_id			= mosGetParam( $_REQUEST, 'website_id', 0);
    $tk_type_id			= mosGetParam( $_REQUEST, 'tk_type_id', 0);
    $share_user_id		= mosGetParam( $_REQUEST, 'share_user_id', 0);
	if ($tk_id == ''){	
		mosInvalidURL();
		exit;
	}	
	if ($tk_id == '0'){
		$priority = mosGetPriority("tbl_tks", "priority", "");
		$sql = "insert into tbl_tks (tk_name, alias, tk_url, pass, ghichu, active, priority, language_id, tel_id, email_id, created_date, last_modified, created_by, modified_by, member_id, website_id, tk_type_id, share_user_id) values ('$tk_name', '$alias', '$tk_url', '$pass', '$ghichu', $active, $priority, $languageid, $tel_id, '$email_id', now(), now(), '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "', '".$_SESSION["login_id"]."', $website_id, $tk_type_id, $share_user_id)";
	}else{
		$sql = "update tbl_tks set tk_name ='$tk_name', alias = '$alias', tk_url = '$tk_url', pass = '$pass', ghichu = '$ghichu', active = $active, tel_id = '$tel_id', email_id = '$email_id', member_id = '$member_id', website_id = '$website_id', tk_type_id = '$tk_type_id', share_user_id = '$share_user_id', last_modified = now(), modified_by = '".$_SESSION['membername']."' where tk_id = $tk_id";
	}
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
	mosList();
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$tk_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($tk_id == 0)
		{	mosInvalidURL();
			exit;
		}	
  if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_tks", "tk_id", $tk_id);
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
			'tk_name' 	 =>	mosGetParam( $_REQUEST, 'tk_name', ''),
            'alias' 	 =>	mosGetParam( $_REQUEST, 'alias', ''),
			'tk_url' 	 =>	mosGetParam( $_REQUEST, 'tk_url', ''),
			'pass' 		   =>	mosGetParam( $_REQUEST, 'pass', ''),	
			'ghichu' 		 =>	mosGetParam( $_REQUEST, 'ghichu', ''),
            'share_user_id' =>	mosGetParam( $_REQUEST, 'share_user_id', ''),
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'tk_id'	   =>	$id,
		));
		$template->set_filenames_new(array(
			'tk' => 'common_lists/tk/tk_info.html')
		);
		
		$template->pparse('tk');	
	}
?>
