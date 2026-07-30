<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/xacnhan',
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
		case 'exe'	:	mosThuchien(); break;
		case 'thongke'	:	mosThongKe(); break;
	
		default:
			mosInvalidURL();
			exit;
	}
function mosList($id)
{	
  global $db, $root_path, $skin, $languageid, $template;
  $parent_id      = mosGetParam( $_REQUEST, 'parent_id', 0 );
  $parent_id      = ($parent_id==0)?$id:$parent_id;
  $member_id      = mosGetParam( $_REQUEST, 'member_id1', '0' );
  $website_id     = mosGetParam( $_REQUEST, 'website_id1', '0' );
  //if($member_id == "")$member_id = $_SESSION["login_id"];
  $created_by_id  = mosGetParam( $_REQUEST, 'created_by_id1', 0 );
  $active				  = mosGetParam( $_REQUEST, 'active1', '0' );
  
  switch( $_SESSION["login_id"] )
	{	
		case '1'	:	$cond = ""; break;
    case '39'	:	$cond = "and (tbl_xacnhan.created_by = '".$_SESSION['membername']."' OR tbl_xacnhan.member_id not in ('1'))"; break;
		default:
			$cond = " and (tbl_xacnhan.created_by = '".$_SESSION['membername']."' OR tbl_xacnhan.member_id = '".$_SESSION["login_id"]."')";
	} 
  $cond = "";
  
  //$cond = (strtolower($_SESSION['membername'])=="administrator" or strtolower($_SESSION['loginname'])=="tho")?"":" and (tbl_xacnhan.created_by = '".$_SESSION['membername']."' OR tbl_xacnhan.member_id = '".$_SESSION["login_id"]."')";
  //$cond = (strtolower($_SESSION['membername'])=="administrator" or strtolower($_SESSION['loginname'])=="tho")?"":((strtolower($_SESSION['loginname'])=="huy")?"and (tbl_xacnhan.created_by = '".$_SESSION['membername']."' OR tbl_xacnhan.member_id in ('".$_SESSION["login_id"]."','5','30','31','32'))":" and (tbl_xacnhan.created_by = '".$_SESSION['membername']."' OR tbl_xacnhan.member_id = '".$_SESSION["login_id"]."')");
  
  $cond .= ($member_id)?' and tbl_xacnhan.member_id = '.$member_id:'';
  $cond .= ($website_id)?' and tbl_xacnhan.website_id = '.$website_id:'';
  $cond .= ($created_by_id)?' and tbl_xacnhan.created_by_id = '.$created_by_id:'';
  $cond .= ($active == 0)?' and tbl_xacnhan.active = 1':'';
  
			$sql = "select tbl_xacnhan.*, SUBSTRING(tbl_xacnhan.ngay, 7, 4) as y, SUBSTRING(tbl_xacnhan.ngay, 4, 2) as m , SUBSTRING(tbl_xacnhan.ngay, 1, 2) as d,tbl_member.fullname, tbl_website.website_name from (tbl_xacnhan left join tbl_member on tbl_xacnhan.member_id = tbl_member.member_id) left join tbl_website on tbl_xacnhan.website_id = tbl_website.website_id where 1 $cond order by y DESC, m DESC, d DESC, soluong, xacnhan_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			$num_row = $db->sql_numrows($result);
			$order = 0;$today = date("Y-m-d");
			while( $row = $db->sql_fetchrow($result) )
			{   $order +=1;	
        $xacnhan_id	= $row['xacnhan_id'];
        $n = $row['ngay'];
        $ngay_tam = substr($n, 6, 4)."-".substr($n, 3, 2)."-".substr($n, 0, 2);
        if (strtotime($today) > strtotime($ngay_tam) and $row['soluong'] != 2) {
          $war = ' ⚠️';
        }else $war = '';
				$template->assign_block_vars('list', array(
					'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
					'order'				  =>  $order,
					'xacnhan_id'	  =>	$row['xacnhan_id'],
					'xacnhan_name'	=>	$row['xacnhan_name'],
          'ngay'		      =>	$row['ngay'].$war,
					'chitiet'		    =>	$row['chitiet'],
					'soluong'		    =>	($row['soluong'] ==0 )?"Chưa Thực Hiện":(($row['soluong'] ==1 )?"<font color='orange'><b>Đang Thực Hiện</b></font>":"<font color='blue'><b>Đã Xong</b></font>"),
					'active' 		    =>	($row['active'] == 1) ? '✔️' : '',
					'up'			      =>	($order == 1) ? ' display: none;' : '',
					'down'			    =>	($order == $num_row) ? ' display: none;' : '',
					'created_by'	  =>	$row['created_by'],
					'created_date'  =>	$row['created_date'],
					'modified_by'   =>	$row['modified_by'],
					'last_modified' =>	$row['last_modified'],
          'member_name'   =>  $row['fullname'],
          'website_name'  =>  $row['website_name'],
				));	
			}

			$sql = "select * from tbl_xacnhan where xacnhan_id=$parent_id";
			if( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_vars(array(
					'parent_id'	     =>	$row['xacnhan_id'],
					'xacnhan_name'	 =>	$row['xacnhan_name'],
					'chitiet'		     =>	$row['chitiet'],
					'soluong'		     =>	$row['soluong'],
          'isthongke'      => (strtolower($_SESSION['membername'])=="administrator")?"":"none",
				));
			}
  
  $cond = (strtolower($_SESSION['membername'])=="administrator")?'':' and active = 1'; 
    $sql = "select * from tbl_website where 1 $cond order by website_name";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_block_vars('website_list', array(
				'website_id'	   =>	$row['website_id'],
				'website_name'	 =>	$row['website_name'],
			));
		}
  
  $template->assign_vars(array(
    'member_id'		   =>	$member_id,
    'website_id'		 =>	$website_id,
    'created_by_id'	 =>	$created_by_id,
    'active'		     =>	($active == 1) ? 'checked' : '',
  ));
		$template->set_filenames_new(array(
			'share' => 'common_lists/xacnhan/xacnhan_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$xacnhan_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$parent_id	 = mosGetParam( $_REQUEST, 'parent_id', 0 );	
  
    $cond = 'and active = 1';
    $sql = "select * from tbl_member where 1 $cond";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_block_vars('member_list', array(
				'member_id'	  =>	$row['member_id'],
				'member_name'	=>	$row['fullname'],
			));
		}
  
    //$cond = 'and active = 1';
    $cond = (strtolower($_SESSION['membername'])=="administrator")?'':' and active = 1'; 
    $sql = "select * from tbl_website where 1 $cond order by website_name";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_block_vars('website_list', array(
				'website_id'	   =>	$row['website_id'],
				'website_name'	 =>	$row['website_name'],
			));
		}
		
		$sql = "select * from tbl_xacnhan where xacnhan_id=$parent_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_vars(array(
				'parent_id'		=>	$row['xacnhan_id'],
				'parent_name'	=>	$row['xacnhan_name'],
				'chitiet'		=>	$row['chitiet'],
				'soluong'		=>	$row['soluong'],
			));
		} 

		if ($xacnhan_id != 0)
		{	$sql = "select * from tbl_xacnhan where xacnhan_id = $xacnhan_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'xacnhan_id'=>	$xacnhan_id,
					'xacnhan_name'	=>	$row['xacnhan_name'],
          'ngay'		  =>	$row['ngay'],
					'chitiet'		=>	$row['chitiet'],
					'soluong'		=>	$row['soluong'],
					'parent_id' =>	$row['parent_id'],
					'active'		=>	($row['active'] == 1) ? 'checked' : '',
          'member_id'		=>	$row['member_id'],
          'website_id'	=>	$row['website_id'],
          'created_date' 	=> $row['created_date'],
					'created_by'	=> $row['created_by'],
					'last_modified'	=> $row['last_modified'],
					'modified_by'	=> $row['modified_by'],
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'active'		=>	'checked' ,
				'allow'     => 'hidden',
        'member_id' => 0,
        'website_id' => 0,
        'soluong'    => 0,
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'common_lists/xacnhan/xacnhan_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$xacnhan_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($xacnhan_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $xacnhan_id, $direction, "tbl_xacnhan", "xacnhan_id", "priority");
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$xacnhan_id 	  = mosGetParam( $_REQUEST, 'id', '0');
		$parent_id 	    = mosGetParam( $_REQUEST, 'parent_id', '0');
		$xacnhan_name	= mosGetParam( $_REQUEST, 'xacnhan_name', '');
    $ngay	          = mosGetParam( $_REQUEST, 'ngay', '');
		$chitiet		    = mosGetParam( $_REQUEST, 'chitiet', '', 0x0003);
		$soluong	      = mosGetParam( $_REQUEST, 'soluong', 0);
		$active			    = mosGetParam( $_REQUEST, 'active', 0);
    $member_id			= mosGetParam( $_REQUEST, 'member_id', 0);
    $website_id			= mosGetParam( $_REQUEST, 'website_id', 0);
  
		
		if ($xacnhan_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($xacnhan_id == '0')
		{	
			$priority = mosGetPriority("tbl_xacnhan", "priority", "");
			$sql = "insert into tbl_xacnhan (parent_id, xacnhan_name, ngay, chitiet, soluong, active, priority, language_id, created_date, last_modified, created_by, modified_by, member_id, website_id, created_by_id) values ($parent_id, '$xacnhan_name', '$ngay', '$chitiet', '$soluong', $active, $priority, $languageid, now(), now(), '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "', '$member_id', '$website_id', '" . $_SESSION["login_id"] . "')";	
		} else
			{ 
			$sql = "update tbl_xacnhan set xacnhan_name ='$xacnhan_name', ngay = '$ngay', chitiet = '$chitiet', soluong = '$soluong',  active = $active, language_id=$languageid , last_modified = now(), modified_by = '".$_SESSION['membername']."', member_id = '$member_id', website_id = '$website_id' where xacnhan_id = $xacnhan_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList($parent_id);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$xacnhan_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($xacnhan_id == 0)
		{	mosInvalidURL();
			exit;
		}
		$sql = "select * from tbl_xacnhan where xacnhan_id = '$xacnhan_id'";
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result))
			$parent_id = $row['parent_id'];
		$sql1 = "select count(*) as child_count from tbl_xacnhan where parent_id = '$xacnhan_id'";
		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		if ( $row1 = $db->sql_fetchrow($result1))		
		{	if (($row1['child_count'] == 0) and strtolower($_SESSION['membername'])=="administrator")
			{	deleteByID("tbl_xacnhan", "xacnhan_id", $xacnhan_id);
				$template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
			} else					
			{	$template->assign_vars(array('MESSAGE' => NONE_EMPTY_ERROR));	}
		} 
		
		mosList($parent_id);
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;				
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$template->assign_vars(array(
			'xacnhan_name'  =>	mosGetParam( $_REQUEST, 'xacnhan_name', ''),
      'ngay' 	         =>	mosGetParam( $_REQUEST, 'ngay', ''),
			'chitiet'		     =>	mosGetParam( $_REQUEST, 'chitiet', ''),
			'soluong'		     =>	mosGetParam( $_REQUEST, 'soluong', 0),			
			'MESSAGE'			   =>	DUPLICATE_ENTRY,
			'xacnhan_id'	   =>	$id,
      'member_id'		   =>	mosGetParam( $_REQUEST, 'member_id', 0),
      'website_id'		 =>	mosGetParam( $_REQUEST, 'website_id', 0),
		));
		$template->set_filenames_new(array(
			'xacnhan' => 'common_lists/xacnhan/xacnhan_info.tpl')
		);
		
		$template->pparse('xacnhan');	
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	
	function mosThuchien( )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;		
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$sql = "insert into tbl_thuchien (member_id, xacnhan_id, ngay) values (".$_SESSION["login_id"].", '$id', CURDATE())";	
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		mosList(0);	
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	function mosThongKe( )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;
		//so lan click
		$sql = "select * from tbl_member";
		if ( !($result = $db->sql_query($sql))) die ( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result))
		{
			$member_id 	= $row['member_id'];
			$template->assign_block_vars('member_list', array(
				'name'		=>	$row['fullname'],
			));
			$sql1 = "select * from tbl_xacnhan";
			if ( !($result1 = $db->sql_query($sql1))) die ( SERVER_BUSY );
			while ( $row1 = $db->sql_fetchrow($result1))
			{
				$xacnhan_id	= $row1['xacnhan_id'];
				$sql2 = "select count(*) as dem from tbl_thuchien where member_id = $member_id and xacnhan_id = $xacnhan_id  and month(ngay) = month(now())";
				if ( !($result2 = $db->sql_query($sql2))) die ( SERVER_BUSY );
				if ( $row2 = $db->sql_fetchrow($result2))
					$dem	= $row2['dem'];
				$template->assign_block_vars('member_list.xacnhan_list', array(
					'xacnhan_name'	=>	$row1['xacnhan_name'],
					'chitiet'		=>	$row1['chitiet'],
					'dem'			=>	$dem,
					'rong'			=>	$dem * 5,
				));
			}
		}		
		$template->set_filenames_new(array(
			'xacnhan' => 'common_lists/xacnhan/thongke.tpl')
		);
		$template->pparse('xacnhan');	
	}

?>