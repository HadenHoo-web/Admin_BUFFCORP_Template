<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'error/error',
		'LANGUAGEID'=> $languageid,
		
	));		

	switch( $action )
	{	
		case 'list'	:	mosList(0); break;
		case 'info'	:	mosInfo(); break;
		case 'up'	:  	mosMove('up'); break;
		case 'down' :  	mosMove('down'); break;
		case 'save'	:	mosSave(); break;
		case 'delete':	mosDelete(); break;
	
		default:
			mosInvalidURL();
			exit;
	}
?>

<?php
function mosList($id)
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$parent_id = mosGetParam( $_REQUEST, 'parent_id', 0 );
		$parent_id = ($parent_id==0)?$id:$parent_id;

	//	if( $parent_id==0 ){
			$sql = "select * from tbl_errors order by error_id DESC limit 200";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			$num_row = $db->sql_numrows($result);
			$order = 0;
			while( $row = $db->sql_fetchrow($result) )
			{	$order = $order + 1;					
		
				$template->assign_block_vars('list', array(
					'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
					'order'				=>  $order,
					'error_id'	=>	$row['error_id'],
					'url_error'	=>	$row['url_error'],
					'pre_url_error'=>	$row['pre_url_error'],
					'time_error'   =>	$row['time_error'],
					'id_error'	 =>	$row['id_error'],
					'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
					'up'			=>	($order == 1) ? ' display: none;' : '',
					'down'			=>	($order == $num_row) ? ' display: none;' : '',		
				));	
			}
	//	}else{
			$sql = "select * from tbl_errors where error_id=$parent_id";
			if( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_vars(array(
					'parent_id'	=>	$row['error_id'],
					'url_error'	=>	$row['url_error'],
				));
			}
	//	}
		
		
		$template->set_filenames_new(array(
			'share' => 'error/error_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$error_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$parent_id	 = mosGetParam( $_REQUEST, 'parent_id', 0 );	
		
		$sql = "select * from tbl_errors where error_id=$parent_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_vars(array(
				'parent_id'		=>	$row['error_id'],
				'parent_name'	=>	$row['url_error'],
			));
		} 

		if ($error_id != 0)
		{	$sql = "select * from tbl_errors where error_id = $error_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'error_id'=>	$error_id,
					'url_error'=>	$row['url_error'],
					'parent_id' =>	$row['parent_id'],
					'active'		=>	($row['active'] == 1) ? 'checked' : '',
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
			'share' => 'error/error_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$error_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($error_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $error_id, $direction, "tbl_errors", "error_id", "priority");
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$error_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$parent_id 	= mosGetParam( $_REQUEST, 'parent_id', '0');
		$url_error	= mosGetParam( $_REQUEST, 'url_error', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($error_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($error_id == '0')
		{	
			if (checkDuplicate("tbl_errors", array('url_error' => $url_error), "url_error",0,false,"language_id = '$languageid' and parent_id = $parent_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_errors", "priority", "");
			$sql = "insert into tbl_errors (parent_id, url_error, active, priority, language_id) values ($parent_id, '$url_error', $active, $priority, $languageid)";	
		} else
			{ 
			if (checkDuplicate("tbl_errors", array('url_error' => $url_error), "url_error",0,false,"language_id = '$languageid' and parent_id = $parent_id"))
			{	
				reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_errors set url_error ='$url_error',  active = $active, language_id=$languageid where error_id = $error_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList($parent_id);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$error_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($error_id == 0)
		{	mosInvalidURL();
			exit;
		}
		
		//admin moi duoc xoa
		if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_errors", "error_id", $error_id);
		}else{
			$template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
		}
		 
		
		mosList(0);
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;				
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$template->assign_vars(array(
			'url_error' 	=>	mosGetParam( $_REQUEST, 'url_error', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'error_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'error' => 'error/error_info.tpl')
		);
		
		$template->pparse('error');	
	}
?>
