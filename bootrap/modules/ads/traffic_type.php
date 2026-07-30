<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'ads/traffic_type',
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
		
		$sql = "select * from tbl_traffic_type where language_id=$languageid order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'traffic_type_id'	=>	$row['traffic_type_id'],
				'traffic_type_name'	=>	$row['traffic_type_name'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'ads/traffic_type_list.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$traffic_type_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir = $root_path . "images/ads/";

		if ($traffic_type_id != 0)
		{	$sql = "select * from tbl_traffic_type where traffic_type_id = $traffic_type_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$image=$row['image'];
				$template->assign_vars(array(
					'traffic_type_id'=>	$traffic_type_id,
					'traffic_type_name'=>	$row['traffic_type_name'],
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
			'share' => 'ads/traffic_type_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$traffic_type_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($traffic_type_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $traffic_type_id, $direction, "tbl_traffic_type", "traffic_type_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$traffic_type_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$traffic_type_name	= mosGetParam( $_REQUEST, 'traffic_type_name', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$image   		= mosGetParam( $_REQUEST, 'new_image', '');
		$old_image   	= mosGetParam( $_REQUEST, 'old_image', '');
		
		
		
		
		if ($traffic_type_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($traffic_type_id == '0')
		{	
			if (checkDuplicate("tbl_traffic_type", array('traffic_type_name' => $traffic_type_name), "traffic_type_name",0,false,"language_id = '$languageid'"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_traffic_type", "priority", "");
			$sql = "insert into tbl_traffic_type (traffic_type_name, active, priority, language_id, image) values ('$traffic_type_name', $active, $priority, $languageid, '$img')";	
		} else
			{ 
			if (checkDuplicate("tbl_traffic_type", array('traffic_type_name' => $traffic_type_name), "traffic_type_name",0,false,"language_id = '$languageid' and traffic_type_id != $traffic_type_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_traffic_type set traffic_type_name ='$traffic_type_name',  active = $active, language_id=$languageid, image = '$img' where traffic_type_id = $traffic_type_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$traffic_type_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($traffic_type_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		
		$sql = "select image from tbl_traffic_type where traffic_type_id = $traffic_type_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )
		{	
			$img = $row['image'];	
		}
		$arrField = array("image");
		checkDeleteOldFile("", $img, 1, $root_path . "images/ads" , "tbl_traffic_type", $arrField, "traffic_type_id", $traffic_type_id);
		if(strtolower($_SESSION['membername'])=="administrator"){	
			deleteByID("tbl_traffic_type", "traffic_type_id", $traffic_type_id);
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
			'traffic_type_name' 	=>	mosGetParam( $_REQUEST, 'traffic_type_name', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'traffic_type_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'traffic_type' => 'ads/traffic_type_info.html')
		);
		
		$template->pparse('traffic_type');	
	}
?>