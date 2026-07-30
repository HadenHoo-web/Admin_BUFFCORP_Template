<?php
	global $languageid, $template;
	$action = mosGetParam($_REQUEST, 'mode', '');
	if (isset($_REQUEST['debug_content']) && $_REQUEST['debug_content'] == '1') {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		ini_set('html_errors', 0);
		register_shutdown_function('mosContentDebugShutdown');
		mosContentDebug('module-start', array(
			'mode' => $action,
			'login_id' => isset($_SESSION['login_id']) ? $_SESSION['login_id'] : '',
			'loginname' => isset($_SESSION['loginname']) ? $_SESSION['loginname'] : '',
			'membername' => isset($_SESSION['membername']) ? $_SESSION['membername'] : '',
		));
	}
	if (!isset($template)) {
		$template = new Template();
	}
	if (!mosContentCanAccessModule()) {
		mosContentDebug('access-denied');
		mosContentShowMessage('Bạn không có quyền xem chức năng Quản lý Content. Vui lòng kiểm tra quyền được share menu này.');
		exit;
	}
	if (!mosContentTablesReady()) {
		mosContentDebug('missing-table');
		mosContentShowMessage('Chưa tạo bảng dữ liệu cho Quản lý Content. Vui lòng chạy file SQL bootrap/db/add_quan_ly_content.sql trong phpMyAdmin.');
		exit;
	}
	if (!mosContentTaskColumnsReady()) {
		mosContentDebug('missing-task-columns');
		mosContentShowMessage('Chưa có cột Loại. Vui lòng chạy SQL cập nhật cột content_type.');
		exit;
	}
	if (!mosContentLinkColumnsReady()) {
		mosContentDebug('missing-link-columns');
		mosContentShowMessage('Chưa có đủ cột Link GG Docs / Bộ từ khoá / Link Website. Vui lòng chạy SQL cập nhật google_docs_urls, keyword_set_url và website_urls.');
		exit;
	}
	mosContentDebug('module-ready');

	$template->assign_vars(array(
		'ROOT' => $root_path,
		'funname' => 'common_lists/quanlycontent',
		'LANGUAGEID' => $languageid,
	));

	switch ($action) {
		case 'list': mosContentList(); break;
		case 'info': mosContentInfo(); break;
		case 'save': mosContentSave(); break;
		case 'delete': mosContentDelete(); break;
		default:
			mosInvalidURL();
			exit;
	}

function mosContentIsAdmin()
{
	return isset($_SESSION['loginname']) && strtolower($_SESSION['loginname']) == 'administrator';
}

function mosContentDebugEnabled()
{
	return isset($_REQUEST['debug_content']) && $_REQUEST['debug_content'] == '1';
}

function mosContentDebug($message, $data = null)
{
	if (!mosContentDebugEnabled()) return;
	$json = function_exists('json_encode') ? json_encode($data) : 'null';
	if ($json === false) $json = 'null';
	echo "<script>console.log('[quanlycontent] ".addslashes($message)."', ".$json.");</script>\n";
	echo "<!-- quanlycontent debug: ".htmlspecialchars($message, ENT_QUOTES, 'UTF-8')." -->\n";
}

function mosContentDebugShutdown()
{
	if (!mosContentDebugEnabled()) return;
	$error = error_get_last();
	if (!$error) {
		echo "<script>console.log('[quanlycontent] shutdown-ok');</script>\n";
		return;
	}
	$fatalTypes = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR);
	if (!in_array($error['type'], $fatalTypes)) return;
	$msg = 'PHP fatal error: '.$error['message'].' in '.$error['file'].':'.$error['line'];
	echo '<pre style="font-family:Arial;font-size:13px;color:#b00020;background:#fff3f3;border:1px solid #f1b5b5;padding:12px;margin:12px;">'.htmlspecialchars($msg, ENT_QUOTES, 'UTF-8').'</pre>';
	echo "<script>console.error('[quanlycontent] ".addslashes($msg)."');</script>\n";
}

