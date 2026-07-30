<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/server',
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
	$member_id	= mosGetParam( $_REQUEST, 'member_id1', '0' );
	$member_id	= (strtolower($_SESSION['membername'])!="administrator")?$_SESSION["login_id"]:$member_id;
	$cond = '';
	$cond = (strtolower($_SESSION['membername'])=="administrator")?' and active = 1':' and active = 1';
	$sql = "select * from tbl_member where 1 $cond order by member_id";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while( $row = $db->sql_fetchrow($result) ){
		$template->assign_block_vars('member_list', array(
			'member_id'	  =>	$row['member_id'],
			'member_name' =>	$row['fullname'],
		));
	}
	$cond    = '';
	$cond   .= ($member_id)?' and (tbl_server.member_id = '.$member_id.' or tbl_server.share_user_id like "%'.$member_id.'%")':'';
	$sql = "SELECT tbl_server.*, tbl_member.fullname FROM (tbl_server LEFT JOIN tbl_emails ON tbl_server.email_id = tbl_emails.`email_id`) LEFT JOIN tbl_member ON tbl_server.member_id = tbl_member.`member_id` where 1 $cond ORDER BY tbl_server.active DESC, tbl_server.priority";
    if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;$days = '';
            if ($row['exp_date']){
                $targetDate = new DateTime( $row['exp_date']);
                $today = new DateTime();
                $interval = $today->diff($targetDate);
// 4. Lấy tổng số ngày chênh lệch
// Sử dụng %r để hiển thị dấu âm nếu ngày mục tiêu đã qua, %a để lấy tổng số ngày
                $days = $interval->format('%r%a');
                if ($days <= 0)$bg_server = "gray";
                elseif ($days <30)$bg_server = "Orange";
                else $bg_server = "Green";
            }else $bg_server = "";
			$template->assign_block_vars('list', array(
				'className'	=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'		=>  $order,
				'server_id'	  =>	$row['server_id'],
				'server_name'    =>	$row['server_name'],
				'ip_server'	=>	$row['ip_server'],
				'order_date'	=>	$row['order_date'],
                'exp_date'		=>	$row['exp_date'],
                'bg_server'		=>	$bg_server,
                'days'			=>	($days)?"(<span style='color: red; font-weight: bold;'>$days</span>)":"",
				'price'		=>	number_format($row['price'], 0, ',', '.'),
				'url'  	 	  =>	$row['url'],
				'username'   	 =>	$row['username'],
				'pass'   		 =>	$row['pass'],
                'share_user_id'=>	$row['share_user_id'],
				'ghichu'   	   =>	$row['ghichu'],
				'member_name'   =>	$row['fullname'],
                'is_owner'      =>	($row['member_id'] == $member_id || strtolower($_SESSION['membername'])=="administrator")?"":"none",
				'active' 	   =>	($row['active'] == 1) ? '' : 'none',
				'up'		   =>	($order == 1) ? ' display: none;' : '',
				'down'		 =>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
	$template->assign_vars(array(
		'member_id'  => $member_id,
		'allow_member'	=>	(strtolower($_SESSION['membername'])=="administrator" || $_SESSION["login_id"] == 38)?'':'none'
	));
		$template->set_filenames_new(array(
			'share' => 'common_lists/server/server_list.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$server_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$cond = 'and active = 1';
		$sql = "select * from tbl_member where 1 $cond";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result) ){
			$template->assign_block_vars('member_list', array(
				'member_id'	  =>	$row['member_id'],
				'member_name'	=>	$row['fullname'],
			));
		}

		if ($server_id != 0)
		{	$sql = "select * from tbl_server where server_id = $server_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'server_id'		 =>	$server_id,
					'server_name'	   =>	$row['server_name'],
					'ip_server'	   =>	$row['ip_server'],
					'order_date'	  =>	$row['order_date'],
                    'exp_date'	  =>	$row['exp_date'],
					'price'		   =>	$row['price'],
					'url'	  		 =>	$row['url'],
					'username'	  	=>	$row['username'],
					'pass'		    =>	$row['pass'],
                    'share_user_id'=>	$row['share_user_id'],
					'ghichu'		  =>	$row['ghichu'],
					'email_id'		=>	($row['email_id'])?$row['email_id']:0,
					'member_id'	  =>	($row['member_id'])?$row['member_id']:0,
					'active'	      =>	($row['active'] == 1) ? 'checked' : '',
					'created_date'   => $row['created_date'],
					'created_by'	 => $row['created_by'],
					'last_modified'  => $row['last_modified'],
					'modified_by'	=> $row['modified_by'],
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'active'	=> 'checked' ,
				'allow'	 => 'hidden',
				'email_id'	=> '0',
				'member_id'  => '0',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'common_lists/server/server_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$server_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($server_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $server_id, $direction, "tbl_server", "server_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{
		global $db, $root_path, $skin, $languageid, $template;	
		$server_id 	   = mosGetParam( $_REQUEST, 'id', '0');
		$server_name	 = mosGetParam( $_REQUEST, 'server_name', '');
		$ip_server 	   = mosGetParam( $_REQUEST, 'ip_server', '');
		$order_date	= mosGetParam( $_REQUEST, 'order_date', '');
        $exp_date		= mosGetParam( $_REQUEST, 'exp_date', '');
		$price		= mosGetParam( $_REQUEST, 'price', '0');
		$url		   = mosGetParam( $_REQUEST, 'url', '');
		$username	  = mosGetParam( $_REQUEST, 'username', '');
		$pass		  = mosGetParam( $_REQUEST, 'pass', '');
        $share_user_id = mosGetParam( $_REQUEST, 'share_user_id', '');
		$ghichu		= mosGetParam( $_REQUEST, 'ghichu', '');
		$email_id		= mosGetParam( $_REQUEST, 'email_id', '0');
		$member_id	= mosGetParam( $_REQUEST, 'member_id', '0');
		$active		= mosGetParam( $_REQUEST, 'active', 0);
		
		if ($server_id == '')
		{	
			mosInvalidURL();
			exit;
		}
		if ($server_id == '0')
		{	
			/*if (checkDuplicate("tbl_server", array('server_name' => $server_name), "server_name",0,false,""))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}*/
			$priority = mosGetPriority("tbl_server", "priority", "");
			$sql = "insert into tbl_server (server_name, ip_server, url, username, pass, share_user_id, ghichu, active, priority, language_id, email_id, member_id, price, order_date, exp_date, created_date, last_modified, created_by, modified_by) values ('$server_name', '$ip_server', '$url', '$username', '$pass', '$share_user_id', '$ghichu', $active, $priority, $languageid, $email_id, $member_id, '$price', '$order_date', '$exp_date', now(), now(), '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "')";
		}else{
			$sql = "update tbl_server set server_name ='$server_name', ip_server = '$ip_server', price = '$price', order_date = '$order_date', exp_date = '$exp_date', url = '$url', username = '$username', pass = '$pass', share_user_id = '$share_user_id', ghichu = '$ghichu', active = $active, email_id = '$email_id', member_id = '$member_id', last_modified = now(), modified_by = '".$_SESSION['membername']."'  where server_id = '$server_id'";
		}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$server_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($server_id == 0)
		{	mosInvalidURL();
			exit;
		}	
    if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_server", "server_id", $server_id);
		}else
			{
				$template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
			}
		mosList();
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;				
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$template->assign_vars(array(
			'server_name' 	  =>	mosGetParam( $_REQUEST, 'server_name', ''),
			'ip_server' 	  =>	mosGetParam( $_REQUEST, 'ip_server', ''),
			'order_date'		=>	mosGetParam( $_REQUEST, 'order_date', ''),
            'exp_date'		=>	mosGetParam( $_REQUEST, 'exp_date', ''),
			'price'			=>	mosGetParam( $_REQUEST, 'price', '0'),
			'url' 	 		=>	mosGetParam( $_REQUEST, 'url', ''),
			'username' 	   =>	mosGetParam( $_REQUEST, 'username', ''),
			'pass' 		   =>	mosGetParam( $_REQUEST, 'pass', ''),
            'share_user_id' =>	mosGetParam( $_REQUEST, 'share_user_id', ''),
			'ghichu' 		 =>	mosGetParam( $_REQUEST, 'ghichu', ''),		
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'server_id'	    =>	$id,
		));
		$template->set_filenames_new(array(
			'server' => 'common_lists/server/server_info.html')
		);
		
		$template->pparse('server');
	}
?>