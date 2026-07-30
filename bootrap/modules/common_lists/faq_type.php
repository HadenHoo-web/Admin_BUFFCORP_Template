<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/faq_type',
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
			$sql = "select * from tbl_faq_type where language_id=$languageid and parent_id=$parent_id order by priority";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			$num_row = $db->sql_numrows($result);
			$order = 0;
			while( $row = $db->sql_fetchrow($result) )
			{	$order = $order + 1;					
		
				$template->assign_block_vars('list', array(
					'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
					'order'				=>  $order,
					'faq_type_id'	=>	$row['faq_type_id'],
					'faq_type_name'=>	$row['faq_type_name'],
					'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
					'up'			=>	($order == 1) ? ' display: none;' : '',
					'down'			=>	($order == $num_row) ? ' display: none;' : '',		
				));	
			}
	//	}else{
			$sql = "select * from tbl_faq_type where faq_type_id=$parent_id";
			if( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_vars(array(
					'parent_id'	=>	$row['faq_type_id'],
					'faq_type_name'	=>	$row['faq_type_name'],
				));
			}
	//	}
		
		
		$template->set_filenames_new(array(
			'share' => 'common_lists/faq_type/faq_type_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$faq_type_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$parent_id	 = mosGetParam( $_REQUEST, 'parent_id', 0 );
		$imgDir = $root_path . "images/faq_type/";	
		
		$sql = "select * from tbl_faq_type where faq_type_id=$parent_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_vars(array(
				'parent_id'		=>	$row['faq_type_id'],
				'parent_name'	=>	$row['faq_type_name'],
			));
		} 

		if ($faq_type_id != 0)
		{	$sql = "select * from tbl_faq_type where faq_type_id = $faq_type_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'faq_type_id'	=>	$faq_type_id,
					'faq_type_name'	=>	$row['faq_type_name'],
					'parent_id' 	=>	$row['parent_id'],
					'active'		=>	($row['active'] == 1) ? 'checked' : '',
					'image'			=>	$image,
					'imgPath'		=>	($image)?"<img src='$imgDir$image' border=0 >":"",
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
			'share' => 'common_lists/faq_type/faq_type_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$faq_type_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($faq_type_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $faq_type_id, $direction, "tbl_faq_type", "faq_type_id", "priority");
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$faq_type_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$parent_id 		= mosGetParam( $_REQUEST, 'parent_id', '0');
		$faq_type_name	= mosGetParam( $_REQUEST, 'faq_type_name', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$image   		= mosGetParam( $_REQUEST, 'new_image', '');
		$old_image   	= mosGetParam( $_REQUEST, 'old_image', '');
		
		$imgDir = $root_path . "images/faq_type/";		

			mosmkdir($imgDir, 0666);		
		$kt=0;
		$img = mosUploadImage($imgDir, "new_image");
		if ($img == '' )
		{	if($adver_id !='0')
			{	$img=$old_image;
				$kt=1;
			}
			else
			{
				reShowPage("UPLOAD ERROR");
				exit;
			}
		}
		
		if ($faq_type_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($faq_type_id == '0')
		{	
			if (checkDuplicate("tbl_faq_type", array('faq_type_name' => $faq_type_name), "faq_type_name",0,false,"language_id = '$languageid' and parent_id = $parent_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_faq_type", "priority", "");
			$sql = "insert into tbl_faq_type (parent_id, faq_type_name, active, priority, language_id, image) values ($parent_id, '$faq_type_name', $active, $priority, $languageid, '$img')";	
		} else
			{ 
			if (checkDuplicate("tbl_faq_type", array('faq_type_name' => $faq_type_name), "faq_type_name",0,false,"language_id = '$languageid' and parent_id = $parent_id and faq_type_id <> $faq_type_id"))
			{	
				reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_faq_type set faq_type_name ='$faq_type_name', active = $active, image = '$img', language_id=$languageid where faq_type_id = $faq_type_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList($parent_id);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$faq_type_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($faq_type_id == 0)
		{	mosInvalidURL();
			exit;
		}
		
		$sql1 = "select count(*) as child_count from tbl_faq_type where parent_id = '$faq_type_id'";
		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		if ( $row1 = $db->sql_fetchrow($result1))		
		{	if (($row1['child_count'] == 0))
			{	
				$sql = "select * from tbl_faq_type where faq_type_id = $faq_type_id";
				if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
				if ( $row = $db->sql_fetchrow($result))
					$parent_id = $row['parent_id'];
				
				deleteByID("tbl_faq_type", "faq_type_id", $faq_type_id);
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
			'faq_type_name' 	=>	mosGetParam( $_REQUEST, 'faq_type_name', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'faq_type_id'		=>	$id,
		));
		$template->set_filenames_new(array(
			'faq_type' => 'common_lists/faq_type/faq_type_info.tpl')
		);
		
		$template->pparse('faq_type');	
	}
?>