function mosContentCanAccessModule()
{
	global $db;
	if (mosContentIsAdmin()) return true;
	if (!isset($_SESSION['login_id']) || (int)$_SESSION['login_id'] <= 0) return false;
	$sql = "select member_id from tbl_permission where member_id = ".intval($_SESSION['login_id'])." and code = 'quanlycontent' limit 1";
	if (!($result = $db->sql_query($sql))) return false;
	return (bool)$db->sql_fetchrow($result);
}

function mosContentShowMessage($message)
{
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
	echo '<div style="font-family:Arial;font-size:13px;padding:16px;color:#333">';
	echo '<h3 style="margin:0 0 10px 0;color:#b00020">Quản lý Content</h3>';
	echo '<p>'.htmlspecialchars($message, ENT_QUOTES, 'UTF-8').'</p>';
	echo '</div>';
}

function mosContentRedirect($mode = 'list', $msg = '')
{
	global $languageid;
	$url = 'main.php?option=common_lists/quanlycontent&mode='.$mode.'&l='.(int)$languageid;
	if (isset($_REQUEST['menu']) && (int)$_REQUEST['menu'] > 0) {
		$url .= '&menu='.(int)$_REQUEST['menu'];
	}
	if ($msg != '') {
		$url .= '&msg='.urlencode($msg);
	}
	if (!headers_sent()) {
		header('Location: '.$url);
		exit;
	}
	echo '<script>document.location='.json_encode($url).';</script>';
	exit;
}

function mosContentTablesReady()
{
	global $db;
	$sql = "show tables like 'tbl_content_tasks'";
	if (!($result = $db->sql_query($sql))) return false;
	if (!$db->sql_fetchrow($result)) return false;
	$sql = "show tables like 'tbl_content_task_links'";
	if (!($result = $db->sql_query($sql))) return false;
	return (bool)$db->sql_fetchrow($result);
}

function mosContentLinkColumnsReady()
{
	global $db;
	$sql = "show columns from tbl_content_task_links like 'google_docs_urls'";
	if (!($result = $db->sql_query($sql))) return false;
	if (!$db->sql_fetchrow($result)) return false;
	$sql = "show columns from tbl_content_task_links like 'website_urls'";
	if (!($result = $db->sql_query($sql))) return false;
	if (!$db->sql_fetchrow($result)) return false;
	$sql = "show columns from tbl_content_task_links like 'keyword_set_url'";
	if (!($result = $db->sql_query($sql))) return false;
	return (bool)$db->sql_fetchrow($result);
}

function mosContentTaskColumnsReady()
{
	global $db;
	$sql = "show columns from tbl_content_tasks like 'content_type'";
	if (!($result = $db->sql_query($sql))) return false;
	return (bool)$db->sql_fetchrow($result);
}

function mosContentCanSee($row)
{
	return mosContentIsAdmin()
		|| (int)$row['created_by_id'] == (int)$_SESSION['login_id']
		|| (int)$row['member_id'] == (int)$_SESSION['login_id'];
}

function mosContentCanApprove($row)
{
	if (mosContentIsAdmin()) return true;
	$loginId = isset($_SESSION['login_id']) ? (int)$_SESSION['login_id'] : 0;
	return ((int)$row['created_by_id'] == $loginId && (int)$row['member_id'] != $loginId);
}

function mosContentUrl($url)
{
	return htmlspecialchars(stripslashes($url), ENT_QUOTES, 'UTF-8');
}

function mosContentTypeName($type)
{
	switch ((int)$type) {
		case 2: return 'Viết bài + Đăng';
		case 3: return 'Viết bài + Đăng + Thêm Hình';
		default: return 'Viết bài';
	}
}

function mosContentGetTask($id)
{
	global $db;
	$sql = "select c.*, m.fullname, w.website_name
		from (tbl_content_tasks c left join tbl_member m on c.member_id = m.member_id)
		left join tbl_website w on c.website_id = w.website_id
		where c.content_task_id = ".intval($id)." limit 1";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	return $db->sql_fetchrow($result);
}

function mosContentLinkHtml($taskId)
{
	global $db;
	return mosContentTypedLinkHtml($taskId, 'website');
}

