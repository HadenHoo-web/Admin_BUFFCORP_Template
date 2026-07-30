<?php
	global $languageid, $template;
	
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'functions/functions',
		'LANGUAGEID'=> $languageid,
		
	));		

	switch( $action )
	{	case 'info':		mosInfo(); break;			
		case 'list':		mosList(); break;
		case 'save':		mosSave(); break;
		case 'delete':		mosDelete(); break;
		case 'permission_list': mosPermissionList(); break;
		case 'permission_save': mosPermissionSave(); break;
		default:
			mosInvalidURL();
			exit;
	}
?>
<?php
//----------------------------------------------------------------------------------------------------------------------------------------
	function mosList()
	{	global $db, $root_path, $skin, $languageid, $template, $theme;
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
	{	global $db, $root_path, $skin, $languageid, $template ,$theme;
		$the_id 	 = mosGetParam( $_REQUEST, 'id', '0' );
		$imgDir="templates/".$skin."/".images."/";
		if ($the_id != '0')
		{	$sql = "select * from tbl_functions where code = '$the_id'";
			if ( !($result = $db->sql_query($sql)) ) 
				message_die( DATABASE_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	$template->assign_vars(array(
					'id'			=>	$the_id,
					'code'			=>	$the_id,					
					'func_name' 	=>	$row['func_name'],
					'description' 	=>	$row['description'],	
					'link'			=>	$row['link'],
					'image_path'	=>	$imgDir.$row['image'],
					'old_image'		=>	$row['image'],
					'readonly'		=>	'readonly',
													
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{	$template->assign_vars(array(
				'id'	=>	'0',
				'readonly' => '',
				'allow'		=> 'none'	
			));
		}
		$template->set_filenames_new(array(
			'functions' => 'functions/functions_info.tpl')
		);
		$template->pparse('functions');
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;				
		$imgDir="templates/".$skin."/".images."/";
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$template->assign_vars(array(
			'code'	=>	mosGetParam( $_REQUEST, 'code', ''),
			'func_name' =>	mosGetParam( $_REQUEST, 'func_name', ''),
			'description'	=>	mosGetParam( $_REQUEST, 'description', ''),
			'link'	=>	mosGetParam( $_REQUEST, 'link', ''),
			'image_path'	=>	($id!=0)?$imgDir.mosGetParam( $_REQUEST, 'old_image', ''):'',
			'MESSAGE'	=>	DUPLICATE_ENTRY,
		));
		$template->set_filenames_new(array(
			'functions' => 'functions/functions_info.tpl')
		);
		
		$template->pparse('functions');	
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	function mosSave()
	{	global $db, $root_path, $skin, $languageid, $template, $theme;	
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$code   	= mosGetParam( $_REQUEST, 'code', '');
		$func_name  = mosGetParam( $_REQUEST, 'func_name', '');
		$description   = mosGetParam( $_REQUEST, 'description', '', 0x0003);
		$old_image		=	mosGetParam( $_REQUEST, 'old_image', '');
		$new_image		=	mosGetParam( $_REQUEST, 'new_image', '');
		$link			=	mosGetParam( $_REQUEST, 'link', '');
		if ($code == '')
		{	mosInvalidURL();
			exit;
		}
		$imgDir="templates/".$skin."/".images."/";
		mosmkdir($imgDir, 0777);
		$img = mosUploadImage($imgDir, "new_image");
		if ($img == '' )
		{	if($id !='0')
			{	$img=$old_image;
			}
		}
		if ($id == '0')
		{	if (checkDuplicate("tbl_functions", array('code' => $code), "code"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
		$sql = "insert into tbl_functions (code,func_name, description,link,image) values ('$code', '$func_name', '$description','$link','$img')";	
		} else
		$sql = "update tbl_functions set func_name = '$func_name', description = '$description',link='$link',image='$img' where code='$code'";	
			
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
		mosList();
	}
//----------------------------------------------------------------------------------------------------------------------------------------
	function mosDelete()
	{	
		global $db, $root_path, $skin, $languageid, $template, $theme;	
		$code=mosGetParam( $_REQUEST, 'code', '');
		if ($code=='')
		{
			mosInvalidURL();
			exit;
		}
		
		$sql = "delete from tbl_permission where code = '$code'";
		if ( !($result = $db->sql_query($sql)) ) 
		message_die( DATABASE_BUSY );
		$sql = "delete from tbl_functions where code = '$code'";
		if ( !($result = $db->sql_query($sql)) ) 
		message_die( DATABASE_BUSY );
		if (isset($template))
		$template->assign_vars(array('MESSAGE'	=>	DELETED_SUCCESS));
		mosList();
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	function mosPermissionList()
	{	global $db, $root_path, $skin, $languageid, $template, $menu_ID,$theme;
		$code = mosGetParam( $_REQUEST, 'id', '0');
		$department_id = (int)mosGetParam( $_REQUEST, 'department_id1', 0);
		if ($code == '0')
		{	mosInvalidURL();
			exit;
		}
		$template->set_filenames_new(array(	'permission' => 'functions/permission_list.tpl'));
		$sql = "select * from tbl_function_menu where code='$code'";		
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars( array(
				'member_id'	=> $code,
				'func_name'	=>	$row["fun_name"],
				'department_id' => $department_id,
				));
			}			
		$departmentOrderSql = "
			case
				when lower(customer_type_name) = 'kinh doanh' then 1
				when lower(customer_type_name) = 'kt seo' then 2
				when lower(customer_type_name) = 'content' then 3
				when lower(customer_type_name) in ('kt website', 'kt web') then 4
				when lower(customer_type_name) = 'hành chính' then 5
				when lower(customer_type_name) = 'hanh chinh' then 5
				when lower(customer_type_name) = 'ctv' then 6
				when lower(customer_type_name) = 'đã nghỉ' then 99
				when lower(customer_type_name) = 'da nghi' then 99
				else 50
			end
		";
		$sql = "select * from tbl_customer_type where active <> 1 order by ".$departmentOrderSql.", customer_type_name";
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
		while( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_block_vars('department_list', array(
				'department_id' => (int)$row['customer_type_id'],
				'department_name' => $row['customer_type_name'],
				'selected' => ((int)$row['customer_type_id'] == $department_id) ? 'selected="selected"' : '',
			));
		}

		$departmentCond = ($department_id > 0) ? " and (m.member_type_id = ".$department_id." or m.extra_member_type_id = ".$department_id.")" : "";
		$sql = "
			select m.*, dept.customer_type_name as department_name, extra_dept.customer_type_name as extra_department_name
			from tbl_member m
			left join tbl_customer_type dept on m.member_type_id = dept.customer_type_id
			left join tbl_customer_type extra_dept on m.extra_member_type_id = extra_dept.customer_type_id
			where 1 ".$departmentCond."
			order by
				case
					when lower(dept.customer_type_name) = 'kinh doanh' then 1
					when lower(dept.customer_type_name) = 'kt seo' then 2
					when lower(dept.customer_type_name) = 'content' then 3
					when lower(dept.customer_type_name) in ('kt website', 'kt web') then 4
					when lower(dept.customer_type_name) = 'hành chính' then 5
					when lower(dept.customer_type_name) = 'hanh chinh' then 5
					when lower(dept.customer_type_name) = 'ctv' then 6
					when lower(dept.customer_type_name) = 'đã nghỉ' then 99
					when lower(dept.customer_type_name) = 'da nghi' then 99
					else 50
				end,
				dept.customer_type_name,
				m.fullname
		";
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{
			$order = $order + 1;
			$departmentName = trim($row['department_name']);
			if (trim($row['extra_department_name']) != '' && $row['extra_department_name'] != $row['department_name']) {
				$departmentName .= ($departmentName != '' ? ', ' : '').trim($row['extra_department_name']);
			}
			$template->assign_block_vars('list', array(
				'className'			=>  	($order % 2 == 1) ? 'alt' : 'inv',
				'order'					=>  	$order,
				'code'					=> 	$row['member_id'],
				'member_name' 			=>	$row['fullname'],
				'department_name'		=>	$departmentName,
				'checked'				=>	isChecked($row['member_id'],$code),
				));	
		}
		$template->pparse('permission');
	}
//----------------------------------------------------------------------------------------------------------------------------------------
	function mosPermissionSave()
	{	global $db, $root_path, $skin, $languageid, $template, $theme;
		$code = mosGetParam( $_REQUEST, 'id', '0');
		$department_id = (int)mosGetParam( $_REQUEST, 'department_id1', 0);
		if ($code == '0')
		{	mosInvalidURL();
			exit;
		}
		$sql = "delete p from tbl_permission p where p.code='$code'";
		if ($department_id > 0) {
			$sql .= " and p.member_id in (
				select member_id from tbl_member
				where member_type_id = ".$department_id." or extra_member_type_id = ".$department_id."
			)";
		}
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
		$_REQUEST['id'] = $code;
		$_REQUEST['department_id1'] = $department_id;
		mosPermissionList();
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
?>
	

