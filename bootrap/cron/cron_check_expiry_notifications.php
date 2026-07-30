<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

if (!defined('_CMS_')) define('_CMS_', true);

if (!defined('EXPIRY_CRON_LOG_DIR')) {
    define('EXPIRY_CRON_LOG_DIR', __DIR__ . '/../zalo/logs');
}
if (!defined('EXPIRY_WARNING_DAYS')) {
    define('EXPIRY_WARNING_DAYS', 10);
}

function expiry_cron_log($msg)
{
    if (!is_dir(EXPIRY_CRON_LOG_DIR)) {
        @mkdir(EXPIRY_CRON_LOG_DIR, 0775, true);
    }
    $line = date('Y-m-d H:i:s') . ' | ' . $msg . PHP_EOL;
    echo $line;
    @file_put_contents(EXPIRY_CRON_LOG_DIR . '/cron_expiry_notifications.log', $line, FILE_APPEND);
}

function expiry_bootstrap()
{
    global $db;

    if (!isset($db)) {
        require_once __DIR__ . '/../common.php';
    }
    require_once __DIR__ . '/../includes/notifications.php';
}

function expiry_parse_date($rawDate)
{
    $rawDate = trim((string)$rawDate);
    if ($rawDate == '') return false;

    $rawDate = str_replace('/', '-', $rawDate);
    $formats = array(
        'Y-m-d H:i:s',
        'Y-m-d H:i',
        'Y-m-d',
        'd-m-Y H:i:s',
        'd-m-Y H:i',
        'd-m-Y'
    );

    foreach ($formats as $format) {
        $date = DateTime::createFromFormat('!' . $format, $rawDate);
        $errors = DateTime::getLastErrors();
        if ($date && (!$errors || ((int)$errors['warning_count'] == 0 && (int)$errors['error_count'] == 0))) {
            return $date;
        }
    }

    try {
        return new DateTime($rawDate);
    } catch (Exception $e) {
        return false;
    }
}

function expiry_days_left($rawDate)
{
    $targetDate = expiry_parse_date($rawDate);
    if (!$targetDate) return null;

    $today = new DateTime('today');
    $targetDate->setTime(0, 0, 0);
    return (int)$today->diff($targetDate)->format('%r%a');
}

function expiry_notification_exists_today($memberId, $type, $link)
{
    global $db;

    $memberId = (int)$memberId;
    $type = notificationSql($type);
    $link = notificationSql($link);

    $sql = "SELECT notification_id
        FROM tbl_notifications
        WHERE member_id = $memberId
          AND type = '$type'
          AND link = '$link'
          AND DATE(created_date) = CURDATE()
        LIMIT 1";
    if ($result = $db->sql_query($sql)) {
        if ($db->sql_fetchrow($result)) return true;
    }
    return false;
}

function expiry_create_once_today($memberId, $type, $title, $message, $link)
{
    notificationEnsureTable();
    if (expiry_notification_exists_today($memberId, $type, $link)) return false;
    return notificationCreate($memberId, $type, $title, $message, $link, 0);
}

function expiry_process_rows($label, $table, $idField, $nameField, $dateField, $option)
{
    global $db;

    $sent = 0;
    $checked = 0;

    $sql = "SELECT item.$idField AS item_id, item.$nameField AS item_name, item.$dateField AS expiry_date, item.member_id
        FROM $table item
        INNER JOIN tbl_member m ON item.member_id = m.member_id
        WHERE item.active = 1
          AND m.active = 1
          AND item.member_id > 0
          AND item.$dateField IS NOT NULL
          AND item.$dateField <> ''";

    $result = $db->sql_query($sql);
    if (!$result) {
        expiry_cron_log('ERR_QUERY_' . strtoupper($label) . '_FAILED');
        return array('checked' => 0, 'sent' => 0);
    }

    while ($row = $db->sql_fetchrow($result)) {
        $checked++;
        $daysLeft = expiry_days_left($row['expiry_date']);
        if ($daysLeft === null) {
            expiry_cron_log("SKIP_BAD_DATE {$label}_id={$row['item_id']} date={$row['expiry_date']}");
            continue;
        }

        if ($daysLeft > EXPIRY_WARNING_DAYS) continue;

        $itemId = (int)$row['item_id'];
        $memberId = (int)$row['member_id'];
        $itemName = (string)$row['item_name'];
        $expiryDate = (string)$row['expiry_date'];
        $link = 'main.php?option=common_lists/' . $option . '&mode=info&id=' . $itemId . '&l=2';

        if ($daysLeft < 0) {
            $type = $option . '_expiry_overdue';
            $title = $label . ' đã quá hạn';
            $message = $label . ' "' . $itemName . '" đã hết hạn ngày ' . $expiryDate . ', quá hạn ' . abs($daysLeft) . ' ngày. Vui lòng gia hạn.';
        } else {
            $type = $option . '_expiry_warning';
            $title = $label . ' sắp hết hạn';
            $message = $label . ' "' . $itemName . '" sẽ hết hạn ngày ' . $expiryDate . ', còn ' . $daysLeft . ' ngày. Vui lòng nhắc khách gia hạn.';
        }

        if (expiry_create_once_today($memberId, $type, $title, $message, $link)) {
            $sent++;
            expiry_cron_log("SENT {$label}_id={$itemId} member_id={$memberId} days={$daysLeft}");
        }
    }

    return array('checked' => $checked, 'sent' => $sent);
}

function expiry_run_notifications()
{
    expiry_bootstrap();
    expiry_cron_log('EXPIRY_CRON_START');

    $server = expiry_process_rows('Server', 'tbl_server', 'server_id', 'server_name', 'exp_date', 'server');
    $host = expiry_process_rows('Hosting', 'tbl_hosts', 'host_id', 'host_name', 'end_date', 'host');

    $checked = (int)$server['checked'] + (int)$host['checked'];
    $sent = (int)$server['sent'] + (int)$host['sent'];
    expiry_cron_log("EXPIRY_CRON_END checked={$checked} sent={$sent}");

    return array('checked' => $checked, 'sent' => $sent);
}

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    expiry_run_notifications();
    exit(0);
}
?>
