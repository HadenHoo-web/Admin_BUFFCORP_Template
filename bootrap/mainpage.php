<?php
include('common.php');
session_name('admintool');
session_start();
checkLogin();
require_once('includes/notifications.php');
$languageid = mosGetParam($_REQUEST, 'l', 2);
$langpath   = getLangPath(2);
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
$template = new Template();
$template->set_filenames([
    'body' => "templates/mainpage/default.tpl"
]);
$mainContentClass = 'main-content';
if (isset($mainTemplate) && strpos($mainTemplate, 'admin_dashboard.php') !== false) {
    $mainContentClass .= ' admin-dashboard-shell';
}
$template->assign_vars([
    'theme'      => $theme,
    'skin'       => $skin,
    'domain'     => $website,
    'MAIN_CONTENT_CLASS' => $mainContentClass,
    'LANGUAGEID' => $languageid,
    'NOTIFICATION_COUNT' => ($notificationUnread > 99 ? '99+' : $notificationUnread),
    'NOTIFICATION_CLASS' => ($notificationUnread > 0 ? 'notify-count show' : 'notify-count'),
    'NOTIFICATION_WRAP_CLASS' => ($notificationUnread > 0 ? 'notify-wrap has-unread' : 'notify-wrap'),
    'ADMIN_HOME_DISPLAY' => ($isPayrollAdminUser ? 'flex' : 'none'),
    'PAYROLL_PREVIEW_DISPLAY' => ($isPayrollPreviewUser ? 'block' : 'none'),
    'PAYROLL_ADMIN_DISPLAY' => ($isPayrollAdminUser ? 'inline-block' : 'none'),
    'CURRENT_MONTH' => date('m'),
    'CURRENT_YEAR' => date('Y')
]);
ob_start();
$_REQUEST['option'] = 'navigation/index';
include 'modules/navigation/index.php';
$LEFT_MENU = ob_get_clean();
ob_start();
include $mainTemplate;
$MAIN_CONTENT = ob_get_clean();
$template->assign_vars([
    'LEFT_MENU'   => $LEFT_MENU,
    'MAIN_CONTENT'=> $MAIN_CONTENT
]);

$template->pparse('body');
