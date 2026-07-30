<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'seo/bookmark',
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
function mosList()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		
		$sql = "select * from tbl_bookmarks where language_id=$languageid order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
			
			$sql1 = "select count(*) as dem from tbl_di_forums where bookmark_id =".$row['bookmark_id'];
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
			if ( $row1 = $db->sql_fetchrow($result1))
				$dem = $row1['dem'];
			
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'bookmark_id'	=>	$row['bookmark_id'],
				'bookmark_name'=>	$row['bookmark_name'],
				'link'				=>	$row['link'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',	
				'countbook'		=>	($dem>0)?'<b><font size="4" color="#FF0000">'.$dem.'</font></b>':'',	
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'seo/bookmark_list.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$bookmark_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir = $root_path . "images/bookmark/";

		if ($bookmark_id != 0)
		{	$sql = "select * from tbl_bookmarks where bookmark_id = $bookmark_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$image=$row['image'];
				$template->assign_vars(array(
					'bookmark_id'=>	$bookmark_id,
					'bookmark_name'=>	$row['bookmark_name'],
					'link'			=>	$row['link'],
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
			'share' => 'seo/bookmark_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$bookmark_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($bookmark_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $bookmark_id, $direction, "tbl_bookmarks", "bookmark_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$bookmark_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$bookmark_name	= mosGetParam( $_REQUEST, 'bookmark_name', '');
		$link			= mosGetParam( $_REQUEST, 'link', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$image   		= mosGetParam( $_REQUEST, 'new_image', '');
		$old_image   	= mosGetParam( $_REQUEST, 'old_image', '');
		
		$imgDir = $root_path . "images/bookmark/";		

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
		
		
		if ($bookmark_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($bookmark_id == '0')
		{	
			if (checkDuplicate("tbl_bookmarks", array('bookmark_name' => $bookmark_name), "bookmark_name",0,false,"language_id = '$languageid'"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_bookmarks", "priority", "");
			$sql = "insert into tbl_bookmarks (bookmark_name, link, active, priority, language_id, image) values ('$bookmark_name', '$link', $active, $priority, $languageid, '$img')";	
		} else
			{ 
			if (checkDuplicate("tbl_bookmarks", array('bookmark_name' => $bookmark_name), "bookmark_name",0,false,"language_id = '$languageid' and bookmark_id != $bookmark_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_bookmarks set bookmark_name ='$bookmark_name', link = '$link',  active = $active, language_id=$languageid, image = '$img' where bookmark_id = $bookmark_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$bookmark_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($bookmark_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		
		$sql = "select image from tbl_bookmarks where bookmark_id = $bookmark_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )
		{	
			$img = $row['image'];	
		}
		$arrField = array("image");
		checkDeleteOldFile("", $img, 1, $root_path . "images/bookmark" , "tbl_bookmarks", $arrField, "bookmark_id", $bookmark_id);
		if(strtolower($_SESSION['membername'])=="administrator"){	
			deleteByID("tbl_bookmarks", "bookmark_id", $bookmark_id);
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
			'bookmark_name' 	=>	mosGetParam( $_REQUEST, 'bookmark_name', ''),
			'link'				=>	mosGetParam( $_REQUEST, 'link', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'bookmark_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'bookmark' => 'seo/bookmark_info.html')
		);
		
		$template->pparse('bookmark');	
	}
?>