function mosContentSplitLinks($text)
{
	$text = str_replace("\r", "\n", stripslashes($text));
	$parts = explode("\n", $text);
	$links = array();
	foreach ($parts as $part) {
		$part = trim($part);
		if ($part != '') $links[] = $part;
	}
	return $links;
}

function mosContentJoinLinks($links)
{
	$out = array();
	if (!is_array($links)) return '';
	foreach ($links as $url) {
		$url = trim(strip_tags($url));
		if ($url != '') $out[] = $url;
	}
	return implode("\n", $out);
}

function mosContentTypedLinkHtml($taskId, $type)
{
	global $db;
	$html = '';
	if (!mosContentLinkColumnsReady()) return '';
	$sql = "select google_docs_urls, website_urls, keyword_set_url from tbl_content_task_links where content_task_id = ".intval($taskId)." order by link_id";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	$index = 0;
	while ($row = $db->sql_fetchrow($result)) {
		if ($type == 'google') {
			$text = $row['google_docs_urls'];
		} elseif ($type == 'keyword') {
			$text = $row['keyword_set_url'];
		} else {
			$text = $row['website_urls'];
		}
		$links = mosContentSplitLinks($text);
		foreach ($links as $urlRaw) {
			$index++;
			$url = mosContentUrl($urlRaw);
			$label = ($type == 'google') ? 'GG Docs '.$index : (($type == 'keyword') ? 'Bộ từ khoá '.$index : mosContentUrl($urlRaw));
			$html .= ($html == '' ? '' : '<br>') . '<a href="'.$url.'" target="_blank">'.$label.'</a>';
		}
	}
	return $html;
}

