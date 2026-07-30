<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'functionmenu/functionmenu',
		'LANGUAGEID'=> $languageid,
		
	));		

	switch( $action )
	{	case 'info':		mosInfo(); break;			
		case 'list':		mosList(); break;
		case 'save':		mosSave(); break;
		case 'delete':		mosDelete(); break;
		case 'fun_up' 		:  	mosFunMove('up'); break;
		case 'fun_down'		:	mosFunMove('down'); break;
		
		case 'permission_list': mosPermissionList(); break;
		case 'permission_save': mosPermissionSave(); break;
		default:
			mosInvalidURL();
			exit;
	}
?>
<?php
//----------------------------------------------------------------------------------------------------------------------------------------
	function mosList($sup_id = 0)
	{	
		global $db, $root_path, $skin, $languageid, $template, $theme;
		if ($sup_id==0)
		{
			$sup_id 	 = mosGetParam( $_REQUEST, 'sup_id', '0' );
		}
		
		if ($sup_id==0)
		{
			$back_id	= 0;
		}
		else
		{
			$sql2 = "select * from tbl_function_menu where fun_id=$sup_id";
			if ( !($result2 = $db->sql_query($sql2)) ) message_die( DATABASE_BUSY );
			$row2 = $db->sql_fetchrow($result2);
			$back_id = $row2['parent_id'];
		}
		$template->set_filenames_new(array(
			'functions' => 'functionmenu/functionmenu_list.tpl')
		);
		if($sup_id!=0)
		{
			$sql1 = "select * from tbl_function_menu where fun_id=$sup_id order by priority";
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( DATABASE_BUSY );
			$row1 = $db->sql_fetchrow($result1);
		
			$template->assign_vars(array(
					'sup_id'		=> 	$row1['fun_id'],
					'sup_fun_name'	=> 	$row1['fun_name'],
					'link_sub'		=>	"#",
				));
		}
		else
		{
			$template->assign_vars(array(
					'link_sub'	=>	"?option=functionmenu/functionmenu&mode=list&sup_id",
				));
		}
		$template->assign_vars(array(
					'back_id'	=> 	$back_id,
					'allow'	=> 'hidden',
				));
	
		$sql = "select * from tbl_function_menu where parent_id=$sup_id order by priority";
		
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
		$order = 0;
		$num_row = $db->sql_numrows($result);
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;
			$template->assign_block_vars('list', array(
				'className'		=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'			=>  $order,
				'sup_id'		=> 	$row1['fun_id'],
				'fun_id'	=>	$row['fun_id'],
				'code'		=>	$row['code'],
				'fun_name' 	=>	$row['fun_name'],
				'description' 	=>	$row['description'],
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',
			));	
		}
		$template->pparse('functions');
	}

