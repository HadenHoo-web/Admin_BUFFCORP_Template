<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

define('DB_BOOTSTRAP', __DIR__ . '/../common.php'); // init $db

define('LOG_DIR', __DIR__ . '/../zalo/logs');       // giữ log chung (không gửi Zalo nữa nhưng vẫn log)
define('BATCH_LIMIT', 50);
define('PENALTY_POINTS', 2);
define('PENALTY_REASON', 'Trễ deadline');
// =============================================================

function cron_log($msg)
{
    if (!is_dir(LOG_DIR)) {
        @mkdir(LOG_DIR, 0775, true);
    }
    $line = date('Y-m-d H:i:s') . ' | ' . $msg . PHP_EOL;

    // "console log" (cron redirect sẽ bắt được)
    echo $line;

    // file log
    @file_put_contents(LOG_DIR . '/cron_overdue.log', $line, FILE_APPEND);
}

cron_log('CRON_START');

// 1) Load DB ($db)
if (!file_exists(DB_BOOTSTRAP)) {
    cron_log('ERR_DB_BOOTSTRAP_NOT_FOUND: ' . DB_BOOTSTRAP);
    exit(1);
}
require_once DB_BOOTSTRAP;

if (!isset($db)) {
    cron_log('ERR_DB_OBJECT_$db_NOT_FOUND (check common.php include db.php)');
    exit(1);
}

/**
 * Convert deadline string -> MySQL datetime expression
 * Supports:
 *  - dd-mm-YYYY HH:ii:ss
 *  - dd-mm-YYYY HH:ii
 *  - dd-mm-YYYY (fallback)
 */
$deadlineExpr = "
    COALESCE(
        STR_TO_DATE(g.ngay, '%d-%m-%Y %H:%i:%s'),
        STR_TO_DATE(g.ngay, '%d-%m-%Y %H:%i'),
        STR_TO_DATE(g.ngay, '%d-%m-%Y')
    )
";

// 2) Query overdue by deadline
$sql = "
    SELECT
        g.giaoviec_id,
        g.giaoviec_name,
        g.website_id,
        g.member_id,
        g.created_by_id,
        g.ngay AS deadline_raw,
        {$deadlineExpr} AS deadline_dt
    FROM tbl_giaoviec g
    WHERE g.soluong != 2
      AND g.penalty_sent = 0
      AND g.ngay IS NOT NULL
      AND g.ngay <> ''
      AND {$deadlineExpr} IS NOT NULL
      AND NOW() > {$deadlineExpr}
    ORDER BY {$deadlineExpr} ASC
    LIMIT " . (int)BATCH_LIMIT . "
";

$rs = $db->sql_query($sql);
if (!$rs) {
    cron_log('ERR_QUERY_OVERDUE_FAILED');
    cron_log('SQL=' . $sql);
    exit(1);
}

$found = 0;
$penalized = 0;

while ($g = $db->sql_fetchrow($rs)) {
    $found++;

    $giaoviec_id   = (int)$g['giaoviec_id'];
    $assignee_id   = (int)$g['member_id'];
    $taskName      = (string)$g['giaoviec_name'];
    $deadline_raw  = (string)$g['deadline_raw'];
    $deadline_dt   = (string)$g['deadline_dt'];

    cron_log("FOUND_OVERDUE giaoviec_id={$giaoviec_id} assignee={$assignee_id} deadline_raw={$deadline_raw} deadline_dt={$deadline_dt}");

    // 3) LOCK chống chạy lặp: ai update được penalty_sent từ 0->1 mới xử lý tiếp
    //    (đồng thời ghi nhận penalty_points/at/reason vào DB)
    $reasonEsc = addslashes(PENALTY_REASON);

    $db->sql_query("
        UPDATE tbl_giaoviec
        SET penalty_sent = 1,
            penalty_points = " . (int)PENALTY_POINTS . ",
            penalty_reason = '{$reasonEsc}',
            penalty_at = NOW()
        WHERE giaoviec_id = {$giaoviec_id}
          AND penalty_sent = 0
    ");

    if ((int)$db->sql_affectedrows() === 0) {
        cron_log("SKIP_LOCKED_ALREADY giaoviec_id={$giaoviec_id}");
        continue;
    }

    // 4) Trừ điểm assignee
    if ($assignee_id > 0) {
        $db->sql_query("
            UPDATE tbl_member
            SET points = points - " . (int)PENALTY_POINTS . "
            WHERE member_id = {$assignee_id}
        ");
    }

    // 5) Log DB-only
    cron_log("PENALTY_ONLY_DB giaoviec_id={$giaoviec_id} assignee={$assignee_id} points=-" . (int)PENALTY_POINTS . " reason=" . PENALTY_REASON);

    $penalized++;
}

cron_log("CRON_END found={$found} penalized={$penalized}");
exit(0);