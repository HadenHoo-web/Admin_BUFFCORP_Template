<?php
session_name('admintool');
session_start();

define('_CMS_', true);
$bootrapRoot = dirname(__DIR__, 2);
chdir($bootrapRoot);
require_once $bootrapRoot . '/common.php';
checkLogin();
require_once __DIR__ . '/chat_helpers.php';

chatEnsureSchema();

$loginId = isset($_SESSION["login_id"]) ? (int)$_SESSION["login_id"] : 0;
if ($loginId <= 0) chatJson(array('ok' => false, 'message' => 'Bạn chưa đăng nhập.'));

chatTouchOnline($loginId);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'users';
switch ($action) {
    case 'users':
        chatApiUsers($loginId);
        break;
    case 'messages':
        chatApiMessages($loginId);
        break;
    case 'send':
        chatApiSend($loginId);
        break;
    case 'heartbeat':
        chatJson(array('ok' => true, 'server_time' => date('Y-m-d H:i:s')));
        break;
    default:
        chatJson(array('ok' => false, 'message' => 'Action không hợp lệ.'));
}

function chatApiUsers($loginId)
{
    global $db;
    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
    $cond = "m.active = 1 AND m.member_id <> " . (int)$loginId;
    if ($q != '') {
        $kw = chatSql($q);
        $cond .= " AND (m.fullname LIKE '%$kw%' OR m.loginname LIKE '%$kw%' OR ct.customer_type_name LIKE '%$kw%' OR extra.customer_type_name LIKE '%$kw%')";
    }

    $sql = "SELECT m.member_id, m.fullname, m.loginname, m.avatar, ct.customer_type_name, extra.customer_type_name AS extra_department,
                o.last_seen,
                TIMESTAMPDIFF(SECOND, o.last_seen, now()) AS seconds_ago,
                lm.message_text AS last_message,
                lm.created_date AS last_message_at,
                unread.total_unread
            FROM tbl_member m
            LEFT JOIN tbl_customer_type ct ON m.member_type_id = ct.customer_type_id
            LEFT JOIN tbl_customer_type extra ON m.extra_member_type_id = extra.customer_type_id
            LEFT JOIN tbl_chat_online o ON m.member_id = o.member_id
            LEFT JOIN tbl_chat_rooms r ON ((r.user_one_id = $loginId AND r.user_two_id = m.member_id) OR (r.user_two_id = $loginId AND r.user_one_id = m.member_id))
            LEFT JOIN tbl_chat_messages lm ON r.last_message_id = lm.message_id
            LEFT JOIN (
                SELECT sender_id, COUNT(*) AS total_unread
                FROM tbl_chat_messages
                WHERE receiver_id = $loginId AND seen_date IS NULL
                GROUP BY sender_id
            ) unread ON unread.sender_id = m.member_id
            WHERE $cond
            ORDER BY (o.last_seen >= DATE_SUB(now(), INTERVAL 2 MINUTE)) DESC, r.last_message_at DESC, m.fullname, m.loginname
            LIMIT 80";

    $users = array();
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) {
            $name = trim((string)$row['fullname']) != '' ? $row['fullname'] : $row['loginname'];
            $department = trim((string)$row['customer_type_name']);
            if (trim((string)$row['extra_department']) != '') {
                $department .= ($department != '' ? ' / ' : '') . $row['extra_department'];
            }
            $online = isset($row['seconds_ago']) && $row['seconds_ago'] !== null && (int)$row['seconds_ago'] <= 120;
            $users[] = array(
                'id' => (int)$row['member_id'],
                'name' => $name,
                'department' => $department != '' ? $department : 'Chưa cập nhật bộ phận',
                'initials' => chatInitials($name),
                'avatar' => trim((string)$row['avatar']),
                'online' => $online,
                'last_seen' => $row['last_seen'] ? date('H:i', strtotime($row['last_seen'])) : 'chưa online',
                'last_message' => trim((string)$row['last_message']),
                'last_message_at' => $row['last_message_at'] ? date('H:i', strtotime($row['last_message_at'])) : '',
                'unread' => isset($row['total_unread']) ? (int)$row['total_unread'] : 0
            );
        }
    }
    chatJson(array('ok' => true, 'users' => $users));
}