//----------------------------------------------------------------------------------------------------------------------------------------	
	function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template ,$theme;
		$sup_id 	 = mosGetParam( $_REQUEST, 'sup_id', '0' );
		$id 	 = mosGetParam( $_REQUEST, 'id', '0' );
		
		$imgDir="templates/".$skin."/".images."/menu/";
		if($sup_id!=0)
		{
			$sql1 = "select * from tbl_function_menu where fun_id=$sup_id ";
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( DATABASE_BUSY );
			$row1 = $db->sql_fetchrow($result1);
		
			$template->assign_vars(array(
					'sup_id'		=> 	$row1['fun_id'],
					'sup_fun_name'	=> 	$row1['fun_name'],
				));
		}
		
		
		if ($id != '0')
		{	$sql = "select * from tbl_function_menu where fun_id = '$id'";
			if ( !($result = $db->sql_query($sql)) ) 
				message_die( DATABASE_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	$template->assign_vars(array(
					'sup_id'		=>	$sup_id,
					'id'			=>	$id,
					'code'			=>	$row['code'],					
					'fun_name' 		=>	$row['fun_name'],
					'description' 	=>	$row['description'],	
					'link'			=>	$row['link'],
					'image'			=>	$row['image'],
					'image_path'	=>	$imgDir.$row['image'],
					'old_image'		=>	$row['image'],
					'readonly'		=>	'readonly',								
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{	
			$template->assign_vars(array(
				'id'	=>	'0',
				'readonly' => '',
				'allow'		=> 'none'	
			));
		}
		
		$template->set_filenames_new(array(
			'functionmenu' => 'functionmenu/functionmenu_info.tpl')
		);
		$template->pparse('functionmenu');
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;	
		$sup_id 	 = mosGetParam( $_REQUEST, 'sup_id', '0' );
		$template->assign_vars(array(
			'sup_id'		=>	$sup_id,					
		));	
		$imgDir="templates/".$skin."/".images."/";
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$template->assign_vars(array(
			'code'			=>	mosGetParam( $_REQUEST, 'code', ''),
			'fun_name' 	=>	mosGetParam( $_REQUEST, 'fun_name', ''),
			'description'	=>	mosGetParam( $_REQUEST, 'description', ''),
			'link'			=>	mosGetParam( $_REQUEST, 'link', ''),
			'MESSAGE'		=>	DUPLICATE_ENTRY,
		));
		$template->set_filenames_new(array(
			'functionmenu' => 'functionmenu/functionmenu_info.tpl')
		);
		
		$template->pparse('functionmenu');	
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	function mosSave()
	{	
		global $db, $root_path, $skin, $languageid, $template, $theme;	
		$sup_id   	= mosGetParam( $_REQUEST, 'sup_id', '0');
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$code   	= mosGetParam( $_REQUEST, 'code', '');
		$fun_name  = mosGetParam( $_REQUEST, 'fun_name', '');
		$description   = mosGetParam( $_REQUEST, 'description', '', 0x0003);
		$old_image		=	mosGetParam( $_REQUEST, 'old_image', '');
		$new_image		=	mosGetParam( $_REQUEST, 'new_image', '');
		$remove_image	=	mosGetParam( $_REQUEST, 'remove_image', '');
		$link			=	mosGetParam( $_REQUEST, 'link', '');
		if ($code == '')
		{	mosInvalidURL();
			exit;
		}
		$imgDir="templates/".$skin."/images/menu/";
		mosmkdir($imgDir, 0777);
		//$img = mosUploadImage($imgDir, "new_image");
		if (($img == '') && ($remove_image == 0))
		{	if($id !='0')
			{	$img=$old_image;
			}
		}
		
		if ($id == '0')
		{	if (checkDuplicate("tbl_function_menu", array('code' => $code), "code"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			if (checkDuplicate("tbl_function_menu", array('fun_name' => $fun_name), "fun_name",0,false,"parent_id=$sup_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
		
			
		$priority = mosGetPriority("tbl_function_menu", "priority", "");
		$sql = "insert into tbl_function_menu (code,fun_name, description,link,image,parent_id,language_id,priority) values ('$code', '$fun_name', '$description','$link','$img',$sup_id,$languageid,$priority)";	
		}
		else
		{
			$arrField = array("image");
			checkDeleteOldFile($new_image, $old_image, $remove_image, $imgDir, "tbl_function_menu", $arrField, "fun_id",$id);
			$sql = "update tbl_function_menu set code = '$code', fun_name = '$fun_name', description = '$description',link='$link',image='$img' where fun_id=$id";		
		}
		
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
		mosList($sup_id);
	}
//----------------------------------------------------------------------------------------------------------------------------------------
	function mosDelete()
	{	
		global $db, $root_path, $skin, $languageid, $template, $theme;	
		$id=mosGetParam( $_REQUEST, 'id', '');
		$sup_id=mosGetParam( $_REQUEST, 'sup_id', '');
		$imgDir="templates/".$skin."/".images."/menu";
		
		if ($id=='')
		{
			mosInvalidURL();
			exit;
		}
		else 
		{
			$sql="select * from tbl_function_menu where fun_id = $id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result))
			$image = $row['image'];
		}
		
		$sql1 = "select count(*) as child_count from tbl_function_menu where parent_id = $id";
		

		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		
		if ( $row1 = $db->sql_fetchrow($result1))		
		{	if (($row1['child_count'] == 0) and strtolower($_SESSION['membername'])=="administrator")
			{	
        deleteByID("tbl_function_menu", "fun_id", $id);
				$template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
			} else					
				{	
				$template->assign_vars(array('MESSAGE' => NONE_EMPTY_ERROR));		}
		} 	
		$arrField = array("image");
		checkDeleteOldFile("", $image, 1, $imgDir, "tbl_function_menu", $arrField, "fun_id", $id);
		
		mosList($sup_id);
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	function mosPermissionList()
	{	global $db, $root_path, $skin, $languageid, $template, $menu_ID,$theme;
		$code = mosGetParam( $_REQUEST, 'id', '0');
		if ($code == '0')
		{	mosInvalidURL();
			exit;
		}
		$template->set_filenames_new(array(	'permission' => 'functions/permission_list.tpl'));
		$sql = "select * from tbl_functions where code='$code'";		
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars( array(
				'member_id'	=> $code,
				'func_name'				=>	$row["func_name"],

				));
			}			
		$sql = "select * from tbl_member ";
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;
			$template->assign_block_vars('list', array(
				'className'			=>  	($order % 2 == 1) ? 'alt' : 'inv',
				'order'					=>  	$order,
				'code'					=> 	$row['member_id'],
				'member_name' 			=>	$row['fullname'],
				'checked'				=>	isChecked($row['member_id'],$code),
				));	
		}
		$template->pparse('permission');
	}
//----------------------------------------------------------------------------------------------------------------------------------------
	function mosPermissionSave()
	{	global $db, $root_path, $skin, $languageid, $template, $theme;
		$code = mosGetParam( $_REQUEST, 'id', '0');
		if ($code == '0')
		{	mosInvalidURL();
			exit;
		}		
		$sql = "delete from tbl_permission where (code='$code')";
		if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
		
			foreach ($_REQUEST as $key => $val) 
				{	if (substr($key,0,4) == "dung")
				{
					$sql = "select role_id from tbl_member where member_id=$val ";
						if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
						if( $row = $db->sql_fetchrow($result) )
						{
							$role_id=$row['role_id'];
						}
					$sql = "insert into tbl_permission (member_id, code, role_id) values ('$val','$code', '$role_id')";
					if ( !$db->sql_query($sql) ) message_die(SERVER_BUSY);
				}
			}
		$template->assign_vars( array(
			'MESSAGE' => PERMISSION_SAVED_SUCCESS
		));
		mosList();
	}	
//----------------------------------------------------------------------------------------------------------------------------------------	
function isChecked($member_id,$code)
	{	global $db;
		$sql = "select role_id from tbl_member where member_id=$member_id ";
		if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
		if( $row = $db->sql_fetchrow($result) )
		{
			$role_id=$row['role_id'];
			$sql = "select * from tbl_permission where code='$code' and role_id='$role_id' and member_id='$member_id'";
			if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
			if( $row = $db->sql_fetchrow($result) )
			{
				return "checked";
			}	
		}
		return "";
	}	
//----------------------------------------------------------------------------------------------------------------------------------------	
function mosFunMove( $direction )
	{	
		global $languageid;
		$id    = mosGetParam( $_REQUEST, 'id', '');
		$sup_id    = mosGetParam( $_REQUEST, 'sup_id', '');
		$condition = "(a.parent_id = b.parent_id) and (a.language_id = b.language_id)";
		
		if ($id == 0)
		{	mosInvalidURL();
			exit;
		}

		mosChangePriority( $id, $direction, "tbl_function_menu", "fun_id", "priority",$condition);
		mosList($sup_id);
	}	
?>
