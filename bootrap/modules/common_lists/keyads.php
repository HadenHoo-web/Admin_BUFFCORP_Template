<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/keyads',
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
	$member_id      = mosGetParam( $_REQUEST, 'member_id1', '0' );
  	$website_id     = mosGetParam( $_REQUEST, 'website_id', '0' );
	$traffic_tye_id     = mosGetParam( $_REQUEST, 'traffic_type_id', '0' );
	$active			= mosGetParam( $_REQUEST, 'active1', '0' );
	$issave			= mosGetParam( $_REQUEST, 'issave1', '0' );
	$isstop			= mosGetParam( $_REQUEST, 'isstop1', '0' );
	$cond = "";
	//$cond .= ($member_id)?' and tbl_keyads.member_id = '.$member_id:'';
  	$cond .= ($website_id)?' and tbl_keyads.website_id = '.$website_id:'';
	$cond .= ($active == 1)?' and tbl_keyads.active = 1':'';
	$cond .= ($issave == 1)?' and tbl_keyads.issave = 1':'';
	$cond .= ($isstop == 1)?' and tbl_keyads.isstop = 1':'';
	$cond .= (strtolower($_SESSION['membername'])=="administrator" || strtolower($_SESSION['loginname'])=="tramanh.buffseo@gmail.com")?'':' and tbl_keyads.member_id = '.$_SESSION["login_id"];
	$sql = "SELECT tbl_keyads.*, tbl_traffic_type.traffic_type_name, tbl_camp_traffic.camp_traffic_name, tbl_camp_traffic.pass as slug, tbl_member.`fullname`, `tbl_website`.`website_name` FROM (((tbl_keyads LEFT JOIN tbl_camp_traffic ON tbl_keyads.camp_traffic_id = tbl_camp_traffic.`camp_traffic_id`) LEFT JOIN tbl_member ON tbl_keyads.`member_id` = tbl_member.`member_id`) LEFT JOIN `tbl_website` ON tbl_keyads.`website_id` = `tbl_website`.`website_id`) left join tbl_traffic_type on tbl_camp_traffic.traffic_type_id = tbl_traffic_type.traffic_type_id WHERE 1 $cond ORDER BY tbl_website.website_id, tbl_keyads.camp_traffic_id, tbl_keyads.priority";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order = 0;
	$tam = "";
	while( $row = $db->sql_fetchrow($result) ){	
		$order = $order + 1;
		$sort1 = ($row['sort1'])?'<a href="'.$row['sort1'].'" target="_blank">sort1</a>':'';
		$sort1 = ($row['check_sort1'])?$sort1:"<strike>".$sort1."</strike>";
		$sort2 = ($row['sort2'])?'<a href="'.$row['sort2'].'" target="_blank">sort2</a>':'';
		$sort2 = ($row['check_sort2'])?$sort2:"<strike>".$sort2."</strike>";
		$get1  = ($row['get1'])?'<a href="'.$row['get1'].'" target="_blank">get1</a>':'';
		$get1  = ($row['check_get1'])?$get1:"<strike>".$get1."</strike>";
		$get2  = ($row['get2'])?'<a href="'.$row['get2'].'" target="_blank">get2</a>':'';
		$get2  = ($row['check_get2'])?$get2:"<strike>".$get2."</strike>";
		$linkdown1 = ($row['linkdown1'])?'<a href="'.$row['linkdown1'].'" target="_blank">linkdown1</a>':'';
		$linkdown1 = ($row['check_linkdown1'])?$linkdown1:"<strike>".$linkdown1."</strike>";
		$linkdown2 = ($row['linkdown2'])?'<a href="'.$row['linkdown2'].'" target="_blank">linkdown2</a>':'';
		$linkdown2 = ($row['check_linkdown2'])?$linkdown2:"<strike>".$linkdown2."</strike>";	
		$us1  = ($row['us1'])?'<a href="'.$row['us1'].'" target="_blank">us1</a>':'';
		$us1  = ($row['check_us1'])?$us1:"<strike>".$us1."</strike>";
		$us2  = ($row['us2'])?'<a href="'.$row['us2'].'" target="_blank">us2</a>':'';
		$us2  = ($row['check_us2'])?$us2:"<strike>".$us2."</strike>";				
		$template->assign_block_vars('list', array(
			'className'		=>  ($order % 2 == 1) ? 'alt' : 'inv',
			'order'			=>  $order,
			'keyads_id'	 	=>	$row['keyads_id'],
			'keyads_name'   =>	$row['keyads_name'],
			'pass'   		=>	$row['pass'],
			'sort1'			=>	$sort1,
			'sort2'			=>	$sort2,
			'get1'			=>	$get1,
			'get2'			=>	$get2,
			'linkdown1'		=>	$linkdown1,
			'linkdown2'		=>	$linkdown2,
			'us1'			=>	$us1,
			'us2'			=>	$us2,
			'ghichu'   	   	=>	$row['ghichu'],
			'fullname'		=>	$row['fullname'],
			'website_name'	=>	($row['website_name'] == $tam)?"":$row['website_name'],
			'camp_traffic_name'		=>	$row['camp_traffic_name'],
			'slug'			=>	$row['slug'],
			'traffic_type_name'		=>	$row['traffic_type_name'],
			'active' 	   	=>	($row['active'] == 1) ? '' : 'none',
			'issave' 	   	=>	($row['issave'] == 1) ? '' : 'none',
			'isstop' 	   	=>	($row['isstop'] == 1) ? 'red' : '',
			'check_sort1' 	=>	($row['check_sort1'] == 1) ? '' : 'none',
			'check_sort2' 	=>	($row['check_sort2'] == 1) ? '' : 'none',
			'check_get1' 	=>	($row['check_get1'] == 1) ? '' : 'none',
			'check_get2' 	=>	($row['check_get2'] == 1) ? '' : 'none',
			'check_linkdown1'	=>	($row['check_linkdown1'] == 1) ? '' : 'none',
			'check_linkdown2'	=>	($row['check_linkdown2'] == 1) ? '' : 'none',
			'check_us1' 	=>	($row['check_us1'] == 1) ? '' : 'none',
			'check_us2' 	=>	($row['check_us2'] == 1) ? '' : 'none',
			'up'		   	=>	($order == 1) ? ' display: none;' : '',
			'down'		 	=>	($order == $num_row) ? ' display: none;' : '',		
		));	
		$tam = $row['website_name'];
	} 
    $sql = "SELECT * FROM tbl_website WHERE website_id IN (SELECT website_id FROM tbl_keyads GROUP BY website_id)";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while ( $row = $db->sql_fetchrow($result) ){
		$template->assign_block_vars('website_list', array(
			'website_id'	   =>	$row['website_id'],
			'website_name'	 =>	$row['website_name'],
		));
	}
	$template->assign_vars(array(
    	'member_id'		=>	$member_id,
    	'website_id'	=>	$website_id,
    	'active'		=>	($active == 1) ? 'checked' : '',
		'isstop'		=>	($isstop == 1) ? 'checked' : '',
  	));
	$template->set_filenames_new(array(
		'share' => 'common_lists/keyads/keyads_list.html'
	));
	$template->pparse('share');
}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo(){	
	global $db, $root_path, $skin, $languageid, $template;
	$keyads_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
	//$cond = 'and active = 1';
    $cond = (strtolower($_SESSION['membername'])=="administrator")?'':' and active = 1';
    $sql = "select * from tbl_website where 1 $cond and website_type_id = 2 order by website_name";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while ( $row = $db->sql_fetchrow($result) ){
		$template->assign_block_vars('website_list', array(
			'website_id'	   =>	$row['website_id'],
			'website_name'	 =>	$row['website_name'],
		));
	}
	$cond = (strtolower($_SESSION['membername'])=="administrator")?'':' and tbl_camp_traffic.active = 1';
	$sql = "select * from tbl_camp_traffic left join tbl_traffic_type on tbl_camp_traffic.traffic_type_id = tbl_traffic_type.traffic_type_id where 1 $cond order by tbl_camp_traffic.camp_traffic_name";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while ( $row = $db->sql_fetchrow($result) ){
		$template->assign_block_vars('camp_traffic_list', array(
			'camp_traffic_id'	   =>	$row['camp_traffic_id'],
			'camp_traffic_name'	 =>	$row['camp_traffic_name'],
			'traffic_type_name'		=>	$row['traffic_type_name'],
		));
	}
	if ($keyads_id != 0){	
		$sql = "select * from tbl_keyads where keyads_id = $keyads_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) ){	
			$template->assign_vars(array(
				'keyads_id'		=>	$keyads_id,
				'keyads_name'	=>	$row['keyads_name'],
				'sort1'			=>	$row['sort1'],
				'sort2'			=>	$row['sort2'],
				'get1'			=>	$row['get1'],
				'get2'			=>	$row['get2'],
				'linkdown1'		=>	$row['linkdown1'],
				'linkdown2'		=>	$row['linkdown2'],
				'us1'			=>	$row['us1'],
				'us2'			=>	$row['us2'],
				'pass'		  	=>	$row['pass'],
				'ghichu'		=>	$row['ghichu'],
				'camp_traffic_id'		=>	($row['camp_traffic_id'])?$row['camp_traffic_id']:0,
				'website_id'	=>	($row['website_id'])?$row['website_id']:0,
				'active'	    =>	($row['active'] == 1) ? 'checked' : '',
				'issave'	    =>	($row['issave'] == 1) ? 'checked' : '',
				'isstop'	    =>	($row['isstop'] == 1) ? 'checked' : '',
				'check_sort1'	=>	($row['check_sort1'] == 1) ? 'checked' : '',
				'check_sort2'	=>	($row['check_sort2'] == 1) ? 'checked' : '',
				'check_get1'	=>	($row['check_get1'] == 1) ? 'checked' : '',
				'check_get2'	=>	($row['check_get2'] == 1) ? 'checked' : '',
				'check_linkdown1'	=>	($row['check_linkdown1'] == 1) ? 'checked' : '',
				'check_linkdown2'	=>	($row['check_linkdown2'] == 1) ? 'checked' : '',
				'check_us1'		=>	($row['check_us1'] == 1) ? 'checked' : '',
				'check_us2'		=>	($row['check_us2'] == 1) ? 'checked' : '',
				'created_date'  => $row['created_date'],
				'created_by'	=> $row['created_by'],
				'last_modified' => $row['last_modified'],
				'modified_by'	=> $row['modified_by'],
			));
		}else message_die( ID_NOTFOUND );		
	}else{			
		$template->assign_vars(array(
			'active'	=> 'checked' ,
			'issave'	=> '' ,
			'isstop'	=> '' ,
			'allow'	 	=> 'hidden',
			'camp_traffic_id'	=> '0',
			'website_id'=> '0',
		));
	}
	$template->set_filenames_new(array(
		'share' => 'common_lists/keyads/keyads_info.html'
	));
	$template->pparse('share');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction ){	
	global $languageid;
	$keyads_id    = mosGetParam( $_REQUEST, 'id', '');
	if ($keyads_id == 0){	
		mosInvalidURL();
		exit;
	}
	mosChangePriority( $keyads_id, $direction, "tbl_keyads", "keyads_id", "priority");
	mosList();
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave(){		
	global $db, $root_path, $skin, $languageid, $template;	
	$keyads_id 	  	= mosGetParam( $_REQUEST, 'id', '0');
	$keyads_name	= mosGetParam( $_REQUEST, 'keyads_name', '');
	$pass		  	= mosGetParam( $_REQUEST, 'pass', '');
	$sort1		  	= mosGetParam( $_REQUEST, 'sort1', '');
	$sort2		  	= mosGetParam( $_REQUEST, 'sort2', '');
	$get1		  	= mosGetParam( $_REQUEST, 'get1', '');
	$get2		  	= mosGetParam( $_REQUEST, 'get2', '');
	$linkdown1		= mosGetParam( $_REQUEST, 'linkdown1', '');
	$linkdown2		= mosGetParam( $_REQUEST, 'linkdown2', '');
	$us1		  	= mosGetParam( $_REQUEST, 'us1', '');
	$us2		  	= mosGetParam( $_REQUEST, 'us2', '');
	$ghichu			= mosGetParam( $_REQUEST, 'ghichu', '');
	$camp_traffic_id			= mosGetParam( $_REQUEST, 'camp_traffic_id', '');
	$website_id		= mosGetParam( $_REQUEST, 'website_id', '');
	$active			= mosGetParam( $_REQUEST, 'active', 0);
	$issave			= mosGetParam( $_REQUEST, 'issave', 0);
	$isstop			= mosGetParam( $_REQUEST, 'isstop', 0);
	$check_sort1	= mosGetParam( $_REQUEST, 'check_sort1', 0);
	$check_sort2	= mosGetParam( $_REQUEST, 'check_sort2', 0);
	$check_get1		= mosGetParam( $_REQUEST, 'check_get1', 0);
	$check_get2		= mosGetParam( $_REQUEST, 'check_get2', 0);
	$check_linkdown1	= mosGetParam( $_REQUEST, 'check_linkdown1', 0);
	$check_linkdown2	= mosGetParam( $_REQUEST, 'check_linkdown2', 0);
	$check_us1		= mosGetParam( $_REQUEST, 'check_us1', 0);
	$check_us2		= mosGetParam( $_REQUEST, 'check_us2', 0);
	$member_id		= mosGetParam( $_REQUEST, 'member_id', 0);
	if ($keyads_id == ''){	
		mosInvalidURL();
		exit;
	}	
	if ($keyads_id == '0'){	
		$priority = mosGetPriority("tbl_keyads", "priority", "");
		$sql = "insert into tbl_keyads (keyads_name, pass, sort1, sort2, get1, get2, linkdown1, linkdown2, us1, us2, ghichu, active, issave, isstop, check_sort1, check_sort2, check_get1, check_get2, check_linkdown1, check_linkdown2, check_us1, check_us2, priority, language_id, camp_traffic_id, website_id, created_date, last_modified, created_by, modified_by, member_id) values ('$keyads_name', '$pass', '$sort1', '$sort2', '$get1', '$get2', '$linkdown1', '$linkdown2', '$us1', '$us2', '$ghichu', $active, $issave, $isstop, $check_sort1, $check_sort2, $check_get1, $check_get2, $check_linkdown1, $check_linkdown2, $check_us1, $check_us2, $priority, $languageid, $camp_traffic_id, $website_id, now(), now(), '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "', '".$_SESSION["login_id"]."')";	
	}else{ 
		$sql = "update tbl_keyads set keyads_name ='$keyads_name', pass = '$pass', sort1 = '$sort1', sort2 = '$sort2', get1 = '$get1', get2 = '$get2', us1 = '$us1', us2 = '$us2', linkdown1 = '$linkdown1', linkdown2 = '$linkdown2', ghichu = '$ghichu', active = $active, issave = $issave, isstop = $isstop, check_sort1 = '$check_sort1', check_sort2 = '$check_sort2', check_get1 = '$check_get1', check_get2 = '$check_get2', check_linkdown1 = '$check_linkdown1', check_linkdown2 = '$check_linkdown2', check_us1 = '$check_us1', check_us2 = '$check_us2', camp_traffic_id = '$camp_traffic_id', website_id = '$website_id', last_modified = now(), modified_by = '".$_SESSION['membername']."'  where keyads_id = $keyads_id";
	}
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
	mosList();
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete(){	
	global $template, $db;	
	$keyads_id = mosGetParam( $_REQUEST, 'id', '0');
	if ($keyads_id == 0){	
		mosInvalidURL();
		exit;
	}	
    if(strtolower($_SESSION['membername'])=="administrator"){	
		deleteByID("tbl_keyads", "keyads_id", $keyads_id);
	}else{
		$template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
	}
	mosList();
}
//----------------------------------------------------------------------------------------------------------------------------------------	
function reShowPage( $message ){	
	global $db, $root_path, $skin, $languageid, $template, $theme;				
	$id   	= mosGetParam( $_REQUEST, 'id', '0');
	$template->assign_vars(array(
		'keyads_name' 	 	=>	mosGetParam( $_REQUEST, 'keyads_name', ''),
		'pass' 		   		=>	mosGetParam( $_REQUEST, 'pass', ''),	
		'sort1' 		   	=>	mosGetParam( $_REQUEST, 'sort1', ''),
		'sort2' 		   	=>	mosGetParam( $_REQUEST, 'sort2', ''),
		'get1' 		   		=>	mosGetParam( $_REQUEST, 'get1', ''),
		'get2' 		   		=>	mosGetParam( $_REQUEST, 'get2', ''),
		'linkdown1' 		=>	mosGetParam( $_REQUEST, 'linkdown1', ''),
		'linkdown2' 		=>	mosGetParam( $_REQUEST, 'linkdown2', ''),
		'us1' 		   		=>	mosGetParam( $_REQUEST, 'us1', ''),
		'us2' 		   		=>	mosGetParam( $_REQUEST, 'us2', ''),
		'ghichu' 		 	=>	mosGetParam( $_REQUEST, 'ghichu', ''),		
		'MESSAGE'			=>	DUPLICATE_ENTRY,
		'keyads_id'	   		=>	$id,
	));
	$template->set_filenames_new(array(
		'keyads' => 'common_lists/keyads/keyads_info.html'
	));
	$template->pparse('keyads');	
}
?>