function mosContentList()
{
	global $db, $languageid, $template;
	mosContentDebug('list-start');
	$member_id = (int)mosGetParam($_REQUEST, 'member_id1', 0);
	$website_id = (int)mosGetParam($_REQUEST, 'website_id1', 0);
	$approved = mosGetParam($_REQUEST, 'approved1', '');

	$cond = mosContentIsAdmin() ? '' : " and (c.created_by_id = ".intval($_SESSION['login_id'])." or c.member_id = ".intval($_SESSION['login_id']).")";
	if ($member_id > 0) $cond .= " and c.member_id = ".$member_id;
	if ($website_id > 0) $cond .= " and c.website_id = ".$website_id;
	if ($approved !== '') $cond .= " and c.approved = ".intval($approved);

	$statCond = mosContentIsAdmin() ? '' : " where (t.created_by_id = ".intval($_SESSION['login_id'])." or t.member_id = ".intval($_SESSION['login_id']).")";
	$sql = "select
			count(*) as total_tasks,
			sum(case when approved = 1 then 1 else 0 end) as approved_tasks,
			sum(case when approved = 0 and link_count > 0 then 1 else 0 end) as waiting_approve_tasks,
			sum(case when approved = 0 and link_count = 0 then 1 else 0 end) as waiting_link_tasks
		from (
			select t.content_task_id, t.approved, count(l.link_id) as link_count
			from tbl_content_tasks t
			left join tbl_content_task_links l on t.content_task_id = l.content_task_id
			$statCond
			group by t.content_task_id, t.approved
		) s";
	mosContentDebug('stats-query', $sql);
	if (!($result = $db->sql_query($sql))) {
		mosContentDebug('stats-query-failed', $sql);
		message_die(SERVER_BUSY);
	}
	$stats = $db->sql_fetchrow($result);
	mosContentDebug('stats-loaded', $stats);

	$sql = "select c.*, m.fullname, w.website_name,
			(select count(*) from tbl_content_task_links l where l.content_task_id = c.content_task_id) as link_count
		from (tbl_content_tasks c left join tbl_member m on c.member_id = m.member_id)
		left join tbl_website w on c.website_id = w.website_id
			where 1 $cond
			order by c.approved asc, c.created_date desc, c.content_task_id desc";
	mosContentDebug('list-query', $sql);
	if (!($result = $db->sql_query($sql))) {
		mosContentDebug('list-query-failed', $sql);
		message_die(SERVER_BUSY);
	}

	$order = 0;
	while ($row = $db->sql_fetchrow($result)) {
		$order++;
		$isApproved = ((int)$row['approved'] == 1);
		$template->assign_block_vars('list', array(
			'className' => (($order % 2 == 1) ? 'alt' : 'inv') . ($isApproved ? ' content-approved-row' : ''),
			'order' => $order,
			'content_task_id' => $row['content_task_id'],
			'title' => mosContentUrl($row['title']),
			'website_name' => mosContentUrl($row['website_name']),
			'member_name' => mosContentUrl($row['fullname']),
			'content_type' => mosContentTypeName($row['content_type']),
			'title_cell_style' => (strlen(strip_tags(stripslashes($row['title']))) > 30) ? ' content-task-wide' : '',
			'google_docs_links' => mosContentTypedLinkHtml($row['content_task_id'], 'google'),
			'keyword_set_links' => mosContentTypedLinkHtml($row['content_task_id'], 'keyword'),
			'website_links' => mosContentTypedLinkHtml($row['content_task_id'], 'website'),
			'link_count' => (int)$row['link_count'],
			'approved' => $isApproved ? '<span class="content-approved-badge">Đã duyệt</span>' : (((int)$row['link_count'] > 0) ? '<font color="orange"><b>Chờ duyệt</b></font>' : 'Chờ link'),
			'approved_date' => $row['approved_date'],
			'created_by' => mosContentUrl($row['created_by']),
			'created_date' => substr($row['created_date'], 0, 10),
			'modified_by' => mosContentUrl($row['modified_by']),
			'last_modified' => $row['last_modified'],
			'delete_display' => mosContentIsAdmin() ? '' : 'none',
		));
	}

	$sql = "select * from tbl_member where active = 1 order by fullname";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	while ($row = $db->sql_fetchrow($result)) {
		$template->assign_block_vars('member_list', array(
			'member_id' => $row['member_id'],
			'member_name' => mosContentUrl($row['fullname']),
		));
	}

	$websiteCond = mosContentIsAdmin() ? '' : ' and active = 1';
	$sql = "select * from tbl_website where 1 $websiteCond order by website_name";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	while ($row = $db->sql_fetchrow($result)) {
		$template->assign_block_vars('website_list', array(
			'website_id' => $row['website_id'],
			'website_name' => mosContentUrl($row['website_name']),
		));
	}

	$template->assign_vars(array(
		'total_tasks' => (int)$stats['total_tasks'],
		'approved_tasks' => (int)$stats['approved_tasks'],
		'waiting_approve_tasks' => (int)$stats['waiting_approve_tasks'],
		'waiting_link_tasks' => (int)$stats['waiting_link_tasks'],
		'member_id' => $member_id,
		'website_id' => $website_id,
		'approved_filter' => $approved,
		'MESSAGE' => (mosGetParam($_REQUEST, 'msg', '') == 'save_success') ? SAVE_SUCCESS : ((mosGetParam($_REQUEST, 'msg', '') == 'delete_success') ? DELETE_SUCCESS : ''),
	));
	$template->set_filenames_new(array('content' => 'common_lists/quanlycontent/quanlycontent_list.html'));
	mosContentDebug('before-render-list-template');
	$template->pparse('content');
}

