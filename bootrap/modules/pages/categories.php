<?php
	global $template, $root_path, $languageid;
	$action = mosGetParam( $_REQUEST, 'mode', '');	
	
	$template->assign_vars(array(
		'ROOT'		 => $root_path,
		'funname'	 => 'pages/categories'
	));
	switch( $action )
	{	case 'list':		mosCategoryList(); break;		
		case 'info':		mosCategoryInfo(); break;
		case 'save':		mosCategorySave(); break;
		case 'delete':		mosCategoryDelete(); break;
		case 'moveup':		mosCategoryMove('up'); break;
		case 'movedown': 	mosCategoryMove('down'); break;		
		default:
			mosInvalidURL();
			exit;
	}

function mosCategoryInfo($parent_id = 0)
{	global $template, $db, $languageid, $skin, $langpath, $root_path;
	if ($parent_id == 0) $parent_id = mosGetParam( $_REQUEST, 'pr', 0);	
	$the_id = mosGetParam( $_REQUEST, 'id', 0 );
	$template->set_filenames_new(array('category_info' => "pages/category_info.tpl"));
	if ($the_id != 0)
	{	$sql = "select * from tbl_page_categories where cat_id = $the_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{	$cat_name 			= $row['cat_name'];
			 $template_name 	   = $row['template'];
			$workflow_id 		  = $row['workflow_id'];			
			$is_new			   = 0;
			$parent_id 		    = $row['parent_id'];
			$imgDir			   = $root_path."images/cat/";
			$template->assign_vars(array(
				'visible'		 => ($row['visible'] == 1) ? 'checked' : '',
				'lock'			=> ($row['lock'] == 1) ? 'checked' : '',				
				'created_date' 	=> mosOurFormatDate($row['created_date'], "DMY"),
				'created_by'	  => $row['created_by'],
				'last_modified'   => mosOurFormatDate($row['last_modified'], "DMY"),
				'modified_by'	 => $row['modified_by'],
				'workflow_id'	 =>	$row['workflow_id'],
				'description'	 => $row['description'],
				'alias'		   => $row['alias'],
				'slug'			=> $row['slug'],
				'title_seo'	   => $row['title_seo'],
				'meta_key'		=> $row['meta_key'],
				'meta_des'		=> $row['meta_des'],
				'meta_schema'	 => $row['meta_schema'],
//				'slogan'		=> $row['slogan'],
				'image' 		   =>	$row['image'],
				'imgPath'		 =>	($row['image'] == '') ? "" : $imgDir.$row['image'],
				'view'			=> $row['view'],
			));
		} else
			message_die( ID_NOTFOUND );		
	} else
	{	$cat_name 		= '';
		$template_name 	= 'default.tpl';
		$is_new			= 1;
	}
	$template->assign_vars(array(
		'cat_id'		=>	$the_id,
		'cat_name' 		=>	$cat_name,
		'parent_id'		=>	$parent_id,
		'template'		=>	$template_name,
		'is_new'		=>	$is_new,
		'template_list'	=> 	mosTemplateList('page_category', false)
	));	
	if ($parent_id != 0) 
		mosCatChain($parent_id);
	if (!defined('SPACES')) define('SPACES', "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
	catList(0);
	$template->pparse('category_info');
}

function mosCategoryList($parent_id = 0)
{	global $template, $db, $languageid, $skin, $langpath;
	if ($parent_id == 0) $parent_id = mosGetParam( $_REQUEST, 'pr', 0);	
	$template->set_filenames_new(array('category_list' => "pages/category_list.tpl"));
	if ($parent_id == 0)
	{	$sql = "select cat_id, cat_name from tbl_page_categories where parent_id = 0 and language_id = $languageid order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
		if( $row = $db->sql_fetchrow($result) )
			$parent_id = $row['cat_id'];
		else
		{	mosInvalidURL();
			return;
		}		
	}
	$sql = "select parent_id from tbl_page_categories where cat_id = $parent_id";
	if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
	if ( $row = $db->sql_fetchrow($result) )
	{	$template->assign_var('isROOT', ($row['parent_id'] == 0) ? '1' : '0');
	}

	$sql = "select cat_id, cat_name, parent_id, created_date, created_by from tbl_page_categories where parent_id = $parent_id and language_id = $languageid order by priority";
	if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
	$num_row = $db->sql_numrows($result);
	$order = 0;	
	while( $row = $db->sql_fetchrow($result) )
	{	$order = $order + 1;
		$template->assign_block_vars('list', array(
			'className'		=>  ($order % 2 == 1) ? 'alt' : 'inv',
			'order'			=>  $order,
			'cat_id'		=>	$row['cat_id'],
			'cat_name'		=>	$row['cat_name'],
			'parent_id'		=>  $row['parent_id'],
			'created_date'	=>	mosOurFormatDate($row['created_date'], "DMY"),
			'created_by'	=>	$row['created_by'],
			'up'			=>	($order == 1) ? ' display: none;' : '',
			'down'			=>	($order == $num_row) ? ' display: none;' : '',
		));	
	}
	$template->assign_vars(array(
		'cat_id' => $parent_id
	));
	if ($num_row == 0) $template->assign_var('MESSAGE', EMPTY_CATEGORY);
	mosCatChain($parent_id);
	$template->pparse('category_list');
}

