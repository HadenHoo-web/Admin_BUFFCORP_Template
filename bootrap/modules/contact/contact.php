<?php	

	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();		
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'contact/contact',
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
	$template->set_filenames_new(array(
		'contact' => 'contact/contact_list.tpl')
	);
	$sql = "select * from tbl_contacts order by contact_id DESC";	
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order = 0;
	while( $row = $db->sql_fetchrow($result) )
	{	
		$order = $order + 1;
		$template->assign_block_vars('list', array(
			'className'			=>  ($row['checked'] == 0) ? 'alt' : 'checked',
			'order'				=>  $order,
			'contact_id'		=>	$row['contact_id'],
			'your_name'			=>	$row['your_name'],
			'phone'				=>	$row['phone'],
			'email'				=>	$row['email'],
			'content'			=>	$row['content'],
			'checked'			=>	($row['checked'] == 0) ? ' display: none;' : '',
			'created_date'		=>	$row['created_date'],
		));	
	}
	$template->pparse('contact');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
{	
	global $db, $root_path, $skin, $languageid, $template;
	$contact_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
	if ($contact_id != 0)
	{	$sql = "select * from tbl_contacts where contact_id = $contact_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{	
			$template->assign_vars(array(
				'contact_id'		=>	$contact_id,
				'your_name'			=>	$row['your_name'],
				'email'				=>	$row['email'],
				'phone'				=>	$row['phone'],
				'content'			=>	$row['content'],
				'your_name'			=>	$row['your_name'],
				'checked'				=>	($row['checked'] == 1) ? 'checked' : '',
				'created_date'		=>	$row['created_date'],
			));
		} else
			message_die( ID_NOTFOUND );		
	} 
	$template->set_filenames_new(array(
		'contact' => 'contact/contact_info.tpl')
	);
	$template->pparse('contact');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
{	global $template, $db, $root_path;	
	$contact_id = mosGetParam( $_REQUEST, 'id', '0');
	if ($contact_id == 0)
	{	mosInvalidURL();
		exit;
	}
	if(strtolower($_SESSION['membername'])=="administrator") 
	{	
		deleteByID("tbl_contacts", "contact_id", $contact_id);
	}else
	{
		$template->assign_vars(array('MESSAGE'	=>	CANT_NOT_DELETE));
	}
	$template->assign_vars(array('MESSAGE'	=>	DELETE_SUCCESS));
	mosList();
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosSave()
{	global $template, $db, $root_path;	
	$contact_id 	= mosGetParam( $_REQUEST, 'id', '0');
	$checked 			= mosGetParam( $_REQUEST, 'checked', '0');
	
	if ($contact_id == 0)
	{	mosInvalidURL();
		exit;
	}
	$sql = "update tbl_contacts set checked = $checked where contact_id = $contact_id";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$template->assign_vars(array('MESSAGE'	=>	PROCESS_SUCCESS));
	mosList();
}
?>

