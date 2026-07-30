<?php	
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'faq/faq',
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
?>

<?php
function mosList()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$faq_type_id	= mosGetParam( $_REQUEST, 'faq_type_id', 0 );
		$cond = "";
		$cond = ($faq_type_id == 0)?'':' and faq_type_id = '.$faq_type_id;
		
		$sql = "select * from tbl_faq where language_id=$languageid $cond order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'faq_id'	=>	$row['faq_id'],
				'a'=>	$row['a'],
				'q'=>	$row['q'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		
		$sql = "select * from tbl_faq_type where active = 1 and language_id = $languageid";
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('faq_type_list',array(
				'faq_type_id'		=>	$row['faq_type_id'],
				'faq_type_name'		=>	$row['faq_type_name'],
			));
		}
		
		$template->assign_vars(array(
			'faq_type_id'	=>	$faq_type_id,
		));
		
		$template->set_filenames_new(array(
			'faq' => 'faq/faq_list.tpl')
		);
		$template->pparse('faq');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$faq_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($faq_id != 0)
		{	$sql = "select * from tbl_faq where faq_id = $faq_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'faq_id'		=>	$faq_id,
					'a'				=>	$row['a'],
					'q'				=>	$row['q'],
					'faq_type_id'	=>	$row['faq_type_id'],
					'active'		=>	($row['active'] == 1) ? 'checked' : '',
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'active'		=>	'checked' ,
				'allow'			=> 'hidden',
				'faq_type_id'	=>	0,
			));
		}
		
		$sql = "select * from tbl_faq_type where active = 1 and language_id = $languageid order by priority";
		if ( !( $result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('faq_type_list',array(
				'faq_type_id'	=>	$row['faq_type_id'],
				'faq_type_name'	=>	$row['faq_type_name'],
			));
		}
		
		$template->set_filenames_new(array(
			'faq' => 'faq/faq_info.tpl')
		);
		$template->pparse('faq');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$faq_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($faq_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $faq_id, $direction, "tbl_faq", "faq_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$faq_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$a	= mosGetParam( $_REQUEST, 'a', '', 0x0003);
		$q	= mosGetParam( $_REQUEST, 'q', '', 0x0003);
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$faq_type_id	= mosGetParam( $_REQUEST, 'faq_type_id', 0);
		
		if ($faq_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($faq_id == '0')
		{	
			$priority = mosGetPriority("tbl_faq", "priority", "");
			$sql = "insert into tbl_faq (q, a, active, priority, language_id, faq_type_id) values ('$q', '$a', $active, $priority, $languageid, $faq_type_id)";	
		} else
			{ 
			$sql = "update tbl_faq set q='$q', a ='$a',  active = $active, language_id=$languageid, faq_type_id = $faq_type_id where faq_id = $faq_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$faq_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($faq_id == 0)
		{	mosInvalidURL();
			exit;
		}	
    if(strtolower($_SESSION['membername'])=="administrator"){	
			deleteByID("tbl_faq", "faq_id", $faq_id);
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
			'a' =>	mosGetParam( $_REQUEST, 'a', ''),			
			'q' =>	mosGetParam( $_REQUEST, 'q', ''),			
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'faq_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'faq' => 'faq/faq_info.tpl')
		);
		
		$template->pparse('faq');	
	}
?>