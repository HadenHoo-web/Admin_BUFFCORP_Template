<?	
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'banhang/banhang_kind',
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
		
		$sql = "select * from tbl_banhang_kind where language_id=$languageid order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'banhang_kind_id'	=>	$row['banhang_kind_id'],
				'banhang_kind_name'=>	$row['banhang_kind_name'],
				'color'				=>	$row['color'],
				'active' 	=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',		
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'banhang/banhang_kind_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$banhang_kind_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir = $root_path . "images/banhang_kind/";

		if ($banhang_kind_id != 0)
		{	$sql = "select * from tbl_banhang_kind where banhang_kind_id = $banhang_kind_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$image=$row['image'];
				$template->assign_vars(array(
					'banhang_kind_id'=>	$banhang_kind_id,
					'banhang_kind_name'=>	$row['banhang_kind_name'],
					'color'			=>	$row['color'],
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
			'share' => 'banhang/banhang_kind_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$banhang_kind_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($banhang_kind_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $banhang_kind_id, $direction, "tbl_banhang_kind", "banhang_kind_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$banhang_kind_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$banhang_kind_name	= mosGetParam( $_REQUEST, 'banhang_kind_name', '');
		$color			= mosGetParam( $_REQUEST, 'color', '');
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$image   		= mosGetParam( $_REQUEST, 'new_image', '');
		$old_image   	= mosGetParam( $_REQUEST, 'old_image', '');
		
		$imgDir = $root_path . "images/banhang_kind/";		

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
		
		
		if ($banhang_kind_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($banhang_kind_id == '0')
		{	
			if (checkDuplicate("tbl_banhang_kind", array('banhang_kind_name' => $banhang_kind_name), "banhang_kind_name",0,false,"language_id = '$languageid'"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_banhang_kind", "priority", "");
			$sql = "insert into tbl_banhang_kind (banhang_kind_name, color, active, priority, language_id, image) values ('$banhang_kind_name', '$color', $active, $priority, $languageid, '$img')";	
		} else
			{ 
			if (checkDuplicate("tbl_banhang_kind", array('banhang_kind_name' => $banhang_kind_name), "banhang_kind_name",0,false,"language_id = '$languageid' and banhang_kind_id != $banhang_kind_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_banhang_kind set banhang_kind_name ='$banhang_kind_name', color = '$color',  active = $active, language_id=$languageid, image = '$img' where banhang_kind_id = $banhang_kind_id";
			}
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$banhang_kind_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($banhang_kind_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		
		$sql = "select image from tbl_banhang_kind where banhang_kind_id = $banhang_kind_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )
		{	
			$img = $row['image'];	
		}
		$arrField = array("image");
		checkDeleteOldFile("", $img, 1, $root_path . "images/banhang_kind" , "tbl_banhang_kind", $arrField, "banhang_kind_id", $banhang_kind_id);
		
    if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_banhang_kind", "banhang_kind_id", $banhang_kind_id);
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
			'banhang_kind_name' 	=>	mosGetParam( $_REQUEST, 'banhang_kind_name', ''),
			'color'				=>	mosGetParam( $_REQUEST, 'color', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'banhang_kind_id'	=>	$id,
		));
		$template->set_filenames_new(array(
			'banhang_kind' => 'banhang/banhang_kind_info.tpl')
		);
		
		$template->pparse('banhang_kind');	
	}
?>