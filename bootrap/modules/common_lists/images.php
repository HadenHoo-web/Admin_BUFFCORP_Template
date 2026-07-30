<?php
	global $languageid, $template, $cat_id, $img_id;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,			
		'funname'	=> 'common_lists/images',
		'LANGUAGEID'=> $languageid,
		
	));				
	switch( $action )
	{	
		case 'cat_save':	mosCategorySave(); break;
		case 'cat_info'	:	mosCategoryInfo();break;
		case 'cat_update':	mosCategoryUpdate(); break;	
		case 'cat_delete':	mosCategoryDelete(); break;	

		case 'image_list':		mosImageList(); break;
		case 'image_upload':	mosimageUpload(); break;
		case 'image_delete' : 	mosImageDelete(); break;
		case 'image_select':			mosImageSelect(); break;
		case 'image_select_upload':		mosImageSelectUpload(); break;
		case 'image_select_delete':		mosImageSelectDelete(); break;
		
		default:
			mosInvalidURL();
			exit;
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosImageList($cat_id  =  -1)
	{	global $db, $root_path, $skin, $languageid, $template;
		
		if  ($cat_id  ==  -1) { $cat_id  = mosGetParam( $_REQUEST, 'cid', 0 );}
		$parent_id = mosGetParam( $_REQUEST, 'pr',  0 );
		$template->set_filenames_new(array(
			'images' => 'common_lists/images/images.tpl')
		);
		
			$sql = "select cat_id, cat_name from tbl_image_categories where parent_id=$cat_id order by priority";
			if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
			$order=0;	
				while ( $row = $db->sql_fetchrow($result) )
				{	
					$order++;
					$template->assign_block_vars('folderList', array(
					'cat_phay'		=>	($order==1)?'':',',
					'cat_id'		=>  $row['cat_id'],
					'cat_name'		=>	$row['cat_name']
				));
					
				}
			$sql = "select cat_id,img_id, img_name,img_size,w,h from tbl_images where cat_id=$cat_id order by img_name";
			if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
			$order=0;	
				while ( $row = $db->sql_fetchrow($result) )
				{	
					$img_thumb="../images/image_manager/cat$cat_id/thumbnail/".$row['img_name'];
					$img_name=	$row['img_name'];
					if(!file_exists("../images/image_manager/cat$cat_id/thumbnail/$img_name"))
						$img_thumb="../images/image_manager/nothumb/default.jpg";
					$order++;
					$template->assign_block_vars('imgList', array(
					'img_phay'		=>	($order==1)?'':',',
					'img_id'		=>  $row['img_id'],
					'img_name'		=>	$row['img_name'],
					'img_thumb'		=>	$img_thumb,	
					'img_size'		=>	$row['img_size'],
					'img_w'		=>	$row['w'],
					'img_h'		=>	$row['h'],
				));
					
				}
		
			mosCatChain($cat_id) ;
			$template->assign_vars(array(
			'cat_id'	=> $cat_id,
			'cat_name'	=> $cat_name,
			'parent_id'	=>$parent_id,
			'toolbar' 	=> ($cat_id == 0) ? 'hidden' : "",
			'show' 		=> ($cat_id == 0) ? 'none' : ""
		));	
		$template->pparse('images');
	}	

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosCategorySave()
	{	global $db, $root_path, $skin, $languageid, $template, $cat_id, $parent_id;	

		$cat_name  		= mosGetParam( $_REQUEST, 'cat_name', '' );	
		$cat_id	   		= mosGetParam( $_REQUEST, 'cid', '0' );	
		$parent_id	   		= mosGetParam( $_REQUEST, 'pr', '0' );
		if (checkDuplicate("tbl_image_categories", array('cat_name' => $cat_name), "cat_id", $cat_id, false, " parent_id = $parent_id"))
		{	
			$template->assign_var('MESSAGE',  DUPLICATE_ENTRY );
			mosImageList();
			exit;
		}
		
		
			$priority = mosGetPriority("tbl_image_categories", "priority", "parent_id = $parent_id");
			$sql = "insert into tbl_image_categories (cat_name, parent_id, created_date, created_by, priority) values ('$cat_name', $parent_id, now(), '" . $_SESSION['membername'] . "', $priority)";
			if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);		
		
		mosImageList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosCategoryUpdate()
	{	global $db, $root_path, $skin, $languageid, $template, $cat_id, $parent_id;	
		$cat_name  		= mosGetParam( $_REQUEST, 'cat_name', '' );	
		$cat_id	   		= mosGetParam( $_REQUEST, 'cid', '0' );	
		$parent_id	   		= mosGetParam( $_REQUEST, 'pr', '0' );
		if (checkDuplicate("tbl_image_categories", array('cat_name' => $cat_name), "cat_id", $cat_id, false, " parent_id = $parent_id"))
		{	
			reShowCategory();
			exit;
		}					
			$sql = "update tbl_image_categories set cat_name='$cat_name' where cat_id=$cat_id";
			if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);			
		mosImageList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function reShowCategory( $message = DUPLICATE_ENTRY)
	{	global $db, $root_path, $skin, $languageid, $template, $cat_id;	
		$template->assign_vars(array(
			'cat_id'		=>	$cat_id,
			'cat_name' 		=>	mosGetParam( $_REQUEST, 'cat_name', ''),
			'parent_id'		=>	mosGetParam( $_REQUEST, 'pr', '0'),
			'MESSAGE'		=>	$message
		));		
		$template->set_filenames_new(array(
			'images' => 'common_lists/images/image_cat_info.tpl')
		);
		$template->pparse('images');	
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosCatChain($cat_ID)
{	global $db, $template;
	$sql = "select parent_id, cat_name, cat_id from tbl_image_categories where cat_ID = $cat_ID";
	if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
	if( $row = $db->sql_fetchrow($result) )
	{	if ($row['parent_id'] != 0)	
			mosCatChain($row['parent_id']);
		$template->assign_block_vars('catChain', array(
			'cat_id'	=>	$row['cat_id'],
			'cat_name'	=>	$row['cat_name'],
			'parent_id'	=>	$row['parent_id']
		));
	}	
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosCategoryInfo()
	{	global $db, $root_path, $skin, $languageid, $template,$theme;
		$the_id 	 = mosGetParam( $_REQUEST, 'cid', 0 );
		if ($the_id == 0)
		{
			mosInvalidURL();
			exit;
		}
		else
		{	
			$template->set_filenames_new(array(
			'images' => 'common_lists/images/image_cat_info.tpl')
			);
			$sql = "select cat_id,cat_name,parent_id from tbl_image_categories where cat_id = $the_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	$template->assign_vars(array(
					'cat_id'		=>	$the_id,
					'cat_name' 		=>	$row['cat_name'],
					'parent_id'	=>	$row['parent_id'],
					
				));
			} 
			else
				message_die( ID_NOTFOUND );		
		}					
		$template->pparse('images');
	}	
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	

