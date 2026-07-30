<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'ads/camp_traffic',
		'LANGUAGEID'=> $languageid,	
	));		
	switch( $action ){	
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
	$sql = "SELECT tbl_camp_traffic.*, tbl_traffic_type.traffic_type_name FROM tbl_camp_traffic LEFT JOIN tbl_traffic_type ON tbl_camp_traffic.traffic_type_id = tbl_traffic_type.`traffic_type_id` ORDER BY tbl_camp_traffic.sort1, tbl_camp_traffic.volum DESC, tbl_camp_traffic.camp_traffic_name";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order = 0;
	while( $row = $db->sql_fetchrow($result) ){	
		$order = $order + 1;
		$sort1 = ($row['sort1'])?'<a href="'.$row['sort1'].'" target="_blank">Sort 1</a>':'';
		$sort2 = ($row['sort2'])?'<a href="'.$row['sort2'].'" target="_blank">Sort 2</a>':'';
		$linkus = ($row['linkus'])?'<a href="'.$row['linkus'].'" target="_blank">LinkUs 1</a>':'';
		$linkus2 = ($row['linkus2'])?'<a href="'.$row['linkus2'].'" target="_blank">LinkUs 2</a>':'';
		$linkdown = ($row['linkdown'])?'<a href="'.$row['linkdown'].'" target="_blank">Down 1</a>':'';	
		$linkdown2 = ($row['linkdown2'])?'<a href="'.$row['linkdown2'].'" target="_blank">Down 2</a>':'';				
		$template->assign_block_vars('list', array(
			'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
			'order'				=>  $order,
			'camp_traffic_id'	=>	$row['camp_traffic_id'],
			'camp_traffic_name' =>	$row['camp_traffic_name'],
			'pass'   		 	=>	$row['pass'],
			'volum'   		 	=>	$row['volum'],
			'sort1'   		 	=>	$sort1,
			'sort2'   		 	=>	$sort2,
			'linkus'   		 	=>	$linkus,
			'linkus2'   		=>	$linkus2,
			'linkdown'   		=>	$linkdown,
			'linkdown2'   		=>	$linkdown2,
			'ghichu'   	   		=>	$row['ghichu'],
			'traffic_type_name'	=>	$row['traffic_type_name'],
			'active' 	   		=>	($row['active'] == 1) ? '' : 'none',
			'up'		   		=>	($order == 1) ? ' display: none;' : '',
			'down'		 		=>	($order == $num_row) ? ' display: none;' : '',		
		));	
	}
	$template->set_filenames_new(array(
		'share' => 'ads/camp_traffic_list.html')
	);
	$template->pparse('share');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo(){	
	global $db, $root_path, $skin, $languageid, $template;
	$camp_traffic_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
	if ($camp_traffic_id != 0){	
		$sql = "select * from tbl_camp_traffic where camp_traffic_id = $camp_traffic_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) ){	
			$template->assign_vars(array(
				'camp_traffic_id'	=>	$camp_traffic_id,
				'camp_traffic_name'	=>	$row['camp_traffic_name'],
				'pass'		  		=>	$row['pass'],
				'volum'		  		=>	$row['volum'],
				'sort1'		  		=>	$row['sort1'],
				'sort2'		  		=>	$row['sort2'],
				'linkus'		  	=>	$row['linkus'],
				'linkus2'		  	=>	$row['linkus2'],
				'linkdown'		  	=>	$row['linkdown'],
				'linkdown2'		  	=>	$row['linkdown2'],
				'ghichu'			=>	$row['ghichu'],
				'traffic_type_id'	=>	($row['traffic_type_id'])?$row['traffic_type_id']:0,
				'active'	    	=>	($row['active'] == 1) ? 'checked' : '',
				'created_date'  => $row['created_date'],
				'created_by'	=> $row['created_by'],
				'last_modified' => $row['last_modified'],
				'modified_by'	=> $row['modified_by'],
			));
		}else message_die( ID_NOTFOUND );		
	}else{			
		$template->assign_vars(array(
			'active'			=> 'checked' ,
			'allow'	 			=> 'hidden',
			'traffic_type_id'	=> '0',
		));
	}
	$template->set_filenames_new(array(
		'share' => 'ads/camp_traffic_info.html')
	);
	$template->pparse('share');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$camp_traffic_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($camp_traffic_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $camp_traffic_id, $direction, "tbl_camp_traffic", "camp_traffic_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave(){		
	global $db, $root_path, $skin, $languageid, $template;	
	$camp_traffic_id 	= mosGetParam( $_REQUEST, 'id', '0');
	$camp_traffic_name	= mosGetParam( $_REQUEST, 'camp_traffic_name', '');
	$pass		  		= mosGetParam( $_REQUEST, 'pass', '');
	$volum		  		= mosGetParam( $_REQUEST, 'volum', '');
	$sort1		  		= mosGetParam( $_REQUEST, 'sort1', '');
	$sort2		  		= mosGetParam( $_REQUEST, 'sort2', '');
	$linkus		  		= mosGetParam( $_REQUEST, 'linkus', '');
	$linkus2		  	= mosGetParam( $_REQUEST, 'linkus2', '');
	$linkdown		  	= mosGetParam( $_REQUEST, 'linkdown', '');
	$linkdown2		  	= mosGetParam( $_REQUEST, 'linkdown2', '');
	$ghichu				= mosGetParam( $_REQUEST, 'ghichu', '');
	$traffic_type_id	= mosGetParam( $_REQUEST, 'traffic_type_id', '');
	$active				= mosGetParam( $_REQUEST, 'active', 0);
	if ($camp_traffic_id == ''){	
		mosInvalidURL();
		exit;
	}	
	if ($camp_traffic_id == '0'){	
		if (checkDuplicate("tbl_camp_traffic", array('camp_traffic_name' => $camp_traffic_name), "camp_traffic_name",0,false,"")){	
			reShowPage( DUPLICATE_ENTRY );
			exit;
		}
		$priority = mosGetPriority("tbl_camp_traffic", "priority", "");
		$sql = "insert into tbl_camp_traffic (camp_traffic_name, pass, volum, sort1, sort2, linkus, linkus2, linkdown, linkdown2, ghichu, active, priority, language_id, traffic_type_id, created_date, last_modified, created_by, modified_by) values ('$camp_traffic_name', '$pass', '$volum', '$sort1', '$sort2', '$linkus', '$linkus2', '$linkdown', '$linkdown2', '$ghichu', $active, $priority, $languageid, $traffic_type_id, now(), now(), '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "')";	
	}else{ 
		if (checkDuplicate("tbl_camp_traffic", array('camp_traffic_name' => $camp_traffic_name), "camp_traffic_name",0,false,"camp_traffic_id != $camp_traffic_id")){	
			reShowPage( DUPLICATE_ENTRY );
			exit;
		}
		$sql = "update tbl_camp_traffic set camp_traffic_name ='$camp_traffic_name', pass = '$pass', volum = '$volum', sort1 = '$sort1', sort2 = '$sort2', linkus = '$linkus', linkus2 = '$linkus2', linkdown = '$linkdown', linkdown2 = '$linkdown2', ghichu = '$ghichu', active = $active, traffic_type_id = '$traffic_type_id', last_modified = now(), modified_by = '".$_SESSION['membername']."' where camp_traffic_id = $camp_traffic_id";
	}
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
	mosList();
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$camp_traffic_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($camp_traffic_id == 0)
		{	mosInvalidURL();
			exit;
		}	
  if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_camp_traffic", "camp_traffic_id", $camp_traffic_id);
		}else
			{
				$template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
			}
		mosList();
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
function reShowPage( $message ){	
	global $db, $root_path, $skin, $languageid, $template, $theme;				
	$id   	= mosGetParam( $_REQUEST, 'id', '0');
	$template->assign_vars(array(
		'camp_traffic_name' =>	mosGetParam( $_REQUEST, 'camp_traffic_name', ''),
		'pass' 		   		=>	mosGetParam( $_REQUEST, 'pass', ''),
		'volum' 		   	=>	mosGetParam( $_REQUEST, 'volum', ''),
		'sort1' 		   	=>	mosGetParam( $_REQUEST, 'sort1', ''),
		'sort2' 		   	=>	mosGetParam( $_REQUEST, 'sort2', ''),
		'linkus' 		   	=>	mosGetParam( $_REQUEST, 'linkus', ''),
		'linkus2' 		   	=>	mosGetParam( $_REQUEST, 'linkus2', ''),		
		'linkdown' 		   	=>	mosGetParam( $_REQUEST, 'linkdown', ''),
		'linkdown2' 		=>	mosGetParam( $_REQUEST, 'linkdown2', ''),
		'ghichu' 		 	=>	mosGetParam( $_REQUEST, 'ghichu', ''),		
		'MESSAGE'			=>	DUPLICATE_ENTRY,
		'camp_traffic_id'	=>	$id,
	));
	$template->set_filenames_new(array(
		'camp_traffic' => 'ads/camp_traffic_info.html')
	);
	$template->pparse('camp_traffic');	
}
?>