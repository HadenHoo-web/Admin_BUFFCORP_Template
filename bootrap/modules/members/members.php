<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'members/members',
		'LANGUAGEID'=> $languageid		
	));		

	switch( $action )
	{	case 'info':		mosInfo(); break;			
		case 'list':		mosList(); break;
		case 'save':		mosSave(); break;
		case 'delete':		mosDelete(); break;
		case 'setpass':		mosSetpassword(); break;
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
			$template->set_filenames_new(array(
			'member' => 'members/member_list.tpl')
		);
		$sql = "select tbl_member.*, tbl_customer_type.customer_type_name, extra_type.customer_type_name as extra_customer_type_name
			from tbl_member
			left join tbl_customer_type on tbl_member.member_type_id = tbl_customer_type.customer_type_id
			left join tbl_customer_type extra_type on tbl_member.extra_member_type_id = extra_type.customer_type_id
			where loginname != 'administrator'
			order by member_type_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;
			$template->assign_block_vars('list', array(
				'className'		=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'			=>  $order,
				'member_id'	=>	$row['member_id'],
				'loginname' =>	$row['loginname'],
				'fullname' 	=>	$row['fullname'],
				'luong'		=>	$row['phone'],
				'luong' 	=>	$row['luong'],
				'cellphone'	=>	$row['cellphone'],
				'email' 	=>	$row['email'],
				'member_type_name'	=> memberDepartmentNames($row['customer_type_name'], $row['extra_customer_type_name']),
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
			));	
		}
		$template->pparse('member');
	}
