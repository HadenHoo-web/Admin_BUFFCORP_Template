<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'ads/id_ads',
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
		
		$sql = "SELECT tbl_id_ads.*, tbl_tels.tel_name, tbl_emails.email_name, tbl_website.website_name FROM ((tbl_id_ads LEFT JOIN tbl_tels ON tbl_id_ads.tel_id = tbl_tels.`tel_id`) LEFT JOIN tbl_emails ON tbl_id_ads.email_id = tbl_emails.`email_id`) left join tbl_website on tbl_id_ads.website_id = tbl_website.website_id ORDER BY tbl_id_ads.priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'	=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'		=>  $order,
				'id_ads_id'	 =>	$row['id_ads_id'],
				'id_ads_name'   =>	$row['id_ads_name'],
				'pass'   		 =>	$row['pass'],
				'ghichu'   	   =>	$row['ghichu'],
				'tel_name'	 =>	$row['tel_name'],
				'email_name'   =>	$row['email_name'],
				'website_name'	=>	$row['website_name'],
        		'created_by'  => $row['created_by'],
				'active' 	   =>	($row['active'] == 1) ? '' : 'none',
				'isdie' 	   =>	($row['isdie'] == 1) ? '' : 'none',
				'up'		   =>	($order == 1) ? ' display: none;' : '',
				'down'		 =>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'ads/id_ads_list.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$id_ads_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($id_ads_id != 0)
		{	$sql = "select * from tbl_id_ads where id_ads_id = $id_ads_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'id_ads_id'	   =>	$id_ads_id,
					'id_ads_name'	 =>	$row['id_ads_name'],
					'pass'		  =>	$row['pass'],
					'ghichu'		=>	$row['ghichu'],
					'tel_id'		=>	($row['tel_id'])?$row['tel_id']:0,
					'email_id'	  =>	($row['email_id'])?$row['email_id']:0,
					'website_id'	  =>	($row['website_id'])?$row['website_id']:0,
					'active'	    =>	($row['active'] == 1) ? 'checked' : '',
					'isdie'	    =>	($row['isdie'] == 1) ? 'checked' : '',
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
				'isdie'	=> '' ,
				'allow'	 => 'hidden',
				'tel_id'	=> '0',
				'email_id'  => '0',
				'website_id'  => '0',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'ads/id_ads_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$id_ads_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($id_ads_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $id_ads_id, $direction, "tbl_id_ads", "id_ads_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$id_ads_id 	   = mosGetParam( $_REQUEST, 'id', '0');
		$id_ads_name	 = mosGetParam( $_REQUEST, 'id_ads_name', '');
		$pass		  = mosGetParam( $_REQUEST, 'pass', '');
		$ghichu		= mosGetParam( $_REQUEST, 'ghichu', '');
		$tel_id		= mosGetParam( $_REQUEST, 'tel_id', '');
		$email_id	  = mosGetParam( $_REQUEST, 'email_id', '');
		$website_id	  = mosGetParam( $_REQUEST, 'website_id', '');
		$active		= mosGetParam( $_REQUEST, 'active', 0);
		$isdie		= mosGetParam( $_REQUEST, 'isdie', 0);
    $member_id			= mosGetParam( $_REQUEST, 'member_id', 0);
		
		if ($id_ads_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($id_ads_id == '0')
		{	
			if (checkDuplicate("tbl_id_ads", array('id_ads_name' => $id_ads_name), "id_ads_name",0,false,""))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_id_ads", "priority", "");
			$sql = "insert into tbl_id_ads (id_ads_name, pass, ghichu, active, isdie, priority, language_id, tel_id, email_id, website_id, created_date, last_modified, created_by, modified_by, member_id) values ('$id_ads_name', '$pass', '$ghichu', $active, $isdie, $priority, $languageid, $tel_id, '$email_id', '$website_id', now(), now(), '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "', '".$_SESSION["login_id"]."')";	
		} else
			{ 
			if (checkDuplicate("tbl_id_ads", array('id_ads_name' => $id_ads_name), "id_ads_name",0,false,"id_ads_id != $id_ads_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_id_ads set id_ads_name ='$id_ads_name', pass = '$pass', ghichu = '$ghichu', active = $active, isdie = $isdie, tel_id = '$tel_id', email_id = '$email_id', website_id = '$website_id', last_modified = now(), modified_by = '".$_SESSION['membername']."' where id_ads_id = $id_ads_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$id_ads_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($id_ads_id == 0)
		{	mosInvalidURL();
			exit;
		}	
  if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_id_ads", "id_ads_id", $id_ads_id);
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
			'id_ads_name' 	 =>	mosGetParam( $_REQUEST, 'id_ads_name', ''),
			'pass' 		   =>	mosGetParam( $_REQUEST, 'pass', ''),	
			'ghichu' 		 =>	mosGetParam( $_REQUEST, 'ghichu', ''),		
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'id_ads_id'	   =>	$id,
		));
		$template->set_filenames_new(array(
			'id_ads' => 'ads/id_ads_info.html')
		);
		
		$template->pparse('id_ads');	
	}
?>