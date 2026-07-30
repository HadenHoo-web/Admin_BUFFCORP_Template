<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'customer/customer',
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
function customerCanViewAllData()
	{
		$fullViewMemberIds = array(2, 28, 71);
		$loginId = isset($_SESSION["login_id"]) ? (int)$_SESSION["login_id"] : 0;

		return (
			strtolower($_SESSION['membername']) == "administrator"
			|| in_array($loginId, $fullViewMemberIds)
		);
	}

function mosList()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$customer_type_id	= mosGetParam( $_REQUEST, 'customer_type', '0' );
		$member_id		   = mosGetParam( $_REQUEST, 'member', '0' );
		$issearch			= mosGetParam( $_REQUEST, 'issearch', '0' );
		$issearch			= mosGetParam( $_REQUEST, 'issearch', '0' );
		$ishc				= mosGetParam( $_REQUEST, 'ishc', '0' );
		$iskh				= mosGetParam( $_REQUEST, 'iskh', '0' );
		$istp				= mosGetParam( $_REQUEST, 'istp', '0' );
		$tel				 = mosGetParam( $_REQUEST, 'tel1', '0' );
		$sinhnhat			= mosGetParam( $_REQUEST, 'sinhnhat', 0 );
		$created_month		= mosGetParam( $_REQUEST, 'created_month', '' );
		$created_year		= mosGetParam( $_REQUEST, 'created_year', '' );
		$official_new		= mosGetParam( $_REQUEST, 'official_new', '' );
		$cond = "";
		$cond .= ($sinhnhat != 0)?' and SUBSTRING(sinhnhat, 4, 2) = '.$sinhnhat:'';
		$cond .= ($customer_type_id != 0)?' and customer_type_id = '.$customer_type_id:'';
		$cond .= ($member_id != 0)?' and tbl_customer.member_id = '.$member_id:'';
		$cond .= ($tel != 0)?' and tbl_customer.tel like "%'.$tel.'%"':'';
		$cond .= ($ishc != 0)?' and tbl_customer.ishc = '.$ishc:'';
		$cond .= ($iskh != 0)?' and tbl_customer.iskh = '.$iskh:'';
		$cond .= ($istp != 0)?' and tbl_customer.istp = '.$istp:'';
		if ($created_month != '' && $created_year != '') {
			$cond .= ' and MONTH(tbl_customer.created_date) = '.intval($created_month).' and YEAR(tbl_customer.created_date) = '.intval($created_year);
		}
		if ($official_new != '' && $created_month != '' && $created_year != '') {
			$cond .= " and EXISTS (
				SELECT 1
				FROM tbl_website w
				INNER JOIN tbl_congno cn ON cn.website_id = w.website_id
				WHERE w.customer_id = tbl_customer.customer_id
				  AND cn.active = 1
				  AND cn.language_id = ".intval($languageid)."
				  AND cn.thuchi = 0
				  AND SUBSTRING(cn.ngay, 4, 2) = '".sprintf('%02d', intval($created_month))."'
				  AND SUBSTRING(cn.ngay, 7, 4) = '".intval($created_year)."'
			)";
		}
        $all_view = array(
            "34",//Trieu Anh
            "63",//Tú
            "50",//Hằng
            "76",//Giao
        );
    //$cond = (strtolower($_SESSION['membername'])=="administrator")?'':' and tbl_customer.member_id = "'.$_SESSION["login_id"].'"';

   //$cond = (strtolower($_SESSION['membername'])=="administrator")?'':((strtolower($_SESSION["login_id"])=="34")?' and tbl_customer.member_id not in (1,2)':' and tbl_customer.member_id = "'.$_SESSION["login_id"].'"');
   if (!customerCanViewAllData()) {
      $cond .= (in_array(strtolower($_SESSION["login_id"]), $all_view))
          ? ' and tbl_customer.member_id not in (1,2)'
          : ' and tbl_customer.member_id = "'.$_SESSION["login_id"].'"';
   }
		 
		if ( $issearch == 1 )
			$sql = "select tbl_customer.*, tbl_member.fullname, tbl_member.member_id from tbl_customer left join tbl_member on tbl_customer.member_id = tbl_member.member_id where language_id = $languageid $cond order by tbl_customer.customer_type_id";
		else 
			$sql = "select tbl_customer.*, tbl_member.fullname, tbl_member.member_id from tbl_customer left join tbl_member on tbl_customer.member_id = tbl_member.member_id where language_id = $languageid $cond order by tbl_customer.customer_type_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$num_row = $db->sql_numrows($result);
		$order = 0;
			while( $row = $db->sql_fetchrow($result) )
			{	$order = $order + 1;
				$customer_type_id = $row['customer_type_id'];
				$customer_type = isset($row['customer_type']) ? $row['customer_type'] : '';
				if ($customer_type_id == 8)$bg_customer = "Green";
			elseif ($customer_type_id == 9)$bg_customer = "Yellow";
			elseif ($customer_type_id == 10)$bg_customer = "Grey";
			else $bg_customer="";
			$sql1 = "SELECT COUNT(banhang_id) AS lan, SUM(banhang_name) AS tongcong FROM tbl_banhang WHERE customer_id = ".$row['customer_id'];
			if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
			if ( $row1 = $db->sql_fetchrow($result1) )
			{
				$lan = $row1['lan'];
				$tongcong = $row1['tongcong'];
			}
							
			$template->assign_block_vars('list', array(
				'className'	  =>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'		  =>  $order,
				'customer_id'	=>	$row['customer_id'],
				'customer_type'  =>	$customer_type,
				'customer_name'  =>	$row['customer_name'],
				'address'		=>	getFirstNCharacters($row['address'],80),
				'tel'			=>	$row['tel'],
				'fax'			=>	$row['fax'],
				'email'		  =>	$row['email'],
				'face'		   =>	($row['face'])?'<a href="'.$row['face'].'" target="_blank"><img border="0" src="https://casauhoaca.com/bootrap/templates/default/images/menu/facebook-ab7.png"></a>':'',
				'lan'			=>	($lan)?$lan:"<b>$lan</b>",
				'tongcong'	   =>	number_format($tongcong, 0, ',', '.'),
				'sinhnhat'	   =>	$row['sinhnhat'],
				'list_id'		=>	$row['list_id'],
				'ishc'		   =>	($row['ishc'] == 1) ? '' : 'none',
				'iskh'		   =>	($row['iskh'] == 1) ? '' : 'none',
				'istp'		   =>	($row['istp'] == 1) ? '' : 'none',
				'active' 		 =>	($row['active'] == 1) ? '' : '2',
				'bg_customer'	 =>	$bg_customer,
				'up'			 =>	($order == 1) ? ' display: none;' : '',
				'down'		   =>	($order == $num_row) ? ' display: none;' : '',
				'created_date'   =>	substr($row['created_date'],0,10),	
				'created_by'	 =>	$row['created_by'],	
				'quanly'		 =>	$row['fullname'],
			));
		}
		
		$sql = "select count(customer_id) as dem from tbl_customer where active = 1";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if( $row = $db->sql_fetchrow($result) )$actived = $row['dem'];
		
		
		$template->assign_vars(array(
			'sum'		=>		$num_row,
			'sinhnhat'   =>		$num_row,
			'tel'		=>		$tel,
			'customer_type_id'	=>	$customer_type_id,
			'member_id'		=>	$member_id,
			'actived'		=>	round(($actived/$num_row)*100,2),
		));
		
		$template->set_filenames_new(array(
			'share' => 'customer/customer/customer_list.html')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$customer_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($customer_id != 0)
		{	$sql = "select * from tbl_customer where customer_id = $customer_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'customer_id'	=>	$customer_id,
					'customer_name'	=>	$row['customer_name'],
					'customer_type_id'	=>	$row['customer_type_id'],
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
        'customer_type_id'=>  '1',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'customer/customer/customer_info.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$customer_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($customer_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $customer_id, $direction, "tbl_customer", "customer_id", "priority");
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$customer_id 	= mosGetParam( $_REQUEST, 'id', '0');
		$customer_name	= mosGetParam( $_REQUEST, 'customer_name', '');
		$customer_type_id= mosGetParam( $_REQUEST, 'customer_type_id', 0);
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
		
		if ($customer_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		
		if ($customer_id == '0')
		{	
			if ($tel != "" && checkDuplicate("tbl_customer", array('tel' => $tel), "tel",0,false,""))
			{	reShowPage( DUPLICATE_TEL );
				exit;
			}
			$priority = mosGetPriority("tbl_customer", "priority", "");
			$sql = "insert into tbl_customer (customer_name, customer_type_id, address, tel, fax, email, face, web, sinhnhat, list_id, ishc, iskh, istp, active, priority, small_img, language_id, created_by, modified_by, member_id, created_date) values ('$customer_name', '$customer_type_id', '$address', '$tel', '$fax', '$email', '$face', '$web', '$sinhnhat', '$list_id', $ishc, $iskh, $istp, $active, $priority, '$small_img', '$languageid', '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "', '$member_id', now())";	
		} else
			{ 
			if ($tel != "" && checkDuplicate("tbl_customer", array('tel' => $tel), "tel",0,false,"customer_id != $customer_id"))
			{	reShowPage( DUPLICATE_TEL );
				exit;
			}
			$arrField = array("small_img");
			checkDeleteOldFile($small_img, $old_small_img, $small_img_remove, $imgDirSmall, "tbl_customer", $arrField, "customer_id", $customer_id);
      $cond = (strtolower($_SESSION['membername'])=="administrator")?"member_id = '$member_id',":'';
			$sql = "update tbl_customer set customer_name ='$customer_name', customer_type_id = '$customer_type_id', address = '$address', tel = '$tel', fax = '$fax', email = '$email', face = '$face', web = '$web', sinhnhat = '$sinhnhat', list_id = '$list_id', ishc = $ishc, iskh = $iskh, istp = $istp, active = $active, small_img = '$small_img', modified_by = '" . $_SESSION['membername'] . "', $cond last_modified = now() where customer_id = $customer_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$customer_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($customer_id == 0)
		{	mosInvalidURL();
			exit;
		}
		if(strtolower($_SESSION['membername'])=="administrator" || strtolower($_SESSION['loginname'])=="ngan") 
		{	
			$imgDirSmall	= $root_path . "images/customer/customer/small_img";
			$sql = "select * from tbl_customer where customer_id = '$customer_id'";
			if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
			if( $row = $db->sql_fetchrow($result) )
			{
				$small_img = $row['small_img'];
			}
		
			deleteByID("tbl_customer", "customer_id", $customer_id);
			$template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
			$arrField = array("small_img");
			checkDeleteOldFile("", $small_img, 1, $imgDirSmall, "tbl_customer", $arrField, "customer_id", $customer_id);
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
			'customer_name' 		=>	mosGetParam( $_REQUEST, 'customer_name', ''),	
			'customer_type_id' 	=>	mosGetParam( $_REQUEST, 'customer_type_id', 0),	
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
			'customer_id'		=>	$id,
		));
		$template->set_filenames_new(array(
			'customer' => 'customer/customer/customer_info.tpl')
		);
		
		$template->pparse('customer');	
	}
?>
