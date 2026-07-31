<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'seo/di_forum',
		'LANGUAGEID'=> $languageid,
	));		

	switch( $action )
	{	
		case 'list'	:	mosList(0); break;
		case 'info'	:	mosInfo(); break;
		case 'up'	:  	mosMove('up'); break;
		case 'down' :  	mosMove('down'); break;
		case 'save'	:	mosSave(); break;
		case 'copy'	:	mosCopy(); break;
		case 'docopy'	:	mosDoCopy(); break;
		case 'delete':	mosDelete(); break;
		case 'infolink'	:	mosInfoLink(); break;
		case 'savelink'	:	mosSaveLink(); break;
		case 'listlink'	:	mosListLink(); break;
		case 'update'	  :	mosUpdate(); break;
		case 'export'	  :	mosExport(); break;
		default:
			mosInvalidURL();
			exit;
	}
function mosList($id)
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$groupkey_id 		 = (int)mosGetParam( $_REQUEST, 'groupkey_id1', 0 );
		$isbl				= (int)mosGetParam( $_REQUEST, 'isbl', 0 );
		$isindex			 = (int)mosGetParam( $_REQUEST, 'isindex1', 2 );
		$ishu				= (int)mosGetParam( $_REQUEST, 'ishu1', 0 );
		$bookmark_id 		 = (int)mosGetParam( $_REQUEST, 'bookmark_id1', 0 );
		$from			    = max(0, (int)mosGetParam( $_REQUEST, 'from_date', 0 ));
		$to			      = max(1, min(500, (int)mosGetParam( $_REQUEST, 'to_date', 100 )));
		
		selectList(0);
		
		$cond = '';
		$cond .= ($groupkey_id != 0)?' and tbl_groupkeys.groupkey_id = '.$groupkey_id:'';
		$cond .= ($bookmark_id != 0)?' and tbl_bookmarks.bookmark_id = '.$bookmark_id:'';
		$cond .= ($isindex != 2)?' and tbl_di_forums.isindex = '.$isindex:'';
		$cond .= ($ishu != 0)?' and tbl_di_forums.ishu = '.$ishu:'';
		
		$sql = "SELECT tbl_di_forums.*, SUBSTRING(tbl_di_forums.ngay, 7, 4) as y, SUBSTRING(tbl_di_forums.ngay, 4, 2) as m , SUBSTRING(tbl_di_forums.ngay, 1, 2) as d, tbl_forums.forum_name, tbl_forums.follow, tbl_forums.da_point, tbl_forums.tf_point, tbl_forums.isno, tbl_groupkeys.groupkey_name, tbl_groupkeys.url, tbl_bookmarks.bookmark_name, tbl_bookmarks.link, COALESCE(child_counts.child_count, 0) AS child_count FROM (((tbl_di_forums inner join tbl_groupkeys on tbl_di_forums.groupkey_id = tbl_groupkeys.groupkey_id) left join tbl_forums on tbl_di_forums.forum_id = tbl_forums.forum_id) left join tbl_bookmarks on tbl_di_forums.bookmark_id = tbl_bookmarks.bookmark_id) left join (SELECT link2_id, COUNT(*) AS child_count FROM tbl_di_forums WHERE link2_id IS NOT NULL AND link2_id <> 0 GROUP BY link2_id) child_counts on child_counts.link2_id = tbl_di_forums.di_forum_id where 1 $cond order by y DESC, m DESC, d DESC, di_forum_name, di_forum_id DESC limit $from,$to" ; //tam thoi list noindex con bi sai
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		$num_row = $db->sql_numrows($result);
		$order = 0;
		$index = 0;
		$bihu  = 0;
		$tam = "";
		$old_di_forum_name = "";
		while( $row = $db->sql_fetchrow($result) )
		{
			$count = (int)$row['child_count'];
			$tam = ($tam == $row['di_forum_name']) ? '' : (($tam != $row['di_forum_name']) ? $row['di_forum_name'] : '');
			
			
			if(($isbl == 0 or $count == 0) and $old_di_forum_name != $row['di_forum_name']){	
			$order = ($tam == '')?$order:$order + 1;
			$index += $row['isindex'];
			$bihu  += $row['ishu'];	
			
			switch( $row['isno'] )
			{	
				case '9'	:	$isno = 'Lime'; break;
				case '8'	:	$isno = 'White'; break;
				default	 :	$isno = 'Silver';
					
			}
			
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  ($tam == '')?'':$order,
				'di_forum_id'	=>	$row['di_forum_id'],
				'di_forum_name'=>	$tam,
				'groupkey_name'	=>	$row['groupkey_name'],
				'url'			=>	$row['url'],
				'bookmark_name'	=>	$row['bookmark_name'],
				'link'			=>	$row['link'],
				'url'			=>	$row['url'],
				'forum_name'	=>	$row['forum_name'],
				'slug'			=>	$row['slug'],
				'meta_key'		=>	$row['meta_key'],
				'meta_des'		=>	$row['meta_des'],
				'title_seo'		=>	$row['title_seo'],
				'fanpage'		=>	$row['fanpage'],
				'youtube'		=>	$row['youtube'],
				'network1'		=>	$row['network1'],
				'network2'		=>	$row['network2'],
				'anchor_text'	=>	$row['anchor_text'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">�</font></b></font>' : '',
				'isindex' 	=>	($row['isindex'] == 1) ? '<b><font size="3" color="#008000">Ok</font></b>' : '',
				'ishu' 	=>	($row['ishu'] == 1) ? '<b><font size="3" color="#800080">Hu</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',
				'date'			=>	substr($row['created_date'],0,10),
        'ngay'			=>	$row['ngay'],
				'count'			=>	($count > 0)?"($count)":'',	
				'follow'		=>	($row['follow'] == 1)?'#FF0000':'#0066FF',
				'da_point'		=>	$row['da_point'],
				'tf_point'		=>	$row['tf_point'],
				'isno'			=>	$isno,
				'nv'			=>	$row['created_by'],
			));
			}else{
				$old_di_forum_name = $row['di_forum_name'];
			}
			
			
			$tam = $row['di_forum_name'];
			$count = '';
		}
		
		$template->assign_vars(array(
			'groupkey_id1'   =>	$groupkey_id,
			'bookmark_id1'   =>	$bookmark_id,
			'isbl'		   =>	($isbl) ? 'checked' : '',
			'isindex1'		=>	$isindex,
			'ishu1'		   =>	($ishu) ? 'checked' : '',
			'num_row'		=>	$order,
			'index'		  =>	$index,
			'bihu'		   =>	$bihu,
			'from_date'	  =>	$from,
			'to_date'		=>	$to,
		));
		
		$template->set_filenames_new(array(
			'share' => 'seo/di_forum_list.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosListLink()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$di_forum_id 	 = mosGetParam( $_REQUEST, 'link_id', 0 );
		$isindex			 = mosGetParam( $_REQUEST, 'isindex1', '2' );
		$ishu				= mosGetParam( $_REQUEST, 'ishu1', '0' );
		$from			    = mosGetParam( $_REQUEST, 'from_date', '0');
		$to			      = mosGetParam( $_REQUEST, 'to_date', '500');
		if ( $di_forum_id == 0 ){
			$cond = '';
			$cond .= ($ishu != 0)?' and tbl_di_forums.ishu = '.$ishu:'';
			$cond .= ($isindex != 2)?' and tbl_di_forums.isindex = '.$isindex:'';
			$sql = "SELECT tbl_di_forums.*, tbl_forums.forum_name, tbl_bookmarks.bookmark_name, tbl_bookmarks.link FROM (tbl_di_forums inner join tbl_forums on tbl_di_forums.forum_id = tbl_forums.forum_id) left join tbl_bookmarks on tbl_di_forums.bookmark_id = tbl_bookmarks.bookmark_id where (link2_id is not null or link2_id != 0) $cond ORDER BY di_forum_id DESC limit $from,$to" ;
		}else $sql = "SELECT tbl_di_forums.*, tbl_forums.forum_name, tbl_bookmarks.bookmark_name, tbl_bookmarks.link FROM (tbl_di_forums inner join tbl_forums on tbl_di_forums.forum_id = tbl_forums.forum_id) left join tbl_bookmarks on tbl_di_forums.bookmark_id = tbl_bookmarks.bookmark_id where link2_id = $di_forum_id ORDER BY di_forum_id DESC";
		
		
		
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		$num_row = $db->sql_numrows($result);
		$order = 0;
		$tam = "";
		while( $row = $db->sql_fetchrow($result) )
		{	
			$sql1 = "select di_forum_name from tbl_di_forums where di_forum_id = ".$row['link2_id'];
			if ( !($result1 = $db->sql_query($sql1)))	message_die(SERVER_BUSY);
			if ( $row1 = $db->sql_fetchrow($result1) ) $link2_name = $row1['di_forum_name'];
				$tam = ($tam == $row['di_forum_name']) ? '' : (($tam != $row['di_forum_name']) ? $row['di_forum_name'] : '');
			$order = ($tam == '')?$order:$order + 1;		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  ($tam == '')?'':$order,
				'link2_name'		=>	$link2_name,
				'di_forum_id'	=>	$row['di_forum_id'],
				'di_forum_name'=>	$tam,
				'groupkey_name'	=>	$row['groupkey_name'],
				'bookmark_name'	=>	$row['bookmark_name'],
				'link'			=>	$row['link'],
				'url'			=>	$row['url'],
				'forum_name'	=>	$row['forum_name'],
				'slug'			=>	$row['slug'],
				'meta_key'		=>	$row['meta_key'],
				'meta_des'		=>	$row['meta_des'],
				'title_seo'		=>	$row['title_seo'],
				'fanpage'		=>	$row['fanpage'],
				'youtube'		=>	$row['youtube'],
				'nv'			=>	$row['created_by'],
				'network1'		=>	$row['network1'],
				'network2'		=>	$row['network2'],
				'anchor_text'	=>	$row['anchor_text'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">�</font></b></font>' : '',
				'isindex' 	=>	($row['isindex'] == 1) ? '<b><font size="3" color="#008000">OK</font></b>' : '',
				'ishu' 	=>	($row['ishu'] == 1) ? '<b><font size="3" color="#008000">Hu</font></b>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',
				'date'			=>	substr($row['created_date'],0,10),	
				'count'			=>	($count > 0)?"($count)":'',	
			));	
			$tam = $row['di_forum_name'];
			$count = '';
		}
		$template->assign_vars(array(
			'isbl'		   =>	($isbl) ? 'checked' : '',
			'isindex1'		=>	$isindex,
			'ishu1'		   =>	($ishu) ? 'checked' : '',
			'num_row'		=>	$order,
			'index'		  =>	$index,
			'bihu'		   =>	$bihu,
			'from_date'	  =>	$from,
			'to_date'		=>	$to,
		));
		
		$template->set_filenames_new(array(
			'share' => 'seo/di_forum_listlink.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$di_forum_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		//$parent_id	 = mosGetParam( $_REQUEST, 'parent_id', 0 );	
		selectList(0);
		selectForumList();
		
		/*$sql = "select * from tbl_di_forums where di_forum_id = $di_forum_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_vars(array(
				'parent_id'		=>	$row['di_forum_id'],
				'parent_name'	=>	$row['di_forum_name'],
				'slug'			=>	$row['slug'],
				'meta_key'		=>	$row['meta_key'],
				'meta_des'		=>	$row['meta_des'],
				'title_seo'		=>	$row['title_seo'],
				'fanpage'		=>	$row['fanpage'],
				'youtube'		=>	$row['youtube'],
				'network1'		=>	$row['network1'],
				'network2'		=>	$row['network2'],
			));
		} */

		if ($di_forum_id != 0)
		{	$sql = "select * from tbl_di_forums where di_forum_id = $di_forum_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'di_forum_id'  =>	$di_forum_id,
					'di_forum_name'=>	$row['di_forum_name'],
					'groupkey_id'	 =>	$row['groupkey_id'],
					'bookmark_id'	 =>	$row['bookmark_id'],
					'forum_id'		 =>	$row['forum_id'],
					'slug'			   =>	$row['slug'],
					'meta_key'		 =>	$row['meta_key'],
					'meta_des'		 =>	$row['meta_des'],
					'title_seo'		 =>	$row['title_seo'],
					'fanpage'		   =>	$row['fanpage'],
					'youtube'		   =>	$row['youtube'],
					'network1'		 =>	$row['network1'],
					'network2'		 =>	$row['network2'],
					'anchor_text'	 =>	$row['anchor_text'],
					'parent_id'    =>	$row['parent_id'],
					'active'		   =>	($row['active'] == 1) ? 'checked' : '',
					'isindex'		   =>	($row['isindex'] == 1) ? 'checked' : '',
					'ishu'		     =>	($row['ishu'] == 1) ? 'checked' : '',
          'ngay'		     =>	$row['ngay'],
          'created_date'   => $row['created_date'],
					'created_by'	 => $row['created_by'],
					'last_modified'  => $row['last_modified'],
					'modified_by'	=> $row['modified_by'],
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'active'		=>	'checked' ,
				'allow'		=> 'hidden',
				'parent_id' =>	'0',
			));
		}
		
		$sql = "SELECT * FROM tbl_bookmarks  WHERE active = 1 ORDER BY priority DESC" ;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
			$template->assign_block_vars('bookmark_list', array(
				'bookmark_id'		=>	$row['bookmark_id'],
				'bookmark_name'	=>	$row['bookmark_name'],	
			));	
		}
		
		$template->set_filenames_new(array(
			'share' => 'seo/di_forum_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfoLink()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$di_forum_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		selectForumList();

		$sql = "SELECT max(di_forum_id) as di_forum_id, di_forum_name FROM tbl_di_forums GROUP BY di_forum_name ORDER BY di_forum_id DESC";
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
			$template->assign_block_vars('select_list', array(
				'di_forum_id'	=>	$row['di_forum_id'],
				'di_forum_name'	=>	$row['di_forum_name'],	
			));	
		}
		
		if ($di_forum_id != 0)
		{	$sql = "select * from tbl_di_forums where di_forum_id = $di_forum_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'di_forum_id'=>	$di_forum_id,
					'di_forum_name'=>	$row['di_forum_name'],
					'groupkey_id'	=>	$row['groupkey_id'],
					'bookmark_id'	=>	$row['bookmark_id'],
					'link2_id'		=>	$row['link2_id'],
					'forum_id'		=>	$row['forum_id'],
					'slug'			=>	$row['slug'],
					'meta_key'		=>	$row['meta_key'],
					'meta_des'		=>	$row['meta_des'],
					'title_seo'		=>	$row['title_seo'],
					'fanpage'		=>	$row['fanpage'],
					'youtube'		=>	$row['youtube'],
					'network1'		=>	$row['network1'],
					'network2'		=>	$row['network2'],
					'anchor_text'	=>	$row['anchor_text'],
					'parent_id' =>	$row['parent_id'],
					'active'		=>	($row['active'] == 1) ? 'checked' : '',
					'isindex'		=>	($row['isindex'] == 1) ? 'checked' : '',
					'ishu'		   =>	($row['ishu'] == 1) ? 'checked' : '',
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'active'		=>	'checked' ,
				'allow'		=> 'hidden',
				'parent_id' =>	'0',
			));
		}
		
		$sql = "SELECT * FROM tbl_bookmarks  WHERE active = 1 ORDER BY priority DESC" ;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
			$template->assign_block_vars('bookmark_list', array(
				'bookmark_id'		=>	$row['bookmark_id'],
				'bookmark_name'	=>	$row['bookmark_name'],	
			));	
		}

		$template->set_filenames_new(array(
			'share' => 'seo/di_forum_infolink.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$di_forum_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($di_forum_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $di_forum_id, $direction, "tbl_di_forums", "di_forum_id", "priority");
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$di_forum_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$groupkey_id 	= mosGetParam( $_REQUEST, 'groupkey_id', '0');
		$bookmark_id 	= mosGetParam( $_REQUEST, 'bookmark_id', '0');
		$forum_id		  = mosGetParam( $_REQUEST, 'forum_id', '0');
		$di_forum_name	= mosGetParam( $_REQUEST, 'di_forum_name', '');
		$slug			= mosGetParam( $_REQUEST, 'slug', '');
		$meta_key		= mosGetParam( $_REQUEST, 'meta_key', '');
		$meta_des		= mosGetParam( $_REQUEST, 'meta_des', '');
		$title_seo		= mosGetParam( $_REQUEST, 'title_seo', '');
		$fanpage		= mosGetParam( $_REQUEST, 'fanpage', '');
		$youtube		= mosGetParam( $_REQUEST, 'youtube', '');
		$network1		= mosGetParam( $_REQUEST, 'network1', '');
		$network2		= mosGetParam( $_REQUEST, 'network2', '');
		$anchor_text	= mosGetParam( $_REQUEST, 'anchor_text', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$isindex		= mosGetParam( $_REQUEST, 'isindex', 0);
		$ishu			= mosGetParam( $_REQUEST, 'ishu', 0);
    $ngay	          = mosGetParam( $_REQUEST, 'ngay', '');
  
    if ( $forum_id == 0){
      $domain = parse_url($di_forum_name);
      $sql = "select * from tbl_forums where forum_name = '".$domain['host']."'";
      if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) ){$forum_id = $row['forum_id'];}
    }
		
		if ($di_forum_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($di_forum_id == '0')
		{	
			if (checkDuplicate("tbl_di_forums", array('di_forum_name' => $di_forum_name), "di_forum_name",0,false,"language_id = '$languageid' and groupkey_id = $groupkey_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			//cap nhat thoi gian su dung forum cuoi cung
			$sql1 = "update tbl_forums set last_use = now() where forum_id = $forum_id";
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
			
			$priority = mosGetPriority("tbl_di_forums", "priority", "");
			$sql = "insert into tbl_di_forums (groupkey_id, bookmark_id, forum_id, di_forum_name, slug, meta_key, meta_des, title_seo, fanpage, youtube, network1, network2, anchor_text, active, isindex, ishu, priority, language_id, created_date, created_by, last_modified, modified_by, ngay) values ('$groupkey_id', '$bookmark_id', '$forum_id', '$di_forum_name', '$slug', '$meta_key', '$meta_des', '$title_seo', '$fanpage', '$youtube', '$network1', '$network2', '$anchor_text', $active, $isindex, $ishu, $priority, $languageid, now(), '" . $_SESSION['membername'] . "', now(), '" . $_SESSION['membername'] . "', '$ngay')";
		} else
			{
			//cap nhat thoi gian su dung forum cuoi cung
			$sql1 = "update tbl_forums set last_use = now() where forum_id = $forum_id";
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );	 
			$sql = "update tbl_di_forums set di_forum_name ='$di_forum_name', slug = '$slug', meta_key = '$meta_key', meta_des = '$meta_des', title_seo = '$title_seo', fanpage = '$fanpage', youtube = '$youtube', network1 = '$network1', network2 = '$network2', anchor_text = '$anchor_text',  active = $active, isindex = '$isindex', ishu = '$ishu', language_id=$languageid, groupkey_id = '$groupkey_id', bookmark_id = '$bookmark_id', forum_id = '$forum_id', last_modified = now() , modified_by= '" . $_SESSION['membername'] . "', ngay = '$ngay'  where di_forum_id = $di_forum_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $di_forum_id != 0 ) mosUpdate($di_forum_id);
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSaveLink()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$di_forum_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$link2_id 		= mosGetParam( $_REQUEST, 'link2_id', '0');
		$bookmark_id 	= mosGetParam( $_REQUEST, 'bookmark_id', '0');
		$forum_id		= mosGetParam( $_REQUEST, 'forum_id', '0');
		$di_forum_name	= mosGetParam( $_REQUEST, 'di_forum_name', '');
		$slug			= mosGetParam( $_REQUEST, 'slug', '');
		$meta_key		= mosGetParam( $_REQUEST, 'meta_key', '');
		$meta_des		= mosGetParam( $_REQUEST, 'meta_des', '');
		$title_seo		= mosGetParam( $_REQUEST, 'title_seo', '');
		$fanpage		= mosGetParam( $_REQUEST, 'fanpage', '');
		$youtube		= mosGetParam( $_REQUEST, 'youtube', '');
		$network1		= mosGetParam( $_REQUEST, 'network1', '');
		$network2		= mosGetParam( $_REQUEST, 'network2', '');
		$anchor_text	= mosGetParam( $_REQUEST, 'anchor_text', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$isindex		= mosGetParam( $_REQUEST, 'isindex', 0);
		$ishu			= mosGetParam( $_REQUEST, 'ishu', 0);
		
		if ($di_forum_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($di_forum_id == '0')
		{	
			if (checkDuplicate("tbl_di_forums", array('di_forum_name' => $di_forum_name), "di_forum_name",0,false,"language_id = '$languageid' and link2_id = $link2_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			//cap nhat thoi gian su dung forum cuoi cung
			$sql1 = "update tbl_forums set last_use = now() where forum_id = $forum_id";
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
			
			$priority = mosGetPriority("tbl_di_forums", "priority", "");
			$sql = "insert into tbl_di_forums (link2_id, bookmark_id, forum_id, di_forum_name, slug, meta_key, meta_des, title_seo, fanpage, youtube, network1, network2, anchor_text, active, isindex, ishu, priority, language_id, created_date, created_by, last_modified, modified_by) values ('$link2_id', '$bookmark_id', '$forum_id', '$di_forum_name', '$slug', '$meta_key', '$meta_des', '$title_seo', '$fanpage', '$youtube', '$network1', '$network2', '$anchor_text', $active, $isindex, $ishu, $priority, $languageid, now(), '" . $_SESSION['membername'] . "', now(), '" . $_SESSION['membername'] . "')";
		} else
			{ 
			$sql = "update tbl_di_forums set di_forum_name ='$di_forum_name', slug = '$slug', meta_key = '$meta_key', meta_des = '$meta_des', title_seo = '$title_seo', fanpage = '$fanpage', youtube = '$youtube', network1 = '$network1', network2 = '$network2', anchor_text = '$anchor_text',  active = $active, isindex = '$isindex', ishu = '$ishu', language_id=$languageid, link2_id = '$link2_id', bookmark_id = '$bookmark_id', forum_id = '$forum_id', last_modified = now() , modified_by= '" . $_SESSION['membername'] . "'  where di_forum_id = $di_forum_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $di_forum_id != 0 ) mosUpdate($di_forum_id);
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosListLink();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$di_forum_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($di_forum_id == 0)
		{	mosInvalidURL();
			exit;
		}
		if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_di_forums", "di_forum_id", $di_forum_id);
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
			'di_forum_name' 	=>	mosGetParam( $_REQUEST, 'di_forum_name', ''),
			'slug'				=>	mosGetParam( $_REQUEST, 'slug', ''),
			'meta_key'			=>	mosGetParam( $_REQUEST, 'meta_key', ''),
			'meta_des'			=>	mosGetParam( $_REQUEST, 'meta_des', ''),
			'title_seo'			=>	mosGetParam( $_REQUEST, 'title_seo', ''),
			'fanpage'			=>	mosGetParam( $_REQUEST, 'fanpage', ''),
			'youtube'			=>	mosGetParam( $_REQUEST, 'youtube', ''),
			'network1'			=>	mosGetParam( $_REQUEST, 'network1', ''),
			'network2'			=>	mosGetParam( $_REQUEST, 'network2', ''),
			'anchor_text'		=>	mosGetParam( $_REQUEST, 'anchor_text', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'di_forum_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'group' => 'seo/di_forum_info.html')
		);
		
		$template->pparse('group');	
	}
//--------------------------------------------------------------------------------------------------
	function selectList($parent_id, $prefix = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")
	{	global $db, $languageid, $template;
		$sql = "SELECT * FROM tbl_groupkeys  WHERE (parent_id = $parent_id) ORDER BY priority" ;
		$i = 0;
		if ( $parent_id != 0 ) $old_parent_id = $parent_id;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{	
			$i 	= $i + 1;
			$order = $order + 1;					
			$template->assign_block_vars('select_list', array(
				'groupkey_id'	=>	$row['groupkey_id'],
				//'groupkey_name'	=>	$prefix . $row['groupkey_name'],
				'groupkey_name'	=>	$prefix.'<b><font size="2" color="#FF0000">'.$i.'</font></b>. '. $row['groupkey_name'],	
			));	
			selectList($row['groupkey_id'], $prefix. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
		}
	}
//--------------------------------------------------------------------------------------------------
	function selectForumList()
	{	global $db, $languageid, $template;
		$sql = "SELECT * FROM tbl_forums  WHERE active = 1 ORDER BY forum_id DESC" ;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
			$template->assign_block_vars('forum_list', array(
				'forum_id'		=>	$row['forum_id'],
				'forum_name'	=>	$row['forum_name'],	
			));	
		}
	}
//--------------------------------------------------------------------------------------------------
	function selectBookmarkList()
	{	global $db, $languageid, $template;
		$sql = "SELECT * FROM tbl_bookmarks  WHERE active = 1 ORDER BY bookmark_id DESC" ;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
			$template->assign_block_vars('bookmark_list', array(
				'bookmark_id'		=>	$row['bookmark_id'],
				'bookmark_name'	=>	$row['bookmark_name'],	
			));	
		}
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosCopy()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$di_forum_id 	= mosGetParam( $_REQUEST, 'id', '0');
		selectForumList();
		
		if ($di_forum_id == '' or $di_forum_id == '0')
		{	
			mosInvalidURL();
			exit;
		}else{
			$sql = "select di_forum_name from tbl_di_forums where di_forum_id = $di_forum_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
				$di_forum_name = $row['di_forum_name'];
			
			$sql = "select * from tbl_di_forums inner join tbl_groupkeys on tbl_di_forums.groupkey_id = tbl_groupkeys.groupkey_id where di_forum_name = '$di_forum_name'";
			if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
			while( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_block_vars('group_list', array(
					'groupkey_id'	=>	$row['groupkey_id'],
					'groupkey_name'	=>	$row['groupkey_name'],
					'anchor_text'	=>	$row['anchor_text'],
					'url'			=>	$row['url'],	
				));
			}
		}
		$template->assign_vars(array(
			'di_forum_id' 	=>	$di_forum_id,
			'di_forum_name'	=>	$di_forum_name,
		));
		$template->set_filenames_new(array(
			'copy' => 'seo/copy_di_forum.html')
		);
		$template->pparse('copy');
		
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosDoCopy()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$old_di_forum_name 	= mosGetParam( $_REQUEST, 'old_di_forum_name', '0');
		$di_forum_name		= mosGetParam( $_REQUEST, 'new_di_forum_name', '');
		$forum_id			= mosGetParam( $_REQUEST, 'forum_id', '0');
		$active				= mosGetParam( $_REQUEST, 'active', 0);
		
		$sql = "select * from tbl_di_forums where di_forum_name = '$old_di_forum_name'";
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{
			$groupkey_id = $row['groupkey_id'];
			$anchor_text = $row['anchor_text'];
			if (checkDuplicate("tbl_di_forums", array('di_forum_name' => $di_forum_name), "di_forum_name",0,false,"language_id = '$languageid' and groupkey_id = $groupkey_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			//cap nhat thoi gian su dung forum cuoi cung
			$sql1 = "update tbl_forums set last_use = now() where forum_id = $forum_id";
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
			
			$priority = mosGetPriority("tbl_di_forums", "priority", "");
			$sql2 = "insert into tbl_di_forums (groupkey_id, forum_id, di_forum_name, slug, meta_key, meta_des, title_seo, fanpage, youtube, network1, network2, anchor_text, active, priority, language_id, created_date, created_by, last_modified, modified_by) values ('$groupkey_id', '$forum_id', '$di_forum_name', '$slug', '$meta_key', '$meta_des', '$title_seo', '$fanpage', '$youtube', '$network1', '$network2', '$anchor_text', $active, $priority, $languageid, now(), '" . $_SESSION['membername'] . "', now(), '" . $_SESSION['membername'] . "')";
			if ( !($result2 = $db->sql_query($sql2)) ) message_die( SERVER_BUSY );
		}
		$template->assign_vars(array('MESSAGE'	=>	COPY_SUCCESS));
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosUpdate($di_forum_id)
	{		
		global $db, $root_path, $skin, $languageid, $template;
		/* Doan ma nay cap nhat tong data, nhung cap nhat xong roi di het ta dung
		//update index	
		$sql = "SELECT di_forum_name FROM tbl_di_forums WHERE isindex = 1 $cond";
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{
			$sql1 = "update tbl_di_forums set isindex = 1 where di_forum_name = '".$row['di_forum_name']."'";
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		}
		//update link hu
		$sql = "SELECT di_forum_name FROM tbl_di_forums WHERE ishu = 1 $cond";
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{
			$sql1 = "update tbl_di_forums set ishu = 1 where di_forum_name = '".$row['di_forum_name']."'";
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		}*/
		//update index	
		if ( $di_forum_id ){
			$sql = "select * from tbl_di_forums where di_forum_id = $di_forum_id";
			if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
			if ( $row = $db->sql_fetchrow($result) )
			$isindex = $row['isindex'];
			$ishu 	= $row['ishu'];
			$di_forum_name = $row['di_forum_name'];
			$sql1 = "update tbl_di_forums set isindex = $isindex, ishu = $ishu where di_forum_name = '$di_forum_name'";
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		}
		$template->assign_vars(array('MESSAGE'	=>	Updated));
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosExport()
	{		
		global $db, $root_path, $skin, $languageid, $template;
		$lever			   = mosGetParam( $_REQUEST, 'lever', '0' );
		$isindex			 = mosGetParam( $_REQUEST, 'isindex1', '2' );
		$isindex			 = mosGetParam( $_REQUEST, 'isindex1', '0' );//de lay 50 noindex truoc
		$ishu				= mosGetParam( $_REQUEST, 'ishu1', '0' );
		$from			    = mosGetParam( $_REQUEST, 'from_date', '0');
		$to			      = mosGetParam( $_REQUEST, 'to_date', '');
		$re_to = $to - $from +1;
		
		$cond = '';
		$cond .= ($ishu != 0)?' and tbl_di_forums.ishu = '.$ishu:'';
		$cond .= ($isindex != 2)?' and tbl_di_forums.isindex = '.$isindex:'';
		$limit = (!($from or $to))?"":"limit $from,$re_to";
		if ($lever == 0) $sql = "SELECT distinct di_forum_name FROM tbl_di_forums where (link2_id is not null or link2_id != 0) $cond ORDER BY di_forum_id DESC $limit";
		else $sql = "SELECT distinct di_forum_name FROM tbl_di_forums where (groupkey_id is not null or groupkey_id != 0) $cond ORDER BY di_forum_id DESC $limit";
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		$num_row = $db->sql_numrows($result);
		while( $row = $db->sql_fetchrow($result) )
		{		
			$template->assign_block_vars('list', array(	
				'di_forum_name'=>	$row['di_forum_name'],
			));	
		}
		$template->assign_vars(array(
			'isindex1'		=>	$isindex,
			'ishu1'		   =>	($ishu) ? 'checked' : '',
			'num_link' 	=>	$num_row,
			'from_date'	=>	$from,
			'to_date'	=>	$to,
			'lever'		=>	$lever,
		));
		
		
		$template->assign_vars(array('MESSAGE'	=>	Export));
		$template->set_filenames_new(array(
			'export' => 'seo/export.html')
		);
		$template->pparse('export');
	}
?>