function mosContentInfo()
{
	global $db, $template;
	$id = (int)mosGetParam($_REQUEST, 'id', 0);
	$row = false;
	$canApprove = false;

	if ($id > 0) {
		$row = mosContentGetTask($id);
		if (!$row || !mosContentCanSee($row)) {
			mosInvalidURL();
			exit;
		}
		$canApprove = mosContentCanApprove($row);
		$template->assign_vars(array(
			'content_task_id' => $id,
			'title' => mosContentUrl($row['title']),
			'content_type' => (int)$row['content_type'] > 0 ? (int)$row['content_type'] : 1,
			'website_id' => $row['website_id'],
			'member_id' => $row['member_id'],
			'note' => mosContentUrl($row['note']),
			'approved' => ((int)$row['approved'] == 1) ? 'checked' : '',
			'approved_by' => mosContentUrl($row['approved_by']),
			'approved_date' => $row['approved_date'],
			'approve_disabled' => $canApprove ? '' : 'disabled',
			'approve_display' => $canApprove ? '' : 'none',
			'assign_disabled' => $canApprove ? '' : 'disabled',
			'title_readonly' => $canApprove ? '' : 'readonly',
			'note_readonly' => $canApprove ? '' : 'readonly',
			'approve_note' => $canApprove ? '' : 'Chỉ người giao task cho người khác hoặc admin được xác nhận duyệt.',
		));

		$sql = "select * from tbl_content_task_links where content_task_id = ".$id." order by link_id";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		$linkCount = 0;
		$googleDocAssigned = 0;
		$websiteAssigned = 0;
		$keywordSetAssigned = 0;
		while ($link = $db->sql_fetchrow($result)) {
			$linkCount++;
			$googleDocsLinks = mosContentSplitLinks($link['google_docs_urls']);
			$websiteLinks = mosContentSplitLinks($link['website_urls']);
			$keywordSetLinks = mosContentSplitLinks($link['keyword_set_url']);
			if (!$googleDocAssigned && isset($googleDocsLinks[0])) {
				$googleDocAssigned = 1;
				$template->assign_block_vars('google_doc_link_list', array(
					'url' => mosContentUrl($googleDocsLinks[0]),
				));
			}
			if (!$websiteAssigned && isset($websiteLinks[0])) {
				$websiteAssigned = 1;
				$template->assign_block_vars('website_link_list', array(
					'url' => mosContentUrl($websiteLinks[0]),
				));
			}
			if (!$keywordSetAssigned && isset($keywordSetLinks[0])) {
				$keywordSetAssigned = 1;
				$template->assign_block_vars('keyword_set_link_list', array(
					'url' => mosContentUrl($keywordSetLinks[0]),
				));
			}
		}
		if ($linkCount == 0 || !$googleDocAssigned) {
			$template->assign_block_vars('google_doc_link_list', array('url' => ''));
		}
		if ($linkCount == 0 || !$websiteAssigned) {
			$template->assign_block_vars('website_link_list', array('url' => ''));
		}
		if ($linkCount == 0 || !$keywordSetAssigned) {
			$template->assign_block_vars('keyword_set_link_list', array('url' => ''));
		}
	} else {
		$template->assign_vars(array(
			'content_task_id' => 0,
			'content_type' => 1,
			'approved' => '',
			'approve_disabled' => 'disabled',
			'approve_display' => 'none',
			'assign_disabled' => '',
			'title_readonly' => '',
			'note_readonly' => '',
			'approve_note' => 'Lưu task trước, sau đó người giao sẽ xác nhận duyệt khi đã kiểm tra bài.',
		));
		$template->assign_block_vars('google_doc_link_list', array('url' => ''));
		$template->assign_block_vars('website_link_list', array('url' => ''));
		$template->assign_block_vars('keyword_set_link_list', array('url' => ''));
	}

	$sql = "select * from tbl_member where active = 1 order by fullname";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	while ($member = $db->sql_fetchrow($result)) {
		$template->assign_block_vars('member_list', array(
			'member_id' => $member['member_id'],
			'member_name' => mosContentUrl($member['fullname']),
		));
	}

	$websiteCond = mosContentIsAdmin() ? '' : ' and active = 1';
	$sql = "select * from tbl_website where 1 $websiteCond order by website_name";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	while ($website = $db->sql_fetchrow($result)) {
		$template->assign_block_vars('website_list', array(
			'website_id' => $website['website_id'],
			'website_name' => mosContentUrl($website['website_name']),
		));
	}

	$template->set_filenames_new(array('content' => 'common_lists/quanlycontent/quanlycontent_info.html'));
	$template->pparse('content');
}

