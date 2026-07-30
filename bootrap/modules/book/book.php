<?php	

	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();		
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'book/book',
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
		'book' => 'book/book_list.tpl')
	);
	$sql = "select * from tbl_books left join tbl_vote on tbl_books.created_by = tbl_vote.vote_id where SUBSTRING(tbl_books.created_date, 1, 4) = '$year' and SUBSTRING(tbl_books.created_date, 6, 2) = '$month' order by book_id DESC";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order 	= 0;
	$suc	= 0;
	while( $row = $db->sql_fetchrow($result) )
	{	
		$product_id = $row['product_id'];
		$sql1 = "select * from tbl_products where product_id = $product_id";
		if ( !($result1 = $db->sql_query($sql1)) ) die ( SERVER_BUSY );
		if ( $row1 = $db->sql_fetchrow($result1))
		$product_name = $row1['product_name'];
		$order = $order + 1;
		if($row['issuc'] == 1){ $className = 'issuc';$suc	= $suc + 1;}
		elseif($row['checked'] == 1) $className = 'checked';
		else $className = 'alt';
		$web = parse_url($row['web']);
		if(strtolower($_SESSION['membername'])=="administrator" ) 
		{
		
		}
		if($web['host'] == "casauhoaca.com" or $web['host'] == "casaukieuhung.com" or 1)
		{
			$template->assign_block_vars('list', array(
				'className'			=>  $className,
				'order'				=>  $order,
				'book_id'			=>	$row['book_id'],
				'your_name'			=>	$row['your_name'],
				'tel'				=>	$row['tel'],
				'email'				=>	$row['email'],
				'address'			=>	$row['address'],
				'note'				=>	$row['note'],
				'checked'			=>	($row['checked'] == 0) ? ' display: none;' : '',
				'issuc'				=>	($row['issuc'] == 0) ? ' display: none;' : '',
				'created_date'		=>	$row['created_date'],
				'paytype'			=>	($row['paytype'] == 1)?'Visa':(($row['paytype'] == 2)?'Ngan hang':'Tien mat'),
				'product_name'		=>	$product_name,
				'product_id'		=>	$row['product_id'],
				'ketqua'			=>	$row['ketqua'],
				'created_by'		=>	$row['created_by'],
				'modified_by'		=>	$row['modified_by'],
				'web'				=>	$web['host'],
				'slug'				=>	$row1['slug'],
				'coupon'			=> $row['coupon'],
			));	
		}elseif(strtolower($_SESSION['membername'])=="administrator" ) 
		{
			$template->assign_block_vars('list', array(
				'className'			=>  $className,
				'order'				=>  $order,
				'book_id'			=>	$row['book_id'],
				'your_name'			=>	$row['your_name'],
				'tel'				=>	$row['tel'],
				'email'				=>	$row['email'],
				'address'			=>	$row['address'],
				'note'				=>	$row['note'],
				'checked'			=>	($row['checked'] == 0) ? ' display: none;' : '',
				'issuc'				=>	($row['issuc'] == 0) ? ' display: none;' : '',
				'created_date'		=>	$row['created_date'],
				'paytype'			=>	($row['paytype'] == 1)?'Visa':(($row['paytype'] == 2)?'Ngan hang':'Tien mat'),
				'product_name'		=>	$product_name,
				'product_id'		=>	$row['product_id'],
				'ketqua'			=>	$row['ketqua'],
				'created_by'		=>	$row['created_by'],
				'modified_by'		=>	$row['modified_by'],
				'web'				=>	$web['host'],
				'slug'				=>	$row1['slug'],
			));
		}
		
	}
	$template->assign_vars(array(
		'year'	=>	$year,
		'month'	=>	$month,
		'sum'	=>	$order,
		'suc'	=>	$suc,
		'tilesuc'	=>	round(($suc/$order)*100, 0),
	));
	$template->pparse('book');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
{	
	global $db, $root_path, $skin, $languageid, $template;
	$book_id 	 = mosGetParam( $_REQUEST, 'id', 0 );
	if ($book_id != 0)
	{	$sql = "select * from tbl_books where book_id = $book_id";
		if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result) )
		{	
			$product_id = $row['product_id'];
			$sql1 = "select * from tbl_products where product_id = $product_id";
			if ( !($result1 = $db->sql_query($sql1)) ) die ( SERVER_BUSY );
			if ( $row1 = $db->sql_fetchrow($result1))
			$product_name = $row1['product_name'];
			$template->assign_vars(array(
				'book_id'			=>	$book_id,
				'your_name'			=>	$row['your_name'],
				'email'				=>	$row['email'],
				'address'			=>	$row['address'],
				'tel'				=>	$row['tel'],
				'note'				=>	$row['note'],
				'your_name'			=>	$row['your_name'],
				'checked'			=>	($row['checked'] == 1) ? 'checked' : '',
				'issuc'				=>	($row['issuc'] == 1) ? 'checked' : '',
				'created_date'		=>	$row['created_date'],
				'paytype'			=>	($row['paytype'] == 1)?'Visa':(($row['paytype'] == 2)?'Ngan hang':'Tien mat'),
				'product_name'		=>	$product_name,
				'product_id'		=>	$row['product_id'],
				'ketqua'			=>	$row['ketqua'],
				'created_by'		=>	$row['created_by'],
				'modified_by'		=>	$row['modified_by'],
				'web'				=>	$row['web'],
				'slug'				=>	$row1['slug'],
				'coupon'			=> $row['coupon'],
			));
		} else
			message_die( ID_NOTFOUND );		
	} 
	$template->set_filenames_new(array(
		'book' => 'book/book_info.tpl')
	);
	$template->pparse('book');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
{	global $template, $db, $root_path;	
	$book_id = mosGetParam( $_REQUEST, 'id', '0');
	if ($book_id == 0)
	{	mosInvalidURL();
		exit;
	}
	if(strtolower($_SESSION['membername'])=="administrator") 
	{
		deleteByID("tbl_books", "book_id", $book_id);
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
	$book_id 	= mosGetParam( $_REQUEST, 'id', '0');
	$checked 	= mosGetParam( $_REQUEST, 'checked', '0');
	$issuc 		= mosGetParam( $_REQUEST, 'issuc', '0');
	$ketqua		= mosGetParam( $_REQUEST, 'ketqua', '');
	
	if ($book_id == 0)
	{	mosInvalidURL();
		exit;
	}
	$sql = "update tbl_books set checked = $checked, issuc = $issuc, ketqua = '$ketqua', modified_by = '".$_SESSION['membername']."' where book_id = $book_id";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$template->assign_vars(array('MESSAGE'	=>	PROCESS_SUCCESS));
	mosList();
}
?>

