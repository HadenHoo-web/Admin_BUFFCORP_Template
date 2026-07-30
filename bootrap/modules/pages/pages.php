<?php
	global $languageid, $template;
	$template->assign_vars(array(
		'ROOT'		 => $root_path,
		'funname'	 => 'pages/pages',
		'LANGUAGEID'	=>	$languageid	
	));
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	switch( $action )
	{	case 'list':	mosPageList(); break;
		case 'list_all':	mosPageListAll(); break;
		case 'info':	mosPageInfo(); break;
		case 'save':	mosPageSave(); break;		
		case 'delete':	mosPageDelete(); break;		
		case 'submit':	mosPageSubmit(); break;
		case 'rollback':mosRollBack(); break;	
		case 'search':	mosSearchImage();break;	
		default:
			mosInvalidURL();
			exit;
	}
	exit;
//--------------------------------------------------------------------------------------------------
	function mosPageListAll($status_id=0)
	{	global $template, $db, $languageid, $skin, $langpath;
		$sortby = "created_date desc";
		if($status_id==0)
			$status_id = mosGetParam( $_REQUEST, 'stat', 0 );	
		if($status_id==0)
		{	mosInvalidURL();
			exit;
		}
		$template->set_filenames_new(array('pages' => 'pages/pages_list_all.tpl'));
		$Condition = getRole(" AND ", "a.");
		$sql = "select a.* from tbl_pages a ,tbl_page_categories b  where a.cat_id=b.cat_id and b.language_id=$languageid and status_id = $status_id $Condition order by $sortby";
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
		$order = 0;
		while ( $row = $db->sql_fetchrow($result) )
		{	$order++;
			$template->assign_block_vars('list', array(
				'className'		=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'			=>  $order,		
				'page_id'		=> $row['page_id'],
				'alias'			=> $row['alias'],
				'title'			=> $row['title'],
				'send_by'		=> $row['send_by'],
				'priority'		=> $row['priority'],
				'source'		=> $row['source'],
				'image_alt'		=> $row['image_alt'],
				'slug'			=> $row['slug'],
				'title_seo'		=> $row['title_seo'],
				'meta_key'		=> $row['meta_key'],
				'meta_des'		=> $row['meta_des'],
				'meta_schema'	 => $row['meta_schema'],
				'view'			=> $row['view'],
				'num_comment'	=>	$row['num_comment'],
				'status'		  => $row['status_id'],
				'homeshow'		=> ($row['status_id'] == 1) ? 'checked' : '',
				'isadsgoogle'	 => ($row['isadsgoogle'] == 1) ? 'checked' : '',
				'ischange'		=> ($row['ischange'] == 1) ? 'checked' : '',
				'issource'		=> ($row['issource'] == 1) ? 'checked' : '',
				'isupdate'		=> ($row['isupdate'] == 1) ? 'checked' : '',
				'isshare'		 => ($row['isshare'] == 1) ? 'checked' : '',
				'isindex'		 => ($row['isindex'] == 1) ? 'checked' : '',
				'istoiuu'		 => ($row['istoiuu'] == 1) ? 'checked' : '',
				'noindex'		 => ($row['noindex'] == 1) ? 'checked' : '',
				'id_product'	  => $row['id_product'],
			));		
		}
		$template->assign_vars(array(
			'status_id'	=> $status_id, 
			
			
		));	
		$template->assign_var('MESSAGE', ($order == 0) ? EMPTY_LIST : "", 1);
		
		$template->pparse('pages');
	}	
