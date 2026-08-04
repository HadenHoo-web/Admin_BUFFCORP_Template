<?php
	global $languageid, $template;
	require_once dirname(__FILE__).'/../../includes/notifications.php';
	$action      = mosGetParam( $_REQUEST, 'mode', '');
	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'common_lists/giaoviec',
		'LANGUAGEID'=> $languageid,
		
	));		

	switch( $action )
	{	
		case 'list'	:	mosList(0); break;
		case 'info'	:	mosInfo(); break;
		case 'up'	:  	mosMove('up'); break;
		case 'down' :  	mosMove('down'); break;
		case 'save'	:	mosSave(); break;
		case 'delete':	mosDelete(); break;
		case 'exe'	:	mosThuchien(); break;
		case 'dashboard'	:	mosDashboardKpiContent(); break;
		case 'thongke'	:	mosThongKe(); break;
	
		default:
			mosInvalidURL();
			exit;
		}

	function gvTaskEscape($value)
	{
		return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
	}

	function gvTaskIsoDate($value)
	{
		$value = trim((string)$value);
		if (preg_match('/^(\d{2})-(\d{2})-(\d{4})/', $value, $parts)) return $parts[3].'-'.$parts[2].'-'.$parts[1];
		if (preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $value, $parts)) return $parts[1].'-'.$parts[2].'-'.$parts[3];
		return '';
	}

	function gvTaskStorageDate($value)
	{
		$iso = gvTaskIsoDate($value);
		return $iso ? substr($iso, 8, 2).'-'.substr($iso, 5, 2).'-'.substr($iso, 0, 4) : trim((string)$value);
	}

	function gvTaskInitials($name)
	{
		$words = preg_split('/\s+/', trim((string)$name));
		$first = isset($words[0]) ? substr($words[0], 0, 1) : '';
		$last = count($words) > 1 ? substr($words[count($words) - 1], 0, 1) : '';
		return strtoupper($first.$last);
	}

	function gvTaskDepartment($primary, $extra)
	{
		$names = array_filter(array(trim((string)$primary), trim((string)$extra)));
		return count($names) ? implode(' / ', $names) : 'Chưa phân phòng';
	}

	function gvZaloLog($message)
	{
		$logDir = dirname(__FILE__).'/../../zalo/logs';
		if (!is_dir($logDir)) {
			@mkdir($logDir, 0775, true);
		}
		@file_put_contents(
			$logDir.'/zalo_send.log',
			date('Y-m-d H:i:s').' | '.$message.PHP_EOL,
			FILE_APPEND
		);
	}

	function mosList($id){	
  	global $db, $root_path, $skin, $languageid, $template;
		return mosListNew($id);
  	$month   		= mosGetParam( $_REQUEST, 'month', date('m') );
  	$year			= mosGetParam( $_REQUEST, 'year', date('Y') );
  	$parent_id      = mosGetParam( $_REQUEST, 'parent_id', 0 );
  	$parent_id      = ($parent_id==0)?$id:$parent_id;
  	$member_id      = mosGetParam( $_REQUEST, 'member_id1', '0' );
  	$website_id     = mosGetParam( $_REQUEST, 'website_id1', '0' );
  	//if($member_id == "")$member_id = $_SESSION["login_id"];
  	$created_by_id  = mosGetParam( $_REQUEST, 'created_by_id1', 0 );
  	$active				  = mosGetParam( $_REQUEST, 'active1', '0' );
  	switch( $_SESSION["login_id"] ){	
		case '1'	:	$cond = ""; break;
		case '63' ://Tú
		case '50' ://luân
    	case '34'	:	$cond = "and (tbl_giaoviec.created_by = '".$_SESSION['membername']."' OR tbl_giaoviec.member_id not in ('1'))"; break;// Triêu Anh
    	case '50'	:	$cond = "and (tbl_giaoviec.created_by = '".$_SESSION['membername']."' OR tbl_giaoviec.member_id in ('45','42','47','50','29','63','48','51','60'))"; break;// Hằng
		default:
			$cond = " and (tbl_giaoviec.created_by = '".$_SESSION['membername']."' OR tbl_giaoviec.member_id = '".$_SESSION["login_id"]."')";
	} 
  	$cond .= ($member_id)?' and tbl_giaoviec.member_id = '.$member_id:'';
  	$cond .= ($website_id)?' and tbl_giaoviec.website_id = '.$website_id:'';
  	$cond .= ($created_by_id)?' and tbl_giaoviec.created_by_id = '.$created_by_id:'';
  	$cond .= ($active == 0)?' and tbl_giaoviec.active = 1':'';
	//$sql = "select tbl_giaoviec.*, SUBSTRING(tbl_giaoviec.ngay, 7, 4) as y, SUBSTRING(tbl_giaoviec.ngay, 4, 2) as m , SUBSTRING(tbl_giaoviec.ngay, 1, 2) as d,tbl_member.fullname, tbl_website.website_name from (tbl_giaoviec left join tbl_member on tbl_giaoviec.member_id = tbl_member.member_id) left join tbl_website on tbl_giaoviec.website_id = tbl_website.website_id where 1 and tbl_giaoviec.parent_id = 0 and SUBSTRING(tbl_giaoviec.ngay, 4, 2) = '$month' and SUBSTRING(tbl_giaoviec.ngay, 7, 4) = '$year' $cond order by y DESC, m DESC, d DESC, soluong, giaoviec_id";
	$sql = "select tbl_giaoviec.*, SUBSTRING(tbl_giaoviec.created_date, 1, 4) AS Y, SUBSTRING(tbl_giaoviec.created_date, 6, 2) AS m , SUBSTRING(tbl_giaoviec.created_date, 9, 2) AS d,tbl_member.fullname, tbl_member.member_type_id, tbl_member.extra_member_type_id, tbl_website.website_name from (tbl_giaoviec left join tbl_member on tbl_giaoviec.member_id = tbl_member.member_id) left join tbl_website on tbl_giaoviec.website_id = tbl_website.website_id where 1 and SUBSTRING(tbl_giaoviec.created_date, 6, 2) = '$month' and SUBSTRING(tbl_giaoviec.ngay, 7, 4) = '$year' $cond order by y DESC, m DESC, d DESC, fullname, soluong, giaoviec_id";
	$contentDepartmentId = gvKpiContentDepartmentId();
	$tam = "";$tam_name = "";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	$num_row = $db->sql_numrows($result);
	$order = 0;$today = date("Y-m-d");
	while( $row = $db->sql_fetchrow($result) ){   
		$order +=1;	
        $giaoviec_id	= $row['giaoviec_id'];
        $n = $row['ngay'];
        $ngay_tam = substr($n, 6, 4)."-".substr($n, 3, 2)."-".substr($n, 0, 2);
        if (strtotime($today) > strtotime($ngay_tam) and $row['soluong'] != 2) {
          	$war = ' ⚠️';
        }else $war = '';
		$created_date = substr($row['created_date'],0,10);
		$created_date = ($created_date == $tam)?"":$created_date;
		$color		  = '';
		switch ($row['created_by_id']) {
			case "1"://Administrator
				$color = ' style="font-weight:bold; color:red"';
				break;
			case "34"://Triêu Anh
				$color = ' style="font-weight:bold; color:orange"';
				break;
			case "50"://Hằng
				$color = ' style="font-weight:bold; color:brown"';
				break;
			case "63":// Tú
				$color = ' style="font-weight:bold; color:green"';
				break;
			default:
				$color = '';
				break;
		}
			$isContentAssignee = ($contentDepartmentId > 0 && ((int)$row['member_type_id'] == $contentDepartmentId || (int)$row['extra_member_type_id'] == $contentDepartmentId));
		$isMissingContentKpi = ($isContentAssignee && !gvKpiIsContentTaskType($row['kpi_type']));
		$template->assign_block_vars('list', array(
			'className'		=>  ($order % 2 == 1) ? 'alt' : 'inv',
			'status'		=>  $isMissingContentKpi ? ' content-kpi-missing-row' : '',
			'order'			=>  $order,
			'giaoviec_id'	=>	$row['giaoviec_id'],
			'giaoviec_name'	=>	$row['giaoviec_name'],
			'kpi_warning'	=>  '',
          	'link_demo'		=>	($row['link_demo'])?"<a href='".$row['link_demo']."' target='_blank'><b>(Link)</b></a>":'',
            'giaoviec_num'	=>	($row['giaoviec_num'])?"(<font color='blue'><b>".$row['giaoviec_num']."</b></font>)":"",
          	'ngay'		    =>	$row['ngay'].$war,
			'chitiet'		=>	$row['chitiet'],
			'soluong'		=>	($row['soluong'] ==0 )?"Chưa Thực Hiện":(($row['soluong'] ==1 )?"<font color='orange'><b>Đang Thực Hiện</b></font>":"<font color='blue'><b>Đã Xong</b></font>"),
			'active' 		=>	($row['active'] == 1) ? '✔️' : '',
			'up'			=>	($order == 1) ? ' display: none;' : '',
			'down'			=>	($order == $num_row) ? ' display: none;' : '',
			'created_by'	=>	$row['created_by'],
			'created_date'  =>	$created_date,
			'modified_by'   =>	$row['modified_by'],
			'last_modified' =>	$row['last_modified'],
          	'member_name'   =>  ($row['fullname'] == $tam_name)?"":$row['fullname'],
          	'website_name'  =>  $row['website_name'],
			//'color'			=>	($row['created_by'] == "Administrator")?' style="font-weight:bold; color:red"':(($row['created_by_id'] == "34" )?' style="font-weight:bold; color:orange"':''),
          	'color'			=>	$color,
		));
		$tam = substr($row['created_date'],0,10);$tam_name = $row['fullname'];
		/*$sql1 = "select tbl_giaoviec.*, SUBSTRING(tbl_giaoviec.ngay, 7, 4) as y, SUBSTRING(tbl_giaoviec.ngay, 4, 2) as m , SUBSTRING(tbl_giaoviec.ngay, 1, 2) as d,tbl_member.fullname, tbl_website.website_name from (tbl_giaoviec left join tbl_member on tbl_giaoviec.member_id = tbl_member.member_id) left join tbl_website on tbl_giaoviec.website_id = tbl_website.website_id where tbl_giaoviec.parent_id = $giaoviec_id order by y DESC, m DESC, d DESC, soluong, giaoviec_id";
		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		while( $row1 = $db->sql_fetchrow($result1) ){
			$order +=1;	
        	$n = $row1['ngay'];
        	$ngay_tam = substr($n, 6, 4)."-".substr($n, 3, 2)."-".substr($n, 0, 2);
        	if (strtotime($today) > strtotime($ngay_tam) and $row1['soluong'] != 2) {
          		$war = ' ⚠️';
        	}else $war = '';
			$template->assign_block_vars('list', array(
				'className'		=>  ($order % 2 == 1) ? 'alt' : 'inv',
				'order'			=>  $order,
				'giaoviec_id'	=>	$row1['giaoviec_id'],
				'giaoviec_name'	=>	" -- ".$row1['giaoviec_name'],
				'giaoviec_num'	=>	($row1['giaoviec_num'])?"(<font color='blue'><b>".$row1['giaoviec_num']."</b></font>)":"",
				'ngay'		    =>	$row1['ngay'].$war,
				'chitiet'		=>	$row1['chitiet'],
				'soluong'		=>	($row1['soluong'] ==0 )?"Chưa Thực Hiện":(($row1['soluong'] ==1 )?"<font color='orange'><b>Đang Thực Hiện</b></font>":"<font color='blue'><b>Đã Xong</b></font>"),
				'active' 		=>	($row1['active'] == 1) ? '✔️' : '',
				'up'			=>	($order == 1) ? ' display: none;' : '',
				'down'			=>	($order == $num_row) ? ' display: none;' : '',
				'created_by'	=>	$row1['created_by'],
				'created_date'  =>	$row1['created_date'],
				'modified_by'   =>	$row1['modified_by'],
				'last_modified' =>	$row1['last_modified'],
				'member_name'   =>  $row1['fullname'],
				'website_name'  =>  $row1['website_name'],
				'color'			=>	($row1['created_by'] == "Administrator")?' style="font-weight:bold; color:red"':"",
			));
		}*/
	}
	$sql = "select * from tbl_giaoviec where giaoviec_id=$parent_id";
	if( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	if( $row = $db->sql_fetchrow($result) ){
		$template->assign_vars(array(
			'parent_id'	     =>	$row['giaoviec_id'],
			'giaoviec_name'	 =>	$row['giaoviec_name'],
          	'giaoviec_num'	 =>	$row['giaoviec_num'],
			'chitiet'		 =>	$row['chitiet'],
			'soluong'		 =>	$row['soluong'],
          	'isthongke'      => (strtolower($_SESSION['membername'])=="administrator")?"":"none",
		));
	}
  	$cond = (strtolower($_SESSION['membername'])=="administrator")?'':' and active = 1'; 
    $sql = "select * from tbl_website where 1 $cond order by website_name";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while ( $row = $db->sql_fetchrow($result) ){
		$template->assign_block_vars('website_list', array(
			'website_id'	   =>	$row['website_id'],
			'website_name'	 =>	$row['website_name'],
		));
	}
  	$template->assign_vars(array(
    	'member_id'		=>	$member_id,
    	'website_id'	=>	$website_id,
    	'created_by_id'	=>	$created_by_id,
    	'active'		=>	($active == 1) ? 'checked' : '',
    	'month'	     	=>	$month,
		'year'	     	=>	$year,
  	));
	$template->set_filenames_new(array(
		'giaoviec' => 'common_lists/giaoviec/giaoviec_list.html')
	);
	$template->pparse('giaoviec');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosListNew($id)
{
	global $db, $languageid, $template;
	$month = mosGetParam($_REQUEST, 'month', '');
	$year = mosGetParam($_REQUEST, 'year', '');
	$view = mosGetParam($_REQUEST, 'view', 'day');
	$taskToast = mosGetParam($_REQUEST, 'task_toast', '');
	if (!in_array($taskToast, array('saved', 'deleted', 'delete_blocked'))) $taskToast = '';
	$selectedDate = gvTaskIsoDate(mosGetParam($_REQUEST, 'day', ''));
	$period = mosGetParam($_REQUEST, 'period', '');
	$member_id = (int)mosGetParam($_REQUEST, 'member_id1', 0);
	$website_id = (int)mosGetParam($_REQUEST, 'website_id1', 0);
	$active = mosGetParam($_REQUEST, 'active1', '0');
	if (!in_array($view, array('day', 'month', 'year'))) $view = 'day';
	if (preg_match('/^\d{4}-\d{2}$/', $period)) {
		$year = substr($period, 0, 4);
		$month = substr($period, 5, 2);
		$view = 'month';
	}

	if (!preg_match('/^\d{2}$/', $month) || !preg_match('/^\d{4}$/', $year)) {
		$month = $selectedDate ? substr($selectedDate, 5, 2) : date('m');
		$year = $selectedDate ? substr($selectedDate, 0, 4) : date('Y');
	}
	if (!$selectedDate || substr($selectedDate, 0, 7) != $year.'-'.$month) $selectedDate = $year.'-'.$month == date('Y-m') ? date('Y-m-d') : $year.'-'.$month.'-01';

	switch ($_SESSION['login_id']) {
		case '1': $accessCond = ''; break;
		case '63':
		case '50':
		case '34': $accessCond = " and (tbl_giaoviec.created_by = '".$_SESSION['membername']."' OR tbl_giaoviec.member_id not in ('1'))"; break;
		default: $accessCond = " and (tbl_giaoviec.created_by = '".$_SESSION['membername']."' OR tbl_giaoviec.member_id = '".$_SESSION['login_id']."')";
	}
	$periodCond = " and SUBSTRING(tbl_giaoviec.ngay, 7, 4) = '".$year."'";
	if ($view == 'day') $periodCond .= " and LEFT(tbl_giaoviec.ngay, 10) = '".substr($selectedDate, 8, 2).'-'.substr($selectedDate, 5, 2).'-'.substr($selectedDate, 0, 4)."'";
	elseif ($view != 'year') $periodCond .= " and SUBSTRING(tbl_giaoviec.ngay, 4, 2) = '".$month."'";
	$visibleCond = ($active == 0) ? ' and tbl_giaoviec.active = 1' : '';
	$websiteCond = $website_id ? ' and tbl_giaoviec.website_id = '.$website_id : '';

	$stats = array();
	$sql = "select tbl_giaoviec.member_id, count(*) as total, sum(tbl_giaoviec.soluong = 1) as progress, sum(tbl_giaoviec.soluong = 2) as done from tbl_giaoviec where 1 $periodCond $accessCond $visibleCond $websiteCond group by tbl_giaoviec.member_id";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	while ($row = $db->sql_fetchrow($result)) $stats[(int)$row['member_id']] = $row;

	$selectedName = '';
	$selectedRole = '';
	$selectedDepartment = '';
	$sql = "select m.*, primary_dept.customer_type_name as primary_department, extra_dept.customer_type_name as extra_department from tbl_member m left join tbl_customer_type primary_dept on m.member_type_id = primary_dept.customer_type_id left join tbl_customer_type extra_dept on m.extra_member_type_id = extra_dept.customer_type_id where m.active = 1 and m.loginname <> 'administrator' order by m.fullname";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	while ($row = $db->sql_fetchrow($result)) {
		$currentMemberId = (int)$row['member_id'];
		$department = gvTaskDepartment($row['primary_department'], $row['extra_department']);
		$role = trim((string)$row['trach_nhiem']) ? $row['trach_nhiem'] : 'Nhân viên';
		$memberStats = isset($stats[$currentMemberId]) ? $stats[$currentMemberId] : array('total' => 0, 'progress' => 0, 'done' => 0);
		$template->assign_block_vars('member_list', array(
			'member_id' => $currentMemberId,
			'member_name' => gvTaskEscape($row['fullname']),
			'initials' => gvTaskEscape(gvTaskInitials($row['fullname'])),
			'role' => gvTaskEscape($role),
			'department' => gvTaskEscape($department),
			'total' => (int)$memberStats['total'],
			'progress' => (int)$memberStats['progress'],
			'done' => (int)$memberStats['done'],
		));
		if ($currentMemberId == $member_id) {
			$selectedName = $row['fullname'];
			$selectedRole = $role;
			$selectedDepartment = $department;
		}
	}

	if ($member_id > 0 && !$selectedName) {
		$sql = "select fullname, trach_nhiem from tbl_member where member_id = ".$member_id." limit 1";
		if (($result = $db->sql_query($sql)) && ($row = $db->sql_fetchrow($result))) {
			$selectedName = $row['fullname'];
			$selectedRole = trim((string)$row['trach_nhiem']) ? $row['trach_nhiem'] : 'Nhân viên';
			$selectedDepartment = 'Chưa phân phòng';
		}
	}

	if ($member_id > 0) {
		$sql = "select tbl_giaoviec.*, tbl_member.fullname, tbl_website.website_name from tbl_giaoviec left join tbl_member on tbl_giaoviec.member_id = tbl_member.member_id left join tbl_website on tbl_giaoviec.website_id = tbl_website.website_id where 1 $periodCond $accessCond $visibleCond $websiteCond and tbl_giaoviec.member_id = $member_id order by STR_TO_DATE(LEFT(tbl_giaoviec.ngay, 10), '%d-%m-%Y') asc, tbl_giaoviec.soluong asc, tbl_giaoviec.giaoviec_id asc";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		while ($row = $db->sql_fetchrow($result)) {
			$status = (int)$row['soluong'];
			$statusKey = $status == 1 ? 'progress' : ($status == 2 ? 'done' : 'todo');
			$deadlineIso = gvTaskIsoDate($row['ngay']);
			$deadlineText = $deadlineIso ? substr($deadlineIso, 8, 2).'/'.substr($deadlineIso, 5, 2).'/'.substr($deadlineIso, 0, 4).(strlen($row['ngay']) >= 16 ? ' '.substr($row['ngay'], 11, 5) : '') : $row['ngay'];
			$startIso = gvTaskIsoDate($row['ngay_bat_dau']);
			$template->assign_block_vars('list', array(
				'giaoviec_id' => (int)$row['giaoviec_id'],
				'giaoviec_name' => gvTaskEscape($row['giaoviec_name']),
				'giaoviec_num' => gvTaskEscape($row['giaoviec_num']),
				'quantity_hidden' => ((int)$row['giaoviec_num'] > 0) ? '' : 'hidden',
				'link_demo' => gvTaskEscape($row['link_demo']),
				'link_hidden' => trim((string)$row['link_demo']) ? '' : 'hidden',
				'status_key' => $statusKey,
				'status_value' => $status,
				'date_iso' => $deadlineIso,
				'deadline' => gvTaskEscape($deadlineText),
				'overdue' => ($deadlineIso && $deadlineIso < date('Y-m-d') && $status != 2) ? ' gv-overdue' : '',
				'website_name' => gvTaskEscape($row['website_name'] ? $row['website_name'] : 'Chưa chọn website'),
				'member_name' => gvTaskEscape($row['fullname'] ? $row['fullname'] : 'Chưa phân công'),
				'member_initials' => gvTaskEscape(gvTaskInitials($row['fullname'])),
				'can_delete' => strtolower($_SESSION['membername']) == 'administrator' ? '' : 'hidden',
				'parent_id' => (int)$row['parent_id'],
				'kpi_type' => gvTaskEscape($row['kpi_type']),
				'ngay' => gvTaskEscape($deadlineIso),
				'gio_deadline' => gvTaskEscape(strlen($row['ngay']) >= 16 ? substr($row['ngay'], 11, 5) : '23:59'),
				'ngay_bat_dau' => gvTaskEscape($startIso),
				'gio_bat_dau' => gvTaskEscape(strlen($row['ngay_bat_dau']) >= 16 ? substr($row['ngay_bat_dau'], 11, 5) : date('H:i')),
				'chitiet' => gvTaskEscape($row['chitiet']),
				'active' => (int)$row['active'],
				'member_id' => (int)$row['member_id'],
				'website_id' => (int)$row['website_id'],
			));
		}
	}

	$template->assign_vars(array(
		'member_id' => $member_id,
		'month' => $month,
		'year' => $year,
		'period' => $year.'-'.$month,
		'view' => $view,
		'task_toast' => $taskToast,
		'selected_date' => $selectedDate,
		'directory_filter_view' => $view == 'month' ? 'month' : 'day',
		'directory_day_active' => $view == 'month' ? '' : 'active',
		'directory_month_active' => $view == 'month' ? 'active' : '',
		'directory_day_pressed' => $view == 'month' ? 'false' : 'true',
		'directory_month_pressed' => $view == 'month' ? 'true' : 'false',
		'directory_day_hidden' => $view == 'month' ? 'hidden' : '',
		'directory_month_hidden' => $view == 'month' ? '' : 'hidden',
		'directory_hidden' => $member_id > 0 ? 'hidden' : '',
		'task_view_hidden' => $member_id > 0 ? '' : 'hidden',
		'selected_member_name' => gvTaskEscape($selectedName),
		'selected_member_initials' => gvTaskEscape(gvTaskInitials($selectedName)),
		'selected_member_meta' => gvTaskEscape(trim($selectedRole.' · '.$selectedDepartment, ' ·')),
	));
	$template->set_filenames_new(array('giaoviec' => 'common_lists/giaoviec/giaoviec_list.html'));
	$template->pparse('giaoviec');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo(){	
	global $db, $root_path, $skin, $languageid, $template;

	$giaoviec_id 	= mosGetParam($_REQUEST, 'id', 0);
	$parent_id	 	= mosGetParam($_REQUEST, 'parent_id', 0);
	$month   		= mosGetParam($_REQUEST, 'month', date('m'));
	$year			= mosGetParam($_REQUEST, 'year', date('Y'));
	$day			= gvTaskIsoDate(mosGetParam($_REQUEST, 'day', ''));
	$view			= mosGetParam($_REQUEST, 'view', 'day');
	$context_member_id = (int)mosGetParam($_REQUEST, 'member_id1', mosGetParam($_REQUEST, 'member_id', 0));
	if (!in_array($view, array('day', 'month', 'year'))) $view = 'day';
	$isAdmin = (isset($_SESSION["loginname"]) && $_SESSION["loginname"] == 'administrator');
	$showContentKpiType = gvKpiIsContentMember((int)$_SESSION["login_id"]) || $isAdmin;
	$showSalesKpiType = gvKpiIsSalesMember((int)$_SESSION["login_id"]) || $isAdmin;
	$showKpiType = $showContentKpiType || $showSalesKpiType;

	// ===== DS member =====
	$cond = 'and active = 1';
	$sql = "select * from tbl_member where 1 $cond";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	while ($row = $db->sql_fetchrow($result)){
		$template->assign_block_vars('member_list', array(
			'member_id'	 => $row['member_id'],
			'member_name'=> gvTaskEscape($row['fullname']),
			'selected'   => ((int)$row['member_id'] === $context_member_id) ? 'selected' : '',
		));
	}

	// ===== DS website =====
	$cond = (strtolower($_SESSION['membername'])=="administrator") ? '' : ' and active = 1'; 
	$sql = "select * from tbl_website where 1 $cond order by website_name";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	while ($row = $db->sql_fetchrow($result)){
		$template->assign_block_vars('website_list', array(
			'website_id'	  => $row['website_id'],
			'website_name' => gvTaskEscape($row['website_name']),
		));
	}

	// ===== Parent info =====
	$sql = "select * from tbl_giaoviec where giaoviec_id=$parent_id";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	if ($row = $db->sql_fetchrow($result)){
		$template->assign_vars(array(
			'parent_id'	 => $row['giaoviec_id'],
			'parent_name'=> gvTaskEscape($row['giaoviec_name']),
			'chitiet'	 => $row['chitiet'],
			'soluong'	 => $row['soluong'],
		));
	}

	// ===== Load task =====
	if ($giaoviec_id != 0){
		$sql = "select * from tbl_giaoviec where giaoviec_id = ".intval($giaoviec_id);
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		if ($row = $db->sql_fetchrow($result)){

			// --- created_date (datetime) -> dd-mm-YYYY
			$created = $row['created_date']; // yyyy-mm-dd HH:ii:ss
			$y = substr($created, 0, 4);
			$m = substr($created, 5, 2);
			$d = substr($created, 8, 2);
			$ngay_bat_dau_default = "$d-$m-$y";

			// --- ngay_bat_dau stored as "dd-mm-YYYY HH:MM" OR "dd-mm-YYYY"
			$nbd_raw = trim((string)$row['ngay_bat_dau']);
			$nbd_date = $ngay_bat_dau_default;
			$nbd_time = date('H:i');

			if ($nbd_raw != ''){
				$nbd_date = substr($nbd_raw, 0, 10);
				if (strlen($nbd_raw) >= 16) $nbd_time = substr($nbd_raw, 11, 5);
			}

			// --- deadline ngay stored as "dd-mm-YYYY HH:MM" OR "dd-mm-YYYY"
			$dl_raw = trim((string)$row['ngay']);
			$dl_date = ($dl_raw != '') ? substr($dl_raw, 0, 10) : date('d-m-Y');
			$dl_time = (strlen($dl_raw) >= 16) ? substr($dl_raw, 11, 5) : '23:59';

			$template->assign_vars(array(
				'form_title' => 'Cập nhật công việc',
				'giaoviec_id'	=> $giaoviec_id,
				'giaoviec_name'	=> gvTaskEscape($row['giaoviec_name']),
				'link_demo'     => gvTaskEscape($row['link_demo']),
				'giaoviec_num'	=> $row['giaoviec_num'],
				'kpi_type'	    => isset($row['kpi_type']) ? $row['kpi_type'] : '',
				'kpi_type_display' => $showKpiType ? '' : 'none',
				'content_kpi_option_display' => $showContentKpiType ? '' : 'none',
				'sales_kpi_option_display' => $showSalesKpiType ? '' : 'none',

				// date + time split for UI
				'ngay'		  	=> gvTaskIsoDate($dl_date),
				'gio_deadline'  => $dl_time,

				'ngay_bat_dau'	=> gvTaskIsoDate($nbd_date),
				'gio_bat_dau'   => $nbd_time,

				'chitiet'		=> gvTaskEscape($row['chitiet']),
				'soluong'		=> $row['soluong'],
				'parent_id' 	=> $row['parent_id'],
				'active'		=> ($row['active'] == 1) ? 'checked' : '',

				'member_id'		=> $row['member_id'],
				'website_id'	=> $row['website_id'],

				'created_date' 	=> $row['created_date'],
				'created_by'	=> $row['created_by'],
				'last_modified'	=> $row['last_modified'],
				'modified_by'	=> $row['modified_by'],

				'allow_edit'	=> ($_SESSION["login_id"] != $row['created_by_id']) ? 'disabled="disabled"' : '',
			));

		} else message_die(ID_NOTFOUND);

	} else {
		$defaultTaskDate = $day ? $day : date("Y-m-d");
		$template->assign_vars(array(
			'form_title' => 'Thêm công việc',
			'giaoviec_id' => 0,
			'active'	   => 'checked',
			'allow'        => 'hidden',
			'member_id'    => $context_member_id,
			'website_id'   => 0,
			'soluong'      => 0,
			'parent_id'    => 0,
			'kpi_type'	   => '',
			'kpi_type_display' => $showKpiType ? '' : 'none',
			'content_kpi_option_display' => $showContentKpiType ? '' : 'none',
			'sales_kpi_option_display' => $showSalesKpiType ? '' : 'none',

			'ngay'		   => $defaultTaskDate,
			'gio_deadline' => '23:59',

			'ngay_bat_dau' => $defaultTaskDate,
			'gio_bat_dau'  => date("H:i"),
		));
	}

	// ===== DS Viec Cha =====
	switch ($_SESSION["login_id"]){
		case '1':  $cond = ""; break;
		case '52':
		case '34': $cond = "and (tbl_giaoviec.created_by = '".$_SESSION['membername']."' OR tbl_giaoviec.member_id not in ('1'))"; break;
		case '45': $cond = "and (tbl_giaoviec.created_by = '".$_SESSION['membername']."' OR tbl_giaoviec.member_id in ('45','42','47','50','29','63','48','51'))"; break;
		default:   $cond = " and (tbl_giaoviec.created_by = '".$_SESSION['membername']."' OR tbl_giaoviec.member_id = '".$_SESSION["login_id"]."')";
	}

	$sql = "select tbl_giaoviec.*, SUBSTRING(tbl_giaoviec.ngay, 7, 4) as y, SUBSTRING(tbl_giaoviec.ngay, 4, 2) as m , SUBSTRING(tbl_giaoviec.ngay, 1, 2) as d
			,tbl_member.fullname, tbl_website.website_name
			from (tbl_giaoviec left join tbl_member on tbl_giaoviec.member_id = tbl_member.member_id)
			left join tbl_website on tbl_giaoviec.website_id = tbl_website.website_id
			where tbl_giaoviec.soluong <> '2' and tbl_giaoviec.parent_id = 0
			and SUBSTRING(tbl_giaoviec.ngay, 4, 2) = '$month' and SUBSTRING(tbl_giaoviec.ngay, 7, 4) = '$year'
			$cond
			order by y DESC, m DESC, d DESC, soluong, giaoviec_id";

	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	$order = 0; $today = date("Y-m-d");
	while ($row = $db->sql_fetchrow($result)){
		$order += 1;
		$n = $row['ngay'];
		$ngay_tam = substr($n, 6, 4)."-".substr($n, 3, 2)."-".substr($n, 0, 2);
		$war = (strtotime($today) > strtotime($ngay_tam) && $row['soluong'] != 2) ? ' ⚠️' : '';

		$template->assign_block_vars('cha_list', array(
			'giaoviec_id'	=> $row['giaoviec_id'],
			'giaoviec_name'	=> gvTaskEscape($row['giaoviec_name']),
			//'giaoviec_num'	=> ($row['giaoviec_num']) ? "(<font color='blue'><b>".$row['giaoviec_num']."</b></font>)" : "",
			'ngay'		    => $row['ngay'].$war,
			'chitiet'		=> $row['chitiet'],
			'soluong'		=> ($row['soluong'] == 0) ? "Chưa Thực Hiện" : (($row['soluong'] == 1) ? "<font color='orange'><b>Đang Thực Hiện</b></font>" : "<font color='blue'><b>Đã Xong</b></font>"),
		));
	}

	$template->assign_vars(array(
		'month' => $month,
		'year' => $year,
		'day' => $day,
		'view' => $view,
		'context_member_id' => $context_member_id,
	));
	$template->set_filenames_new(array(
		'share' => 'common_lists/giaoviec/giaoviec_info.html'
	));
	$template->pparse('share');
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove( $direction )
	{	
		global $languageid;
		$giaoviec_id    = mosGetParam( $_REQUEST, 'id', '');
	
		if ($giaoviec_id == 0)
		{	mosInvalidURL();
			exit;
		}
		mosChangePriority( $giaoviec_id, $direction, "tbl_giaoviec", "giaoviec_id", "priority");
		mosList(0);
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave(){		
	global $db, $root_path, $skin, $languageid, $template;	

	$giaoviec_id   = mosGetParam($_REQUEST, 'id', '0');
	$parent_id     = mosGetParam($_REQUEST, 'parent_id', '0');
	$giaoviec_name = mosGetParam($_REQUEST, 'giaoviec_name', '');
	$link_demo     = mosGetParam($_REQUEST, 'link_demo', '');
	$giaoviec_num  = mosGetParam($_REQUEST, 'giaoviec_num', '0');
	$kpi_type      = mosGetParam($_REQUEST, 'kpi_type', '');

	$ngay          = mosGetParam($_REQUEST, 'ngay', '');             // dd-mm-YYYY
	$ngay_bat_dau  = mosGetParam($_REQUEST, 'ngay_bat_dau', '');     // dd-mm-YYYY
	$ngay          = gvTaskStorageDate($ngay);
	$ngay_bat_dau  = gvTaskStorageDate($ngay_bat_dau);

	$gio_deadline  = mosGetParam($_REQUEST, 'gio_deadline', '');     // HH:MM
	$gio_bat_dau   = mosGetParam($_REQUEST, 'gio_bat_dau', '');      // HH:MM

	$chitiet       = mosGetParam($_REQUEST, 'chitiet', '', 0x0003);
	$soluong       = mosGetParam($_REQUEST, 'soluong', 0);
	$active        = mosGetParam($_REQUEST, 'active', 0);
	$member_id     = mosGetParam($_REQUEST, 'member_id', 0);
	if (!(int)$member_id) $member_id = mosGetParam($_REQUEST, 'member_id1', 0);
	$website_id    = mosGetParam($_REQUEST, 'website_id', 0);
	$isAdmin = (isset($_SESSION["loginname"]) && $_SESSION["loginname"] == 'administrator');
	$isContentMember = gvKpiIsContentMember((int)$_SESSION["login_id"]) || $isAdmin;
	$isSalesMember = gvKpiIsSalesMember((int)$_SESSION["login_id"]) || $isAdmin;
	if (gvKpiIsContentTaskType($kpi_type) && !$isContentMember) $kpi_type = '';
	if ($kpi_type == 'tim_khach_hang' && !$isSalesMember) $kpi_type = '';
	if (!gvKpiIsContentTaskType($kpi_type) && $kpi_type != 'tim_khach_hang') $kpi_type = '';

	if ($giaoviec_id === ''){
		mosInvalidURL();
		exit;
	}

	if ($isContentMember && gvKpiIsContentTaskType($kpi_type) && (int)$soluong == 2) {
		$missingKpiFields = array();
		if (trim($giaoviec_name) == '') $missingKpiFields[] = 'Tiêu đề';
		if ((int)$giaoviec_num <= 0) $missingKpiFields[] = 'Số lượng';
		if (trim($link_demo) == '') $missingKpiFields[] = 'Link hoàn thành';
		if (count($missingKpiFields) > 0) {
			reShowPage('Chưa thể chuyển Đã xong. Vui lòng nhập đủ: '.implode(', ', $missingKpiFields));
			return;
		}
	}
	if ($isSalesMember && $kpi_type == 'tim_khach_hang' && (int)$soluong == 2) {
		$missingKpiFields = array();
		if (trim($giaoviec_name) == '') $missingKpiFields[] = 'Tiêu đề';
		if ((int)$giaoviec_num <= 0) $missingKpiFields[] = 'Số lượng khách hàng';
		if (count($missingKpiFields) > 0) {
			reShowPage('Chưa thể chuyển Đã xong. Vui lòng nhập đủ: '.implode(', ', $missingKpiFields));
			return;
		}
	}

	date_default_timezone_set('Asia/Ho_Chi_Minh');

	// ===== ghép ngày + giờ để lưu =====
	$gio_bat_dau  = $gio_bat_dau  ? $gio_bat_dau  : date('H:i');
	$gio_deadline = $gio_deadline ? $gio_deadline : '23:59';

	if ($ngay_bat_dau) $ngay_bat_dau = trim($ngay_bat_dau).' '.$gio_bat_dau;  // dd-mm-YYYY HH:MM
	if ($ngay)         $ngay         = trim($ngay).' '.$gio_deadline;        // dd-mm-YYYY HH:MM

	// ================== CREATE NEW TASK ==================
	if ($giaoviec_id == '0'){

		// created_date: lấy từ ngày bắt đầu (nếu có) -> yyyy-mm-dd HH:ii:ss
		$created_datetime = date('Y-m-d H:i:s');
		if (!empty($ngay_bat_dau) && strlen($ngay_bat_dau) >= 16){
			$d = substr($ngay_bat_dau, 0, 2);
			$m = substr($ngay_bat_dau, 3, 2);
			$y = substr($ngay_bat_dau, 6, 4);
			$hhmm = substr($ngay_bat_dau, 11, 5);
			$created_datetime = $y.'-'.$m.'-'.$d.' '.$hhmm.':00';
		}

		$priority = mosGetPriority("tbl_giaoviec", "priority", "");

		$sql = "
			INSERT INTO tbl_giaoviec
			(parent_id, giaoviec_name, link_demo, giaoviec_num, kpi_type, ngay, ngay_bat_dau, chitiet, soluong, active,
			 priority, language_id, created_date, last_modified, created_by, modified_by,
			 member_id, website_id, created_by_id)
			VALUES
			(".intval($parent_id).", '".$giaoviec_name."', '".$link_demo."', '".$giaoviec_num."', '".$kpi_type."', '".$ngay."', '".$ngay_bat_dau."', '".$chitiet."',
			 '".$soluong."', ".intval($active).", ".intval($priority).", ".intval($languageid).", '".$created_datetime."', NOW(),
			 '".$_SESSION['membername']."', '".$_SESSION['membername']."',
			 '".intval($member_id)."', '".intval($website_id)."', '".intval($_SESSION["login_id"])."')
		";

		if (!$db->sql_query($sql)) message_die(SERVER_BUSY);
		$newTaskId = 0;
		if ($resultNewId = $db->sql_query("SELECT LAST_INSERT_ID() AS new_id")) {
			if ($rowNewId = $db->sql_fetchrow($resultNewId)) $newTaskId = (int)$rowNewId['new_id'];
		}
		if ($newTaskId <= 0 && function_exists('mysql_insert_id')) $newTaskId = (int)mysql_insert_id();
		$taskLink = 'main.php?option=common_lists/giaoviec&mode=info&id='.$newTaskId;

		if ((int)$member_id > 0) {
			notificationCreate(
				(int)$member_id,
				'task_assigned',
				'Bạn được giao việc mới',
				$giaoviec_name.' - Deadline: '.$ngay,
				$taskLink,
				(int)$_SESSION["login_id"]
			);
		}

		// ================== SEND ZALO TO ASSIGNEE ==================
		if ((int)$member_id > 0) {

			$sqlMember = "
				SELECT fullname, zalo_user_id
				FROM tbl_member
				WHERE member_id = '".intval($member_id)."'
				LIMIT 1
			";
			$rs = $db->sql_query($sqlMember);
			$member = $db->sql_fetchrow($rs);

			if (!empty($member['zalo_user_id'])) {

				$websiteName = '';
				if ((int)$website_id > 0) {
					$sqlWeb = "
						SELECT website_name
						FROM tbl_website
						WHERE website_id = '".intval($website_id)."'
						LIMIT 1
					";
					$rw = $db->sql_query($sqlWeb);
					if ($rw) {
						$web = $db->sql_fetchrow($rw);
						if (!empty($web['website_name'])) $websiteName = $web['website_name'];
					}
				}
				$websiteText = $websiteName ? $websiteName : ('ID: '.$website_id);

				$message =
					"📌 BẠN ĐƯỢC GIAO VIỆC MỚI\n\n".
					"📝 Việc: {$giaoviec_name}\n".
					"⏰ Deadline: {$ngay}\n".
					"🌐 Website: {$websiteText}\n".
					"👤 Giao bởi: ".$_SESSION['membername'];

				try {
					require_once dirname(__FILE__).'/../../zalo/services/ZaloService.php';
					$zalo = new ZaloService();
					$res = $zalo->sendText($member['zalo_user_id'], $message);
				} catch (Throwable $e) {
					$res = array('error' => $e->getMessage());
				}

				gvZaloLog("CREATE_TASK | ".json_encode($res, JSON_UNESCAPED_UNICODE));

			} else {
				gvZaloLog("NO_ZALO_USER_ID | member_id={$member_id}");
			}
		}

	} else {

		// ================== UPDATE TASK ==================
		$sqlOld = "SELECT * FROM tbl_giaoviec WHERE giaoviec_id = ".intval($giaoviec_id)." LIMIT 1";
		if (!$resultOld = $db->sql_query($sqlOld)) message_die(SERVER_BUSY);
		$rowOld = $db->sql_fetchrow($resultOld);
		if (!$rowOld) message_die(ID_NOTFOUND);
		if (
			(!$isContentMember && gvKpiIsContentTaskType($rowOld['kpi_type'])) ||
			(!$isSalesMember && $rowOld['kpi_type'] == 'tim_khach_hang')
		) {
			$kpi_type = isset($rowOld['kpi_type']) ? $rowOld['kpi_type'] : '';
		}

		$old_status = (int)$rowOld['soluong'];
		$new_status = (int)$soluong;

		$actor_login_id  = (int)(isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : 0);
		$actor_member_id = (int)(isset($_SESSION["member_id"]) ? $_SESSION["member_id"] : 0);

		$shouldNotifyDone = (
			$old_status != 2 &&
			$new_status == 2 &&
			(
				(int)$rowOld['member_id'] == $actor_member_id ||
				(int)$rowOld['member_id'] == $actor_login_id
			)
		);

		gvZaloLog("DONE_CHECK | old={$old_status} new={$new_status} ".
			"assignee={$rowOld['member_id']} actor_login={$actor_login_id} actor_member={$actor_member_id} ".
			"created_by_id={$rowOld['created_by_id']} should=".($shouldNotifyDone?1:0));

		// Update theo quyền
		if ($_SESSION["login_id"] != $rowOld['created_by_id']) {

			$sqlUpdate = "
				UPDATE tbl_giaoviec SET
					chitiet = '".$chitiet."',
					soluong = '".$soluong."',
					giaoviec_num = '".$giaoviec_num."',
					kpi_type = '".$kpi_type."',
					active = ".intval($active).",
					language_id = ".intval($languageid).",
					last_modified = NOW(),
					modified_by = '".$_SESSION['membername']."'
				WHERE giaoviec_id = ".intval($giaoviec_id)."
			";

		} else {

			// created_date giữ logic cũ: lấy từ ngày_bat_dau (nếu có) -> yyyy-mm-dd HH:ii:ss
			$created_datetime = $rowOld['created_date'];
			if (!empty($ngay_bat_dau) && strlen($ngay_bat_dau) >= 16){
				$d = substr($ngay_bat_dau, 0, 2);
				$m = substr($ngay_bat_dau, 3, 2);
				$y = substr($ngay_bat_dau, 6, 4);
				$hhmm = substr($ngay_bat_dau, 11, 5);
				$created_datetime = $y.'-'.$m.'-'.$d.' '.$hhmm.':00';
			}

			$sqlUpdate = "
				UPDATE tbl_giaoviec SET
					giaoviec_name = '".$giaoviec_name."',
					link_demo = '".$link_demo."',
					giaoviec_num = '".$giaoviec_num."',
					kpi_type = '".$kpi_type."',
					chitiet = '".$chitiet."',
					soluong = '".$soluong."',
					active = ".intval($active).",
					language_id = ".intval($languageid).",
					created_date = '".$created_datetime."',
					last_modified = NOW(),
					modified_by = '".$_SESSION['membername']."',
					website_id = '".intval($website_id)."',
					parent_id = '".intval($parent_id)."',
					ngay = '".$ngay."',
					ngay_bat_dau = '".$ngay_bat_dau."'
				WHERE giaoviec_id = ".intval($giaoviec_id)."
			";
		}

		if (!$db->sql_query($sqlUpdate)) message_die(SERVER_BUSY);
		$taskLink = 'main.php?option=common_lists/giaoviec&mode=info&id='.intval($giaoviec_id);

		if ((int)$member_id > 0 && (int)$member_id != (int)$rowOld['member_id']) {
			notificationCreate(
				(int)$member_id,
				'task_assigned',
				'Bạn được giao việc mới',
				$giaoviec_name.' - Deadline: '.$ngay,
				$taskLink,
				(int)$_SESSION["login_id"]
			);
		}

		// ================== SEND ZALO TO CREATOR WHEN DONE ==================
		if ($shouldNotifyDone) {
			if ((int)$rowOld['created_by_id'] > 0) {
				notificationCreate(
					(int)$rowOld['created_by_id'],
					'task_done',
					'Công việc đã hoàn thành',
					$_SESSION['membername'].' đã hoàn thành: '.$rowOld['giaoviec_name'],
					$taskLink,
					(int)$_SESSION["login_id"]
				);
			}

			$boss = null;

			$sqlBoss1 = "
				SELECT fullname, zalo_user_id
				FROM tbl_member
				WHERE member_id = '".intval($rowOld['created_by_id'])."'
				LIMIT 1
			";
			$rb1 = $db->sql_query($sqlBoss1);
			if ($rb1) $boss = $db->sql_fetchrow($rb1);

			if (!$boss || empty($boss['zalo_user_id'])) {
				$sqlBoss2 = "
					SELECT fullname, zalo_user_id
					FROM tbl_member
					WHERE login_id = '".intval($rowOld['created_by_id'])."'
					LIMIT 1
				";
				$rb2 = $db->sql_query($sqlBoss2);
				if ($rb2) $boss = $db->sql_fetchrow($rb2);
			}

			if (!empty($boss['zalo_user_id'])) {

				$websiteName = '';
				if ((int)$rowOld['website_id'] > 0) {
					$sqlWeb = "
						SELECT website_name
						FROM tbl_website
						WHERE website_id = '".intval($rowOld['website_id'])."'
						LIMIT 1
					";
					$rw = $db->sql_query($sqlWeb);
					if ($rw) {
						$web = $db->sql_fetchrow($rw);
						if (!empty($web['website_name'])) $websiteName = $web['website_name'];
					}
				}
				$websiteText = $websiteName ? $websiteName : ('ID: '.$rowOld['website_id']);

				$actorName = $_SESSION['membername'];

				$msg =
					"✅ VIỆC ĐÃ HOÀN THÀNH\n\n".
					"📝 Việc: ".$rowOld['giaoviec_name']."\n".
					"⏰ Deadline: ".$rowOld['ngay']."\n".
					"🌐 Website: ".$websiteText."\n".
					"👤 Hoàn thành bởi: ".$actorName;

				try {
					require_once dirname(__FILE__).'/../../zalo/services/ZaloService.php';
					$zalo = new ZaloService();
					$res = $zalo->sendText($boss['zalo_user_id'], $msg);
				} catch (Throwable $e) {
					$res = array('error' => $e->getMessage());
				}

				gvZaloLog("DONE_NOTIFY | giaoviec_id=".$rowOld['giaoviec_id']." | ".json_encode($res, JSON_UNESCAPED_UNICODE));

			} else {
				gvZaloLog("DONE_NOTIFY_NO_BOSS_ZALO | created_by_id=".$rowOld['created_by_id']." | giaoviec_id=".$rowOld['giaoviec_id']);
			}
		}
	}

	if ($giaoviec_id == '0') {
		$savedDate = gvTaskIsoDate($ngay);
		if ($savedDate) {
			$_REQUEST['day'] = $savedDate;
			$_REQUEST['month'] = substr($savedDate, 5, 2);
			$_REQUEST['year'] = substr($savedDate, 0, 4);
		}
		if ((int)$member_id > 0) $_REQUEST['member_id1'] = (int)$member_id;
	}
	if (mosGetParam($_REQUEST, 'ajax_status', 0)) {
		echo 'GV_STATUS_SAVED';
		exit;
	}
	$_REQUEST['task_toast'] = 'saved';
	$template->assign_vars(['MESSAGE' => '']);
	mosList($parent_id);
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function mosDelete()
	{	
		global $template, $db;	
		$giaoviec_id = mosGetParam( $_REQUEST, 'id', '0');
		if ($giaoviec_id == 0)
		{	mosInvalidURL();
			exit;
		}
		$sql = "select * from tbl_giaoviec where giaoviec_id = '$giaoviec_id'";
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		if ( $row = $db->sql_fetchrow($result))
			$parent_id = $row['parent_id'];
		$sql1 = "select count(*) as child_count from tbl_giaoviec where parent_id = '$giaoviec_id'";
		if ( !($result1 = $db->sql_query($sql1)) ) message_die( SERVER_BUSY );
		if ( $row1 = $db->sql_fetchrow($result1))		
		{	if (($row1['child_count'] == 0)){	
        if(strtolower($_SESSION['membername'])=="administrator"){	
			    deleteByID("tbl_giaoviec", "giaoviec_id", $giaoviec_id);
			  $_REQUEST['task_toast'] = 'deleted';
          $template->assign_vars(array('MESSAGE'	=>	''));
		    }else{
				  $_REQUEST['task_toast'] = 'delete_blocked';
				  $template->assign_vars(array('MESSAGE'	=>	''));
			  }
		}else{	
		  $_REQUEST['task_toast'] = 'delete_blocked';
      $template->assign_vars(array('MESSAGE' => ''));	}
		} 	
		mosList($parent_id);
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	
	function reShowPage( $message )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;				
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$isAdmin = (isset($_SESSION["loginname"]) && $_SESSION["loginname"] == 'administrator');
		$showContentKpiType = gvKpiIsContentMember((int)$_SESSION["login_id"]) || $isAdmin;
		$showSalesKpiType = gvKpiIsSalesMember((int)$_SESSION["login_id"]) || $isAdmin;
		$showKpiType = $showContentKpiType || $showSalesKpiType;
		$template->assign_vars(array(
			'giaoviec_name'  =>	mosGetParam( $_REQUEST, 'giaoviec_name', ''),
			'link_demo' 		=>	mosGetParam( $_REQUEST, 'link_demo', ''),
			'giaoviec_num'   =>	mosGetParam( $_REQUEST, 'giaoviec_num', ''),
			'kpi_type'       =>	mosGetParam( $_REQUEST, 'kpi_type', ''),
			'kpi_type_display' => $showKpiType ? '' : 'none',
			'content_kpi_option_display' => $showContentKpiType ? '' : 'none',
			'sales_kpi_option_display' => $showSalesKpiType ? '' : 'none',
			'ngay' 	         =>	mosGetParam( $_REQUEST, 'ngay', ''),
			'gio_deadline'   =>	mosGetParam( $_REQUEST, 'gio_deadline', '23:59'),
			'ngay_bat_dau'   =>	mosGetParam( $_REQUEST, 'ngay_bat_dau', date("d-m-Y")),
			'gio_bat_dau'    =>	mosGetParam( $_REQUEST, 'gio_bat_dau', date("H:i")),
			'chitiet'		     =>	mosGetParam( $_REQUEST, 'chitiet', ''),
			'soluong'		     =>	mosGetParam( $_REQUEST, 'soluong', 0),			
			'MESSAGE'			   =>	$message,
			'giaoviec_id'	   =>	$id,
			'member_id'		   =>	mosGetParam( $_REQUEST, 'member_id', 0),
			'website_id'		 =>	mosGetParam( $_REQUEST, 'website_id', 0),
			'parent_id'		   =>	mosGetParam( $_REQUEST, 'parent_id', 0),
			'active'		   =>	mosGetParam( $_REQUEST, 'active', 0) ? 'checked' : '',
		));
		$template->set_filenames_new(array(
			'giaoviec' => 'common_lists/giaoviec/giaoviec_info.html')
		);
		
		$template->pparse('giaoviec');	
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	
	function mosThuchien( )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;		
		$id   	= mosGetParam( $_REQUEST, 'id', '0');
		$sql = "insert into tbl_thuchien (member_id, giaoviec_id, ngay) values (".$_SESSION["login_id"].", '$id', CURDATE())";	
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		mosList(0);	
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	function gvKpiContentDepartmentId()
	{
		global $db;
		$sql = "select customer_type_id from tbl_customer_type where lower(customer_type_name) = 'content' limit 1";
		if ($result = $db->sql_query($sql)) {
			if ($row = $db->sql_fetchrow($result)) return (int)$row['customer_type_id'];
		}
		return 0;
	}

	function gvKpiIsContentMember($memberId)
	{
		global $db;
		$contentDepartmentId = gvKpiContentDepartmentId();
		if ($contentDepartmentId <= 0) return false;
		$sql = "select member_id from tbl_member where active = 1 and member_id = ".(int)$memberId." and (member_type_id = ".$contentDepartmentId." or extra_member_type_id = ".$contentDepartmentId.") limit 1";
		if ($result = $db->sql_query($sql)) {
			if ($db->sql_fetchrow($result)) return true;
		}
		return false;
	}

	function gvKpiContentTeamIds()
	{
		global $db;
		$ids = array();
		$contentDepartmentId = gvKpiContentDepartmentId();
		if ($contentDepartmentId <= 0) return $ids;
		$sql = "select member_id from tbl_member where active = 1 and (member_type_id = ".$contentDepartmentId." or extra_member_type_id = ".$contentDepartmentId.") order by fullname";
		if ($result = $db->sql_query($sql)) {
			while ($row = $db->sql_fetchrow($result)) {
				$ids[] = (int)$row['member_id'];
			}
		}
		return $ids;
	}

	function gvKpiSalesDepartmentId()
	{
		global $db;
		$sql = "select customer_type_id from tbl_customer_type where lower(customer_type_name) = 'kinh doanh' limit 1";
		if ($result = $db->sql_query($sql)) {
			if ($row = $db->sql_fetchrow($result)) return (int)$row['customer_type_id'];
		}
		return 0;
	}

	function gvKpiIsSalesMember($memberId)
	{
		global $db;
		$departmentId = gvKpiSalesDepartmentId();
		if ($departmentId <= 0) return false;
		$sql = "select member_id from tbl_member where active = 1 and member_id = ".(int)$memberId." and (member_type_id = ".$departmentId." or extra_member_type_id = ".$departmentId.") limit 1";
		if ($result = $db->sql_query($sql)) {
			if ($db->sql_fetchrow($result)) return true;
		}
		return false;
	}

	function gvKpiIsContentViewer($teamIds)
	{
		$loginId = (int)(isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : 0);
		if (isset($_SESSION["loginname"]) && $_SESSION["loginname"] == 'administrator') return true;
		if (in_array($loginId, gvKpiOverviewMemberIds())) return true;
		return in_array($loginId, $teamIds);
	}

	function gvKpiOverviewMemberIds()
	{
		return array(34,50,71);
	}

	function gvKpiCanViewContentOverview()
	{
		if (isset($_SESSION["loginname"]) && $_SESSION["loginname"] == 'administrator') return true;
		$loginId = (int)(isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : 0);
		return in_array($loginId, gvKpiOverviewMemberIds());
	}

	function gvKpiMonthTarget($month, $year)
	{
		$days = (int)date('t', strtotime(sprintf('%04d-%02d-01', $year, $month)));
		$weekdayTarget = 0;
		$saturdayTarget = 0;
		for ($day = 1; $day <= $days; $day++) {
			$weekDay = (int)date('N', strtotime(sprintf('%04d-%02d-%02d', $year, $month, $day)));
			if ($weekDay >= 1 && $weekDay <= 5) $weekdayTarget += 4;
			if ($weekDay == 6) $saturdayTarget += 2;
		}
		return array($weekdayTarget + $saturdayTarget, $weekdayTarget, $saturdayTarget, $days);
	}

	function gvKpiPercent($done, $target)
	{
		if ($target <= 0) return 0;
		return round(($done / $target) * 100, 1);
	}

	function gvKpiStatusClass($percent)
	{
		if ($percent >= 100) return 'kpi-ok';
		if ($percent >= 80) return 'kpi-warn';
		return 'kpi-danger';
	}

	function gvKpiContentTaskTypes()
	{
		return array('viet_content', 'audit_content', 'nghien_cuu_tu_khoa');
	}

	function gvKpiIsContentTaskType($type)
	{
		return in_array($type, gvKpiContentTaskTypes());
	}

	function gvKpiContentTaskTypesSql()
	{
		$quoted = array();
		foreach (gvKpiContentTaskTypes() as $type) $quoted[] = "'".addslashes($type)."'";
		return implode(',', $quoted);
	}

	function gvKpiContentDayTarget($workDate)
	{
		$weekDay = (int)date('N', strtotime($workDate));
		if ($weekDay >= 1 && $weekDay <= 5) return 4;
		if ($weekDay == 6) return 2;
		return 0;
	}

	function gvKpiContentIsCompanyWorkExempt($status, $note)
	{
		$text = mb_strtolower(trim($status.' '.$note), 'UTF-8');
		$keywords = array(
			'đi gặp khách hàng',
			'gap khach hang',
			'gặp khách hàng',
			'đi gửi hợp đồng',
			'di gui hop dong',
			'gửi hợp đồng',
			'gui hop dong',
			'đi công tác',
			'di cong tac',
			'công tác',
			'cong tac',
			'đi làm việc công ty',
			'di lam viec cong ty',
			'làm việc bên ngoài',
			'lam viec ben ngoai',
			'ra ngoài làm việc',
			'ra ngoai lam viec'
		);
		foreach ($keywords as $keyword) {
			if ($keyword != '' && mb_strpos($text, $keyword, 0, 'UTF-8') !== false) return true;
		}
		return false;
	}

	function gvKpiContentAttendanceRowIsLeave($row)
	{
		$checkIn = isset($row['check_in']) ? trim($row['check_in']) : '';
		$checkOut = isset($row['check_out']) ? trim($row['check_out']) : '';
		$status = isset($row['status']) ? trim($row['status']) : '';
		$note = isset($row['note']) ? trim($row['note']) : '';
		if (gvKpiContentIsCompanyWorkExempt($status, $note)) return false;

		$hasCheckIn = ($checkIn != '' && $checkIn != '-');
		$hasCheckOut = ($checkOut != '' && $checkOut != '-');
		if (!$hasCheckIn || !$hasCheckOut) return true;
		return false;
	}

	function gvKpiContentLeaveDaysMap($memberIds, $month, $year, $daysInMonth)
	{
		global $db;
		$leaveDays = array();
		if (empty($memberIds)) return $leaveDays;
		$teamSql = implode(',', array_map('intval', $memberIds));

		$sql = "
			select member_id, CAST(SUBSTRING(ngay, 1, 2) AS UNSIGNED) as day_num
			from tbl_nghiphep
			where active = 1 and member_id in (".$teamSql.")
				and SUBSTRING(ngay, 4, 2) = '".sprintf('%02d', $month)."'
				and SUBSTRING(ngay, 7, 4) = '".$year."'
		";
		if ($result = $db->sql_query($sql)) {
			while ($row = $db->sql_fetchrow($result)) {
				$memberId = (int)$row['member_id'];
				$day = (int)$row['day_num'];
				if (!isset($leaveDays[$memberId])) $leaveDays[$memberId] = array();
				$leaveDays[$memberId][$day] = true;
			}
		}

		$attendanceUsers = array();
		$sql = "select member_id, attendance_user_id from tbl_member where active = 1 and member_id in (".$teamSql.")";
		if ($result = $db->sql_query($sql)) {
			while ($row = $db->sql_fetchrow($result)) {
				$attendanceUserId = trim($row['attendance_user_id']);
				if ($attendanceUserId != '') $attendanceUsers[(int)$row['member_id']] = $attendanceUserId;
			}
		}

		if (count($attendanceUsers) <= 0) return $leaveDays;

		$userIds = array();
		foreach ($attendanceUsers as $attendanceUserId) $userIds[] = "'".addslashes($attendanceUserId)."'";
		$sql = "
			select *
			from tbl_chamcong
			where MONTH(work_date) = ".$month."
				and YEAR(work_date) = ".$year."
				and TRIM(user_id) in (".implode(',', $userIds).")
		";
		$attendanceMap = array();
		if ($result = $db->sql_query($sql)) {
			while ($row = $db->sql_fetchrow($result)) {
				$userId = trim($row['user_id']);
				if (!isset($attendanceMap[$userId])) $attendanceMap[$userId] = array();
				$attendanceMap[$userId][$row['work_date']] = $row;
			}
		}

		foreach ($attendanceUsers as $memberId => $attendanceUserId) {
			for ($day = 1; $day <= $daysInMonth; $day++) {
				$workDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
				$weekDay = (int)date('N', strtotime($workDate));
				if ($weekDay == 7) continue;
				if (strtotime($workDate) >= strtotime(date('Y-m-d'))) continue;
				$isLeave = false;
				if (!isset($attendanceMap[$attendanceUserId]) || !isset($attendanceMap[$attendanceUserId][$workDate])) {
					$isLeave = true;
				} else {
					$isLeave = gvKpiContentAttendanceRowIsLeave($attendanceMap[$attendanceUserId][$workDate]);
				}
				if ($isLeave) {
					if (!isset($leaveDays[$memberId])) $leaveDays[$memberId] = array();
					$leaveDays[$memberId][$day] = true;
				}
			}
		}

		return $leaveDays;
	}

		function gvKpiContentMemberTargetMap($memberIds, $month, $year, $daysInMonth, $leaveDays)
		{
			$targets = array();
			foreach ($memberIds as $memberId) {
				$memberId = (int)$memberId;
				$targets[$memberId] = 0;
				for ($day = 1; $day <= $daysInMonth; $day++) {
					$workDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
					$targets[$memberId] += gvKpiContentDayTarget($workDate);
				}
		}
		return $targets;
	}

	function mosDashboardKpiContent()
	{
		global $db, $languageid, $template;

		$teamIds = gvKpiContentTeamIds();
		if (!gvKpiIsContentViewer($teamIds)) {
			$template->assign_vars(array(
				'funname' => 'common_lists/giaoviec',
				'LANGUAGEID' => $languageid,
				'MESSAGE' => 'Bạn không thuộc team Content nên không có quyền xem dashboard KPI Content.',
				'MESSAGE_DISPLAY' => 'block',
				'month' => date('m'),
				'year' => date('Y'),
				'member_id' => 0,
				'overview_option_disabled' => 'disabled="disabled"',
				'target' => 0,
				'weekday_target' => 0,
				'saturday_target' => 0,
				'total_done' => 0,
				'total_target' => 0,
				'total_missing' => 0,
				'total_percent' => 0,
				'chart_names' => '[]',
				'chart_done' => '[]',
				'chart_target' => '[]',
				'daily_labels' => '[]',
				'daily_done' => '[]',
				'daily_target' => '[]',
				'daily_missing' => '[]'
			));
			$template->set_filenames_new(array('giaoviec' => 'common_lists/giaoviec/giaoviec_dashboard_content.html'));
			$template->pparse('giaoviec');
			return;
		}

		$month = (int)mosGetParam($_REQUEST, 'month', date('m'));
		$year = (int)mosGetParam($_REQUEST, 'year', date('Y'));
		$memberFilter = (int)mosGetParam($_REQUEST, 'member_id1', 0);
		$loginId = (int)(isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : 0);
		$canViewOverview = gvKpiCanViewContentOverview();
		if ($month < 1 || $month > 12) $month = (int)date('m');
		if ($year < 2000 || $year > 2100) $year = (int)date('Y');
		if (!$canViewOverview) $memberFilter = $loginId;
		if ($canViewOverview && $memberFilter > 0 && !in_array($memberFilter, $teamIds)) $memberFilter = 0;

		list($target, $weekdayTarget, $saturdayTarget, $daysInMonth) = gvKpiMonthTarget($month, $year);
			$teamSql = count($teamIds) ? implode(',', $teamIds) : '0';
			$selectedTeamIds = ($memberFilter > 0 && in_array($memberFilter, $teamIds)) ? array($memberFilter) : $teamIds;
			$memberTargetMap = gvKpiContentMemberTargetMap($selectedTeamIds, $month, $year, $daysInMonth, array());
		$memberCond = ($memberFilter > 0 && in_array($memberFilter, $teamIds)) ? " and m.member_id = ".$memberFilter : "";
		$taskMemberCond = ($memberFilter > 0 && in_array($memberFilter, $teamIds)) ? " and gv.member_id = ".$memberFilter : "";
		$dateCond = " and YEAR(gv.created_date) = ".$year." and MONTH(gv.created_date) = ".$month;
		$kpiTaskCond = " and gv.kpi_type in (".gvKpiContentTaskTypesSql().")";
		$selectedMemberCount = 0;

		$summary = array();
		$sql = "
			select
				m.member_id,
				m.fullname,
				sum(case when gv.giaoviec_id is not null then case when ifnull(gv.giaoviec_num,0) > 0 then gv.giaoviec_num else 1 end else 0 end) as total_tasks,
				sum(case when gv.soluong = 2 then case when ifnull(gv.giaoviec_num,0) > 0 then gv.giaoviec_num else 1 end else 0 end) as done_tasks,
				sum(case when trim(gv.giaoviec_name) <> '' and ifnull(gv.giaoviec_num,0) > 0 and trim(gv.link_demo) <> '' and gv.soluong = 2 then gv.giaoviec_num else 0 end) as kpi_done,
				sum(case when gv.giaoviec_id is not null and not (trim(gv.giaoviec_name) <> '' and ifnull(gv.giaoviec_num,0) > 0 and trim(gv.link_demo) <> '' and gv.soluong = 2) then case when ifnull(gv.giaoviec_num,0) > 0 then gv.giaoviec_num else 1 end else 0 end) as invalid_tasks
			from tbl_member m
			left join tbl_giaoviec gv on gv.member_id = m.member_id ".$dateCond." and gv.active = 1 ".$kpiTaskCond."
			where m.active = 1 and m.member_id in (".$teamSql.") ".$memberCond."
			group by m.member_id, m.fullname
			order by kpi_done desc, m.fullname
		";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		while ($row = $db->sql_fetchrow($result)) {
				$memberId = (int)$row['member_id'];
				$memberTarget = isset($memberTargetMap[$memberId]) ? (int)$memberTargetMap[$memberId] : $target;
				$done = (int)$row['kpi_done'];
				$percent = gvKpiPercent($done, $memberTarget);
				$missing = max(0, $memberTarget - $done);
				$class = gvKpiStatusClass($percent);
				$summary[] = array('name' => $row['fullname'], 'member_id' => $memberId, 'done' => $done, 'target' => $memberTarget, 'missing' => $missing, 'percent' => $percent);
				$selectedMemberCount++;
				$template->assign_block_vars('member_kpi', array(
					'member_id' => $memberId,
					'fullname' => htmlspecialchars($row['fullname']),
					'total_tasks' => (int)$row['total_tasks'],
					'done_tasks' => (int)$row['done_tasks'],
					'kpi_done' => $done,
					'target' => $memberTarget,
					'missing' => $missing,
				'invalid_tasks' => (int)$row['invalid_tasks'],
				'percent' => $percent,
				'bar_width' => min(100, $percent),
				'status_class' => $class,
				'status_text' => ($percent >= 100 ? 'Đạt KPI' : ($percent >= 80 ? 'Cần bổ sung' : 'Đang thiếu')),
			));
		}

		$sql = "
			select day(gv.created_date) as day_num,
				sum(case when trim(gv.giaoviec_name) <> '' and ifnull(gv.giaoviec_num,0) > 0 and trim(gv.link_demo) <> '' and gv.soluong = 2 then gv.giaoviec_num else 0 end) as kpi_done
			from tbl_giaoviec gv
			where gv.active = 1 and gv.member_id in (".$teamSql.") ".$taskMemberCond." ".$dateCond." ".$kpiTaskCond."
			group by day(gv.created_date)
			order by day_num
		";
		$dailyDone = array();
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		while ($row = $db->sql_fetchrow($result)) {
			$dailyDone[(int)$row['day_num']] = (int)$row['kpi_done'];
		}

		$dailyLabels = array();
		$dailyValues = array();
		$dailyTargets = array();
		$dailyMissing = array();
		for ($day = 1; $day <= $daysInMonth; $day++) {
			$workDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
			$dayDone = isset($dailyDone[$day]) ? $dailyDone[$day] : 0;
			$baseDayTarget = gvKpiContentDayTarget($workDate);
				$dayTarget = 0;
				foreach ($selectedTeamIds as $selectedMemberId) {
					$selectedMemberId = (int)$selectedMemberId;
					$dayTarget += $baseDayTarget;
				}
			$dailyLabels[] = sprintf('%02d', $day);
			$dailyValues[] = $dayDone;
			$dailyTargets[] = $dayTarget;
			$dailyMissing[] = max(0, $dayTarget - $dayDone);
		}

		$sql = "
			select gv.giaoviec_id, gv.giaoviec_name, gv.link_demo, gv.giaoviec_num, gv.kpi_type, gv.soluong, gv.created_date, gv.ngay, m.fullname, w.website_name
			from tbl_giaoviec gv
			left join tbl_member m on gv.member_id = m.member_id
			left join tbl_website w on gv.website_id = w.website_id
			where gv.active = 1 and gv.member_id in (".$teamSql.") ".$taskMemberCond." ".$dateCond." ".$kpiTaskCond."
			and not (trim(gv.giaoviec_name) <> '' and ifnull(gv.giaoviec_num,0) > 0 and trim(gv.link_demo) <> '' and gv.soluong = 2)
			order by gv.created_date desc, m.fullname
			limit 200
		";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		$order = 0;
		while ($row = $db->sql_fetchrow($result)) {
			$order++;
			$reason = array();
			if (trim($row['giaoviec_name']) == '') $reason[] = 'Thiếu tiêu đề';
			if ((int)$row['giaoviec_num'] <= 0) $reason[] = 'Thiếu số lượng';
			if (trim($row['link_demo']) == '') $reason[] = 'Thiếu link hoàn thành';
			if ((int)$row['soluong'] != 2) $reason[] = 'Chưa tick Đã xong';
			$statusText = ((int)$row['soluong'] == 0) ? 'Chưa thực hiện' : (((int)$row['soluong'] == 1) ? 'Đang thực hiện' : 'Đã xong');
			$statusClass = ((int)$row['soluong'] == 0) ? 'kpi-status-new' : (((int)$row['soluong'] == 1) ? 'kpi-status-doing' : 'kpi-status-done');
			$template->assign_block_vars('invalid_task', array(
				'order' => $order,
				'giaoviec_id' => (int)$row['giaoviec_id'],
				'fullname' => htmlspecialchars($row['fullname']),
				'giaoviec_name' => htmlspecialchars($row['giaoviec_name']),
				'website_name' => htmlspecialchars($row['website_name']),
				'created_date' => substr($row['created_date'], 0, 10),
				'deadline' => htmlspecialchars($row['ngay']),
				'viet_selected' => ($row['kpi_type'] == 'viet_content') ? 'selected="selected"' : '',
				'audit_selected' => ($row['kpi_type'] == 'audit_content') ? 'selected="selected"' : '',
				'keyword_selected' => ($row['kpi_type'] == 'nghien_cuu_tu_khoa') ? 'selected="selected"' : '',
				'status_text' => $statusText,
				'status_class' => $statusClass,
				'reason' => htmlspecialchars(implode(', ', $reason)),
			));
		}

		$memberListCond = $canViewOverview ? "member_id in (".$teamSql.")" : "member_id = ".$loginId;
		$sql = "select member_id, fullname from tbl_member where active = 1 and ".$memberListCond." order by fullname";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		while ($row = $db->sql_fetchrow($result)) {
			$template->assign_block_vars('member_list', array(
				'member_id' => (int)$row['member_id'],
				'member_name' => htmlspecialchars($row['fullname']),
			));
		}

		$totalDone = 0;
		$totalTarget = 0;
		$totalMissing = 0;
		foreach ($summary as $item) {
			$totalDone += $item['done'];
			$totalTarget += $item['target'];
			$totalMissing += $item['missing'];
		}

		$template->assign_vars(array(
			'funname' => 'common_lists/giaoviec',
			'LANGUAGEID' => $languageid,
			'month' => sprintf('%02d', $month),
			'year' => $year,
			'member_id' => $memberFilter,
			'overview_option_disabled' => $canViewOverview ? '' : 'disabled="disabled"',
			'target' => $totalTarget,
			'weekday_target' => $weekdayTarget,
			'saturday_target' => $saturdayTarget,
			'total_done' => $totalDone,
			'total_target' => $totalTarget,
			'total_missing' => $totalMissing,
			'total_percent' => gvKpiPercent($totalDone, $totalTarget),
			'chart_names' => json_encode(array_map(function($item) { return $item['name']; }, $summary)),
			'chart_done' => json_encode(array_map(function($item) { return $item['done']; }, $summary)),
			'chart_target' => json_encode(array_map(function($item) { return $item['target']; }, $summary)),
			'daily_labels' => json_encode($dailyLabels),
			'daily_done' => json_encode($dailyValues),
			'daily_target' => json_encode($dailyTargets),
			'daily_missing' => json_encode($dailyMissing),
			'MESSAGE' => '',
			'MESSAGE_DISPLAY' => 'none',
		));

		$template->set_filenames_new(array(
			'giaoviec' => 'common_lists/giaoviec/giaoviec_dashboard_content.html')
		);
		$template->pparse('giaoviec');
	}
//----------------------------------------------------------------------------------------------------------------------------------------	
	function mosThongKe( )
	{	global $db, $root_path, $skin, $languageid, $template, $theme;
		//so lan click
		$sql = "select * from tbl_member";
		if ( !($result = $db->sql_query($sql))) die ( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result))
		{
			$member_id 	= $row['member_id'];
			$template->assign_block_vars('member_list', array(
				'name'		=>	$row['fullname'],
			));
			$sql1 = "select * from tbl_giaoviec";
			if ( !($result1 = $db->sql_query($sql1))) die ( SERVER_BUSY );
			while ( $row1 = $db->sql_fetchrow($result1))
			{
				$giaoviec_id	= $row1['giaoviec_id'];
				$sql2 = "select count(*) as dem from tbl_thuchien where member_id = $member_id and giaoviec_id = $giaoviec_id  and month(ngay) = month(now())";
				if ( !($result2 = $db->sql_query($sql2))) die ( SERVER_BUSY );
				if ( $row2 = $db->sql_fetchrow($result2))
					$dem	= $row2['dem'];
				$template->assign_block_vars('member_list.giaoviec_list', array(
					'giaoviec_name'	=>	$row1['giaoviec_name'],
                    'link_demo'		=>	$row1['link_demo'],
					'chitiet'		=>	$row1['chitiet'],
					'dem'			=>	$dem,
					'rong'			=>	$dem * 5,
				));
			}
		}		
		$template->set_filenames_new(array(
			'giaoviec' => 'common_lists/giaoviec/thongke.tpl')
		);
		$template->pparse('giaoviec');	
	}

?>
