<?php
	global $languageid, $template;
	$action      = mosGetParam($_REQUEST, 'mode', '');

	if (!defined('HOST_PAYMENT_VAT_BANK_ID')) define('HOST_PAYMENT_VAT_BANK_ID', 'TCB');
	if (!defined('HOST_PAYMENT_VAT_ACCOUNT_NO')) define('HOST_PAYMENT_VAT_ACCOUNT_NO', '909900141');
	if (!defined('HOST_PAYMENT_VAT_ACCOUNT_NAME')) define('HOST_PAYMENT_VAT_ACCOUNT_NAME', 'CÔNG TY TNHH BUFF CORP');
	if (!defined('HOST_PAYMENT_NO_VAT_BANK_ID')) define('HOST_PAYMENT_NO_VAT_BANK_ID', 'MB');
	if (!defined('HOST_PAYMENT_NO_VAT_ACCOUNT_NO')) define('HOST_PAYMENT_NO_VAT_ACCOUNT_NO', '909900141');
	if (!defined('HOST_PAYMENT_NO_VAT_ACCOUNT_NAME')) define('HOST_PAYMENT_NO_VAT_ACCOUNT_NAME', 'HO KINH DOANH');
	if (!defined('HOST_RENEW_NOTICE_DAYS')) define('HOST_RENEW_NOTICE_DAYS', 10);

	if (!isset($template))
		$template = new Template();

	$template->assign_vars(array(
		'ROOT'       => $root_path,
		'funname'    => 'common_lists/host',
		'LANGUAGEID' => $languageid,
	));

	switch ($action)
	{
		case 'list'   : mosList(); break;
		case 'info'   : mosInfo(); break;
		case 'renew'  : mosRenew(); break;
		case 'up'     : mosMove('up'); break;
		case 'down'   : mosMove('down'); break;
		case 'save'   : mosSave(); break;
		case 'delete' : mosDelete(); break;

		default:
			mosInvalidURL();
			exit;
	}

	/**
	 * Mapping text phân loại hosting
	 */
	function getHostingTypeText($hosting_type = 0)
	{
		switch ((int)$hosting_type) {
			case 1: return 'Customer Hosting';
			case 2: return 'Demo Hosting';
			case 3: return 'Internal System Hosting';
			default: return '';
		}
	}

	/**
	 * Mapping class để dùng badge màu bên tpl list (nếu muốn)
	 */
	function getHostingTypeClass($hosting_type = 0)
	{
		switch ((int)$hosting_type) {
			case 1: return 'hosting-type customer';
			case 2: return 'hosting-type demo';
			case 3: return 'hosting-type internal';
			default: return 'hosting-type';
		}
	}

	function normalizeHostDate($date = '')
	{
		$date = trim($date);
		if ($date == '') return '';

		if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $date, $matches)) {
			$day = (int)$matches[1];
			$month = (int)$matches[2];
			$year = (int)$matches[3];
			if (checkdate($month, $day, $year)) {
				return sprintf('%04d-%02d-%02d', $year, $month, $day);
			}
		}

		if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $date, $matches)) {
			$day = (int)$matches[1];
			$month = (int)$matches[2];
			$year = (int)$matches[3];
			if (checkdate($month, $day, $year)) {
				return sprintf('%04d-%02d-%02d', $year, $month, $day);
			}
		}

		if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $date, $matches)) {
			$year = (int)$matches[1];
			$month = (int)$matches[2];
			$day = (int)$matches[3];
			if (checkdate($month, $day, $year)) {
				return sprintf('%04d-%02d-%02d', $year, $month, $day);
			}
		}

		return $date;
	}

	function hostMoneyValue($value)
	{
		return (int)preg_replace('/[^0-9]/', '', (string)$value);
	}

	function hostPaymentMonths()
	{
		return array(12, 24, 36);
	}

	function hostPaymentContent($row, $months = 12)
	{
		$name = trim(isset($row['host_name']) ? $row['host_name'] : '');
		$name = preg_replace('/\s+/', ' ', $name);
		return 'GIA HAN HOSTING '.$row['host_id'].' '.$months.' THANG '.$name;
	}

	function hostRenewMonthsFromContent($row, $paymentContent)
	{
		$paymentContent = strtoupper(trim($paymentContent));
		$monthsList = hostPaymentMonths();
		foreach ($monthsList as $months) {
			if ($paymentContent == strtoupper(hostPaymentContent($row, $months))) return $months;
		}

		return 0;
	}

	function hostPaymentHostIdFromContent($paymentContent)
	{
		$paymentContent = strtoupper(trim($paymentContent));
		if (preg_match('/^GIA HAN HOSTING\s+([0-9]+)\s+(12|24|36)\s+THANG\s+/', $paymentContent, $matches)) {
			return (int)$matches[1];
		}
		return 0;
	}

	function hostRenewDueInfo($endDate)
	{
		if (trim($endDate) == '') return array('show' => false, 'days' => '', 'text' => '');
		try {
			$today = new DateTime(date('Y-m-d'));
			$targetDate = new DateTime($endDate);
			$days = (int)$today->diff($targetDate)->format('%r%a');
		} catch (Exception $e) {
			return array('show' => false, 'days' => '', 'text' => '');
		}
		$show = ($days <= HOST_RENEW_NOTICE_DAYS);
		$text = $days < 0 ? 'Đã quá hạn '.abs($days).' ngày' : 'Còn '.$days.' ngày đến hạn';
		return array('show' => $show, 'days' => $days, 'text' => $text);
	}

	function hostPaymentQrUrl($amount, $content, $bankId, $accountNo, $accountName = '')
	{
		if ($bankId == '' || $accountNo == '') return '';
		$url = 'https://img.vietqr.io/image/'.rawurlencode($bankId).'-'.rawurlencode($accountNo).'-compact2.png';
		$url .= '?amount='.rawurlencode($amount);
		$url .= '&addInfo='.rawurlencode($content);
		if ($accountName != '') $url .= '&accountName='.rawurlencode($accountName);
		return $url;
	}