//----------------------------------------------------------------------------------------------------------------------------------------
	function mosSetpassword()
	{	global $db, $root_path, $skin, $languageid, $template, $theme;
		$member_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($member_id == '0')
		{	mosInvalidURL();
				exit;
		}
		if( $_SERVER['REQUEST_METHOD'] == 'GET')
		{	$template->set_filenames_new(array(
				'member' =>  'members/setpassword.tpl')
			);
			$sql = "select loginname from tbl_member where member_id = $member_id";
			if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
			if ( $row = $db->sql_fetchrow($result) )
				$template->assign_vars( array(
					'member_id'	=> $member_id,
					'loginname'	=>	$row['loginname']));	
			else
			{	mosInvalidURL();
				exit;
			}
			$template->pparse('member');			
		} else
		{	$password = md5(mosGetParam( $_REQUEST, 'new_password', '' ));
			$sql = "update tbl_member set password = '$password' where member_id = $member_id";
			if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
			$template->assign_vars( array(
				'MESSAGE'	=> PASSWORD_SAVED_SUCCESS
			));				
			mosList();
		}
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	function mosInfo()
	{	global $db, $root_path, $skin, $languageid, $template;
		$the_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$extraMemberTypeId = 0;
		if ($the_id != 0)
		{	$sql = "select * from tbl_member where member_id = $the_id";
			if ( !($result = $db->sql_query($sql)) ) 
				message_die( DATABASE_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	$template->assign_vars(array(
					'member_id'	=>	$the_id,
					'loginname' =>	$row['loginname'],
					'fullname'	=>	$row['fullname'],
					'address'	=>	$row['address'],
          'note'	  =>	$row['note'],
					'email'		=>  $row['email'],
					'member_type_id'	=>	($row['member_type_id'])?$row['member_type_id']:'0',
					'phone'		=>  $row['phone'],
					'luong'		=>  $row['luong'],
					'trach_nhiem' => isset($row['trach_nhiem']) ? $row['trach_nhiem'] : 0,
					'cellphone'	=>	$row['cellphone'],
					'active'	=>	($row['active'] == 0) ? 'checked' : '',
					'role'	=> _getRole($row['role_id']),
					'member_type_id'	=>	$row['member_type_id'],								
					'extra_member_type_id' => isset($row['extra_member_type_id']) ? $row['extra_member_type_id'] : 0,
					'bhxh_code' => isset($row['bhxh_code']) ? $row['bhxh_code'] : '',
					'bhxh_salary' => isset($row['bhxh_salary']) ? $row['bhxh_salary'] : 0,
					'bhxh_start_date' => isset($row['bhxh_start_date']) ? $row['bhxh_start_date'] : '',
					'bhxh_status' => isset($row['bhxh_status']) ? $row['bhxh_status'] : 0,
					'bhxh_note' => isset($row['bhxh_note']) ? $row['bhxh_note'] : '',
				));
				$extraMemberTypeId = isset($row['extra_member_type_id']) ? (int)$row['extra_member_type_id'] : 0;
			} else
				message_die( ID_NOTFOUND );		
		} else
		{	$template->assign_vars(array(
				'member_id'	=>	'0',
				'role'	=>_getRole(),
				'member_type_id'	=>	0,
				'extra_member_type_id' => 0,
				'bhxh_code' => '',
				'trach_nhiem' => 0,
				'bhxh_salary' => 0,
				'bhxh_start_date' => '',
				'bhxh_status' => 0,
				'bhxh_note' => '',
			));
		}
		$sql = "select * from tbl_customer_type where active <> 1";
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;
			$customerTypeId = (int)$row['customer_type_id'];
			$template->assign_block_vars('member_type_list', array(
				'member_type_id'		=>	$customerTypeId,
				'member_type_name' 	=>	$row['customer_type_name'],
			));	
			$template->assign_block_vars('member_extra_type_list', array(
				'member_type_id' => $customerTypeId,
				'member_type_name' => $row['customer_type_name'],
				'extra_selected' => ($customerTypeId == $extraMemberTypeId) ? 'selected' : '',
			));
		}
		$template->set_filenames_new(array(
			'member' => 'members/member_info.tpl')
		);
		$template->pparse('member');
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;	
			if (!defined('DUPLICATE_ENTRY')) define('DUPLICATE_ENTRY', "Trùng tên đăng nhập. Vui lòng chọn một tên đăng nhập khác.");
		$template->assign_vars(array(
			'member_id'	=>	mosGetParam( $_REQUEST, 'id', '0'),
			'loginname' =>	mosGetParam( $_REQUEST, 'loginname', ''),
			'fullname'	=>	mosGetParam( $_REQUEST, 'fullname', ''),
			'address'	=>	mosGetParam( $_REQUEST, 'address', ''),
      'note'	  =>	mosGetParam( $_REQUEST, 'note', ''),
			'email'		=>  mosGetParam( $_REQUEST, 'email', ''),
			'member_type_id'	=>  mosGetParam( $_REQUEST, 'member_type_id', ''),
			'phone'		=>  mosGetParam( $_REQUEST, 'phone', ''),
			'luong'		=>  mosGetParam( $_REQUEST, 'luong', ''),
			'trach_nhiem' => mosGetParam( $_REQUEST, 'trach_nhiem', 0),
			'cellphone'	=>	mosGetParam( $_REQUEST, 'cellphone', ''),
			'active'	=>	(mosGetParam( $_REQUEST, 'active',1) == 1) ? 'checked' : '',
			'role'		=>_getRole(mosGetParam( $_REQUEST, 'role', '0')),
			'extra_member_type_id' => mosGetParam( $_REQUEST, 'extra_member_type_id', 0),
			'bhxh_code' => mosGetParam( $_REQUEST, 'bhxh_code', ''),
			'bhxh_salary' => mosGetParam( $_REQUEST, 'bhxh_salary', 0),
			'bhxh_start_date' => mosGetParam( $_REQUEST, 'bhxh_start_date', ''),
			'bhxh_status' => mosGetParam( $_REQUEST, 'bhxh_status', 0),
			'bhxh_note' => mosGetParam( $_REQUEST, 'bhxh_note', ''),
			'MESSAGE'	=>	DUPLICATE_ENTRY,
		));
		$extraMemberTypeId = (int)mosGetParam( $_REQUEST, 'extra_member_type_id', 0);
		$sql = "select * from tbl_customer_type where active <> 1";
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
		while( $row = $db->sql_fetchrow($result) )
		{
			$customerTypeId = (int)$row['customer_type_id'];
			$template->assign_block_vars('member_type_list', array(
				'member_type_id' => $customerTypeId,
				'member_type_name' => $row['customer_type_name'],
			));
			$template->assign_block_vars('member_extra_type_list', array(
				'member_type_id' => $customerTypeId,
				'member_type_name' => $row['customer_type_name'],
				'extra_selected' => ($customerTypeId == $extraMemberTypeId) ? 'selected' : '',
			));
		}
		$template->set_filenames_new(array(
			'member' => 'members/member_info.tpl')
		);
		$template->pparse('member');	
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	function mosSave()
	{	global $db, $root_path, $skin, $languageid, $template;	
		$item_id   	= mosGetParam( $_REQUEST, 'member_id', '0');
		$item_name  = mosGetParam( $_REQUEST, 'loginname', '');
		$fullname   = mosGetParam( $_REQUEST, 'fullname', '');
		$address	= mosGetParam( $_REQUEST, 'address', '');
    $note	    = mosGetParam( $_REQUEST, 'note', '');
		$email		= mosGetParam( $_REQUEST, 'email', '');
		$member_type_id	= mosGetParam( $_REQUEST, 'member_type_id', '');
		$extra_member_type_id = (int)mosGetParam( $_REQUEST, 'extra_member_type_id', 0);
		if ($extra_member_type_id == (int)$member_type_id) $extra_member_type_id = 0;
		$phone		= mosGetParam( $_REQUEST, 'phone', '');
		$luong		= mosGetParam( $_REQUEST, 'luong', '');
		$trach_nhiem = (int)mosGetParam( $_REQUEST, 'trach_nhiem', 0);
		$cellphone	= mosGetParam( $_REQUEST, 'cellphone', '');
		$bhxh_code = mosGetParam( $_REQUEST, 'bhxh_code', '');
		$bhxh_salary = (int)mosGetParam( $_REQUEST, 'bhxh_salary', 0);
		$bhxh_start_date = mosGetParam( $_REQUEST, 'bhxh_start_date', '');
		$bhxh_status = (int)mosGetParam( $_REQUEST, 'bhxh_status', 0);
		$bhxh_note = mosGetParam( $_REQUEST, 'bhxh_note', '');
		$active		= mosGetParam( $_REQUEST, 'active', 1);
		$role			=	mosGetParam( $_REQUEST, 'role', 1);
		if ($item_name == '')
		{	mosInvalidURL();
			exit;
		}		
		if (checkDuplicate("tbl_member", array('loginname' => $item_name), "member_id", $item_id))
		{	reShowPage( DUPLICATE_ENTRY );
			exit;
		}	
		if ($item_id == '0')
		{	$password = md5($item_name);	
			$sql = "insert into tbl_member (loginname, password, fullname, address, note, email, member_type_id, extra_member_type_id, phone, luong, trach_nhiem, cellphone, bhxh_code, bhxh_salary, bhxh_start_date, bhxh_status, bhxh_note, active, created_date, created_by,role_id) values ('$item_name', '$password', '$fullname', '$address', '$note', '$email', '$member_type_id', '$extra_member_type_id', '$phone', '$luong', '$trach_nhiem', '$cellphone', '$bhxh_code', '$bhxh_salary', ".($bhxh_start_date != '' ? "'$bhxh_start_date'" : "NULL").", '$bhxh_status', '$bhxh_note', $active, now(), '".$_SESSION['membername']."','$role')";
		} else
			$sql = "update tbl_member set loginname = '$item_name', fullname = '$fullname', address = '$address', note = '$note', email = '$email', member_type_id = '$member_type_id', extra_member_type_id = '$extra_member_type_id', phone = '$phone', luong = '$luong', trach_nhiem = '$trach_nhiem', cellphone = '$cellphone', bhxh_code = '$bhxh_code', bhxh_salary = '$bhxh_salary', bhxh_start_date = ".($bhxh_start_date != '' ? "'$bhxh_start_date'" : "NULL").", bhxh_status = '$bhxh_status', bhxh_note = '$bhxh_note', active = $active ,role_id='$role' where member_id = $item_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
		mosList();
	}
//----------------------------------------------------------------------------------------------------------------------------------------
	function mosDelete()
	{	
		global $db, $root_path, $skin, $languageid, $template;
    if(strtolower($_SESSION['membername'])=="administrator"){	
			$deleteMemberId = mosGetParam( $_REQUEST, 'id', '0');
			deleteByID("tbl_permission", "member_id", $deleteMemberId);
		  deleteByID("tbl_member", "member_id", $deleteMemberId);
      $template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
		}else{
		  $template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
		}
		mosList();
	}
//----------------------------------------------------------------------------------------------------------------------------------------
	function memberDepartmentNames($primaryName, $extraName)
	{
		$names = array();
		if ($primaryName != '') $names[] = $primaryName;
		if ($extraName != '' && !in_array($extraName, $names)) $names[] = $extraName;
		return implode(', ', $names);
	}

//----------------------------------------------------------------------------------------------------------------------------------------
	
	function _getRole($role_id=0)
	{	global $db;
		$html_code = '<select size="1" name="role" style="width:200">';	
		$sql = "select role_id, role_name from tbl_roles order by role_id";
		if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);	
		while( $row = $db->sql_fetchrow($result) )
		{
			$html_code .= '<option value="'.$row['role_id'].'" '. (($role_id==$row['role_id']) ? 'selected' : '' ) .'>'.$row['role_name'].'</option>';
		}		
		$html_code .= '</select>';		
		return $html_code;
	}	
