<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/group',
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
			$sql = "select * from tbl_group where language_id=$languageid and parent_id=$parent_id order by priority";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			$num_row = $db->sql_numrows($result);
			$order = 0;
			while( $row = $db->sql_fetchrow($result) )
			{	$order = $order + 1;					
		
				$template->assign_block_vars('list', array(
					'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
					'order'				=>  $order,
					'group_id'	=>	$row['group_id'],
					'group_name'=>	$row['group_name'],
					'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">�</font></b></font>' : '',
					'up'			=>	($order == 1) ? ' display: none;' : '',
					'down'			=>	($order == $num_row) ? ' display: none;' : '',		
				));	
			}
	//	}else{
			$sql = "select * from tbl_group where group_id=$parent_id";
			if( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_vars(array(
					'parent_id'	=>	$row['group_id'],
					'group_name'	=>	$row['group_name'],
				));
			}
	//	}
		
		
		$template->set_filenames_new(array(
			'share' => 'common_lists/group/group_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$group_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$parent_id	 = mosGetParam( $_REQUEST, 'parent_id', 0 );	
		
		$sql = "select * from tbl_group where group_id=$parent_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_vars(array(
				'parent_id'		=>	$row['group_id'],
				'parent_name'	=>	$row['group_name'],
			));
		} 

		if ($group_id != 0)
		{	$sql = "select * from tbl_group where group_id = $group_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'group_id'=>	$group_id,
					'group_name'=>	$row['group_name'],
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
			'share' => 'common_lists/group/group_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$group_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($group_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $group_id, $direction, "tbl_group", "group_id", "priority");
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$group_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$parent_id 	= mosGetParam( $_REQUEST, 'parent_id', '0');
		$group_name	= mosGetParam( $_REQUEST, 'group_name', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($group_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($group_id == '0')
		{	
			if (checkDuplicate("tbl_group", array('group_name' => $group_name), "group_name",0,false,"language_id = '$languageid' and parent_id = $parent_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_group", "priority", "");
			$sql = "insert into tbl_group (parent_id, group_name, active, priority, language_id) values ($parent_id, '$group_name', $active, $priority, $languageid)";	
		} else
			{ 
			if (checkDuplicate("tbl_group", array('group_name' => $group_name), "group_name",0,false,"language_id = '$languageid' and parent_id = $parent_id"))
			{	echo "vao day";
				reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_group set group_name ='$group_name',  active = $active, language_id=$languageid where group_id = $group_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList($parent_id);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$group_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($group_id == 0)
		{	mosInvalidURL();
			exit;
		}
		$sql = "select * from tbl_group where group_id = '$group_id'";
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result))
			$parent_id = $row['parent_id'];
		$sql1 = "select count(*) as child_count from tbl_group where parent_id = '$group_id'";
		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		if ( $row1 = $db->sql_fetchrow($result1))		
		{	if (($row1['child_count'] == 0))
			{	
        if(strtolower($_SESSION['membername'])=="administrator"){	
			    deleteByID("tbl_group", "group_id", $group_id);
          $template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
		    }else{
				  $template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
			  }
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
			'group_name' 	=>	mosGetParam( $_REQUEST, 'group_name', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'group_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'group' => 'common_lists/group/group_info.tpl')
		);
		
		$template->pparse('group');	
	}
?>