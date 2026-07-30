<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'seo/link',
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
		
		$sql = "select * from tbl_links where language_id=$languageid order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'link_id'	=>	$row['link_id'],
				'link_name'	=>	$row['link_name'],
				'da_point'			=>	$row['da_point'],
				'tf_point'			=>	$row['tf_point'],
				'link_out'			=>	$row['link_out'],
				'dr_point'			=>	$row['dr_point'],
				'note'				=>	$row['note'],
				'view'				=>	$row['view'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'follow' 	=>	($row['follow'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'seo/link_list.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$link_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir = $root_path . "images/link/";

		if ($link_id != 0)
		{	$sql = "select * from tbl_links where link_id = $link_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$image=$row['image'];
				$template->assign_vars(array(
					'link_id'=>	$link_id,
					'link_name'=>	$row['link_name'],
					'da_point'		=>	$row['da_point'],
					'tf_point'		=>	$row['tf_point'],
					'link_out'		=>	$row['link_out'],
					'dr_point'		=>	$row['dr_point'],
					'note'			=>	$row['note'],
					'view'			=>	$row['view'],
					'active'		=>	($row['active'] == 1) ? 'checked' : '',
					'follow'		=>	($row['follow'] == 1) ? 'checked' : '',
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
			'share' => 'seo/link_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$link_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($link_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $link_id, $direction, "tbl_links", "link_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$link_id 		= mosGetParam( $_REQUEST, 'id', '0');
		$link_name		= mosGetParam( $_REQUEST, 'link_name', '');
		$da_point		= mosGetParam( $_REQUEST, 'da_point', '');
		$tf_point		= mosGetParam( $_REQUEST, 'tf_point', '');
		$link_out		= mosGetParam( $_REQUEST, 'link_out', '');
		$dr_point		= mosGetParam( $_REQUEST, 'dr_point', '');
		$note			= mosGetParam( $_REQUEST, 'note', '');
		$view			= mosGetParam( $_REQUEST, 'view', 0);
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$follow			= mosGetParam( $_REQUEST, 'follow', 0);
		$image   		= mosGetParam( $_REQUEST, 'new_image', '');
		$old_image   	= mosGetParam( $_REQUEST, 'old_image', '');
		
		$imgDir = $root_path . "images/link/";		

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
		
		if ($link_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($link_id == '0')
		{	
			if (checkDuplicate("tbl_links", array('link_name' => $link_name), "link_name",0,false,"language_id = '$languageid'"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_links", "priority", "");
			$sql = "insert into tbl_links (link_name, da_point, tf_point, link_out, dr_point, note, view, active, follow, priority, language_id, image) values ('$link_name', '$da_point', '$tf_point', '$link_out', '$dr_point', '$note', '$view', $active, $follow, $priority, $languageid, '$img')";
		} else
			{ 
			if (checkDuplicate("tbl_links", array('link_name' => $link_name), "link_name",0,false,"language_id = '$languageid' and link_id != $link_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_links set link_name ='$link_name', da_point = '$da_point', tf_point = '$tf_point', link_out = '$link_out', dr_point = '$dr_point', note = '$note', view = '$view',  active = $active, follow = $follow, language_id=$languageid, image = '$img' where link_id = $link_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$link_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($link_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		
		$sql = "select image from tbl_links where link_id = $link_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )
		{	
			$img = $row['image'];	
		}
		$arrField = array("image");
		checkDeleteOldFile("", $img, 1, $root_path . "images/link" , "tbl_links", $arrField, "link_id", $link_id);
		
		if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_links", "link_id", $link_id);
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
			'link_name' 	=>	mosGetParam( $_REQUEST, 'link_name', ''),
			'da_point' 				=>	mosGetParam( $_REQUEST, 'da_point', ''),
			'tf_point'			=>	mosGetParam( $_REQUEST, 'tf_point', ''),
			'link_out' 			=>	mosGetParam( $_REQUEST, 'tf_point', ''),
			'dr_point' 		=>	mosGetParam( $_REQUEST, 'dr_point', ''),
			'note'			=>	mosGetParam( $_REQUEST, 'note', ''),
			'view' 				=>	mosGetParam( $_REQUEST, 'view', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'link_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'link' => 'seo/link_info.html')
		);
		
		$template->pparse('link');	
	}
?>