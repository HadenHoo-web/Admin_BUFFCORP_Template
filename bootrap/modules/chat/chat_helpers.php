<?php
if (!defined('_CMS_')) {
    if (PHP_SAPI === 'cli') {
        define('_CMS_', true);
    } else {
        die('HACKING_ATTEMPT');
    }
}

function chatSql($value)
{
    global $db;
    return mysqli_real_escape_string($db->db_connect_id, (string)$value);
}

function chatHtml($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function chatEnsureSchema()
{
    global $db;

    $db->sql_query("CREATE TABLE IF NOT EXISTS tbl_chat_rooms (
        room_id int(11) NOT NULL AUTO_INCREMENT,
        user_one_id int(11) NOT NULL,
        user_two_id int(11) NOT NULL,
        last_message_id int(11) NOT NULL DEFAULT 0,
        last_message_at datetime DEFAULT NULL,
        created_date datetime NOT NULL,
        PRIMARY KEY (room_id),
        UNIQUE KEY uniq_chat_pair (user_one_id, user_two_id),
        KEY idx_last_message_at (last_message_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

    $db->sql_query("CREATE TABLE IF NOT EXISTS tbl_chat_messages (
        message_id int(11) NOT NULL AUTO_INCREMENT,
        room_id int(11) NOT NULL,
        sender_id int(11) NOT NULL,
        receiver_id int(11) NOT NULL,
        message_text text,
        has_attachment tinyint(1) NOT NULL DEFAULT 0,
        created_date datetime NOT NULL,
        seen_date datetime DEFAULT NULL,
        PRIMARY KEY (message_id),
        KEY idx_room_message (room_id, message_id),
        KEY idx_receiver_seen (receiver_id, seen_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

    $db->sql_query("CREATE TABLE IF NOT EXISTS tbl_chat_attachments (
        attachment_id int(11) NOT NULL AUTO_INCREMENT,
        message_id int(11) NOT NULL,
        original_name varchar(255) NOT NULL,
        file_name varchar(255) NOT NULL,
        file_path varchar(255) NOT NULL,
        mime_type varchar(120) NOT NULL DEFAULT '',
        file_size int(11) NOT NULL DEFAULT 0,
        created_date datetime NOT NULL,
        PRIMARY KEY (attachment_id),
        KEY idx_message_id (message_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

    $db->sql_query("CREATE TABLE IF NOT EXISTS tbl_chat_online (
        member_id int(11) NOT NULL,
        last_seen datetime NOT NULL,
        PRIMARY KEY (member_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
}

function chatTouchOnline($memberId)
{
    global $db;
    $memberId = (int)$memberId;
    if ($memberId <= 0) return;
    $db->sql_query("REPLACE INTO tbl_chat_online (member_id, last_seen) VALUES ($memberId, now())");
}

function chatCurrentUser($memberId)
{
    global $db;
    $memberId = (int)$memberId;
    $user = array('id' => $memberId, 'name' => 'User', 'department' => '', 'avatar' => '');
    $sql = "SELECT m.member_id, m.fullname, m.loginname, m.avatar, ct.customer_type_name, extra.customer_type_name AS extra_department
            FROM tbl_member m
            LEFT JOIN tbl_customer_type ct ON m.member_type_id = ct.customer_type_id
            LEFT JOIN tbl_customer_type extra ON m.extra_member_type_id = extra.customer_type_id
            WHERE m.member_id = $memberId LIMIT 1";
    if ($result = $db->sql_query($sql)) {
        if ($row = $db->sql_fetchrow($result)) {
            $name = trim((string)$row['fullname']) != '' ? $row['fullname'] : $row['loginname'];
            $department = trim((string)$row['customer_type_name']);
            if (trim((string)$row['extra_department']) != '') {
                $department .= ($department != '' ? ' / ' : '') . $row['extra_department'];
            }
            $user = array('id' => (int)$row['member_id'], 'name' => $name, 'department' => $department, 'avatar' => trim((string)$row['avatar']));
        }
    }
    return $user;
}

function chatInitials($name)
{
    $name = trim(strip_tags((string)$name));
    if ($name == '') return 'U';
    $parts = preg_split('/\s+/u', $name);
    $first = mb_substr($parts[0], 0, 1, 'UTF-8');
    $last = count($parts) > 1 ? mb_substr($parts[count($parts) - 1], 0, 1, 'UTF-8') : '';
    return chatHtml(mb_strtoupper($first . $last, 'UTF-8'));
}

function chatRoomId($userId, $peerId)
{
    global $db;
    $userId = (int)$userId;
    $peerId = (int)$peerId;
    if ($userId <= 0 || $peerId <= 0 || $userId == $peerId) return 0;
    $one = min($userId, $peerId);
    $two = max($userId, $peerId);
    $sql = "SELECT room_id FROM tbl_chat_rooms WHERE user_one_id = $one AND user_two_id = $two LIMIT 1";
    if ($result = $db->sql_query($sql)) {
        if ($row = $db->sql_fetchrow($result)) return (int)$row['room_id'];
    }
    $db->sql_query("INSERT INTO tbl_chat_rooms (user_one_id, user_two_id, created_date) VALUES ($one, $two, now())");
    return (int)$db->sql_nextid();
}

function chatTimeAgo($date)
{
    $time = strtotime((string)$date);
    if (!$time) return '';
    return date('H:i', $time);
}

function chatWeekdayName($time)
{
    $days = array(
        1 => 'Thứ 2',
        2 => 'Thứ 3',
        3 => 'Thứ 4',
        4 => 'Thứ 5',
        5 => 'Thứ 6',
        6 => 'Thứ 7',
        7 => 'Chủ nhật',
    );
    $day = (int)date('N', $time);
    return isset($days[$day]) ? $days[$day] : date('d/m/Y', $time);
}

function chatMessageGroupTime($date)
{
    $time = strtotime((string)$date);
    if (!$time) return '';
    if (date('Y-m-d', $time) == date('Y-m-d')) {
        return date('H:i', $time);
    }
    return chatWeekdayName($time) . ' ' . date('H:i', $time);
}

function chatMessageHoverTime($date)
{
    $time = strtotime((string)$date);
    if (!$time) return '';
    return chatWeekdayName($time) . ', ' . date('d/m/Y H:i', $time);
}

function chatJson($payload)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload);
    exit;
}
?>
