<?php
include('common.php');
session_name('admintool');
session_start();
checkLogin();
require_once('includes/notifications.php');
require_once('includes/ui_layout.php');
$languageid = mosGetParam($_REQUEST, 'l', 2);
$langpath   = getLangPath(2);
$requestedOption = isset($_REQUEST['option']) ? $_REQUEST['option'] : '';
$requestedMode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$resolvedOption = $requestedOption;
if ($resolvedOption === '' && isset($mainTemplate)
    && preg_match('#^modules/(.+)\.php$#', $mainTemplate, $optionMatch)) {
    $resolvedOption = $optionMatch[1];
}
$resolvedMode = $requestedMode !== ''
    ? $requestedMode
    : ($resolvedOption === 'common_lists/admin_dashboard' ? 'dashboard' : 'list');
function buffcorpResolvePageTitle($option)
{
    global $db;
    $option = trim((string)$option);
    if ($option === 'chat/chat') return 'Chat';
    if ($option === 'functionmenu/functionmenu') return 'Quản lý menu';
    if ($option === '' || !isset($db)) return 'Tổng quan';
    $mode = isset($_REQUEST['mode']) ? trim((string)$_REQUEST['mode']) : '';
    if ($option === 'functions/functions' && in_array($mode, array('permission_list', 'permission_save'))) {
        $code = isset($_REQUEST['id']) ? trim((string)$_REQUEST['id']) : '';
        if ($code !== '') {
            $safeCode = addslashes($code);
            $sql = "select fun_name from tbl_function_menu where code='$safeCode' limit 1";
            if ($result = $db->sql_query($sql)) {
                $row = $db->sql_fetchrow($result);
                if ($row && isset($row['fun_name']) && trim($row['fun_name']) !== '') {
                    return 'Phân quyền: ' . trim($row['fun_name']);
                }
            }
        }
        return 'Phân quyền chức năng';
    }
    $safeOption = addslashes($option);
    $sql = "select fun_name from tbl_function_menu
            where parent_id > 0 and link like '%option=$safeOption%'
            order by priority limit 1";
    if ($result = $db->sql_query($sql)) {
        $row = $db->sql_fetchrow($result);
        if ($row && isset($row['fun_name']) && trim($row['fun_name']) !== '') return trim($row['fun_name']);
    }
    return 'Tổng quan';
}
$pageTitle = buffcorpResolvePageTitle($resolvedOption);
if ($resolvedOption === 'common_lists/giaoviec' && $resolvedMode === 'dashboard') {
    $pageTitle = 'Dashboard KPI Content';
}
$loginId = isset($_SESSION["login_id"]) ? (int)$_SESSION["login_id"] : 0;
$notificationUnread = notificationUnreadCount($loginId);
$isPayrollPreviewUser = (
    (isset($_SESSION["loginname"]) && strtolower($_SESSION["loginname"]) == 'administrator')
    || (isset($_SESSION["membername"]) && strtolower($_SESSION["membername"]) == 'administrator')
    || $loginId == 71
    || $loginId == 28
);
$isPayrollAdminUser = (
    (isset($_SESSION["loginname"]) && strtolower($_SESSION["loginname"]) == 'administrator')
    || (isset($_SESSION["membername"]) && strtolower($_SESSION["membername"]) == 'administrator')
    || $loginId == 71
);
$userDisplayName = trim(isset($_SESSION["membername"]) && $_SESSION["membername"] !== ''
    ? $_SESSION["membername"]
    : (isset($_SESSION["loginname"]) ? $_SESSION["loginname"] : 'Người dùng'));