function mosContentSave()
{
	global $db, $template;
	$id = (int)mosGetParam($_REQUEST, 'id', 0);
	$title = mosGetParam($_REQUEST, 'title', '');
	$content_type = (int)mosGetParam($_REQUEST, 'content_type', 1);
	$website_id = (int)mosGetParam($_REQUEST, 'website_id', 0);
	$member_id = (int)mosGetParam($_REQUEST, 'member_id', 0);
	$note = mosGetParam($_REQUEST, 'note', '', _MOS_ALLOWHTML);
	$approvedInput = (int)mosGetParam($_REQUEST, 'approved', 0);
	$googleDocLinks = isset($_REQUEST['google_doc_url']) && is_array($_REQUEST['google_doc_url']) ? $_REQUEST['google_doc_url'] : array();
	$websiteLinks = isset($_REQUEST['website_url']) && is_array($_REQUEST['website_url']) ? $_REQUEST['website_url'] : array();
	$keywordSetLinks = isset($_REQUEST['keyword_set_url']) && is_array($_REQUEST['keyword_set_url']) ? $_REQUEST['keyword_set_url'] : array();
	$approvedSql = '';
	$old = false;
	if ($content_type < 1 || $content_type > 3) $content_type = 1;

	if ($id > 0) {
		$old = mosContentGetTask($id);
		if (!$old || !mosContentCanSee($old)) {
			mosInvalidURL();
			exit;
		}
		$canApprove = mosContentCanApprove($old);
		if ($canApprove) {
			$approvedSql = ", approved = ".$approvedInput.", approved_by = ".($approvedInput ? "'".$_SESSION['membername']."'" : "''").", approved_by_id = ".($approvedInput ? intval($_SESSION['login_id']) : "0").", approved_date = ".($approvedInput ? "now()" : "null");
		} else {
			$title = addslashes($old['title']);
			$content_type = (int)$old['content_type'];
			$website_id = (int)$old['website_id'];
			$member_id = (int)$old['member_id'];
			$note = addslashes($old['note']);
		}
	}

	if (trim($title) == '' || $website_id <= 0 || $member_id <= 0) {
		$template->assign_vars(array('MESSAGE' => 'Vui lòng nhập đủ tiêu đề, website và nhân viên thực hiện.'));
		mosContentInfo();
		return;
	}

	if ($id > 0) {
		$sql = "update tbl_content_tasks set
				title = '$title',
				content_type = $content_type,
				website_id = $website_id,
				member_id = $member_id,
				note = '$note',
				modified_by = '".$_SESSION['membername']."',
				last_modified = now()
				$approvedSql
			where content_task_id = $id";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	} else {
		$sql = "insert into tbl_content_tasks
				(title, content_type, website_id, member_id, note, approved, created_by, created_by_id, created_date, modified_by, last_modified)
			values
				('$title', $content_type, $website_id, $member_id, '$note', 0, '".$_SESSION['membername']."', ".intval($_SESSION['login_id']).", now(), '".$_SESSION['membername']."', now())";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		$sql = "select last_insert_id() as new_id";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		$new = $db->sql_fetchrow($result);
		$id = (int)$new['new_id'];
	}

	$sql = "delete from tbl_content_task_links where content_task_id = $id";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	$googleDocsText = addslashes(mosContentJoinLinks($googleDocLinks));
	$websiteText = addslashes(mosContentJoinLinks($websiteLinks));
	$keywordSetText = addslashes(mosContentJoinLinks($keywordSetLinks));
	if ($googleDocsText != '' || $websiteText != '' || $keywordSetText != '') {
		$sql = "insert into tbl_content_task_links (content_task_id, google_docs_urls, website_urls, keyword_set_url, created_by_id, created_date)
			values ($id, '$googleDocsText', '$websiteText', '$keywordSetText', ".intval($_SESSION['login_id']).", now())";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	}

	mosContentRedirect('list', 'save_success');
}

function mosContentDelete()
{
	global $db, $template;
	$id = (int)mosGetParam($_REQUEST, 'id', 0);
	$row = mosContentGetTask($id);
	if (!$row || !mosContentIsAdmin()) {
		mosInvalidURL();
		exit;
	}
	$sql = "delete from tbl_content_task_links where content_task_id = $id";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	$sql = "delete from tbl_content_tasks where content_task_id = $id";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	mosContentRedirect('list', 'delete_success');
}
?>
