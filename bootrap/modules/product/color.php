<?	
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'product/color',
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
		global $db, $root_path, $color, $languageid, $template;
		
		$sql = "select * from tbl_colors where language_id=$languageid order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'color_id'	=>	$row['color_id'],
				'color_name'=>	$row['color_name'],
				'slug'				=>	$row['slug'],
				'meta_key'			=>	$row['meta_key'],
				'meta_des'			=>	$row['meta_des'],
				'title_seo'			=>	$row['title_seo'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'product/color_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $color, $languageid, $template;
		$color_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir = $root_path . "images/color/";

		if ($color_id != 0)
		{	$sql = "select * from tbl_colors where color_id = $color_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$image=$row['image'];
				$template->assign_vars(array(
					'color_id'=>	$color_id,
					'color_name'=>	$row['color_name'],
					'slug'			=>	$row['slug'],
					'meta_key'		=>	$row['meta_key'],
					'meta_des'		=>	$row['meta_des'],
					'title_seo'		=>	$row['title_seo'],
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
			'share' => 'product/color_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$color_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($color_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $color_id, $direction, "tbl_colors", "color_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $color, $languageid, $template;	
		$color_id 		= mosGetParam( $_REQUEST, 'id', '0');
		$color_name		= mosGetParam( $_REQUEST, 'color_name', '');
		$slug			= mosGetParam( $_REQUEST, 'slug', '');
		$meta_key		= mosGetParam( $_REQUEST, 'meta_key', '');
		$meta_des		= mosGetParam( $_REQUEST, 'meta_des', '');
		$title_seo		= mosGetParam( $_REQUEST, 'title_seo', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$image   		= mosGetParam( $_REQUEST, 'new_image', '');
		$old_image   	= mosGetParam( $_REQUEST, 'old_image', '');
		
		$imgDir = $root_path . "images/color/";		

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
		
		
		if ($color_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($color_id == '0')
		{	
			if (checkDuplicate("tbl_colors", array('color_name' => $color_name), "color_name",0,false,"language_id = '$languageid'"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_colors", "priority", "");
			$sql = "insert into tbl_colors (color_name, slug, meta_key, meta_des, title_seo, active, priority, language_id, image) values ('$color_name', '$slug', '$meta_key', '$meta_des', '$title_seo', $active, $priority, $languageid, '$img')";	
		} else
			{ 
			if (checkDuplicate("tbl_colors", array('color_name' => $color_name), "color_name",0,false,"language_id = '$languageid' and color_id != $color_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_colors set color_name ='$color_name', slug = '$slug', meta_key = '$meta_key', meta_des = '$meta_des', title_seo = '$title_seo',  active = $active, language_id=$languageid, image = '$img' where color_id = $color_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$color_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($color_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		
		$sql = "select image from tbl_colors where color_id = $color_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )
		{	
			$img = $row['image'];	
		}
		$arrField = array("image");
		checkDeleteOldFile("", $img, 1, $root_path . "images/color" , "tbl_colors", $arrField, "color_id", $color_id);
		if(strtolower($_SESSION['membername'])=="administrator"){	
			deleteByID("tbl_colors", "color_id", $color_id);
      $template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
		}else{
		  $template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
		}
		mosList();
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	
	function reShowPage( $message )
	{	global $db, $root_path, $color, $languageid, $template, $theme;				
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$template->assign_vars(array(
			'color_name' 	=>	mosGetParam( $_REQUEST, 'color_name', ''),	
			'slug'			=>	mosGetParam( $_REQUEST, 'slug', ''),
			'meta_key'		=>	mosGetParam( $_REQUEST, 'meta_key', ''),
			'meta_des'		=>	mosGetParam( $_REQUEST, 'meta_des', ''),
			'title_seo'		=>	mosGetParam( $_REQUEST, 'title_seo', ''),		
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'color_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'color' => 'product/color_info.tpl')
		);
		
		$template->pparse('color');	
	}
?>