function mosList(){
	global $db, $root_path, $skin, $languageid, $template;

	$member_id = mosGetParam($_REQUEST, 'member_id1', '0');
  	$hosting_type_filter = (int) mosGetParam($_REQUEST, 'hosting_type_filter', 0);
	$member_id = (strtolower($_SESSION['membername']) != "administrator" && $_SESSION["login_id"] != 34) ? $_SESSION["login_id"] : $member_id;

	$cond = '';
	$cond = (strtolower($_SESSION['membername']) == "administrator") ? ' and active = 1' : ' and active = 1';

	$sql = "select * from tbl_member where 1 $cond order by member_id";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);

	while ($row = $db->sql_fetchrow($result)) {
		$template->assign_block_vars('member_list', array(
			'member_id'   => $row['member_id'],
			'member_name' => $row['fullname'],
		));
	}

	$cond = '';
    $cond .= ($member_id) ? ' and (tbl_hosts.member_id = '.$member_id.' or tbl_hosts.share_user_id like "%'.$member_id.'%")' : '';
    $cond .= ($hosting_type_filter > 0) ? ' and tbl_hosts.hosting_type = '.$hosting_type_filter : '';

	$sql = "SELECT tbl_hosts.*, tbl_member.fullname, tbl_customer.customer_name
			FROM ((((tbl_hosts
			LEFT JOIN tbl_emails ON tbl_hosts.email_id = tbl_emails.email_id)
			LEFT JOIN tbl_member ON tbl_hosts.member_id = tbl_member.member_id)
			LEFT JOIN tbl_packages ON tbl_hosts.package_id = tbl_packages.package_id)
			LEFT JOIN tbl_server ON tbl_hosts.server_id = tbl_server.server_id)
			LEFT JOIN tbl_customer ON tbl_hosts.customer_id = tbl_customer.customer_id
			WHERE 1 $cond
			ORDER BY tbl_hosts.active DESC, tbl_hosts.member_id, tbl_hosts.priority";

	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);

	$num_row = $db->sql_numrows($result);
	$order = 0;

	while ($row = $db->sql_fetchrow($result))
	{
		$order = $order + 1;
		$days = '';
		if ($row['end_date']) {
			$targetDate = new DateTime($row['end_date']);
			$today = new DateTime();
			$interval = $today->diff($targetDate);
			$days = $interval->format('%r%a');
			if ($days <= 0) $bg_host = "gray";
			elseif ($days < 30) $bg_host = "Orange";
			else $bg_host = "Green";
		} else {
			$bg_host = "";
		}

				$template->assign_block_vars('list', array(
				'className'          => ($order % 2 == 1) ? 'alt' : 'inv',
				'order'              => $order,
				'host_id'            => $row['host_id'],
				'host_name'          => $row['host_name'],

			// NEW: hosting_type
			'hosting_type'       => (int)$row['hosting_type'],
			'hosting_type_text'  => getHostingTypeText($row['hosting_type']),
			'hosting_type_class' => getHostingTypeClass($row['hosting_type']),

			'customer_name'      => $row['customer_name'],
			'ip_host'            => $row['ip_host'],
			'order_date'         => $row['order_date'],
			'end_date'           => $row['end_date'],
			'bg_host'            => $bg_host,
			'days'               => ($days) ? "(<span style='color: red; font-weight: bold;'>$days</span>)" : "",
			'price'              => number_format($row['price'], 0, ',', '.'),
			'url'                => $row['url'],
			'username'           => $row['username'],
			'pass'               => $row['pass'],
			'ghichu'             => $row['ghichu'],
			'member_name'        => $row['fullname'],
			'share_user_id'      => $row['share_user_id'],
			'is_owner'           => ($row['member_id'] == $member_id || $_SESSION["login_id"] == 1) ? "" : "none",
				'active'             => ($row['active'] == 1) ? '' : 'none',
					'up'                 => ($order == 1) ? ' display: none;' : '',
					'down'               => ($order == $num_row) ? ' display: none;' : '',
				));
			}

	$template->assign_vars(array(
      'member_id'            => $member_id,
      'hosting_type_filter'  => $hosting_type_filter,
      'allow_member'         => (strtolower($_SESSION['membername']) == "administrator" || $_SESSION["login_id"] == 38) ? '' : 'none'
	));

	$template->set_filenames_new(array(
		'share' => 'common_lists/host/host_list.tpl')
	);
	$template->pparse('share');
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosInfo()
{
	global $db, $root_path, $skin, $languageid, $template;

	$host_id = mosGetParam($_REQUEST, 'id', 0);

	$cond = 'and active = 1';
	$sql = "select * from tbl_member where 1 $cond";
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);

	while ($row = $db->sql_fetchrow($result)) {
		$template->assign_block_vars('member_list', array(
			'member_id'   => $row['member_id'],
			'member_name' => $row['fullname'],
		));
	}

	if ($host_id != 0)
	{
		$sql = "select * from tbl_hosts where host_id = $host_id";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);

		if ($row = $db->sql_fetchrow($result))
		{
				$renewDue = hostRenewDueInfo($row['end_date']);
				$paymentAmount = hostMoneyValue($row['price']);
				$paymentMessage = '';
				if ($renewDue['show'] && ((HOST_PAYMENT_VAT_BANK_ID == '' || HOST_PAYMENT_VAT_ACCOUNT_NO == '') || (HOST_PAYMENT_NO_VAT_BANK_ID == '' || HOST_PAYMENT_NO_VAT_ACCOUNT_NO == ''))) {
					$paymentMessage = 'Chưa cấu hình đủ tài khoản QR có VAT / không VAT.';
				}
				if ($renewDue['show']) {
					$monthsList = hostPaymentMonths();
					foreach ($monthsList as $months) {
						$optionAmount = (int)round($paymentAmount * ($months / 12));
						$optionContent = hostPaymentContent($row, $months);
						$vatQrUrl = hostPaymentQrUrl($optionAmount, $optionContent, HOST_PAYMENT_VAT_BANK_ID, HOST_PAYMENT_VAT_ACCOUNT_NO, HOST_PAYMENT_VAT_ACCOUNT_NAME);
						$noVatQrUrl = hostPaymentQrUrl($optionAmount, $optionContent, HOST_PAYMENT_NO_VAT_BANK_ID, HOST_PAYMENT_NO_VAT_ACCOUNT_NO, HOST_PAYMENT_NO_VAT_ACCOUNT_NAME);
						$template->assign_block_vars('payment_option', array(
							'months' => $months,
							'amount' => number_format($optionAmount, 0, ',', '.'),
							'content' => $optionContent,
							'vat_qr_url' => $vatQrUrl,
							'vat_qr_display' => ($vatQrUrl != '') ? '' : 'none',
							'no_vat_qr_url' => $noVatQrUrl,
							'no_vat_qr_display' => ($noVatQrUrl != '') ? '' : 'none',
						));
					}
				}
			$template->assign_vars(array(
				'host_id'        => $host_id,
				'host_name'      => $row['host_name'],
				'ip_host'        => $row['ip_host'],
				'order_date'     => $row['order_date'],
				'end_date'       => $row['end_date'],
				'price'          => $row['price'],
				'url'            => $row['url'],
				'username'       => $row['username'],
				'pass'           => $row['pass'],
				'ghichu'         => $row['ghichu'],
				'email_id'       => ($row['email_id']) ? $row['email_id'] : 0,
				'package_id'     => ($row['package_id']) ? $row['package_id'] : 0,
				'server_id'      => ($row['server_id']) ? $row['server_id'] : 0,
				'customer_id'    => ($row['customer_id']) ? $row['customer_id'] : 0,
				'member_id'      => ($row['member_id']) ? $row['member_id'] : 0,

				// NEW: hosting_type
				'hosting_type'   => isset($row['hosting_type']) ? (int)$row['hosting_type'] : 0,

				'share_user_id'  => $row['share_user_id'],
				'active'         => ($row['active'] == 1) ? 'checked' : '',
				'created_date'   => $row['created_date'],
				'created_by'     => $row['created_by'],
				'last_modified'  => $row['last_modified'],
				'modified_by'    => $row['modified_by'],
				'renew_display'  => $renewDue['show'] ? '' : 'none',
				'renew_due_text' => $renewDue['text'],
				'payment_amount' => number_format($paymentAmount, 0, ',', '.'),
				'payment_message'=> $paymentMessage,
			));
		}
		else {
			message_die(ID_NOTFOUND);
		}
	}
	else
	{
		$template->assign_vars(array(
			'active'         => 'checked',
			'allow'          => 'hidden',
			'email_id'       => '0',
			'package_id'     => '0',
			'server_id'      => '0',
			'customer_id'    => '0',
			'member_id'      => '0',

			// NEW: default hosting_type
			'hosting_type'   => '0',
			'renew_display'  => 'none',
			'payment_amount' => '0',
			'payment_message'=> '',
			'renew_due_text' => '',
		));
	}

	$template->set_filenames_new(array(
		'share' => 'common_lists/host/host_info.tpl')
	);
	$template->pparse('share');
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosMove($direction)
{
	global $languageid;

	$host_id = mosGetParam($_REQUEST, 'id', '');

	if ($host_id == 0)
	{
		mosInvalidURL();
		exit;
	}

	mosChangePriority($host_id, $direction, "tbl_hosts", "host_id", "priority");
	mosList();
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSave()
{
	global $db, $root_path, $skin, $languageid, $template;

	$host_id       = mosGetParam($_REQUEST, 'id', '0');
	$host_name     = mosGetParam($_REQUEST, 'host_name', '');
	$ip_host       = mosGetParam($_REQUEST, 'ip_host', '');
	$order_date    = normalizeHostDate(mosGetParam($_REQUEST, 'order_date', ''));
	$end_date      = normalizeHostDate(mosGetParam($_REQUEST, 'end_date', ''));
	$price         = mosGetParam($_REQUEST, 'price', '0');
	$url           = mosGetParam($_REQUEST, 'url', '');
	$username      = mosGetParam($_REQUEST, 'username', '');
	$pass          = mosGetParam($_REQUEST, 'pass', '');
	$ghichu        = mosGetParam($_REQUEST, 'ghichu', '');
	$email_id      = mosGetParam($_REQUEST, 'email_id', '0');
	$package_id    = mosGetParam($_REQUEST, 'package_id', '0');
	$server_id     = mosGetParam($_REQUEST, 'server_id', '0');
	$customer_id   = mosGetParam($_REQUEST, 'customer_id', '0');
	$member_id     = mosGetParam($_REQUEST, 'member_id', '0');
	$share_user_id = mosGetParam($_REQUEST, 'share_user_id', '');
	$active        = mosGetParam($_REQUEST, 'active', 0);

	// NEW: hosting_type (1/2/3)
	$hosting_type  = (int) mosGetParam($_REQUEST, 'hosting_type', 0);

	if ($host_id == '')
	{
		mosInvalidURL();
		exit;
	}

	if ($host_id == '0')
	{
		if (checkDuplicate("tbl_hosts", array('host_name' => $host_name), "host_name", 0, false, ""))
		{
			reShowPage(DUPLICATE_ENTRY);
			exit;
		}

		$priority = mosGetPriority("tbl_hosts", "priority", "");

		$sql = "insert into tbl_hosts (
					host_name, ip_host, url, username, pass, ghichu, active, priority, language_id,
					email_id, package_id, server_id, customer_id, member_id, price, order_date, end_date,
					created_date, last_modified, created_by, modified_by, share_user_id, hosting_type
				) values (
					'$host_name', '$ip_host', '$url', '$username', '$pass', '$ghichu', $active, $priority, $languageid,
					$email_id, $package_id, $server_id, $customer_id, $member_id, '$price', '$order_date', '$end_date',
					now(), now(), '" . $_SESSION['membername'] . "', '" . $_SESSION['membername'] . "', '$share_user_id', $hosting_type
				)";
	}
	else
	{
		if (checkDuplicate("tbl_hosts", array('host_name' => $host_name), "host_name", 0, false, "host_id != $host_id"))
		{
			reShowPage(DUPLICATE_ENTRY);
			exit;
		}

		$sql = "update tbl_hosts set
					host_name = '$host_name',
					ip_host = '$ip_host',
					price = '$price',
					order_date = '$order_date',
					end_date = '$end_date',
					url = '$url',
					username = '$username',
					pass = '$pass',
					ghichu = '$ghichu',
					active = $active,
					email_id = '$email_id',
					package_id = '$package_id',
					server_id = '$server_id',
					customer_id = '$customer_id',
					member_id = '$member_id',
					hosting_type = $hosting_type,
					last_modified = now(),
					modified_by = '".$_SESSION['membername']."',
					share_user_id = '$share_user_id'
				where host_id = $host_id";
	}

	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);

	$template->assign_vars(array('MESSAGE' => SAVE_SUCCESS));
	mosList();
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosRenew()
{
	global $db, $template;

	$host_id = (int)mosGetParam($_REQUEST, 'id', 0);
	$payment_content = trim(mosGetParam($_REQUEST, 'payment_content', ''));
	$return_to = mosGetParam($_REQUEST, 'return_to', '');

	if ($host_id <= 0 && $payment_content != '') {
		$host_id = hostPaymentHostIdFromContent($payment_content);
	}

	if ($host_id <= 0) {
		$template->assign_vars(array('MESSAGE' => 'Không xác định được hosting từ nội dung thanh toán.'));
		if ($return_to == 'list') mosList(); else mosInfo();
		return;
	}

	$sql = "select * from tbl_hosts where host_id = ".$host_id;
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
	if (!($row = $db->sql_fetchrow($result))) message_die(ID_NOTFOUND);

	$renewDue = hostRenewDueInfo($row['end_date']);
	if (!$renewDue['show']) {
		$template->assign_vars(array('MESSAGE' => 'Hosting này chưa đến kỳ gia hạn.'));
		if ($return_to == 'list') mosList(); else mosInfo();
		return;
	}

	$renew_months = hostRenewMonthsFromContent($row, $payment_content);
	if ($renew_months <= 0) {
		$template->assign_vars(array('MESSAGE' => 'Nội dung thanh toán không khớp. Vui lòng nhập đúng nội dung trên QR có số tháng gia hạn.'));
		if ($return_to == 'list') mosList(); else mosInfo();
		return;
	}

	$today = date('Y-m-d');
	$baseDate = $today;
	if (trim($row['end_date']) != '') {
		try {
			$currentEnd = new DateTime($row['end_date']);
			$baseDate = $currentEnd->format('Y-m-d');
		} catch (Exception $e) {
			$baseDate = $today;
		}
	}

	$newEnd = new DateTime($baseDate);
	$newEnd->modify('+'.$renew_months.' months');
	$newEndDate = $newEnd->format('Y-m-d');
	$renewNote = "\n[".date('Y-m-d H:i:s')."] Gia hạn hosting ".$renew_months." tháng. Nội dung thanh toán: ".$payment_content.". Hạn mới: ".$newEndDate.". NV: ".$_SESSION['membername'];
	$newNote = addslashes($row['ghichu'].$renewNote);

	$sql = "update tbl_hosts set
				end_date = '".$newEndDate."',
				ghichu = '".$newNote."',
				last_modified = now(),
				modified_by = '".$_SESSION['membername']."'
			where host_id = ".$host_id;
	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);

	$template->assign_vars(array('MESSAGE' => 'Đã gia hạn hosting đến '.$newEndDate));
	if ($return_to == 'list') mosList(); else mosInfo();
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosDelete()
{
	global $template, $db;

	$host_id = mosGetParam($_REQUEST, 'id', '0');
	if ($host_id == 0)
	{
		mosInvalidURL();
		exit;
	}

	if (strtolower($_SESSION['membername']) == "administrator")
	{
		deleteByID("tbl_hosts", "host_id", $host_id);
	}
	else
	{
		$template->assign_vars(array('MESSAGE' => CANT_NOT_DELETE));
	}

	mosList();
}

