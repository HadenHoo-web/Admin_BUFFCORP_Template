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
    'USER_ROLE' => ($isPayrollAdminUser ? 'Quản trị hệ thống' : 'Nhân viên'),
    'CURRENT_MONTH' => date('m'),
    'CURRENT_YEAR' => date('Y')
]);
ob_start();
$_REQUEST['option'] = 'navigation/index';
include 'modules/navigation/index.php';
$LEFT_MENU = ob_get_clean();
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
