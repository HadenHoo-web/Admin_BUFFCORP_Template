<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/keytraffic',
		'LANGUAGEID'=> $languageid,
		
	));		

	switch( $action )
	{	
		case 'list'			:	mosList(); break;
		case 'info'			:	mosInfo(); break;
		case 'save'			:	mosSave(); break;
		case 'delete'		:	mosDelete(); break;
	
		default:
			mosInvalidURL();
			exit;
	}
function mosList()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		
		$sql = "SELECT * FROM tbl_keytraffics ORDER BY keytraffic_id DESC";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$order = 0;
		while( $row = $db->sql_fetchrow($result) )
		{	$order = $order + 1;					
		
			$template->assign_block_vars('list', array(
				'className'	=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'		=>  $order,
				'keytraffic_id'	 =>	$row['keytraffic_id'],
				'keyword'   =>	$row['keyword'],
				'volume'   		 =>	$row['volume'],
			));	
		}
		$template->set_filenames_new(array(
			'share' => 'common_lists/keytraffic/keytraffic_list.tpl')
		);
		$template->pparse('share');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$keytraffic_id 	 = mosGetParam( $_REQUEST, 'id', 0 );

		if ($keytraffic_id != 0)
		{	$sql = "select * from tbl_keytraffics where keytraffic_id = $keytraffic_id";
			if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
			if ( $row = $db->sql_fetchrow($result) )
			{	
				$template->assign_vars(array(
					'keytraffic_id'	=>	$keytraffic_id,
					'keyword'	=>	$row['keyword'],
					'volume'		  =>	$row['volume'],
				));
			} else
				message_die( ID_NOTFOUND );		
		} else
		{			
			$template->assign_vars(array(
				'allow'	 => 'hidden',
			));
		}
		
		$template->set_filenames_new(array(
			'share' => 'common_lists/keytraffic/keytraffic_info.tpl')
		);
		$template->pparse('share');
	}
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$keytraffic_id 	  = mosGetParam( $_REQUEST, 'id', '0');
		$keyword	= mosGetParam( $_REQUEST, 'keyword', '');
		$volume		  = mosGetParam( $_REQUEST, 'volume', '');
		
		if ($keytraffic_id == '')
		{	
			mosInvalidURL();
			exit;
		}	
		if ($keytraffic_id == '0')
		{	
			if (checkDuplicate("tbl_keytraffics", array('keyword' => $keyword), "keyword",0,false,""))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "insert into tbl_keytraffics (keyword, volume) values ('$keyword', '$volume')";	
		} else
			{ 
			if (checkDuplicate("tbl_keytraffics", array('keyword' => $keyword), "keyword",0,false,"keytraffic_id != $keytraffic_id"))
			{	reShowPage( DUPLICATE_ENTRY );
				exit;
			}
			$sql = "update tbl_keytraffics set keyword ='$keyword', volume = '$volume' where keytraffic_id = $keytraffic_id";
			}

		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosList();
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$keytraffic_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($keytraffic_id == 0)
		{	mosInvalidURL();
			exit;
		}	
  if(strtolower($_SESSION['membername'])=="administrator") 
		{	
			deleteByID("tbl_keytraffics", "keytraffic_id", $keytraffic_id);
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
			'keyword' 	 =>	mosGetParam( $_REQUEST, 'keyword', ''),
			'volume' 		   =>	mosGetParam( $_REQUEST, 'volume', ''),	
			'MESSAGE'		=>	DUPLICATE_ENTRY,
			'keytraffic_id'	   =>	$id,
		));
		$template->set_filenames_new(array(
			'keytraffic' => 'common_lists/keytraffic/keytraffic_info.tpl')
		);
		
		$template->pparse('keytraffic');	
	}
?>
