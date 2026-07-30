<?php
define('_CMS_', true);
include('common.php');
session_name('admintool');
session_start();
checkLogin();
require_once('includes/notifications.php');

header('Content-Type: application/json; charset=utf-8');

$memberId = isset($_SESSION['login_id']) ? (int)$_SESSION['login_id'] : 0;
$action = mosGetParam($_REQUEST, 'action', 'list');

if ($memberId <= 0) {
    echo json_encode(array('ok' => false, 'message' => 'NO_LOGIN'));
    exit;
}

if ($action == 'test') {
    notificationCreate(
        $memberId,
        'test',
        'Test thông báo',
        'Thông báo test cho tài khoản đang đăng nhập.',
        'main.php?option=common_lists/giaoviec&mode=list&l=2',
        $memberId
    );
}

if ($action == 'read') {
    notificationMarkRead($memberId);
}

if ($action == 'read_one') {
    $notificationId = mosGetParam($_REQUEST, 'id', 0);
    notificationMarkOneRead($memberId, $notificationId);
}

$items = notificationListForMember($memberId);
$data = array();
foreach ($items as $row) {
    $data[] = array(
        'id' => (int)$row['notification_id'],
        'type' => $row['type'],
        'title' => $row['title'],
        'message' => $row['message'],
        'link' => $row['link'],
        'is_read' => (int)$row['is_read'],
        'created_date' => $row['created_date']
    );
}

echo json_encode(array(
    'ok' => true,
    'unread' => notificationUnreadCount($memberId),
    'items' => $data
));
exit;
?>
