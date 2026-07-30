<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/map',
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
		
		$sql = "SELECT tbl_maps.*, tbl_emails.email_name FROM tbl_maps LEFT JOIN tbl_emails ON tbl_maps.email_id = tbl_emails.`email_id` ORDER BY tbl_maps.priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'	=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'		=>  $order,
				'map_id'	 =>	$row['map_id'],
				'map_name'   =>	$row['map_name'],
				'url'   		 =>	($row['url'])?"<a href=".$row['url']." target='_blank'>Xem Map</a>":"",
				'address'   	  =>	$row['address'],
				'ghichu'   	   =>	$row['ghichu'],
				'tel_name'	 =>	$row['tel_name'],
				'email_name'   =>	$row['email_name'],
				'active' 	   =>	($row['active'] == 1) ? '' : 'none',
				'up'		   =>	($order == 1) ? ' display: none;' : '',
				'down'		 =>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'common_lists/map/map_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$map_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($map_id != 0)
		{	$sql = "select * from tbl_maps where map_id = $map_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'map_id'	=>	$map_id,
					'map_name'	=>	$row['map_name'],
					'url'		  =>	$row['url'],
					'ghichu'		=>	$row['ghichu'],
					'address'	   =>	$row['address'],
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
			'share' => 'common_lists/map/map_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$map_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($map_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $map_id, $direction, "tbl_maps", "map_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$map_id 	    = mosGetParam( $_REQUEST, 'id', '0');
		$map_name	  = mosGetParam( $_REQUEST, 'map_name', '');
		$url		   = mosGetParam( $_REQUEST, 'url', '');
		$ghichu		= mosGetParam( $_REQUEST, 'ghichu', '');
		$address	   = mosGetParam( $_REQUEST, 'address', '');
		$tel_id		= mosGetParam( $_REQUEST, 'tel_id', '');
		$email_id	  = mosGetParam( $_REQUEST, 'email_id', '');
		$active		= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($map_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($map_id == '0')
		{	
			if (checkDuplicate("tbl_maps", array('map_name' => $map_name), "map_name",0,false,""))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_maps", "priority", "");
			$sql = "insert into tbl_maps (map_name, url, ghichu, address, active, priority, language_id, tel_id, email_id) values ('$map_name', '$url', '$ghichu', '$address', $active, $priority, $languageid, $tel_id, '$email_id')";	
		} else
			{ 
			if (checkDuplicate("tbl_maps", array('map_name' => $map_name), "map_name",0,false,"map_id != $map_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_maps set map_name ='$map_name', url = '$url', ghichu = '$ghichu', address = '$address', active = $active, tel_id = '$tel_id', email_id = '$email_id' where map_id = $map_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$map_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($map_id == 0)
		{	mosInvalidURL();
			exit;
		}	
  if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_maps", "map_id", $map_id);
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
			'map_name' 	 =>	mosGetParam( $_REQUEST, 'map_name', ''),
			'url' 		   =>	mosGetParam( $_REQUEST, 'url', ''),	
			'ghichu' 		 =>	mosGetParam( $_REQUEST, 'ghichu', ''),	
			'address' 		 =>	mosGetParam( $_REQUEST, 'address', ''),	
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'map_id'	   =>	$id,
		));
		$template->set_filenames_new(array(
			'map' => 'common_lists/map/map_info.tpl')
		);
		
		$template->pparse('map');	
	}
?>