function mosCategoryDelete()
	{	global $db, $parent_id, $template, $root_path,$skin,$theme;
		$cat_id	   		= mosGetParam( $_REQUEST, 'cid', '0' );	
		if ( mosDataCount( "tbl_images", "cat_id = $cat_id") + mosDataCount( "tbl_image_categories", "parent_id = $cat_id") > 0)
		{	
			$template->assign_vars(array('MESSAGE' => NONE_EMPTY_ERROR));
			mosImageList($cat_id);			
			exit;		
		}
		$sql="select parent_id from tbl_image_categories where cat_id = $cat_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	$parent_id=$row['parent_id'];
				deleteByID("tbl_image_categories", "cat_id", $cat_id);
				/*	Xoa tat ca cac anh trong thu muc tuong ung va xoa thu muc do di. */	
				mosDeleteDirectory($root_path . "images/image_manager/cat$cat_id/thumbnail");
				mosDeleteDirectory($root_path . "images/image_manager/cat$cat_id");
				mosImageList($parent_id);
			} 
			else
				message_die( ID_NOTFOUND );		
		
		
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosImageDelete()
	{	global $db, $parent_id, $template, $root_path,$skin,$theme;
		$img_id	   		= mosGetParam( $_REQUEST, 'imgid', '0' );	
		$cat_id  = mosGetParam( $_REQUEST, 'cid', '0' );
		if($img_id == '0')
		{
			mosInvalidURL();
			exit;
		}
		$sql="select img_name,cat_id from tbl_images where img_id = $img_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
				{
					$img_name	=	$row['img_name'];
					deleteByID("tbl_images", "img_id", $img_id);
					/*	Xoa anh trong thu muc tuong ung */	
					if(file_exists($root_path . "images/image_manager/cat$cat_id/$img_name"))
						unlink($root_path . "images/image_manager/cat$cat_id/$img_name");
					if(file_exists($root_path . "images/image_manager/cat$cat_id/thumbnail/$img_name"))
						unlink($root_path . "images/image_manager/cat$cat_id/thumbnail/$img_name");
					mosImageList($cat_id);

				}
			else
			{
				mosImageList($cat_id);
			}
						
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	

function mosImageUpload()
{	global $db, $root_path, $skin, $languageid, $template;	
	$cat_id   		= mosGetParam( $_REQUEST, 'cid', '0');
	$img_name  		= mosGetParam( $_REQUEST, 'filename', '');
	$imgDir = $root_path . "images/image_manager/cat$cat_id/";		
	mosmkdir($imgDir, 0666);
//	if (!is_dir($imgDir)) mkdir($imgDir, 0666);
	$imgDir_thumbnail = $root_path . "images/image_manager/cat$cat_id/thumbnail/";
	mosmkdir($imgDir_thumbnail, 0666);
//	if (!is_dir($imgDir_thumbnail)) mkdir($imgDir_thumbnail, 0666);	

	$img = mosUploadImage($imgDir, "filename" );
	if ($img == '')
	{		$template->assign_var('MESSAGE', UPLOAD_ERROR);
			mosImageList($cat_id);			
			exit;	
	} else
	{	$imagesize = getimagesize($imgDir.$img);			
		$filesize = filesize($imgDir.$img);	
	}
	$sql="select cat_id from tbl_images where img_name='$img' and cat_id=$cat_id";
	if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
	if( $row = $db->sql_fetchrow($result) )
		{	
			$template->assign_var('MESSAGE',  DUPLICATE_ENTRY );
			mosImageList($cat_id);
			exit;
		}
	else
		{
			mosCreateThumbnail($imgDir . $img, $imgDir_thumbnail.$img,100,100 );
			$sql = "insert into tbl_images (cat_id, img_name, img_size, w, h) values ($cat_id, '$img', $filesize, $imagesize[0],$imagesize[1])";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );	
			mosimageList($cat_id);
		}
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosImageSelect($cat_id  =  -1)
	{	global $db, $root_path, $skin, $languageid, $template;
		
		if  ($cat_id  ==  -1) { $cat_id  = mosGetParam( $_REQUEST, 'cid', 0 );}
		$cat_id_tam = $cat_id;
		$parent_id = mosGetParam( $_REQUEST, 'pr',  0 );
		$template->set_filenames_new(array(
			'images' => 'common_lists/images/images_select.tpl')
		);
		
			$sql = "select cat_id, cat_name,parent_id from tbl_image_categories order by priority";
			if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
			$order=0;	
				while ( $row = $db->sql_fetchrow($result) )
				{	
					$order++;
					$template->assign_block_vars('folderList', array(
					'cat_phay'		=>	($order==1)?'':',',
					'cat_id'		=>  $row['cat_id'],
					'cat_name'		=>	$row['cat_name'],
					'parent_id'		=> $row['parent_id']
					));
					
				}
			$sql = "select cat_id,img_id, img_name from tbl_images order by img_id DESC";	
			if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
			$order=0;	
				while ( $row = $db->sql_fetchrow($result) )
				{	
					$img_name=	$row['img_name'];
					$cat_id=$row['cat_id'];
					$img_thumb="../images/image_manager/cat$cat_id/thumbnail/".$img_name;
					if(!file_exists("../images/image_manager/cat$cat_id/thumbnail/$img_name"))
						$img_thumb="../images/image_manager/nothumb/default.jpg";
					$order++;
					$template->assign_block_vars('imgList', array(
					'img_phay'		=>	($order==1)?'':',',
					'img_id'		=>  $row['img_id'],
					'img_name'		=>	$row['img_name'],
					'img_thumb'		=>	$img_thumb,	
					'cat_id'		=>	$row['cat_id'],
					
					));
					
				}
			$template->assign_vars(array(
				'cat_id'	=>	$cat_id_tam,
			));
			catList(0);
		$template->pparse('images');
	}	
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	

function mosImageSelectUpload()
{	global $db, $root_path, $skin, $languageid, $template;	
	$cat_id   		= mosGetParam( $_REQUEST, 'cid', '0');
	$img_name  		= mosGetParam( $_REQUEST, 'filename', '');
	$imgDir = $root_path . "images/image_manager/cat$cat_id/";		
	mosmkdir($imgDir, 0666);
//	if (!is_dir($imgDir)) mkdir($imgDir, 0666);
	$imgDir_thumbnail = $root_path . "images/image_manager/cat$cat_id/thumbnail/";
	mosmkdir($imgDir_thumbnail, 0666);
//	if (!is_dir($imgDir_thumbnail)) mkdir($imgDir_thumbnail, 0666);	

	$img = mosUploadImage($imgDir, "filename" );
	if ($img == '')
	{		$template->assign_var('MESSAGE', UPLOAD_ERROR);
			mosImageSelect($cat_id);			
			exit;	
	} else
	{	$imagesize = getimagesize($imgDir.$img);			
		$filesize = filesize($imgDir.$img);	
	}
	$sql="select cat_id from tbl_images where img_name='$img' and cat_id=$cat_id";
	if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
	if( $row = $db->sql_fetchrow($result) )
		{	
			$template->assign_var('MESSAGE',  DUPLICATE_ENTRY );
			mosImageSelect($cat_id);
			exit;
		}
	else
		{
			mosCreateThumbnail($imgDir . $img, $imgDir_thumbnail.$img,100,100 );
			$sql = "insert into tbl_images (cat_id, img_name, img_size, w, h) values ($cat_id, '$img', $filesize, $imagesize[0],$imagesize[1])";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );	
			mosImageSelect($cat_id);
		}
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosImageSelectDelete()
	{	global $db, $parent_id, $template, $root_path,$skin,$theme;
		$img_id	   		= mosGetParam( $_REQUEST, 'imgid', '0' );	
		$cat_id  = mosGetParam( $_REQUEST, 'cid', '0' );
		if($img_id == '0')
		{
			mosInvalidURL();
			exit;
		}
		$sql="select img_name,cat_id from tbl_images where img_id = $img_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
				{
					$img_name	=	$row['img_name'];
					deleteByID("tbl_images", "img_id", $img_id);
					/*	Xoa anh trong thu muc tuong ung */	
					if(file_exists($root_path . "images/image_manager/cat$cat_id/$img_name"))
						unlink($root_path . "images/image_manager/cat$cat_id/$img_name");
					if(file_exists($root_path . "images/image_manager/cat$cat_id/thumbnail/$img_name"))
						unlink($root_path . "images/image_manager/cat$cat_id/thumbnail/$img_name");
					mosImageSelect($cat_id);

				}
			else
			{
				mosImageSelect($cat_id);
			}
						
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	

function catList($parent_id, $prefix = "&nbsp;")
{	global $db, $languageid, $template;
	$sql = "SELECT cat_id, cat_name FROM tbl_image_categories a WHERE (parent_id = $parent_id) ORDER BY priority" ;
	if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
	while( $row = $db->sql_fetchrow($result) )
	{	$template->assign_block_vars('catlist', array(
			'cat_id'	=>	$row['cat_id'],
			'cat_name'	=>	$prefix. $row['cat_name']
		));
		catList($row['cat_id'], $prefix."&nbsp;&nbsp;&nbsp;" );
	}	
}	
	
?>