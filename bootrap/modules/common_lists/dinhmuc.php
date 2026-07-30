<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/dinhmuc',
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
function mosList($id)
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$parent_id = mosGetParam( $_REQUEST, 'parent_id', 0 );
		$parent_id = ($parent_id==0)?$id:$parent_id;

	//	if( $parent_id==0 ){
			$sql = "select * from tbl_dinhmuc where language_id=$languageid and parent_id=$parent_id order by dinhmuc_id DESC";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			$num_row = $db->sql_numrows($result);
			$order = 0;
			while( $row = $db->sql_fetchrow($result) )
			{	$order = $order + 1;					
		
				$template->assign_block_vars('list', array(
					'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
					'order'				=>  $order,
					'dinhmuc_id'	=>	$row['dinhmuc_id'],
					'dinhmuc_name'	=>	$row['dinhmuc_name'],
					'soluong'		=>	$row['soluong'],
					'active' 		=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">�</font></b></font>' : '',
					'up'			=>	($order == 1) ? ' display: none;' : '',
					'down'			=>	($order == $num_row) ? ' display: none;' : '',		
				));	
			}
	//	}else{
			$sql = "select * from tbl_dinhmuc where dinhmuc_id=$parent_id";
			if( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_vars(array(
					'parent_id'	=>	$row['dinhmuc_id'],
					'dinhmuc_name'	=>	$row['dinhmuc_name'],
					'soluong'		=>	$row['soluong'],
				));
			}
	//	}
		
		
		$template->set_filenames_new(array(
			'share' => 'common_lists/dinhmuc/dinhmuc_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$dinhmuc_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$parent_id	 = mosGetParam( $_REQUEST, 'parent_id', 0 );	
		
		$sql = "select * from tbl_dinhmuc where dinhmuc_id=$parent_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_vars(array(
				'parent_id'		=>	$row['dinhmuc_id'],
				'parent_name'	=>	$row['dinhmuc_name'],
				'soluong'		=>	$row['soluong'],
			));
		} 

		if ($dinhmuc_id != 0)
		{	$sql = "select * from tbl_dinhmuc where dinhmuc_id = $dinhmuc_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'dinhmuc_id'=>	$dinhmuc_id,
					'dinhmuc_name'=>	$row['dinhmuc_name'],
					'soluong'		=>	$row['soluong'],
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
			'share' => 'common_lists/dinhmuc/dinhmuc_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$dinhmuc_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($dinhmuc_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $dinhmuc_id, $direction, "tbl_dinhmuc", "dinhmuc_id", "priority");
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$dinhmuc_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$parent_id 	= mosGetParam( $_REQUEST, 'parent_id', '0');
		$dinhmuc_name	= mosGetParam( $_REQUEST, 'dinhmuc_name', '');
		$soluong	= mosGetParam( $_REQUEST, 'soluong', 0);
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($dinhmuc_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($dinhmuc_id == '0')
		{	
			if (checkDuplicate("tbl_dinhmuc", array('dinhmuc_name' => $dinhmuc_name), "dinhmuc_name",0,false,"language_id = '$languageid' and parent_id = $parent_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_dinhmuc", "priority", "");
			$sql = "insert into tbl_dinhmuc (parent_id, dinhmuc_name, soluong, active, priority, language_id) values ($parent_id, '$dinhmuc_name', '$soluong', $active, $priority, $languageid)";	
		} else
			{ 
			$sql = "update tbl_dinhmuc set dinhmuc_name ='$dinhmuc_name', soluong = '$soluong',  active = $active, language_id=$languageid where dinhmuc_id = $dinhmuc_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList($parent_id);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$dinhmuc_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($dinhmuc_id == 0)
		{	mosInvalidURL();
			exit;
		}
		$sql = "select * from tbl_dinhmuc where dinhmuc_id = '$dinhmuc_id'";
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result))
			$parent_id = $row['parent_id'];
		$sql1 = "select count(*) as child_count from tbl_dinhmuc where parent_id = '$dinhmuc_id'";
		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		if ( $row1 = $db->sql_fetchrow($result1))		
		{	if (($row1['child_count'] == 0))
			{	deleteByID("tbl_dinhmuc", "dinhmuc_id", $dinhmuc_id);
				$template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
			} else					
			{	$template->assign_vars(array('MESSAGE' => NONE_EMPTY_ERROR));	}
		} 
		
		mosList($parent_id);
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;				
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$template->assign_vars(array(
			'dinhmuc_name' 	=>	mosGetParam( $_REQUEST, 'dinhmuc_name', ''),
			'soluong'		=>	mosGetParam( $_REQUEST, 'soluong', 0),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'dinhmuc_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'dinhmuc' => 'common_lists/dinhmuc/dinhmuc_info.tpl')
		);
		
		$template->pparse('dinhmuc');	
	}
?>