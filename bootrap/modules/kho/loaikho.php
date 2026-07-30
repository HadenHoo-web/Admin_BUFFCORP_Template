<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'kho/loaikho',
		'LANGUAGEID'=> $languageid,
	));		

	switch( $action )
	{	
		case 'list'	:	mosList(); break;
		case 'info'	:	mosInfo(); break;
		case 'up'	:  	mosMove('up'); break;
		case 'down' :  	mosMove('down'); break;
		case 'save'	:	mosSave(); break;
		case 'delete':	mosDelete(); break;
	
		default:
			mosInvalidURL();
			exit;
	}
function mosList()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		
		$sql = "select * from tbl_loaikho where language_id=$languageid order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'loaikho_id'	=>	$row['loaikho_id'],
				'loaikho_name'=>	$row['loaikho_name'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'kho/loaikho_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$loaikho_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir = $root_path . "images/loaikho/";

		if ($loaikho_id != 0)
		{	$sql = "select * from tbl_loaikho where loaikho_id = $loaikho_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$image=$row['image'];
				$template->assign_vars(array(
					'loaikho_id'=>	$loaikho_id,
					'loaikho_name'=>	$row['loaikho_name'],
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
			'share' => 'kho/loaikho_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$loaikho_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($loaikho_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $loaikho_id, $direction, "tbl_loaikho", "loaikho_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$loaikho_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$loaikho_name	= mosGetParam( $_REQUEST, 'loaikho_name', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$image   		= mosGetParam( $_REQUEST, 'new_image', '');
		$old_image   	= mosGetParam( $_REQUEST, 'old_image', '');
		
		
		
		
		if ($loaikho_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($loaikho_id == '0')
		{	
			if (checkDuplicate("tbl_loaikho", array('loaikho_name' => $loaikho_name), "loaikho_name",0,false,"language_id = '$languageid'"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_loaikho", "priority", "");
			$sql = "insert into tbl_loaikho (loaikho_name, active, priority, language_id, image) values ('$loaikho_name', $active, $priority, $languageid, '$img')";	
		} else
			{ 
			if (checkDuplicate("tbl_loaikho", array('loaikho_name' => $loaikho_name), "loaikho_name",0,false,"language_id = '$languageid' and loaikho_id != $loaikho_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_loaikho set loaikho_name ='$loaikho_name',  active = $active, language_id=$languageid, image = '$img' where loaikho_id = $loaikho_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$loaikho_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($loaikho_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		
		$sql = "select image from tbl_loaikho where loaikho_id = $loaikho_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )
		{	
			$img = $row['image'];	
		}
		$arrField = array("image");
		checkDeleteOldFile("", $img, 1, $root_path . "images/loaikho" , "tbl_loaikho", $arrField, "loaikho_id", $loaikho_id);
		if(strtolower($_SESSION['membername'])=="administrator"){	
			deleteByID("tbl_loaikho", "loaikho_id", $loaikho_id);
      $template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
		}else{
		  $template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
		}
		mosList();
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;				
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$template->assign_vars(array(
			'loaikho_name' 	=>	mosGetParam( $_REQUEST, 'loaikho_name', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'loaikho_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'loaikho' => 'kho/loaikho_info.tpl')
		);
		
		$template->pparse('loaikho');	
	}
?>