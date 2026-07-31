<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'nhaphang/nhaphang',
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
?>

<?php
function mosList()
	{
		global $db, $root_path, $skin, $languageid, $template;
		$month				= mosGetParam( $_REQUEST, 'month', date('m') );
		$product_type_id	= mosGetParam( $_REQUEST, 'product_type_id', '0' );
		$product_id		   = mosGetParam( $_REQUEST, 'product_id', '0' );
		$nhap_xuat			= mosGetParam( $_REQUEST, 'nhap_xuat', '0' );
		$member_id 			= mosGetParam( $_REQUEST, 'member_id', '0' );
		$from_date			= mosGetParam( $_REQUEST, 'from_date', date('d') );
		$to_date			= mosGetParam( $_REQUEST, 'to_date', 32 );
		$cond = '';
		$cond .= ($product_type_id != 0)?' and tbl_nhaphang.product_type_id = '.$product_type_id:'';
		$cond .= ($product_id != 0)?' and tbl_nhaphang.product_id = '.$product_id:'';
		$cond .= ($nhap_xuat != 0)?' and tbl_nhaphang.nhap_xuat = '.$nhap_xuat:'';
		$cond .= ($member_id != 0)?' and tbl_nhaphang.member_id = '.$member_id:'';
		
		$sql = "select * from tbl_nhaphang inner join tbl_products on tbl_nhaphang.product_id = tbl_products.product_id where SUBSTRING(ngay, 4, 2) = '$month' and SUBSTRING(ngay, 1, 2) >= '$from_date' and SUBSTRING(ngay, 1, 2) < '$to_date' $cond order by nhaphang_id DESC";
		
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		$tam = "";
		$num_date = 0;
		$sum = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;
			$sum += $row['nhaphang_name'];	
			if ( $tam != $row['ngay'] )
				{
					$num_date ++;
				}	
			$ln = $row['nhaphang_name']*1 - $row['old_price']*1 ;			
			$template->assign_block_vars('list', array(
				'className'			=>  ($num_date % 2 == 0) ? 'alt' : 'inv',
				'order'				=>  $order,
				'nhaphang_id'	=>	$row['nhaphang_id'],
				'nhaphang_code'	=>	$row['nhaphang_code'],
				'product_type_id'	=>	$row['product_type_id'],
				'product_id'	=>	$row['product_id'],
				'nhap_xuat'		=>	($row['nhap_xuat']==0)?"nhập hàng +":"xuất hàng -",
				'product_type_name'	=>	$row['product_type_name'],
				'product_name'	=>	$row['product_name'],
				'slug'			=>	$row['slug'],
				'nhaphang_name'	=>	number_format($row['nhaphang_name'], 0, ',', '.'),
				'ngay'		=>	($row['ngay'] == $tam)?'':$row['ngay'],
				'gio'			=>	$row['gio'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',	
				'bg'		=>	$row['color'],	
				'created_by'	=>	$row['created_by'],
			));	
				$tam = $row['ngay'];
		}
		$template->assign_vars(array(
			'month'	=>	$month,
			'product_type_id'	=>	$product_type_id,
			'product_id'	=>	$product_id,
			'nhap_xuat'	=>	$nhap_xuat,
			'member_id'	=>	$member_id,
			'from_date'	=>	$from_date,
			'to_date'	=>	$to_date,
			'sum'		=>	$sum,
			'allow_month'	=>	(($_SESSION['membername'])!="administrator" and $_SESSION["loginname"] != "kieu" )?'disabled':'',
			'allow_display'	=>	(($_SESSION['membername'])!="administrator" and $_SESSION["loginname"] != "kieu1" )?'none':'',
			'allow_ln'		=>	(($_SESSION['membername'])!="administrator")?'1':'60',
		));
		//product_type_list
	$sql = "select * from tbl_product_types where active = 1 order by priority";
	if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
	while ( $row = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('product_type_list', array(
			'product_type_id'	=>	$row['product_type_id'],
			'product_id'	=>	$row['product_id'],
			'product_type_code'	=>	$row['product_type_code'],
			'product_type_name'	=>	$row['product_type_name'],
			'parent_id'			=>	($row['parent_id'] == 0)?'':'&nbsp;&nbsp;&nbsp;&nbsp;',
		));
	}
	
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
		
		$template->set_filenames_new(array(
			'share' => 'nhaphang/nhaphang_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$nhaphang_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($nhaphang_id != 0)
		{	$sql = "select * from tbl_nhaphang inner join tbl_products on tbl_nhaphang.product_id = tbl_products.product_id where nhaphang_id = $nhaphang_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'nhaphang_id'		=>	$nhaphang_id,
					'nhaphang_code'	=>	$row['nhaphang_code'],
					'nhaphang_name'	=>	$row['nhaphang_name'],
					'product_type_id'		=>	$row['product_type_id'],
					'product_id'		=>	$row['product_id'],
					'product_name'		=>	$row['product_name'],
					'slug'			=>	$row['slug'],
					'nhap_xuat'		=>	$row['nhap_xuat'],
					'product_type_name'		=>	$row['product_type_name'],
					'ngay'			=>	$row['ngay'],
					'active'		=>	($row['active'] == 1) ? 'checked' : '',
					'readonly'		=>	($_SESSION['membername']=="administrator" or $_SESSION["loginname"]=="kieu1")?'':'readonly',
					'readonly_nd'	=>	($_SESSION['membername']=="administrator" or $_SESSION["loginname"]=="kieu" or $_SESSION["loginname"]=="hoa")?'':'readonly',
					'disabled'		=>	($_SESSION['membername']=="administrator" or $_SESSION["loginname"]=="kieu1")?'':'disabled',
					'allow_display'	=>	(($_SESSION['membername'])!="administrator" and $_SESSION["loginname"] != "kieu1" )?'none':'',
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'product_type_id'		=>	'0',
				'product_id'		=>	'0',
				'nhap_xuat'		=>	'0',
				'active'		=>	(($_SESSION['membername'])!="administrator")?'disabled':'',
				'allow'			=> 	'none',
				'ngay'			=>	date("d-m-Y"),
			));
		}
		
		//product_type_list
		$sql = "select * from tbl_product_types where active = 1 order by priority";
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('product_type_list', array(
				'product_type_id'	=>	$row['product_type_id'],
				'product_id'	=>	$row['product_id'],
				'product_type_code'	=>	$row['product_type_code'],
				'product_type_name'	=>	$row['product_type_name'],
				'parent_id'			=>	($row['parent_id'] == 0)?'':'&nbsp;&nbsp;&nbsp;&nbsp;',
			));
		}
		
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
		
		$template->set_filenames_new(array(
			'share' => 'nhaphang/nhaphang_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$nhaphang_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($nhaphang_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $nhaphang_id, $direction, "tbl_nhaphang", "nhaphang_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$nhaphang_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$nhaphang_code	= mosGetParam( $_REQUEST, 'nhaphang_code', '');
		$nhaphang_name	= mosGetParam( $_REQUEST, 'nhaphang_name', '0');
		$product_type_id	= mosGetParam( $_REQUEST, 'product_type_id', 0);
		$product_id	= mosGetParam( $_REQUEST, 'product_id', 0);
		$nhap_xuat		= mosGetParam( $_REQUEST, 'nhap_xuat', 0);
		$ngay			= mosGetParam( $_REQUEST, 'ngay', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$gio 			= date('H:i:s');

		if ($nhaphang_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($nhaphang_id == '0')
		{	
			$priority = mosGetPriority("tbl_nhaphang", "priority", "");
			$sql = "insert into tbl_nhaphang (nhaphang_code, nhaphang_name, product_id, product_type_id, nhap_xuat, ngay, gio, active, priority, language_id, created_by, modified_by, member_id) values ('$nhaphang_code', '$nhaphang_name', '$product_id', '$product_type_id', '$nhap_xuat', '$ngay', CURTIME(), $active, $priority, $languageid, '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "', '" . $_SESSION['login_id'] . "')";
			if( $nhap_xuat == 0 )
				$sql_update = "update tbl_products set soluong = soluong + $nhaphang_name where product_id = '$product_id'";
			else
				$sql_update = "update tbl_products set soluong = soluong - $nhaphang_name where product_id = '$product_id'";


			if ( !($result = $db->sql_query($sql_update)) ) message_die( SERVER_BUSY );
		} else
			{ 
			$sql = "update tbl_nhaphang set nhaphang_code = '$nhaphang_code', nhaphang_name ='$nhaphang_name', product_id = '$product_id', product_type_id = '$product_type_id', nhap_xuat = '$nhap_xuat', ngay = '$ngay',  active = $active, language_id=$languageid, modified_by = '" . $_SESSION['membername'] . "' where nhaphang_id = $nhaphang_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$nhaphang_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($nhaphang_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		if(strtolower($_SESSION['membername'])=="administrator") 
		{			
			deleteByID("tbl_nhaphang", "nhaphang_id", $nhaphang_id);
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
			'nhaphang_code'		=>	mosGetParam( $_REQUEST, 'nhaphang_code', ''),
			'nhaphang_name' 	=>	mosGetParam( $_REQUEST, 'nhaphang_name', ''),
			'product_type_id'		=>	mosGetParam( $_REQUEST, 'product_type_id', 0),
			'product_id'		=>	mosGetParam( $_REQUEST, 'product_id', 0),
			'nhap_xuat'		=>	mosGetParam( $_REQUEST, 'nhap_xuat', 0),
			'ngay'			=>	mosGetParam( $_REQUEST, 'ngay', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'nhaphang_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'nhaphang' => 'nhaphang/nhaphang_info.tpl')
		);
		
		$template->pparse('nhaphang');	
	}
?>
