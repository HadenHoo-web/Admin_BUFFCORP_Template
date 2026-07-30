<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'congno/congno',
		'LANGUAGEID'=> $languageid,
	));		
	switch( $action ){	
		case 'list'	:	mosList(); break;
    	case 'thongke'	:	mosThongKe(); break;
		case 'dashboard'	:	mosDashboardKpiSales(); break;
		case 'dashboard_seo'	:	mosDashboardKpiSeo(); break;
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
	$thuchi  = mosGetParam( $_REQUEST, 'thuchi1', '2' );
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
    $cond   .= ($member_id)?' and tbl_congno.member_id = '.$member_id:'';

	$login_id = $_SESSION["login_id"];
	switch ($login_id) {
		case '1':
			break;
		case '34'://T.A
			$cond .= " and tbl_congno.member_id not in (1,2,63)";//Not in Admin, Kiều, Tú
			break;
		default:
			$cond .= " and tbl_congno.member_id = $login_id";
			break;
	}
	
	
    //$cond   .= (strtolower($_SESSION['membername'])=="administrator")?'':((strtolower($_SESSION["login_id"])=="34")?' and tbl_congno.member_id not in (1,2)':' and tbl_congno.member_id = "'.$_SESSION["login_id"].'"');
	$sql = "select * from tbl_congno where SUBSTRING(ngay, 4, 2) = '$month' $cond and language_id=$languageid order by congno_id DESC";
	$sql = "select tbl_congno.*, tbl_member.fullname, tbl_website.website_name, tbl_website.website_id, tbl_member.fullname, tbl_loaikho.loaikho_name from ((tbl_congno left join tbl_website on tbl_congno.website_id = tbl_website.website_id) left join tbl_member on tbl_congno.member_id = tbl_member.member_id) left join tbl_loaikho on tbl_congno.loai = tbl_loaikho.loaikho_id where SUBSTRING(tbl_congno.ngay, 4, 2) = '$month' and SUBSTRING(tbl_congno.ngay, 7, 4) = '$year' and tbl_congno.language_id=$languageid $cond order by tbl_congno.ngay DESC, tbl_congno.congno_id DESC";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order = 0;
	$sum = 0; $webid = "";
	while( $row = $db->sql_fetchrow($result) ){	
		$order = $order + 1;
		$pos = strpos($webid, $row['website_id']);
		if ($pos === false)
			if($webid)$webid .= $row['website_id'].",";
		$template->assign_block_vars('list', array(
			'className'	=>  ($order % 2 == 1) ? 'alt' : 'inv',
			'order'		=>  $order,
			'congno_id'	=>	$row['congno_id'],
			'congno_code'  =>	$row['congno_code'],
			'congno_name'  =>	number_format($row['congno_name'], 0, ',', '.'),
			'thuchi'	   =>	($row['thuchi'] == 0)?'+':'-',
			//'loai'		 =>	($row['loai']==1)?'SEO':(($row['loai']==2)?'WEB':(($row['loai']==3)?'ĐÀO TẠO':'CTY')),
        	'loai'		 =>	$row['loaikho_name'],
        	'website_name'  => $row['website_name'],
        	'member_name'   => $row['fullname'],
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
		'share' => 'congno/congno_list.html')
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
	function salesKpiDepartmentId()
	{
		global $db;
		$sql = "select customer_type_id from tbl_customer_type where lower(customer_type_name) = 'kinh doanh' limit 1";
		if ($result = $db->sql_query($sql)) {
			if ($row = $db->sql_fetchrow($result)) return (int)$row['customer_type_id'];
		}
		return 0;
	}

	function salesKpiTeamIds()
	{
		global $db;
		$ids = array();
		$departmentId = salesKpiDepartmentId();
		if ($departmentId <= 0) return $ids;
		$sql = "select member_id from tbl_member where active = 1 and (member_type_id = ".$departmentId." or extra_member_type_id = ".$departmentId.") order by fullname";
		if ($result = $db->sql_query($sql)) {
			while ($row = $db->sql_fetchrow($result)) $ids[] = (int)$row['member_id'];
		}
		return $ids;
	}

	function salesKpiOverviewMemberIds()
	{
		return array(34,71);
	}

	function seoKpiDepartmentId()
	{
		global $db;
		$sql = "select customer_type_id from tbl_customer_type where lower(customer_type_name) = 'kt seo' limit 1";
		if ($result = $db->sql_query($sql)) {
			if ($row = $db->sql_fetchrow($result)) return (int)$row['customer_type_id'];
		}
		return 0;
	}

	function seoKpiTeamIds()
	{
		global $db;
		$ids = array();
		$departmentId = seoKpiDepartmentId();
		if ($departmentId <= 0) return $ids;
		$sql = "select member_id from tbl_member where active = 1 and (member_type_id = ".$departmentId." or extra_member_type_id = ".$departmentId.") order by fullname";
		if ($result = $db->sql_query($sql)) {
			while ($row = $db->sql_fetchrow($result)) $ids[] = (int)$row['member_id'];
		}
		return $ids;
	}

	function seoKpiOverviewMemberIds()
	{
		return array(71);
	}

	function seoKpiCanViewOverview()
	{
		if (isset($_SESSION["loginname"]) && $_SESSION["loginname"] == 'administrator') return true;
		$loginId = (int)(isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : 0);
		return in_array($loginId, seoKpiOverviewMemberIds());
	}

	function seoKpiIsViewer($teamIds)
	{
		$loginId = (int)(isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : 0);
		return seoKpiCanViewOverview() || in_array($loginId, $teamIds);
	}

	function salesKpiCanViewOverview()
	{
		if (isset($_SESSION["loginname"]) && $_SESSION["loginname"] == 'administrator') return true;
		$loginId = (int)(isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : 0);
		return in_array($loginId, salesKpiOverviewMemberIds());
	}

	function salesKpiIsViewer($teamIds)
	{
		$loginId = (int)(isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : 0);
		return salesKpiCanViewOverview() || in_array($loginId, $teamIds);
	}

	function salesKpiPercent($revenue, $target)
	{
		if ($target <= 0) return 0;
		return round(($revenue / $target) * 100, 1);
	}

	function salesKpiStatusClass($percent)
	{
		if ($percent >= 100) return 'sales-ok';
		if ($percent >= 80) return 'sales-warn';
		return 'sales-danger';
	}

	function salesKpiIsCompanyWorkExempt($status, $note)
	{
		$text = mb_strtolower(trim($status.' '.$note), 'UTF-8');
		$keywords = array(
			'đi gặp khách hàng',
			'gap khach hang',
			'gặp khách hàng',
			'đi gửi hợp đồng',
			'di gui hop dong',
			'gửi hợp đồng',
			'gui hop dong',
			'đi công tác',
			'di cong tac',
			'công tác',
			'cong tac',
			'đi làm việc công ty',
			'di lam viec cong ty',
			'làm việc bên ngoài',
			'lam viec ben ngoai',
			'ra ngoài làm việc',
			'ra ngoai lam viec'
		);
		foreach ($keywords as $keyword) {
			if ($keyword != '' && mb_strpos($text, $keyword, 0, 'UTF-8') !== false) return true;
		}
		return false;
	}

	function salesKpiAttendanceRowIsLeave($row)
	{
		$checkIn = isset($row['check_in']) ? trim($row['check_in']) : '';
		$checkOut = isset($row['check_out']) ? trim($row['check_out']) : '';
		$status = isset($row['status']) ? trim($row['status']) : '';
		$note = isset($row['note']) ? trim($row['note']) : '';
		if (salesKpiIsCompanyWorkExempt($status, $note)) return false;

		$hasCheckIn = ($checkIn != '' && $checkIn != '-');
		$hasCheckOut = ($checkOut != '' && $checkOut != '-');
		if (!$hasCheckIn || !$hasCheckOut) return true;
		return false;
	}

	function mosDashboardKpiSales()
	{
		global $db, $languageid, $template;

		$teamIds = salesKpiTeamIds();
		$target = 75000000;
		if (!salesKpiIsViewer($teamIds)) {
			$template->assign_vars(array(
				'funname' => 'congno/congno',
				'LANGUAGEID' => $languageid,
				'dashboard_title' => 'Dashboard KPI Kinh Doanh',
				'dashboard_mode' => 'dashboard',
				'team_name' => 'Kinh Doanh',
				'revenue_note' => 'Chỉ tính mục Thu trong Thu Tiền',
				'quarter_display' => 'block',
				'search_display' => 'block',
				'MESSAGE' => 'Bạn không thuộc team Kinh Doanh nên không có quyền xem dashboard KPI Kinh Doanh.',
				'MESSAGE_DISPLAY' => 'block',
				'month' => date('m'),
				'year' => date('Y'),
				'member_id' => 0,
				'overview_option_disabled' => 'disabled="disabled"',
				'target' => number_format($target, 0, ',', '.'),
				'total_revenue' => 0,
				'total_target' => 0,
				'total_missing' => 0,
				'total_percent' => 0,
				'chart_names' => '[]',
				'chart_revenue' => '[]',
				'chart_target' => '[]',
				'daily_labels' => '[]',
				'daily_revenue' => '[]',
			));
			$template->set_filenames_new(array('share' => 'congno/congno_dashboard_sales.html'));
			$template->pparse('share');
			return;
		}

		$month = (int)mosGetParam($_REQUEST, 'month', date('m'));
		$year = (int)mosGetParam($_REQUEST, 'year', date('Y'));
		$memberFilter = (int)mosGetParam($_REQUEST, 'member_id1', 0);
		$loginId = (int)(isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : 0);
		$canViewOverview = salesKpiCanViewOverview();
		if ($month < 1 || $month > 12) $month = (int)date('m');
		if ($year < 2000 || $year > 2100) $year = (int)date('Y');
		if (!$canViewOverview) $memberFilter = $loginId;
		if ($canViewOverview && $memberFilter > 0 && !in_array($memberFilter, $teamIds)) $memberFilter = 0;

		$teamSql = count($teamIds) ? implode(',', $teamIds) : '0';
		$memberCond = ($memberFilter > 0 && in_array($memberFilter, $teamIds)) ? " and m.member_id = ".$memberFilter : "";
		$debtMemberCond = ($memberFilter > 0 && in_array($memberFilter, $teamIds)) ? " and c.member_id = ".$memberFilter : "";
		$taskMemberCond = ($memberFilter > 0 && in_array($memberFilter, $teamIds)) ? " and member_id = ".$memberFilter : "";
		$dateCond = " and SUBSTRING(c.ngay, 4, 2) = '".sprintf('%02d', $month)."' and SUBSTRING(c.ngay, 7, 4) = '".$year."'";
		$moneyExpr = "CAST(REPLACE(REPLACE(ifnull(c.congno_name,0),'.',''),',','') AS UNSIGNED)";

		$summary = array();
		$sql = "
			select
				m.member_id,
				m.fullname,
				sum(case when c.thuchi = 0 then 1 else 0 end) as receipt_count,
				sum(case when c.thuchi = 0 then ".$moneyExpr." else 0 end) as revenue
			from tbl_member m
			left join tbl_congno c on c.member_id = m.member_id and c.active = 1 and c.language_id = ".$languageid." ".$dateCond."
			where m.active = 1 and m.member_id in (".$teamSql.") ".$memberCond."
			group by m.member_id, m.fullname
			order by revenue desc, m.fullname
		";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		while ($row = $db->sql_fetchrow($result)) {
			$revenue = (int)$row['revenue'];
			$percent = salesKpiPercent($revenue, $target);
			$missing = max(0, $target - $revenue);
			$class = salesKpiStatusClass($percent);
			$summary[] = array('member_id' => (int)$row['member_id'], 'name' => $row['fullname'], 'revenue' => $revenue, 'target' => $target, 'missing' => $missing, 'percent' => $percent);
			$template->assign_block_vars('member_kpi', array(
				'member_id' => (int)$row['member_id'],
				'fullname' => htmlspecialchars($row['fullname']),
				'receipt_count' => (int)$row['receipt_count'],
				'revenue' => number_format($revenue, 0, ',', '.'),
				'target' => number_format($target, 0, ',', '.'),
				'missing' => number_format($missing, 0, ',', '.'),
				'percent' => $percent,
				'bar_width' => min(100, $percent),
				'status_class' => $class,
				'status_text' => ($percent >= 100 ? 'Đạt KPI' : 'Chưa đạt KPI'),
			));
		}

		$daysInMonth = (int)date('t', strtotime(sprintf('%04d-%02d-01', $year, $month)));
		$sql = "
			select CAST(SUBSTRING(c.ngay, 1, 2) AS UNSIGNED) as day_num,
				sum(case when c.thuchi = 0 then ".$moneyExpr." else 0 end) as revenue
			from tbl_congno c
			where c.active = 1 and c.language_id = ".$languageid." and c.member_id in (".$teamSql.") ".$debtMemberCond." ".$dateCond."
			group by CAST(SUBSTRING(c.ngay, 1, 2) AS UNSIGNED)
			order by day_num
		";
		$dailyRevenueByDay = array();
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		while ($row = $db->sql_fetchrow($result)) $dailyRevenueByDay[(int)$row['day_num']] = (int)$row['revenue'];

		$dailyLabels = array();
		$dailyRevenue = array();
		for ($day = 1; $day <= $daysInMonth; $day++) {
			$dailyLabels[] = sprintf('%02d', $day);
			$dailyRevenue[] = isset($dailyRevenueByDay[$day]) ? $dailyRevenueByDay[$day] : 0;
		}

		$quarterLabel = 'Năm '.$year;
		$quarterRevenue = array();
		$sql = "
			select c.member_id, CAST(SUBSTRING(c.ngay, 4, 2) AS UNSIGNED) as month_num,
				sum(case when c.thuchi = 0 then ".$moneyExpr." else 0 end) as revenue
			from tbl_congno c
			where c.active = 1 and c.language_id = ".$languageid."
				and c.member_id in (".$teamSql.") ".$debtMemberCond."
				and SUBSTRING(c.ngay, 7, 4) = '".$year."'
			group by c.member_id, CAST(SUBSTRING(c.ngay, 4, 2) AS UNSIGNED)
		";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		while ($row = $db->sql_fetchrow($result)) {
			$quarterMemberId = (int)$row['member_id'];
			$quarterMonth = (int)$row['month_num'];
			if (!isset($quarterRevenue[$quarterMemberId])) $quarterRevenue[$quarterMemberId] = array();
			$quarterRevenue[$quarterMemberId][$quarterMonth] = (int)$row['revenue'];
		}
		foreach ($summary as $item) {
			$memberId = (int)$item['member_id'];
			for ($quarterNo = 1; $quarterNo <= 4; $quarterNo++) {
				$quarterStartMonth = (($quarterNo - 1) * 3) + 1;
				$quarterEndMonth = $quarterStartMonth + 2;
				$quarterTotal = 0;
				$quarterPassed = 0;
				$quarterFailed = 0;
				for ($quarterMonth = $quarterStartMonth; $quarterMonth <= $quarterEndMonth; $quarterMonth++) {
					$monthRevenue = isset($quarterRevenue[$memberId][$quarterMonth]) ? $quarterRevenue[$memberId][$quarterMonth] : 0;
					$quarterTotal += $monthRevenue;
					if ($monthRevenue >= $target) {
						$quarterPassed++;
					} else {
						$quarterFailed++;
					}
				}
				$quarterTarget = $target * 3;
				$quarterPercent = salesKpiPercent($quarterTotal, $quarterTarget);
				$quarterClass = salesKpiStatusClass($quarterPercent);
				$template->assign_block_vars('quarter_kpi', array(
					'fullname' => htmlspecialchars($item['name']),
					'quarter_name' => 'Quý '.$quarterNo.' ('.sprintf('%02d', $quarterStartMonth).'-'.sprintf('%02d', $quarterEndMonth).')',
					'total_revenue' => number_format($quarterTotal, 0, ',', '.'),
					'target' => number_format($quarterTarget, 0, ',', '.'),
					'passed_months' => $quarterPassed,
					'failed_months' => $quarterFailed,
					'percent' => $quarterPercent,
					'bar_width' => min(100, $quarterPercent),
					'status_class' => $quarterClass,
					'status_text' => ($quarterPassed == 3 ? 'Đạt đủ 3 tháng' : 'Chưa đạt '.$quarterFailed.' tháng'),
				));
			}
		}

			$searchDone = array();
		$sql = "
			select member_id, sum(ifnull(giaoviec_num,0)) as done_count
			from tbl_giaoviec
			where active = 1 and soluong = 2 and kpi_type = 'tim_khach_hang'
				and member_id in (".$teamSql.") ".$taskMemberCond."
				and YEAR(created_date) = ".$year."
				and MONTH(created_date) = ".$month."
			group by member_id
		";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		while ($row = $db->sql_fetchrow($result)) $searchDone[(int)$row['member_id']] = (int)$row['done_count'];

			foreach ($summary as $item) {
				$memberId = (int)$item['member_id'];
				$workDays = 0;
				$searchTarget = 0;
				for ($day = 1; $day <= $daysInMonth; $day++) {
					$weekDay = (int)date('N', strtotime(sprintf('%04d-%02d-%02d', $year, $month, $day)));
					if ($weekDay == 7) continue;
					$workDays++;
					$searchTarget += ($weekDay == 6) ? 2 : 4;
				}
				$doneCount = isset($searchDone[$memberId]) ? $searchDone[$memberId] : 0;
				$searchPercent = salesKpiPercent($doneCount, $searchTarget);
				$searchClass = salesKpiStatusClass($searchPercent);
				$template->assign_block_vars('search_kpi', array(
					'fullname' => htmlspecialchars($item['name']),
					'work_days' => $workDays,
					'done_count' => $doneCount,
					'target' => $searchTarget,
				'missing' => max(0, $searchTarget - $doneCount),
				'percent' => $searchPercent,
				'bar_width' => min(100, $searchPercent),
				'status_class' => $searchClass,
				'status_text' => ($searchPercent >= 100 ? 'Đạt KPI' : 'Chưa đạt KPI'),
			));
		}

		$memberListCond = $canViewOverview ? "member_id in (".$teamSql.")" : "member_id = ".$loginId;
		$sql = "select member_id, fullname from tbl_member where active = 1 and ".$memberListCond." order by fullname";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		while ($row = $db->sql_fetchrow($result)) {
			$template->assign_block_vars('member_list', array(
				'member_id' => (int)$row['member_id'],
				'member_name' => htmlspecialchars($row['fullname']),
			));
		}

		$totalRevenue = 0;
		$totalTarget = 0;
		$totalMissing = 0;
		foreach ($summary as $item) {
			$totalRevenue += $item['revenue'];
			$totalTarget += $item['target'];
		}
		$totalMissing = max(0, $totalTarget - $totalRevenue);

		$template->assign_vars(array(
			'funname' => 'congno/congno',
			'LANGUAGEID' => $languageid,
			'dashboard_title' => 'Dashboard KPI Kinh Doanh',
			'dashboard_mode' => 'dashboard',
			'team_name' => 'Kinh Doanh',
			'revenue_note' => 'Chỉ tính mục Thu trong Thu Tiền',
			'quarter_display' => 'block',
			'search_display' => 'block',
			'month' => sprintf('%02d', $month),
			'year' => $year,
			'member_id' => $memberFilter,
			'quarter_label' => $quarterLabel,
			'overview_option_disabled' => $canViewOverview ? '' : 'disabled="disabled"',
			'target' => number_format($target, 0, ',', '.'),
			'total_revenue' => number_format($totalRevenue, 0, ',', '.'),
			'total_target' => number_format($totalTarget, 0, ',', '.'),
			'total_missing' => number_format($totalMissing, 0, ',', '.'),
			'total_percent' => salesKpiPercent($totalRevenue, $totalTarget),
			'chart_names' => json_encode(array_map(function($item) { return $item['name']; }, $summary)),
			'chart_revenue' => json_encode(array_map(function($item) { return $item['revenue']; }, $summary)),
			'chart_target' => json_encode(array_map(function($item) { return $item['target']; }, $summary)),
			'daily_labels' => json_encode($dailyLabels),
			'daily_revenue' => json_encode($dailyRevenue),
			'MESSAGE' => '',
			'MESSAGE_DISPLAY' => 'none',
		));

		$template->set_filenames_new(array('share' => 'congno/congno_dashboard_sales.html'));
		$template->pparse('share');
	}

	function mosDashboardKpiSeo()
	{
		global $db, $languageid, $template;

		$teamIds = seoKpiTeamIds();
		$target = 75000000;
		if (!seoKpiIsViewer($teamIds)) {
			$template->assign_vars(array(
				'funname' => 'congno/congno',
				'LANGUAGEID' => $languageid,
				'dashboard_title' => 'Dashboard KPI KT SEO',
				'dashboard_mode' => 'dashboard_seo',
				'team_name' => 'KT SEO',
				'revenue_note' => 'Tính mục Thu theo website nhân viên KT SEO đang quản lí',
				'quarter_display' => 'none',
				'search_display' => 'none',
				'MESSAGE' => 'Bạn không thuộc team KT SEO nên không có quyền xem dashboard KPI KT SEO.',
				'MESSAGE_DISPLAY' => 'block',
				'month' => date('m'),
				'year' => date('Y'),
				'member_id' => 0,
				'overview_option_disabled' => 'disabled="disabled"',
				'target' => number_format($target, 0, ',', '.'),
				'total_revenue' => 0,
				'total_target' => 0,
				'total_missing' => 0,
				'total_percent' => 0,
				'chart_names' => '[]',
				'chart_revenue' => '[]',
				'chart_target' => '[]',
				'daily_labels' => '[]',
				'daily_revenue' => '[]',
			));
			$template->set_filenames_new(array('share' => 'congno/congno_dashboard_sales.html'));
			$template->pparse('share');
			return;
		}

		$month = (int)mosGetParam($_REQUEST, 'month', date('m'));
		$year = (int)mosGetParam($_REQUEST, 'year', date('Y'));
		$memberFilter = (int)mosGetParam($_REQUEST, 'member_id1', 0);
		$loginId = (int)(isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : 0);
		$canViewOverview = seoKpiCanViewOverview();
		if ($month < 1 || $month > 12) $month = (int)date('m');
		if ($year < 2000 || $year > 2100) $year = (int)date('Y');
		if (!$canViewOverview) $memberFilter = $loginId;
		if ($canViewOverview && $memberFilter > 0 && !in_array($memberFilter, $teamIds)) $memberFilter = 0;

		$teamSql = count($teamIds) ? implode(',', $teamIds) : '0';
		$memberCond = ($memberFilter > 0 && in_array($memberFilter, $teamIds)) ? " and m.member_id = ".$memberFilter : "";
		$seoMemberCond = ($memberFilter > 0 && in_array($memberFilter, $teamIds)) ? " and w.kt_id = ".$memberFilter : "";
		$dateCond = " and SUBSTRING(c.ngay, 4, 2) = '".sprintf('%02d', $month)."' and SUBSTRING(c.ngay, 7, 4) = '".$year."'";
		$moneyExpr = "CAST(REPLACE(REPLACE(ifnull(c.congno_name,0),'.',''),',','') AS UNSIGNED)";

		$summary = array();
		$sql = "
			select
				m.member_id,
				m.fullname,
				sum(case when c.thuchi = 0 then 1 else 0 end) as receipt_count,
				sum(case when c.thuchi = 0 then ".$moneyExpr." else 0 end) as revenue
			from tbl_member m
			left join tbl_website w on w.kt_id = m.member_id and w.active = 1
			left join tbl_congno c on c.website_id = w.website_id and c.active = 1 and c.language_id = ".$languageid." ".$dateCond."
			where m.active = 1 and m.member_id in (".$teamSql.") ".$memberCond."
			group by m.member_id, m.fullname
			order by revenue desc, m.fullname
		";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		while ($row = $db->sql_fetchrow($result)) {
			$revenue = (int)$row['revenue'];
			$percent = salesKpiPercent($revenue, $target);
			$missing = max(0, $target - $revenue);
			$class = salesKpiStatusClass($percent);
			$summary[] = array('member_id' => (int)$row['member_id'], 'name' => $row['fullname'], 'revenue' => $revenue, 'target' => $target, 'missing' => $missing, 'percent' => $percent);
			$template->assign_block_vars('member_kpi', array(
				'member_id' => (int)$row['member_id'],
				'fullname' => htmlspecialchars($row['fullname']),
				'receipt_count' => (int)$row['receipt_count'],
				'revenue' => number_format($revenue, 0, ',', '.'),
				'target' => number_format($target, 0, ',', '.'),
				'missing' => number_format($missing, 0, ',', '.'),
				'percent' => $percent,
				'bar_width' => min(100, $percent),
				'status_class' => $class,
				'status_text' => ($percent >= 100 ? 'Đạt KPI' : 'Chưa đạt KPI'),
			));
		}

		$daysInMonth = (int)date('t', strtotime(sprintf('%04d-%02d-01', $year, $month)));
		$sql = "
			select CAST(SUBSTRING(c.ngay, 1, 2) AS UNSIGNED) as day_num,
				sum(case when c.thuchi = 0 then ".$moneyExpr." else 0 end) as revenue
			from tbl_congno c
			inner join tbl_website w on c.website_id = w.website_id
			where c.active = 1 and c.language_id = ".$languageid." and w.kt_id in (".$teamSql.") ".$seoMemberCond." ".$dateCond."
			group by CAST(SUBSTRING(c.ngay, 1, 2) AS UNSIGNED)
			order by day_num
		";
		$dailyRevenueByDay = array();
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		while ($row = $db->sql_fetchrow($result)) $dailyRevenueByDay[(int)$row['day_num']] = (int)$row['revenue'];

		$dailyLabels = array();
		$dailyRevenue = array();
		for ($day = 1; $day <= $daysInMonth; $day++) {
			$dailyLabels[] = sprintf('%02d', $day);
			$dailyRevenue[] = isset($dailyRevenueByDay[$day]) ? $dailyRevenueByDay[$day] : 0;
		}

		$quarterLabel = 'Năm '.$year;
		$quarterRevenue = array();
		$sql = "
			select w.kt_id as member_id, CAST(SUBSTRING(c.ngay, 4, 2) AS UNSIGNED) as month_num,
				sum(case when c.thuchi = 0 then ".$moneyExpr." else 0 end) as revenue
			from tbl_congno c
			inner join tbl_website w on c.website_id = w.website_id
			where c.active = 1 and c.language_id = ".$languageid."
				and w.kt_id in (".$teamSql.") ".$seoMemberCond."
				and SUBSTRING(c.ngay, 7, 4) = '".$year."'
			group by w.kt_id, CAST(SUBSTRING(c.ngay, 4, 2) AS UNSIGNED)
		";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		while ($row = $db->sql_fetchrow($result)) {
			$quarterMemberId = (int)$row['member_id'];
			$quarterMonth = (int)$row['month_num'];
			if (!isset($quarterRevenue[$quarterMemberId])) $quarterRevenue[$quarterMemberId] = array();
			$quarterRevenue[$quarterMemberId][$quarterMonth] = (int)$row['revenue'];
		}
		foreach ($summary as $item) {
			$memberId = (int)$item['member_id'];
			for ($quarterNo = 1; $quarterNo <= 4; $quarterNo++) {
				$quarterStartMonth = (($quarterNo - 1) * 3) + 1;
				$quarterEndMonth = $quarterStartMonth + 2;
				$quarterTotal = 0;
				$quarterPassed = 0;
				$quarterFailed = 0;
				for ($quarterMonth = $quarterStartMonth; $quarterMonth <= $quarterEndMonth; $quarterMonth++) {
					$monthRevenue = isset($quarterRevenue[$memberId][$quarterMonth]) ? $quarterRevenue[$memberId][$quarterMonth] : 0;
					$quarterTotal += $monthRevenue;
					if ($monthRevenue >= $target) $quarterPassed++;
					else $quarterFailed++;
				}
				$quarterTarget = $target * 3;
				$quarterPercent = salesKpiPercent($quarterTotal, $quarterTarget);
				$quarterClass = salesKpiStatusClass($quarterPercent);
				$template->assign_block_vars('quarter_kpi', array(
					'fullname' => htmlspecialchars($item['name']),
					'quarter_name' => 'Quý '.$quarterNo.' ('.sprintf('%02d', $quarterStartMonth).'-'.sprintf('%02d', $quarterEndMonth).')',
					'total_revenue' => number_format($quarterTotal, 0, ',', '.'),
					'target' => number_format($quarterTarget, 0, ',', '.'),
					'passed_months' => $quarterPassed,
					'failed_months' => $quarterFailed,
					'percent' => $quarterPercent,
					'bar_width' => min(100, $quarterPercent),
					'status_class' => $quarterClass,
					'status_text' => ($quarterPassed == 3 ? 'Đạt đủ 3 tháng' : 'Chưa đạt '.$quarterFailed.' tháng'),
				));
			}
		}

		$memberListCond = $canViewOverview ? "member_id in (".$teamSql.")" : "member_id = ".$loginId;
		$sql = "select member_id, fullname from tbl_member where active = 1 and ".$memberListCond." order by fullname";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		while ($row = $db->sql_fetchrow($result)) {
			$template->assign_block_vars('member_list', array(
				'member_id' => (int)$row['member_id'],
				'member_name' => htmlspecialchars($row['fullname']),
			));
		}

		$totalRevenue = 0;
		$totalTarget = 0;
		$totalMissing = 0;
		foreach ($summary as $item) {
			$totalRevenue += $item['revenue'];
			$totalTarget += $item['target'];
		}
		$totalMissing = max(0, $totalTarget - $totalRevenue);

		$template->assign_vars(array(
			'funname' => 'congno/congno',
			'LANGUAGEID' => $languageid,
			'dashboard_title' => 'Dashboard KPI KT SEO',
			'dashboard_mode' => 'dashboard_seo',
			'team_name' => 'KT SEO',
			'revenue_note' => 'Tính mục Thu theo website nhân viên KT SEO đang quản lí',
			'quarter_display' => 'none',
			'search_display' => 'none',
			'month' => sprintf('%02d', $month),
			'year' => $year,
			'member_id' => $memberFilter,
			'quarter_label' => $quarterLabel,
			'overview_option_disabled' => $canViewOverview ? '' : 'disabled="disabled"',
			'target' => number_format($target, 0, ',', '.'),
			'total_revenue' => number_format($totalRevenue, 0, ',', '.'),
			'total_target' => number_format($totalTarget, 0, ',', '.'),
			'total_missing' => number_format($totalMissing, 0, ',', '.'),
			'total_percent' => salesKpiPercent($totalRevenue, $totalTarget),
			'chart_names' => json_encode(array_map(function($item) { return $item['name']; }, $summary)),
			'chart_revenue' => json_encode(array_map(function($item) { return $item['revenue']; }, $summary)),
			'chart_target' => json_encode(array_map(function($item) { return $item['target']; }, $summary)),
			'daily_labels' => json_encode($dailyLabels),
			'daily_revenue' => json_encode($dailyRevenue),
			'MESSAGE' => '',
			'MESSAGE_DISPLAY' => 'none',
		));

		$template->set_filenames_new(array('share' => 'congno/congno_dashboard_sales.html'));
		$template->pparse('share');
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
