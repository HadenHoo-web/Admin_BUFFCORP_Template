<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'banhang/banhang',
		'LANGUAGEID'=> $languageid,
	));		

	switch( $action )
	{	
		case 'list'	:	mosList(); break;
		case 'baocao'	:	mosBaoCao(); break;
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
function mosList()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$month				= mosGetParam( $_REQUEST, 'month', date('m') );
		$year				= mosGetParam( $_REQUEST, 'year', date('Y') );
		$banhang_kind_id	= mosGetParam( $_REQUEST, 'banhang_kind_id', '0' );
		$member_id 			= mosGetParam( $_REQUEST, 'member_id', '0' );
		$from_date			= mosGetParam( $_REQUEST, 'from_date', date('d') );
		$to_date			= mosGetParam( $_REQUEST, 'to_date', 32 );
		$isimage			= mosGetParam( $_REQUEST, 'isimage', '0' );
		$cond = '';
		$cond .= ($banhang_kind_id != 0)?' and tbl_banhang.banhang_kind_id = '.$banhang_kind_id:'';
		$cond .= ($member_id != 0)?' and tbl_banhang.member_id = '.$member_id:'';
		
		$sql = "select tbl_banhang.*, tbl_products.product_name, tbl_products.soluong, tbl_products.product_id, tbl_products.old_price, tbl_products.slug, tbl_products.image0, tbl_banhang_kind.color, tbl_customer.customer_name, tbl_customer.tel, tbl_customer.customer_id, tbl_member.fullname from (((tbl_banhang inner join tbl_banhang_kind on tbl_banhang.banhang_kind_id = tbl_banhang_kind.banhang_kind_id) left join tbl_products on tbl_banhang.product_code = tbl_products.product_id) LEFT JOIN tbl_customer ON tbl_banhang.customer_id = tbl_customer.customer_id) LEFT JOIN tbl_member ON tbl_banhang.member_id = tbl_member.member_id where SUBSTRING(ngay, 7, 4) = '$year' and SUBSTRING(ngay, 4, 2) = '$month' and SUBSTRING(ngay, 1, 2) >= '$from_date' and SUBSTRING(ngay, 1, 2) < '$to_date' $cond order by banhang_id DESC";
		
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		$sum = 0;
		$tam = "";
		$num_date = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;	
			if ( $tam != $row['ngay'] )
				{
					$num_date ++;
				}	
			$ln = $row['banhang_name']*1 - $row['old_price']*1 ;		
			$template->assign_block_vars('list', array(
				'className'			=>  ($num_date % 2 == 0) ? 'alt' : 'inv',
				'order'				=>  $order,
				'banhang_id'	=>	$row['banhang_id'],
				'banhang_code'=>	$row['banhang_code'],
				'product_id'	  =>	$row['product_id'],
				'product_code'	=>	$row['product_code'],
				'product_name'	=>	$row['product_name'],
				'soluong'		 =>	$row['soluong'],
				'customer_name'   =>	$row['customer_name'],
				'tel'			 =>	$row['tel'],
				'customer_id'	 =>	$row['customer_id'],
				'banhang_name'	=>	number_format($row['banhang_name'], 0, ',', '.'),
				'giagoc'		=>	(($_SESSION['membername'])!="administrator")?'':number_format($row['old_price'], 0, ',', '.'),
				'loinhuan'		=>	(($_SESSION['membername'])!="administrator")?'':number_format($ln, 0, ',', '.'),
				'ngay'		=>	($row['ngay'] == $tam)?'':$row['ngay'],
				'gio'			=>	$row['gio'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',	
				'bg'		=>	$row['color'],	
				'created_by'	=>	$row['fullname'],
				//'slug'			=>	$row['slug'],
				'slug'			   =>	(stripos($row['slug'], "https") === false)?"https://casauhoaca.com/".$row['slug'].".htm":$row['slug'],
				'image0'		=>	$row['image0'],
			));	
				$sum += $row['banhang_name'];
				$tam = $row['ngay'];
				$loinhuan += $ln;
		}
		$template->assign_vars(array(
			'sum'	=>	number_format($sum, 0, ',', '.'),
			'loinhuan'	=> number_format(($loinhuan*0.90)/1000, 0, ',', '.'),
			'month'	=>	$month,
			'year'	=>	$year,
			'banhang_kind_id'	=>	$banhang_kind_id,
			'member_id'	=>	$member_id,
			'from_date'	=>	$from_date,
			'to_date'	=>	$to_date,
			'allow_month'	=>	(($_SESSION['membername'])!="administrator" and $_SESSION["loginname"] != "kieu" and $_SESSION["loginname"] != "thao" )?'disabled':'',
			'allow_display'	=>	(($_SESSION['membername'])!="administrator" )?'none':'',
			'allow_ln'		=>	(($_SESSION['membername'])!="administrator")?'1':'50',
			'allow_image'	=>	($isimage)?'':'none',
			'isimage'		=>	($isimage) ? 'checked' : '',
		));
		$template->set_filenames_new(array(
			'share' => 'banhang/banhang_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$banhang_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		//list sản phẩm chính thức
		$sql = "select * from tbl_products where ismain = 1 order by hoaca_code";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_block_vars('list_product', array(
				'product_id'	=>	$row['product_id'],
				'product_name'  =>	$row['product_name'],
				'hoaca_code'	=>	$row['hoaca_code'],
				'price'		 =>	number_format($row['price'], 0, ',', '.'),
				'soluong'	   =>	$row['soluong'],
			));
		}
		
		//list khách hàng chính thức
		$sql = "select * from tbl_customer order by tel";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_block_vars('list_customer', array(
				'tel'	=>	$row['tel'],
				'customer_name'  =>	$row['customer_name'],
			));
		}
		
		//list nhân viên bán hàng
		$sql = "select * from tbl_member where member_id <> 1 and active = 1 order by member_id DESC";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_block_vars('list_member', array(
				'member_id'		=>	$row['member_id'],
				'fullname'  		 =>	$row['fullname'],
			));
		}

		if ($banhang_id != 0)
		{	$sql = "select * from tbl_banhang where banhang_id = $banhang_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				// lấy số điện thoại
				$sql1 = "select * from tbl_customer where customer_id = '".$row['customer_id']."'";
				if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
				if ( $row1 = $db->sql_fetchrow($result1) )
					$tel = $row1['tel'];
				
				$template->assign_vars(array(
					'banhang_id'	  =>	$banhang_id,
					'banhang_code'	=>	$row['banhang_code'],
					'product_code'	=>	$row['product_code'],
					'banhang_name'	=>	$row['banhang_name'],
					'banhang_kind_id' =>	$row['banhang_kind_id'],
					'ngay'			=>	$row['ngay'],
					'active'		  =>	($row['active'] == 1) ? 'checked' : '',
					'readonly'		=>	($_SESSION['membername']=="administrator" or $_SESSION["loginname"]=="kieu")?'':'readonly',
					'disabled'		=>	($_SESSION['membername']=="administrator" or $_SESSION["loginname"]=="kieu")?'':'disabled',
					'tel'			 =>	($tel)?$tel:0,
					'member_id'	   =>	$row['member_id'],
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'banhang_kind_id' =>	'3',
				'product_code'	=>	'0',
				'tel'			 =>	'0',
				'member_id'	   =>	'0',
				'active'		  =>	(($_SESSION['membername'])!="administrator")?'disabled':'',
				'allow'		   => 	'none',
				'ngay'			=>	date("d-m-Y"),
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'banhang/banhang_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$banhang_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($banhang_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $banhang_id, $direction, "tbl_banhang", "banhang_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$banhang_id 	  = mosGetParam( $_REQUEST, 'id', '0');
		$banhang_code	= mosGetParam( $_REQUEST, 'banhang_code', '');
		$product_code	= mosGetParam( $_REQUEST, 'product_code', '');
		$tel			 = mosGetParam( $_REQUEST, 'sdt', '0');
		$banhang_name	= mosGetParam( $_REQUEST, 'banhang_name', '');
		$banhang_kind_id = mosGetParam( $_REQUEST, 'banhang_kind_id', 0);
		$ngay			= mosGetParam( $_REQUEST, 'ngay', '');
		$active		  = mosGetParam( $_REQUEST, 'active', 0);
		$nokho		   = mosGetParam( $_REQUEST, 'nokho', 0);
		$gio 			 = date('H:i:s');
		$member_id	   = mosGetParam( $_REQUEST, 'member_id', 0);
				
		if ($banhang_id == '' || $tel == '' || $product_code == '')
		{	
			mosInvalidURL();
			exit;
		}
		// chuyển số điện thoại qua customer_id
		$sql = "select * from tbl_customer where tel = '$tel'";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{
			$customer_id = $row['customer_id'];
		}
			
		if ($banhang_id == '0')
		{	
			$priority = mosGetPriority("tbl_banhang", "priority", "");
			$sql = "insert into tbl_banhang (banhang_code, product_code, banhang_name, banhang_kind_id, customer_id, ngay, gio, active, priority, language_id, created_by, modified_by, member_id) values ('$banhang_code', '$product_code', '$banhang_name', '$banhang_kind_id', '$customer_id', '$ngay', CURTIME(), $active, $priority, $languageid, '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "', '$member_id')";
			$sql_update = "update tbl_products set soluong = soluong - 1 where product_id = '$product_code'";
			if ( $nokho == 0 ){
				if ( !($result = $db->sql_query($sql_update)) ) message_die( SERVER_BUSY );
			}
		} else
			{ 
			$sql = "update tbl_banhang set banhang_code = '$banhang_code', product_code = '$product_code', banhang_name ='$banhang_name', banhang_kind_id = '$banhang_kind_id', customer_id = '$customer_id', ngay = '$ngay',  active = $active, language_id=$languageid, member_id = '$member_id', modified_by = '" . $_SESSION['membername'] . "' where banhang_id = $banhang_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$banhang_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($banhang_id == 0){	
      mosInvalidURL();
			exit;
		}	
		if(strtolower($_SESSION['membername'])=="administrator"){	
			deleteByID("tbl_banhang", "banhang_id", $banhang_id);
      $template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
		}else{
		  $template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
		}
		mosList();
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
function mosBaoCao()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$banhang_kind_id	= mosGetParam( $_REQUEST, 'banhang_kind_id', '0' );
		$member_id 		  = mosGetParam( $_REQUEST, 'member_id', '0' );
		$product_code 	   = mosGetParam( $_REQUEST, 'product_code', '0' );
		$from_date		  = mosGetParam( $_REQUEST, 'from_date', date('Y-m-d') );
		$to_date			= mosGetParam( $_REQUEST, 'to_date', date('Y-m-d') );
		$isimage			= mosGetParam( $_REQUEST, 'isimage', '0' );
		$isdang			 = mosGetParam( $_REQUEST, 'isdang', '0' );
		$cond = '';
		$cond .= ($banhang_kind_id != 0)?' and tbl_banhang.banhang_kind_id = '.$banhang_kind_id:'';
		$cond .= ($member_id != 0)?' and tbl_banhang.member_id = '.$member_id:'';
		$cond .= ($product_code == 0)?' and tbl_products.product_id NOT IN ("1562", "1564", "7906", "1565", "8311", "8325")':' and tbl_products.product_id = '.$product_code;
		$cond .= ($product_code == 0)?' and tbl_products.product_type_id NOT IN ("68")':'';
		
		//list sản phẩm chính thức
		$sql = "select * from tbl_products where ismain = 1 order by hoaca_code";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_block_vars('list_product', array(
				'product_id'	=>	$row['product_id'],
				'product_name'  =>	$row['product_name'],
				'hoaca_code'	=>	$row['hoaca_code'],
				'price'		 =>	number_format($row['price'], 0, ',', '.'),
				'soluong'	   =>	$row['soluong'],
			));
		}
		
		if ($isdang == 0){
			$sql = "SELECT tbl_banhang.*, tbl_products.product_name, tbl_products.product_id, tbl_products.price, tbl_products.old_price, tbl_products.slug, tbl_products.image0, tbl_banhang_kind.color, tbl_customer.customer_name, tbl_customer.tel FROM ((tbl_banhang inner join tbl_banhang_kind on tbl_banhang.banhang_kind_id = tbl_banhang_kind.banhang_kind_id) left join tbl_products on tbl_banhang.product_code = tbl_products.product_id) LEFT JOIN tbl_customer ON tbl_banhang.customer_id = tbl_customer.customer_id WHERE CONCAT(SUBSTRING(ngay, 7, 4),'-',SUBSTRING(ngay, 4, 2),'-', SUBSTRING(ngay, 1, 2)) >= '$from_date' AND CONCAT(SUBSTRING(ngay, 7, 4),'-',SUBSTRING(ngay, 4, 2),'-', SUBSTRING(ngay, 1, 2))<= '$to_date' $cond ORDER BY banhang_id DESC LIMIT 1000";
			
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		$sum = 0;
		$sum_price = 0;
		$soluong = 0;
		$tam = "";
		$num_date = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;	
			if ( $tam != $row['ngay'] )
				{
					$num_date ++;
				}	
			$ln = $row['banhang_name']*1 - $row['old_price']*1 ;			
			$template->assign_block_vars('list', array(
				'className'			=>  ($num_date % 2 == 0) ? 'alt' : 'inv',
				'order'				=>  $order,
				'banhang_id'	=>	$row['banhang_id'],
				'banhang_code'=>	$row['banhang_code'],
				'product_id'	  =>	$row['product_id'],
				'product_code'	=>	$row['product_code'],
				'hoaca_code'	  =>	$row['hoaca_code'],
				'product_name'	=>	$row['product_name'],
				'customer_name'   =>	$row['customer_name'],
				'tel'			 =>	$row['tel'],
				'banhang_name'	=>	number_format($row['banhang_name'], 0, ',', '.'),
				'giagoc'		  =>	(($_SESSION['membername'])!="administrator")?'':number_format($row['old_price'], 0, ',', '.'),
				'loinhuan'		=>	(($_SESSION['membername'])!="administrator")?'':number_format($ln, 0, ',', '.'),
				'ngay'			=>	($row['ngay'] == $tam)?'':$row['ngay'],
				'gio'			 =>	$row['gio'],
				'active' 		  =>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'up'			  =>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',	
				'bg'			  =>	$row['color'],	
				'created_by'	  =>	$row['created_by'],
				'slug'			=>	$row['slug'],
				'image0'		  =>	$row['image0'],
				'sl'			  =>	$row['soluong'],
				'price'		   =>	number_format($row['price'], 0, ',', '.'),
				'tt'			  =>	number_format($row['tt'], 0, ',', '.'),	
			));	
				$sum += $row['banhang_name'];
				$sum_price += $row['price'];
				$soluong += $row['sl'];
				$tam = $row['ngay'];
				$loinhuan += $ln;
		$template->assign_vars(array(
			'sum'	=>	number_format($sum, 0, ',', '.'),
			'sum_price'	=>	number_format($sum_price, 0, ',', '.'),
			'soluong'	=>	$soluong,
			'loinhuan'	=> number_format($loinhuan/1000, 0, ',', '.'),
			'month'	=>	$month,
			'year'	=>	$year,
			'banhang_kind_id'	=>	$banhang_kind_id,
			'member_id'	=>	$member_id,
			'from_date'	=>	$from_date,
			'to_date'	=>	$to_date,
			'allow_month'	=>	(($_SESSION['membername'])!="administrator" and $_SESSION["loginname"] != "kieu" and $_SESSION["loginname"] != "diem" )?'disabled':'',
			//'allow_display'	=>	(($_SESSION['membername'])!="administrator" and $_SESSION["loginname"] != "kieu" )?'none':'',
			'allow_display'	=>	(($_SESSION['membername'])!="administrator" )?'none':'',
			'allow_ln'		=>	(($_SESSION['membername'])!="administrator")?'1':'50',
			'allow_image'	=>	($isimage)?'':'none',
			'isimage'		=>	($isimage) ? 'checked' : '',
			'isdang'		=>	($isdang) ? 'checked' : '',
			'product_code'		=>	$product_code,
		));
		}
		}else{ 
			$sql = "SELECT *, sum(price) as tt, count(tbl_banhang.banhang_id) as sl FROM (tbl_banhang INNER JOIN tbl_banhang_kind ON tbl_banhang.banhang_kind_id = tbl_banhang_kind.banhang_kind_id) LEFT JOIN tbl_products ON tbl_banhang.product_code = tbl_products.product_id WHERE CONCAT(SUBSTRING(ngay, 7, 4),'-',SUBSTRING(ngay, 4, 2),'-', SUBSTRING(ngay, 1, 2)) >= '$from_date' AND CONCAT(SUBSTRING(ngay, 7, 4),'-',SUBSTRING(ngay, 4, 2),'-', SUBSTRING(ngay, 1, 2))<= '$to_date' $cond group by hoaca_code ORDER BY hoaca_code";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			$num_row = $db->sql_numrows($result);
			$order = 0;
			$sum_price = 0;
			$soluong = 0;
			while( $row = $db->sql_fetchrow($result) )
			{	
				$order = $order + 1;	
				$tri_gia	= $row['price'] * $row['sl'];
				$tri_gia_von= $row['old_price'] * $row['sl'];
				$template->assign_block_vars('list', array(
					'className'	   =>  ($order % 2 == 0) ? 'alt' : 'inv',
					'order'		   =>  $order,
					'hoaca_code'	  =>	$row['hoaca_code'],
					'sl'			  =>	$row['sl'],
					'price'		   =>	number_format($row['price'], 0, ',', '.'),
					'tri_gia'		 =>	number_format($tri_gia, 0, ',', '.'),
					'old_price'	   =>	number_format($row['old_price'], 0, ',', '.'),
					'tri_gia_von'	 =>	number_format($tri_gia_von, 0, ',', '.'),		
					'banhang_id'	  =>	$row['banhang_id'],
					'product_id'	  =>	$row['product_id'],
					'product_name'	=>	$row['product_name'],
					'slug'			=>	$row['slug'],	
				));	
					$sum_price += $tri_gia;
					$sum_gia_von += $tri_gia_von;
					$soluong += $row['sl'];
				}
			$template->assign_vars(array(
				'sum_price'  =>	number_format($sum_price, 0, ',', '.'),
				'sum_gia_von'=>	number_format($sum_gia_von, 0, ',', '.'),
				'soluong'	=>	$soluong,
				'from_date'  =>	$from_date,
				'to_date'	=>	$to_date,
				'isdang'	 =>	($isdang) ? 'checked' : '',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => ($isdang == 0) ?'banhang/baocao_list.tpl':'banhang/baocao_list_group.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;				
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$template->assign_vars(array(
			'banhang_code'		=>	mosGetParam( $_REQUEST, 'banhang_code', ''),
			'product_code'		=>	mosGetParam( $_REQUEST, 'product_code', ''),
			'banhang_name' 	=>	mosGetParam( $_REQUEST, 'banhang_name', ''),
			'banhang_kind_id'		=>	mosGetParam( $_REQUEST, 'banhang_kind_id', 0),
			'sdt'			=>	mosGetParam( $_REQUEST, 'sdt', ''),
			'ngay'			=>	mosGetParam( $_REQUEST, 'ngay', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'banhang_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'banhang' => 'banhang/banhang_info.tpl')
		);
		
		$template->pparse('banhang');	
	}

?>
