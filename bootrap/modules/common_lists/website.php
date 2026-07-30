<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/website',
		'LANGUAGEID'=> $languageid,
		
	));		
	switch( $action )
	{	
		case 'list'		:	mosList(0); break;
		case 'info'		:	mosInfo(); break;
		case 'up'	  	: mosMove('up'); break;
		case 'down' 	: mosMove('down'); break;
		case 'save'		:	mosSave(); break;
		case 'delete' :	mosDelete(); break;
		default:
			mosInvalidURL();
			exit;
	}
function mosList($id){
	global $db, $root_path, $skin, $languageid, $template;
		$parent_id = mosGetParam( $_REQUEST, 'parent_id', 0 );
	$customer_id = mosGetParam( $_REQUEST, 'customer_id1', 0 );
	$member_id      = mosGetParam( $_REQUEST, 'member_id1', '0' );
    $kt_id          = mosGetParam( $_REQUEST, 'kt_id1', '0' );
    $content_id          = mosGetParam( $_REQUEST, 'content_id1', '0' );
    $code_id          = mosGetParam( $_REQUEST, 'code_id1', '0' );
    $active			= mosGetParam( $_REQUEST, 'active', '0');
	$website_type_id1      = mosGetParam( $_REQUEST, 'website_type_id1', '0' );
    $dat_kpi1      = mosGetParam( $_REQUEST, 'dat_kpi1', '' );
    $created_month = mosGetParam( $_REQUEST, 'created_month', '' );
    $created_year  = mosGetParam( $_REQUEST, 'created_year', '' );
    $expire_month  = mosGetParam( $_REQUEST, 'expire_month', '' );
    $expire_year   = mosGetParam( $_REQUEST, 'expire_year', '' );
	$parent_id = ($parent_id==0)?$id:$parent_id;
	$cond = "";
	$cond .= ($customer_id)?" and tbl_customer.customer_id = '$customer_id'":"";
	$cond .= ($member_id != 0)?' and tbl_member.member_id = '.$member_id:'';
	$cond .= ($kt_id != 0)?' and tbl_website.kt_id = '.$kt_id:'';
    $cond .= ($content_id != 0)?' and tbl_website.content_id = '.$content_id:'';
    $cond .= ($code_id != 0)?' and tbl_website.code_id = '.$code_id:'';
    $cond .= ($active)?" and tbl_website.active = '$active'":"";
	$cond .= ($website_type_id1)?" and tbl_website.website_type_id = '$website_type_id1'":"";
    $cond .= ($dat_kpi1 !== '') ? " and IFNULL(tbl_website.dat_kpi, 0) = ".intval($dat_kpi1) : "";

    if ($created_month != '' && $created_year != '') {
        $cond .= " and SUBSTRING(tbl_website.ngay, 4, 2) = '".sprintf('%02d', intval($created_month))."' and SUBSTRING(tbl_website.ngay, 7, 4) = '".intval($created_year)."'";
    }

    if ($expire_month != '' && $expire_year != '') {
        $cond .= " and tbl_website.ngay_kh IS NOT NULL and MONTH(tbl_website.ngay_kh) = ".intval($expire_month)." and YEAR(tbl_website.ngay_kh) = ".intval($expire_year);
    }
	$ky_tu = 'i'; // Biểu thức cần kiểm tra
	$login_id = $_SESSION["login_id"];
	$full_view = array("1", "2", "28", "71");
	switch ($login_id) {
		case '34':
		case '76':
            $cond .= " and tbl_website.website_type_id not in (7)";
			break;
        case '50'://Hằng
        case '63'://Tú
            $cond .= " and tbl_website.kt_id in ($login_id)";
            break;
        default:
            if (!in_array(strtolower($login_id), $full_view)) {
			    $cond .= " and (tbl_website.member_id = '$login_id' or tbl_website.kt_id = '$login_id')";
            }
			break;
	}


	//$cond .= (strtolower($_SESSION['membername'])=="administrator")?'':((strtolower($_SESSION["login_id"])=="34" || strtolower($_SESSION["login_id"])=="63")?' and tbl_website.member_id not in (1,2)':' and tbl_website.member_id = "'.$_SESSION["login_id"].'"');
	$sql = "SELECT tbl_website.*,tbl_customer.customer_name, tbl_member.fullname, tbl_website_type.priority FROM ((tbl_website LEFT JOIN tbl_customer ON tbl_website.customer_id = tbl_customer.customer_id) LEFT JOIN tbl_member ON tbl_website.member_id = tbl_member.member_id) LEFT JOIN tbl_website_type ON tbl_website.website_type_id = tbl_website_type.website_type_id  where 1 $cond ORDER BY tbl_website.active DESC, tbl_website_type.priority, tbl_website.kt_id, tbl_website.content_id, tbl_website.code_id, tbl_website.website_type_id, tbl_website.priority, tbl_website.website_name";
    if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order = 0;
	while( $row = $db->sql_fetchrow($result)){
        $sql1 = "select fullname from tbl_member where member_id = ".$row['kt_id'];
        if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
        if( $row1 = $db->sql_fetchrow($result1))
        $kt_name = $row1['fullname'];
		$order = $order + 1;
		$website_type_id = $row['website_type_id'];
		if ($website_type_id == 1)$bg_website = "Yellow";
		elseif ($website_type_id == 2)$bg_website = "Orange";
		elseif ($website_type_id == 3)$bg_website = "AFD788";
		elseif ($website_type_id == 4)$bg_website = "D7D7D7";
		elseif ($website_type_id == 5)$bg_website = "98D0B9";
        elseif ($website_type_id == 8)$bg_website = "Green";
		else $bg_website="";
		$php_version = $row['php_version'];
		if ($php_version == 1)$version = "5.6";
		elseif ($php_version == 2)$version = "7.0";
		else $version = "";
		$template->assign_block_vars('list', array(
			'className'	    =>  ($order % 2 == 1) ? 'alt' : 'inv',
			'order'		      =>  $order,
			'website_id'	  =>	$row['website_id'],
			'website_name'  =>	$row['website_name'],
          	'website_code'  =>	$row['website_code'],
			'title_seo'	    =>	($row['title_seo'])?"<a href='".$row['title_seo']."' target='_blank'>BG</a>":"",
			'sheet'	  	    =>	($row['sheet'])?"<a href='".$row['sheet']."' target='_blank'>HĐ</a>":"",
          	'username'	    =>	$row['username'],
			'ngay'	        =>	$row['ngay'],
			'pass'	  	    =>	$row['pass'],
			'ip'	  	   	  =>	($row['ip'])?"<a href='".$row['ip']."' target='_blank'>Sheet</a>":"",
			'soluong'		    =>	$row['soluong'],
			'traffic'		    =>	$row['traffic'],
			'customer_name'	=>	$row['customer_name'],
          	'member_name'	  =>	$row['fullname'],
            'kt_name'	    =>	$kt_name,
			'tel_name'	    =>	$row['tel_name'],
			'ghichu'		    =>	$row['ghichu'],
			'priority'	    =>	$row['priority'],
			'active' 		    =>	($row['active'] == 1) ? '<b><font size="2" color="#008000">Actived</font></b>' : '',
			'up'			      =>	($order == 1) ? ' display: none;' : '',
			'down'		      =>	($order == $num_row) ? ' display: none;' : '',
			'bg_website'	  =>	$bg_website,
			'php_version'	  =>	$version,
		));
        $kt_name = '';
	}
	$sql = "select * from tbl_website where website_id=$parent_id";
	if( !($result = $db->sql_query($sql))) message_die( SERVER_BUSY );
	if( $row = $db->sql_fetchrow($result)){
		$template->assign_vars(array(
			'parent_id'	   =>	$row['website_id'],
			'website_name' =>	$row['website_name'],
          	'website_code' =>	$row['website_code'],
			'title_seo'	   =>	$row['title_seo'],
			'sheet'	  	   =>	$row['sheet'],
			'username'	   =>	$row['username'],
          	'ngay'	       =>	$row['ngay'],
			'pass'	  	   =>	$row['pass'],
			'ip'	  	   =>	$row['ip'],
			'soluong'		=>	$row['soluong'],
			'traffic'		=>	$row['traffic'],
			'ghichu'		=>	$row['ghichu'],
			'priority'	   	=>	$row['priority'],
		));
	}
	$template->assign_vars(array(
		'customer_id'	=>	$customer_id,
		'member_id'		=>	$member_id,
		'kt_id'		=>	$kt_id,
		'content_id'		=>	$content_id,
		'code_id'		=>	$code_id,
		'website_type_id'=>	$website_type_id1,
        'active'		  =>	($active == 1) ? 'checked' : '',
	));
	$template->set_filenames_new(array(
		'website' => 'common_lists/website/website_list.html')
	);
	$template->pparse('website');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$full_view = array("2", "28", "71");
		$can_view_all = (strtolower($_SESSION['membername'])=="administrator" || in_array(strtolower($_SESSION["login_id"]), $full_view));
		$website_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$parent_id	 = mosGetParam( $_REQUEST, 'parent_id', 0 );
		$imgDir = $root_path . "images/logoweb/";
		$sql = "select * from tbl_website where website_id=$parent_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_vars(array(
				'parent_id'	  =>	$row['website_id'],
				'parent_name'	=>	$row['website_name'],
				'title_name'	 =>	$row['title_seo'],
				'sheet'		  =>	$row['sheet'],
				'soluong'		=>	$row['soluong'],
				'traffic'		=>	$row['traffic'],
				'email_id'	   =>	$row['email_id'],
				'host_id'	   =>	$row['host_id'],
				'website_type_id'=>    $row['website_type_id'],
				'php_version'	=>    $row['php_version'],
				'ghichu'		 =>	$row['ghichu'],
				'priority'	   =>	$row['priority'],
			));
		}

    $cond = ($can_view_all)?'':' and member_id = "'.$_SESSION["login_id"].'"';
    $sql = "select * from tbl_customer where 1 $cond and customer_type_id = 8 order by customer_name";

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_block_vars('list_customer', array(
				'customer_id'	  =>	$row['customer_id'],
				'customer_name'	=>	$row['customer_name'],
			));
		}
		$cond = ($can_view_all)?'':' and member_id = "'.$_SESSION["login_id"].'"';
		$sql = "select * from tbl_hosts where 1 $cond order by host_name";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_block_vars('list_host', array(
				'host_id'	  =>	$row['host_id'],
				'host_name'	=>	$row['host_name'],
			));
		}
		if ($website_id != 0)
		{	$sql = "select * from tbl_website where website_id = $website_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	$host_id = ($row['host_id'])?$row['host_id']:0;
				$template->assign_vars(array(
					'website_id'	    =>	$website_id,
					'website_name'    =>	$row['website_name'],
          'website_code'    =>	$row['website_code'],
					'title_seo'	      =>	$row['title_seo'],
					'sheet'	  	      =>	$row['sheet'],
					'username'	      =>	$row['username'],
          'ngay'	          =>	$row['ngay'],
          'ngay_kt'	      =>	$row['ngay_kt'],
          'ngay_kh'	      =>	$row['ngay_kh'],
					'pass'	 	        =>	$row['pass'],
					'ip'	 	   	      =>	$row['ip'],
					'soluong'		      =>	$row['soluong'],
					'traffic'		      =>	$row['traffic'],
					'email_id'	      =>	($row['email_id'])?$row['email_id']:0,
					'host_id'	 	=>	($row['host_id'])?$row['host_id']:0,
					'website_type_id' =>	($row['website_type_id'])?$row['website_type_id']:0,
					'php_version'	    =>	($row['php_version'])?$row['php_version']:0,
					'ghichu'		      =>	$row['ghichu'],
					'priority'	      =>	$row['priority'],
					'parent_id' 	    =>	$row['parent_id'],
					'active'		      =>	($row['active'] == 1) ? 'checked' : '',
					'dat_kpi'		      =>	($row['dat_kpi'] == 1) ? 'checked' : '',
					'logo'		        =>	$row['logo'],
					'logoPath'	      =>	($row['logo'])?"<img src='$imgDir".$row['logo']."' border=0 >":"",
					'allow_logo'	    =>	($row['logo'])?"":"none",
          'customer_id'	    =>	$row['customer_id'],
          'member_id'	      =>	$row['member_id'],
          'kt_id'           =>	$row['kt_id'],
          'content_id'           =>	$row['content_id'],
          'code_id'           =>	$row['code_id'],
          'allow_member_id' => ($can_view_all)?'':'none',
				));
			} else
				message_die( ID_NOTFOUND );		
		}else{
			$template->assign_vars(array(
				'active'		=>	'checked' ,
				'allow'		 => 'hidden',
				'email_id'	  => 0,
				'host_id'	  => 0,
                'kt_id'        => 0,
                'content_id'        => 0,
                'code_id'        => 0,
				'website_type_id'=> 0,
				'php_version'   => 0,
                'customer_id'	  		=>	0,
                'member_id'	  		=>	$_SESSION["login_id"],
                'allow_member_id' => ($can_view_all)?'':'none',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'common_lists/website/website_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$website_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($website_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $website_id, $direction, "tbl_website", "website_id", "priority");
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave(){
        global $db, $root_path, $skin, $languageid, $template,$imgDir;
		$website_id 	  = mosGetParam( $_REQUEST, 'id', '0');
		$parent_id 	   = mosGetParam( $_REQUEST, 'parent_id', '0');
		$website_name	= mosGetParam( $_REQUEST, 'website_name', '');
    $website_code	= mosGetParam( $_REQUEST, 'website_code', '');
		$title_seo	   = mosGetParam( $_REQUEST, 'title_seo', '');
		$sheet	   	   = mosGetParam( $_REQUEST, 'sheet', '');
		$username	    = mosGetParam( $_REQUEST, 'username', '');
    $ngay	    = mosGetParam( $_REQUEST, 'ngay', '');
    $ngay_kt	    = mosGetParam( $_REQUEST, 'ngay_kt', '');
    $ngay_kh	    = mosGetParam( $_REQUEST, 'ngay_kh', '');
    $ngay_kt_sql = ($ngay_kt != '')?"'$ngay_kt'":"NULL";
    $ngay_kh_sql = ($ngay_kh != '')?"'$ngay_kh'":"NULL";
		$pass	   		= mosGetParam( $_REQUEST, 'pass', '');
		$ip		   	  = mosGetParam( $_REQUEST, 'ip', '');
		$soluong		 = mosGetParam( $_REQUEST, 'soluong', '0');
		$traffic		 = mosGetParam( $_REQUEST, 'traffic', '0');
		$email_id		= mosGetParam( $_REQUEST, 'email_id', '0');
		$host_id		= mosGetParam( $_REQUEST, 'host_id', '0');
    $customer_id		= mosGetParam( $_REQUEST, 'customer_id', '0');
    $member_id		= (mosGetParam( $_REQUEST, 'member_id', '0'))?mosGetParam( $_REQUEST, 'member_id', '0'):$_SESSION["login_id"];
    $kt_id		= (mosGetParam( $_REQUEST, 'kt_id', '0'))?mosGetParam( $_REQUEST, 'kt_id', '0'):$_SESSION["login_id"];
    $content_id		= (mosGetParam( $_REQUEST, 'content_id', '0'))?mosGetParam( $_REQUEST, 'content_id', '0'):$_SESSION["login_id"];
    $code_id		= (mosGetParam( $_REQUEST, 'code_id', '0'))?mosGetParam( $_REQUEST, 'code_id', '0'):$_SESSION["login_id"];
		$website_type_id = mosGetParam( $_REQUEST, 'website_type_id', '0');
		$php_version	 = mosGetParam( $_REQUEST, 'php_version', '');
		$ghichu		  = mosGetParam( $_REQUEST, 'ghichu', '');
		$priority		= mosGetParam( $_REQUEST, 'priority', '');
		$active		  = mosGetParam( $_REQUEST, 'active', 0);
		$dat_kpi		  = mosGetParam( $_REQUEST, 'dat_kpi', 0);
		$logo			= mosGetParam( $_REQUEST, 'logo', '');
		$old_logo		= mosGetParam( $_REQUEST, 'old_logo', '');
		$remove_logo	 = mosGetParam( $_REQUEST, 'remove_logo', '');
		
		//$imgDir = $root_path . "images/logoweb/";
		//$logo = mosUploadImage($imgDir, "logo");
		//if (($remove_logo == 0) && ($logo == ''))
			//$logo = $old_logo;
			
		if ($website_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($website_id == '0')
		{	
			if (checkDuplicate("tbl_website", array('website_name' => $website_name), "website_name",0,false,"language_id = '$languageid' and parent_id = $parent_id"))
			{	reShowPage( DUPLICATE_ENTRY_WEBSITE_NAME );
				exit;
			}
      /*if (checkDuplicate("tbl_website", array('website_code' => $website_code), "website_code",0,false,"language_id = '$languageid' and parent_id = $parent_id"))
			{	reShowPage( DUPLICATE_ENTRY_WEBSITE_CODE );
				exit;
			}*/
			$priority = mosGetPriority("tbl_website", "priority", "");
			$sql = "insert into tbl_website (parent_id, website_name, website_code, title_seo, sheet, username, ngay, ngay_kt, ngay_kh, pass, ip, soluong, traffic, ghichu, active, dat_kpi, priority, language_id, logo, email_id, host_id, member_id, kt_id, content_id, code_id, customer_id, website_type_id, php_version) 
                    values ($parent_id, '$website_name', '$website_code', '$title_seo', '$sheet', '$username', '$ngay', $ngay_kt_sql, $ngay_kh_sql, '$pass', '$ip', '$soluong', '$traffic', '$ghichu', $active, $dat_kpi, $priority, $languageid, '$logo', '$email_id', '$host_id', '$member_id', '$kt_id', '$content_id', '$code_id', $customer_id, '$website_type_id', '$php_version')";
		} else{ 
      $cond = (strtolower($_SESSION['membername'])=="administrator")?"member_id = '$member_id',":'';
			$sql = "update tbl_website set website_name ='$website_name', website_code = '$website_code', title_seo = '$title_seo', sheet = '$sheet', username = '$username', ngay = '$ngay', ngay_kt = $ngay_kt_sql, ngay_kh = $ngay_kh_sql, pass = '$pass', ip = '$ip', soluong = '$soluong', traffic = '$traffic', ghichu = '$ghichu', priority = '$priority',  active = $active, dat_kpi = $dat_kpi, language_id=$languageid, logo = '$logo', email_id = '$email_id', host_id = '$host_id', $cond customer_id = '$customer_id', website_type_id = '$website_type_id', php_version = '$php_version', kt_id = '$kt_id', content_id = '$content_id', code_id = '$code_id' where website_id = $website_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		//$arrField = array("logo");
		//checkDeleteOldFile($logo, $old_logo, $remove_logo, $imgDir , "tbl_website", $arrField, "website_id", $website_id);
		mosList($parent_id);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$website_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($website_id == 0)
		{	mosInvalidURL();
			exit;
		}
		$sql = "select * from tbl_website where website_id = '$website_id'";
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result)){
			$parent_id = $row['parent_id'];
			$old_logo 	= $row['logo'];
		}
		$sql1 = "select count(*) as child_count from tbl_website where parent_id = '$website_id'";
		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		if ( $row1 = $db->sql_fetchrow($result1))		
		{	if (($row1['child_count'] == 0))
			{	
				$imgDir = $root_path . "images/logoweb/";
				$arrField = array("logo");
				checkDeleteOldFile('', $old_logo, 1, $imgDir , "tbl_website", $arrField, "website_id", $website_id);
      if(strtolower($_SESSION['membername'])=="administrator"){	
			    deleteByID("tbl_website", "website_id", $website_id);
          $template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
		    }else{
				  $template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
			  }
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
			'website_name'   =>	mosGetParam( $_REQUEST, 'website_name', ''),
      'website_code'   =>	mosGetParam( $_REQUEST, 'website_code', ''),
			'title_seo'	  =>	mosGetParam( $_REQUEST, 'title_seo', ''),
			'sheet'	  	  =>	mosGetParam( $_REQUEST, 'sheet', ''),
			'username'	   =>	mosGetParam( $_REQUEST, 'username', ''),
      'ngay'	  	   =>	mosGetParam( $_REQUEST, 'ngay', ''),
      'ngay_kt'	   =>	mosGetParam( $_REQUEST, 'ngay_kt', ''),
      'ngay_kh'	   =>	mosGetParam( $_REQUEST, 'ngay_kh', ''),
      'dat_kpi'	   =>	(mosGetParam( $_REQUEST, 'dat_kpi', 0) == 1) ? 'checked' : '',
			'pass'	  	   =>	mosGetParam( $_REQUEST, 'pass', ''),
			'ip'	  	     =>	mosGetParam( $_REQUEST, 'ip', ''),
			'soluong'		=>	mosGetParam( $_REQUEST, 'soluong', 0),
			'traffic'		=>	mosGetParam( $_REQUEST, 'traffic', 0),
			'email_id'	   =>	mosGetParam( $_REQUEST, 'email_id', 0),
	  'host_id'	   =>	mosGetParam( $_REQUEST, 'host_id', 0),
      'allow_member_id' => (strtolower($_SESSION['membername'])=="administrator")?'false':'true',
      'member_id'	   =>	mosGetParam( $_REQUEST, 'member_id', 0),
      'kt_id'	   =>	mosGetParam( $_REQUEST, 'kt_id', 0),
      'content_id'	   =>	mosGetParam( $_REQUEST, 'content_id', 0),
      'code_id'	   =>	mosGetParam( $_REQUEST, 'code_id', 0),
      'customer_id'	   =>	mosGetParam( $_REQUEST, 'customer_id', 0),
			'website_type_id'=>	mosGetParam( $_REQUEST, 'website_type_id', 0),
			'php_version'	=>	mosGetParam( $_REQUEST, 'php_version', 0),
			'ghichu'		 =>	mosGetParam( $_REQUEST, 'ghichu', ''),	
			'priority'	   =>	mosGetParam( $_REQUEST, 'priority', ''),		
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'website_id'	 =>	$id,
		));
		$template->set_filenames_new(array(
			'website' => 'common_lists/website/website_info.html')
		);
		
		$template->pparse('website');	
	}
?>
