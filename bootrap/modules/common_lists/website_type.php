<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/website_type',
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
    $sql = "select * from tbl_website_type where language_id = $languageid order by priority";
    if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
    $num_row = $db->sql_numrows($result);
    $order = 0;
    while( $row = $db->sql_fetchrow($result) ){
        $order = $order + 1;
        $template->assign_block_vars('list', array(
            'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
            'order'				=>  $order,
            'website_type_id'			=>	$row['website_type_id'],
            'website_type_name'			=>	$row['website_type_name'],
            'active' 		=>	($row['active'] == 1) ? '' : 'none',
            'up'			=>	($order == 1) ? ' display: none;' : '',
            'down'			=>	($order == $num_row) ? ' display: none;' : '',
        ));
    }
    $template->set_filenames_new(array(
        'share' => 'common_lists/website_type/website_type_list.html')
    );
    $template->pparse('share');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$website_type_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		if ($website_type_id != 0)
		{	$website_type_id = intval($website_type_id);
			$sql = "select * from tbl_website_type where website_type_id = $website_type_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'website_type_id'	=>	$website_type_id,
					'website_type_name'	=>	$row['website_type_name'],
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
			'share' => 'common_lists/website_type/website_type_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{
		global $languageid;
		$website_type_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($website_type_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $website_type_id, $direction, "tbl_website_type", "website_type_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$website_type_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$website_type_name	= mosGetParam( $_REQUEST, 'website_type_name', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($website_type_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($website_type_id == '0')
		{	
			if (checkDuplicate("tbl_website_type", array('website_type_name' => $website_type_name), "website_type_name",0,false,""))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_website_type", "priority", "");
			$sql = "insert into tbl_website_type (website_type_name, active, priority, language_id) values ('$website_type_name', $active, $priority, $languageid)";
		} else
			{ 
			if (checkDuplicate("tbl_website_type", array('website_type_name' => $website_type_name), "website_type_name",0,false,"website_type_id != $website_type_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_website_type set website_type_name ='$website_type_name', active = $active where website_type_id = $website_type_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$website_type_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($website_type_id == 0)
		{	mosInvalidURL();
			exit;
		}	
    if(strtolower($_SESSION['membername'])=="administrator"){	
			deleteByID("tbl_website_type", "website_type_id", $website_type_id);
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
			'website_type_name' 	=>	mosGetParam( $_REQUEST, 'website_type_name', ''),
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'website_type_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'website_type' => 'common_lists/website_type/website_type_info.html')
		);
		
		$template->pparse('website_type');
	}
?>