$userWords = preg_split('/\s+/u', $userDisplayName, -1, PREG_SPLIT_NO_EMPTY);
$userInitial = '';
foreach (array_unique([0, count($userWords) - 1]) as $wordIndex) {
    if (!isset($userWords[$wordIndex])) continue;
    $userInitial .= function_exists('mb_substr')
        ? mb_substr($userWords[$wordIndex], 0, 1, 'UTF-8')
        : substr($userWords[$wordIndex], 0, 1);
}
$userInitial = function_exists('mb_strtoupper') ? mb_strtoupper($userInitial, 'UTF-8') : strtoupper($userInitial);
$userProfileUrl = 'main.php?option=members/members&mode=info&l=' . rawurlencode((string)$languageid) . '&id=' . rawurlencode((string)$loginId);
$userAccountUrl = 'main.php?option=members/change_password&mode=list&l=' . rawurlencode((string)$languageid);
$template = new Template();
$template->set_filenames([
    'body' => "templates/mainpage/default.tpl"
]);
$mainContentClass = 'main-content';
if (isset($mainTemplate) && strpos($mainTemplate, 'admin_dashboard.php') !== false) {
    $mainContentClass .= ' admin-dashboard-shell';
}
if ($resolvedOption === 'chat/chat') {
    $mainContentClass .= ' chat-shell';
}
$dashboardHeaderOptions = array('common_lists/giaoviec', 'common_lists/kpi_tonghop', 'congno/congno');
if (in_array($resolvedOption, $dashboardHeaderOptions) && $resolvedMode === 'dashboard') {
    $mainContentClass .= ' dashboard-header-icons';
}
$template->assign_vars([
    'theme'      => $theme,
    'skin'       => $skin,
    'domain'     => $website,
    'MAIN_CONTENT_CLASS' => $mainContentClass,
    'CURRENT_OPTION' => htmlspecialchars($resolvedOption, ENT_QUOTES, 'UTF-8'),
    'CURRENT_MODE' => htmlspecialchars($resolvedMode, ENT_QUOTES, 'UTF-8'),
    'LANGUAGEID' => $languageid,
    'NOTIFICATION_COUNT' => ($notificationUnread > 99 ? '99+' : $notificationUnread),
    'NOTIFICATION_CLASS' => ($notificationUnread > 0 ? 'notify-count show' : 'notify-count'),
    'NOTIFICATION_WRAP_CLASS' => ($notificationUnread > 0 ? 'notify-wrap has-unread' : 'notify-wrap'),
    'ADMIN_HOME_DISPLAY' => ($isPayrollAdminUser ? 'flex' : 'none'),
    'PAYROLL_PREVIEW_DISPLAY' => ($isPayrollPreviewUser ? 'block' : 'none'),
    'PAYROLL_ADMIN_DISPLAY' => ($isPayrollAdminUser ? 'inline-block' : 'none'),
    'USER_DISPLAY_NAME' => htmlspecialchars($userDisplayName, ENT_QUOTES, 'UTF-8'),
    'USER_INITIAL' => htmlspecialchars($userInitial, ENT_QUOTES, 'UTF-8'),
    'USER_PROFILE_URL' => htmlspecialchars($userProfileUrl, ENT_QUOTES, 'UTF-8'),
    'USER_ACCOUNT_URL' => htmlspecialchars($userAccountUrl, ENT_QUOTES, 'UTF-8'),
    'USER_ROLE' => ($isPayrollAdminUser ? 'Quản trị hệ thống' : 'Nhân viên'),
    'CURRENT_MONTH' => date('m'),
    'CURRENT_YEAR' => date('Y'),
    'PAGE_TITLE' => htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8')
]);
ob_start();
$GLOBALS['buffcorp_current_option'] = $resolvedOption;
$_REQUEST['option'] = 'navigation/index';
include 'modules/navigation/index.php';
$LEFT_MENU = ob_get_clean();
unset($GLOBALS['buffcorp_current_option']);
if ($requestedOption !== '') $_REQUEST['option'] = $requestedOption;
else unset($_REQUEST['option']);
ob_start();
include $mainTemplate;
$MAIN_CONTENT = ob_get_clean();
$MAIN_CONTENT = buffcorpPrepareModuleHtml(
    $MAIN_CONTENT,
    $resolvedOption,
    $resolvedMode
);
$template->assign_vars([
    'LEFT_MENU'   => $LEFT_MENU,
    'MAIN_CONTENT'=> $MAIN_CONTENT
]);

$template->pparse('body');
