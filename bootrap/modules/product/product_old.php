<?php	
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();		
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'product/product',
		'LANGUAGEID'=> $languageid,
	));		
	switch( $action )
	{	case 'list'		:	mosList(); break;
		case 'info'		 :	mosInfo(); break;
		case 'up'		   :  	mosMove('up'); break;
		case 'down' 		 :  	mosMove('down'); break;
		case 'save'		 :	mosSave(); break;
		case 'savecopy'	 :	mosSaveCopy(); break;
		case 'delete'  	   :	mosDelete(); break;  
		case 'reshow'  	   :	mosReshow(); break; 
		case 'slug'		 :	mosSlug(); break;
		case 'comment'	  :    mosComment(); break;
		case 'export'	   :	mosExport(); break;

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
		'product' => 'product/product_list.tpl')
	);
	$product_type_id	 = mosGetParam( $_REQUEST, 'product_type_id1', '29' );
	$isvip			   = mosGetParam( $_REQUEST, 'isvip', '0' );
	$ismain			  = mosGetParam( $_REQUEST, 'ismain', '0' );
	$issl				= mosGetParam( $_REQUEST, 'issl', '1' );
	$hoaca_code		  = mosGetParam( $_REQUEST, 'hoaca_code1','');
	$chietkhau		   = mosGetParam( $_REQUEST, 'chietkhau1','');
	$cond = '';
	$a = mosGetCat($product_type_id);
	$a = substr($a, 1);
	$cond .= ( $product_type_id == 0 )?'':' and product_type_id in ('.$a.')';
	$cond .= ( $isvip == 0 )?'':'  and isvip = '.$isvip;
	$cond .= ( $ismain == 0 )?'':'  and ismain = '.$ismain;
	$cond .= ( $issl == 0 )?' and soluong > 0':'';
	$cond .= ( $hoaca_code == '' )?'':' and hoaca_code like "%'.$hoaca_code.'%"';
	$sql = "select * from tbl_products where 1 $cond order by priority DESC, hoaca_code, product_id DESC";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order = 1;
	$von = 0;
	$loi = 0;
	while( $row = $db->sql_fetchrow($result) )
	{					
		$template->assign_block_vars('list', array(
			'className'		  =>  ($order % 2 == 1) ? 'alt' : 'inv',
			'order'			  =>  $order,
			'product_id'		 =>	$row['product_id'],
			'product_code'	   =>	$row['product_code'],
			'hoaca_code'		 =>	$row['hoaca_code'],
			'chietkhau'		  =>	$row['chietkhau'],
			'product_name'	   =>	$row['product_name'],
			'old_price'		  =>	(strtolower($_SESSION['membername'])=="administrator" || $_SESSION['loginname']=="kieu")?number_format($row['old_price'], 0, ',', '.'):'',
			'price'			  =>	number_format($row['price'], 0, ',', '.'),
			'sale_price'		 =>	number_format($row['sale_price'], 0, ',', '.'),
			'view'			   =>	$row['view'],
			'num_comment'		=>	$row['num_comment'],
			'soluong'			=>	$row['soluong'],
			'priority'		   =>	$row['priority'],
			'bgcolor'			=>	($row['soluong'] > 0)?'1':'',
			'website'			=>	$row['website'],
			'description'		=>	$row['description'],
			'active' 			 =>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
			'isadv' 			  =>	($row['isadv'] == 1) ? '' : 'none',
			'isvip' 			  =>	($row['isvip'] == 1) ? '' : 'none',
			'ismain' 			 =>	($row['ismain'] == 1) ? '' : 'none',
			'up'				 =>	($order == 1) ? ' display: none;' : '',
			'down'			   =>	($order == $num_row) ? ' display: none;' : '',	
			'created_by'		 =>	$row['created_by'],	
			'slug'			   =>	(stripos($row['slug'], "https") === false)?"https://casauhoaca.com/".$row['slug'].".htm":$row['slug'],
		));	
		$von += $row['old_price']*$row['soluong'];
		$loi += $row['price']*$row['soluong'];
		$order ++;
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
			'parent_id'			=>	($row['parent_id'] == 0)?'':'&nbsp;&nbsp;&nbsp;&nbsp;',
		));
	}
	$template->assign_vars(array(
		'product_type_id'		=>	$product_type_id,
		'von'					=>	(strtolower($_SESSION['membername'])=="administrator")?number_format($von/1000000, 0, ',', '.'):'',
		'loi'					=>	(strtolower($_SESSION['membername'])=="administrator")?number_format($loi/1000000, 0, ',', '.'):'',
		'isvip'				  =>	($isvip == 1) ? 'checked' : '',
		'ismain'				 =>	($ismain == 1) ? 'checked' : '',
		'issl'				   =>	($issl == 1) ? 'checked' : '',
		'hoaca_code'		  	 =>	$hoaca_code,
		'chietkhau'		  	  =>	$chietkhau,
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
	{	$sql = "select * from tbl_products where product_id = $product_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{	
			$template->assign_vars(array(
				'product_id'		=>	($copy == 0)?$product_id:'',
				'product_code'	  =>	($copy == 0)?$row['product_code']:mosCode(),
				'hoaca_code'		=>	$row['hoaca_code'],
				'chietkhau'		 =>	$row['chietkhau'],
				'product_name'	  =>	$row['product_name'],
				'product_type_id'   =>	$row['product_type_id'],
				'product_kind_id'   =>	$row['product_kind_id'],
				'skin_id'		   =>	$row['skin_id'],
				'color_id'		  =>	$row['color_id'],
				'gift_id'		   =>	$row['gift_id'],
				'source'			=>	($row['source'])?$row['source']:0,
				'size'			  =>	$row['size'],
				'view'			  =>	$row['view'],
				'num_comment'	   =>	$row['num_comment'],
				'soluong'		   =>	$row['soluong'],
				'priority'		  =>	$row['priority'],
				'old_price'		 =>	$row['old_price'],
				'price'			 =>	$row['price'],
				'sale_price'		=>	$row['sale_price'],
				'website'		   =>	$row['website'],
				'description'	   =>	$row['description'],
				'active'			=>	($row['active'] == 1) ? 'checked' : '',
				'isadv'			 =>	($row['isadv'] == 1) ? 'checked' : '',
				'isvip'			 =>	($row['isvip'] == 1) ? 'checked' : '',
				'ismain'			=>	($row['ismain'] == 1) ? 'checked' : '',
				'image0'			=>	$row['image0'],
				'image1'			=>	$row['image1'],
				'image2'			=>	$row['image2'],
				'image3'			=>	$row['image3'],
				'image4'			=>	$row['image4'],
				'image5'			=>	$row['image5'],
				'image6'			=>	$row['image6'],
				'image7'			=>	$row['image7'],
				'image8'			=>	$row['image8'],
				'image9'			=>	$row['image9'],
				'image10'		   =>	$row['image10'],
				'allow_image0'	  =>	($row['image0'])?"":"none",
				'allow_image1'	  =>	($row['image1'])?"":"none",
				'allow_image2'	  =>	($row['image2'])?"":"none",
				'allow_image3'	  =>	($row['image3'])?"":"none",
				'allow_image4'	  =>	($row['image4'])?"":"none",
				'allow_image5'	  =>	($row['image5'])?"":"none",
				'allow_image6'	  =>	($row['image6'])?"":"none",
				'allow_image7'	  =>	($row['image7'])?"":"none",
				'allow_image8'	  =>	($row['image8'])?"":"none",
				'allow_image9'	  =>	($row['image9'])?"":"none",
				'allow_image10'	 =>	($row['image10'])?"":"none",
				'readonly'		  =>	'readonly',		
				'isadmin'		   =>	($_SESSION['membername']=="administrator" || $_SESSION['loginname']=="kieu")?'':'none',
				'readonly'		  =>	($_SESSION['membername']=="administrator" || $_SESSION['loginname']=="kieu")?'':'readonly',
				'created_by'		=>	$row['created_by'],
				'modified_by'	   =>	$row['modified_by'],
				'created_date'	  =>	$row['created_date'],
				'last_modified'	 =>	$row['last_modified'],
				'slug'			  =>	$row['slug'],
				'meta_key'		  =>	$row['meta_key'],
				'meta_des'		  =>	$row['meta_des'],
				'meta_schema'	   =>	$row['meta_schema'],
				'title_seo'		 =>	$row['title_seo'],
				'alt_img1'		  =>	$row['alt_img1'],
				'alt_img2'		  =>	$row['alt_img2'],
				'alt_img3'		  =>	$row['alt_img3'],
				'alt_img4'		  =>	$row['alt_img4'],
			));
		} else
			message_die( ID_NOTFOUND );		
	} else
	{			
		$template->assign_vars(array(
			'product_code'		=>	mosCode(),
			'hoaca_code'		  =>	'',
			'chietkhau'		   =>	'',
			'product_kind_id'	 => '0',
			'product_type_id'	 => '0',
			'skin_id'			 => '0',	
			'color_id'			=> '0',	
			'gift_id'			 => '0',
			'source'			  => '0',
			'soluong'			 =>	'1',		
			'active'			  =>	'checked' ,
			'allow_image0'		=>	"none",
			'allow_image1'		=>	"none",
			'allow_image2'		=>	"none",
			'allow_image3'		=>	"none",
			'allow_image4'		=>	"none",
			'allow_image5'		=>	"none",
			'allow_image6'		=>	"none",
			'allow_image7'		=>	"none",
			'allow_image8'		=>	"none",
			'allow_image9'		=>	"none",
			'allow_image10'	   =>	"none",
			'num_comment'		 =>	'2',
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
			'parent_id'			=>	($row['parent_id'] == 0)?'':'&nbsp;&nbsp;&nbsp;&nbsp;',
		));
	}
	catList(0);
	$template->set_filenames_new(array(
		'product' => 'product/product_info.tpl')
	);
	$template->pparse('product');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$product_id  	  = mosGetParam( $_REQUEST, 'id', '0');
		$product_code  	= mosGetParam( $_REQUEST, 'product_code', '');
		$hoaca_code	  = mosGetParam( $_REQUEST, 'hoaca_code', '');
		$chietkhau	   = mosGetParam( $_REQUEST, 'chietkhau', '');
		$product_name  	= mosGetParam( $_REQUEST, 'product_name', '');
		$product_type_id = mosGetParam( $_REQUEST, 'product_type_id', 0);
		$product_kind_id = mosGetParam( $_REQUEST, 'product_kind_id', 0);
		$skin_id		 = mosGetParam( $_REQUEST, 'skin_id', 0);
		$color_id		= mosGetParam( $_REQUEST, 'color_id', 0);
		$gift_id		 = mosGetParam( $_REQUEST, 'gift_id', 0);
		$source		  = mosGetParam( $_REQUEST, 'source', 0);
		$size			= mosGetParam( $_REQUEST, 'size', '');
		$view			= mosGetParam( $_REQUEST, 'view', '');
		$num_comment	 = mosGetParam( $_REQUEST, 'num_comment', '2');
		$soluong		 = mosGetParam( $_REQUEST, 'soluong', '');
		$priority		= mosGetParam( $_REQUEST, 'priority', '');
		$old_price	   = mosGetParam( $_REQUEST, 'old_price', '');
		$price		   = mosGetParam( $_REQUEST, 'price', '');
		$sale_price	  = mosGetParam( $_REQUEST, 'sale_price', '');
		$website		 = mosGetParam( $_REQUEST, 'website', '');
		$description	 = mosGetParam( $_REQUEST, 'description', '', 0x0003);
		$active		  = mosGetParam( $_REQUEST, 'active', '0');
		$isadv		   = mosGetParam( $_REQUEST, 'isadv', '0');
		$isvip		   = mosGetParam( $_REQUEST, 'isvip', '0');
		$ismain		  = mosGetParam( $_REQUEST, 'ismain', '0');
		$image0		  = mosGetParam( $_REQUEST, 'image0', '');
		$old_image0	  = mosGetParam( $_REQUEST, 'old_image0', '');
		$remove_image0   = mosGetParam( $_REQUEST, 'remove_image0', '');
		$image1		  = mosGetParam( $_REQUEST, 'image1', '');
		$old_image1	  = mosGetParam( $_REQUEST, 'old_image1', '');
		$remove_image1   = mosGetParam( $_REQUEST, 'remove_image1', '');
		$image2		  = mosGetParam( $_REQUEST, 'image2', '');
		$old_image2	  = mosGetParam( $_REQUEST, 'old_image2', '');
		$remove_image2   = mosGetParam( $_REQUEST, 'remove_image2', '');
		$image3		  = mosGetParam( $_REQUEST, 'image3', '');
		$old_image3	  = mosGetParam( $_REQUEST, 'old_image3', '');
		$remove_image3   = mosGetParam( $_REQUEST, 'remove_image3', '');
		$image4		  = mosGetParam( $_REQUEST, 'image4', '');
		$old_image4	  = mosGetParam( $_REQUEST, 'old_image4', '');
		$remove_image4   = mosGetParam( $_REQUEST, 'remove_image4', '');
		$image5		  = mosGetParam( $_REQUEST, 'image5', '');
		$old_image5	  = mosGetParam( $_REQUEST, 'old_image5', '');
		$remove_image5   = mosGetParam( $_REQUEST, 'remove_image5', '');
		$image6		  = mosGetParam( $_REQUEST, 'image6', '');
		$old_image6	  = mosGetParam( $_REQUEST, 'old_image6', '');
		$remove_image6   = mosGetParam( $_REQUEST, 'remove_image6', '');
		$image7		  = mosGetParam( $_REQUEST, 'image7', '');
		$old_image7	  = mosGetParam( $_REQUEST, 'old_image7', '');
		$remove_image7   = mosGetParam( $_REQUEST, 'remove_image7', '');
		$image8		  = mosGetParam( $_REQUEST, 'image8', '');
		$old_image8	  = mosGetParam( $_REQUEST, 'old_image8', '');
		$remove_image8   = mosGetParam( $_REQUEST, 'remove_image8', '');
		$image9		  = mosGetParam( $_REQUEST, 'image9', '');
		$old_image9	  = mosGetParam( $_REQUEST, 'old_image9', '');
		$remove_image9   = mosGetParam( $_REQUEST, 'remove_image9', '');
		$image10		 = mosGetParam( $_REQUEST, 'image10', '');
		$old_image10	 = mosGetParam( $_REQUEST, 'old_image10', '');
		$remove_image10  = mosGetParam( $_REQUEST, 'remove_image10', '');
		$slug			= mosGetParam( $_REQUEST, 'slug', '');
		$meta_key		= mosGetParam( $_REQUEST, 'meta_key', '');
		$meta_des		= mosGetParam( $_REQUEST, 'meta_des', '');
		$meta_schema	 = mosGetParam( $_REQUEST, 'meta_schema', '');
		$title_seo	   = mosGetParam( $_REQUEST, 'title_seo', '');
		$alt_img1		= mosGetParam( $_REQUEST, 'alt_img1', '');
		$alt_img2		= mosGetParam( $_REQUEST, 'alt_img2', '');
		$alt_img3		= mosGetParam( $_REQUEST, 'alt_img3', '');
		$alt_img4		= mosGetParam( $_REQUEST, 'alt_img4', '');
		
		$num_comment	 = ($num_comment < 2)?2:$num_comment;
		
		if ($product_code == '')
		{	
			mosInvalidURL();
			exit;
		}
		
		$imgDir = $root_path . "images/product/";		
		if (! is_dir($imgDir))
			mkdir($imgDir, 0666);				
		
		if (checkDuplicate("tbl_products", array('product_code' => $product_code), "product_id", $product_id, false, ""))
		{	
			$template->assign_vars(array('MESSAGE'	=>	'Trùng mã sản phẩm'));
			mosList();		
			exit;
		}
		if (checkDuplicate("tbl_products", array('slug' => $slug), "product_id", $product_id, false, ""))
		{	
			$template->assign_vars(array('MESSAGE'	=>	"Trùng slug"));
			mosList();		
			exit;
		}	
		if (checkDuplicate("tbl_products", array('meta_des' => $meta_des), "product_id", $product_id, false, ""))
		{	
			$template->assign_vars(array('MESSAGE'	=>	'Trung Meta des'));
			mosList();		
			exit;
		}
		if (checkDuplicate("tbl_products", array('title_seo' => $title_seo), "product_id", $product_id, false, ""))
		{	
			$template->assign_vars(array('MESSAGE'	=>	'Trung Title Seo'));
			mosList();		
			exit;
		}
		$num_comment = round($view/1000);
		$num_comment = ($num_comment>1)?$num_comment:1;

		if ($product_id == '0')
		{	
			$image0 = mosUploadImage($imgDir, "image0");
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
				$image4 = $old_image4;
			$image5 = mosUploadImage($imgDir, "image5");
			if (($remove_image5 == 0) && ($image5 == ''))
				$image5 = $old_image5;
			$image6 = mosUploadImage($imgDir, "image6");
			if (($remove_image6 == 0) && ($image6 == ''))
				$image6 = $old_image6;
			$image7 = mosUploadImage($imgDir, "image7");
			if (($remove_image7 == 0) && ($image7 == ''))
				$image7 = $old_image7;
			$image8 = mosUploadImage($imgDir, "image8");
			if (($remove_image8 == 0) && ($image8 == ''))
				$image8 = $old_image8;
			$image9 = mosUploadImage($imgDir, "image9");
			if (($remove_image9 == 0) && ($image9 == ''))
				$image9 = $old_image9;
			$image10 = mosUploadImage($imgDir, "image10");
			if (($remove_image10 == 0) && ($image10 == ''))
				$image10 = $old_image10;
			
			$meta_schema = creMetaSchemaProduct($meta_schema, $title_seo, $image1, $meta_des, $slug, $price, $num_comment, $soluong, $product_code, $product_id);
			
			
			$sql = "select if(max(product_id) is null, 1, max(product_id) + 1) as product_id  from tbl_products";
			if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
			if( $row = $db->sql_fetchrow($result) )
			{		
				$code	= substr($product_code,0, -strlen($row['product_id'])).$row['product_id'];
			}
			else
			{	message_die(SERVER_BUSY);
				exit;
			}
			$priority = mosGetPriority("tbl_products", "priority", "");
			$sql = "insert into tbl_products (product_code, hoaca_code, chietkhau, product_name, product_type_id, product_kind_id, skin_id, color_id, gift_id, source, size, view, num_comment, soluong, old_price, price, sale_price, website, description, active, isadv, isvip, ismain, created_date, created_by, member_id, priority, image0, image1, image2, image3, image4, image5, image6, image7, image8, image9, image10, language_id, slug, meta_key, meta_des, meta_schema, title_seo, alt_img1, alt_img2, alt_img3, alt_img4) values ('$code', '$hoaca_code', '$chietkhau', '$product_name', '$product_type_id', '$product_kind_id', '$skin_id', '$color_id', '$gift_id', '$source', '$size', '$view', '$num_comment', '$soluong', '$old_price', '$price', '$sale_price', '$website', '$description', $active, $isadv, $isvip, '$ismain', now(), '".$_SESSION['membername']."', '".$_SESSION["login_id"]."', $priority, '$image0', '$image1', '$image2', '$image3', '$image4', '$image5', '$image6', '$image7', '$image8', '$image9', '$image10', '$languageid', '$slug', '$meta_key', '$meta_des', '$meta_schema', '$title_seo', '$alt_img1', '$alt_img2', '$alt_img3', '$alt_img4')";	
		} else
		{ 
			$image0 = mosUploadImage($imgDir, "image0", $old_image0);
			if (($remove_image0 == 0) && ($image0 == ''))
				$image0 = $old_image0;	
			$image1 = mosUploadImage($imgDir, "image1", $old_image1);
			if (($remove_image1 == 0) && ($image1 == ''))
				$image1 = $old_image1;
			$image2 = mosUploadImage($imgDir, "image2", $old_image2);
			if (($remove_image2 == 0) && ($image2 == ''))
				$image2 = $old_image2;
			$image3 = mosUploadImage($imgDir, "image3", $old_image3);
			if (($remove_image3 == 0) && ($image3 == ''))
				$image3 = $old_image3;
			$image4 = mosUploadImage($imgDir, "image4", $old_image4);
			if (($remove_image4 == 0) && ($image4 == ''))
				$image4 = $old_image4;
			$image5 = mosUploadImage($imgDir, "image5", $old_image5);
			if (($remove_image5 == 0) && ($image5 == ''))
				$image5 = $old_image5;
			$image6 = mosUploadImage($imgDir, "image6", $old_image6);
			if (($remove_image6 == 0) && ($image6 == ''))
				$image6 = $old_image6;
			$image7 = mosUploadImage($imgDir, "image7", $old_image7);
			if (($remove_image7 == 0) && ($image7 == ''))
				$image7 = $old_image7;
			$image8 = mosUploadImage($imgDir, "image8", $old_image8);
			if (($remove_image8 == 0) && ($image8 == ''))
				$image8 = $old_image8;
			$image9 = mosUploadImage($imgDir, "image9", $old_image9);
			if (($remove_image9 == 0) && ($image9 == ''))
				$image9 = $old_image9;
			$image10 = mosUploadImage($imgDir, "image10", $old_image10);
			if (($remove_image10 == 0) && ($image10 == ''))
				$image10 = $old_image10;
				
			$meta_schema = creMetaSchemaProduct($meta_schema, $title_seo, $image1, $meta_des, $slug, $price, $num_comment, $soluong, $product_code, $product_id);
				
			$sql = "update tbl_products set product_code='$product_code', hoaca_code = '$hoaca_code', chietkhau = '$chietkhau', product_name = '$product_name', product_type_id = '$product_type_id', product_kind_id = '$product_kind_id', skin_id = '$skin_id', color_id = '$color_id', gift_id = '$gift_id', source = '$source', size = '$size', view = '$view', num_comment = '$num_comment', soluong = '$soluong', priority = '$priority', old_price = '$old_price', price = '$price', sale_price = '$sale_price', website = '$website', description = '$description' , active = $active, isadv = $isadv, isvip = $isvip, ismain = '$ismain', image0 = '$image0', image1 = '$image1', image2 = '$image2', image3 = '$image3', image4 = '$image4', image5 = '$image5', image6 = '$image6', image7 = '$image7', image8 = '$image8', image9 = '$image9', image10 = '$image10', language_id='$languageid', modified_by = '".$_SESSION['membername']."', last_modified = now(), slug = '$slug', meta_key = '$meta_key', meta_des = '$meta_des', meta_schema = '$meta_schema', title_seo = '$title_seo', alt_img1 = '$alt_img1', alt_img2 = '$alt_img2', alt_img3 = '$alt_img3', alt_img4 = '$alt_img4' where product_id = $product_id";
		}
		
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		
		$arrField = array("image0","image1","image2","image3","image4","image5","image6","image7","image8","image9","image10");
		
		checkDeleteOldFile($image0, $old_image0, $remove_image0, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image1, $old_image1, $remove_image1, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image2, $old_image2, $remove_image2, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image3, $old_image3, $remove_image3, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image4, $old_image4, $remove_image4, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image5, $old_image5, $remove_image5, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image6, $old_image6, $remove_image6, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image7, $old_image7, $remove_image7, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image8, $old_image8, $remove_image8, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image9, $old_image9, $remove_image9, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image10, $old_image10, $remove_image10, $imgDir , "tbl_products", $arrField, "product_id", $product_id);

		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosSaveCopy()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$product_id  		= mosGetParam( $_REQUEST, 'id', '0');
		$product_code  		= mosGetParam( $_REQUEST, 'product_code', '');
		$hoaca_code			= mosGetParam( $_REQUEST, 'hoaca_code', '');
		$chietkhau			= mosGetParam( $_REQUEST, 'chietkhau', '');
		$product_name  		= mosGetParam( $_REQUEST, 'product_name', '');
		$product_type_id	= mosGetParam( $_REQUEST, 'product_type_id', 0);
		$product_kind_id	= mosGetParam( $_REQUEST, 'product_kind_id', 0);
		$skin_id			= mosGetParam( $_REQUEST, 'skin_id', 0);
		$color_id			= mosGetParam( $_REQUEST, 'color_id', 0);
		$gift_id			= mosGetParam( $_REQUEST, 'gift_id', 0);
		$source				= mosGetParam( $_REQUEST, 'source', 0);
		$size				= mosGetParam( $_REQUEST, 'size', '');
		$view				= mosGetParam( $_REQUEST, 'view', '');
		$num_comment		= mosGetParam( $_REQUEST, 'num_comment', '');
		$soluong			= mosGetParam( $_REQUEST, 'soluong', '');
		$priority			= mosGetParam( $_REQUEST, 'priority', '');
		$old_price			= mosGetParam( $_REQUEST, 'old_price', '');
		$price				= mosGetParam( $_REQUEST, 'price', '');
		$sale_price			= mosGetParam( $_REQUEST, 'sale_price', '');
		$website			= mosGetParam( $_REQUEST, 'website', '');
		$description		= mosGetParam( $_REQUEST, 'description', '', 0x0003);
		$active				= mosGetParam( $_REQUEST, 'active', '0');
		$isadv				= mosGetParam( $_REQUEST, 'isadv', '0');
		$isvip				= mosGetParam( $_REQUEST, 'isvip', '0');
		$ismain				= mosGetParam( $_REQUEST, 'ismain', '0');
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
		$image5			= mosGetParam( $_REQUEST, 'image5', '');
		$old_image5		= mosGetParam( $_REQUEST, 'old_image5', '');
		$remove_image5	= mosGetParam( $_REQUEST, 'remove_image5', '');
		$image6			= mosGetParam( $_REQUEST, 'image6', '');
		$old_image6		= mosGetParam( $_REQUEST, 'old_image6', '');
		$remove_image6	= mosGetParam( $_REQUEST, 'remove_image6', '');
		$image7			= mosGetParam( $_REQUEST, 'image7', '');
		$old_image7		= mosGetParam( $_REQUEST, 'old_image7', '');
		$remove_image7	= mosGetParam( $_REQUEST, 'remove_image7', '');
		$image8			= mosGetParam( $_REQUEST, 'image8', '');
		$old_image8		= mosGetParam( $_REQUEST, 'old_image8', '');
		$remove_image8	= mosGetParam( $_REQUEST, 'remove_image8', '');
		$image9			= mosGetParam( $_REQUEST, 'image9', '');
		$old_image9		= mosGetParam( $_REQUEST, 'old_image9', '');
		$remove_image9	= mosGetParam( $_REQUEST, 'remove_image9', '');
		$image10			= mosGetParam( $_REQUEST, 'image10', '');
		$old_image10		= mosGetParam( $_REQUEST, 'old_image10', '');
		$remove_image10	= mosGetParam( $_REQUEST, 'remove_image10', '');
		
		if ($product_code == '')
		{	
			mosInvalidURL();
			exit;
		}
		
		$imgDir = $root_path . "images/product/";		
		if (! is_dir($imgDir))
			mkdir($imgDir, 0666);				
			
		$image0 = mosUploadImage($imgDir, "image0");
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
			$image4 = $old_image4;
		$image5 = mosUploadImage($imgDir, "image5");
		if (($remove_image5 == 0) && ($image5 == ''))
			$image5 = $old_image5;
		$image6 = mosUploadImage($imgDir, "image6");
		if (($remove_image6 == 0) && ($image6 == ''))
			$image6 = $old_image6;
		$image7 = mosUploadImage($imgDir, "image7");
		if (($remove_image7 == 0) && ($image7 == ''))
			$image7 = $old_image7;
		$image8 = mosUploadImage($imgDir, "image8");
		if (($remove_image8 == 0) && ($image8 == ''))
			$image8 = $old_image8;
		$image9 = mosUploadImage($imgDir, "image9");
		if (($remove_image9 == 0) && ($image9 == ''))
			$image9 = $old_image9;
		$image10 = mosUploadImage($imgDir, "image10");
		if (($remove_image10 == 0) && ($image10 == ''))
			$image10 = $old_image10;
		
		if (checkDuplicate("tbl_products", array('product_code' => $product_code), "product_id", $product_id, false, ""))
		{	
			$template->assign_vars(array('MESSAGE'	=>	DUPLICATE_ENTRY));
			mosList();		
			exit;
		}		

		if ($product_id == '0')
		{	
			$sql = "select if(max(product_id) is null, 1, max(product_id) + 1) as product_id  from tbl_products";
			if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
			if( $row = $db->sql_fetchrow($result) )	
			{	
				$product_id = $row['product_id'];
			}
			else
			{	message_die(SERVER_BUSY);
				exit;
			}
			$priority = mosGetPriority("tbl_products", "priority", "");
			$sql = "insert into tbl_products (product_id, product_code, hoaca_code, chietkhau, product_name, product_type_id, product_kind_id, skin_id, color_id, gift_id, source, size, view, num_comment, soluong, old_price, price, sale_price, website, description, active, isadv, isvip, ismain, created_date, created_by, priority, image0, image1, image2, image3, image4, image5, image6, image7, image8, image9, image10, language_id) values ('$product_id', '$product_code', '$hoaca_code', '$chietkhau', '$product_name', '$product_type_id', '$product_kind_id', '$skin_id', '$color_id', '$gift_id', '$source', '$size', '$view', '$num_comment', '$soluong', '$old_price', '$price', '$sale_price', '$website', '$description', $active, $isadv, $isvip, '$ismain', now(), '".$_SESSION['membername']."', $priority, '$image0', '$image1', '$image2', '$image3', '$image4', '$image5', '$image6', '$image7', '$image8', '$image9', '$image10', '$languageid')";	
		} else
		{ 
			$sql = "update tbl_products set product_code='$product_code', hoaca_code = '$hoaca_code', chietkhau = '$chietkhau', product_name = '$product_name', product_type_id = '$product_type_id', product_kind_id = '$product_kind_id', skin_id = '$skin_id', color_id = '$color_id', gift_id = '$gift_id', source = '$source', size = '$size', view = '$view', num_comment = '$num_comment', soluong = '$soluong', old_price = '$old_price', price = '$price', sale_price = '$sale_price', website = '$website', description = '$description' , active = $active, isadv = $isadv, isvip = $isvip, ismain = '$ismain', image0 = '$image0', image1 = '$image1', image2 = '$image2', image3 = '$image3', image4 = '$image4', image5 = '$image5', image6 = '$image6', image7 = '$image7', image8 = '$image8', image9 = '$image9', image10 = '$image10', language_id='$languageid' where product_id = $product_id";
		}
		
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		
		$arrField = array("image0","image1","image2","image3","image4","image5","image6","image7","image8","image9","image10");
		
		checkDeleteOldFile($image0, $old_image0, $remove_image0, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image1, $old_image1, $remove_image1, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image2, $old_image2, $remove_image2, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image3, $old_image3, $remove_image3, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image4, $old_image4, $remove_image4, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image5, $old_image5, $remove_image5, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image6, $old_image6, $remove_image6, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image7, $old_image7, $remove_image7, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image8, $old_image8, $remove_image8, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image9, $old_image9, $remove_image9, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		checkDeleteOldFile($image10, $old_image10, $remove_image10, $imgDir , "tbl_products", $arrField, "product_id", $product_id);

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
		
		$sql = "select image0, image1, image2, image3, image4, image5, image6, image7, image8, image9, image10, ismain from tbl_products where product_id = $product_id";
		if ( !($result = $db->sql_query($sql)) ) mosInvalidUrl();
		if ( $row = $db->sql_fetchrow($result))
		{
			$ismain = $row['ismain'];
			$old_image0 = $row['image0'];
			$old_image1 = $row['image1'];
			$old_image2 = $row['image2'];
			$old_image3 = $row['image3'];
			$old_image4 = $row['image4'];
			$old_image5 = $row['image5'];
			$old_image6 = $row['image6'];
			$old_image7 = $row['image7'];
			$old_image8 = $row['image8'];
			$old_image9 = $row['image9'];
			$old_image10= $row['image10'];
		}
		if(strtolower($_SESSION['membername'])=="administrator" && $ismain != 1) 
		{	
			$sql = "select image0, image1, image2, image3, image4, image5, image6, image7, image8, image9, image10 from tbl_products where product_id = $product_id";
			if ( !($result = $db->sql_query($sql)) ) mosInvalidUrl();
			if ( $row = $db->sql_fetchrow($result))
			{
				$old_image0 = $row['image0'];
				$old_image1 = $row['image1'];
				$old_image2 = $row['image2'];
				$old_image3 = $row['image3'];
				$old_image4 = $row['image4'];
				$old_image5 = $row['image5'];
				$old_image6 = $row['image6'];
				$old_image7 = $row['image7'];
				$old_image8 = $row['image8'];
				$old_image9 = $row['image9'];
				$old_image10= $row['image10'];
			}
			deleteByID("tbl_products", "product_id", $product_id);
			$template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
		
			$imgDir = $root_path . "images/product/";
			$arrField = array("image0","image1","image2","image3","image4","image5","image6","image7","image8","image9","image10");
			checkDeleteOldFile('', $old_image0, 1, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
			checkDeleteOldFile('', $old_image1, 1, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
			checkDeleteOldFile('', $old_image2, 1, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
			checkDeleteOldFile('', $old_image3, 1, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
			checkDeleteOldFile('', $old_image4, 1, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
			checkDeleteOldFile('', $old_image5, 1, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
			checkDeleteOldFile('', $old_image6, 1, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
			checkDeleteOldFile('', $old_image7, 1, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
			checkDeleteOldFile('', $old_image8, 1, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
			checkDeleteOldFile('', $old_image9, 1, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
			checkDeleteOldFile('', $old_image10, 1, $imgDir , "tbl_products", $arrField, "product_id", $product_id);
		}else
		{
			$template->assign_vars(array('MESSAGE'	=>	($ismain)?"Không xóa sp chính":CANT_NOT_DELETE));
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
		mosChangePriority( $product_id, $direction, "tbl_products", "product_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosUpdateService($product_id)
	{	
		global $db, $root_path, $skin, $languageid, $template;
		
		//xoa service_product cu
		$sql = "delete from tbl_product_service where product_id = $product_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		
		$sql = "select * from tbl_service where language_id = $languageid order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result) )
		{	$service_id = $row['service_id'];
			$service	=	mosGetParam( $_REQUEST, $service_id, '0' );
			$sql1 = "insert into tbl_product_service ( `product_id`, `service_id` , `chk`) values ( '$product_id', '$service_id', '$service' )";
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
			$sql = "select * from tbl_products where product_id = $product_id";
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
				$sql1 = "select * from tbl_product_service where service_id = $service_id and product_id = $product_id";
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
		$hoaca_code		= mosGetParam( $_REQUEST, 'hoaca_code', '');
		$chietkhau		= mosGetParam( $_REQUEST, 'chietkhau', '');
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
		$isvip			= mosGetParam( $_REQUEST, 'isvip', '0');
		$ismain			= mosGetParam( $_REQUEST, 'ismain', '0');
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
				'hoaca_code'	=>	$hoaca_code,
				'chietkhau'	=>	$chietkhau,
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
				'isvip'			=>	($isvip == 1) ? 'checked' : '',
				'ismain'			=>	($ismain == 1) ? 'checked' : '',
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
		'product' => 'product/product_info.tpl')
	);
	$template->pparse('product');
		
		
		
			
	}	
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------		
function mosCode()
	{	
		global $db, $languageid, $template;
		$sql = "select if(max(product_id) is null, 1, max(product_id) + 1) as product_id  from tbl_products";
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
	
	$sql = "select * from (tbl_products inner join tbl_color on tbl_products.color_id = tbl_color.color_id) inner join tbl_skin on tbl_products.skin_id = tbl_skin.skin_id where tbl_products.soluong > 0 and tbl_products.product_type_id in ($a) order by tbl_products.product_id DESC";
	$order = 1;
	if ( !($result = $db->sql_query($sql)) ) mosInvalidUrl();
	while ( $row = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('product_list', array(
			'product_id'		=>	$row['product_id'],
			'product_code'		=>	$row['product_code'],
			'hoaca_code'		=>	$row['hoaca_code'],
			'chietkhau'		=>	$row['chietkhau'],
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
	$sql = "select tbl_pages.code, tbl_pages.type, tbl_pages.grade, tbl_pages.ngay, tbl_pages.svc, tbl_pages.issoc, tbl_pages.cat_id, tbl_pages.page_id, tbl_pages.url, tbl_pages.isvip, tbl_pages.ismain, tbl_pages.isgood, tbl_pages.price, tbl_page_content.intro_text, SUBSTR(tbl_pages.grade,3,2)*1 as tam from tbl_pages inner join tbl_page_content on tbl_pages.page_id = tbl_page_content.page_id where tbl_pages.cat_id = $cat_id and homeshow = 1 order by $order_by tbl_pages.ngay, tam, tbl_pages.priority DESC";
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
	$allow_main = ($row1['ismain'] == 1)?'':'none';
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


//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------Slug
function mosSlug()
{	
	echo "tạm ngưng";exit;	
	global $db, $root_path, $skin, $languageid, $template;
	$template->set_filenames_new(array(
		'slug' => 'product/product_list.tpl')
	);
	// tao slug
	$sql  = "select * from tbl_products where slug IS NULL OR slug = '' order by product_id DESC";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while( $row = $db->sql_fetchrow($result) )
	{
		$slug = mosStrip($row['product_name'])."-".$row['product_id'];
		$sql1 = "update tbl_products set slug = '$slug' where product_id = ".$row['product_id'];
		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );			
	}
	
	// Tao Title_seo, meta_key, meta_des
	$sql  = "select * from tbl_products where title_seo IS NULL OR title_seo = '' or meta_key IS NULL OR meta_key = '' or meta_des IS NULL OR meta_des = '' order by product_id";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while( $row = $db->sql_fetchrow($result) )
	{
		$title_seo 	= $row['product_name'];
		$meta_key	= $row['product_name'];
		$meta_des	= getFirstNCharacters(strip_tags($row['description']),140)." - Giá: ".$row['price']." vnđ";
		
		$sql1 = "update tbl_products set title_seo = '$title_seo', meta_key = '$meta_key', meta_des = '$meta_des' where product_id = ".$row['product_id'];
		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );			
	}
	$template->assign_vars(array(
		'product_type_id'	=>	'0',
	));
	$template->pparse('slug');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------Slug
function mosComment()
{	
	echo "tạm ngưng comment/ đến 30/11/2018 cho chạy lại OK";exit;	
	global $db, $root_path, $skin, $languageid, $template;
	$template->set_filenames_new(array(
		'comment' => 'product/product_list.tpl')
	);
	// tao Num_comment, test id = 2723
	$sql  = "select * from tbl_products where active = 1";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while( $row = $db->sql_fetchrow($result) )
	{
		$num_comment = round($row['view']/1000);
		$sql1 = "update tbl_products set num_comment = '$num_comment' where product_id = ".$row['product_id'];
		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );			
	}
	
	// Tạo schema
	$sql  = "select * from tbl_products where product_id = 3652";
	$sql  = "select * from tbl_products where active = 1";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while( $row = $db->sql_fetchrow($result) )
	{
		$meta_schema = creMetaSchemaProduct("", $row['title_seo'], $row['image1'], $row['meta_des'], $row['slug'], $row['price'], $row['num_comment'], $row['soluong'], $row['product_code']);
		$sql1 = "update tbl_products set meta_schema = '$meta_schema' where product_id = ".$row['product_id'];
		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );			
	}
	$template->assign_vars(array(
		'product_type_id'	=>	'0',
	));
	$template->pparse('comment');
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
//--------------------------------------------------------------------------------------------------
	function catList($parent_id, $prefix = "&nbsp;&nbsp;")
	{	global $db, $languageid, $template;
		$sql = "SELECT product_type_id, product_type_name, product_type_code FROM tbl_product_types  WHERE (parent_id = $parent_id) and (language_id = $languageid) and active = 1 ORDER BY priority" ;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{	$template->assign_block_vars('catlist', array(
				'product_type_id'	=>	$row['product_type_id'],
				'product_type_name'	=>	$prefix . $row['product_type_name'],
				'product_type_code'	=>	$row['product_type_code'],
			));
			catList($row['product_type_id'], $prefix. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
		}	
	}
//--------------------------------------------------------------------------------------------------
	function creMetaSchemaProduct($meta_schema, $title_seo, $image1, $meta_des, $slug, $price, $num_comment, $soluong, $product_code, $product_id)
	{	
		if($num_comment == 1)
		{	
			$ratingValue = 5;
			$ratingCount = 1;
		}else
		{
			$ratingValue = number_format((($num_comment * 5)-1)/$num_comment, 1, ',', ' ');
			$ratingValue = ($ratingValue == 5)?"4,9":$ratingValue;
			$ratingCount = $num_comment;
		}
		$stock = ($soluong >0)?"InStock":"OutOfStock";
		//Tạo schema
		if ( 1 )
		{
		$meta_schema = '{
  			"@context" : "http://schema.org",
  			"@type" : "Product",
  			"name" : "'.$title_seo.'",
  			"image" : "https://casauhoaca.com/images/product/'.$image1.'",
  			"description" : "'.$meta_des.'",
  			"url" : "https://casauhoaca.com/'.$slug.'.htm",
  			"brand" : {
    		"@type" : "Brand",
    		"name" : "Cá Sấu Hoa Cà",
    		"logo" : "https://casauhoaca.com/templates/default/images/logo.png"
  			},
  			"offers" : {
    			"@type" : "Offer",
				"availability": "'.$stock.'",
				"priceValidUntil": "2022-12-30",
				"url" : "https://casauhoaca.com/'.$slug.'.htm",
    			"price" : "'.$price.'",
				"priceCurrency" : "VND"
  			},
  			"aggregateRating" : {
    			"@type" : "AggregateRating",
    			"ratingValue" : "'.$ratingValue.'",
    			"ratingCount" : "'.$ratingCount.'"
  			},
			"review": {
  				"@type": "Review",
 				 "author": "Cá Sấu Hoa Cà",
  				"datePublished": "2019-11-01",
  				"description": "Sản phẩm tốt.",
  				"name": "Awesome!",
 				"reviewRating": {
  					"@type": "Rating",
  					"bestRating": "5",
  					"ratingValue": "'.$ratingValue.'",
  					"worstRating": "4"
  				}
			},
			"sku" : "'.$product_code.'",
			"gtin8" : "'.$product_id.'"
			}';	
		}
		return $meta_schema;
	}
?>
