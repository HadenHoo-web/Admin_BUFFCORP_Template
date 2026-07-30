<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'seo/groupkey',
		'LANGUAGEID'=> $languageid,
	));		

	switch( $action )
	{	
		case 'list'	:	mosList(0); break;
		case 'info'	:	mosInfo(); break;
		case 'up'	:  	mosMove('up'); break;
		case 'down' :  	mosMove('down'); break;
		case 'save'	:	mosSave(); break;
		case 'delete':	mosDelete(); break;
		case 'detail':	mosDetail(); break;
	
		default:
			mosInvalidURL();
			exit;
	}
function mosList($id)
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$website_id	= mosGetParam( $_REQUEST, 'website_id', '0');
		$order = 0;
		groupkeyList(0,'');	
			
		$template->assign_vars(array(
			'parent_id'	=>	0,
			'website_id'	=> $website_id,
		));
		$template->set_filenames_new(array(
			'share' => 'seo/groupkey_list.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$groupkey_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$parent_id	 = mosGetParam( $_REQUEST, 'parent_id', 0 );	
		selectList(0);	
		$sql = "select * from tbl_groupkeys where groupkey_id=$parent_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{
			$template->assign_vars(array(
				'parent_id'		=>	$row['groupkey_id'],
				'parent_name'	=>	$row['groupkey_name'],
				'url'			=>	$row['url'],
				'slug'			=>	$row['slug'],
				'meta_key'		=>	$row['meta_key'],
				'meta_des'		=>	$row['meta_des'],
				'title_seo'		=>	$row['title_seo'],
				'fanpage'		=>	$row['fanpage'],
				'youtube'		=>	$row['youtube'],
				'network1'		=>	$row['network1'],
				'network2'		=>	$row['network2'],
				'note'			=>	$row['note'],
			));
		} 

		if ($groupkey_id != 0)
		{	$sql = "select tbl_groupkeys.*, tbl_website.website_id from tbl_groupkeys left join tbl_website on tbl_groupkeys.website_id = tbl_website.website_id where groupkey_id = $groupkey_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'groupkey_id'		=>	$groupkey_id,
					'groupkey_name'	  =>	$row['groupkey_name'],
					'url'				=>	$row['url'],
					'slug'			   =>	$row['slug'],
					'meta_key'		   =>	$row['meta_key'],
					'meta_des'		   =>	$row['meta_des'],
					'title_seo'		  =>	$row['title_seo'],
					'fanpage'		    =>	$row['fanpage'],
					'youtube'		    =>	$row['youtube'],
					'network1'		   =>	$row['network1'],
					'network2'		   =>	$row['network2'],
					'parent_id' 	      =>	$row['parent_id'],
					'active'			 =>	($row['active'] == 1) ? 'checked' : '',
					'isshare'		    =>	($row['isshare'] == 1) ? 'checked' : '',
					'issource'		   =>	($row['issource'] == 1) ? 'checked' : '',
					'isupdate'		   =>	($row['isupdate'] == 1) ? 'checked' : '',
					'priority'		   =>	$row['priority'],
					'note'			   =>	$row['note'],
					'website_id'		 =>	$row['website_id'],
					'website_name'	   =>	$row['website_name'],
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
		
		$template->set_filenames_new(array(
			'share' => 'seo/groupkey_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$groupkey_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($groupkey_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $groupkey_id, $direction, "tbl_groupkeys", "groupkey_id", "priority");
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$groupkey_id 	 = mosGetParam( $_REQUEST, 'id', '0');
		$parent_id 	   = mosGetParam( $_REQUEST, 'parent_id', '0');
		$website_id 	  = mosGetParam( $_REQUEST, 'website_id', '0');
		$groupkey_name   = mosGetParam( $_REQUEST, 'groupkey_name', '');
		$url			 = mosGetParam( $_REQUEST, 'url', '');
		$slug			= mosGetParam( $_REQUEST, 'slug', '');
		$meta_key		= mosGetParam( $_REQUEST, 'meta_key', '');
		$meta_des		= mosGetParam( $_REQUEST, 'meta_des', '');
		$title_seo	   = mosGetParam( $_REQUEST, 'title_seo', '');
		$fanpage		 = mosGetParam( $_REQUEST, 'fanpage', '');
		$youtube		 = mosGetParam( $_REQUEST, 'youtube', '');
		$network1		= mosGetParam( $_REQUEST, 'network1', '');
		$network2		= mosGetParam( $_REQUEST, 'network2', '');
		$active		  = mosGetParam( $_REQUEST, 'active', 0);
		$isshare		 = mosGetParam( $_REQUEST, 'isshare', 0);
		$issource		= mosGetParam( $_REQUEST, 'issource', 0);
		$isupdate		= mosGetParam( $_REQUEST, 'isupdate', 0);
		$priority		= mosGetParam( $_REQUEST, 'priority', 0);
		$note			= mosGetParam( $_REQUEST, 'note', '');
		
		if ($groupkey_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($groupkey_id == '0')
		{	
			if (checkDuplicate("tbl_groupkeys", array('groupkey_name' => $groupkey_name), "groupkey_name",0,false,"language_id = '$languageid' and parent_id = $parent_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			if (checkDuplicate("tbl_groupkeys", array('url' => $url), "url",0,false,"language_id = '$languageid' and parent_id = $parent_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_groupkeys", "priority", "");
			$sql = "insert into tbl_groupkeys (parent_id, groupkey_name, url, slug, meta_key, meta_des, title_seo, fanpage, youtube, network1, network2, active, isshare, issource, isupdate, priority, language_id, note, website_id) values ($parent_id, '$groupkey_name', '$url', '$slug', '$meta_key', '$meta_des', '$title_seo', '$fanpage', '$youtube', '$network1', '$network2', $active, $isshare, $issource, $isupdate, $priority, $languageid, '$note', '$website_id')";
		} else
			{
			$sql = "update tbl_groupkeys set groupkey_name ='$groupkey_name', url = '$url', slug = '$slug', meta_key = '$meta_key', meta_des = '$meta_des', title_seo = '$title_seo', fanpage = '$fanpage', youtube = '$youtube', network1 = '$network1', network2 = '$network2',  active = $active, isshare = $isshare, issource = $issource, isupdate = $isupdate, language_id=$languageid, parent_id = '$parent_id', note = '$note', priority = '$priority', website_id = '$website_id' where groupkey_id = $groupkey_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$groupkey_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($groupkey_id == 0)
		{	mosInvalidURL();
			exit;
		}
		
		if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			//deleteByID("tbl_groupkeys", "groupkey_id", $groupkey_id);
		}else
			{
				$template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
			}
		mosList($parent_id);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDetail()
	{	
		global $template, $db;	
		$groupkey_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($groupkey_id == 0)
		{	mosInvalidURL();
			exit;
		}

		$sql = "SELECT count(*) AS tong FROM tbl_di_forums WHERE groupkey_id = 16";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result))
			$tong = $row['tong'];
		$sql = "SELECT di_forum_id, anchor_text, COUNT(*) AS dem FROM tbl_di_forums WHERE groupkey_id = $groupkey_id GROUP BY anchor_text order by dem DESC";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result))
		{
			$dem 	= $row['dem'];
			$tile	= round(($dem / $tong) * 100, 0);
			$template->assign_block_vars('anchor_text_list', array(
				'anchor_text'		=>	$row['anchor_text'],
				'dem'				=>	$dem,
				'tile'				=>	$tile,	
				'di_forum_id'		=>	$row['di_forum_id'],
			));
			$sql1 = "select * from tbl_di_forums where anchor_text = '".$row['anchor_text']."' order by di_forum_id DESC";
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
			while ( $row1 = $db->sql_fetchrow($result1))
			{
				$template->assign_block_vars('anchor_text_list.sub_list', array(
					'di_forum_name'		=>	$row1['di_forum_name'],
				));
			}
		}
		
				
		//$sql2 = "select * from tbl_di_profiles where groupkey_id = $groupkey_id";
		//if ( !($result2 = $db->sql_query($sql2)) ) message_die( SERVER_BUSY );
		//if ( $row2 = $db->sql_fetchrow($result2))
			//$dem_profile = $row2['dem'];
		//$dem = $dem_forum + $dem_profile;
		
		$template->set_filenames_new(array(
			'anchor_text' => 'seo/anchor_text.html')
		);
		$template->pparse('anchor_text');
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;				
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$template->assign_vars(array(
			'parent_id'			=>	mosGetParam( $_REQUEST, 'parent_id', 0),
			'website_id'		=>	mosGetParam( $_REQUEST, 'website_id', 0),
			'groupkey_name' 	=>	mosGetParam( $_REQUEST, 'groupkey_name', ''),
			'url'				=>	mosGetParam( $_REQUEST, 'url', ''),
			'slug'				=>	mosGetParam( $_REQUEST, 'slug', ''),
			'meta_key'			=>	mosGetParam( $_REQUEST, 'meta_key', ''),
			'meta_des'			=>	mosGetParam( $_REQUEST, 'meta_des', ''),
			'title_seo'			=>	mosGetParam( $_REQUEST, 'title_seo', ''),
			'fanpage'			=>	mosGetParam( $_REQUEST, 'fanpage', ''),
			'youtube'			=>	mosGetParam( $_REQUEST, 'youtube', ''),
			'network1'			=>	mosGetParam( $_REQUEST, 'network1', ''),
			'network2'			=>	mosGetParam( $_REQUEST, 'network2', ''),	
			'note'				=>	mosGetParam( $_REQUEST, 'note', ''),		
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'groupkey_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'group' => 'seo/groupkey_info.html')
		);
		
		$template->pparse('group');	
	}
//--------------------------------------------------------------------------------------------------
	function groupkeyList($parent_id, $prefix = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")
	{	global $db, $languageid, $template, $order, $old_parent_id;
		$website_id	= mosGetParam( $_REQUEST, 'website_id', '0');
		$cond = ($website_id > 0)?' and tbl_groupkeys.website_id = '.$website_id:'';
		
		$sql = "SELECT tbl_groupkeys.*, tbl_website.website_name FROM tbl_groupkeys left join tbl_website on tbl_groupkeys.website_id = tbl_website.website_id  WHERE (tbl_groupkeys.parent_id = $parent_id) $cond ORDER BY priority" ;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		$num_row = $db->sql_numrows($result);
		$i = 0;
		$j = $tam;
		if ( $parent_id != 0 ) $old_parent_id = $parent_id;
		else {$old_parent_id = '';$j = '';}
		while( $row = $db->sql_fetchrow($result) )
		{	
			$i 	= $i + 1;
			if($parent_id != $old_parent_id and $old_parent_id != ''){$old_parent_id = $parent_id; $j++;}
			
			$order = $order + 1;
			$sql1 = "select count(*) as dem from tbl_di_forums where groupkey_id =".$row['groupkey_id'];
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
			if ( $row1 = $db->sql_fetchrow($result1))
				$dem_forum = $row1['dem'];
				
			$sql2 = "select count(*) as dem from tbl_di_profiles where groupkey_id =".$row['groupkey_id'];
			if ( !($result2 = $db->sql_query($sql2)) ) message_die( SERVER_BUSY );
			if ( $row2 = $db->sql_fetchrow($result2))
				$dem_profile = $row2['dem'];
				
			$dem = $dem_forum + $dem_profile;
						
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'groupkey_id'		  => $row['groupkey_id'],
				'groupkey_name'	=>	$prefix.'<b><font size="2" color="#FF0000">'.$i.'</font></b>. '. $row['groupkey_name'],
				'url'			=>	$row['url'],
				'slug'			=>	$row['slug'],
				'meta_key'		=>	$row['meta_key'],
				'meta_des'		=>	$row['meta_des'],
				'title_seo'		=>	$row['title_seo'],
				'fanpage'		=>	$row['fanpage'],
				'youtube'		=>	$row['youtube'],
				'network1'		=>	$row['network1'],
				'network2'		=>	$row['network2'],
				'backlink'		=>	($dem>0)?'<b><font size="4" color="#FF0000">'.$dem.'</font></b>':'',
				'note'			=>	$row['note'],
				'active' 		=>	($row['active'] == 1) ? '<b><font size="3" color="#008000">V</font></b>' : '',
				'isshare' 		=>	($row['isshare'] == 1) ? '<b><font size="3" color="#008000">Share chéo</font></b>' : '',
				'issource' 		=>	($row['issource'] == 1) ? '<b><font size="2" color="#008000">Đi nguồn</font></b>' : '',
				'isupdate' 		=>	($row['isupdate'] == 1) ? '<b><font size="3" color="#008000">Up SEO</font></b>' : '',
				'priority'		=>	"<b><font size='3' color='#008000'>".$row['priority']."</font></b>",
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',	
				'website_id'		=> $row['website_id'],
				'website_name'	=>	$row['website_name'],	
			));	
			groupkeyList($row['groupkey_id'], $prefix. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
		}
	}
//--------------------------------------------------------------------------------------------------
	function selectList($parent_id, $prefix = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")
	{	global $db, $languageid, $template;
		$sql = "SELECT groupkey_id, groupkey_name FROM tbl_groupkeys  WHERE (parent_id = $parent_id) ORDER BY priority" ;
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
			$template->assign_block_vars('select_list', array(
				'groupkey_id'	=>	$row['groupkey_id'],
				'groupkey_name'=>	$prefix . $row['groupkey_name'],	
			));	
			selectList($row['groupkey_id'], $prefix. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
		}
	}
?>