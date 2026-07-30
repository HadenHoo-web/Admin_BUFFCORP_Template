<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/tk_type',
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
function mosList(){
    global $db, $root_path, $skin, $languageid, $template;
    $sql = "select * from tbl_tk_type where language_id = $languageid order by priority";
    if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
    $num_row = $db->sql_numrows($result);
    $order = 0;
    while( $row = $db->sql_fetchrow($result) ){
        $order = $order + 1;
        $template->assign_block_vars('list', array(
            'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
            'order'				=>  $order,
            'tk_type_id'			=>	$row['tk_type_id'],
            'tk_type_name'			=>	$row['tk_type_name'],
            'active' 		=>	($row['active'] == 1) ? '' : 'none',
            'up'			=>	($order == 1) ? ' display: none;' : '',
            'down'			=>	($order == $num_row) ? ' display: none;' : '',
        ));
    }
    $template->set_filenames_new(array(
        'share' => 'common_lists/tk_type/tk_type_list.html')
    );
    $template->pparse('share');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$tk_type_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		if ($tk_type_id != 0)
		{	$sql = "select * from tbl_tk_type where tk_type_id = $tk_type_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'tk_type_id'	=>	$tk_type_id,
					'tk_type_name'	=>	$row['tk_type_name'],
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
			'share' => 'common_lists/tk_type/tk_type_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$tk_type_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($tk_type_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $tk_type_id, $direction, "tbl_tk_type", "tk_type_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$tk_type_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$tk_type_name	= mosGetParam( $_REQUEST, 'tk_type_name', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($tk_type_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($tk_type_id == '0')
		{	
			if (checkDuplicate("tbl_tk_type", array('tk_type_name' => $tk_type_name), "tk_type_name",0,false,""))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_tk_type", "priority", "");
			$sql = "insert into tbl_tk_type (tk_type_name, active, priority, language_id) values ('$tk_type_name', $active, $priority, $languageid)";	
		} else
			{ 
			if (checkDuplicate("tbl_tk_type", array('tk_type_name' => $tk_type_name), "tk_type_name",0,false,"tk_type_id != $tk_type_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_tk_type set tk_type_name ='$tk_type_name', active = $active where tk_type_id = $tk_type_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$tk_type_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($tk_type_id == 0)
		{	mosInvalidURL();
			exit;
		}	
    if(strtolower($_SESSION['membername'])=="administrator"){	
			deleteByID("tbl_tk_type", "tk_type_id", $tk_type_id);
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
			'tk_type_name' 	=>	mosGetParam( $_REQUEST, 'tk_type_name', ''),			
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'tk_type_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'tk_type' => 'common_lists/tk_type/tk_type_info.html')
		);
		
		$template->pparse('tk_type');	
	}
?>