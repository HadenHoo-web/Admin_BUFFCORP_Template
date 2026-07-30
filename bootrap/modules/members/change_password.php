<?php
	global $root_path,$languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');			
	
	$template = new Template();
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'members/change_password',
		'LANGUAGEID'=> $languageid,		
	));		
	switch( $action )
	{	
		case 'list':		mosList(); break;
		case 'save':		mosSave(); break;
		default:
			mosInvalidURL();
			exit;
	}
?>
<?php
function moslist()
{	global $db, $root_path, $skin, $template,$languageid;
	$member_ID 	 = mosGetParam( $_REQUEST, 'id', 0 );
	$template->set_filenames_new(array(
			'body' => 'members/changepassword.tpl')
		);			
	$template->assign_vars(array(
		'member_id'	=> $member_ID,	
		'skin'		=> $skin,	
	));		
	$template->pparse('body');
}
function mosSave()
{	global $db, $root_path, $skin, $template;
		$member_ID 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$old_password = md5(mosGetParam( $_REQUEST, 'old_password', '' ));
		$password     = md5(mosGetParam( $_REQUEST, 'new_password', '' ));
		$loginname    = $_SESSION["loginname"];
		$sql = "select member_id from tbl_member where (password = '$old_password') and (loginname = '$loginname')";		
		if ( !($result = $db->sql_query($sql)) ) message_die("Database error");
		if ( $row = $db->sql_fetchrow($result) )
		{	$member_ID = $row['member_id'];
		} else
		{	$template->assign_vars( array(
				'MESSAGE'	=> OLD_PASSWORD_ERROR
			));	
			moslist();
			exit;
		}
		$sql = "update tbl_member set password = '$password' where member_id = $member_ID";
		if ( !($result = $db->sql_query($sql)) ) message_die("Database error");
			$template->assign_vars( array(
				'MESSAGE'	=> PASSWORD_SAVED_SUCCESS
			));	
		moslist();
					
}
?>

