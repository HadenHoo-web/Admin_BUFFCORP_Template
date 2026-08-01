<?php
global $template, $root_path, $languageid;

$action = mosGetParam($_REQUEST, 'mode', 'dashboard');

$template->assign_vars(array(
    'ROOT' => $root_path,
    'funname' => 'getpass_exact/getpass_exact',
    'LANGUAGEID' => $languageid,
));

switch ($action) {
    case 'share':
        mosGetpassExactSaveShare();
        break;

    case 'unshare':
        mosGetpassExactDeleteShare();
        break;

    case 'dashboard':
    case 'list':
        mosGetpassExactDashboard();
        break;

    default:
        mosInvalidURL();
        exit;
}

function getpassExactDefaultDate($offsetDays)
{
    return date('Y-m-d', strtotime($offsetDays . ' days'));
}

function getpassExactNormalizeDate($date, $default)
{
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return $default;
    }

    return $date;
}

function getpassExactFetchCategoryWeb($fromDate, $toDate)
{
    if (!defined('GETPASS_EXACT_API_URL') || !defined('GETPASS_EXACT_API_TOKEN')) {
        return array(false, 'Chua cau hinh GETPASS_EXACT_API_URL / GETPASS_EXACT_API_TOKEN', array());
    }

    $params = array(
        'token' => GETPASS_EXACT_API_TOKEN,
        'from_date' => $fromDate,
        'to_date' => $toDate,
    );

    $separator = (strpos(GETPASS_EXACT_API_URL, '?') === false) ? '?' : '&';
    $url = GETPASS_EXACT_API_URL . $separator . http_build_query($params);

    if (function_exists('curl_init')) {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $body = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($body === false) {
            return array(false, 'Khong goi duoc API Getpass Exact: ' . $error, array());
        }

        if ($httpCode >= 400) {
            return array(false, 'API Getpass Exact tra HTTP ' . $httpCode, array());
        }
    } else {
        $context = stream_context_create(array(
            'http' => array(
                'timeout' => 20
            )
        ));

        $body = @file_get_contents($url, false, $context);

        if ($body === false) {
            return array(false, 'Server chua bat curl va file_get_contents khong goi duoc API', array());
        }
    }

    $data = json_decode($body, true);

    if (!is_array($data)) {
        return array(false, 'API tra du lieu khong phai JSON hop le', array());
    }

    if (empty($data['success'])) {
        $message = isset($data['message']) ? $data['message'] : 'Du lieu API khong hop le hoac token sai';
        return array(false, $message, $data);
    }

    return array(true, '', $data);
}

