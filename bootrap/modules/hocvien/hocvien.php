<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();

	$template->assign_vars(array(
		'ROOT'		=> $root_path,
		'funname'	=> 'hocvien/hocvien',
		'LANGUAGEID'=> $languageid,

	));

	switch( $action )
	{
		case 'list'			:	mosList(); break;
		case 'info'			:	mosInfo(); break;
		case 'up'			:  	mosMove('up'); break;
		case 'down' 		:  	mosMove('down'); break;
		case 'save'			:	mosSave(); break;
		case 'delete'		:	mosDelete(); break;
		default:
			mosInvalidURL();
			exit;
	}
function mosList()
	{
		global $db, $root_path, $skin, $languageid, $template;
		$hocvien_type_id	= mosGetParam( $_REQUEST, 'hocvien_type', '0' );
		$member_id		   = mosGetParam( $_REQUEST, 'member', '0' );
		$issearch			= mosGetParam( $_REQUEST, 'issearch', '0' );
		$issearch			= mosGetParam( $_REQUEST, 'issearch', '0' );
		$ishc				= mosGetParam( $_REQUEST, 'ishc', '0' );
		$iskh				= mosGetParam( $_REQUEST, 'iskh', '0' );
		$istp				= mosGetParam( $_REQUEST, 'istp', '0' );
		$tel				 = mosGetParam( $_REQUEST, 'tel1', '0' );
		$sinhnhat			= mosGetParam( $_REQUEST, 'sinhnhat', 0 );
		$cond = "";
		$cond .= ($sinhnhat != 0)?' and SUBSTRING(sinhnhat, 4, 2) = '.$sinhnhat:'';
		$cond .= ($hocvien_type_id != 0)?' and hocvien_type_id = '.$hocvien_type_id:'';
		$cond .= ($member_id != 0)?' and tbl_hocvien.member_id = '.$member_id:'';
		$cond .= ($tel != 0)?' and tbl_hocvien.tel like "%'.$tel.'%"':'';
		$cond .= ($ishc != 0)?' and tbl_hocvien.ishc = '.$ishc:'';
		$cond .= ($iskh != 0)?' and tbl_hocvien.iskh = '.$iskh:'';
		$cond .= ($istp != 0)?' and tbl_hocvien.istp = '.$istp:'';
    //$cond = (strtolower($_SESSION['membername'])=="administrator")?'':' and tbl_hocvien.member_id = "'.$_SESSION["login_id"].'"';
   $cond = (strtolower($_SESSION['membername'])=="administrator")?'':((strtolower($_SESSION["login_id"])=="39")?' and tbl_hocvien.member_id in (2,39,5,34,35)':' and tbl_hocvien.member_id = "'.$_SESSION["login_id"].'"');

		if ( $issearch == 1 )
			$sql = "select tbl_hocvien.*, tbl_member.fullname, tbl_member.member_id from tbl_hocvien left join tbl_member on tbl_hocvien.member_id = tbl_member.member_id where language_id = $languageid $cond order by hocvien_id DESC";
		else
			$sql = "select tbl_hocvien.*, tbl_member.fullname, tbl_member.member_id from tbl_hocvien left join tbl_member on tbl_hocvien.member_id = tbl_member.member_id where language_id = $languageid $cond order by hocvien_id DESC";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;


			$template->assign_block_vars('list', array(
				'className'	  =>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'		  =>  $order,
				'hocvien_id'	=>	$row['hocvien_id'],
				'hocvien_type'  =>	$row['hocvien_type_id'],
				'hocvien_name'  =>	$row['hocvien_name'],
				'address'		=>	getFirstNCharacters($row['address'],80),
				'tel'			=>	$row['tel'],
				'fax'			=>	getFirstNCharacters($row['fax'],100),
				'email'		  =>	$row['email'],
				'face'		   =>	($row['face'])?'<a href="'.$row['face'].'" target="_blank"><img border="0" src="https://casauhoaca.com/bootrap/templates/default/images/menu/facebook-ab7.png"></a>':'',
				'sinhnhat'	   =>	$row['sinhnhat'],
				'list_id'		=>	$row['list_id'],
				'ishc'		   =>	($row['ishc'] == 1) ? '' : 'none',
				'iskh'		   =>	($row['iskh'] == 1) ? '' : 'none',
				'istp'		   =>	($row['istp'] == 1) ? '' : 'none',
				'active' 		 =>	($row['active'] == 1) ? '' : '2',
				'up'			 =>	($order == 1) ? ' display: none;' : '',
				'down'		   =>	($order == $num_row) ? ' display: none;' : '',
				'created_date'   =>	substr($row['created_date'],0,10),
				'created_by'	 =>	$row['created_by'],
				'quanly'		 =>	$row['fullname'],
			));
		}

		$sql = "select count(hocvien_id) as dem from tbl_hocvien where active = 1";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$actived = 0;
		if( $row = $db->sql_fetchrow($result) )$actived = $row['dem'];
		$activePercent = ($num_row > 0) ? round(($actived/$num_row)*100,2) : 0;


		$template->assign_vars(array(
			'sum'		=>		$num_row,
			'sinhnhat'   =>		$num_row,
			'tel'		=>		$tel,
			'hocvien_type_id'	=>	$hocvien_type_id,
			'member_id'		=>	$member_id,
			'actived'		=>	$activePercent,
		));

		$template->set_filenames_new(array(
			'share' => 'hocvien/hocvien/hocvien_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{
		global $db, $root_path, $skin, $languageid, $template;
		$hocvien_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($hocvien_id != 0)
		{	$sql = "select * from tbl_hocvien where hocvien_id = $hocvien_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_vars(array(
					'hocvien_id'	=>	$hocvien_id,
					'hocvien_name'	=>	$row['hocvien_name'],
					'hocvien_type_id'	=>	$row['hocvien_type_id'],
					'address'		=>	$row['address'],
					'tel'			=>	$row['tel'],
					'fax'			=>	$row['fax'],
					'email'		  =>	$row['email'],
					'face'		   =>	$row['face'],
					'web'			=>	$row['web'],
					'sinhnhat'	   =>	$row['sinhnhat'],
					'list_id'		=>	$row['list_id'],
					'ishc'		   =>	($row['ishc'] == 1) ? 'checked' : '',
					'iskh'		   =>	($row['iskh'] == 1) ? 'checked' : '',
					'istp'		   =>	($row['istp'] == 1) ? 'checked' : '',
					'active'		 =>	($row['active'] == 1) ? 'checked' : '',
					'small_img'	  =>	$row['small_img'],
					'allow_small_img'=>	($row['small_img'])?"":"none",
					'member_id'	  =>	$row['member_id'],
          'allow_member_id' => (strtolower($_SESSION['membername'])=="administrator")?'false':'true',
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
				'active'			=>	'' ,
				'allow'				=> 'hidden',
				'allow_small_img'	=>  'none',
				'member_id'	  		=>	$_SESSION["login_id"],
        'allow_member_id' => (strtolower($_SESSION['membername'])=="administrator")?'false':'true',
        'hocvien_type_id'=>  '1',
			));
		}

		$template->set_filenames_new(array(
			'share' => 'hocvien/hocvien/hocvien_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{
		global $languageid;
		$hocvien_id    = mosGetParam( $_REQUEST, 'id', '');

		if ($hocvien_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $hocvien_id, $direction, "tbl_hocvien", "hocvien_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{
		global $db, $root_path, $skin, $languageid, $template;
		$hocvien_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$hocvien_name	= mosGetParam( $_REQUEST, 'hocvien_name', '');
		$hocvien_type_id= mosGetParam( $_REQUEST, 'hocvien_type_id', 0);
		$address		= mosGetParam( $_REQUEST, 'address', '');
		$tel			= mosGetParam( $_REQUEST, 'tel', '');
		$fax			= mosGetParam( $_REQUEST, 'fax', '', 0x0003);
		//$fax			= mosGetParam( $_REQUEST, 'fax', '');
		$email			= mosGetParam( $_REQUEST, 'email', '');
		$face			= mosGetParam( $_REQUEST, 'face', '');
		$web			= mosGetParam( $_REQUEST, 'web', '');
		$sinhnhat			= mosGetParam( $_REQUEST, 'sinhnhat', '');
		$list_id		= mosGetParam( $_REQUEST, 'list_id', '');
		$ishc			= mosGetParam( $_REQUEST, 'ishc', 0);
		$iskh			= mosGetParam( $_REQUEST, 'iskh', 0);
		$istp			= mosGetParam( $_REQUEST, 'istp', 0);
		$active			= mosGetParam( $_REQUEST, 'active', 1);
		$old_small_img  = mosGetParam( $_REQUEST, 'old_small_img', '');
		$small_img  	= mosGetParam( $_REQUEST, 'small_img', '' );
		$small_img_remove  = mosGetParam( $_REQUEST, 'small_img_remove', 0 );
		$member_id		= (mosGetParam( $_REQUEST, 'member_id', '0'))?mosGetParam( $_REQUEST, 'member_id', '0'):$_SESSION["login_id"];

		if ($hocvien_id == '')
		{
			mosInvalidURL();
			exit;
		}

		if ($hocvien_id == '0')
		{
			if ($tel != "" && checkDuplicate("tbl_hocvien", array('tel' => $tel), "tel",0,false,""))
			{	reShowPage( DUPLICATE_TEL );
				exit;
			}
			$priority = mosGetPriority("tbl_hocvien", "priority", "");
			$sql = "insert into tbl_hocvien (hocvien_name, hocvien_type_id, address, tel, fax, email, face, web, sinhnhat, list_id, ishc, iskh, istp, active, priority, small_img, language_id, created_by, modified_by, member_id, created_date) values ('$hocvien_name', '$hocvien_type_id', '$address', '$tel', '$fax', '$email', '$face', '$web', '$sinhnhat', '$list_id', $ishc, $iskh, $istp, $active, $priority, '$small_img', '$languageid', '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "', '$member_id', now())";
		} else
			{
			if ($tel != "" && checkDuplicate("tbl_hocvien", array('tel' => $tel), "tel",0,false,"hocvien_id != $hocvien_id"))
			{	reShowPage( DUPLICATE_TEL );
				exit;
			}
			$arrField = array("small_img");
			checkDeleteOldFile($small_img, $old_small_img, $small_img_remove, $imgDirSmall, "tbl_hocvien", $arrField, "hocvien_id", $hocvien_id);
      $cond = (strtolower($_SESSION['membername'])=="administrator")?"member_id = '$member_id',":'';
			$sql = "update tbl_hocvien set hocvien_name ='$hocvien_name', hocvien_type_id = '$hocvien_type_id', address = '$address', tel = '$tel', fax = '$fax', email = '$email', face = '$face', web = '$web', sinhnhat = '$sinhnhat', list_id = '$list_id', ishc = $ishc, iskh = $iskh, istp = $istp, active = $active, small_img = '$small_img', modified_by = '" . $_SESSION['membername'] . "', $cond last_modified = now() where hocvien_id = $hocvien_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosDelete()
	{
		global $template, $db;
		$hocvien_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($hocvien_id == 0)
		{	mosInvalidURL();
			exit;
		}
		if(strtolower($_SESSION['membername'])=="administrator" || strtolower($_SESSION['loginname'])=="ngan")
		{
			$imgDirSmall	= $root_path . "images/hocvien/hocvien/small_img";
			$sql = "select * from tbl_hocvien where hocvien_id = '$hocvien_id'";
			if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
			if( $row = $db->sql_fetchrow($result) )
			{
				$small_img = $row['small_img'];
			}

			deleteByID("tbl_hocvien", "hocvien_id", $hocvien_id);
			$template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
			$arrField = array("small_img");
			checkDeleteOldFile("", $small_img, 1, $imgDirSmall, "tbl_hocvien", $arrField, "hocvien_id", $hocvien_id);
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
			'hocvien_name' 		=>	mosGetParam( $_REQUEST, 'hocvien_name', ''),
			'hocvien_type_id' 	=>	mosGetParam( $_REQUEST, 'hocvien_type_id', 0),
			'address'	 		=>	mosGetParam( $_REQUEST, 'address', ''),
			'tel'		 		=>	mosGetParam( $_REQUEST, 'tel', ''),
			'fax' 				=>	mosGetParam( $_REQUEST, 'fax', ''),
			'email' 			=>	mosGetParam( $_REQUEST, 'email', ''),
			'face' 			=>	mosGetParam( $_REQUEST, 'face', ''),
			'web' 				=>	mosGetParam( $_REQUEST, 'web', ''),
			'sinhnhat' 				=>	mosGetParam( $_REQUEST, 'sinhnhat', ''),
			'list_id'			=>	mosGetParam( $_REQUEST, 'list_id', ''),
			'ishc'				=>	(mosGetParam( $_REQUEST, 'ishc', 0) == 1) ? '' : '',
			'iskh'				=>	(mosGetParam( $_REQUEST, 'iskh', 0) == 1) ? '' : '',
			'istp'				=>	(mosGetParam( $_REQUEST, 'istp', 0) == 1) ? '' : '',
			'active'			=>	(mosGetParam( $_REQUEST, 'active', 0) == 1) ? '' : '',
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'hocvien_id'		=>	$id,
		));
		$template->set_filenames_new(array(
			'hocvien' => 'hocvien/hocvien/hocvien_info.tpl')
		);

		$template->pparse('hocvien');
	}
?>
