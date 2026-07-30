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
            'hocvien_type'  =>	$hocvien_type,
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
    if( $row = $db->sql_fetchrow($result) )$actived = $row['dem'];


    $template->assign_vars(array(
        'sum'		=>		$num_row,
        'sinhnhat'   =>		$num_row,
        'tel'		=>		$tel,
        'hocvien_type_id'	=>	$hocvien_type_id,
        'member_id'		=>	$member_id,
        'actived'		=>	round(($actived/$num_row)*100,2),
    ));

    $template->set_filenames_new(array(
            'share' => 'hocvien/hocvien/hocvien_list.tpl')
    );
    $template->pparse('share');
}
?>