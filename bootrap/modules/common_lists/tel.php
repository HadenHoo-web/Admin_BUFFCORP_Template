<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/tel',
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
		
		$sql = "select * from tbl_tels where language_id = $languageid order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'tel_id'			=>	$row['tel_id'],
				'tel_name'			=>	$row['tel_name'],
				'ghichu'			=>	$row['ghichu'],
				'active' 		=>	($row['active'] == 1) ? '' : 'none',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'common_lists/tel/tel_list.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$tel_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($tel_id != 0)
		{	$sql = "select * from tbl_tels where tel_id = $tel_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'tel_id'	=>	$tel_id,
					'tel_name'	=>	$row['tel_name'],
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
			'share' => 'common_lists/tel/tel_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$tel_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($tel_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $tel_id, $direction, "tbl_tels", "tel_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$tel_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$tel_name	= mosGetParam( $_REQUEST, 'tel_name', '');
		$ghichu	= mosGetParam( $_REQUEST, 'ghichu', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($tel_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($tel_id == '0')
		{	
			if (checkDuplicate("tbl_tels", array('tel_name' => $tel_name), "tel_name",0,false,""))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_tels", "priority", "");
			$sql = "insert into tbl_tels (tel_name, ghichu, active, priority, language_id) values ('$tel_name', '$ghichu', $active, $priority, $languageid)";	
		} else
			{ 
			if (checkDuplicate("tbl_tels", array('tel_name' => $tel_name), "tel_name",0,false,"tel_id != $tel_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_tels set tel_name ='$tel_name', ghichu = '$ghichu', active = $active where tel_id = $tel_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$tel_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($tel_id == 0)
		{	mosInvalidURL();
			exit;
		}	
    if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_tels", "tel_id", $tel_id);
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
			'tel_name' 	=>	mosGetParam( $_REQUEST, 'tel_name', ''),
			'ghichu' 	=>	mosGetParam( $_REQUEST, 'ghichu', ''),			
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'tel_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'tel' => 'common_lists/tel/tel_info.html')
		);
		
		$template->pparse('tel');	
	}
?>