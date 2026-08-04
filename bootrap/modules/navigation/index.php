<?php	
	global $root_path, $languageid, $template, $skin, $theme, $db;
  $is_menu_admin = (
    (isset($_SESSION["loginname"]) && strtolower(trim($_SESSION["loginname"])) == "administrator")
    || (isset($_SESSION["membername"]) && strtolower(trim($_SESSION["membername"])) == "administrator")
  );
  $cond = (strtolower($_SESSION['membername'])=="administrator")?"":" and (tbl_giaoviec.created_by = '".$_SESSION['membername']."' OR tbl_giaoviec.member_id = '".$_SESSION["login_id"]."')";
  $sql = "select * from tbl_giaoviec left join tbl_member on tbl_giaoviec.member_id = tbl_member.member_id where 1 and tbl_giaoviec.soluong != 2 $cond order by priority";
  if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
  $num_row = $db->sql_numrows($result);
	$template->assign_vars(array(
		'ROOT'		 => $root_path,
		'LANGUAGEID' => $languageid,
		'funname'	 => 'navigation/index',
		'login_id'	 => $_SESSION["login_id"],
		'membername' =>	$_SESSION["membername"],	
		'menu_admin_link' => $is_menu_admin ? '<a class="sidebar-support-item" href="?option=functionmenu/functionmenu&amp;mode=list&amp;id=0"><span class="sidebar-support-icon" data-sidebar-icon="settings"></span><span class="sidebar-support-text">Quản lý menu</span></a>' : '',
    'giaoviec_sum'  =>  $num_row,
	));
	$template->set_filenames_new(array(
		'navigation' => 'navigation/navigation.tpl')
	);
	$template->pparse('navigation');
?>
