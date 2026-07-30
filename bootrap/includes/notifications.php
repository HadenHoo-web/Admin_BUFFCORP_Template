<?php
if (!defined('_CMS_')) die('HACKING_ATTEMPT');

function notificationEnsureTable()
{
    global $db;
    static $checked = false;
    if ($checked) return;
    $checked = true;

    $sql = "CREATE TABLE IF NOT EXISTS tbl_notifications (
        notification_id int(11) NOT NULL AUTO_INCREMENT,
        member_id int(11) NOT NULL,
        actor_id int(11) NOT NULL DEFAULT 0,
        type varchar(50) NOT NULL DEFAULT '',
        title varchar(255) NOT NULL DEFAULT '',
        message text,
        link varchar(255) NOT NULL DEFAULT '',
        is_read tinyint(1) NOT NULL DEFAULT 0,
        created_date datetime NOT NULL,
        read_date datetime DEFAULT NULL,
        PRIMARY KEY (notification_id),
        KEY idx_member_read_date (member_id, is_read, created_date),
        KEY idx_type_date (type, created_date)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
    if (!$db->sql_query($sql)) {
        notificationLog('ENSURE_TABLE_FAIL', $sql);
    }
}

function notificationSql($value)
{
    return addslashes($value);
}

function notificationLog($type, $detail)
{
    $logFile = dirname(__DIR__).'/logs/notification.log';
    $line = date('Y-m-d H:i:s').' | '.$type.' | '.$detail.PHP_EOL;
    @file_put_contents($logFile, $line, FILE_APPEND);
}

function notificationCreate($memberId, $type, $title, $message, $link = '', $actorId = 0)
{
    global $db;
    $memberId = (int)$memberId;
    if ($memberId <= 0) {
        notificationLog('SKIP_INVALID_MEMBER', 'type='.$type.' title='.$title);
        return false;
    }

    notificationEnsureTable();

    $actorId = (int)$actorId;
    $sql = "INSERT INTO tbl_notifications
        (member_id, actor_id, type, title, message, link, is_read, created_date)
        VALUES
        ($memberId, $actorId, '".notificationSql($type)."', '".notificationSql($title)."',
         '".notificationSql($message)."', '".notificationSql($link)."', 0, NOW())";
    $ok = $db->sql_query($sql);
    if (!$ok) {
        $error = method_exists($db, 'sql_error') ? $db->sql_error() : array('message' => 'unknown');
        notificationLog('INSERT_FAIL', 'member_id='.$memberId.' type='.$type.' error='.print_r($error, true).' sql='.$sql);
    } else {
        notificationLog('INSERT_OK', 'member_id='.$memberId.' actor_id='.$actorId.' type='.$type.' title='.$title);
    }
    return $ok;
}

function notificationCreateMany($memberIds, $type, $title, $message, $link = '', $actorId = 0)
{
    $done = array();
    foreach ($memberIds as $memberId) {
        $memberId = (int)$memberId;
        if ($memberId <= 0 || isset($done[$memberId])) continue;
        notificationCreate($memberId, $type, $title, $message, $link, $actorId);
        $done[$memberId] = true;
    }
}

function notificationActiveMemberIds()
{
    global $db;
    $ids = array();
    notificationEnsureTable();

    $sql = "SELECT member_id FROM tbl_member WHERE active = 1";
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) {
            $memberId = (int)$row['member_id'];
            if ($memberId > 0) $ids[] = $memberId;
        }
    }
    return $ids;
}

function notificationCreateForActiveMembers($type, $title, $message, $link = '', $actorId = 0)
{
    notificationCreateMany(notificationActiveMemberIds(), $type, $title, $message, $link, $actorId);
}

function notificationDepartmentIds($departmentName)
{
    global $db;
    $ids = array();
    notificationEnsureTable();

    $departmentName = notificationSql(strtolower($departmentName));
    $departmentId = 0;
    $sql = "SELECT customer_type_id FROM tbl_customer_type WHERE LOWER(customer_type_name) = '$departmentName' LIMIT 1";
    if ($result = $db->sql_query($sql)) {
        if ($row = $db->sql_fetchrow($result)) $departmentId = (int)$row['customer_type_id'];
    }
    if ($departmentId <= 0) return $ids;

    $sql = "SELECT member_id FROM tbl_member
        WHERE active = 1 AND (member_type_id = $departmentId OR extra_member_type_id = $departmentId)";
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) $ids[] = (int)$row['member_id'];
    }
    return $ids;
}

function notificationListForMember($memberId, $limit = 0)
{
    global $db;
    $items = array();
    $memberId = (int)$memberId;
    $limit = (int)$limit;
    if ($memberId <= 0) return $items;

    notificationEnsureTable();

    $sql = "SELECT notification_id, type, title, message, link, is_read, created_date
        FROM tbl_notifications
        WHERE member_id = $memberId
          AND (
            is_read = 0
            OR (
              is_read = 1
              AND (
                read_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)
                OR (read_date IS NULL AND created_date >= DATE_SUB(NOW(), INTERVAL 1 DAY))
              )
            )
          )
        ORDER BY is_read ASC, created_date DESC, notification_id DESC";
    if ($limit > 0) {
        if ($limit > 200) $limit = 200;
        $sql .= " LIMIT $limit";
    }
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) $items[] = $row;
    }
    return $items;
}

function notificationUnreadCount($memberId)
{
    global $db;
    $memberId = (int)$memberId;
    if ($memberId <= 0) return 0;

    notificationEnsureTable();

    $sql = "SELECT COUNT(*) AS total FROM tbl_notifications WHERE member_id = $memberId AND is_read = 0";
    if ($result = $db->sql_query($sql)) {
        if ($row = $db->sql_fetchrow($result)) return (int)$row['total'];
    }
    return 0;
}

function notificationMarkRead($memberId)
{
    global $db;
    $memberId = (int)$memberId;
    if ($memberId <= 0) return false;

    notificationEnsureTable();

    $sql = "UPDATE tbl_notifications SET is_read = 1, read_date = NOW()
        WHERE member_id = $memberId AND is_read = 0";
    return $db->sql_query($sql);
}

function notificationMarkOneRead($memberId, $notificationId)
{
    global $db;
    $memberId = (int)$memberId;
    $notificationId = (int)$notificationId;
    if ($memberId <= 0 || $notificationId <= 0) return false;

    notificationEnsureTable();

    $sql = "UPDATE tbl_notifications SET is_read = 1, read_date = NOW()
        WHERE member_id = $memberId AND notification_id = $notificationId";
    return $db->sql_query($sql);
}
?>
