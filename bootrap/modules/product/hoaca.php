<?php	
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();		
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'product/hoaca',
		'LANGUAGEID'=> $languageid,
	));		
	switch( $action )
	{	case 'list'				:	mosList(); break;
		case 'info'				:	mosInfo(); break;
		case 'up'				:  	mosMove('up'); break;
		case 'down' 			:  	mosMove('down'); break;
		case 'save'				:	mosSave(); break;
		case 'savecopy'			:	mosSaveCopy(); break;
		case 'delete'  			:	mosDelete(); break;  
		case 'reshow'  			:	mosReshow(); break; 
		case 'export'			:	mosExport(); break;

		default:		
			mosInvalidURL();
			exit;
	}
?>
<?php

function mosList()
{		
	global $db, $root_path, $skin, $languageid, $template;
	$template->set_filenames_new(array(
		'product' => 'product/hoaca_list.tpl')
	);
	$product_type_id	= mosGetParam( $_REQUEST, 'product_type_id', 0 );
	$cond = '';
	$cond .= ( $product_type_id == 0 )?'':' and product_type_id = '.$product_type_id;
	$sql = "select * from tbl_hoaca where 1 $cond order by product_code";
	
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order = 1;
	$von = 0;
	$loi = 0;
	while( $row = $db->sql_fetchrow($result) )
	{					
		$template->assign_block_vars('list', array(
			'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
			'order'				=>  $order,
			'product_id'		=>	$row['product_id'],
			'product_code'		=>	$row['product_code'],
			'product_name'		=>	$row['product_name'],
			'old_price'			=>	(strtolower($_SESSION['membername'])=="administrator")?number_format($row['old_price'], 0, ',', '.'):'',
			'price'				=>	number_format($row['price'], 0, ',', '.'),
			'view'				=>	$row['view'],
			'soluong'			=>	$row['soluong'],
			'bgcolor'			=>	($row['soluong'] > 0)?'1':'',
			'website'			=>	$row['website'],
			'description'		=>	$row['description'],
			'active' 		=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
			'isadv' 		=>	($row['isadv'] == 1) ? '' : 'none',
			'up'			=>	($order == 1) ? ' display: none;' : '',
			'down'			=>	($order == $num_row) ? ' display: none;' : '',		
		));	
		$von += $row['old_price']*$row['soluong'];
		$loi += $row['price']*$row['soluong'];
		$order ++;
	}
	$template->assign_vars(array(
		'product_type_id'	=>	$product_type_id,
		'von'				=>	(strtolower($_SESSION['membername'])=="administrator")?number_format($von, 0, ',', '.'):'',
		'loi'				=>	(strtolower($_SESSION['membername'])=="administrator")?number_format($loi, 0, ',', '.'):'',
	));
	
	$template->pparse('product');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo($copy=0)
{	
	global $db, $root_path, $skin, $languageid, $template;
	$product_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
	if ( $product_id == 0 ) $product_id = $copy;
	if ($product_id != 0)
	{	$sql = "select * from tbl_hoaca where product_id = $product_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{	
			$template->assign_vars(array(
				'product_id'		=>	($copy == 0)?$product_id:'',
				'product_code'		=>	($copy == 0)?$row['product_code']:mosCode(),
				'product_name'		=>	$row['product_name'],
				'product_type_id'	=>	$row['product_type_id'],
				'product_kind_id'	=>	$row['product_kind_id'],
				'skin_id'			=>	$row['skin_id'],
				'color_id'			=>	$row['color_id'],
				'size'				=>	$row['size'],
				'view'				=>	$row['view'],
				'soluong'			=>	$row['soluong'],
	
				'old_price'			=>	$row['old_price'],
				'price'				=>	$row['price'],
				'website'			=>	$row['website'],
				'description'		=>	$row['description'],
				'active'			=>	($row['active'] == 1) ? 'checked' : '',
				'isadv'				=>	($row['isadv'] == 1) ? 'checked' : '',
				'image0'			=>	$row['image0'],
				'image1'			=>	$row['image1'],
				'image2'			=>	$row['image2'],
				'image3'			=>	$row['image3'],
				'image4'			=>	$row['image4'],
				'allow_image0'		=>	($row['image0'])?"":"none",
				'allow_image1'		=>	($row['image1'])?"":"none",
				'allow_image2'		=>	($row['image2'])?"":"none",
				'allow_image3'		=>	($row['image3'])?"":"none",
				'allow_image4'		=>	($row['image4'])?"":"none",
				'readonly'			=>	'readonly',		
				'isadmin'			=>	($_SESSION['membername']=="administrator")?'':'none',
				'readonly'			=>	($_SESSION['membername']=="administrator")?'':'readonly',
			));
		} else
			message_die( ID_NOTFOUND );		
	} else
	{			
		$template->assign_vars(array(
			'product_code'		=>	mosCode(),
			'product_kind_id'	=> '0',
			'product_type_id'	=> '0',
			'skin_id'			=> '0',	
			'color_id'			=> '0',	
			'soluong'			=>	'1',		
			'active'			=>	'checked' ,
			'allow_image0'		=>	"none",
			'allow_image1'		=>	"none",
			'allow_image2'		=>	"none",
			'allow_image3'		=>	"none",
			'allow_image4'		=>	"none",
		));
	}	
	//product_type_list
	$sql = "select * from tbl_product_types where active = 1 order by priority";
	if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
	while ( $row = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('product_type_list', array(
			'product_type_id'	=>	$row['product_type_id'],
			'product_type_code'	=>	$row['product_type_code'],
			'product_type_name'	=>	$row['product_type_name'],
		));
	}
	$template->set_filenames_new(array(
		'product' => 'product/hoaca_info.tpl')
	);
	$template->pparse('product');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$product_id  		= mosGetParam( $_REQUEST, 'id', '0');
		$product_code  		= mosGetParam( $_REQUEST, 'product_code', '');
		$product_name  		= mosGetParam( $_REQUEST, 'product_name', '');
		$product_type_id	= mosGetParam( $_REQUEST, 'product_type_id', 0);
		$product_kind_id	= mosGetParam( $_REQUEST, 'product_kind_id', 0);
		$skin_id			= mosGetParam( $_REQUEST, 'skin_id', 0);
		$color_id			= mosGetParam( $_REQUEST, 'color_id', 0);
		$size				= mosGetParam( $_REQUEST, 'size', '');
		$view				= mosGetParam( $_REQUEST, 'view', '');
		$soluong			= mosGetParam( $_REQUEST, 'soluong', '');
		$old_price			= mosGetParam( $_REQUEST, 'old_price', '');
		$price				= mosGetParam( $_REQUEST, 'price', '');
		$website			= mosGetParam( $_REQUEST, 'website', '');
		$description		= mosGetParam( $_REQUEST, 'description', '', 0x0003);
		$active				= mosGetParam( $_REQUEST, 'active', '0');
		$isadv				= mosGetParam( $_REQUEST, 'isadv', '0');
		
		$image0			= mosGetParam( $_REQUEST, 'image0', '');
		$old_image0		= mosGetParam( $_REQUEST, 'old_image0', '');
		$remove_image0	= mosGetParam( $_REQUEST, 'remove_image0', '');
		$image1			= mosGetParam( $_REQUEST, 'image1', '');
		$old_image1		= mosGetParam( $_REQUEST, 'old_image1', '');
		$remove_image1	= mosGetParam( $_REQUEST, 'remove_image1', '');
		$image2			= mosGetParam( $_REQUEST, 'image2', '');
		$old_image2		= mosGetParam( $_REQUEST, 'old_image2', '');
		$remove_image2	= mosGetParam( $_REQUEST, 'remove_image2', '');
		$image3			= mosGetParam( $_REQUEST, 'image3', '');
		$old_image3		= mosGetParam( $_REQUEST, 'old_image3', '');
		$remove_image3	= mosGetParam( $_REQUEST, 'remove_image3', '');
		$image4			= mosGetParam( $_REQUEST, 'image4', '');
		$old_image4		= mosGetParam( $_REQUEST, 'old_image4', '');
		$remove_image4	= mosGetParam( $_REQUEST, 'remove_image4', '');
		
		if ($product_code == '')
		{	
			mosInvalidURL();
			exit;
		}
		
		$imgDir = $root_path . "images/product/";		
		if (! is_dir($imgDir))
			mkdir($imgDir, 0666);				
			
		/*$image0 = mosUploadImage($imgDir, "image0");
		if (($remove_image0 == 0) && ($image0 == ''))
			$image0 = $old_image0;	
		$image1 = mosUploadImage($imgDir, "image1");
		if (($remove_image1 == 0) && ($image1 == ''))
			$image1 = $old_image1;
		$image2 = mosUploadImage($imgDir, "image2");
		if (($remove_image2 == 0) && ($image2 == ''))
			$image2 = $old_image2;
		$image3 = mosUploadImage($imgDir, "image3");
		if (($remove_image3 == 0) && ($image3 == ''))
			$image3 = $old_image3;
		$image4 = mosUploadImage($imgDir, "image4");
		if (($remove_image4 == 0) && ($image4 == ''))
			$image4 = $old_image4;*/
		
		if (checkDuplicate("tbl_hoaca", array('product_code' => $product_code), "product_id", $product_id, false, ""))
		{	
			$template->assign_vars(array('MESSAGE'	=>	DUPLICATE_ENTRY));
			mosList();		
			exit;
		}		

		if ($product_id == '0')
		{	
			$priority = mosGetPriority("tbl_hoaca", "priority", "");
			$sql = "insert into tbl_hoaca (product_code, product_name, product_type_id, product_kind_id, skin_id, color_id, size, view, soluong, old_price, price, website, description, active, isadv, created_date, created_by, priority, image0, image1, image2, image3, image4, language_id) values ('$product_code', '$product_name', '$product_type_id', '$product_kind_id', '$skin_id', '$color_id', '$size', '$view', '$soluong', '$old_price', '$price', '$website', '$description', $active, $isadv, now(), '".$_SESSION['membername']."', $priority, '$image0', '$image1', '$image2', '$image3', '$image4', '$languageid')";	
		} else
		{ 
			$sql = "update tbl_hoaca set product_code='$product_code', product_name = '$product_name', product_type_id = '$product_type_id', product_kind_id = '$product_kind_id', skin_id = '$skin_id', color_id = '$color_id', size = '$size', view = '$view', soluong = '$soluong', old_price = '$old_price', price = '$price', website = '$website', description = '$description' , active = $active, isadv = $isadv, image0 = '$image0', image1 = '$image1', image2 = '$image2', image3 = '$image3', image4 = '$image4', language_id='$languageid' where product_id = $product_id";
		}
		
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		
		$arrField = array("image0","image1","image2","image3","image4");
		
		checkDeleteOldFile($image0, $old_image0, $remove_image0, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image1, $old_image1, $remove_image1, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image2, $old_image2, $remove_image2, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image3, $old_image3, $remove_image3, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image4, $old_image4, $remove_image4, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);

		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosSaveCopy()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$product_id  		= mosGetParam( $_REQUEST, 'id', '0');
		$product_code  		= mosGetParam( $_REQUEST, 'product_code', '');
		$product_name  		= mosGetParam( $_REQUEST, 'product_name', '');
		$product_type_id	= mosGetParam( $_REQUEST, 'product_type_id', 0);
		$product_kind_id	= mosGetParam( $_REQUEST, 'product_kind_id', 0);
		$skin_id			= mosGetParam( $_REQUEST, 'skin_id', 0);
		$color_id			= mosGetParam( $_REQUEST, 'color_id', 0);
		$size				= mosGetParam( $_REQUEST, 'size', '');
		$view				= mosGetParam( $_REQUEST, 'view', '');
		$soluong			= mosGetParam( $_REQUEST, 'soluong', '');
		$old_price			= mosGetParam( $_REQUEST, 'old_price', '');
		$price				= mosGetParam( $_REQUEST, 'price', '');
		$website			= mosGetParam( $_REQUEST, 'website', '');
		$description		= mosGetParam( $_REQUEST, 'description', '', 0x0003);
		$active				= mosGetParam( $_REQUEST, 'active', '0');
		$isadv				= mosGetParam( $_REQUEST, 'isadv', '0');
		
		$image0			= mosGetParam( $_REQUEST, 'image0', '');
		$old_image0		= mosGetParam( $_REQUEST, 'old_image0', '');
		$remove_image0	= mosGetParam( $_REQUEST, 'remove_image0', '');
		$image1			= mosGetParam( $_REQUEST, 'image1', '');
		$old_image1		= mosGetParam( $_REQUEST, 'old_image1', '');
		$remove_image1	= mosGetParam( $_REQUEST, 'remove_image1', '');
		$image2			= mosGetParam( $_REQUEST, 'image2', '');
		$old_image2		= mosGetParam( $_REQUEST, 'old_image2', '');
		$remove_image2	= mosGetParam( $_REQUEST, 'remove_image2', '');
		$image3			= mosGetParam( $_REQUEST, 'image3', '');
		$old_image3		= mosGetParam( $_REQUEST, 'old_image3', '');
		$remove_image3	= mosGetParam( $_REQUEST, 'remove_image3', '');
		$image4			= mosGetParam( $_REQUEST, 'image4', '');
		$old_image4		= mosGetParam( $_REQUEST, 'old_image4', '');
		$remove_image4	= mosGetParam( $_REQUEST, 'remove_image4', '');
		
		if ($product_code == '')
		{	
			mosInvalidURL();
			exit;
		}
		
		$imgDir = $root_path . "images/product/";		
		if (! is_dir($imgDir))
			mkdir($imgDir, 0666);				
			
		/*$image0 = mosUploadImage($imgDir, "image0");
		if (($remove_image0 == 0) && ($image0 == ''))
			$image0 = $old_image0;	
		$image1 = mosUploadImage($imgDir, "image1");
		if (($remove_image1 == 0) && ($image1 == ''))
			$image1 = $old_image1;
		$image2 = mosUploadImage($imgDir, "image2");
		if (($remove_image2 == 0) && ($image2 == ''))
			$image2 = $old_image2;
		$image3 = mosUploadImage($imgDir, "image3");
		if (($remove_image3 == 0) && ($image3 == ''))
			$image3 = $old_image3;
		$image4 = mosUploadImage($imgDir, "image4");
		if (($remove_image4 == 0) && ($image4 == ''))
			$image4 = $old_image4;*/
		
		if (checkDuplicate("tbl_hoaca", array('product_code' => $product_code), "product_id", $product_id, false, ""))
		{	
			$template->assign_vars(array('MESSAGE'	=>	DUPLICATE_ENTRY));
			mosList();		
			exit;
		}		

		if ($product_id == '0')
		{	
			$sql = "select if(max(product_id) is null, 1, max(product_id) + 1) as product_id  from tbl_hoaca";
			if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
			if( $row = $db->sql_fetchrow($result) )	
			{	
				$product_id = $row['product_id'];
			}
			else
			{	message_die(SERVER_BUSY);
				exit;
			}
			$priority = mosGetPriority("tbl_hoaca", "priority", "");
			$sql = "insert into tbl_hoaca (product_id, product_code, product_name, product_type_id, product_kind_id, skin_id, color_id, size, view, soluong, old_price, price, website, description, active, isadv, created_date, created_by, priority, image0, image1, image2, image3, image4, language_id) values ('$product_id', '$product_code', '$product_name', '$product_type_id', '$product_kind_id', '$skin_id', '$color_id', '$size', '$view', '$soluong', '$old_price', '$price', '$website', '$description', $active, $isadv, now(), '".$_SESSION['membername']."', $priority, '$image0', '$image1', '$image2', '$image3', '$image4', '$languageid')";	
		} else
		{ 
			$sql = "update tbl_hoaca set product_code='$product_code', product_name = '$product_name', product_type_id = '$product_type_id', product_kind_id = '$product_kind_id', skin_id = '$skin_id', color_id = '$color_id', size = '$size', view = '$view', soluong = '$soluong', old_price = '$old_price', price = '$price', website = '$website', description = '$description' , active = $active, isadv = $isadv, image0 = '$image0', image1 = '$image1', image2 = '$image2', image3 = '$image3', image4 = '$image4', language_id='$languageid' where product_id = $product_id";
		}
		
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		
		$arrField = array("image0","image1","image2","image3","image4");
		
		checkDeleteOldFile($image0, $old_image0, $remove_image0, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image1, $old_image1, $remove_image1, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image2, $old_image2, $remove_image2, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image3, $old_image3, $remove_image3, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image4, $old_image4, $remove_image4, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);

		mosInfo($product_id);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
	function mosDelete()
	{	global $template, $db, $root_path;	
		$product_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($product_id == 0)
		{	mosInvalidURL();
			exit;
		}
		if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			$sql = "select image0, image1, image2, image3, image4 from tbl_hoaca where product_id = $product_id";
			if ( !($result = $db->sql_query($sql)) ) mosInvalidUrl();
			if ( $row = $db->sql_fetchrow($result))
			{
				$old_image0 = $row['image0'];
				$old_image1 = $row['image1'];
				$old_image2 = $row['image2'];
				$old_image3 = $row['image3'];
				$old_image4 = $row['image4'];
			}
			deleteByID("tbl_hoaca", "product_id", $product_id);
			$template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
		
			$imgDir = $root_path . "images/product/";
			$arrField = array("image0","image1","image2","image3","image4");
			checkDeleteOldFile('', $old_image0, 1, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);
			checkDeleteOldFile('', $old_image1, 1, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);
			checkDeleteOldFile('', $old_image2, 1, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);
			checkDeleteOldFile('', $old_image3, 1, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);
			checkDeleteOldFile('', $old_image4, 1, $imgDir , "tbl_hoaca", $arrField, "product_id", $product_id);
		}else
		{
			$template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
		}
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosMove( $direction )
	{	
		global $languageid;
		$product_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($product_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $product_id, $direction, "tbl_hoaca", "product_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosUpdateService($product_id)
	{	
		global $db, $root_path, $skin, $languageid, $template;
		
		//xoa service_product cu
		$sql = "delete from tbl_hoaca_service where product_id = $product_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		
		$sql = "select * from tbl_service where language_id = $languageid order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result) )
		{	$service_id = $row['service_id'];
			$service	=	mosGetParam( $_REQUEST, $service_id, '0' );
			$sql1 = "insert into tbl_hoaca_service ( `product_id`, `service_id` , `chk`) values ( '$product_id', '$service_id', '$service' )";
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		}
	}	
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosServiceList($product_id)
	{	
		global $db, $root_path, $skin, $languageid, $template;
		if ( $product_id == 0 )
		{
			$sql = "select * from tbl_service where language_id = $languageid order by priority";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			$order = 0;
			while ( $row = $db->sql_fetchrow($result) )
			{	$order+=1;
				$template->assign_block_vars('service',array(
					'service_id'	=>	$row['service_id'],
					'service_name'	=>	$row['service_name'],
					'tr'			=>	($order%4==0)?'</tr><tr>':'',
					'chk'			=>	'',
				));
			}	
		}else
		{
			$sql = "select * from tbl_hoaca where product_id = $product_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{
				$tinh = $row['tinh'];
			}
			$sql = "select * from tbl_service where active = 1";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			$order = 0;
			while ( $row = $db->sql_fetchrow($result) )
			{	
				$chk = 0;
				$service_id	=	$row['service_id'];
				$sql1 = "select * from tbl_hoaca_service where service_id = $service_id and product_id = $product_id";
				if ( !($result1 = $db->sql_query($sql1)) ) die( SERVER_BUSY );
				if ( $row1 = $db->sql_fetchrow($result1) )
				{
					$chk = $row1['chk'];
				} 
				$order+=1;
				$template->assign_block_vars('service',array(
					'service_id'	=>	$row['service_id'],
					'service_name'	=>	$row['service_name'],
					'tr'			=>	($order%4==0)?'</tr><tr>':'',
					'chk'			=>	($chk == 1) ? 'checked' : '',
				));
			}
			
		}		
	}	
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosReshow()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$product_id  		= mosGetParam( $_REQUEST, 'id', '0');
		$product_code  	= mosGetParam( $_REQUEST, 'product_code', '');
		$product_name  	= mosGetParam( $_REQUEST, 'product_name', '');
		$address		= mosGetParam( $_REQUEST, 'address', '');
		$province_id	= mosGetParam( $_REQUEST, 'province', '');
		$price_min		= mosGetParam( $_REQUEST, 'price_min', '');
		$price_max		= mosGetParam( $_REQUEST, 'price_max', '');
		$total_room		= mosGetParam( $_REQUEST, 'total_room', '');
		$classifies_id	= mosGetParam( $_REQUEST, 'classifies', '');
		$product_cat_id	= mosGetParam( $_REQUEST, 'product_cat_id', '');
		$active			= mosGetParam( $_REQUEST, 'active', '0');
		$isadv			= mosGetParam( $_REQUEST, 'isadv', '0');
		$telephone		= mosGetParam( $_REQUEST, 'telephone', '');
		$email			= mosGetParam( $_REQUEST, 'email', '');
		$website		= mosGetParam( $_REQUEST, 'website', '');
		$link_map		= mosGetParam( $_REQUEST, 'link_map', '');
		$fax			= mosGetParam( $_REQUEST, 'fax', '');
		$description	= mosGetParam( $_REQUEST, 'description', '', 0x0003);
		
		if ( $product_id !=0 )
		{
			mosInfo();
			exit;
		}
		
		$template->assign_vars(array(
				'product_id'		=>	$product_id,
				'product_code'	=>	$product_code,
				'product_name'	=>	$product_name,
				'product_address'	=>	$address,
				'province_id'	=>	$province_id,
				'price_min'		=> 	$price_min,
				'price_max'		=> 	$price_max,
				'total_room'	=>	$total_room,
				'classifies_id'		=> 	$classifies_id,
				'product_cat_id'	=>	$product_cat_id,
				'active'		=>	($active == 1) ? 'checked' : '',
				'isadv'			=>	($isadv == 1) ? 'checked' : '',
				'email'			=> 	$email,
				'telephone'		=> 	$telephone,
				'website'		=> 	$website,
				'link_map'		=> 	$link_map,
				'description'	=>	$description,
				'fax'			=>	$fax,				
				'allow_product_logo'	=>	"none",
				'allow_image0'	=>	"none",
				'allow_image1'	=>	"none",
				'allow_large_img'	=>	"none",
				'allow_small_map'	=>	"none",
				'allow_large_map'	=>	"none",	
			));
				
	$template->set_filenames_new(array(
		'product' => 'product/hoaca_info.tpl')
	);
	$template->pparse('product');
		
		
		
			
	}	
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------		
function mosCode()
	{	
		global $db, $languageid, $template;
		$sql = "select if(max(product_id) is null, 1, max(product_id) + 1) as product_id  from tbl_hoaca";
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		if( $row = $db->sql_fetchrow($result) )		
			$id = $row['product_id'];
		$code = "BCD".substr("0000".$id, -5);
		return $code;
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------		
function mosExport()
{	
	global $template,$db, $root_path;
	$product_type_id	= mosGetParam( $_REQUEST, 'product_type_id', 0);
	if ( $product_type_id != 0 )
	{
	//doc ghi file
	$sql = "select * from tbl_product_types where product_type_id = $product_type_id";
	if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
	if ( $row = $db->sql_fetchrow($result))
	{
		
	}
	
	$CountFile = "../temp/".$product_type_id."_type.txt";
	$CF = fopen ($CountFile, "w");
	
	
	
	
	$a = mosGetCat($row['product_type_id']);
	$a = substr($a, 1);
	
	$sql = "select * from (tbl_hoaca inner join tbl_color on tbl_hoaca.color_id = tbl_color.color_id) inner join tbl_skin on tbl_hoaca.skin_id = tbl_skin.skin_id where tbl_hoaca.soluong > 0 and tbl_hoaca.product_type_id in ($a) order by tbl_hoaca.product_id DESC";
	$order = 1;
	if ( !($result = $db->sql_query($sql)) ) mosInvalidUrl();
	while ( $row = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('product_list', array(
			'product_id'		=>	$row['product_id'],
			'product_code'		=>	$row['product_code'],
			'product_name'		=>	$row['product_name'],
			'strip_product_name'=>	mosStrip($row['product_name']),
			'image1'			=>	$row['image0'],
			'price'				=>	number_format($row['price'], 0, ',', '.'),
			'skin_id'			=>	$row['skin_id'],
			'skin_name'			=>	$row['skin_name'],
			'strip_skin_name'	=>	mosStrip($row['skin_name']),
			'color_id'			=>	$row['color_id'],
			'color_name'		=>	$row['color_name'],
			'strip_color_name'	=>	mosStrip($row['color_name']),
			'tr'				=>	($order % 3 == 0)?'</tr><tr>':'',
		));
		$order ++;
	}
	
	$template->pparse('type');
	
	
	
	
	
$text = '';
if ( $order == 2 ) $order_by = "tbl_pages.type, ";
if ( $order == 1 )
{
	$sql = "select *, SUBSTR(tbl_pages.grade,3,2)*1 as tam from (tbl_pages inner join tbl_page_content on tbl_pages.page_id = tbl_page_content.page_id) left join tbl_sort on tbl_pages.type = tbl_sort.sort_name where tbl_pages.cat_id = $cat_id and tbl_sort.cat_id = $cat_id and homeshow = 1 order by tbl_sort.priority DESC, tbl_pages.ngay, tam";
}elseif( $order == 3 )
{
	$sql = "select *, SUBSTR(tbl_pages.ngay,3,4)*1 as tam, SUBSTR(tbl_pages.grade,3,2)*1 as tam1 from tbl_pages inner join tbl_page_content on tbl_pages.page_id = tbl_page_content.page_id where tbl_pages.cat_id = $cat_id and homeshow = 1 order by tam, tam1, tbl_pages.ngay, tbl_pages.priority DESC";
}
else{
	$sql = "select tbl_pages.code, tbl_pages.type, tbl_pages.grade, tbl_pages.ngay, tbl_pages.svc, tbl_pages.issoc, tbl_pages.cat_id, tbl_pages.page_id, tbl_pages.url, tbl_pages.isvip, tbl_pages.isgood, tbl_pages.price, tbl_page_content.intro_text, SUBSTR(tbl_pages.grade,3,2)*1 as tam from tbl_pages inner join tbl_page_content on tbl_pages.page_id = tbl_page_content.page_id where tbl_pages.cat_id = $cat_id and homeshow = 1 order by $order_by tbl_pages.ngay, tam, tbl_pages.priority DESC";
}
$dem = 0;
if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
while ( $row1 = $db->sql_fetchrow($result))
{
	if ( $row1['isgood'] == 1)
	{
		$price = '<b>SOLD</b>';
	}else if ( $row1['price'] == 'P.O.R' or $row1['price'] == 'HOLD' )
	{
		$price = $row1['price'];
	}else
	{
		$price = '$'.$row1['price'];
	}	
	$type = $row1['type'];
	$code = $row1['code'];
	$url  = $row1['url'];
	$page_id = $row1['page_id'];
	$ngay	= $row1['ngay'];
	$grade	= $row1['grade'];
	$svc	= $row1['svc'];
	$intro_text = $row1['intro_text'];
	$allow_vip = ($row1['isvip'] == 1)?'':'none';
	$no_allow_soc = ($row1['issoc'] == 1)?'none':'';
	$allow_soc	= ($row1['issoc'] == 1)?'':'none';
	
		$text.="<tr class=xl40 height=18 style='mso-height-source:userset;height:13.5pt' bordercolor='#CCCCCC'><td height=70 class=xl45 style='height:13.5pt'><div align='center'><font size='2'>".$code."</font></div></td><td class=xl51 height='70'> <div align='center'><font size='2'>".$type."</font></div></td><td class=xl51 style='border-left:none' height='70'> <div align='center'><font size='2'><img src='http://www.usrarecoininvestments.com/images/coins_for_sale/new.gif' width='28' height='11' style='display:".$allow_vip."'><a href".$allow_soc."='".$url."_".$page_id."_d.htm'>".$ngay."</a></font></div></td><td class=xl51 style='border-left:none' height='70'> <div align='center'><font size='2'>".$svc."</font></div></td><td class=xl51 style='border-left:none' height='70'> <div align='center'><font size='2'>".$grade."</font></div></td><td class=xl49 style='border-left:none' x:num='20750' height='70'><div align='center'><font size='2'>".$price."</font></div></td><td class=xl40 height='70'> <div align='center' style='display:".$allow_soc.";'><font size='1'><a href='".$url."_".$page_id."_d.htm'><img src='../images/offerings/view_images.jpg' width='19' height='15' border='0'></a></font></div><div align='center' style='display:".$no_allow_soc.";'><font size='1'><a href='http://www.usrarecoininvestments.com/imagerequest.php' target='_blank'>Request Image</a></font></div></td><td class=xl50 width=299 style='width:224pt' height='70'><div align='left'><font size='2'>".$intro_text."<A HREF='".$url."_".$page_id."_d.htm' style='display:".$allow_soc.";' onMouseOver='javascript:stdRollover(this,\"#CC0000\")' onMouseOut='javascript:stdRollout(this,\"#666699\")'>More &gt;&gt;&gt;</A></font></div></td></tr>";
	$dem ++;
}
		fwrite ($CF, $text);
		$template->assign_vars(array('MESSAGE' => 'EXPORT_SUC_'.$dem.'_record'));
	}else
	{
		echo 'khong co id';
	}
	mosList($cat_id);
	}
function mosGetCat($product_type_id)
	{	global $db, $languageid, $template, $str_product_type_id;
		$str_product_type_id .= ','.$product_type_id;
		$sql = "select product_type_id from tbl_product_types where parent_id = $product_type_id and active = 1" ;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{	$tam = $row['product_type_id'];
			mosGetCat($tam);
		}
		return $str_product_type_id;
	}

?>
