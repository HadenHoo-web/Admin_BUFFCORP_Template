<?	
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'newsletter/newsletter',
		'LANGUAGEID'=> $languageid,
		
	));		

	switch( $action )
	{	
		case 'list'			:	mosList(); break;
		case 'info'			:	mosInfo(); break;
		case 'up'			:  	mosMove('up'); break;
		case 'down' 		:  	mosMove('down'); break;
		case 'save'			:	mosSave(); break;
		case 'delete'		:	mosDelete(); break;
	
		default:
			mosInvalidURL();
			exit;
	}
?>

<? 
function mosList()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		
		$sql = "select * from tbl_newsletter order by newsletter_id DESC";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'newsletter_id'			=>	$row['newsletter_id'],
				'email'			=>	$row['email'],
				'ip' 			=>	$row['ip'],
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'newsletter/newsletter_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$newsletter_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($newsletter_id != 0)
		{	$sql = "select * from tbl_newsletter where newsletter_id = $newsletter_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'newsletter_id'	=>	$newsletter_id,
					'email'	=>	$row['email'],
					'active'	=>	($row['active'] == 1) ? 'checked' : '',
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
			'share' => 'newsletter/newsletter_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$newsletter_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($newsletter_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $newsletter_id, $direction, "tbl_newsletter", "newsletter_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$newsletter_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$email	= mosGetParam( $_REQUEST, 'email', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($newsletter_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($newsletter_id == '0')
		{	
			if (checkDuplicate("tbl_newsletter", array('email' => $email), "email",0,false,""))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_newsletter", "priority", "");
			$sql = "insert into tbl_newsletter (email, active, priority) values ('$email', $active, $priority)";	
		} else
			{ 
			if (checkDuplicate("tbl_newsletter", array('email' => $email), "email",0,false,"newsletter_id != $newsletter_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_newsletter set email ='$email', active = $active where newsletter_id = $newsletter_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$newsletter_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($newsletter_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		if(strtolower($_SESSION['membername'])=="administrator"){	
			deleteByID("tbl_newsletter", "newsletter_id", $newsletter_id);
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
			'email' 	=>	mosGetParam( $_REQUEST, 'email', ''),			
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'newsletter_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'source' => 'newsletter/newsletter_info.tpl')
		);
		
		$template->pparse('newsletter');	
	}
?>