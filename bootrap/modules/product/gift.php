<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'product/gift',
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
		
		$sql = "select * from tbl_gifts where language_id=$languageid order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'gift_id'	=>	$row['gift_id'],
				'gift_name'	=>	$row['gift_name'],
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
			'share' => 'product/gift_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$gift_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir = $root_path . "images/gift/";

		if ($gift_id != 0)
		{	$sql = "select * from tbl_gifts where gift_id = $gift_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$image=$row['image'];
				$template->assign_vars(array(
					'gift_id'=>	$gift_id,
					'gift_name'=>	$row['gift_name'],
					'slug'			=>	$row['slug'],
					'meta_key'		=>	$row['meta_key'],
					'meta_des'		=>	$row['meta_des'],
					'title_seo'		=>	$row['title_seo'],
					'view'			=>	$row['view'],
					'active'		=>	($row['active'] == 1) ? 'checked' : '',
					'image'			=>	$image,
					'imgPath'		=>	($image)?"<img src='$imgDir$image' border=0 >":"",
					'intro_text'	=>	$row['intro_text'],
					'product_id'	=>	$row['product_id'],
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
			'share' => 'product/gift_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$gift_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($gift_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $gift_id, $direction, "tbl_gifts", "gift_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$gift_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$gift_name	= mosGetParam( $_REQUEST, 'gift_name', '');
		$slug				= mosGetParam( $_REQUEST, 'slug', '');
		$meta_key			= mosGetParam( $_REQUEST, 'meta_key', '');
		$meta_des			= mosGetParam( $_REQUEST, 'meta_des', '');
		$title_seo			= mosGetParam( $_REQUEST, 'title_seo', '');
		$view			= mosGetParam( $_REQUEST, 'view', 0);
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$image   		= mosGetParam( $_REQUEST, 'new_image', '');
		$old_image   	= mosGetParam( $_REQUEST, 'old_image', '');
		$intro_text		= mosGetParam( $_REQUEST, 'intro_text', '', 0x0003);
		$product_id		= mosGetParam( $_REQUEST, 'product_id', '');
		
		$imgDir = $root_path . "images/gift/";		

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
		
		
		if ($gift_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($gift_id == '0')
		{	
			if (checkDuplicate("tbl_gifts", array('gift_name' => $gift_name), "gift_name",0,false,"language_id = '$languageid'"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_gifts", "priority", "");
			$sql = "insert into tbl_gifts (gift_name, slug, meta_key, meta_des, title_seo, view, active, priority, language_id, image, intro_text, product_id) values ('$gift_name', '$slug', '$meta_key', '$meta_des', '$title_seo', '$view', $active, $priority, $languageid, '$img', '$intro_text', '$product_id')";	
		} else
			{ 
			if (checkDuplicate("tbl_gifts", array('gift_name' => $gift_name), "gift_name",0,false,"language_id = '$languageid' and gift_id != $gift_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			
			$sql = "update tbl_gifts set gift_name ='$gift_name', slug = '$slug', meta_key = '$meta_key', meta_des = '$meta_des', title_seo = '$title_seo', view = '$view',  active = $active, language_id=$languageid, image = '$img', intro_text = '$intro_text', product_id = '$product_id 1' where gift_id = $gift_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$gift_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($gift_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		
		$sql = "select image from tbl_gifts where gift_id = $gift_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )
		{	
			$img = $row['image'];	
		}
		$arrField = array("image");
		checkDeleteOldFile("", $img, 1, $root_path . "images/gift" , "tbl_gifts", $arrField, "gift_id", $gift_id);
		if(strtolower($_SESSION['membername'])=="administrator"){	
			deleteByID("tbl_gifts", "gift_id", $gift_id);
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
			'gift_name' 	=>	mosGetParam( $_REQUEST, 'gift_name', ''),
			'slug' 				=>	mosGetParam( $_REQUEST, 'slug', ''),
			'meta_key'			=>	mosGetParam( $_REQUEST, 'meta_key', ''),
			'meta_des' 			=>	mosGetParam( $_REQUEST, 'meta_key', ''),
			'title_seo' 		=>	mosGetParam( $_REQUEST, 'title_seo', ''),
			'view' 				=>	mosGetParam( $_REQUEST, 'view', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'gift_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'gift' => 'product/gift_info.tpl')
		);
		
		$template->pparse('gift');	
	}
?>