//--------------------------------------------------------------------------------------------------	
	function mosPageList($cat_id = 0)
	{	global $template, $db, $languageid, $skin, $langpath, $str_cat_id;
		$sortby = "created_date desc";
    $tungay = mosGetParam( $_REQUEST, 'tungay', '' );
    $denngay = mosGetParam( $_REQUEST, 'denngay', '' );
   if( !$denngay ){
			date_default_timezone_set('Asia/Ho_Chi_Minh');
			$now=time();
			$denngay = date('Y-m-d',$now);
		}
		if( !$tungay ){
			$maxngay = date_create($denngay);
			date_sub($maxngay, date_interval_create_from_date_string("2 days"));
			$tungay = date_format($maxngay, 'Y-m-d');
		}
		$status_id = mosGetParam( $_REQUEST, 'stat', 0 );
		if ($cat_id == 0)
			$cat_id    = mosGetParam( $_REQUEST, 'cid',  0 );
		if ($cat_id == 0)	
		{	$sql = "select cat_id, cat_name from tbl_page_categories where parent_id = 0 and language_id = $languageid order by priority";
			if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
			if( $row = $db->sql_fetchrow($result) )
				$cat_id = $row['cat_id'];
			else
			{	mosInvalidURL();
				return;
			}		
		}			
		$template->set_filenames_new(array('pages' => 'pages/pages_list.tpl'));
		$catCondition = (($cat_id != -1) ? "cat_id = $cat_id" : "");
		$Condition = getRole(" AND ");
		if ($status_id != 0)
			$sql = "select * from tbl_pages where $catCondition " . (strlen($catCondition) > 0 ? " and " : ""). " status_id = $status_id $Condition order by $sortby";
		else
			$sql = "select * from tbl_pages " . (strlen($catCondition) > 0 ? " where $catCondition " : "") . " $Condition order by $sortby";
		$str_cat_id = mosGetCat($cat_id);
		$str_cat_id = substr($str_cat_id, 1);
   
   $first_date = strtotime($tungay);
   $second_date = strtotime($denngay);
   $datediff = abs($first_date - $second_date);
   $songay = floor($datediff / (60*60*24))+1;
   $listngay = "'a'";
   for ($i = 0; $i < $songay; $i++){
			$maxngay = date_create($denngay);
			date_sub($maxngay, date_interval_create_from_date_string("$i days"));
			$tamngay = date_format($maxngay, 'd-m-Y');
			//$sql .= ", (SELECT COUNT(*) FROM tbl_total_view WHERE ngaythang = '$tamngay' AND product_id IN (SELECT product_id FROM tbl_product WHERE product_category = tbl_category.`id`)) AS '$tamngay'";
     $listngay .= ",'$tamngay'";
		}
   $cond = (strtolower($_SESSION['membername'])=="administrator" || strtolower($_SESSION['loginname'])=="trieuanh.buffseo" || strtolower($_SESSION['loginname'])=="tramanh.buffseo@gmail.com"  || strtolower($_SESSION['loginname'])=="tuan.buffseo@gmail.com")?'':' and tbl_pages.member_id = "'.$_SESSION["login_id"].'"';
   $cond .= " and tbl_pages.ngay in ($listngay)";
		$sql = "select tbl_pages.*, SUBSTRING(tbl_pages.ngay, 7, 4) as y, SUBSTRING(tbl_pages.ngay, 4, 2) as m , SUBSTRING(tbl_pages.ngay, 1, 2) as d, tbl_website.website_name  from tbl_pages left join tbl_website on tbl_pages.website_id = tbl_website.website_id where cat_id in ( $cat_id ) $cond order by y DESC, m DESC, d DESC, tbl_website.priority, page_id DESC";

		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
		$order = 0;$sumword = 0;
		while ( $row = $db->sql_fetchrow($result) )
		{	$order++;$sumword += $row['countword'];
			$template->assign_block_vars('list', array(
				'className'		=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'			=>  $order,		
				'page_id'		=> $row['page_id'],
				'alias'			=> $row['alias'],
				'title'			=> $row['title'],
				'send_by'		=> $row['send_by'],
				'priority'		=> $row['priority'],
				'source'		=> $row['source'],
				'image_alt'		=> $row['image_alt'],
				'slug'			=> ($row['slug'])?"<a href='".$row['slug']."' target='_blank'>Xem Bài Viết</a>":"",
				'title_seo'		=> $row['title_seo'],
				'meta_key'		=> $row['meta_key'],
				'meta_des'		=> $row['meta_des'],
				'meta_schema'	 => $row['meta_schema'],
				'view'			=> $row['view'],
				'num_comment'	=> $row['num_comment'],
				'group_name'		=> $row['group_name'],
				'status'		=> $row['status_id'],
				'created_date'	=> mosOurFormatDate($row['created_date'], "DMY"),
				'created_by'	=> $row['created_by'],
				'homeshow'		=> ($row['homeshow'] == 0) ? 'none;' : '',
				'home_right'	=> ($row['home_right'] == 0) ? 'none;' : '',
				'isadsgoogle'	 => ($row['isadsgoogle'] == 0) ? 'none;' : '',
				'ischange'		=> ($row['ischange'] == 0) ? 'none;' : '',
				'issource'		=> ($row['issource'] == 0) ? 'none;' : '',
				'isupdate'		=> ($row['isupdate'] == 0) ? 'none;' : '',
				'isshare'		 => ($row['isshare'] == 0) ? 'none;' : '',
				'isindex'		 => ($row['isindex'] == 0) ? 'none;' : '',
				'istoiuu'		 => ($row['istoiuu'] == 0) ? 'none;' : '',
				'noindex'		 => ($row['noindex'] == 0) ? 'none;' : '',
				'id_product'	  => $row['id_product'],
        'website_name'  => $row['website_name'],
        'ngay'          => $row['ngay'],
        'countword'   =>  (strtolower($_SESSION['membername'])=="administrator")?'<font color="red"><b>'.number_format($row['countword'], 0, ',', '.').'</b></font>':'<font color="red"><b>'.number_format($row['countword'], 0, ',', '.').'</b></font>',
			));		
		}
		$template->assign_vars(array(
			'cat_id'	=> $cat_id,
			'status'	=> $status_id, 
			'cat_name'	=> $cat_name,
			'page_list'	=> $page_list,
			'rec_count'	=> $rec_count,
      'tungay'    => $tungay,
      'denngay'   => $denngay,
      'sumword'   => number_format($sumword, 0, ',', '.'),
			'cat_chain'	=> ($cat_id != -1) ? mosCatChain($cat_id) : ""
		));	
		$template->assign_var('MESSAGE', ($order == 0) ? EMPTY_LIST : "", 1);
		catList(0);		
		$template->pparse('pages');
	}	
