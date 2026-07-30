<?	
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'product/product_kind',
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

<?
function mosList()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		
		$sql = "select * from tbl_product_kinds where language_id=$languageid order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'product_kind_id'	=>	$row['product_kind_id'],
				'product_kind_name'	=>	$row['product_kind_name'],
				'slug'				=>	$row['slug'],
				'meta_key'			=>	$row['meta_key'],
				'meta_des'			=>	$row['meta_des'],
				'title_seo'			=>	$row['title_seo'],
				'view'				=>	$row['view'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'product/product_kind_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$product_kind_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir = $root_path . "images/product_kind/";

		if ($product_kind_id != 0)
		{	$sql = "select * from tbl_product_kinds where product_kind_id = $product_kind_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$image=$row['image'];
				$template->assign_vars(array(
					'product_kind_id'=>	$product_kind_id,
					'product_kind_name'=>	$row['product_kind_name'],
					'slug'			=>	$row['slug'],
					'meta_key'		=>	$row['meta_key'],
					'meta_des'		=>	$row['meta_des'],
					'title_seo'		=>	$row['title_seo'],
					'view'			=>	$row['view'],
					'active'		=>	($row['active'] == 1) ? 'checked' : '',
					'image'			=>	$image,
					'imgPath'		=>	($image)?"<img src='$imgDir$image' border=0 >":"",
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'active'		=>	'checked' ,
				'allow'		=> 'hidden',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'product/product_kind_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$product_kind_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($product_kind_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $product_kind_id, $direction, "tbl_product_kinds", "product_kind_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$product_kind_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$product_kind_name	= mosGetParam( $_REQUEST, 'product_kind_name', '');
		$slug				= mosGetParam( $_REQUEST, 'slug', '');
		$meta_key			= mosGetParam( $_REQUEST, 'meta_key', '');
		$meta_des			= mosGetParam( $_REQUEST, 'meta_des', '');
		$title_seo			= mosGetParam( $_REQUEST, 'title_seo', '');
		$view			= mosGetParam( $_REQUEST, 'view', 0);
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$image   		= mosGetParam( $_REQUEST, 'new_image', '');
		$old_image   	= mosGetParam( $_REQUEST, 'old_image', '');
		
		$imgDir = $root_path . "images/product_kind/";		

			mosmkdir($imgDir, 0666);		
		$kt=0;
		$img = mosUploadImage($imgDir, "new_image");
		if ($img == '' )
		{	if($adver_id !='0')
			{	$img=$old_image;
				$kt=1;
			}
			else
			{
				reShowPage("UPLOAD ERROR");
				exit;
			}
		}
		
		
		if ($product_kind_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($product_kind_id == '0')
		{	
			if (checkDuplicate("tbl_product_kinds", array('product_kind_name' => $product_kind_name), "product_kind_name",0,false,"language_id = '$languageid'"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_product_kinds", "priority", "");
			$sql = "insert into tbl_product_kinds (product_kind_name, slug, meta_key, meta_des, title_seo, view, active, priority, language_id, image) values ('$product_kind_name', '$slug', '$meta_key', '$meta_des', '$title_seo', '$view', $active, $priority, $languageid, '$img')";	
		} else
			{ 
			if (checkDuplicate("tbl_product_kinds", array('product_kind_name' => $product_kind_name), "product_kind_name",0,false,"language_id = '$languageid' and product_kind_id != $product_kind_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_product_kinds set product_kind_name ='$product_kind_name', slug = '$slug', meta_key = '$meta_key', meta_des = '$meta_des', title_seo = '$title_seo', view = '$view',  active = $active, language_id=$languageid, image = '$img' where product_kind_id = $product_kind_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$product_kind_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($product_kind_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		
		$sql = "select image from tbl_product_kinds where product_kind_id = $product_kind_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )
		{	
			$img = $row['image'];	
		}
		$arrField = array("image");
		checkDeleteOldFile("", $img, 1, $root_path . "images/product_kind" , "tbl_product_kinds", $arrField, "product_kind_id", $product_kind_id);
		if(strtolower($_SESSION['membername'])=="administrator"){	
			deleteByID("tbl_product_kinds", "product_kind_id", $product_kind_id);
      $template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
		}else{
		  $template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
		}
		mosList();
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;				
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$template->assign_vars(array(
			'product_kind_name' 	=>	mosGetParam( $_REQUEST, 'product_kind_name', ''),
			'slug' 				=>	mosGetParam( $_REQUEST, 'slug', ''),
			'meta_key'			=>	mosGetParam( $_REQUEST, 'meta_key', ''),
			'meta_des' 			=>	mosGetParam( $_REQUEST, 'meta_key', ''),
			'title_seo' 		=>	mosGetParam( $_REQUEST, 'title_seo', ''),
			'view' 				=>	mosGetParam( $_REQUEST, 'view', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'product_kind_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'product_kind' => 'product/product_kind_info.tpl')
		);
		
		$template->pparse('product_kind');	
	}
?>