function getpassExactHtml($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function getpassExactNormalizeWebsite($website)
{
    $website = strtolower(trim((string) $website));
    $website = preg_replace('/^https?:\/\//', '', $website);
    $website = preg_replace('/^www\./', '', $website);
    $website = preg_replace('/\/.*$/', '', $website);
    return trim($website);
}

function getpassExactSql($value)
{
    if (function_exists('mysql_real_escape_string')) {
        return mysql_real_escape_string((string) $value);
    }

    return addslashes((string) $value);
}

function getpassExactCurrentMemberId()
{
    return isset($_SESSION['login_id']) ? (int) $_SESSION['login_id'] : 0;
}

function getpassExactCanManageShares()
{
    $loginName = isset($_SESSION['loginname']) ? strtolower($_SESSION['loginname']) : '';
    $memberId = getpassExactCurrentMemberId();
    return $memberId == 1 || $memberId == 71 || $loginName == 'administrator';
}

function getpassExactEnsureShareTable()
{
    global $db;

    static $ready = false;
    if ($ready) {
        return;
    }

    $sql = "CREATE TABLE IF NOT EXISTS tbl_getpass_website_share (
        share_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        module varchar(30) NOT NULL DEFAULT 'getpass',
        website varchar(191) NOT NULL,
        member_id int(11) NOT NULL,
        created_by varchar(100) DEFAULT '',
        created_by_id int(11) DEFAULT NULL,
        created_date datetime DEFAULT NULL,
        modified_by varchar(100) DEFAULT '',
        modified_date datetime DEFAULT NULL,
        PRIMARY KEY (share_id),
        UNIQUE KEY uniq_module_member_website (module, member_id, website),
        KEY idx_member_id (member_id)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8";

    $db->sql_query($sql);
    $db->sql_query("ALTER TABLE tbl_getpass_website_share ADD module varchar(30) NOT NULL DEFAULT 'getpass'");
    $db->sql_query("ALTER TABLE tbl_getpass_website_share ADD website varchar(191) NOT NULL");
    $db->sql_query("ALTER TABLE tbl_getpass_website_share ADD created_by_id int(11) DEFAULT NULL");
    $db->sql_query("ALTER TABLE tbl_getpass_website_share ADD modified_by varchar(100) DEFAULT ''");
    $db->sql_query("ALTER TABLE tbl_getpass_website_share ADD modified_date datetime DEFAULT NULL");
    $db->sql_query("ALTER TABLE tbl_getpass_website_share MODIFY share_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT");
    $db->sql_query("UPDATE tbl_getpass_website_share SET website = website_name WHERE (website = '' OR website IS NULL) AND website_name IS NOT NULL");
    $db->sql_query("UPDATE tbl_getpass_website_share SET module = 'getpass_exact' WHERE module = '' OR module IS NULL");
    $db->sql_query("DELETE FROM tbl_getpass_website_share WHERE active = 0");
    $db->sql_query("ALTER TABLE tbl_getpass_website_share DROP INDEX uniq_member_website");
    $db->sql_query("ALTER TABLE tbl_getpass_website_share ADD UNIQUE KEY uniq_module_member_website (module, member_id, website)");
    $ready = true;
}

function getpassExactGetSharedWebsiteKeys()
{
    global $db;

    getpassExactEnsureShareTable();

    $memberId = getpassExactCurrentMemberId();
    $keys = array();

    if ($memberId <= 0) {
        return $keys;
    }

    $sql = "SELECT website FROM tbl_getpass_website_share WHERE member_id = $memberId AND module = 'getpass_exact'";
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) {
            $key = getpassExactNormalizeWebsite($row['website']);
            if ($key != '') {
                $keys[$key] = true;
            }
        }
    }

    return $keys;
}

function getpassExactFilterRowsByPermission($rows)
{
    if (!is_array($rows)) {
        return array();
    }

    if (getpassExactCanManageShares()) {
        return $rows;
    }

    $allowed = getpassExactGetSharedWebsiteKeys();
    if (empty($allowed)) {
        return array();
    }

    $filtered = array();
    foreach ($rows as $row) {
        $website = isset($row['website']) ? $row['website'] : '';
        $key = getpassExactNormalizeWebsite($website);
        if ($key != '' && isset($allowed[$key])) {
            $filtered[] = $row;
        }
    }

    return $filtered;
}

function getpassExactSumRowsByDate($rows, $date)
{
    $sum = 0;

    foreach ($rows as $row) {
        if (isset($row['counts']) && is_array($row['counts']) && isset($row['counts'][$date])) {
            $sum += (int) $row['counts'][$date];
        }
    }

    return $sum;
}

function getpassExactRenderTable($data)
{
    $dates = isset($data['dates']) && is_array($data['dates']) ? $data['dates'] : array();
    $rows = isset($data['rows']) && is_array($data['rows']) ? getpassExactFilterRowsByPermission($data['rows']) : array();

    $html = '<table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%" class="selector getpass-source-table" style="border-collapse:collapse">';
    $html .= '<tr class="header">';
    $html .= '<td width="70" align="left">STT</td>';
    $html .= '<td width="350" align="left">Website</td>';

    foreach ($dates as $date) {
        $html .= '<td width="140" align="left">' . getpassExactHtml($date) . '</td>';
    }

    $html .= '</tr>';

    if (empty($rows)) {
        $colspan = count($dates) + 2;
        $html .= '<tr class="alt"><td colspan="' . $colspan . '" style="padding:15px">Không có dữ liệu.</td></tr>';
    }

    $order = 0;
    foreach ($rows as $row) {
        $order++;
        $className = ($order % 2 == 1) ? 'alt' : 'inv';
        $website = isset($row['website']) ? $row['website'] : '';
        $viewUrl = isset($row['url']) ? $row['url'] : '';
        $counts = isset($row['counts']) && is_array($row['counts']) ? $row['counts'] : array();

        $html .= '<tr class="' . $className . '">';
        $html .= '<td align="left" style="vertical-align:middle">' . $order . '</td>';
        $html .= '<td style="vertical-align:middle"><b>' . getpassExactHtml($website) . '</b>';

        if ($viewUrl != '') {
            $html .= ' <a href="' . getpassExactHtml($viewUrl) . '" target="_blank">(Xem)</a>';
        }

        $html .= '</td>';

        foreach ($dates as $date) {
            $value = isset($counts[$date]) ? (int) $counts[$date] : 0;
            $html .= '<td style="vertical-align:middle"><b>' . $value . '</b></td>';
        }

        $html .= '</tr>';
    }

    $html .= '</table>';

    return $html;
}

