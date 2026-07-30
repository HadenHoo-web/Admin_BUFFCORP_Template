<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'seo/submit',
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
		$sql = "select * from tbl_submits where language_id=$languageid order by active";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'submit_id'		=>	$row['submit_id'],
				'submit_name'	=>	$row['submit_name'],
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
			'share' => 'seo/submit_list.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$submit_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir = $root_path . "images/submit/";

		if ($submit_id != 0)
		{	$sql = "select * from tbl_submits where submit_id = $submit_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$image=$row['image'];
				$template->assign_vars(array(
					'submit_id'=>	$submit_id,
					'submit_name'=>	$row['submit_name'],
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
				'allow'		=> 'hidden',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'seo/submit_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$submit_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($submit_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $submit_id, $direction, "tbl_submits", "submit_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$submit_id 		= mosGetParam( $_REQUEST, 'id', '0');
		$submit_name		= mosGetParam( $_REQUEST, 'submit_name', '');
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
		
		$imgDir = $root_path . "images/submit/";		

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
		
		if ($submit_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($submit_id == '0')
		{	
			if (checkDuplicate("tbl_submits", array('submit_name' => $submit_name), "submit_name",0,false,"language_id = '$languageid'"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_submits", "priority", "");
			$sql = "insert into tbl_submits (submit_name, da_point, tf_point, link_out, dr_point, note, view, active, follow, priority, language_id, image) values ('$submit_name', '$da_point', '$tf_point', '$link_out', '$dr_point', '$note', '$view', $active, $follow, $priority, $languageid, '$img')";
		} else
			{ 
			if (checkDuplicate("tbl_submits", array('submit_name' => $submit_name), "submit_name",0,false,"language_id = '$languageid' and submit_id != $submit_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_submits set submit_name ='$submit_name', da_point = '$da_point', tf_point = '$tf_point', link_out = '$link_out', dr_point = '$dr_point', note = '$note', view = '$view',  active = $active, follow = $follow, language_id=$languageid, image = '$img' where submit_id = $submit_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$submit_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($submit_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		
		$sql = "select image from tbl_submits where submit_id = $submit_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )
		{	
			$img = $row['image'];	
		}
		$arrField = array("image");
		checkDeleteOldFile("", $img, 1, $root_path . "images/submit" , "tbl_submits", $arrField, "submit_id", $submit_id);
		
		if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_submits", "submit_id", $submit_id);
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
			'submit_name' 	=>	mosGetParam( $_REQUEST, 'submit_name', ''),
			'da_point' 				=>	mosGetParam( $_REQUEST, 'da_point', ''),
			'tf_point'			=>	mosGetParam( $_REQUEST, 'tf_point', ''),
			'link_out' 			=>	mosGetParam( $_REQUEST, 'tf_point', ''),
			'dr_point' 		=>	mosGetParam( $_REQUEST, 'dr_point', ''),
			'note'			=>	mosGetParam( $_REQUEST, 'note', ''),
			'view' 				=>	mosGetParam( $_REQUEST, 'view', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'submit_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'submit' => 'seo/submit_info.html')
		);
		
		$template->pparse('submit');	
	}
?>