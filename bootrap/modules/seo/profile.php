<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'seo/profile',
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
		$da_point			= mosGetParam( $_REQUEST, 'da_point1', '0');
		$tf_point			= mosGetParam( $_REQUEST, 'tf_point1', '0' );
		$dofollow			= mosGetParam( $_REQUEST, 'dofollow', '0' );
		$nofollow			= mosGetParam( $_REQUEST, 'nofollow', '0' );
		$cond = '';
		if(($dofollow == 0 and $nofollow == 0) or ($dofollow == 1 and $nofollow == 1)){
			$cond = '';
			
		}elseif(($dofollow == 1)){
			$cond = ' and follow = 1';
		}else $cond = ' and follow = 0';
		
		//$sql = "select * from tbl_profiles where language_id=$languageid and da_point >= $da_point and tf_point >= $tf_point $cond order by da_point";
		$sql = "select * from tbl_profiles where da_point >= $da_point and tf_point >= $tf_point $cond order by isno, profile_id DESC";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;
			$dem = 0;					
			$sql1 = "select count(profile_id) as dem from tbl_di_profiles where profile_id = ".$row['profile_id'];
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
			if( $row1 = $db->sql_fetchrow($result1) )
			$dem = $row1['dem'];
			//$dem = ($row1['dem']>0)?'<b><font size="4" color="#FF0000">'.$dem.'</font></b>':'';
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'profile_id'	=>	$row['profile_id'],
				'profile_name'	=>	$row['profile_name'],
				'da_point'				=>	$row['da_point'],
				'tf_point'			=>	$row['tf_point'],
				'link_out'			=>	$row['link_out'],
				'dr_point'			=>	$row['dr_point'],
				'bio'				=>	$row['bio'],
				'note'				=>	$row['note'],
				'view'				=>	$row['view'],
				'sum'			=>	($row1['dem']>0)?'<b><font size="4" color="#FF0000">'.$dem.'</font></b>':'',
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'isno' 		=>	($row['isno'] == 1) ? '<b><font size="2" color="#FF0000">Bỏ</font></b>' : '',
				'follow' 	=>	($row['follow'] == 1) ? '<b><font color="#FF0000">DO</font></b>' : '<font color="#008000">NO</font>',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->assign_vars(array(
			'da_point'		=>	$da_point,
			'tf_point'		=>	$tf_point,
			'dofollow'		=>	($dofollow) ? 'checked' : '',
			'nofollow'		=>	($nofollow) ? 'checked' : '',
		));
		$template->set_filenames_new(array(
			'share' => 'seo/profile_list.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$profile_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir = $root_path . "images/profile/";

		if ($profile_id != 0)
		{	$sql = "select * from tbl_profiles where profile_id = $profile_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$image=$row['image'];
				$template->assign_vars(array(
					'profile_id'=>	$profile_id,
					'profile_name'=>	$row['profile_name'],
					'da_point'		=>	$row['da_point'],
					'tf_point'		=>	$row['tf_point'],
					'link_out'		=>	$row['link_out'],
					'dr_point'		=>	$row['dr_point'],
					'bio'			=>	$row['bio'],
					'note'			=>	$row['note'],
					'view'			=>	$row['view'],
					'active'		=>	($row['active'] == 1) ? 'checked' : '',
					'isno'			=>	($row['isno'] == 1) ? 'checked' : '',
					'follow'		=>	($row['follow'] == 1) ? 'checked' : '',
					'image'			=>	$image,
					'imgPath'		=>	($image)?"<img src='$imgDir$image' border=0 >":"",
					'created_date' 	=> 	$row['created_date'],
					'created_by'	=> 	$row['created_by'],
					'last_modified'	=> 	$row['last_modified'],
					'modified_by'	=> 	$row['modified_by'],
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
			'share' => 'seo/profile_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$profile_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($profile_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $profile_id, $direction, "tbl_profiles", "profile_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$profile_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$profile_name	= mosGetParam( $_REQUEST, 'profile_name', '');
		$da_point				= mosGetParam( $_REQUEST, 'da_point', '');
		$tf_point			= mosGetParam( $_REQUEST, 'tf_point', '');
		$link_out			= mosGetParam( $_REQUEST, 'link_out', '');
		$dr_point			= mosGetParam( $_REQUEST, 'dr_point', '');
		$bio			= mosGetParam( $_REQUEST, 'bio', 0);
		$note			= mosGetParam( $_REQUEST, 'note', '');
		$view			= mosGetParam( $_REQUEST, 'view', 0);
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$isno			= mosGetParam( $_REQUEST, 'isno', 0);
		$follow		= mosGetParam( $_REQUEST, 'follow', 0);
		$image   		= mosGetParam( $_REQUEST, 'new_image', '');
		$old_image   	= mosGetParam( $_REQUEST, 'old_image', '');
		
		$imgDir = $root_path . "images/profile/";		

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
		
		
		if ($profile_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($profile_id == '0')
		{	
			if (checkDuplicate("tbl_profiles", array('profile_name' => $profile_name), "profile_name",0,false,"language_id = '$languageid'"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_profiles", "priority", "");
			$sql = "insert into tbl_profiles (profile_name, da_point, tf_point, link_out, dr_point, bio, note, view, active, follow, priority, language_id, image, isno, created_date, created_by, last_modified, modified_by) values ('$profile_name', '$da_point', '$tf_point', '$link_out', '$dr_point', '$bio', '$note', '$view', $active, $follow, $priority, $languageid, '$img', '$isno', now(), '" . $_SESSION['membername'] . "', now(), '" . $_SESSION['membername'] . "')";
		} else
			{ 
			if (checkDuplicate("tbl_profiles", array('profile_name' => $profile_name), "profile_name",0,false,"language_id = '$languageid' and profile_id != $profile_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_profiles set profile_name ='$profile_name', da_point = '$da_point', tf_point = '$tf_point', link_out = '$link_out', dr_point = '$dr_point', bio = '$bio', note = '$note', view = '$view',  active = $active, follow = $follow, language_id=$languageid, image = '$img', isno = '$isno', last_modified = now() , modified_by= '" . $_SESSION['membername'] . "' where profile_id = $profile_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$profile_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($profile_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		
		$sql = "select image from tbl_profiles where profile_id = $profile_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )
		{	
			$img = $row['image'];	
		}
		$arrField = array("image");
		checkDeleteOldFile("", $img, 1, $root_path . "images/profile" , "tbl_profiles", $arrField, "profile_id", $profile_id);
		
		if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_profiles", "profile_id", $profile_id);
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
			'profile_name' 	=>	mosGetParam( $_REQUEST, 'profile_name', ''),
			'da_point' 				=>	mosGetParam( $_REQUEST, 'da_point', ''),
			'tf_point'			=>	mosGetParam( $_REQUEST, 'tf_point', ''),
			'link_out' 			=>	mosGetParam( $_REQUEST, 'tf_point', ''),
			'dr_point' 		=>	mosGetParam( $_REQUEST, 'dr_point', ''),
			'bio'			=>	mosGetParam( $_REQUEST, 'bio', ''),
			'note'			=>	mosGetParam( $_REQUEST, 'note', ''),
			'view' 				=>	mosGetParam( $_REQUEST, 'view', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'profile_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'profile' => 'seo/profile_info.html')
		);
		
		$template->pparse('profile');	
	}
?>