function mosCategoryMove($direction = 'up')
{	global $template;
	$cat_id = mosGetParam( $_REQUEST, 'id', 0 );
	if ($cat_id == 0)
		mosInvalidURL();
	else
	{	mosChangePriority( $cat_id, $direction, "tbl_page_categories", "cat_id", "priority", $condition = "(a.parent_id = b.parent_id) and (a.language_id = b.language_id)");
		$template->assign_var('MESSAGE', MOVE_SUCCESS);	
		mosCategoryList();
	}
}

function mosCatChain($cat_ID)
{	global $db, $template;
	$sql = "select parent_id, cat_name, cat_id from tbl_page_categories where cat_ID = $cat_ID";
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

function mosGetParent($cat_id)
{	global $db;
	$sql = "select parent_id from tbl_page_categories where cat_id = $cat_id";
	if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
	if( $row = $db->sql_fetchrow($result) )
		return $parent_id = $row['parent_id'];
	else
		return 0;	
}

//---------------------------------------------------------------------------------------------------
	function reShowCategory( $message = DUPLICATE_ENTRY)
	{	global $db, $root_path, $skin, $languageid, $template, $cat_id, $langpath;	
		$template->assign_vars(array(
			'cat_id'		=>	$cat_id,
			'cat_name' 		=>	mosGetParam( $_REQUEST, 'cat_name', ''),
			'parent_id'		=>	mosGetParam( $_REQUEST, 'parent_id', '0'),
			'created_date'	=>	mosGetParam( $_REQUEST, 'created_date', ''),
			'description'	=>	mosGetParam( $_REQUEST, 'description', ''),
			'created_by'	=>	mosGetParam( $_REQUEST, 'created_by', ''),
			'last_modified'	=>	mosGetParam( $_REQUEST, 'last_modified', ''),
			'modified_by'	=>	mosGetParam( $_REQUEST, 'modified_by', ''),		
			'workflow_id'	=>	mosGetParam( $_REQUEST, 'workflow_id', ''),
			'template'		=>	mosGetParam( $_REQUEST, 'template', 'default.tpl'),
			'alias'		=>	mosGetParam( $_REQUEST, 'alias', ''),
			'slug'		=>	mosGetParam( $_REQUEST, 'slug', ''),
			'meta_key'		=>	mosGetParam( $_REQUEST, 'meta_key', ''),
			'meta_des'		=>	mosGetParam( $_REQUEST, 'meta_des', ''),
//			'theme'			=>	mosGetParam( $_REQUEST, 'theme', 'default.css'),
			'template_list'	=> 	mosTemplateList('page_category', false),
			'theme_list'	=> 	mosThemeList(false),
			'lock'			=>	(mosGetParam( $_REQUEST, 'lock', 0) == 1) ? 'checked' : '',
			'visible'		=>	(mosGetParam( $_REQUEST, 'visible', 0) == 1) ? 'checked' : '',			
			'MESSAGE'		=>	$message
		));		
		if ($parent_id == 0) 
			$parent_id = mosGetParent($cat_id);
		mosCatChain($parent_id);
		if (!defined('SPACES')) define('SPACES', "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
		catList(0);
		$template->set_filenames_new(array('category_info' => "pages/category_info.tpl"));
		$template->pparse('category_info');	
	}
//---------------------------------------------------------------------------------------------------
	function mosCategorySave()
	{	global $db, $root_path, $skin, $languageid, $template, $cat_id, $parent_id;	

		$cat_name  			= mosGetParam( $_REQUEST, 'cat_name', '' );	
		$cat_id	   		  = mosGetParam( $_REQUEST, 'id', '0' );	
		$parent_id		   = mosGetParam( $_REQUEST, 'pr', '0' );
		$workflow_id		 = mosGetParam( $_REQUEST, 'workflow_id', '0' );		
		$template_name 	   = mosGetParam( $_REQUEST, 'template', 'default.tpl' );				
//		$theme_name 	= mosGetParam( $_REQUEST, 'theme', 'default.css' );	
		$visible 			 = mosGetParam( $_REQUEST, 'visible', '0' );	
		$lock 				= mosGetParam( $_REQUEST, 'lock', '0' );	
		$description		 = mosGetParam( $_REQUEST, 'description', '', 0x0003);	
		$alias 			   = mosGetParam( $_REQUEST, 'alias', 'none' );
		$slug 				= mosGetParam( $_REQUEST, 'slug', '' );
		$title_seo		   = mosGetParam( $_REQUEST, 'title_seo', '' );
		$meta_key			= mosGetParam( $_REQUEST, 'meta_key', '' );
		$meta_des			= mosGetParam( $_REQUEST, 'meta_des', '' );
		$meta_schema		 = mosGetParam( $_REQUEST, 'meta_des', '' );	
		$view		 		= mosGetParam( $_REQUEST, 'view', '0' );
//		$slogan			= mosGetParam( $_REQUEST, 'slogan', '' );	
		$image   			   = mosGetParam( $_REQUEST, 'new_img', '');
		$old_img 			 = mosGetParam( $_REQUEST, 'old_img', '');
		$img_remove 		  = mosGetParam( $_REQUEST, 'img_remove', '0');
			
		if (($parent_id == $cat_id)&& ($cat_id != 0))
		{	reShowCategory( SAVE_FAILED );
			return;
		}
		
		if (checkDuplicate("tbl_page_categories", array('cat_name' => $cat_name), "cat_id", $cat_id, false, " parent_id = $parent_id and language_id = $languageid"))
		{	reShowCategory( DUPLICATE_ENTRY );
			exit;
		}
		
		$imgDir = $root_path . "images/cat/";		
		if (! is_dir($imgDir))
			mosmkdir($imgDir, 0777);
		$kt=0;
		
		$img = mosUploadImage($imgDir, "new_img");
		if (($img_remove == 0) && ($img == ''))
		{	$img = $old_img;
			$kt=1;
		}
		
		$num_comment = round($view/1000);
		$num_comment = ($num_comment>1)?$num_comment:1;
		$meta_schema = creMetaSchemaPage($meta_schema, $title_seo, $img, $cat_name, $meta_des, $slug, $num_comment);
		
		if ($cat_id == 0)
		{	$priority = mosGetPriority("tbl_page_categories", "priority", "parent_id = $parent_id and language_id = $languageid");
			$sql = "insert into tbl_page_categories (cat_name, parent_id, created_date, created_by, priority, language_id, workflow_id, template, last_modified, modified_by, visible, `lock`, description, alias, slug, title_seo, meta_key, meta_des, meta_schema, image)  values ('$cat_name', $parent_id, now(), '" . $_SESSION['membername'] . "', $priority, $languageid, $workflow_id, '$template_name', now(), '" . $_SESSION['membername'] . "', $visible, $lock, '$description','$alias', '$slug', '$title_seo', '$meta_key', '$meta_des', '$meta_schema','$img')";				
			if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);		
		}
		else
		{	
			$arrField = array("image"); 
			checkDeleteOldFile($img, $old_img, $img_remove, $root_path . "images/cat/" , "tbl_page_categories", $arrField, "cat_id", $cat_id);
			if ($kt == 0)
				checkDeleteOldFile($img, $old_img, 1, $root_path . "images/cat/" , "tbl_page_categories", $arrField, "cat_id", $cat_id);
			$sql = "update tbl_page_categories set cat_name = '$cat_name', parent_id = $parent_id, workflow_id = $workflow_id, template = '$template_name', last_modified = now() , modified_by= '" . $_SESSION['membername'] . "' , visible = $visible , `lock` = $lock, description = '$description', alias = '$alias', slug = '$slug', title_seo = '$title_seo', meta_key = '$meta_key', meta_des = '$meta_des', meta_schema = '$meta_schema', image = '$img' where cat_id = $cat_id";			
	
			if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
		}
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosCategoryList();		
	}
//--------------------------------------------------------------------------------------------------
	function creMetaSchemaPage($meta_schema, $title_seo, $image1, $cat_name, $meta_des, $slug, $num_comment)
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
		//Tao schema
		if ( $meta_schema == '' or 1 )
		{
		/*$meta_schema = '{
  "@context" : "http://schema.org",
  "@type" : "Article",
  "mainEntityOfPage":{"@type":"WebPage","@id":"https://casauhoaca.com/'.$slug.'.htm"},
  "headline":"'.$title_seo.'",
  "datePublished" : "2018-09-18",
  "dateModified"  : "'.date("Y-m-d H:i:s").'",
  "image":{"@type":"ImageObject","url":"https://casauhoaca.com/images/page/'.$image1.'","width":370,"height":270},
  "author" : {"@type" : "Person","name" : "Cá Sấu Hoa Cà"},
  "articleSection" : "'.$cat_name.'",
  "articleBody" : "'.$meta_des.'",
  "url" : "https://casauhoaca.com/'.$slug.'.htm",
  "publisher" : {
    "@type" : "Organization",
    "name" : "Cá Sấu Hoa Cà",
	"logo":{"@type":"ImageObject","url":"https://casauhoaca.com/images/logo-casauhoaca.png","width":313,"height":60}
  },
  "aggregateRating" : {
    "@type" : "AggregateRating",
    "ratingValue" : "'.$ratingValue.'",
    "ratingCount" : "'.$ratingCount.'"
  }
}';	*/

//cập nhật ngày 17/10/2019
		$meta_schema = '{
  "@context" : "http://schema.org",
  "@type": "CreativeWorkSeason",
  "aggregateRating": {
    "@type": "AggregateRating",
    "bestRating": "5",
    "ratingValue" : "'.$ratingValue.'",
    "ratingCount" : "'.$ratingCount.'"
  },
  "image": "https://casauhoaca.com/images/page/'.$image1.'",
  "name": "'.$title_seo.'",
  "description": "'.$title_seo.'"
}';
		}
		return $meta_schema;
	}	