//----------------------------------------------------------------------------------------------------------------------------------------
function reShowPage($message)
{
	global $db, $root_path, $skin, $languageid, $template, $theme;

	$id = mosGetParam($_REQUEST, 'id', '0');

	$template->assign_vars(array(
		'host_name'      => mosGetParam($_REQUEST, 'host_name', ''),
		'ip_host'        => mosGetParam($_REQUEST, 'ip_host', ''),
		'order_date'     => mosGetParam($_REQUEST, 'order_date', ''),
		'end_date'       => mosGetParam($_REQUEST, 'end_date', ''),
		'price'          => mosGetParam($_REQUEST, 'price', '0'),
		'url'            => mosGetParam($_REQUEST, 'url', ''),
		'username'       => mosGetParam($_REQUEST, 'username', ''),
		'pass'           => mosGetParam($_REQUEST, 'pass', ''),
		'ghichu'         => mosGetParam($_REQUEST, 'ghichu', ''),
		'MESSAGE'        => DUPLICATE_ENTRY,
		'host_id'        => $id,
		'share_user_id'  => mosGetParam($_REQUEST, 'share_user_id', ''),

		// NEW: giữ lại hosting_type khi báo lỗi duplicate
		'hosting_type'   => (int) mosGetParam($_REQUEST, 'hosting_type', 0),

		// giữ lại các select khác (nếu tpl có set js)
		'email_id'       => mosGetParam($_REQUEST, 'email_id', '0'),
		'package_id'     => mosGetParam($_REQUEST, 'package_id', '0'),
		'server_id'      => mosGetParam($_REQUEST, 'server_id', '0'),
		'customer_id'    => mosGetParam($_REQUEST, 'customer_id', '0'),
		'member_id'      => mosGetParam($_REQUEST, 'member_id', '0'),
	));

	$template->set_filenames_new(array(
		'host' => 'common_lists/host/host_info.tpl')
	);

	$template->pparse('host');
}
?>
