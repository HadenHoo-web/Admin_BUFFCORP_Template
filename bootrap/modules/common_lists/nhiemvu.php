<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/nhiemvu',
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
		case 'exe'	:	mosThuchien(); break;
		case 'thongke'	:	mosThongKe(); break;
	
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
			$sql = "select * from tbl_nhiemvu where language_id=$languageid and parent_id=$parent_id order by priority";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			$num_row = $db->sql_numrows($result);
			$order = 0;
			while( $row = $db->sql_fetchrow($result) )
			{	
				$nhiemvu_id	= $row['nhiemvu_id'];
				$sql1 = "select * from tbl_thuchien where member_id = ".$_SESSION["login_id"]." and nhiemvu_id = $nhiemvu_id and ngay = curdate()";
				if ( !($result1 = $db->sql_query($sql1))) die ( SERVER_BUSY );
				if ( $row1 = $db->sql_fetchrow($result1))
					$thuchien = 1;
				else 
					$thuchien = 0;
				$order = $order + 1;					
				$template->assign_block_vars('list', array(
					'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
					'order'				=>  $order,
					'nhiemvu_id'	=>	$row['nhiemvu_id'],
					'nhiemvu_name'	=>	$row['nhiemvu_name'],
					'soluong'		=>	$row['soluong'].$thuchien,
					'active' 		=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
					'up'			=>	($order == 1) ? ' display: none;' : '',
					'down'			=>	($order == $num_row) ? ' display: none;' : '',	
					'thuchien'		=>	($thuchien)?'xong':'<a href="?option=common_lists/nhiemvu&mode=exe&id='.$row['nhiemvu_id'].'"  target="_self"> Đã thực hiện</a>'	
				));	
			}
	//	}else{
			$sql = "select * from tbl_nhiemvu where nhiemvu_id=$parent_id";
			if( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_vars(array(
					'parent_id'	=>	$row['nhiemvu_id'],
					'nhiemvu_name'	=>	$row['nhiemvu_name'],
					'soluong'		=>	$row['soluong'],
				));
			}
	//	}
		
		
		$template->set_filenames_new(array(
			'share' => 'common_lists/nhiemvu/nhiemvu_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$nhiemvu_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$parent_id	 = mosGetParam( $_REQUEST, 'parent_id', 0 );	
		
		$sql = "select * from tbl_nhiemvu where nhiemvu_id=$parent_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_vars(array(
				'parent_id'		=>	$row['nhiemvu_id'],
				'parent_name'	=>	$row['nhiemvu_name'],
				'soluong'		=>	$row['soluong'],
			));
		} 

		if ($nhiemvu_id != 0)
		{	$sql = "select * from tbl_nhiemvu where nhiemvu_id = $nhiemvu_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'nhiemvu_id'=>	$nhiemvu_id,
					'nhiemvu_name'=>	$row['nhiemvu_name'],
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
			'share' => 'common_lists/nhiemvu/nhiemvu_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$nhiemvu_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($nhiemvu_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $nhiemvu_id, $direction, "tbl_nhiemvu", "nhiemvu_id", "priority");
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$nhiemvu_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$parent_id 	= mosGetParam( $_REQUEST, 'parent_id', '0');
		$nhiemvu_name	= mosGetParam( $_REQUEST, 'nhiemvu_name', '');
		$soluong	= mosGetParam( $_REQUEST, 'soluong', 0);
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($nhiemvu_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($nhiemvu_id == '0')
		{	
			if (checkDuplicate("tbl_nhiemvu", array('nhiemvu_name' => $nhiemvu_name), "nhiemvu_name",0,false,"language_id = '$languageid' and parent_id = $parent_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_nhiemvu", "priority", "");
			$sql = "insert into tbl_nhiemvu (parent_id, nhiemvu_name, soluong, active, priority, language_id) values ($parent_id, '$nhiemvu_name', '$soluong', $active, $priority, $languageid)";	
		} else
			{ 
			$sql = "update tbl_nhiemvu set nhiemvu_name ='$nhiemvu_name', soluong = '$soluong',  active = $active, language_id=$languageid where nhiemvu_id = $nhiemvu_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList($parent_id);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$nhiemvu_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($nhiemvu_id == 0)
		{	mosInvalidURL();
			exit;
		}
		$sql = "select * from tbl_nhiemvu where nhiemvu_id = '$nhiemvu_id'";
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result))
			$parent_id = $row['parent_id'];
		$sql1 = "select count(*) as child_count from tbl_nhiemvu where parent_id = '$nhiemvu_id'";
		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		if ( $row1 = $db->sql_fetchrow($result1))		
		{	if (($row1['child_count'] == 0))
			{	deleteByID("tbl_nhiemvu", "nhiemvu_id", $nhiemvu_id);
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
			'nhiemvu_name' 	=>	mosGetParam( $_REQUEST, 'nhiemvu_name', ''),
			'soluong'		=>	mosGetParam( $_REQUEST, 'soluong', 0),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'nhiemvu_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'nhiemvu' => 'common_lists/nhiemvu/nhiemvu_info.tpl')
		);
		
		$template->pparse('nhiemvu');	
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	
	function mosThuchien( )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;		
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$sql = "insert into tbl_thuchien (member_id, nhiemvu_id, ngay) values (".$_SESSION["login_id"].", '$id', CURDATE())";	
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		mosList(0);	
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	function mosThongKe( )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;
		//so lan click
		$sql = "select * from tbl_member";
		if ( !($result = $db->sql_query($sql))) die ( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result))
		{
			$member_id 	= $row['member_id'];
			$template->assign_block_vars('member_list', array(
				'name'		=>	$row['fullname'],
			));
			$sql1 = "select * from tbl_nhiemvu";
			if ( !($result1 = $db->sql_query($sql1))) die ( SERVER_BUSY );
			while ( $row1 = $db->sql_fetchrow($result1))
			{
				$nhiemvu_id	= $row1['nhiemvu_id'];
				$sql2 = "select count(*) as dem from tbl_thuchien where member_id = $member_id and nhiemvu_id = $nhiemvu_id  and month(ngay) = month(now())";
				if ( !($result2 = $db->sql_query($sql2))) die ( SERVER_BUSY );
				if ( $row2 = $db->sql_fetchrow($result2))
					$dem	= $row2['dem'];
				$template->assign_block_vars('member_list.nhiemvu_list', array(
					'nhiemvu_name'	=>	$row1['nhiemvu_name'],
					'dem'			=>	$dem,
					'rong'			=>	$dem * 5,
				));
			}
		}		
		$template->set_filenames_new(array(
			'nhiemvu' => 'common_lists/nhiemvu/thongke.tpl')
		);
		$template->pparse('nhiemvu');	
	}

?>