<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'banners/banners',
		'LANGUAGEID'=> $languageid,		
	));		

	switch( $action )
	{	
		case 'list'  :	mosList(); break;
		case 'info'  :	mosInfo(); break;					
		case 'save'  :	mosSave(); break;
		case 'delete': 	mosDelete(); break;
		case 'setdefault'	: mosSetDefault();break;
		default:
			mosInvalidURL();
			exit;
	}	
?>
<?php
	function mosList()
	{	global $db, $root_path, $skin, $languageid, $template;
		$place 	 = mosGetParam( $_REQUEST, 'place', '0' );
		$imgDir_thumbnail = $root_path . "images/banners/thumbnail/";		
		$imgDir = $root_path . "images/banners/";	
		$cond = ($place)?" and place = ".$place:"";
		$sql = "select * from tbl_banners where 1 order by banner_id ";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;
			$banner_name=$row['banner_name'];
			if(PixType($banner_name)=="swf")
			{	$height = 75;
				$width = ceil(($row['w'] * 75) / $row['h']);
				$imgPath	= "<OBJECT classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' id='kmbluetooth' ALIGN='' VIEWASTEXT  width='$width' height='$height'><PARAM NAME='quality' VALUE='high'><PARAM NAME='movie' VALUE='$imgDir$banner_name'><EMBED src='$imgDir$banner_name' quality='high' NAME='kmbluetooth' TYPE='application/x-shockwave-flash' PLUGINSPAGE='http://www.macromedia.com/go/getflashplayer' width='$width' height='$height'></EMBED></OBJECT>";
			}else
			{	
				$imgPath	=	"<img src='$imgDir_thumbnail$banner_name' border=0 >";					
			}
			$template->assign_block_vars('list', array(
				'className'		=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'			=>  $order,
				'banner_id'		=>	$row['banner_id'],
				'place'			=>	$row['place'],
				'banner_name' 	=>	$banner_name,
				'w' 			=>	$row['w'] ,
				'h' 			=>	$row['h'] ,
				'filesize' 		=>	($row['banner_size']<1024 )?$row['banner_size']. " bytes":substr(($row['banner_size']/1024),0,strpos(($row['banner_size']/1024),".")+2)." KB",			
				'isactive'		=> ($row['default'] == 1) ? 'checked' : '',
				'imgPath'		=>	$imgPath,
			));	
		}
		$template->assign_vars(array(
			'place'	=>	$place,
		));
		$template->set_filenames_new(array(
			'banners' => 'banners/banners_list.tpl')
		);		
		$template->pparse('banners');
	}
//------------------------------------------------------------------------------------------------------	
	function mosInfo()
	{	global $db, $root_path, $skin, $languageid, $template;
		$banner_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir_thumbnail = $root_path . "images/banners/thumbnail/";		
		$imgDir = $root_path . "images/banners/";	
		if ($banner_id != 0)
		{	$sql = "select * from tbl_banners where banner_id = $banner_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$banner_name	=$row['banner_name'];	
				$template->assign_vars(array(
					'banner_id'	=>	$banner_id,
					'title' 	=>	$row['title'],
					'link'		=>	$row['link'],
					'place'		=>	$row['place'],
					'banner_name'	=>$banner_name,					
					'posted_date'	=>mosOurFormatDate($row['posted_date'],"DMY"),
					'posted_by'		=> $row['posted_by'],
				));
				
				if(PixType($imgDir.$banner_name)=="swf")
				{	$height = 75;
					$width = ceil(($row['w'] * 75) / $row['h']);
					$template->assign_vars(array(		
					'imgPathLarge'	=>"imgview('$imgDir$banner_name')",
					'imgPath'	=> "<OBJECT classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' id='kmbluetooth' ALIGN='' VIEWASTEXT  width='$width' height='$height'><PARAM NAME='quality' VALUE='high'><PARAM NAME='movie' VALUE='$imgDir$banner_name'><EMBED src='$imgDir$banner_name' quality='high' NAME='kmbluetooth' TYPE='application/x-shockwave-flash' PLUGINSPAGE='http://www.macromedia.com/go/getflashplayer' width='$width' height='$height'></EMBED></OBJECT>",
					));			
				}else
				{	$template->assign_vars(array(
					
					'imgPath'	=>	"<img src='$imgDir_thumbnail$banner_name' border=0 >",					
					'imgPathLarge'	=>"imgview('$imgDir$banner_name')",
					));					
				}
			} else
				message_die( ID_NOTFOUND );		
		} else
		{	$template->assign_vars(array(
				'banner_id'	=>	'0',
				'id'	=> '0',
				'allow'	=> 'hidden',
				'display'	=> 'none'
			));
		}
		$template->set_filenames_new(array(
			'banners' => 'banners/banners_info.tpl')
		);
		$template->pparse('banners');
	}
