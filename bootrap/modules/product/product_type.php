<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'product/product_type',
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
		
		$sql = "select *, (select sum(soluong) from tbl_products where product_type_id = tbl_product_types.product_type_id and soluong > 0) as dem from tbl_product_types where language_id=$languageid order by priority";
		$tong	= 0;
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
			$dem = ($row['dem'])?$row['dem']:'0';
			$lev1 = array("1", "3", "29","67");
			$lev2 = array("33","58","61","6","54","64");

			
		if ( $row['parent_id'] == 0 )$parent_id = '';
		//elseif ($row['parent_id'] == 1 or $row['parent_id'] == 3 or $row['parent_id'] == 29) $parent_id = '&nbsp;&nbsp;&nbsp;&nbsp;';
		elseif (in_array($row['parent_id'], $lev1)) $parent_id = '&nbsp;&nbsp;&nbsp;&nbsp;';
		elseif (in_array($row['parent_id'], $lev2)) $parent_id = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		else $parent_id = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'product_type_id'	=>	$row['product_type_id'],
				'product_type_code'	=>	$row['product_type_code'],
				'product_type_name'	=>	$row['product_type_name'],
				'post_page'			=>	$row['post_page'],
				'slug'		=>	$row['slug'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'isnu'	 	=>	($row['isnu'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'isnam'	 	=>	($row['isnam'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',
				'view'			=>	$row['view'],	
				'soluong'		=>	$row['soluong'],
				'dem'			=>	$dem,
				'bg'			=>	($row['soluong'] != $dem)?'#00FFFF':'#FFFFFF',	
				'dinhmuc'		=>	(($_SESSION['membername'])!="administrator")?'':$row['dinhmuc'],
				'bg_dinhmuc'	=>	($row['dinhmuc'] > $row['soluong'])?'#CCFFCC':'#FFFFFF',
				'parent_id'		=>	$parent_id,
				'priority'		=>	$row['priority'],
			));	
			$tong +=$dem;
		}
		$template->assign_vars(array(
			'tong'			=>	$tong,
			'width_dinhmuc'	=>	(($_SESSION['membername'])!="administrator")?'1':80,
		));
		$template->set_filenames_new(array(
			'share' => 'product/product_type_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$product_type_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir = $root_path . "images/product_type/";

		if ($product_type_id != 0)
		{	$sql = "select * from tbl_product_types where product_type_id = $product_type_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$image=$row['image'];
				$template->assign_vars(array(
					'product_type_id'	  =>	$product_type_id,
					'product_type_code'	=>	$row['product_type_code'],
					'product_type_name'	=>	$row['product_type_name'],
					'post_page'	   =>	$row['post_page'],
					'slug'			=>	$row['slug'],
					'parent_id'	   =>	$row['parent_id'],
					'active'		  =>	($row['active'] == 1) ? 'checked' : '',
					'isnu'			=>	($row['isnu'] == 1)?'checked':'',
					'isnam'		   =>	($row['isnam'] == 1)?'checked':'',
					'logo'			=>	$row['logo'],
					'image'		   =>	$image,
					'image_hoaca'	 =>	$row['image_hoaca'],
					'logoPath'		=>	($row['logo'])?"<img src='$imgDir".$row['logo']."' border=0 >":"",
					'imgPath'		 =>	($image)?"<img src='$imgDir$image' border=0 >":"",
					'imgHoaCaPath'	=>	($row['image_hoaca'])?"<img src='$imgDir".$row['image_hoaca']."' border=0 >":"",
					'allow_logo'	  =>	($row['logo'])?"":"none",
					'allow'		   =>	($row['image'])?"":"none",
					'allow_hoaca'	 =>	($row['image_hoaca'])?"":"none",
					'view'			=>	$row['view'],
					'soluong'		 =>	$row['soluong'],
					'price'		   =>	$row['price'],
					'dinhmuc'		 =>	$row['dinhmuc'],
					'readonly'		=>	($_SESSION['membername']=="administrator"|| $_SESSION['loginname']=="kieu1")?'':'readonly',
					'top_text'	  	=>	$row['top_text'],
					'intro_text'	  =>	$row['intro_text'],
					'meta_key'		=>	$row['meta_key'],
					'meta_des'		=>	$row['meta_des'],
					'meta_schema'	 =>	$row['meta_schema'],
					'title_seo'	   =>	$row['title_seo'],
					'priority'		=>	$row['priority'],
					'created_date' 	=> 	mosOurFormatDate($row['created_date'], "DMY"),
					'created_by'	  => 	$row['created_by'],
					'last_modified'   => 	mosOurFormatDate($row['last_modified'], "DMY"),
					'modified_by'	 => 	$row['modified_by'],
					'script'		  =>	$row['script'],
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'active'		=>	'checked' ,
				'allow'			=> 	'none',
				'parent_id'		=>	'0',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'product/product_type_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$product_type_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($product_type_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $product_type_id, $direction, "tbl_product_types", "product_type_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$product_type_id 	  = mosGetParam( $_REQUEST, 'id', '0');
		$product_type_code	= mosGetParam( $_REQUEST, 'product_type_code', '');
		$product_type_name	= mosGetParam( $_REQUEST, 'product_type_name', '');
		$post_page			= mosGetParam( $_REQUEST, 'post_page', '');
		$slug				 = mosGetParam( $_REQUEST, 'slug', '');
		$parent_id			= mosGetParam( $_REQUEST, 'parent_id', 0);
		$active			   = mosGetParam( $_REQUEST, 'active', 0);
		$isnu				 = mosGetParam( $_REQUEST, 'isnu', 0);
		$isnam				= mosGetParam( $_REQUEST, 'isnam', 0);
		$view				 = mosGetParam( $_REQUEST, 'view', 0);
		$soluong			  = mosGetParam( $_REQUEST, 'soluong', 0);
		$price			    = mosGetParam( $_REQUEST, 'price', 0);
		$dinhmuc			  = mosGetParam( $_REQUEST, 'dinhmuc', 0);
		$logo				 = mosGetParam( $_REQUEST, 'logo', '');
		$old_logo			 = mosGetParam( $_REQUEST, 'old_logo', '');
		$remove_logo		  = mosGetParam( $_REQUEST, 'remove_logo', '');
		$image				= mosGetParam( $_REQUEST, 'image', '');
		$old_image			= mosGetParam( $_REQUEST, 'old_image', '');
		$remove_image		 = mosGetParam( $_REQUEST, 'remove_image', '');
		$image_hoaca		  = mosGetParam( $_REQUEST, 'image_hoaca', '');
		$old_image_hoaca	  = mosGetParam( $_REQUEST, 'old_image_hoaca', '');
		$remove_image_hoaca   = mosGetParam( $_REQUEST, 'remove_image_hoaca', '');
		$intro_text		   = mosGetParam( $_REQUEST, 'intro_text', '', 0x0003);
		$top_text			 = mosGetParam( $_REQUEST, 'top_text', '', 0x0003);
		$meta_key			 = mosGetParam( $_REQUEST, 'meta_key', '');
		$meta_des			 = mosGetParam( $_REQUEST, 'meta_des', '');
		$title_seo			= mosGetParam( $_REQUEST, 'title_seo', '');
		$priority			 = mosGetParam( $_REQUEST, 'priority', '');
		$script			   = mosGetParam( $_REQUEST, 'script', '', 0x0003);
		
		
		$imgDir = $root_path . "images/product_type/";				
		if (! is_dir($imgDir))
			mkdir($imgDir, 0666);
		$image = mosUploadImage($imgDir, "image");
		if (($remove_image == 0) && ($image == ''))
			$image = $old_image;
			
		$image_hoaca = mosUploadImage($imgDir, "image_hoaca");
		if (($remove_image_hoaca == 0) && ($image_hoaca == ''))
			$image_hoaca = $old_image_hoaca;
			
		$logo = mosUploadImage($imgDir, "logo");
		if (($remove_logo == 0) && ($logo == ''))
			$logo = $old_logo;
		
		if ($product_type_id == '')
		{	
			mosInvalidURL();
			exit;
		}
		
		$num_comment = round($view/10000);
		$num_comment = ($num_comment == 0)?'1':$num_comment;
		$meta_schema = creMetaSchemaProduct($meta_schema, $title_seo, $image_hoaca, $meta_des, $slug, $price, $num_comment, $soluong, $product_type_code, $product_type_id);
		if ($product_type_id == '0')
		{	
			$priority = mosGetPriority("tbl_product_types", "priority", "");
			$sql = "insert into tbl_product_types (product_type_code, product_type_name, post_page, slug, parent_id, active, isnu, isnam, priority, language_id, logo, image, image_hoaca, view, soluong, price, dinhmuc, intro_text, top_text, meta_key, meta_des, title_seo, created_date, created_by, last_modified, modified_by, script) values ('$product_type_code', '$product_type_name', '$post_page', '$slug', '$parent_id', $active, '$isnu', '$isnam', $priority, $languageid, '$logo', '$image', '$image_hoaca', '$view', '$soluong', '$price', '$dinhmuc', '$intro_text', '$top_text', '$meta_key', '$meta_des', '$title_seo', now(), '" . $_SESSION['membername'] . "', now(), '" . $_SESSION['membername'] . "', '$script' )";	
		} else
			{ 
			//if (checkDuplicate("tbl_product_types", array('product_type_name' => $product_type_name), "product_type_name",0,false,"language_id = '$languageid' and product_type_id != $product_type_id"))
			//{	reShowPage( DUPLICATE_ENTRY );
				//exit;
			//}
			$sql = "update tbl_product_types set product_type_code = '$product_type_code', product_type_name ='$product_type_name', post_page = '$post_page', slug = '$slug', parent_id = '$parent_id',  active = $active, isnu = '$isnu', isnam = '$isnam', language_id=$languageid, logo = '$logo', image = '$image', image_hoaca = '$image_hoaca', view = '$view', soluong = '$soluong', price = '$price', dinhmuc = '$dinhmuc', intro_text = '$intro_text', top_text = '$top_text', meta_key = '$meta_key', meta_des = '$meta_des', meta_schema = '$meta_schema', title_seo = '$title_seo', priority = '$priority', last_modified = now() , modified_by= '" . $_SESSION['membername'] . "', script = '$script'  where product_type_id = $product_type_id"; 
			}
	
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		$arrField = array("image","logo","image_hoaca");
		checkDeleteOldFile($image, $old_image, $remove_image, $imgDir , "tbl_product_types", $arrField, "product_type_id", $product_type_id);
		checkDeleteOldFile($image_hoaca, $old_image_hoaca, $remove_image_hoaca, $imgDir , "tbl_product_types", $arrField, "product_type_id", $product_type_id);
		checkDeleteOldFile($logo, $old_logo, $remove_logo, $imgDir , "tbl_product_types", $arrField, "product_type_id", $product_type_id);
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$product_type_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($product_type_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		
		if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			$sql = "select logo,image from tbl_product_types where product_type_id = $product_type_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if( $row = $db->sql_fetchrow($result) )
			{	
				$img = $row['image'];
				$img_hoaca = $row['image_hoaca'];
				$logo = $row['logo'];	
			}
			$arrField = array("image","image_hoaca","logo");
			checkDeleteOldFile("", $logo, 1, $root_path . "images/product_type" , "tbl_product_types", $arrField, "product_type_id", $product_type_id);
			checkDeleteOldFile("", $img, 1, $root_path . "images/product_type" , "tbl_product_types", $arrField, "product_type_id", $product_type_id);
			checkDeleteOldFile("", $img_hoaca, 1, $root_path . "images/product_type" , "tbl_product_types", $arrField, "product_type_id", $product_type_id);
		
			deleteByID("tbl_product_types", "product_type_id", $product_type_id);
		}else
		{
			$template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE_KHONG_DUOC_PHEP));
		}
		
		mosList();
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;				
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$template->assign_vars(array(
			'product_type_code'  =>	mosGetParam( $_REQUEST, 'product_type_code', ''),
			'product_type_name'  =>	mosGetParam( $_REQUEST, 'product_type_name', ''),
			'post_page'		  =>	mosGetParam( $_REQUEST, 'post_page', ''),
			'slug'			   =>	mosGetParam( $_REQUEST, 'slug', ''),
			'parent_id'		  =>	mosGetParam( $_REQUEST, 'parent_id', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'product_type_id'	=>	$id,
			'view'			   =>	mosGetParam( $_REQUEST, 'view', 0),
			'soluong'			=>	mosGetParam( $_REQUEST, 'soluong', 0),
			'price'			  =>	mosGetParam( $_REQUEST, 'price', 0),
			'dinhmuc'		    =>	mosGetParam( $_REQUEST, 'dinhmuc', 0),
			'intro_text'		 =>	mosGetParam( $_REQUEST, 'intro_text', '', 0x0003),
			'top_text'		   =>	mosGetParam( $_REQUEST, 'top_text', '', 0x0003),
			'script'			 =>	mosGetParam( $_REQUEST, 'script', '', 0x0003),
			'meta_key'		   =>	mosGetParam( $_REQUEST, 'meta_key', ''),
			'meta_des'		   =>	mosGetParam( $_REQUEST, 'meta_des', ''),
			'title_seo'		  =>	mosGetParam( $_REQUEST, 'title_seo', ''),
			'created_date'	   =>	mosGetParam( $_REQUEST, 'created_date', ''),
			'created_by'		 =>	mosGetParam( $_REQUEST, 'created_by', ''),
			'last_modified'	  =>	mosGetParam( $_REQUEST, 'last_modified', ''),
			'modified_by'	    =>	mosGetParam( $_REQUEST, 'modified_by', ''),
		));
		$template->set_filenames_new(array(
			'product_type' => 'product/product_type_info.tpl')
		);
		
		$template->pparse('product_type');	
	}
	//--------------------------------------------------------------------------------------------------
	function creMetaSchemaProduct($meta_schema, $title_seo, $image1, $meta_des, $slug, $price, $num_comment, $soluong, $product_code, $product_type_id)
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
		$stock = "InStock";
		//Tạo schema
		if ( 1 )
		{
		$meta_schema = '{
  			"@context" : "http://schema.org",
  			"@type" : "Product",
  			"name" : "'.$title_seo.'",
  			"image" : "https://casauhoaca.com/images/product_type/'.$image1.'",
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
			"gtin8" : "'.$product_type_id.'"
			}';	
		}
		return $meta_schema;
	}
?>