//------------------------------------------------------------------------------
// function mosCategoryDelete 
//------------------------------------------------------------------------------
function mosCategoryDelete($cat_id = 0)
{	global $db, $template;	
		if ($cat_id == 0) $cat_id = mosGetParam( $_REQUEST, 'id', 0 );
		// Thông sô´ dâ`u va`o không ho?p lê?, 
		// truo`ng ho?p na`y xa?y ra khi nguo`i du`ng da´nh dou`ng dâ~n tru?c tiê´p trên thanh di?a chi`
		if ($cat_id == 0)
		{	$template->assign_vars(array('MESSAGE'	=>	INVALID_PARAMETER));
			mosCategoryList($parent_id);	// Vê` trang danh sa´ch, không la`m thao ta´c naa`o ca?.
			exit;
		}
		// Kiê?m tra xem danh mu?c co´ tô`n ta?i trong CSDL
		$sql = "select parent_id from tbl_page_categories where cat_id = $cat_id";
		if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);		
		if( $row = $db->sql_fetchrow($result) )	// Nê´u co´ danh mu?c
		{	$parent_id = $row['parent_id'];		// Giu~ thu mu?c cha la?i dê? chuyê?n vê` khi kê´t thu´c quy tri`nh	

			// Kiê?m tra xem co´ trang thông tin na`o trong chuyên mu?c hay không ?
			$sql = "select count(*) as count from tbl_pages where cat_id = $cat_id";
			if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);		
			if( $row = $db->sql_fetchrow($result) )
			{	if ($row['count'] > 0)	// Co´ trang chuyên mu?c trong trang
				{	$template->assign_vars(array('MESSAGE'	=>	NONE_EMPTY_DELETED));	// Thông ba´o lô~i
				} else	// nguo?c la?i, tiê´p tu?c kiê?m tra xem chuyên mu?c co´ chu´a ca´c chuyên mu?c con
				{	$sql = "select count(*) as count from tbl_page_categories where parent_id = $cat_id";
					if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);		
					if( $row = $db->sql_fetchrow($result) )
					{	if ($row['count'] > 0)	// Nê´u chuyên mu?c co´ chu´a ca´c chuyên mu?c con
							$template->assign_vars(array('MESSAGE'	=>	NONE_EMPTY_DELETED));	// thông ba´o lô~i
						else
							deleteByID("tbl_page_categories", "cat_id", $cat_id);	// Xo´a chuyên mu?c
					}
				}
			}
		} else
		{	$template->assign_vars(array('MESSAGE'	=>	NOT_EXISTS_CATEGORY));	// Thông ba´o lô~i nê´u chuyên mu?c không tô`n ta?i trong CSDL
			$parent_id = 0;	// Không xa´c di?nh duo?c cha do không tô`n ta?i danh mu?c, vê` trang danh mu?c chi´nh
		}
		mosCategoryList($parent_id);	// Vê` trang danh mu?c sau khi kê´t thu´c quy tri`nh xo´a chuyên mu?c trang thông tin
}

function catList($parent_id, $prefix = SPACES)
{	global $db, $languageid, $template;
	$sql = "SELECT cat_id, cat_name FROM tbl_page_categories a WHERE (parent_id = $parent_id) and (language_id = $languageid) ORDER BY priority" ;
	if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
	while( $row = $db->sql_fetchrow($result) )
	{	$template->assign_block_vars('catlist', array(
			'cat_id'	=>	$row['cat_id'],
			'cat_name'	=>	$prefix . $row['cat_name']
		));
		catList($row['cat_id'], $prefix . SPACES);
	}	
}	
?>
