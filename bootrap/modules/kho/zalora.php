<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'kho/zalora',
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
		$loaikho_id 	 = mosGetParam( $_REQUEST, 'loaikho_id', 0 );
		$cond	= "";
		$cond .= ($loaikho_id != 0)?' and tbl_kho.loaikho_id = '.$loaikho_id:'';
		$sql = "select tbl_kho.*, tbl_products.product_name, tbl_products.product_code, tbl_products.old_price, tbl_products.price, tbl_loaikho.loaikho_name from (tbl_kho left join tbl_products on tbl_kho.product_id = tbl_products.product_id) left join tbl_loaikho on tbl_kho.loaikho_id = tbl_loaikho.loaikho_id where 1 $cond order by loaikho_id, priority";
		
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		$sum	= 0;
		$von = 0;
		$loi = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;
			$sum	= $sum + $row['soluong'];		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 0) ? 'alt' : 'inv',
				'order'				=>  $order,
				'kho_id'			=>	$row['kho_id'],
				'loaikho_id'		=>	$row['loaikho_id'],
				'loaikho_name'		=>	$row['loaikho_name'],
				'product_id'		=>	$row['product_id'],
				'product_code'		=>	$row['product_code'],
				'product_name'		=>	$row['product_code']." - ".$row['product_name'],
				'soluong'			=>	$row['soluong'],
				'giagoc'			=>	(strtolower($_SESSION['membername'])=="administrator" || $_SESSION['loginname']=="kieu")?number_format($row['old_price'], 0, ',', '.'):'',
				'giaban'			=>	number_format($row['price'], 0, ',', '.'),
				'noidung'			=>	$row['noidung'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
				'created_by'	=>	$row['created_by'],
			));	
			$von += $row['old_price']*$row['soluong'];
			$loi += $row['price']*$row['soluong'];
		}
		$template->assign_vars( array(
			'loaikho_id'	=>	$loaikho_id,
			'sum'	=>	$sum,
			'von'				=>	(strtolower($_SESSION['membername'])=="administrator")?number_format($von/1000000, 0, ',', '.'):'',
			'loi'				=>	(strtolower($_SESSION['membername'])=="administrator")?number_format($loi/1000000, 0, ',', '.'):'',
		));
		$template->set_filenames_new(array(
			'share' => 'kho/zalora_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$kho_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($kho_id != 0)
		{	$sql = "select * from tbl_kho where kho_id = $kho_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'kho_id'		=>	$kho_id,
					'product_id'	=>	$row['product_id'],
					'loaikho_id'	=>	$row['loaikho_id'],
					'soluong'		=>	$row['soluong'],
					'noidung'		=>	$row['noidung'],
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'loaikho_id'	=>	'0',
				'nhap_xuat'		=>	'0',
				'active'		=>	(($_SESSION['membername'])!="administrator")?'disabled':'',
				'allow'			=> 	'none',
				'ngay'			=>	date("d-m-Y"),
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'kho/zalora_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$kho_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($kho_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $kho_id, $direction, "tbl_kho", "kho_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$kho_id 	= mosGetParam( $_REQUEST, 'id', 0);
		$loaikho_id	= mosGetParam( $_REQUEST, 'loaikho_id', '0');
		$product_id	= mosGetParam( $_REQUEST, 'product_id', '0');
		$soluong	= mosGetParam( $_REQUEST, 'soluong', '');
		$noidung	= mosGetParam( $_REQUEST, 'noidung', '');
		$active		= mosGetParam( $_REQUEST, 'active', 0);
	
		if ($kho_id == '0')
		{	
			$priority = mosGetPriority("tbl_kho", "priority", "");
			$sql = "insert into tbl_kho (loaikho_id, product_id, soluong, noidung, active, priority, language_id, created_by, modified_by, member_id) values ('$loadkho_id', '$product_id', '$soluong', '$noidung', 1, $priority, $languageid, '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "', '" . $_SESSION['login_id'] . "')";
		} else
		{ 
			$sql = "update tbl_kho set loaikho_id = '$loaikho_id', product_id ='$product_id', soluong = '$soluong', noidung = '$noidung', active = $active, language_id=$languageid, modified_by = '" . $_SESSION['membername'] . "' where kho_id = $kho_id";
		}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$kho_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($kho_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		if(strtolower($_SESSION['membername'])=="administrator") 
		{			
			deleteByID("tbl_kho", "kho_id", $kho_id);
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
			'kho_code'		=>	mosGetParam( $_REQUEST, 'kho_code', ''),
			'kho_name' 	=>	mosGetParam( $_REQUEST, 'kho_name', ''),
			'product_type_id'		=>	mosGetParam( $_REQUEST, 'product_type_id', 0),
			'nhap_xuat'		=>	mosGetParam( $_REQUEST, 'nhap_xuat', 0),
			'ngay'			=>	mosGetParam( $_REQUEST, 'ngay', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'kho_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'kho' => 'kho/zalora_info.tpl')
		);
		
		$template->pparse('kho');	
	}
?>