function getpassExactRenderWebsiteOptions($rows, $selectedWebsite)
{
    $options = '<option value="">Chọn website</option>';
    $seen = array();

    if (!is_array($rows)) {
        return $options;
    }

    foreach ($rows as $row) {
        $website = isset($row['website']) ? trim($row['website']) : '';
        $key = getpassExactNormalizeWebsite($website);
        if ($website == '' || $key == '' || isset($seen[$key])) {
            continue;
        }

        $seen[$key] = true;
        $selected = ($key == getpassExactNormalizeWebsite($selectedWebsite)) ? ' selected' : '';
        $options .= '<option value="' . getpassExactHtml($website) . '"' . $selected . '>' . getpassExactHtml($website) . '</option>';
    }

    return $options;
}

function getpassExactRenderMemberOptions($selectedMemberId)
{
    global $db;

    $options = '<option value="0">Chọn người nhận</option>';
    $sql = "SELECT member_id, fullname, loginname FROM tbl_member WHERE active = 1 ORDER BY member_id";

    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) {
            $memberId = (int) $row['member_id'];
            $label = $memberId . ' - ' . $row['fullname'] . ' (' . $row['loginname'] . ')';
            $selected = ($memberId == (int) $selectedMemberId) ? ' selected' : '';
            $options .= '<option value="' . $memberId . '"' . $selected . '>' . getpassExactHtml($label) . '</option>';
        }
    }

    return $options;
}

function getpassExactRenderShareList()
{
    global $db;

    if (!getpassExactCanManageShares()) {
        return '';
    }

    getpassExactEnsureShareTable();

    $html = '';
    $sql = "SELECT s.*, m.fullname FROM tbl_getpass_website_share s LEFT JOIN tbl_member m ON s.member_id = m.member_id WHERE s.module = 'getpass_exact' ORDER BY s.share_id DESC LIMIT 50";
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) {
            $html .= '<form action="main.php" method="POST" class="getpass-share-chip">';
            $html .= '<input type="hidden" name="option" value="getpass_exact/getpass_exact">';
            $html .= '<input type="hidden" name="mode" value="unshare">';
            $html .= '<input type="hidden" name="id" value="' . (int) $row['share_id'] . '">';
            $html .= getpassExactHtml($row['website']) . ' -> ID ' . (int) $row['member_id'] . ' ' . getpassExactHtml($row['fullname']);
            $html .= ' <button type="submit">Xóa</button>';
            $html .= '</form>';
        }
    }

    return $html;
}

function mosGetpassExactSaveShare()
{
    global $template;

    $defaultTo = getpassExactDefaultDate(0);
    $defaultFrom = getpassExactDefaultDate(-6);
    $fromDate = getpassExactNormalizeDate(mosGetParam($_REQUEST, 'from_date', $defaultFrom), $defaultFrom);
    $toDate = getpassExactNormalizeDate(mosGetParam($_REQUEST, 'to_date', $defaultTo), $defaultTo);

    if (!getpassExactCanManageShares()) {
        $template->assign_vars(array('MESSAGE' => 'Ban khong co quyen share website.'));
        mosGetpassExactDashboard();
        return;
    }

    global $db;
    getpassExactEnsureShareTable();

    $website = trim(mosGetParam($_REQUEST, 'share_website', ''));
    $memberId = (int) mosGetParam($_REQUEST, 'share_member_id', 0);
    $websiteKey = getpassExactNormalizeWebsite($website);

    if ($websiteKey == '' || $memberId <= 0) {
        $_REQUEST['from_date'] = $fromDate;
        $_REQUEST['to_date'] = $toDate;
        $template->assign_vars(array('MESSAGE' => 'Vui long chon website va ID nguoi nhan.'));
        mosGetpassExactDashboard();
        return;
    }

    $websiteSql = getpassExactSql($website);
    $websiteKeySql = getpassExactSql($websiteKey);
    $createdBy = getpassExactSql(isset($_SESSION['membername']) ? $_SESSION['membername'] : '');
    $createdById = getpassExactCurrentMemberId();

    $sql = "INSERT INTO tbl_getpass_website_share (module, website, member_id, created_by, created_by_id, created_date, modified_by, modified_date)
            VALUES ('getpass_exact', '$websiteKeySql', $memberId, '$createdBy', $createdById, now(), '$createdBy', now())
            ON DUPLICATE KEY UPDATE modified_by = '$createdBy', modified_date = now()";
    $db->sql_query($sql);

    $_REQUEST['from_date'] = $fromDate;
    $_REQUEST['to_date'] = $toDate;
    $template->assign_vars(array('MESSAGE' => 'Da share website ' . getpassExactHtml($website) . ' cho ID ' . $memberId . '.'));
    mosGetpassExactDashboard();
}