//------------------------------------------------------------------------------------------------------	
	function reShowPage( $message = DUPLICATE_ENTRY)
	{	global $db, $root_path, $skin, $languageid, $template, $cat_id;	
		$banner_name=mosGetParam( $_REQUEST, 'old_banner', '');
		$banner_id=mosGetParam( $_REQUEST, 'id', '0');
		$imgDir = $root_path . "images/banners/";		
		$template->assign_vars(array(
			'banner_id'		=>	mosGetParam( $_REQUEST, 'id', ''),
			'title' 		=>	mosGetParam( $_REQUEST, 'title', ''),
			'link' 			=>	mosGetParam( $_REQUEST, 'link', ''),
			'place'			=>	mosGetParam( $_REQUEST, 'place', 0),
			'posted_by'		=>	mosGetParam( $_REQUEST, 'posted_by', '0'),
			'posted_date'		=>	mosGetParam( $_REQUEST, 'posted_date', '0'),
			'banner_name'	=>	$banner_name,
			'allow'			=> ($banner_id=='0')?'hidden':'',
			'MESSAGE'		=>	$message
		));		
			
			if( $banner_id != '0')
			{	
				if(PixType($banner_name)=="swf")	
				{	$height = 75;
					$width = ceil(($row['w'] * 75) / $row['h']);
					$template->assign_vars(array(		
					'imgPathLarge'	=>"imgview('$imgDir$banner_name')",
					'imgPath'	=> "<OBJECT classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' id='kmbluetooth' ALIGN='' VIEWASTEXT  width='$width' height='$height'><PARAM NAME='quality' VALUE='high'><PARAM NAME='movie' VALUE='$imgDir$banner_name'><EMBED src='$imgDir$banner_name' quality='high'  width='$width' height='$height' NAME='kmbluetooth' TYPE='application/x-shockwave-flash' PLUGINSPAGE='http://www.macromedia.com/go/getflashplayer'></EMBED></OBJECT>",
					));			
				}else 
				{	$template->assign_vars(array(
					
					'imgPath'	=>	"<img src='$imgDir_thumbnail$banner_name' border=0 >",					
					'imgPathLarge'	=>"imgview('$imgDir$banner_name')",
					));					
				}
			}
		$template->set_filenames_new(array(
			'banners' => 'banners/banners_info.tpl')
		);
		$template->pparse('banners');	
	}
//------------------------------------------------------------------------------------------------------	
	function mosSave()
	{	
		global $db, $root_path, $skin, $languageid, $template;	
		$banner_id	=	mosGetParam( $_REQUEST, 'id', '0');
		$place		=	mosGetParam( $_REQUEST, 'place', '0');
		$title		=	mosGetParam( $_REQUEST, 'title', '');
		$link		=	mosGetParam( $_REQUEST, 'link', '');
		$imgDir = $root_path . "images/banners/";		
		if (! is_dir($imgDir))
			mkdir($imgDir, 0666);
		$imgDir_thumbnail = $root_path . "images/banners/thumbnail/";			
		if (! is_dir($imgDir_thumbnail))
			mkdir($imgDir_thumbnail, 0666);	
		
		$img = mosUploadImage($imgDir, "new_banner");
		if ($img == '' )
		{	$template->assign_var('MESSAGE',UPLOAD_ERROR);
		}
		else
		{	$imagesize = getimagesize($imgDir.$img);
			$filesize = filesize($imgDir.$img);			
		}
				
		if (checkDuplicate("tbl_banners", array('banner_name' => $img), "banner_id", $banner_id ,true,"place = $place"))
		{	
			$template->assign_var('MESSAGE',DUPLICATE_ENTRY);
			mosList();
			return;
		}
		mosCreateBannerThumbnail($imgDir.$img, $imgDir_thumbnail.$img );
		//else
		if ( !$banner_id )
		{		
			$default=0;
			$sql= "select * from tbl_banners";			
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if($db->sql_numrows($result)==0) $default=1;
			$sql = "insert into tbl_banners (title, link,banner_name,banner_size, w, h,`default`,posted_date,posted_by, place) values ( '$title', '$link','$img',$filesize , $imagesize[0], $imagesize[1],$default,now(),'".$_SESSION['membername']."', $place)";										
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		}else
		{
			$sql = "update tbl_banners set title = '$title', link = '$link', place = '$place' where banner_id = '$banner_id' ";									
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		}
		mosList();
	}