//----------------------------------------------------------------------------------------------------------------------------------------	
function mosPermissionList()
	{	global $db, $root_path, $skin, $languageid, $template, $menu_ID,$theme;
		$member_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($member_id == 0)
		{	mosInvalidURL();
			exit;
		}
		$template->set_filenames_new(array(	'permission' => 'members/permission_list.tpl'));
		$sql = "select * from tbl_member where member_id='$member_id'";		
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars( array(
				'member_id'	=> $member_id,
				'login_name'				=>	$row["loginname"],
				));
			}			
		$sql = "select * from tbl_function_menu where parent_id = 0 Order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;
			$template->assign_block_vars('list', array(
				'className'				=> 	($order % 2 == 1) ? 'alt' : 'inv',
				'order'					=> 	$order,
				'code'					=> 	$row['code'],
				'func_name' 			=>	$row['fun_name'],
				'checked'				=>	isChecked($member_id,$row['code']),
				));	
		}
		$template->pparse('permission');
	}
//----------------------------------------------------------------------------------------------------------------------------------------
	function mosPermissionSave()
	{	global $db, $root_path, $skin, $languageid, $template, $theme;
		$member_id = mosGetParam( $_REQUEST, 'id', 0);
		if ($member_id == 0)
		{	mosInvalidURL();
			exit;
		}		
		$sql = "delete from tbl_permission where (member_id=$member_id)";
		if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
		$sql = "select role_id from tbl_member where member_id=$member_id ";
		if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
		if( $row = $db->sql_fetchrow($result) )
		{
			$role_id=$row['role_id'];
		}
			foreach ($_REQUEST as $key => $val) 
				{	if (substr($key,0,4) == "dung")
				{
					$sql = "insert into tbl_permission (member_id, code, role_id) values ('$member_id','$val', '$role_id')";
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

?>