function mosGetpassExactDeleteShare()
{
    global $db, $template;

    if (!getpassExactCanManageShares()) {
        $template->assign_vars(array('MESSAGE' => 'Ban khong co quyen xoa share website.'));
        mosGetpassExactDashboard();
        return;
    }

    getpassExactEnsureShareTable();

    $shareId = (int) mosGetParam($_REQUEST, 'id', 0);
    if ($shareId <= 0) {
        $template->assign_vars(array('MESSAGE' => 'Khong tim thay quyen share can xoa.'));
        mosGetpassExactDashboard();
        return;
    }

    $sql = "DELETE FROM tbl_getpass_website_share WHERE share_id = $shareId AND module = 'getpass_exact'";
    $db->sql_query($sql);

    $template->assign_vars(array('MESSAGE' => 'Da xoa quyen share website.'));
    mosGetpassExactDashboard();
}

function mosGetpassExactDashboard()
{
    global $template;

    $defaultTo = getpassExactDefaultDate(0);
    $defaultFrom = getpassExactDefaultDate(-6);

    $fromDate = getpassExactNormalizeDate(mosGetParam($_REQUEST, 'from_date', $defaultFrom), $defaultFrom);
    $toDate = getpassExactNormalizeDate(mosGetParam($_REQUEST, 'to_date', $defaultTo), $defaultTo);

    list($ok, $message, $data) = getpassExactFetchCategoryWeb($fromDate, $toDate);

    $today = isset($data['today']) ? $data['today'] : $toDate;
    $rows = isset($data['rows']) && is_array($data['rows']) ? getpassExactFilterRowsByPermission($data['rows']) : array();
    $todayTotal = $ok ? getpassExactSumRowsByDate($rows, $today) : 0;
    $notice = isset($template->_tpldata['.'][0]['MESSAGE']) ? $template->_tpldata['.'][0]['MESSAGE'] : '';

    $template->assign_vars(array(
        'STATUS_CLASS' => $ok ? 'ok' : 'error',
        'STATUS_TEXT' => $ok ? 'Đã kết nối API Getpass Exact' : getpassExactHtml($message),
        'MESSAGE' => $notice,
        'MESSAGE_CLASS' => ($notice == '') ? 'hide' : '',
        'PAGE_TITLE' => 'Quản lý nguồn website',
        'FROM_DATE' => getpassExactHtml($fromDate),
        'TO_DATE' => getpassExactHtml($toDate),
        'TODAY' => getpassExactHtml($today),
        'TODAY_TOTAL' => number_format($todayTotal),
        'TABLE_HTML' => $ok ? getpassExactRenderTable($data) : '',
        'SHARE_PANEL_DISPLAY' => getpassExactCanManageShares() ? 'flex' : 'none',
        'SHARE_WEBSITE_OPTIONS' => getpassExactRenderWebsiteOptions(isset($data['rows']) ? $data['rows'] : array(), mosGetParam($_REQUEST, 'share_website', '')),
        'SHARE_MEMBER_OPTIONS' => getpassExactRenderMemberOptions(mosGetParam($_REQUEST, 'share_member_id', 0)),
        'SHARE_LIST_HTML' => getpassExactRenderShareList(),
    ));

    $template->set_filenames_new(array(
        'getpass' => 'getpass_exact/getpass_exact_main.html'
    ));

    $template->pparse('getpass');
}
?>
