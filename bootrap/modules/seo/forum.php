<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'seo/forum',
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
	  case 'dieuchinh':	mosDieuChinh(); break;
		default:
			mosInvalidURL();
			exit;
	}
function mosList()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$da_point			= mosGetParam( $_REQUEST, 'da_point1', '0');
		$tf_point			= mosGetParam( $_REQUEST, 'tf_point1', '0' );
		$organic_key		 = mosGetParam( $_REQUEST, 'organic_key1', '0' );
		$dofollow			= mosGetParam( $_REQUEST, 'dofollow1', '0' );
		$nofollow			= mosGetParam( $_REQUEST, 'nofollow1', '0' );
		$nofollow			= mosGetParam( $_REQUEST, 'nofollow1', '0' );
		$isvn				= mosGetParam( $_REQUEST, 'isvn1', '0' );
		$isnew			   = mosGetParam( $_REQUEST, 'isnew1', '0' );
		$isno				= mosGetParam( $_REQUEST, 'isno1', '0' );
		$isngon			  = mosGetParam( $_REQUEST, 'isngon1', '0' );
		$nouse			   = mosGetParam( $_REQUEST, 'nouse', '0' );
		$from			    = mosGetParam( $_REQUEST, 'from_date', '0');
		$to			      = mosGetParam( $_REQUEST, 'to_date', '50');	
											
		$website_id		  = mosGetParam( $_REQUEST, 'website_id1', '0' );
		//cấu hình thời gian cho phép đi lại diễn đàn
		$day_post = 30;
		
		$cond = '';
		$cond .= (1)?' and da_point >= '.$da_point:'';
		$cond .= ($tf_point != '0')?' and tf_point >= '.$tf_point:'';
		$cond .= ($organic_key != '0')?' and organnic_key >= '.$organic_key:'';
		if(($dofollow == 0 and $nofollow == 0) or ($dofollow == 1 and $nofollow == 1)){
			$cond = '';	
		}elseif(($dofollow == 1)){
			$cond = ' and follow = 1';
		}else $cond = ' and follow = 0';
		
		if($isvn == 1){
			$cond .= " and isvn = 1";
		}elseif($isvn == 2){
			$cond .= " and isvn = 0";
		}
		$cond .= ($isnew)?" and isnew = ".$isnew:'';
		$cond .= ($isno)?" and isno = ".$isno:'';
		$cond .= ($isngon)?" and isngon = ".$isngon:'';
		
		$sql = "select * from tbl_forums where da_point >= $da_point and tf_point >= $tf_point and organic_key >= $organic_key $cond order by isno DESC,tf_point DESC, forum_id DESC limit $from,$to";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{						
			$dem = 0;
			if ( $website_id == 0 )					
				$sql1 = "select count(distinct di_forum_name) as dem from tbl_di_forums where forum_id = ".$row['forum_id'];
			else
				$sql1 = "SELECT *, COUNT(DISTINCT di_forum_name) AS dem  FROM tbl_di_forums RIGHT JOIN tbl_groupkeys ON tbl_di_forums.`groupkey_id` = tbl_groupkeys.`groupkey_id`  WHERE forum_id = ".$row['forum_id']." and website_id = ".$website_id;
			
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
			if( $row1 = $db->sql_fetchrow($result1) )
			$dem = $row1['dem'];
			
			if ( $row['follow'] == 2 ){
				$forum_name = "<font color='#000000'>".$row['forum_name']."</font>";
			}elseif ( $row['follow'] == 1 ){
				$forum_name = "<font color='#FF0000'><b>".$row['forum_name']."</b></font>";
			}else{
				$forum_name = "<font color='#3366FF'>".$row['forum_name']."</font>";
			}
			$last_use = substr($row['last_use'], 0, 10);
			//$date = date("2015-08-12");
			$day_1 = $last_use ;
			$day_2 = date('Y-m-d') ; //current date
			
			$day_ago = (strtotime($day_2) - strtotime($day_1)) / (60 * 60 * 24);
			if ( $day_ago >= $day_post and $day_ago <1000 ) $bg = "#1A9834";
			else $bg = "";
			
			
			switch( $row['isno'] )
			{	
				case '9'	:	$chatluong = "OK, Cao"; break;
				case '8'	:	$chatluong = "OK, TB"; break;
				case '7'	:  	$chatluong = "OK, Thấp"; break;
				case '6' 	:  	$chatluong = "Chưa, Cao"; break;
				case '5'	:	$chatluong = "Chưa, Trung bình"; break;
				case '4'	:	$chatluong = "Chưa, Thấp"; break;
				case '3'	:	$chatluong = "Chỉ Profile"; break;
				case '2'	:	$chatluong = "Chưa, thấp xem xét"; break;
				case '1'	:	$chatluong = "Bỏ"; break;
	
			default:
				$chatluong = "Chưa xem"; break;
			}
			if(($nouse == 0) or ($nouse == 1 and $dem == 0)){
				$order = $order + 1;
			$template->assign_block_vars('list', array(
				'className'			=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'				=>  $order,
				'forum_id'		=>	$row['forum_id'],
				'forum_name'	=>	$forum_name,
				'link'			=>	$row['forum_name'],
				'email'			=>	$row['email'],
				'pass'			=>	$row['pass'],
				'da_point'		=>	$row['da_point'],
				'tf_point'		=>	$row['tf_point'],
				'organic_key'	 =>	$row['organic_key'],
				'link_out'		=>	$row['link_out'],
				'dr_point'		=>	$row['dr_point'],
				'last_use'		=>	$last_use,
				'day_ago'		=>	$day_ago,
				'note'			=>	$row['note'],
				'view'			=>	$row['view'],
				'sum'			=>	($row1['dem']>0)?'<b><font size="4" color="#FF0000">'.$dem.'</font></b>':'',
				'active' 		=>	($row['active'] == 1) ? '<font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>' : '',
				'isvn' 			=>	($row['isvn'] == 1) ? '<font size="2" color="#008000">VN</font>' : '<b><font size="2" color="#FF0000">NN</font></b>',
				'isno' 			=>	$chatluong,
				'isngon'		=>	($row['isngon'] == 1) ? '<font size="2" color="#008000">Ngon</font>' : '',
				'isnew' 		=>	($row['isnew'] == 1) ? '<b><font size="2" color="#FF0000">Mới</font></b>' : '',
				'follow' 		=>	($row['follow'] == 1) ? '<b><font color="#FF0000">DO</font></b>' : '<font color="#008000">NO</font>',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',
				'bg'			=>	$bg,		
			));
			}
		}

		$template->assign_vars(array(
			'da_point1'		=>	$da_point,
			'tf_point1'		=>	$tf_point,
			'organic_key1'	 =>	$organic_key,
			'dofollow1'		=>	($dofollow) ? 'checked' : '',
			'nofollow1'		=>	($nofollow) ? 'checked' : '',
			'isvn1'			=>	$isvn,
			'isno1'			=>	$isno,
			'isngon1'		  =>	($isngon) ? 'checked' : '',
			'website_id1'	  =>	$website_id,
			'isnew1'		   =>	($isnew) ? 'checked' : '',
			'nouse'			=>	($nouse) ? 'checked' : '',
			'form_date'	    =>	$from,
			'to_date'		  =>	$to,
		));
		$template->set_filenames_new(array(
			'share' => 'seo/forum_list.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$forum_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
		$imgDir = $root_path . "images/forum/";

		if ($forum_id != 0)
		{	$sql = "select * from tbl_forums where forum_id = $forum_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$image=$row['image'];
				$template->assign_vars(array(
					'forum_id'=>	$forum_id,
					'forum_name'=>	$row['forum_name'],
					'email'			=>	$row['email'],
					'pass'			=>	$row['pass'],
					'da_point'		=>	$row['da_point'],
					'tf_point'		=>	$row['tf_point'],
					'organic_key'	 =>	$row['organic_key'],
					'link_out'		=>	$row['link_out'],
					'dr_point'		=>	$row['dr_point'],
					'note'			=>	$row['note'],
					'view'			=>	$row['view'],
					'active'		=>	($row['active'] == 1) ? 'checked' : '',
					'isvn'			=>	($row['isvn'] == 1) ? 'checked' : '',
					'isno'			=>	$row['isno'],
					'isngon'		  =>	($row['isngon'] == 1) ? 'checked' : '',
					'isnew'		   =>	($row['isnew'] == 1) ? 'checked' : '',
					'follow'		=>	$row['follow'],
					'image'			=>	$image,
					'imgPath'		=>	($image)?"<img src='$imgDir$image' border=0 >":"",
					'created_date' 	=> $row['created_date'],
					'created_by'	=> $row['created_by'],
					'last_modified'	=> $row['last_modified'],
					'modified_by'	=> $row['modified_by'],
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'active'		=>	'checked' ,
				'isvn'			=>	'' ,
				'isnew'			=>	'' ,
				'allow'			=> 	'hidden',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'seo/forum_info.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$forum_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($forum_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $forum_id, $direction, "tbl_forums", "forum_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$forum_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$forum_name	= mosGetParam( $_REQUEST, 'forum_name', '');
		$email		= mosGetParam( $_REQUEST, 'email', '');
		$pass		= mosGetParam( $_REQUEST, 'pass', '');
		$da_point				= mosGetParam( $_REQUEST, 'da_point', '');
		$tf_point			= mosGetParam( $_REQUEST, 'tf_point', '');
		$organic_key		 = mosGetParam( $_REQUEST, 'organic_key', '');
		$link_out			= mosGetParam( $_REQUEST, 'link_out', '');
		$dr_point			= mosGetParam( $_REQUEST, 'dr_point', '');
		$note			= mosGetParam( $_REQUEST, 'note', '');
		$view			= mosGetParam( $_REQUEST, 'view', 0);
		$active			= mosGetParam( $_REQUEST, 'active', 0);
		$isvn			= mosGetParam( $_REQUEST, 'isvn', 0);
		$isno			= mosGetParam( $_REQUEST, 'isno', 0);
		$isngon		  = mosGetParam( $_REQUEST, 'isngon', 0);
		$isnew			= mosGetParam( $_REQUEST, 'isnew', 0);
		$follow		= mosGetParam( $_REQUEST, 'follow', 0);
		$image   		= mosGetParam( $_REQUEST, 'new_image', '');
		$old_image   	= mosGetParam( $_REQUEST, 'old_image', '');
		
		
		
		
		if ($forum_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($forum_id == '0')
		{	
			if (checkDuplicate("tbl_forums", array('forum_name' => $forum_name), "forum_name",0,false,"language_id = '$languageid'"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$priority = mosGetPriority("tbl_forums", "priority", "");
			$sql = "insert into tbl_forums (forum_name, email, pass, da_point, tf_point, organic_key, link_out, dr_point, note, view, active, isvn, isno, isngon, isnew, follow, priority, language_id, image, created_date, created_by, last_modified, modified_by) values ('$forum_name', '$email', '$pass', '$da_point', '$tf_point', '$organic_key', '$link_out', '$dr_point', '$note', '$view', $active, $isvn, $isno, $isngon, $isnew, $follow, $priority, $languageid, '$img', now(), '" . $_SESSION['membername'] . "', now(), '" . $_SESSION['membername'] . "')";
		} else
			{ 
			if (checkDuplicate("tbl_forums", array('forum_name' => $forum_name), "forum_name",0,false,"language_id = '$languageid' and forum_id != $forum_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_forums set forum_name ='$forum_name', email = '$email', pass = '$pass', da_point = '$da_point', tf_point = '$tf_point', organic_key = '$organic_key', link_out = '$link_out', dr_point = '$dr_point', note = '$note', view = '$view',  active = $active, isvn = $isvn, isno = $isno, isngon = $isngon, isnew = $isnew, follow = $follow, language_id=$languageid, image = '$img', last_modified = now() , modified_by= '" . $_SESSION['membername'] . "' where forum_id = $forum_id";
			}echo $sql;
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );echo "qua day";
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db, $root_path;	
		$forum_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($forum_id == 0)
		{	mosInvalidURL();
			exit;
		}	
		
		$sql = "select image from tbl_forums where forum_id = $forum_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )
		{	
			$img = $row['image'];	
		}
		$arrField = array("image");
		checkDeleteOldFile("", $img, 1, $root_path . "images/forum" , "tbl_forums", $arrField, "forum_id", $forum_id);
		
		if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_forums", "forum_id", $forum_id);
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
			'forum_name' 	=>	mosGetParam( $_REQUEST, 'forum_name', ''),
			'email'			=>	mosGetParam( $_REQUEST, 'email', ''),
			'pass'			=>	mosGetParam( $_REQUEST, 'pass', ''),
			'da_point' 			=>	mosGetParam( $_REQUEST, 'da_point', ''),
			'tf_point'			=>	mosGetParam( $_REQUEST, 'tf_point', ''),
			'organic_key'		 =>	mosGetParam( $_REQUEST, 'organic_key', ''),
			'link_out' 			=>	mosGetParam( $_REQUEST, 'tf_point', ''),
			'dr_point' 		=>	mosGetParam( $_REQUEST, 'dr_point', ''),
			'note'			=>	mosGetParam( $_REQUEST, 'note', ''),
			'view' 				=>	mosGetParam( $_REQUEST, 'view', ''),			
			'MESSAGE'			=>	DUPLICATE_ENTRY,
			'forum_id'		=>	$id,
			
			'active'		=>	mosGetParam( $_REQUEST, 'active', 0),
			'isvn'			=> 	mosGetParam( $_REQUEST, 'isvn', 0),
			'isno'			=>	mosGetParam( $_REQUEST, 'isno', 0),
			'isngon'		  =>	mosGetParam( $_REQUEST, 'isngon', 0),
			'isnew'			=>	mosGetParam( $_REQUEST, 'isnew', 0),
			'follow'		=>	mosGetParam( $_REQUEST, 'follow', 0),	
		));
		$template->set_filenames_new(array(
			'forum' => 'seo/forum_info.html')
		);
		
		$template->pparse('forum');	
	}
//--------------------------------------------------------------------
	function mosDieuChinh()
	{	global $db, $root_path, $skin, $languageid, $template, $theme;	
    $sql = "select * from tbl_forums";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		while( $row = $db->sql_fetchrow($result)){
      $domain = parse_url($row['forum_name']);echo $domain['host'];
      $sql1 = "update tbl_forums set forum_name_1 = '".$domain['host']."' where forum_name = '".$row['forum_name']."' ";
      if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
    }
		mosList();	
	}
?>