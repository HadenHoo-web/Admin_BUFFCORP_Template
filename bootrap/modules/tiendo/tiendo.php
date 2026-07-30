<?	
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'tiendo/tiendo',
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
?>

<?
function mosList()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$month	= mosGetParam( $_REQUEST, 'month', date('m') );
		$thuchi	= mosGetParam( $_REQUEST, 'thuchi', '2' );
		$cond = '';
		$cond .= ($thuchi == 1 or $thuchi == 0)?' and thuchi = '.$thuchi:'';
		
		$sql = "select * from tbl_tiendo where SUBSTRING(ngay, 4, 2) = '$month' $cond and language_id=$languageid order by tiendo_id DESC";
		$sql = "select * from tbl_tiendo where SUBSTRING(ngay, 4, 2) = '$month' and language_id=$languageid $cond order by tiendo_id DESC";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		$sum = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'tiendo_id'	=>	$row['tiendo_id'],
				'tiendo_code'=>	$row['tiendo_code'],
				'tiendo_name'=>	number_format($row['tiendo_name'], 0, ',', '.'),
				'thuchi'	=>	($row['thuchi'] == 0)?'+':'-',
				'ngay'		=>	$row['ngay'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
			));	
			if ( $row['thuchi'] == 0)
				$sum += $row['tiendo_name'];
			else 
				$sum -= $row['tiendo_name'];
		}
		$template->assign_vars(array(
			'sum'	=>	number_format($sum, 0, ',', '.'),
			'month'	=>	$month,
			'thuchi'=>	$thuchi,
		));
		$template->set_filenames_new(array(
			'share' => 'tiendo/tiendo_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$tiendo_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir = $root_path . "images/tiendo/";

		if ($tiendo_id != 0)
		{	$sql = "select * from tbl_tiendo where tiendo_id = $tiendo_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$image=$row['image'];
				$template->assign_vars(array(
					'tiendo_id'		=>	$tiendo_id,
					'tiendo_code'	=>	$row['tiendo_code'],
					'tiendo_name'	=>	$row['tiendo_name'],
					'thuchi'		=>	$row['thuchi'],
					'ngay'			=>	$row['ngay'],
					'active'		=>	($row['active'] == 1) ? 'checked' : '',
					'image'			=>	$image,
					'imgPath'		=>	($image)?"<img src='$imgDir$image' border=0 >":"",
					'allow'			=>	($row['image'])?"":"none",
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'thuchi'		=>	'1',
				'active'		=>	'checked' ,
				'allow'			=> 	'none',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'tiendo/tiendo_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$tiendo_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($tiendo_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $tiendo_id, $direction, "tbl_tiendo", "tiendo_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$tiendo_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$tiendo_code	= mosGetParam( $_REQUEST, 'tiendo_code', '');
		$tiendo_name	= mosGetParam( $_REQUEST, 'tiendo_name', '');
		$thuchi			= mosGetParam( $_REQUEST, 'thuchi', 0);
		$ngay			= mosGetParam( $_REQUEST, 'ngay', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$image			= mosGetParam( $_REQUEST, 'image', '');
		$old_image		= mosGetParam( $_REQUEST, 'old_image', '');
		$remove_image	= mosGetParam( $_REQUEST, 'remove_image', '');
			
		if ($tiendo_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($tiendo_id == '0')
		{	
			$priority = mosGetPriority("tbl_tiendo", "priority", "");
			$sql = "insert into tbl_tiendo (tiendo_code, tiendo_name, thuchi, ngay, active, priority, language_id, image) values ('$tiendo_code', '$tiendo_name', '$thuchi', '$ngay', $active, $priority, $languageid, '$image')";	
		} else
			{ 
			$sql = "update tbl_tiendo set tiendo_code = '$tiendo_code', tiendo_name ='$tiendo_name', thuchi = '$thuchi', ngay = '$ngay',  active = $active, language_id=$languageid, image = '$image' where tiendo_id = $tiendo_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		$arrField = array("image");
		checkDeleteOldFile($image, $old_image, $remove_image, $imgDir , "tbl_tiendo", $arrField, "tiendo_id", $tiendo_id);
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$tiendo_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($tiendo_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		
		$sql = "select image from tbl_tiendo where tiendo_id = $tiendo_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )
		{	
			$img = $row['image'];	
		}
		$arrField = array("image");
		checkDeleteOldFile("", $img, 1, $root_path . "images/tiendo" , "tbl_tiendo", $arrField, "tiendo_id", $tiendo_id);
		if(strtolower($_SESSION['membername'])=="administrator"){	
			deleteByID("tbl_tiendo", "tiendo_id", $tiendo_id);
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
			'tiendo_code'		=>	mosGetParam( $_REQUEST, 'tiendo_code', ''),
			'tiendo_name' 	=>	mosGetParam( $_REQUEST, 'tiendo_name', ''),
			'thuchi'		=>	mosGetParam( $_REQUEST, 'thuchi', 0),
			'ngay'			=>	mosGetParam( $_REQUEST, 'ngay', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'tiendo_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'tiendo' => 'tiendo/tiendo_info.tpl')
		);
		
		$template->pparse('tiendo');	
	}
?>
