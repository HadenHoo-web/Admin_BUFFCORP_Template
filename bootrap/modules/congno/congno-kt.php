<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'congno/congno-kt',
		'LANGUAGEID'=> $languageid,
	));		
	switch( $action ){	
		case 'list'	:	mosList(); break;
    	case 'thongke'	:	mosThongKe(); break;
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
function mosList(){	
	global $db, $root_path, $skin, $languageid, $template;
	$month   = mosGetParam( $_REQUEST, 'month', date('m') );
	$year			= mosGetParam( $_REQUEST, 'year', date('Y') );
	$thuchi  = mosGetParam( $_REQUEST, 'thuchi1', '0' );
	$loai	= mosGetParam( $_REQUEST, 'loai1', '0' );
    $website_id	= mosGetParam( $_REQUEST, 'website_id1', '0' );
    $member_id	= mosGetParam( $_REQUEST, 'member_id1', '0' );
  
    $cond = (strtolower($_SESSION['membername'])=="administrator")?'':' and active = 1'; 
    $sql = "select * from tbl_loaikho where 1 $cond order by loaikho_id";
    if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
    while( $row = $db->sql_fetchrow($result) ){					
		$template->assign_block_vars('loai_list', array(
			'loaikho_id'	 =>	$row['loaikho_id'],
			'loaikho_name' =>	$row['loaikho_name'],
		));	
    }
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
	$cond   .= ($thuchi == 1 or $thuchi == 0)?' and tbl_congno.thuchi = '.$thuchi:'';
	$cond   .= ($loai)?' and tbl_congno.loai = '.$loai:'';
    $cond   .= ($website_id)?' and tbl_congno.website_id = '.$website_id:'';
    $cond   .= ($member_id)?' and tbl_website.kt_id = '.$member_id:'';

	$login_id = $_SESSION["login_id"];
	switch ($login_id) {
		case '1':
			break;
		case '34'://T.A
		case '76':
			$cond .= " and tbl_website.member_id not in (1,2) and tbl_website.kt_id not in (1,2)";//Not in Admin, Kiều, Tú
			break;
		default:
			$cond .= " and tbl_website.kt_id = $login_id";
			break;
	}
	$sql = "select tbl_congno.*, tbl_member.fullname, tbl_website.website_name, tbl_website.website_id, tbl_website.kt_id, tbl_website.website_type_id, tbl_member.fullname, tbl_loaikho.loaikho_name from ((tbl_congno left join tbl_website on tbl_congno.website_id = tbl_website.website_id) left join tbl_member on tbl_congno.member_id = tbl_member.member_id) left join tbl_loaikho on tbl_congno.loai = tbl_loaikho.loaikho_id where SUBSTRING(tbl_congno.ngay, 4, 2) = '$month' and SUBSTRING(tbl_congno.ngay, 7, 4) = '$year' and tbl_congno.language_id=$languageid $cond order by tbl_website.kt_id DESC, tbl_congno.ngay DESC, tbl_congno.congno_id DESC";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order = 0;
	$sum = 0; $webid = "";
	while( $row = $db->sql_fetchrow($result) ){	
		$order = $order + 1;
		$website_type_id = $row['website_type_id'];
		if ($website_type_id == 1)$bg_website = "Yellow";
		elseif ($website_type_id == 2)$bg_website = "Orange";
		elseif ($website_type_id == 3)$bg_website = "AFD788";
		elseif ($website_type_id == 4)$bg_website = "D7D7D7";
		elseif ($website_type_id == 5)$bg_website = "98D0B9";
		elseif ($website_type_id == 8)$bg_website = "Green";
		else $bg_website="";
		$pos = strpos($webid, $row['website_id']);
		if ($pos === false)
			if($webid)$webid .= $row['website_id'].",";

		$sql1 = "select fullname from tbl_member where member_id = ".$row['kt_id'];
		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		if( $row1 = $db->sql_fetchrow($result1))
			$kt_name = $row1['fullname'];

		$template->assign_block_vars('list', array(
			'className'	=>  ($order % 2 == 1) ? 'alt' : 'inv',
			'order'		=>  $order,
			'bg_website'	=>	$bg_website,
			'congno_id'	=>	$row['congno_id'],
			'congno_code'  =>	$row['congno_code'],
			'congno_name'  =>	number_format($row['congno_name'], 0, ',', '.'),
			'thuchi'	   =>	($row['thuchi'] == 0)?'+':'-',
			//'loai'		 =>	($row['loai']==1)?'SEO':(($row['loai']==2)?'WEB':(($row['loai']==3)?'ĐÀO TẠO':'CTY')),
        	'loai'		 =>	$row['loaikho_name'],
        	'website_name'  => $row['website_name'],
        	'member_name'   => $row['fullname'],
			'kt_name'	   => $kt_name,
			'ngay'		 =>	$row['ngay'],
			'active' 	   =>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
			'up'		   =>	($order == 1) ? ' display: none;' : '',
			'down'		 =>	($order == $num_row) ? ' display: none;' : '',
			'creatd_by'	=>	$row['created_by'],
        	'created_date'  =>	$row['created_date'],
			'modified_by'   =>	$row['modified_by'],
			'last_modified' =>	$row['last_modified'],
		));	
		if ( $row['thuchi'] == 0)
			$sum += $row['congno_name'];
		else 
			$sum -= $row['congno_name'];
	}
	$cond = (strtolower($_SESSION['membername'])=="administrator")?'':' and tbl_website.member_id = "'.$_SESSION["login_id"].'"'; 
	$webid = substr($webid, 0, -1);
	$cond .= ($webid)?"and website_id in ($webid)":"";
    $sql = "select * from tbl_website where website_type_id NOT IN (0,6,100) $cond order by website_name";
    if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
    while( $row = $db->sql_fetchrow($result) ){					
		$template->assign_block_vars('website_list', array(
			'website_id'	 =>	$row['website_id'],
			'website_name' =>	$row['website_name'],
		));	
    }
	//tiền bán hàng
	$sql = "select tbl_banhang.* from tbl_banhang where SUBSTRING(ngay, 7, 4) = '$year' and SUBSTRING(ngay, 4, 2) = '$month' and SUBSTRING(ngay, 1, 2) >= '01' and SUBSTRING(ngay, 1, 2) < '32'  order by banhang_id DESC";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$sum_banhang = 0;
	while( $row = $db->sql_fetchrow($result) ){
		$sum_banhang += $row['banhang_name'];	
	}
	$tien_con_lai = $sum_banhang + $sum;
	$template->assign_vars(array(
		'sum'	       =>	number_format($sum, 0, ',', '.'),
		'month'	     =>	$month,
		'year'	     =>	$year,
		'thuchi'	   =>	$thuchi,
		'loai'	     =>	$loai,
      	'website_id' =>	$website_id,
      	'member_id'  => $member_id,
		'thongke'    =>	(strtolower($_SESSION['membername'])=="administrator")?number_format($sum_banhang, 0, ',', '.')." ".number_format($sum, 0, ',', '.')." = ".number_format($tien_con_lai, 0, ',', '.'):"",
      	'isthongke'  => (strtolower($_SESSION['membername'])=="administrator")?"":"none",
	));
	$template->set_filenames_new(array(
		'share' => 'congno/congno_kt_list.html')
	);
	$template->pparse('share');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosThongKe(){	
	global $db, $root_path, $skin, $languageid, $template;
	$from_date		= mosGetParam( $_REQUEST, 'from_date', date('Y-m-d') );
	$to_date		= mosGetParam( $_REQUEST, 'to_date', date('Y-m-d') );
	$thuchi  = mosGetParam( $_REQUEST, 'thuchi1', '2' );
	$loai	= mosGetParam( $_REQUEST, 'loai1', '0' );
    $website_id	= mosGetParam( $_REQUEST, 'website_id1', '0' );
    $member_id	= mosGetParam( $_REQUEST, 'member_id1', '0' );

	$cond = (strtolower($_SESSION['membername'])=="administrator")?'':' and tbl_website.member_id = "'.$_SESSION["login_id"].'"'; 
    $sql = "select * from tbl_website where website_type_id NOT IN (0,6,100) $cond order by website_name";
    if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
    while( $row = $db->sql_fetchrow($result) ){					
		$template->assign_block_vars('website_list', array(
			'website_id'	 =>	$row['website_id'],
			'website_name' =>	$row['website_name'],
		));	
    }
	$cond = (strtolower($_SESSION['membername'])=="administrator")?'':' and active = 1'; 
    $sql = "select * from tbl_loaikho where 1 $cond order by loaikho_id";
    if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
    while( $row = $db->sql_fetchrow($result) ){					
		$template->assign_block_vars('loai_list', array(
			'loaikho_id'	 =>	$row['loaikho_id'],
			'loaikho_name' =>	$row['loaikho_name'],
		));	
    }
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
	$cond   .= ($thuchi == 1 or $thuchi == 0)?' and tbl_congno.thuchi = '.$thuchi:'';
	$cond   .= ($loai)?' and tbl_congno.loai = '.$loai:'';
    $cond   .= ($website_id)?' and tbl_congno.website_id = '.$website_id:'';
    $cond   .= ($member_id)?' and tbl_congno.member_id = '.$member_id:'';
    $cond   .= (strtolower($_SESSION['membername'])=="administrator")?'':((strtolower($_SESSION["login_id"])=="34")?' and tbl_congno.member_id not in (1,2)':' and tbl_congno.member_id = "'.$_SESSION["login_id"].'"');
	$cond   .= ($website_id)?' and tbl_congno.website_id = '.$website_id:'';
	$sql = "select tbl_congno.*,tbl_website.website_name, tbl_member.fullname, tbl_loaikho.loaikho_name, tbl_loaikho.loaikho_id from ((tbl_congno left join tbl_website on tbl_congno.website_id = tbl_website.website_id) left join tbl_member on tbl_congno.member_id = tbl_member.member_id) left join tbl_loaikho on tbl_congno.loai = tbl_loaikho.loaikho_id where SUBSTRING(tbl_congno.ngay, 4, 2) = '$month' and SUBSTRING(tbl_congno.ngay, 7, 4) = '$year' and tbl_congno.language_id=$languageid $cond order by tbl_congno.loai, tbl_congno.congno_id DESC";//sql old
	$sql = "select tbl_congno.*,tbl_website.website_name, tbl_member.fullname, tbl_loaikho.loaikho_name, tbl_loaikho.loaikho_id from ((tbl_congno left join tbl_website on tbl_congno.website_id = tbl_website.website_id) left join tbl_member on tbl_congno.member_id = tbl_member.member_id) left join tbl_loaikho on tbl_congno.loai = tbl_loaikho.loaikho_id where CONCAT(SUBSTRING(tbl_congno.ngay, 7, 4),'-',SUBSTRING(tbl_congno.ngay, 4, 2),'-', SUBSTRING(tbl_congno.ngay, 1, 2)) >= '$from_date' AND CONCAT(SUBSTRING(tbl_congno.ngay, 7, 4),'-',SUBSTRING(tbl_congno.ngay, 4, 2),'-', SUBSTRING(tbl_congno.ngay, 1, 2))<= '$to_date' and tbl_congno.language_id=$languageid $cond order by tbl_congno.loai, tbl_congno.congno_id DESC";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order = 0;$tam = 1;$sum_thu = 0;$sum_chi = 0;$sum_tong = 0;$loaikho_name = '';
	$sum = 0;
	while( $row = $db->sql_fetchrow($result) ){	
		$order = $order + 1;
      	if($tam != $row['loaikho_id']){
        	$tam = $row['loaikho_id'];
        	$tr = '
          <td align="left"><span style="font-weight: bold;">'.$loaikho_name.'</span></td> 
          <td align="center" ><span style="font-weight: bold;">Tổng Thu</span></td>
          <td align="right" ><span style="font-weight: bold;color:blue;font-size: 12px;">'.number_format($sum_thu, 0, ',', '.').'</span></td>
          <td align="left" ><span style="font-weight: bold;">Tổng Chi</span></td>
          <td align="right" ><span style="font-weight: bold;color:orange;font-size: 12px;">'.number_format($sum_chi, 0, ',', '.').'</td>
          <td align="left" ><span style="font-weight: bold;">Tổng cộng</span></td>
          <td align="right" ><span style="font-weight: bold;color:red;font-size: 12px;">'.number_format($sum_tong, 0, ',', '.').'</span></td>
          <td  colspan="4" width="80"></td>';
        	$sum_thu = 0;$sum_chi = 0;$sum_tong = 0;
        	if ($row['thuchi'] == 0){
          		$sum_thu += $row['congno_name'];
          		$sum_tong += $row['congno_name'];
        	}else{
          		$sum_chi += $row['congno_name'];
          		$sum_tong -= $row['congno_name'];
        	}
      	}else{ 
        	$tr = "";$loaikho_name = $row['loaikho_name'];
        	if ($row['thuchi'] == 0){
          		$sum_thu += $row['congno_name'];
          		$sum_tong += $row['congno_name'];
        	}else{
          		$sum_chi += $row['congno_name'];
          		$sum_tong -= $row['congno_name'];
        	}
      	}
		$template->assign_block_vars('list', array(
			'className'	   =>  ($order % 2 == 1) ? 'alt' : 'inv',
			'order'		     =>  $order,
			'congno_id'	   =>	$row['congno_id'],
			'congno_code'  =>	$row['congno_code'],
			'congno_name'  =>	number_format($row['congno_name'], 0, ',', '.'),
			'thuchi'	     =>	($row['thuchi'] == 0)?'+':'-',
			//'loai'		  =>	($row['loai']==1)?'SEO':(($row['loai']==2)?'WEB':(($row['loai']==3)?'ĐÀO TẠO':'CTY')),
        	'loai'		     =>	$row['loaikho_name'],
        	'website_name'  => $row['website_name'],
        	'member_name'   => $row['fullname'],
			'ngay'		 =>	$row['ngay'],
			'active' 	   =>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
			'up'		   =>	($order == 1) ? ' display: none;' : '',
			'down'		 =>	($order == $num_row) ? ' display: none;' : '',
			'creatd_by'	=>	$row['created_by'],	
        	'tr'        =>  $tr,
		));	
		if ( $row['thuchi'] == 0)
			$sum += $row['congno_name'];
		else 
			$sum -= $row['congno_name'];
	}	
	//tiền bán hàng
	$sql = "select tbl_banhang.* from tbl_banhang where SUBSTRING(ngay, 7, 4) = '$year' and SUBSTRING(ngay, 4, 2) = '$month' and SUBSTRING(ngay, 1, 2) >= '01' and SUBSTRING(ngay, 1, 2) < '32'  order by banhang_id DESC";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$sum_banhang = 0;
	while( $row = $db->sql_fetchrow($result) ){
		$sum_banhang += $row['banhang_name'];	
	}
	$tien_con_lai = $sum_banhang + $sum;
	$template->assign_vars(array(
		'sum'	   =>	number_format($sum, 0, ',', '.'),
		'thuchi'	=>	$thuchi,
		'loai'	  =>	$loai,
      	'website_id'	  =>	$website_id,
		'member_id'  => $member_id,
		'thongke'   =>	(strtolower($_SESSION['membername'])=="administrator")?number_format($sum_banhang, 0, ',', '.')." ".number_format($sum, 0, ',', '.')." = ".number_format($tien_con_lai, 0, ',', '.'):"",
      	'isthongke'  => (strtolower($_SESSION['membername'])=="administrator")?"":"none",
		'from_date'	=>	$from_date,
		'to_date'	=>	$to_date,
	));
	$template->set_filenames_new(array(
		'share' => 'congno/congno_thongke.html')
	);
	$template->pparse('share');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$congno_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir = $root_path . "images/congno/";
    
    $cond = (strtolower($_SESSION['membername'])=="administrator")?'':' and (tbl_website.member_id = "'.$_SESSION["login_id"].'" or tbl_website.website_id = 15)'; 
    $sql = "select * from tbl_website where website_type_id NOT IN (0,6,100) $cond order by website_name";
    if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
    while( $row = $db->sql_fetchrow($result) )
		{					
			$template->assign_block_vars('website_list', array(
				'website_id'	 =>	$row['website_id'],
				'website_name' =>	$row['website_name'],
			));	
    }
  
    $cond = (strtolower($_SESSION['membername'])=="administrator")?'':' and active = 1'; 
    $sql = "select * from tbl_loaikho where 1 $cond order by loaikho_id";
    if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
    while( $row = $db->sql_fetchrow($result) )
		{					
			$template->assign_block_vars('loai_list', array(
				'loaikho_id'	 =>	$row['loaikho_id'],
				'loaikho_name' =>	$row['loaikho_name'],
			));	
    }
  
		if ($congno_id != 0)
		{	$sql = "select * from tbl_congno where congno_id = $congno_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$image=$row['image'];
				$template->assign_vars(array(
					'congno_id'		=>	$congno_id,
					'congno_code'	=>	$row['congno_code'],
					'congno_name'	=>	$row['congno_name'],
					'thuchi'		=>	$row['thuchi'],
					'loai'			=>	$row['loai'],
          'website_id'=>  $row['website_id'],
					'ngay'			=>	$row['ngay'],
					'active'		=>	($row['active'] == 1) ? 'checked' : '',
					'image'			=>	$image,
					'imgPath'		=>	($image)?"<img src='$imgDir$image' border=0 >":"",
					'allow'			=>	($row['image'])?"":"none",
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
				'thuchi'		=>	'0',
				'loai'			=>	'1',
        'website_id'			=>	'0',
				'active'		=>	'checked' ,
				'allow'			=> 	'none',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'congno/congno_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$congno_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($congno_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $congno_id, $direction, "tbl_congno", "congno_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$congno_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$congno_code	= mosGetParam( $_REQUEST, 'congno_code', '');
		$congno_name	= mosGetParam( $_REQUEST, 'congno_name', '');
		$thuchi			= mosGetParam( $_REQUEST, 'thuchi', 0);
		$loai			= mosGetParam( $_REQUEST, 'loai', 0);
    $website_id			= mosGetParam( $_REQUEST, 'website_id', 0);
		$ngay			= mosGetParam( $_REQUEST, 'ngay', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$image			= mosGetParam( $_REQUEST, 'image', '');
		$old_image		= mosGetParam( $_REQUEST, 'old_image', '');
		$remove_image	= mosGetParam( $_REQUEST, 'remove_image', '');
			
		if ($congno_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($congno_id == '0')
		{	
			$sql = "insert into tbl_congno (congno_code, congno_name, thuchi, ngay, active, language_id, loai, website_id, created_date, created_by, last_modified, modified_by, member_id ) values ('$congno_code', '$congno_name', '$thuchi', '$ngay', $active, $languageid, '$loai', '$website_id', now(), '" . $_SESSION['membername'] . "', now(), '" . $_SESSION['membername'] . "', '" . $_SESSION["login_id"] . "')";
		} else
			{ 
			$sql = "update tbl_congno set congno_code = '$congno_code', congno_name ='$congno_name', thuchi = '$thuchi', ngay = '$ngay', loai = '$loai', website_id = '$website_id', last_modified = now() , modified_by = '" . $_SESSION['membername'] . "' where congno_id = $congno_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		//$arrField = array("image");
		//checkDeleteOldFile($image, $old_image, $remove_image, $imgDir , "tbl_congno", $arrField, "congno_id", $congno_id);
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$congno_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($congno_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		
		$sql = "select image from tbl_congno where congno_id = $congno_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )
		{	
			$img = $row['image'];	
		}
		$arrField = array("image");
		checkDeleteOldFile("", $img, 1, $root_path . "images/congno" , "tbl_congno", $arrField, "congno_id", $congno_id);
  
    if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_congno", "congno_id", $congno_id);
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
			'congno_code'		=>	mosGetParam( $_REQUEST, 'congno_code', ''),
			'congno_name' 	=>	mosGetParam( $_REQUEST, 'congno_name', ''),
			'thuchi'		=>	mosGetParam( $_REQUEST, 'thuchi', 0),
			'loai'			=>	mosGetParam( $_REQUEST, 'loai', 0),
      'website_id'			=>	mosGetParam( $_REQUEST, 'website_id', 0),
			'ngay'			=>	mosGetParam( $_REQUEST, 'ngay', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'congno_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'congno' => 'congno/congno_info.html')
		);
		
		$template->pparse('congno');	
	}
?>