function chatApiMessages($loginId)
{
    global $db;
    $peerId = isset($_GET['peer_id']) ? (int)$_GET['peer_id'] : 0;
    $afterId = isset($_GET['after_id']) ? (int)$_GET['after_id'] : 0;
    if ($peerId <= 0 || $peerId == $loginId) chatJson(array('ok' => false, 'message' => 'User không hợp lệ.'));

    $roomId = chatRoomId($loginId, $peerId);
    if ($roomId <= 0) chatJson(array('ok' => false, 'message' => 'Không tạo được phòng chat.'));

    $db->sql_query("UPDATE tbl_chat_messages SET seen_date = now() WHERE room_id = $roomId AND receiver_id = $loginId AND seen_date IS NULL");

    $peer = chatCurrentUser($peerId);
    $cond = $afterId > 0 ? "AND msg.message_id > $afterId" : "";
    $limit = $afterId > 0 ? 80 : 60;
    $sql = "SELECT msg.*, a.attachment_id, a.original_name, a.file_path, a.mime_type, a.file_size
            FROM tbl_chat_messages msg
            LEFT JOIN tbl_chat_attachments a ON msg.message_id = a.message_id
            WHERE msg.room_id = $roomId $cond
            ORDER BY msg.message_id DESC
            LIMIT $limit";

    $rows = array();
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) $rows[] = $row;
    }
    $rows = array_reverse($rows);

    $messages = array();
    foreach ($rows as $row) {
        $createdTs = strtotime($row['created_date']);
        $message = array(
            'id' => (int)$row['message_id'],
            'sender_id' => (int)$row['sender_id'],
            'mine' => (int)$row['sender_id'] == $loginId,
            'text' => (string)$row['message_text'],
            'created_at' => date('d/m/Y H:i', $createdTs),
            'created_ts' => $createdTs,
            'group_time' => chatMessageGroupTime($row['created_date']),
            'hover_time' => chatMessageHoverTime($row['created_date']),
            'time_ago' => date('H:i', $createdTs),
            'attachment' => null
        );
        if (!empty($row['attachment_id'])) {
            $message['attachment'] = array(
                'name' => $row['original_name'],
                'url' => $row['file_path'],
                'mime' => $row['mime_type'],
                'size' => (int)$row['file_size']
            );
        }
        $messages[] = $message;
    }

    chatJson(array('ok' => true, 'peer' => array(
        'id' => $peer['id'],
        'name' => $peer['name'],
        'department' => $peer['department'] != '' ? $peer['department'] : 'Chưa cập nhật bộ phận',
        'initials' => chatInitials($peer['name']),
        'avatar' => isset($peer['avatar']) ? $peer['avatar'] : ''
    ), 'messages' => $messages));
}

function chatApiSend($loginId)
{
    global $db;
    $receiverId = isset($_POST['receiver_id']) ? (int)$_POST['receiver_id'] : 0;
    $messageText = isset($_POST['message']) ? trim((string)$_POST['message']) : '';
    if ($receiverId <= 0 || $receiverId == $loginId) chatJson(array('ok' => false, 'message' => 'Người nhận không hợp lệ.'));

    $hasFile = isset($_FILES['attachment']) && isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '' && $_FILES['attachment']['error'] != UPLOAD_ERR_NO_FILE;
    if ($messageText == '' && !$hasFile) chatJson(array('ok' => false, 'message' => 'Vui lòng nhập tin nhắn hoặc chọn file.'));

    $roomId = chatRoomId($loginId, $receiverId);
    if ($roomId <= 0) chatJson(array('ok' => false, 'message' => 'Không tạo được phòng chat.'));

    $fileMeta = null;
    if ($hasFile) {
        $fileMeta = chatSaveAttachmentUpload();
        if (!$fileMeta['ok']) chatJson($fileMeta);
    }

    $safeText = chatSql($messageText);
    $hasAttachment = $fileMeta ? 1 : 0;
    $db->sql_query("INSERT INTO tbl_chat_messages (room_id, sender_id, receiver_id, message_text, has_attachment, created_date)
                    VALUES ($roomId, $loginId, $receiverId, '$safeText', $hasAttachment, now())");
    $messageId = (int)$db->sql_nextid();
    if ($messageId <= 0) chatJson(array('ok' => false, 'message' => 'Không gửi được tin nhắn.'));

    if ($fileMeta) {
        $original = chatSql($fileMeta['original_name']);
        $fileName = chatSql($fileMeta['file_name']);
        $filePath = chatSql($fileMeta['file_path']);
        $mime = chatSql($fileMeta['mime_type']);
        $size = (int)$fileMeta['file_size'];
        $db->sql_query("INSERT INTO tbl_chat_attachments (message_id, original_name, file_name, file_path, mime_type, file_size, created_date)
                        VALUES ($messageId, '$original', '$fileName', '$filePath', '$mime', $size, now())");
    }

    $db->sql_query("UPDATE tbl_chat_rooms SET last_message_id = $messageId, last_message_at = now() WHERE room_id = $roomId");
    chatJson(array('ok' => true, 'message_id' => $messageId));
}

function chatSaveAttachmentUpload()
{
    $file = $_FILES['attachment'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return array('ok' => false, 'message' => 'Upload file không thành công.');
    }
    if ((int)$file['size'] > 20 * 1024 * 1024) {
        return array('ok' => false, 'message' => 'File vượt quá 20MB.');
    }

    $original = basename($file['name']);
    $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    $blocked = array('php', 'phtml', 'php3', 'php4', 'php5', 'phar', 'cgi', 'pl', 'sh', 'exe', 'bat', 'cmd', 'js', 'html', 'htm');
    if (in_array($ext, $blocked)) {
        return array('ok' => false, 'message' => 'Loại file này không được phép gửi.');
    }

    $dir = dirname(__DIR__, 2) . '/uploads/chat';
    if (!is_dir($dir)) @mkdir($dir, 0775, true);
    $safeExt = $ext != '' ? '.' . preg_replace('/[^a-z0-9]/', '', $ext) : '';
    $fileName = date('YmdHis') . '_' . substr(md5(uniqid('', true)), 0, 10) . $safeExt;
    $target = $dir . '/' . $fileName;
    if (!move_uploaded_file($file['tmp_name'], $target)) {
        return array('ok' => false, 'message' => 'Không lưu được file upload.');
    }

    return array(
        'ok' => true,
        'original_name' => $original,
        'file_name' => $fileName,
        'file_path' => 'uploads/chat/' . $fileName,
        'mime_type' => isset($file['type']) ? $file['type'] : '',
        'file_size' => (int)$file['size']
    );
}
?>
