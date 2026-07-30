<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'seo/di_profile',
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
		//$parent_id = mosGetParam( $_REQUEST, 'parent_id', 0 );
		//$parent_id = ($parent_id==0)?$id:$parent_id;

		di_profileList(0,'');	
	//	if( $parent_id==0 ){
		/*$sql = "select * from tbl_di_profiles where language_id=$languageid and parent_id=$parent_id order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'di_profile_id'	=>	$row['di_profile_id'],
				'di_profile_name'=>	$row['di_profile_name'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">�</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}*/

		/*lay thong tin di_profile
			$sql = "select * from tbl_di_profiles where di_profile_id=$parent_id";
			if( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_vars(array(
					'parent_id'	=>	$row['di_profile_id'],
					'di_profile_name'	=>	$row['di_profile_name'],
				));
			}
	//	}*/
		
		
		$template->set_filenames_new(array(
			'share' => 'seo/di_profile_list.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$di_profile_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		//$parent_id	 = mosGetParam( $_REQUEST, 'parent_id', 0 );	
		selectList(0);
		selectProfileList();
		
		/*$sql = "select * from tbl_di_profiles where di_profile_id = $di_profile_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_vars(array(
				'parent_id'		=>	$row['di_profile_id'],
				'parent_name'	=>	$row['di_profile_name'],
				'slug'			=>	$row['slug'],
				'meta_key'		=>	$row['meta_key'],
				'meta_des'		=>	$row['meta_des'],
				'title_seo'		=>	$row['title_seo'],
				'fanpage'		=>	$row['fanpage'],
				'youtube'		=>	$row['youtube'],
				'network1'		=>	$row['network1'],
				'network2'		=>	$row['network2'],
			));
		} */

		if ($di_profile_id != 0)
		{	$sql = "select * from tbl_di_profiles where di_profile_id = $di_profile_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'di_profile_id'=>	$di_profile_id,
					'di_profile_name'=>	$row['di_profile_name'],
					'groupkey_id'	=>	$row['groupkey_id'],
					'profile_id'	=>	$row['profile_id'],
					'slug'			=>	$row['slug'],
					'meta_key'		=>	$row['meta_key'],
					'meta_des'		=>	$row['meta_des'],
					'title_seo'		=>	$row['title_seo'],
					'fanpage'		=>	$row['fanpage'],
					'youtube'		=>	$row['youtube'],
					'network1'		=>	$row['network1'],
					'network2'		=>	$row['network2'],
					'anchor_text'	=>	$row['anchor_text'],
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
				'parent_id' =>	'0',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'seo/di_profile_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$di_profile_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($di_profile_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $di_profile_id, $direction, "tbl_di_profiles", "di_profile_id", "priority");
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$di_profile_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$groupkey_id 	= mosGetParam( $_REQUEST, 'groupkey_id', '0');
		$profile_id		= mosGetParam( $_REQUEST, 'profile_id', '0');
		$di_profile_name	= mosGetParam( $_REQUEST, 'di_profile_name', '');
		$slug			= mosGetParam( $_REQUEST, 'slug', '');
		$meta_key		= mosGetParam( $_REQUEST, 'meta_key', '');
		$meta_des		= mosGetParam( $_REQUEST, 'meta_des', '');
		$title_seo		= mosGetParam( $_REQUEST, 'title_seo', '');
		$fanpage		= mosGetParam( $_REQUEST, 'fanpage', '');
		$youtube		= mosGetParam( $_REQUEST, 'youtube', '');
		$network1		= mosGetParam( $_REQUEST, 'network1', '');
		$network2		= mosGetParam( $_REQUEST, 'network2', '');
		$anchor_text	= mosGetParam( $_REQUEST, 'anchor_text', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($di_profile_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($di_profile_id == '0')
		{	
			if (checkDuplicate("tbl_di_profiles", array('di_profile_name' => $di_profile_name), "di_profile_name",0,false,"language_id = '$languageid' and groupkey_id = $groupkey_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_di_profiles", "priority", "");
			$sql = "insert into tbl_di_profiles (groupkey_id, profile_id, di_profile_name, slug, meta_key, meta_des, title_seo, fanpage, youtube, network1, network2, anchor_text, active, priority, language_id) values ('$groupkey_id', '$profile_id', '$di_profile_name', '$slug', '$meta_key', '$meta_des', '$title_seo', '$fanpage', '$youtube', '$network1', '$network2', '$anchor_text', $active, $priority, $languageid)";
		} else
			{ 
			$sql = "update tbl_di_profiles set di_profile_name ='$di_profile_name', slug = '$slug', meta_key = '$meta_key', meta_des = '$meta_des', title_seo = '$title_seo', fanpage = '$fanpage', youtube = '$youtube', network1 = '$network1', network2 = '$network2', anchor_text = '$anchor_text',  active = $active, language_id=$languageid, groupkey_id = '$groupkey_id', profile_id = '$profile_id' where di_profile_id = $di_profile_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$di_profile_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($di_profile_id == 0)
		{	mosInvalidURL();
			exit;
		}
		
		if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_di_profiles", "di_profile_id", $di_profile_id);
		}else
			{
				$template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
			}
		
		mosList($parent_id);
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;				
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$template->assign_vars(array(
			'di_profile_name' 	=>	mosGetParam( $_REQUEST, 'di_profile_name', ''),
			'slug'				=>	mosGetParam( $_REQUEST, 'slug', ''),
			'meta_key'			=>	mosGetParam( $_REQUEST, 'meta_key', ''),
			'meta_des'			=>	mosGetParam( $_REQUEST, 'meta_des', ''),
			'title_seo'			=>	mosGetParam( $_REQUEST, 'title_seo', ''),
			'fanpage'			=>	mosGetParam( $_REQUEST, 'fanpage', ''),
			'youtube'			=>	mosGetParam( $_REQUEST, 'youtube', ''),
			'network1'			=>	mosGetParam( $_REQUEST, 'network1', ''),
			'network2'			=>	mosGetParam( $_REQUEST, 'network2', ''),
			'anchor_text'		=>	mosGetParam( $_REQUEST, 'anchor_text', ''),	
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'di_profile_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'group' => 'seo/di_profile_info.html')
		);
		
		$template->pparse('group');	
	}
//--------------------------------------------------------------------------------------------------
	function di_profileList($parent_id, $prefix = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")
	{	global $db, $languageid, $template;
		$sql = "SELECT * FROM (tbl_di_profiles inner join tbl_groupkeys on tbl_di_profiles.groupkey_id = tbl_groupkeys.groupkey_id) inner join tbl_profiles on tbl_di_profiles.profile_id = tbl_profiles.profile_id ORDER BY di_profile_id DESC" ;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;				
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'di_profile_id'	=>	$row['di_profile_id'],
				'di_profile_name'=>	$prefix . $row['di_profile_name'],
				'groupkey_name'	=>	$row['groupkey_name'],
				'url'			=>	$row['url'],
				'profile_name'	=>	$row['profile_name'],
				'slug'			=>	$row['slug'],
				'meta_key'		=>	$row['meta_key'],
				'meta_des'		=>	$row['meta_des'],
				'title_seo'		=>	$row['title_seo'],
				'fanpage'		=>	$row['fanpage'],
				'youtube'		=>	$row['youtube'],
				'network1'		=>	$row['network1'],
				'network2'		=>	$row['network2'],
				'anchor_text'	=>	$row['anchor_text'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">�</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
	}
//--------------------------------------------------------------------------------------------------
	function selectList($parent_id, $prefix = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")
	{	global $db, $languageid, $template;
		$sql = "SELECT * FROM tbl_groupkeys  WHERE (parent_id = $parent_id) ORDER BY priority" ;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
			$template->assign_block_vars('select_list', array(
				'groupkey_id'	=>	$row['groupkey_id'],
				'groupkey_name'	=>	$prefix . $row['groupkey_name'],	
			));	
			selectList($row['groupkey_id'], $prefix. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
		}
	}
//--------------------------------------------------------------------------------------------------
	function selectProfileList()
	{	global $db, $languageid, $template;
		$sql = "SELECT * FROM tbl_profiles  WHERE active = 1 ORDER BY profile_id" ;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
			$template->assign_block_vars('profile_list', array(
				'profile_id'	=>	$row['profile_id'],
				'profile_name'	=>	$row['profile_name'],	
			));	
		}
	}
?>