//--------------------------------------------------------------------------------------------------	
	function mosCatChain($cat_id)
	{	global $db, $template;
		$sql = "select parent_id, cat_name, cat_id from tbl_page_categories where cat_id = $cat_id";
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
//--------------------------------------------------------------------------------------------------
	function catList($parent_id, $prefix = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")
	{	global $db, $languageid, $template;
		$sql = "SELECT cat_id, cat_name FROM tbl_page_categories  WHERE (parent_id = $parent_id) and (language_id = $languageid) and visible = 1 ORDER BY priority" ;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{	$template->assign_block_vars('catlist', array(
				'cat_id'	=>	$row['cat_id'],
				'cat_name'	=>	$prefix . $row['cat_name']
			));
			catList($row['cat_id'], $prefix. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
		}	
	}
//--------------------------------------------------------------------------------------------------
	function groupList($parent_id, $prefix = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")
	{	global $db, $languageid, $template;
		$sql = "SELECT group_id, group_name FROM tbl_group  WHERE (parent_id = $parent_id) and (language_id = $languageid) ORDER BY priority" ;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{	$template->assign_block_vars('grouplist', array(
				'group_id'	=>	$row['group_id'],
				'group_name'=>	$prefix . $row['group_name']
			));
			groupList($row['group_id'], $prefix. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
		}	
	}	
//--------------------------------------------------------------------------------------------------
	function mosPageSubmit()
	{	global $db, $template;
		$page_id	= mosGetParam( $_REQUEST, 'id', '0' );	
		$status_id	= mosGetParam( $_REQUEST, 'stat', '0' );
//		$cid		= mosGetParam( $_REQUEST, 'cid', '0' );
		$sql = "select status_id, cat_id from tbl_pages where page_id = $page_id";
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		if( $row = $db->sql_fetchrow($result) )
		{	$status_id = $row['status_id'];
			$cat_id    = $row['cat_id'];
		} else
		{	mosInvalidURL();
			exit;
		}

		$sql = "select a.* from tbl_workflow a, tbl_page_categories b where (cat_id = $cat_id) and (a.workflow_id = b.workflow_id)";
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		if( $row = $db->sql_fetchrow($result) )
		{	$must_edit 		= $row['must_edit'];
			$must_approve 	= $row['must_approve'];
			$must_publish	= $row['must_publish'];
		} else
		{	$must_edit = 1;	$must_approve = 1;	$must_publish = 1;
		}
		switch($status_id)
		{	case 1:	if ($must_edit    == 1) {$next_status = 2;	break;};
			case 2:	if ($must_approve == 1) {$next_status = 3;	break;};
			case 3:	if ($must_publish == 1) {$next_status = 4;	break;};
			case 4:	$next_status = 5; break;
			default: break;
		}
		if (isset($next_status))
		{	$sql = "update tbl_pages set status_id = $next_status where page_id = $page_id";
			if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
			$template->assign_vars(array('MESSAGE'	=>	SUBMIT_SUCCESS));
		} else
			$template->assign_vars(array('MESSAGE'	=>	SUBMIT_FAILED));
	//if($status_id==0)
		mosPageList($cat_id);
	//else
		//mosPageListAll($status_id);
	}
//--------------------------------------------------------------------------------------------------	
	function mosRollBack()
	{	global $db, $template;
		$page_id	= mosGetParam( $_REQUEST, 'id', '0' );	
		$status_id	=  mosGetParam( $_REQUEST, 'stat', '0' );	
		$sql = "select status_id, cat_id from tbl_pages where page_id = $page_id";
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		if( $row = $db->sql_fetchrow($result) )
		{	$status_id = $row['status_id'];
			$cat_id    = $row['cat_id'];
		} else
		{	mosInvalidURL();
			exit;
		}
		$sql = "select a.* from tbl_workflow a, tbl_page_categories b where cat_id = $cat_id and a.workflow_id = b.workflow_id";
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		if( $row = $db->sql_fetchrow($result) )
		{	$must_edit 		= $row['must_edit'];
			$must_approve 	= $row['must_approve'];
			$must_publish	= $row['must_publish'];
		} else
		{	$must_edit = 1;	$must_approve = 1;	$must_publish = 1;
		}
		switch($status_id)
		{	case 5:	if ($must_publish == 1) {$next_status = 4;	break;};
			case 4:	if ($must_approve == 1) {$next_status = 3;	break;};
			case 3:	if ($must_edit    == 1) {$next_status = 2;	break;};
			case 2:	$next_status = 1; break;
			default: break;
		}
		if (isset($next_status))
		{	$sql = "update tbl_pages set status_id = $next_status where page_id = $page_id";
			if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
			$template->assign_vars(array('MESSAGE'	=>	ROLLBACK_SUCCESS));
		} else
			$template->assign_vars(array('MESSAGE'	=>	ROLLBACK_FAILED));
	//if($status_id==0)
		mosPageList($cat_id);
	//else
		//mosPageListAll($status_id);
	}
//--------------------------------------------------------------------------------------------------
	function mosPageInfo()
	{	global $db, $root_path, $skin, $languageid, $template, $cat_id, $page_id, $theme;
		$page_id	= mosGetParam( $_REQUEST, 'id', '0' );
		$status_id = mosGetParam( $_REQUEST, 'stat', '0' );	
   
   $cond = (strtolower($_SESSION['membername'])=="administrator")?'':' and active = 1';
    $sql = "select * from tbl_website where 1 $cond order by website_name";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_block_vars('website_list', array(
				'website_id'	  =>	$row['website_id'],
				'website_name'	=>	$row['website_name'],
			));
		}
   
		if ($page_id != 0)
		{	$sql = "SELECT * FROM tbl_pages WHERE page_id = $page_id";
		
			if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
			
			if( $row = $db->sql_fetchrow($result) )
			{	$cat_id 	= $row['cat_id'];
				$group_id 	= $row['group_id'];
				$status = $row['status_id'];
				$imgDir			= $root_path."images/page/";
				
				$template->assign_vars(array(
					'page_id'		=>  $page_id,
					'cat_id'	 	=>	$cat_id,
					'group_id'	 	=>	$group_id,
					'title'	 		=>	$row['title'],
					'send_by' 		=>	$row['send_by'],
					'priority' 		=>	$row['priority'],
					'source' 		=>	$row['source'],
					'image_alt'		=>	$row['image_alt'],
					'slug'			=>	$row['slug'],
					'title_seo'		=>	$row['title_seo'],
					'meta_key'		=>	$row['meta_key'],
					'meta_des'		=>	$row['meta_des'],
					'meta_schema'	 =>	$row['meta_schema'],
					'view'			=>	$row['view'],
					'num_comment'	=>	$row['num_comment'],
					'alias' 		   =>	$row['alias'],
					'intro_text'	  =>	$row['intro_text'],
					'main_text'		=>	$row['main_text'],
					'quotation'		=>	$row['quotation'],				
					'template'		=>  $row['template'],
					'homeshow'		=>	($row['homeshow'] == 1) ? 'checked' : '',
					'home_right'	=>	($row['home_right'] == 1) ? 'checked' : '',
					'isadsgoogle'	 =>	($row['isadsgoogle'] == 1) ? 'checked' : '',
					'ischange'		=>	($row['ischange'] == 1) ? 'checked' : '',
					'issource'		=>	($row['issource'] == 1) ? 'checked' : '',
					'isupdate'		=>	($row['isupdate'] == 1) ? 'checked' : '',
					'isshare'		 =>	($row['isshare'] == 1) ? 'checked' : '',
					'isindex'		 =>	($row['isindex'] == 1) ? 'checked' : '',
					'istoiuu'		 =>	($row['istoiuu'] == 1) ? 'checked' : '',
					'noindex'		 =>	($row['noindex'] == 1) ? 'checked' : '',
					'image' 		=>	$row['image'],
					'imgPath'		=>	($row['image'] == '') ? "" : $imgDir.$row['image'],
					'image_share'	=>	$row['image_share'],
					'imgSharePath'	=>	($row['image_share'] == '') ? "" : $imgDir.$row['image_share'],
					'id_product'	  =>	$row['id_product'],
          'website_id'  =>  $row['website_id'],
          'ngay'        =>  $row['ngay'],
          'created_date'   => $row['created_date'],
					'created_by'	 => $row['created_by'],
					'last_modified'  => $row['last_modified'],
					'modified_by'	=> $row['modified_by'],
				));
				
			} // if ($row = ...
		} else // if ($page_id != 0)
			$template->assign_vars(array(
				'page_id'	    => '0',
				'cat_id'	    => mosGetParam( $_REQUEST, 'cid', '0' ),
				'template'	  => 'default.tpl',
        'website_id'  =>  0,
			));	
				
		$template->assign_vars(array(
			'template_list'	=> 	mosTemplateList('pages', false),
			'status_id'		=>	($status_id!=0)?$status_id:'', 
		));	
			
		$pageTemplate = (($status <= 2) ? "page_info.html" : "page_view.tpl");
		$template->set_filenames_new(array(
			'pages' => 'pages/' . $pageTemplate)
		);
		
		catList(0);	
			
		$template->pparse('pages');	
	}
//--------------------------------------------------------------------------------------------------
function mosPageSave()
{	
	global $db, $root_path, $skin, $languageid, $template, $theme, $website;	
	$status_id = mosGetParam( $_REQUEST, 'stat', '0' );
	$cat_id   		= mosGetParam( $_REQUEST, 'cid', '0');
	$group_id  		= mosGetParam( $_REQUEST, 'groupid', '0');
	$page_id  		= mosGetParam( $_REQUEST, 'id', '0');
	$title   		= mosGetParam( $_REQUEST, 'title', '');
	$send_by   		= mosGetParam( $_REQUEST, 'send_by', '');
	$priority  		= mosGetParam( $_REQUEST, 'priority', '');
	$source   		= mosGetParam( $_REQUEST, 'source', $website);
	$image_alt 		= mosGetParam( $_REQUEST, 'image_alt', '');
	$slug 			= mosGetParam( $_REQUEST, 'slug', '');
	$title_seo 		= mosGetParam( $_REQUEST, 'title_seo', '');
	$meta_key 		= mosGetParam( $_REQUEST, 'meta_key', '');
	$meta_des 		= mosGetParam( $_REQUEST, 'meta_des', '');
	$meta_schema 	 = mosGetParam( $_REQUEST, 'meta_schema', '');
	$view 	 		= mosGetParam( $_REQUEST, 'view', '');
	$num_comment 	 = mosGetParam( $_REQUEST, 'num_comment', '');
	$alias   		= mosGetParam( $_REQUEST, 'alias', 'none');	
	$homeshow  		= mosGetParam( $_REQUEST, 'homeshow', '0');	
	$home_right 	= mosGetParam( $_REQUEST, 'home_right', '0');	
	$isadsgoogle  	 = mosGetParam( $_REQUEST, 'isadsgoogle', '0');
	$ischange  		= mosGetParam( $_REQUEST, 'ischange', '0');
	$issource  		= mosGetParam( $_REQUEST, 'issource', '0');
	$isupdate  		= mosGetParam( $_REQUEST, 'isupdate', '0');
	$isshare  		 = mosGetParam( $_REQUEST, 'isshare', '0');
	$isindex  		 = mosGetParam( $_REQUEST, 'isindex', '0');
	$istoiuu  		 = mosGetParam( $_REQUEST, 'istoiuu', '0');
	$noindex  		 = mosGetParam( $_REQUEST, 'noindex', '0');
	$intro_text		= mosGetParam( $_REQUEST, 'intro_text', '', 0x0003);
	$main_text 		= mosGetParam( $_REQUEST, 'main_text' , '', 0x0003);
	$quotation 		= mosGetParam( $_REQUEST, 'quotation' , '', 0x0003);
	$template_name 	= mosGetParam( $_REQUEST, 'template', 'default.tpl');
	$id_product	   = mosGetParam( $_REQUEST, 'id_product', '');
  $website_id	   = mosGetParam( $_REQUEST, 'website_id', '');
  $ngay          = mosGetParam( $_REQUEST, 'ngay', '');
  $countword = str_word_count(strip_tags(convert($main_text)));
  
	$kt=0;
	$sql = "select cat_name from tbl_page_categories where cat_id = $cat_id";
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		if( $row = $db->sql_fetchrow($result) )
			$cat_name = $row['cat_name'];
	$num_comment = round($view/1000);
	$num_comment = ($num_comment>1)?$num_comment:1;
	if ($page_id == '0'){	
		$sql = "insert into tbl_pages (cat_id, title, slug, created_date, last_modified, created_by, modified_by,   main_text, member_id, alias, website_id, ngay, countword) values ($cat_id, '$title', '$slug', now(), now(), '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "',  '$main_text', '".$_SESSION["login_id"]."', '$alias', $website_id, '$ngay', '$countword')";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	}else{	
		$meta_schema = creMetaSchemaPage($meta_schema, $title_seo, $img, $cat_name, $meta_des, $slug, $num_comment);
		$sql = "select * from tbl_page_content where page_id = $page_id";
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		if( $row = $db->sql_fetchrow($result) )
		{	$old_version	=	$row['version'];
			if ($row['modified_by'] != $_SESSION['membername'])
			{	
				$sql = "update tbl_page_content set modified_date = now(), modified_by = '".$_SESSION['membername']."', version = ".($old_version+1).", intro_text = '$intro_text', main_text = '$main_text' where page_id = $page_id";
				if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
				$sql = "update tbl_page_content set quotation = '$quotation' where page_id = $page_id"; //tach muc luc tranh hu database
				if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
			} else
			{	$sql = "update tbl_page_content set modified_date = now(), intro_text = '$intro_text', main_text = '$main_text' where page_id = $page_id";
				if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);	
				$sql = "update tbl_page_content set quotation = '$quotation' where page_id = $page_id";
				if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);		
			}			
		}			
		$sql = "update tbl_pages set cat_id = $cat_id, group_id = $group_id, title = '$title', send_by = '$send_by', priority = '$priority', source = '$source', image_alt = '$image_alt', slug = '$slug', title_seo = '$title_seo', meta_key = '$meta_key', meta_des = '$meta_des', meta_schema = '$meta_schema', view = '$view', num_comment = '$num_comment', image = '$img', image_share = '$img_share', alias = '$alias', homeshow = $homeshow, home_right = $home_right, isadsgoogle = $isadsgoogle, ischange = $ischange, issource = $issource, isupdate = $isupdate, isshare = $isshare, isindex = $isindex, istoiuu = '$istoiuu', noindex = '$noindex', template = '$template_name', intro_text = '$intro_text', main_text = '$main_text', last_modified = now(), modified_by = '".$_SESSION['membername']."', id_product = '$id_product', website_id = '$website_id', ngay = '$ngay', countword = '$countword' where page_id = $page_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$sql = "update tbl_pages set quotation = '$quotation' where page_id = $page_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$arrField = array("image"); 
		checkDeleteOldFile($img, $old_img, $img_remove, $root_path . "images/page/" , "tbl_pages", $arrField, "page_id", $page_id);
		$arrField = array("image_share"); 
		checkDeleteOldFile($img_share, $old_img_share, $img_remove_share, $root_path . "images/page/" , "tbl_pages", $arrField, "page_id", $page_id);
	}
	if($status_id==0)
		mosPageList($cat_id);
	else
		mosPageListAll($status_id);
	
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
		//Tạo schema
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
	"bestRating"  : "5",
    "ratingValue" : "'.$ratingValue.'",
    "ratingCount" : "'.$ratingCount.'"
  },
  "name": "99 lời chúc mừng sinh nhật chị, em gái ngọt ngào và đầy ý nghĩa"
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
//--------------------------------------------------------------------------------------------------

function mosPageDelete()
	{	global $db, $parent_id, $template, $root_path;
		$page_id = mosGetParam( $_REQUEST, 'page_id', '0' );
		$status_id = mosGetParam( $_REQUEST, 'stat', '0' );
		if( $page_id == '0')
		{	mosInvalidURL();
			return;
		}
		$sql = "select cat_id,status_id,created_by, image, image_share from tbl_pages where page_id = '$page_id'";	
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		if( $row = $db->sql_fetchrow($result) )
		{	$cat_id = $row['cat_id'];
			$status= $row['status_id'];
			$created_by = $row['created_by'];
			$old_img = $row['image'];
			$old_img_share = $row['image_share'];
		}
	
		if(strtolower($_SESSION['membername'])=="administrator") 
		{	deleteByID("tbl_pages", "page_id", $page_id);
			deleteByID("tbl_page_content", "page_id", $page_id);
			$arrField = array("image"); 
			checkDeleteOldFile("", $old_img, 1, $root_path . "images/page/" , "tbl_pages", $arrField, "page_id", $page_id);
			$arrField = array("image_share"); 
			checkDeleteOldFile("", $old_img_share, 1, $root_path . "images/page/" , "tbl_pages", $arrField, "page_id", $page_id);
			if($status_id==0)
				mosPageList($cat_id);
			else
				mosPageListAll($status_id);
		}
		else
		//kiem tra status
		if ($status==1)
		//Neu dung la 1
		{	//Kiem tra co phai la tac gia hay ko
			
			if ($created_by != $_SESSION['membername'])
			//neu dung
			{	deleteByID("tbl_pages", "page_id", $page_id);
				if($status_id==0)
					mosPageList($cat_id);
				else
					mosPageListAll($status_id);
			}
			else
			//Neu sai
			{
				$template->assign_vars(array('MESSAGE' => DELETE_ERROR));
				if($status_id==0)
					mosPageList($cat_id);
				else
					mosPageListAll($status_id);			
				exit;	
			}
		}
		else
		//Neu # 1
		{	
			if ($status >= 4)
			{	$template->assign_vars(array('MESSAGE' => DELETE_ERROR));
				if($status_id==0)
					mosPageList($cat_id);
				else
					mosPageListAll($status_id);			
				exit;	
			}
			else
			{
				$can_delete  = $_SESSION['can_delete'];
				$role_id	=	 $_SESSION['role_id'];
				if(($can_delete==1) && ($status==$role_id))
				{
					deleteByID("tbl_pages", "page_id", $page_id);
					if($status_id==0)
					mosPageList($cat_id);
				else
					mosPageListAll($status_id);
					exit;
				}
				else
				{
					$template->assign_vars(array('MESSAGE' => DELETE_ERROR));
					if($status_id==0)
					mosPageList($cat_id);
				else
					mosPageListAll($status_id);			
					exit;	
				}
				
			}
		}		
	}
//--------------------------------------------------------------------------------------------------
	function mosGetCat($cat_id)
	{	global $db, $languageid, $template, $str_cat_id;
		$str_cat_id .= ','.$cat_id;
		$sql = "select cat_id from tbl_page_categories where parent_id = $cat_id and visible = 1" ;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{	$tam = $row['cat_id'];
			mosGetCat($tam);
		}
		
		return $str_cat_id;
	}
//--------------------------------------------------------------------------------------------------	
	function mosSearchImage()
	{	global $template, $db, $languageid, $skin, $langpath, $str_cat_id;
		$sortby = "created_date desc";
		$image = mosGetParam( $_REQUEST, 'image', '' );
				
		$template->set_filenames_new(array('pages' => 'pages/pages_search_list.tpl'));
		
		$sql = "select * from tbl_pages where image = '$image' order by $sortby";
		
		if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
		$order = 0;
		while ( $row = $db->sql_fetchrow($result) )
		{	$order++;
			$template->assign_block_vars('list', array(
				'className'		=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'			=>  $order,		
				'page_id'		=> $row['page_id'],
				'alias'			=> $row['alias'],
				'title'			=> $row['title'],
				'send_by'		=> $row['send_by'],
				'priority'		=> $row['priority'],
				'source'		=> $row['source'],
				'image_alt'		=> $row['image_alt'],
				'slug'			=> $row['slug'],
				'title_seo'		=> $row['title_seo'],
				'meta_key'		=> $row['meta_key'],
				'meta_des'		=> $row['meta_des'],
				'meta_schema'	 => $row['meta_schema'],
				'view'			=> $row['view'],
				'num_comment'		=> $row['num_comment'],
				'status'		=> $row['status_id'],
				'created_date'	=> mosOurFormatDate($row['created_date'], "DMY"),
				'created_by'	=> $row['created_by'],
				'image'			=> $row['image'],
				'id_product'	  => $row['id_product'],
			));		
		}

		$template->assign_var('MESSAGE', ($order == 0) ? EMPTY_LIST : "", 1);	
		$template->pparse('pages');
	}	
//--------------------------------------------------------------------------------------------------	
	function mosMucLuc($str)
	{	
		global $template, $db, $languageid, $skin, $langpath, $str_cat_id;
		$mucluc = "";
		$count = 1;
		if ($str){
			$arrHeading = get_all_headings( $str );
			if ($arrHeading){
				foreach ($arrHeading as $tt) {
					if ($tt['tag'] == "h2"){
						$mucluc .= "<a href=#".$tt['neo'].">".$tt['title']."</a><br>";
					}elseif ($tt['tag'] == "h3"){
						$mucluc .= "&nbsp;&nbsp;&nbsp;&nbsp;<a href=#".$tt['neo'].">".$tt['title']."</a><br>";
					}elseif ($tt['tag'] == "h4"){
						$mucluc .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=#".$tt['neo'].">".$tt['title']."</a><br>";	
					}
					$count++;
				}
			}else exit;	
		}else {$mucluc = ""; exit;}
		return $mucluc;
		exit;
	}
	
function get_all_headings( $content ) {
    preg_match_all( '/\<(h[1-6]) id="(.*)"\>
	(.*)<\/h[1-6]>/i', $content, $matches );

    $r = array();
    if( !empty( $matches[1] ) && !empty( $matches[2] ) ){
        $tags = $matches[1];
		$neo = $matches[2];
        $titles = $matches[3];
        foreach ($tags as $i => $tag) {
            $r[] = array( 'tag' => $tag, 'neo' => $neo[ $i ], 'title' => $titles[ $i ] );
        }
    }

    return $r;
}	
?>

