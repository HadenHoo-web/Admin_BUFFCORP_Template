<?php	

	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();		
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'comment/comment',
		'LANGUAGEID'=> $languageid,
	));		
	switch( $action )
	{	case 'list'		:	mosList(); break;
		case 'info'		:	mosInfo(); break;
		case 'save'		:	mosSave(); break;
		case 'delete'  	:	mosDelete(); break;   
		default:		
			mosInvalidURL();
			exit;
	}
?>
<?php

function mosList()
{		
	global $db, $root_path, $skin, $languageid, $template;
	$month				= mosGetParam( $_REQUEST, 'month', date('m') );
	$year				= mosGetParam( $_REQUEST, 'year', date('Y') );
	$template->set_filenames_new(array(
		'comment' => 'comment/comment_list.tpl')
	);
	$sql = "select * from tbl_comments order by comment_id DESC";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order 	= 0;
	$suc	= 0;
	while( $row = $db->sql_fetchrow($result) )
	{	
		$order = $order + 1;
		if($row['checked'] == 1){ $className = 'issuc';$suc	= $suc + 1;}
		else $className = 'alt';
		$template->assign_block_vars('list', array(
			'className'		=>  $className,
			'order'			=>  $order,
			'comment_id'	   =>	$row['comment_id'],
			'your_name'		=>	$row['your_name'],
			'email'			=>	$row['email'],
			'content'		  =>	$row['content'],
			'checked'		  =>	($row['checked'] == 0) ? ' display: none;' : '',
			'created_date'	 =>	$row['created_date'],
			'comment_type'	 =>	$row['comment_type'],
			'web'			  =>	$row['web'],
			'rating'		   =>	$row['rating'],
		));		
	}
	$template->pparse('comment');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
{	
	global $db, $root_path, $skin, $languageid, $template;
	$comment_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
	if ($comment_id != 0)
	{	$sql = "select * from tbl_comments where comment_id = $comment_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{	
			$template->assign_vars(array(
				'comment_id'		=>	$comment_id,
				'your_name'		 =>	$row['your_name'],
				'email'			 =>	$row['email'],	
				'content'		   =>	$row['content'],
				'your_name'		 =>	$row['your_name'],
				'checked'		   =>	($row['checked'] == 1) ? 'checked' : '',
				'created_date'	  =>	$row['created_date'],
				'comment_type'	  =>	$row['comment_type'],
				'created_by'		=>	$row['created_by'],	
				'web'			   =>	$row['web'],
				'rating'		    =>	$row['rating'],	
			));
		} else
			message_die( ID_NOTFOUND );		
	} 
	$template->set_filenames_new(array(
		'comment' => 'comment/comment_info.tpl')
	);
	$template->pparse('comment');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
{	global $template, $db, $root_path;	
	$comment_id = mosGetParam( $_REQUEST, 'id', '0');
	if ($comment_id == 0)
	{	mosInvalidURL();
		exit;
	}
	if(strtolower($_SESSION['membername'])=="administrator" or 1) 
	{
		deleteByID("tbl_comments", "comment_id", $comment_id);
		$template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
	}else
	{
		$template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
	}
	
	mosList();
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosSave()
{	global $template, $db, $root_path;	
	$comment_id 	= mosGetParam( $_REQUEST, 'id', '0');
	$checked 	= mosGetParam( $_REQUEST, 'checked', '0');
	$issuc 		= mosGetParam( $_REQUEST, 'issuc', '0');
	$content		= mosGetParam( $_REQUEST, 'content', '');
	
	if ($comment_id == 0)
	{	mosInvalidURL();
		exit;
	}
	$sql = "update tbl_comments set checked = $checked, content = '$content', modified_by = '".$_SESSION['membername']."' where comment_id = $comment_id";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$template->assign_vars(array('MESSAGE'	=>	PROCESS_SUCCESS));
	mosList();
}
?>