//----------------------------------------------------------------------------------------------
	function mosSetDefault()
	{	global $db, $template,  $root_path,$languageid;
		$banner_id	=	mosGetParam( $_REQUEST, 'id', '');
		$place		=	mosGetParam( $_REQUEST, 'place', 0);
	
		if ($banner_id == '')
		{	mosInvalidURL();
			exit;
		}
		$banner_name='';
		$sql = "select banner_name, title, link, `default` from tbl_banners where banner_id = $banner_id";
		
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
			{	
				$banner_name	= $row['banner_name'];
				$title			= $row['title'];
				$link			= $row['link'];	
				$default		= ($row['default'])?'0':'1';
			}

		$sql = "update tbl_banners set `default`=$default where banner_id = $banner_id and place = $place";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		//$sql = "update tbl_banners set `default`=0 where banner_id != $banner_id and place = $place";
		//if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	UPDATE_SUCCESS));
		$langname=getLangPath($languageid);
		if(strtolower($langname)=="vietnam")
			$fileconfig="hk_config_vn.ini";
		if(strtolower($langname)=="english")
		$fileconfig="hk_config_eng.ini";
		//writeFile($root_path . $fileconfig,"banner".$place, $banner_name);
		//writeFile($root_path . $fileconfig,"title_banner", $title);
		//writeFile($root_path . $fileconfig,"link_banner", $link);	
		mosList();
}
//----------------------------------------------------------------------------------------------
	function mosDelete()
	{	global $db, $template, $banner_id, $root_path;
		$banner_id = mosGetParam( $_REQUEST, 'id', '');
		if ($banner_id == '')
		{	mosInvalidURL();
			exit;
		}
		$sql = "select banner_name,`default` from tbl_banners where banner_id = $banner_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )
		{	$img = $row['banner_name'];	
			$default=$row['default'];
		}
			
		if($default==1)
		{	
			$template->assign_vars(array('MESSAGE'	=>	NOT_DELETE_DEFAULT));
			mosList();
			exit;
		}
   if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_banners", "banner_id", $banner_id);
		}else
			{
				$template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
			}
		
		$template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
		$arrField = array("banner_name");
		checkDeleteOldFile("", $img, 1, $root_path . "images/banners" , "tbl_banners", $arrField, "banner_id", $banner_id);
		checkDeleteOldFile("", $img, 1,  $root_path . "images/banners/thumbnail" , "tbl_banners", $arrField, "banner_id", $banner_id);				
		
		mosList();
	}
//----------------------------------------------------------------------------------------------
	
function writeFile($filePath, $key, $value)
{	global $db, $template,  $root_path;
	if (file_exists( $filePath)) 
	{	$ini_config = file_get_contents( $filePath); 
		$lines = explode("\n", $ini_config); 
		$line_count = sizeof($lines); 
		$have=0;
		for ($i = 0; $i < $line_count; $i++) 
		{ 	if (preg_match('#(.*?)\s*=\s*(.*)#', $lines[$i], $m)) 
			{ 	if($m[1] == $key)
				{	$have=1;					
					$text .= ($key . " = " . $value . "\n");
				} else
					$text .= $lines[$i] . "\n";
			}
		}
		if ($have == 0) 
			$text .= ($key . " = " . $value . "\n");

		$filename = $filePath;
		if (is_writable($filename))
		{	
			if ($handle = fopen($filename,'w')) 
			{	if (fwrite($handle, $text) == TRUE) 
				{	fclose($handle);
					return;
				}
			}
		}
	}
	$template->assign_var('MESSAGE', WRITE_TO_FILE_ERROR);
}
?>