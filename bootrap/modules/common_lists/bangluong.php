<?php
global $languageid, $template, $root_path, $db;

$action = mosGetParam($_REQUEST, 'mode', 'list');

if (!isset($template)) {
    $template = new Template();
}

$template->assign_vars(array(
    'ROOT'       => $root_path,
    'funname'    => 'common_lists/bangluong',
    'LANGUAGEID' => $languageid,
));

switch ($action) {
    case 'realtime_api':
        break;

    case 'detail':
        mosDetailBangLuong();
        break;

    case 'export':
        exportExcelBangLuong();
        break;

    case 'export_detail':
        exportExcelBangLuongDetail();
        break;

    case 'save_note':
        mosSaveBangLuongNote();
        break;

    case 'list':
    default:
        mosListBangLuong();
        break;
}

/* =========================================================
 * HELPER
 * ========================================================= */

function formatMoney($number) {
    return number_format((float)$number, 0, ',', '.');
}

function formatNumber2($number) {
    return number_format((float)$number, 2, '.', '');
}

function formatLeaveDays($number) {
    $number = (float)$number;

    if (abs($number - round($number)) < 0.00001) {
        return (string)intval(round($number));
    }

    if (abs($number * 2 - round($number * 2)) < 0.00001) {
        return number_format($number, 1, '.', '');
    }

    return number_format($number, 2, '.', '');
}

function secondsToTime($seconds) {
    $seconds = max(0, (int)$seconds);
    $hours   = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $secs    = $seconds % 60;
    return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
}

function secondsToDurationText($seconds) {
    $seconds = max(0, (int)$seconds);
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $remainSeconds = $seconds % 60;
    $parts = array();

    if ($hours > 0) {
        $parts[] = $hours . ' giờ';
    }

    if ($minutes > 0) {
        $parts[] = $minutes . ' phút';
    }

    if ($remainSeconds > 0) {
        $parts[] = $remainSeconds . ' giây';
    }

    if (empty($parts)) {
        return '0 giây';
    }

    return implode(' ', $parts);
}

function hhmmssToSeconds($timeStr) {
    $timeStr = trim((string)$timeStr);

    if ($timeStr === '' || $timeStr === '-') {
        return 0;
    }

    // Nếu DB trả về dạng datetime: 2026-03-20 08:30:24
    if (strpos($timeStr, ' ') !== false) {
        $tmp = explode(' ', $timeStr);
        $timeStr = trim(end($tmp));
    }

    if (preg_match('/^(\d{1,2}):(\d{2})(?::(\d{2}))?$/', $timeStr, $m)) {
        $h = intval($m[1]);
        $i = intval($m[2]);
        $s = isset($m[3]) ? intval($m[3]) : 0;
        return ($h * 3600) + ($i * 60) + $s;
    }

    return 0;
}

function h($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

function bangluongNoteClass($text) {
    $lower = function_exists('mb_strtolower') ? mb_strtolower((string)$text, 'UTF-8') : strtolower((string)$text);
    $class = 'note-item';

    if (strpos($lower, 'trễ') !== false || strpos($lower, 'tre') !== false || strpos($lower, 'về sớm') !== false || strpos($lower, 've som') !== false) {
        $class .= ' note-late';
    }
    if (strpos($lower, 'phép') !== false || strpos($lower, 'phep') !== false || strpos($lower, 'nghỉ') !== false || strpos($lower, 'nghi') !== false) {
        $class .= ' note-leave';
    }
    if (strpos($lower, 'hết phép') !== false || strpos($lower, 'het phep') !== false || strpos($lower, 'tổng cộng') !== false || strpos($lower, 'tong cong') !== false) {
        $class .= ' note-danger';
    }

    return $class;
}

function bangluongSplitNoteItems($text) {
    $text = trim((string)$text);
    if ($text === '') return array();

    $text = str_replace(array("\r\n", "\r"), "\n", $text);
    $text = preg_replace('/<br\s*\/?>/i', "\n", $text);
    $parts = preg_split('/\s*\|\|\s*|\n+/u', $text);
    $items = array();

    foreach ($parts as $part) {
        $part = trim($part);
        if ($part !== '') $items[] = $part;
    }

    return $items;
}

function bangluongFormatNoteHtml($text, $emptyText = 'Không có ghi chú') {
    $items = bangluongSplitNoteItems($text);
    if (count($items) === 0) {
        return '<div class="note-empty">'.h($emptyText).'</div>';
    }

    $html = '';
    foreach ($items as $item) {
        $date = '';
        $content = $item;
        if (preg_match('/^(\d{2}[-\/]\d{2})\s*:\s*(.*)$/u', $item, $match)) {
            $date = $match[1];
            $content = trim($match[2]);
        }

        $html .= '<div class="'.bangluongNoteClass($item).'">';
        if ($date !== '') {
            $html .= '<span class="note-date">'.h($date).'</span>';
        }
        $html .= '<span class="note-content">'.h($content).'</span>';
        $html .= '</div>';
    }

    return $html;
}

function bangluongFormatDeductHtml($text) {
    return bangluongFormatNoteHtml($text, 'Không có giờ trừ');
}

function appendText($base, $extra, $separator = ' | ') {
    $base  = trim((string)$base);
    $extra = trim((string)$extra);

    if ($extra == '') return $base;
    if ($base == '') return $extra;

    return $base . $separator . $extra;
}

function getSalesDepartmentId() {
    global $db;

    $sql = "SELECT customer_type_id FROM tbl_customer_type WHERE LOWER(customer_type_name) = 'kinh doanh' LIMIT 1";
    if ($result = $db->sql_query($sql)) {
        if ($row = $db->sql_fetchrow($result)) {
            return intval($row['customer_type_id']);
        }
    }

    return 0;
}

function getSalesPayrollCommissionMap($month, $year) {
    global $db, $languageid;

    $targetMin = 75000000;
    $targetHigh = 100000000;
    $managerId = 34;
    $departmentId = getSalesDepartmentId();
    $commissionMap = array();

    if ($departmentId <= 0) {
        return $commissionMap;
    }

    $salesMembers = array();
    $sql = "
        SELECT member_id
        FROM tbl_member
        WHERE active = 1
          AND (member_type_id = " . $departmentId . " OR extra_member_type_id = " . $departmentId . ")
    ";
    if (!($result = $db->sql_query($sql))) {
        return $commissionMap;
    }
    while ($row = $db->sql_fetchrow($result)) {
        $salesMembers[] = intval($row['member_id']);
    }

    if (empty($salesMembers)) {
        return $commissionMap;
    }

    foreach ($salesMembers as $memberId) {
        $commissionMap[$memberId] = array(
            'sales_revenue' => 0,
            'sales_commission' => 0,
            'manager_commission' => 0,
        );
    }

    $teamSql = implode(',', $salesMembers);
    $moneyExpr = "CAST(REPLACE(REPLACE(IFNULL(c.congno_name,0),'.',''),',','') AS UNSIGNED)";
    $sql = "
        SELECT c.member_id,
               SUM(CASE WHEN c.thuchi = 0 THEN " . $moneyExpr . " ELSE 0 END) AS revenue
        FROM tbl_congno c
        WHERE c.active = 1
          AND c.language_id = " . intval($languageid) . "
          AND c.member_id IN (" . $teamSql . ")
          AND SUBSTRING(c.ngay, 4, 2) = '" . sprintf('%02d', intval($month)) . "'
          AND SUBSTRING(c.ngay, 7, 4) = '" . intval($year) . "'
        GROUP BY c.member_id
    ";
    if (!($result = $db->sql_query($sql))) {
        return $commissionMap;
    }

    $managerCommission = 0;
    while ($row = $db->sql_fetchrow($result)) {
        $memberId = intval($row['member_id']);
        $revenue = intval($row['revenue']);
        $personalCommission = 0;

        if ($revenue >= $targetHigh) {
            $personalCommission = ($targetHigh * 0.01) + (($revenue - $targetHigh) * 0.02);
        } elseif ($revenue >= $targetMin) {
            $personalCommission = $revenue * 0.01;
        }

        if (!isset($commissionMap[$memberId])) {
            $commissionMap[$memberId] = array(
                'sales_revenue' => 0,
                'sales_commission' => 0,
                'manager_commission' => 0,
            );
        }

        $commissionMap[$memberId]['sales_revenue'] = $revenue;
        $commissionMap[$memberId]['sales_commission'] = round($personalCommission);

        if ($memberId != $managerId && $revenue >= $targetMin) {
            $managerCommission += round($revenue * 0.01);
        }
    }

    if (!isset($commissionMap[$managerId])) {
        $commissionMap[$managerId] = array(
            'sales_revenue' => 0,
            'sales_commission' => 0,
            'manager_commission' => 0,
        );
    }
    $commissionMap[$managerId]['manager_commission'] = round($managerCommission);

    return $commissionMap;
}

function getTechnicalPayrollCommissionMap($month, $year) {
    global $db, $languageid;

    $technicalMemberIds = array(50, 63); // Ms.Hằng, Ms.Tú
    $commissionMap = array();

    foreach ($technicalMemberIds as $memberId) {
        $commissionMap[$memberId] = array(
            'technical_revenue' => 0,
            'technical_commission' => 0,
        );
    }

    $teamSql = implode(',', $technicalMemberIds);
    $moneyExpr = "CAST(REPLACE(REPLACE(IFNULL(c.congno_name,0),'.',''),',','') AS UNSIGNED)";
    $sql = "
        SELECT w.kt_id AS member_id,
               SUM(CASE WHEN c.thuchi = 0 THEN " . $moneyExpr . " ELSE 0 END) AS revenue
        FROM tbl_congno c
        INNER JOIN tbl_website w ON c.website_id = w.website_id
        WHERE c.active = 1
          AND c.language_id = " . intval($languageid) . "
          AND w.kt_id IN (" . $teamSql . ")
          AND SUBSTRING(c.ngay, 4, 2) = '" . sprintf('%02d', intval($month)) . "'
          AND SUBSTRING(c.ngay, 7, 4) = '" . intval($year) . "'
        GROUP BY w.kt_id
    ";

    if (!($result = $db->sql_query($sql))) {
        return $commissionMap;
    }

    while ($row = $db->sql_fetchrow($result)) {
        $memberId = intval($row['member_id']);
        $revenue = intval($row['revenue']);

        $commissionMap[$memberId] = array(
            'technical_revenue' => $revenue,
            'technical_commission' => round($revenue * 0.01),
        );
    }

    return $commissionMap;
}

function getSalaryConfig() {
    return array(
        'working_days'            => 26, // FIX CỨNG
        'hours_per_day'           => 8,
        'attendance_bonus'        => 200000,
        'max_work_seconds'        => 8 * 3600,
        'early_deduct_threshold'  => 60,   // về sớm từ 1 phút là trừ toàn bộ phút về sớm
        'late_free_occurrences'   => 4,    // 4 lần trễ đầu áp ngưỡng 30 phút
        'late_first_threshold'    => 1800, // trễ đủ 30 phút là trừ toàn bộ phút trễ
        'late_later_threshold'    => 600,  // từ lần 5, trễ đủ 10 phút là trừ toàn bộ phút trễ

        // Thứ 2 - Thứ 6
        'weekday_morning_start'   => '08:30:00',
        'weekday_morning_end'     => '12:00:00',
        'weekday_afternoon_start' => '13:00:00',
        'weekday_afternoon_end'   => '17:30:00',

        // Thứ 7
        'saturday_start'          => '08:30:00',
        'saturday_end'            => '12:00:00',
    );
}

function getMemberDisplayName($member) {
    if (!empty($member['fullname'])) {
        return trim($member['fullname']);
    }
    if (!empty($member['loginname'])) {
        return trim($member['loginname']);
    }
    if (!empty($member['attendance_user_id'])) {
        return trim($member['attendance_user_id']);
    }
    return 'Không xác định';
}

/**
 * Thứ 2 -> Thứ 6:
 *   08:30-12:00 và 13:00-17:30
 * Thứ 7:
 *   08:30-12:00 nhưng vẫn tính đủ 8 tiếng = 1 công
 */
function getWorkScheduleByDate($workDate, $config) {
    $dayOfWeek = date('w', strtotime($workDate)); // 0=CN, 6=T7

    if ($dayOfWeek == 6) {
        return array(
            'type'              => 'saturday',
            'intervals'         => array(
                array($config['saturday_start'], $config['saturday_end']),
            ),
            'paid_full_seconds' => intval($config['max_work_seconds']),
        );
    }

    return array(
        'type'              => 'normal',
        'intervals'         => array(
            array($config['weekday_morning_start'], $config['weekday_morning_end']),
            array($config['weekday_afternoon_start'], $config['weekday_afternoon_end']),
        ),
        'paid_full_seconds' => intval($config['max_work_seconds']),
    );
}

function getScheduledSecondsInRange($fromSec, $toSec, $intervals) {
    if ($toSec <= $fromSec) {
        return 0;
    }

    $total = 0;

    foreach ($intervals as $it) {
        $startSec = hhmmssToSeconds($it[0]);
        $endSec   = hhmmssToSeconds($it[1]);

        $overlapStart = max($fromSec, $startSec);
        $overlapEnd   = min($toSec, $endSec);

        if ($overlapEnd > $overlapStart) {
            $total += ($overlapEnd - $overlapStart);
        }
    }

    return $total;
}

function getFirstIntervalStart($intervals) {
    if (empty($intervals)) return 0;
    return hhmmssToSeconds($intervals[0][0]);
}

function getLastIntervalEnd($intervals) {
    if (empty($intervals)) return 0;
    $last = $intervals[count($intervals) - 1];
    return hhmmssToSeconds($last[1]);
}

function getIntervalSeconds($startTime, $endTime) {
    return max(0, hhmmssToSeconds($endTime) - hhmmssToSeconds($startTime));
}

function getMorningSessionSeconds($intervals) {
    if (!isset($intervals[0])) return 0;
    return getIntervalSeconds($intervals[0][0], $intervals[0][1]); // 3.5h ngày thường
}

function getAfternoonSessionSeconds($intervals) {
    if (!isset($intervals[1])) return 0;
    return getIntervalSeconds($intervals[1][0], $intervals[1][1]); // 4.5h ngày thường
}

function getLeaveUnit($leaveSession) {
    if ($leaveSession == 'morning' || $leaveSession == 'afternoon') return 0.5;
    if ($leaveSession == 'full') return 1;
    return 0;
}

function getAbsentSecondsForLeaveSession($leaveSession, $intervals, $maxWorkSeconds) {
    if ($leaveSession == 'morning') {
        return getMorningSessionSeconds($intervals);
    }

    if ($leaveSession == 'afternoon') {
        return getAfternoonSessionSeconds($intervals);
    }

    if ($leaveSession == 'full') {
        return $maxWorkSeconds;
    }

    return 0;
}

function getLateDeductSeconds($lateSeconds, $lateOccurrence, $config) {
    $lateSeconds = intval($lateSeconds);
    $lateOccurrence = intval($lateOccurrence);

    if ($lateSeconds <= 0 || $lateOccurrence <= 0) {
        return 0;
    }

    $freeOccurrences = isset($config['late_free_occurrences']) ? intval($config['late_free_occurrences']) : 4;
    $firstThreshold  = isset($config['late_first_threshold']) ? intval($config['late_first_threshold']) : 1800;
    $laterThreshold  = isset($config['late_later_threshold']) ? intval($config['late_later_threshold']) : 600;

    if ($lateOccurrence <= $freeOccurrences) {
        return ($lateSeconds >= $firstThreshold) ? $lateSeconds : 0;
    }

    return ($lateSeconds >= $laterThreshold) ? $lateSeconds : 0;
}

function getWorkingIntervalsAfterLeave($intervals, $leaveSession) {
    if ($leaveSession == 'morning') {
        return isset($intervals[1]) ? array($intervals[1]) : array();
    }

    if ($leaveSession == 'afternoon') {
        return isset($intervals[0]) ? array($intervals[0]) : array();
    }

    if ($leaveSession == 'full') {
        return array();
    }

    return $intervals;
}

/**
 * Danh sách ngày đi làm trong tháng:
 * nghỉ Chủ nhật, làm Thứ 2 -> Thứ 7
 */
function getWorkingDatesInMonth($month, $year) {
    $month = intval($month);
    $year  = intval($year);

    if ($month <= 0) $month = intval(date('m'));
    if ($year <= 0)  $year  = intval(date('Y'));

    $currentYear  = intval(date('Y'));
    $currentMonth = intval(date('m'));
    $currentDay   = intval(date('d'));

    // Nếu chọn tháng trong tương lai => chưa tính ngày nào
    if ($year > $currentYear || ($year == $currentYear && $month > $currentMonth)) {
        return array();
    }

    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    // Nếu là tháng hiện tại thì chỉ tính tới ngày hôm qua
    $maxDay = $daysInMonth;
    if ($year == $currentYear && $month == $currentMonth) {
        $maxDay = $currentDay - 1;
    }

    // Nếu hôm nay là ngày 1 thì không có ngày nào để tính
    if ($maxDay < 1) {
        return array();
    }

    $dates = array();

    for ($d = 1; $d <= $maxDay; $d++) {
        $date = sprintf('%04d-%02d-%02d', $year, $month, $d);
        $w    = date('w', strtotime($date)); // 0 = Chủ nhật

        // Chỉ tính ngày làm việc: Thứ 2 -> Thứ 7
        if ($w != 0) {
            $dates[] = $date;
        }
    }

    return $dates;
}

/* =========================================================
 * MEMBER + ATTENDANCE DATA
 * ========================================================= */

function getMembersForPayroll($keyword = '', $memberId = 0, $memberName = '') {
    global $db;

    $cond = " WHERE TRIM(IFNULL(attendance_user_id, '')) <> '' ";

    if (intval($memberId) > 0) {
        $cond .= " AND member_id = " . intval($memberId);
    }

    if ($memberName != '') {
        $memberNameSafe = addslashes(trim($memberName));
        $cond .= " AND (
            TRIM(LOWER(fullname)) = TRIM(LOWER('" . $memberNameSafe . "'))
            OR TRIM(LOWER(loginname)) = TRIM(LOWER('" . $memberNameSafe . "'))
            OR TRIM(LOWER(attendance_user_id)) = TRIM(LOWER('" . $memberNameSafe . "'))
        )";
    }

    if ($keyword != '') {
        $keywordSafe = addslashes(trim($keyword));
        $cond .= " AND (
            TRIM(LOWER(fullname)) LIKE TRIM(LOWER('%" . $keywordSafe . "%'))
            OR TRIM(LOWER(loginname)) LIKE TRIM(LOWER('%" . $keywordSafe . "%'))
            OR TRIM(LOWER(attendance_user_id)) LIKE TRIM(LOWER('%" . $keywordSafe . "%'))
        )";
    }

    $sql = "
        SELECT
            member_id,
            IFNULL(luong, 0) AS base_salary,
            IFNULL(trach_nhiem, 0) AS responsibility_amount,
            IFNULL(leave_days_default, 1) AS leave_days_default,
            fullname,
            loginname,
            attendance_user_id
        FROM tbl_member
        $cond
        ORDER BY
            CASE
                WHEN TRIM(IFNULL(fullname, '')) <> '' THEN TRIM(fullname)
                WHEN TRIM(IFNULL(loginname, '')) <> '' THEN TRIM(loginname)
                ELSE TRIM(attendance_user_id)
            END ASC
    ";

    $result = $db->sql_query($sql);
    $rows = array();

    while ($row = $db->sql_fetchrow($result)) {
        $row['display_name'] = getMemberDisplayName($row);
        $rows[] = $row;
    }

    return $rows;
}

function getAttendanceRowsMap($month, $year, $attendanceUserIds = array()) {
    global $db;

    if (empty($attendanceUserIds)) {
        return array();
    }

    $ids = array();
    foreach ($attendanceUserIds as $id) {
        $id = trim($id);
        if ($id != '') {
            $ids[] = "'" . addslashes($id) . "'";
        }
    }

    if (empty($ids)) {
        return array();
    }

    $sql = "
        SELECT *
        FROM tbl_chamcong
        WHERE MONTH(work_date) = '" . intval($month) . "'
          AND YEAR(work_date) = '" . intval($year) . "'
          AND TRIM(user_id) IN (" . implode(',', $ids) . ")
        ORDER BY work_date ASC
    ";

    $result = $db->sql_query($sql);
    $map = array();

    while ($row = $db->sql_fetchrow($result)) {
        $uid  = trim($row['user_id']);
        $date = $row['work_date'];

        if (!isset($map[$uid])) {
            $map[$uid] = array();
        }

        $map[$uid][$date] = $row;
    }

    return $map;
}

function buildMonthlyRowsForMember($member, $attendanceMap, $month, $year) {
    $dates = getWorkingDatesInMonth($month, $year);
    $rows  = array();

    $uid = trim($member['attendance_user_id']);

    foreach ($dates as $date) {
        if (isset($attendanceMap[$uid]) && isset($attendanceMap[$uid][$date])) {
            $row = $attendanceMap[$uid][$date];
            $row['base_salary']        = $member['base_salary'];
            $row['responsibility_amount'] = $member['responsibility_amount'];
            $row['leave_days_default'] = $member['leave_days_default'];
            $row['member_id']          = $member['member_id'];
            $row['fullname']           = $member['fullname'];
            $row['loginname']          = $member['loginname'];
            $row['attendance_user_id'] = $member['attendance_user_id'];
            $row['display_name']       = $member['display_name'];
            $row['_auto_missing']      = 0;
        } else {
            $row = array(
                'work_date'            => $date,
                'check_in'             => '',
                'check_out'            => '',
                'status'               => '',
                'note'                 => '',
                'user_id'              => $uid,
                'name'                 => $member['display_name'],
                'base_salary'          => $member['base_salary'],
                'responsibility_amount'=> $member['responsibility_amount'],
                'leave_days_default'   => $member['leave_days_default'],
                'member_id'            => $member['member_id'],
                'fullname'             => $member['fullname'],
                'loginname'            => $member['loginname'],
                'attendance_user_id'   => $member['attendance_user_id'],
                'display_name'         => $member['display_name'],
                '_auto_missing'        => 1,
            );
        }

        $rows[] = $row;
    }

    return $rows;
}

/* =========================================================
 * AUTO LEAVE DETECTION
 * ========================================================= */

/**
 * Rule tự suy ra nghỉ:
 * - Không có chấm công / thiếu cặp vào-ra => full
 * - checkin > 12:00:00 => nghỉ buổi sáng
 * - checkout < 13:00:00 => nghỉ buổi chiều
 * - thứ 7 chỉ có 1 buổi => không tự nhận 0.5, chỉ full hoặc none
 */
function hasAnyKeyword($text, $keywords) {
    $text = mb_strtolower(trim((string)$text), 'UTF-8');

    foreach ($keywords as $kw) {
        if ($kw !== '' && mb_strpos($text, $kw, 0, 'UTF-8') !== false) {
            return true;
        }
    }

    return false;
}

function getAttendanceNoteText($row) {
    $status = isset($row['status']) ? mb_strtolower(trim($row['status']), 'UTF-8') : '';
    $note   = isset($row['note']) ? mb_strtolower(trim($row['note']), 'UTF-8') : '';

    return trim($status . ' ' . $note);
}

function hasCompanyWorkKeyword($text) {
    return hasAnyKeyword($text, array(
        'đi gặp khách hàng',
        'gap khach hang',
        'gặp khách hàng',
        'đi gửi hợp đồng',
        'di gui hop dong',
        'gửi hợp đồng',
        'gui hop dong',
        'đi công tác',
        'di cong tac',
        'công tác',
        'cong tac',
        'đi làm việc công ty',
        'di lam viec cong ty',
        'làm việc bên ngoài',
        'lam viec ben ngoai',
        'ra ngoài làm việc cho công ty',
        'hỗ trợ công ty',
        'đi mua giấy in',
        'di mua giay in',
        'đi làm việc công ty',
      	'đi làm sim cho công ty',
    ));
}

function hasLateContext($text) {
    return hasAnyKeyword($text, array(
        'trễ',
        'tre',
        'đi trễ',
        'di tre',
        'checkin',
        'check in',
        'vào trễ',
        'vao tre',
        'đầu giờ',
        'dau gio'
    ));
}

function hasEarlyContext($text) {
    return hasAnyKeyword($text, array(
        'về sớm',
        've som',
        'checkout',
        'check out',
        'ra sớm',
        'ra som',
        'cuối giờ',
        'cuoi gio'
    ));
}

function getNoteSegments($text) {
    $text = mb_strtolower(trim((string)$text), 'UTF-8');
    $segments = preg_split('/[|;\\r\\n]+/u', $text);
    $cleanSegments = array();

    foreach ($segments as $segment) {
        $segment = trim($segment);
        if ($segment != '') {
            $cleanSegments[] = $segment;
        }
    }

    return $cleanSegments;
}

function hasCompanyWorkInEventSegment($text, $event) {
    foreach (getNoteSegments($text) as $segment) {
        if (!hasCompanyWorkKeyword($segment)) {
            continue;
        }

        if ($event == 'late' && hasLateContext($segment)) {
            return true;
        }

        if ($event == 'early' && hasEarlyContext($segment)) {
            return true;
        }
    }

    return false;
}

function isLateCompanyWorkExempt($row) {
    $text = getAttendanceNoteText($row);

    if (!hasCompanyWorkKeyword($text)) {
        return false;
    }

    if (hasCompanyWorkInEventSegment($text, 'late')) {
        return true;
    }

    // Note công việc bên ngoài không ghi rõ "về sớm" thì mặc định chỉ áp cho đi trễ/check-in.
    return !hasEarlyContext($text);
}

function isEarlyCompanyWorkExempt($row) {
    $text = getAttendanceNoteText($row);

    return hasCompanyWorkInEventSegment($text, 'early');
}

function isCompanyWorkExempt($row) {
    return isLateCompanyWorkExempt($row) || isEarlyCompanyWorkExempt($row);
}

function shouldAppendNoteToEvent($row, $event) {
    $text = getAttendanceNoteText($row);

    if ($text == '') {
        return false;
    }

    if ($event == 'late') {
        return !hasEarlyContext($text) || hasLateContext($text);
    }

    if ($event == 'early') {
        return hasEarlyContext($text);
    }

    return false;
}

function extractEventNote($row, $event) {
    $rawNote = isset($row['note']) ? trim($row['note']) : '';
    $text = getAttendanceNoteText($row);

    if ($rawNote == '') {
        return '';
    }

    $matchedNotes = array();
    foreach (getNoteSegments($rawNote) as $segment) {
        $matchesEvent = false;
        if ($event == 'late') {
            $matchesEvent = hasLateContext($segment);
        } elseif ($event == 'early') {
            $matchesEvent = hasEarlyContext($segment);
        }

        if (!$matchesEvent) {
            continue;
        }

        $notePart = $segment;
        $dashPos = mb_strpos($segment, '-', 0, 'UTF-8');
        if ($dashPos !== false) {
            $notePart = trim(mb_substr($segment, $dashPos + 1, null, 'UTF-8'));
        }

        if ($notePart != '') {
            $matchedNotes[] = $notePart;
        }
    }

    if (!empty($matchedNotes)) {
        return implode(' | ', $matchedNotes);
    }

    if ($event == 'late' && shouldAppendNoteToEvent($row, 'late')) {
        return $rawNote;
    }

    if ($event == 'early' && shouldAppendNoteToEvent($row, 'early')) {
        return $rawNote;
    }

    return '';
}

function deriveAutoLeaveSessionFromAttendance($row, $config) {
    $workDate  = isset($row['work_date']) ? $row['work_date'] : date('Y-m-d');
    $dayOfWeek = date('w', strtotime($workDate)); // 0=CN, 6=T7

    $checkInRaw  = isset($row['check_in']) ? trim($row['check_in']) : '';
    $checkOutRaw = isset($row['check_out']) ? trim($row['check_out']) : '';
    $hasCheckIn  = ($checkInRaw != '' && $checkInRaw != '-');
    $hasCheckOut = ($checkOutRaw != '' && $checkOutRaw != '-');

    if (!empty($row['_auto_missing']) && intval($row['_auto_missing']) == 1) {
        return 'full';
    }

    if (!$hasCheckIn && !$hasCheckOut) {
        return 'full';
    }

    if (!$hasCheckIn || !$hasCheckOut) {
        return 'full';
    }

    // Thứ 7 chỉ có 1 buổi, không auto nhận 0.5
    if ($dayOfWeek == 6) {
        return 'none';
    }

    $checkInSec   = hhmmssToSeconds($checkInRaw);
    $checkOutSec  = hhmmssToSeconds($checkOutRaw);
    $middaySec    = hhmmssToSeconds('12:00:00');
    $afternoonSec = hhmmssToSeconds('13:00:00');

    // nếu dữ liệu bất thường, coi như nghỉ full
    if ($checkInSec > $middaySec && $checkOutSec < $afternoonSec) {
        return 'full';
    }

    if ($checkInSec > $middaySec) {
        return isLateCompanyWorkExempt($row) ? 'none' : 'morning';
    }

    if ($checkOutSec < $afternoonSec) {
        return isEarlyCompanyWorkExempt($row) ? 'none' : 'afternoon';
    }

    return 'none';
}

/* =========================================================
 * ANALYZE ONE DAY
 * ========================================================= */

function analyzeAttendanceRow($row, $config, &$leaveRemain, &$lateOccurrenceCount) {
    $workDate        = isset($row['work_date']) ? $row['work_date'] : date('Y-m-d');
    $schedule        = getWorkScheduleByDate($workDate, $config);
    $intervals       = $schedule['intervals'];
    $maxWorkSeconds  = intval($schedule['paid_full_seconds']);
    $earlyDeductThreshold = isset($config['early_deduct_threshold']) ? intval($config['early_deduct_threshold']) : 60;

    $checkInRaw  = isset($row['check_in']) ? trim($row['check_in']) : '';
    $checkOutRaw = isset($row['check_out']) ? trim($row['check_out']) : '';

    $checkInSec  = hhmmssToSeconds($checkInRaw);
    $checkOutSec = hhmmssToSeconds($checkOutRaw);

    $rawStatus = isset($row['status']) ? trim($row['status']) : '';
    $rawNote   = isset($row['note']) ? trim($row['note']) : '';
    $isLateCompanyExempt = isLateCompanyWorkExempt($row);
    $isEarlyCompanyExempt = isEarlyCompanyWorkExempt($row);

    $hasCheckIn  = ($checkInRaw != '' && $checkInRaw != '-');
    $hasCheckOut = ($checkOutRaw != '' && $checkOutRaw != '-');

    $leaveSession = deriveAutoLeaveSessionFromAttendance($row, $config);
    $leaveUnit    = getLeaveUnit($leaveSession);

    $result = array(
        'work_date'      => $workDate,
        'check_in'       => ($checkInRaw != '' ? $checkInRaw : '-'),
        'check_out'      => ($checkOutRaw != '' ? $checkOutRaw : '-'),
        'status'         => $rawStatus,
        'note'           => $rawNote,
        'status_display' => $rawStatus,
        'note_display'   => $rawNote,
        'is_leave'       => ($leaveSession != 'none'),
        'leave_session'  => $leaveSession,
        'used_leave'     => 0.0,
        'late_count'     => 0,
        'late_seconds'   => 0,
        'late_deduct_seconds' => 0,
        'early_deduct_seconds' => 0,
        'early_seconds'  => 0,
        'absent_seconds' => 0,
        'paid_seconds'   => 0,
        'count_text'     => '',
        'has_attendance' => ($hasCheckIn && $hasCheckOut) ? 1 : 0,
    );

    $countParts = array();

    // Ghi note tự động
    if ($leaveSession == 'morning') {
        $result['note_display']   = appendText($result['note_display'], 'Tự ghi nhận nghỉ buổi sáng do giờ checkin lớn hơn 12:00');
        $result['status_display'] = appendText($result['status_display'], 'Nghỉ buổi sáng');
    } elseif ($leaveSession == 'afternoon') {
        $result['note_display']   = appendText($result['note_display'], 'Tự ghi nhận nghỉ buổi chiều do giờ checkout sớm hơn 13:00');
        $result['status_display'] = appendText($result['status_display'], 'Nghỉ buổi chiều');
    } elseif ($leaveSession == 'full') {
        if (!$hasCheckIn && !$hasCheckOut) {
            $result['note_display']   = appendText($result['note_display'], 'Tự ghi nhận nghỉ cả ngày do không có chấm công');
            $result['status_display'] = appendText($result['status_display'], 'Nghỉ cả ngày');
        } else {
            $result['note_display']   = appendText($result['note_display'], 'Tự ghi nhận nghỉ cả ngày do chấm công không đầy đủ');
            $result['status_display'] = appendText($result['status_display'], 'Nghỉ cả ngày');
        }
    }

    // FULL DAY LEAVE
    // FULL DAY LEAVE
    if ($leaveSession == 'full') {

        // Còn đủ 1 ngày phép
        if ($leaveRemain >= 1) {
            $result['used_leave']     = 1;
            $result['absent_seconds'] = 0;
            $result['paid_seconds']   = $maxWorkSeconds;
            $leaveRemain              = $leaveRemain - 1;

            $countParts[] = 'Tự trừ 1 phép cho ngày nghỉ';
        }

        // Còn phép nhưng không đủ 1 ngày, ví dụ còn 0.5 phép
        elseif ($leaveRemain > 0) {
            $usedLeave = floatval($leaveRemain);

            // Phần phép còn lại được tính lương
            $paidByLeaveSeconds = intval(round($maxWorkSeconds * $usedLeave));

            // Phần còn lại bị tính nghỉ không phép
            $absentSeconds = $maxWorkSeconds - $paidByLeaveSeconds;

            if ($absentSeconds < 0) {
                $absentSeconds = 0;
            }

            $result['used_leave']     = $usedLeave;
            $result['absent_seconds'] = $absentSeconds;
            $result['paid_seconds']   = $paidByLeaveSeconds;

            $leaveRemain = 0;

            $countParts[] = 'Tự trừ ' . formatLeaveDays($usedLeave) . ' ngày phép';
            $countParts[] = 'Nghỉ nửa ngày - hết phép';
        }

        // Hết phép hoàn toàn
        else {
            $result['used_leave']     = 0;
            $result['absent_seconds'] = $maxWorkSeconds;
            $result['paid_seconds']   = 0;

            $countParts[] = 'Nghỉ cả ngày - hết phép';
        }

        $result['count_text'] = implode(' | ', $countParts);
        return $result;
    }

    // HALF DAY LEAVE
    if ($leaveSession == 'morning' || $leaveSession == 'afternoon') {
        if ($leaveRemain >= 0.5) {
            $result['used_leave'] = 0.5;
            $leaveRemain          = $leaveRemain - 0.5;

            if ($leaveSession == 'morning') {
                $countParts[] = 'Tự trừ 0.5 phép - nghỉ buổi sáng';
            } else {
                $countParts[] = 'Tự trừ 0.5 phép - nghỉ buổi chiều';
            }
        } else {
            $result['used_leave']      = 0;
            $result['absent_seconds'] += getAbsentSecondsForLeaveSession($leaveSession, $intervals, $maxWorkSeconds);

            if ($leaveSession == 'morning') {
                $countParts[] = 'Nghỉ buổi sáng - hết phép';
            } else {
                $countParts[] = 'Nghỉ buổi chiều - hết phép';
            }
        }

        $activeIntervals = getWorkingIntervalsAfterLeave($intervals, $leaveSession);
    } else {
        $activeIntervals = $intervals;
    }

    if (empty($activeIntervals)) {
        $result['paid_seconds'] = max(0, $maxWorkSeconds - $result['absent_seconds']);
        $result['count_text']   = implode(' | ', $countParts);
        return $result;
    }

    $activeStartSec = getFirstIntervalStart($activeIntervals);
    $activeEndSec   = getLastIntervalEnd($activeIntervals);

    // Đi trễ: chỉ từ 1 phút trở lên mới note + tăng số lần đi trễ
    if ($hasCheckIn && $checkInSec > $activeStartSec) {
        $lateSec = getScheduledSecondsInRange($activeStartSec, $checkInSec, $activeIntervals);

        if ($lateSec >= 60) {
            $result['late_seconds'] = $lateSec;

            if (!$isLateCompanyExempt) {
                $result['late_count'] = 1;
                $lateOccurrenceCount++;
            }

            $lateText = 'Trễ ' . secondsToTime($lateSec);
            $lateNote = extractEventNote($row, 'late');

            if ($lateNote != '') {
                $lateText .= ' - ' . $lateNote;
            }

            $countParts[] = $lateText;

            if (!$isLateCompanyExempt) {
                $lateDeductSec = getLateDeductSeconds($lateSec, $lateOccurrenceCount, $config);

                if ($lateDeductSec > 0) {
                    $result['late_deduct_seconds'] = $lateDeductSec;
                    $result['absent_seconds'] += $lateDeductSec;
                    $countParts[] = 'Trừ đi trễ lần ' . $lateOccurrenceCount . ': ' . secondsToTime($lateDeductSec);
                }
            }
        }
    }

    if ($hasCheckOut && $checkOutSec < $activeEndSec) {
        $earlySec = getScheduledSecondsInRange($checkOutSec, $activeEndSec, $activeIntervals);

        if ($earlySec >= 60) {
            $result['early_seconds'] = $earlySec;

            $earlyText = 'Về sớm ' . secondsToTime($earlySec);
            $earlyNote = extractEventNote($row, 'early');

            if ($earlyNote != '') {
                $earlyText .= ' - ' . $earlyNote;
            }

            $countParts[] = $earlyText;

            if ($earlySec >= $earlyDeductThreshold) {
                if (!$isEarlyCompanyExempt) {
                    $result['early_deduct_seconds'] = $earlySec;
                    $result['absent_seconds'] += $earlySec;
                }
            }
        }
    }
    if ($result['absent_seconds'] > $maxWorkSeconds) {
        $result['absent_seconds'] = $maxWorkSeconds;
    }

    $result['paid_seconds'] = $maxWorkSeconds - $result['absent_seconds'];
    if ($result['paid_seconds'] < 0) {
        $result['paid_seconds'] = 0;
    }

    $result['count_text'] = implode(' | ', $countParts);
    return $result;
}

/* =========================================================
 * PAYROLL AGGREGATE DATA
 * ========================================================= */

function getBangLuongData($month, $year, $keyword = '') {
    $config            = getSalaryConfig();
    $members           = getMembersForPayroll($keyword, 0, '');
    $salesCommissionMap = getSalesPayrollCommissionMap($month, $year);
    $technicalCommissionMap = getTechnicalPayrollCommissionMap($month, $year);
    $attendanceUserIds = array();

    foreach ($members as $m) {
        $attendanceUserIds[] = trim($m['attendance_user_id']);
    }

    $attendanceMap      = getAttendanceRowsMap($month, $year, $attendanceUserIds);
    $workingDaysOfMonth = intval($config['working_days']);

    $finalData = array();

    foreach ($members as $member) {
        $rows = buildMonthlyRowsForMember($member, $attendanceMap, $month, $year);

        $leaveRemain              = floatval($member['leave_days_default']);
        $leaveUsed                = 0.0;
        $actualDays               = 0;
        $totalPaidSeconds         = 0;
        $totalAbsentSec           = 0;
        $lateCount                = 0;
        $lateOccurrenceCount      = 0;
        $totalLateDeductSec       = 0;
        $totalLateEarlyDeductSec  = 0;
        $totalLeaveDayEquivalent  = 0.0;
        $countNotes               = array();
        $deductedTimeNotes        = array();

        foreach ($rows as $row) {
            $analysis = analyzeAttendanceRow($row, $config, $leaveRemain, $lateOccurrenceCount);

            $totalLeaveDayEquivalent += getLeaveUnit($analysis['leave_session']);

            if ($analysis['has_attendance'] == 1) {
                $actualDays++;
            }

            $leaveUsed        += floatval($analysis['used_leave']);
            $totalPaidSeconds += intval($analysis['paid_seconds']);
            $totalAbsentSec   += intval($analysis['absent_seconds']);
            $lateCount        += intval($analysis['late_count']);
            $totalLateDeductSec += intval($analysis['late_deduct_seconds']);

            if ($analysis['count_text'] != '') {
                $countNotes[] = date('d-m', strtotime($analysis['work_date'])) . ': ' . $analysis['count_text'];
            }

            $deductedTimeSec = intval($analysis['late_deduct_seconds']) + intval($analysis['early_deduct_seconds']);
            if ($deductedTimeSec > 0) {
                $deductedParts = array();
                if (intval($analysis['late_deduct_seconds']) > 0) {
                    $deductedParts[] = 'Đi trễ ' . secondsToDurationText($analysis['late_deduct_seconds']);
                }
                if (intval($analysis['early_deduct_seconds']) > 0) {
                    $deductedParts[] = 'Về sớm ' . secondsToDurationText($analysis['early_deduct_seconds']);
                }

                $totalLateEarlyDeductSec += $deductedTimeSec;
                $deductedTimeNotes[] = date('d-m', strtotime($analysis['work_date'])) . ': ' . implode(' | ', $deductedParts);
            }
        }

        if ($totalLateEarlyDeductSec > 0) {
            $deductedTimeNotes[] = 'Tổng cộng: ' . secondsToDurationText($totalLateEarlyDeductSec);
        }

        $baseSalary    = intval($member['base_salary']);
        $responsibilityAmount = intval($member['responsibility_amount']);
        $salaryPerDay  = ($workingDaysOfMonth > 0) ? ($baseSalary / $workingDaysOfMonth) : 0;
        $salaryPerHour = ($workingDaysOfMonth > 0 && intval($config['hours_per_day']) > 0)
            ? ($baseSalary / $workingDaysOfMonth / intval($config['hours_per_day']))
            : 0;

        $absentHours     = $totalAbsentSec / 3600;
        $deductAmount    = $salaryPerHour * $absentHours;
        $salaryWork      = max(0, $baseSalary - $deductAmount);

        $attendanceBonus = ($lateCount >= 5 || $totalLeaveDayEquivalent > 0)
            ? 0
            : intval($config['attendance_bonus']);

        $memberId = intval($member['member_id']);
        $salesRevenue = isset($salesCommissionMap[$memberId]) ? intval($salesCommissionMap[$memberId]['sales_revenue']) : 0;
        $salesCommission = isset($salesCommissionMap[$memberId]) ? intval($salesCommissionMap[$memberId]['sales_commission']) : 0;
        $managerCommission = isset($salesCommissionMap[$memberId]) ? intval($salesCommissionMap[$memberId]['manager_commission']) : 0;
        $technicalRevenue = isset($technicalCommissionMap[$memberId]) ? intval($technicalCommissionMap[$memberId]['technical_revenue']) : 0;
        $technicalCommission = isset($technicalCommissionMap[$memberId]) ? intval($technicalCommissionMap[$memberId]['technical_commission']) : 0;
        $personalCommission = $salesCommission + $technicalCommission;
        $totalCommission = $personalCommission + $managerCommission;

        $totalSalary = $salaryWork + $attendanceBonus + $totalCommission + $responsibilityAmount;

        $finalData[] = array(
            'member_id'         => $memberId,
            'name'              => $member['display_name'],
            'base_salary'       => $baseSalary,
            'responsibility_amount' => $responsibilityAmount,
            'sales_revenue'     => $salesRevenue,
            'technical_revenue' => $technicalRevenue,
            'sales_commission'  => $personalCommission,
            'technical_commission' => $technicalCommission,
            'manager_commission'=> $managerCommission,
            'total_commission'  => $totalCommission,
            'working_days'      => $workingDaysOfMonth,
            'actual_days'       => $actualDays,
            'work_days'         => round($totalPaidSeconds / 3600 / intval($config['hours_per_day']), 2),
            'total_time_text'   => secondsToTime($totalPaidSeconds),
            'absent_hours'      => round($absentHours, 2),
            'absent_time_text'  => secondsToTime($totalAbsentSec),
            'late_count'        => $lateCount,
            'late_deduct_text'  => implode(' || ', $deductedTimeNotes),
            'late_deduct_total' => secondsToDurationText($totalLateEarlyDeductSec),
            'salary_per_day'    => round($salaryPerDay),
            'salary_per_hour'   => round($salaryPerHour),
            'deduct_amount'     => round($deductAmount),
            'salary_work'       => round($salaryWork),
            'attendance_bonus'  => $attendanceBonus,
            'total_salary'      => round($totalSalary),
            'leave_default'     => floatval($member['leave_days_default']),
            'leave_used'        => $leaveUsed,
            'leave_remain'      => $leaveRemain,
            'count_text'        => implode(' || ', $countNotes),
        );
    }

    return $finalData;
}

/* =========================================================
 * PAYROLL DETAIL DATA
 * ========================================================= */

function getBangLuongDetailData($memberId, $memberName, $month, $year) {
    $config  = getSalaryConfig();
    $members = getMembersForPayroll('', intval($memberId), $memberName);
    $salesCommissionMap = getSalesPayrollCommissionMap($month, $year);
    $technicalCommissionMap = getTechnicalPayrollCommissionMap($month, $year);

    if (empty($members)) {
        return array();
    }

    $member        = $members[0];
    $attendanceMap = getAttendanceRowsMap($month, $year, array(trim($member['attendance_user_id'])));
    $rows          = buildMonthlyRowsForMember($member, $attendanceMap, $month, $year);

    $workingDaysOfMonth = intval($config['working_days']);

    $detailRows               = array();
    $baseSalary               = intval($member['base_salary']);
    $responsibilityAmount     = intval($member['responsibility_amount']);
    $leaveDefault             = floatval($member['leave_days_default']);
    $leaveRemain              = floatval($member['leave_days_default']);
    $leaveUsed                = 0.0;
    $actualDays               = 0;
    $totalPaidSeconds         = 0;
    $totalAbsentSec           = 0;
    $totalLateEarlyDeductSec  = 0;
    $lateCount                = 0;
    $lateOccurrenceCount      = 0;
    $totalLeaveDayEquivalent  = 0.0;

    foreach ($rows as $row) {
        $analysis = analyzeAttendanceRow($row, $config, $leaveRemain, $lateOccurrenceCount);

        $totalLeaveDayEquivalent += getLeaveUnit($analysis['leave_session']);

        if ($analysis['has_attendance'] == 1) {
            $actualDays++;
        }

        $leaveUsed        += floatval($analysis['used_leave']);
        $totalPaidSeconds += intval($analysis['paid_seconds']);
        $totalAbsentSec   += intval($analysis['absent_seconds']);
        $totalLateEarlyDeductSec += intval($analysis['late_deduct_seconds']) + intval($analysis['early_deduct_seconds']);
        $lateCount        += intval($analysis['late_count']);

        $dayOfWeek = date('w', strtotime($row['work_date']));
        $thuArr = array(
            0 => 'Chủ nhật',
            1 => 'Hai',
            2 => 'Ba',
            3 => 'Tư',
            4 => 'Năm',
            5 => 'Sáu',
            6 => 'Bảy'
        );

        $detailRows[] = array(
            'work_date'   => date('d-m-Y', strtotime($row['work_date'])),
            'thu'         => $thuArr[$dayOfWeek],
            'check_in'    => $analysis['check_in'],
            'check_out'   => $analysis['check_out'],
            'work_time'   => secondsToTime($analysis['paid_seconds']),
            'absent_time' => secondsToTime($analysis['absent_seconds']),
            'status'      => $analysis['status_display'],
            'note'        => $analysis['note_display'],
            'count_text'  => $analysis['count_text'],
            'used_leave'  => $analysis['used_leave'],
            'is_late'     => ($analysis['late_count'] > 0 ? 1 : 0),
        );
    }

    $salaryPerDay  = ($workingDaysOfMonth > 0) ? ($baseSalary / $workingDaysOfMonth) : 0;
    $salaryPerHour = ($workingDaysOfMonth > 0 && intval($config['hours_per_day']) > 0)
        ? ($baseSalary / $workingDaysOfMonth / intval($config['hours_per_day']))
        : 0;

    $absentHours     = $totalAbsentSec / 3600;
    $deductAmount    = $salaryPerHour * $absentHours;
    $lateEarlyDeductAmount = $salaryPerHour * ($totalLateEarlyDeductSec / 3600);
    $leaveDeductSec = max(0, $totalAbsentSec - $totalLateEarlyDeductSec);
    $leaveDeductAmount = $salaryPerHour * ($leaveDeductSec / 3600);
    $salaryWork      = max(0, $baseSalary - $deductAmount);

    $attendanceBonus = ($lateCount >= 5 || $totalLeaveDayEquivalent > 0)
        ? 0
        : intval($config['attendance_bonus']);

    $currentMemberId = intval($member['member_id']);
    $salesRevenue = isset($salesCommissionMap[$currentMemberId]) ? intval($salesCommissionMap[$currentMemberId]['sales_revenue']) : 0;
    $salesCommission = isset($salesCommissionMap[$currentMemberId]) ? intval($salesCommissionMap[$currentMemberId]['sales_commission']) : 0;
    $managerCommission = isset($salesCommissionMap[$currentMemberId]) ? intval($salesCommissionMap[$currentMemberId]['manager_commission']) : 0;
    $technicalRevenue = isset($technicalCommissionMap[$currentMemberId]) ? intval($technicalCommissionMap[$currentMemberId]['technical_revenue']) : 0;
    $technicalCommission = isset($technicalCommissionMap[$currentMemberId]) ? intval($technicalCommissionMap[$currentMemberId]['technical_commission']) : 0;
    $personalCommission = $salesCommission + $technicalCommission;
    $totalCommission = $personalCommission + $managerCommission;

    $totalSalary = $salaryWork + $attendanceBonus + $totalCommission + $responsibilityAmount;

    return array(
        'member_id'         => $currentMemberId,
        'employee_name'     => $member['display_name'],
        'month'             => $month,
        'year'              => $year,
        'base_salary'       => $baseSalary,
        'responsibility_amount' => $responsibilityAmount,
        'sales_revenue'     => $salesRevenue,
        'technical_revenue' => $technicalRevenue,
        'sales_commission'  => $personalCommission,
        'technical_commission' => $technicalCommission,
        'manager_commission'=> $managerCommission,
        'total_commission'  => $totalCommission,
        'working_days'      => $workingDaysOfMonth,
        'hours_per_day'     => $config['hours_per_day'],
        'salary_per_hour'   => round($salaryPerHour),
        'salary_per_day'    => round($salaryPerDay),
        'actual_days'       => $actualDays,
        'leave_default'     => $leaveDefault,
        'leave_used'        => $leaveUsed,
        'leave_remain'      => $leaveRemain,
        'total_paid_text'   => secondsToTime($totalPaidSeconds),
        'work_days_float'   => round($totalPaidSeconds / 3600 / intval($config['hours_per_day']), 2),
        'absent_hours'      => round($absentHours, 2),
        'absent_time_text'  => secondsToTime($totalAbsentSec),
        'deduct_amount'     => round($deductAmount),
        'late_early_deduct_amount' => round($lateEarlyDeductAmount),
        'leave_deduct_amount' => round($leaveDeductAmount),
        'late_count'        => $lateCount,
        'attendance_bonus'  => $attendanceBonus,
        'salary_work'       => round($salaryWork),
        'total_salary'      => round($totalSalary),
        'rows'              => $detailRows,
    );
}
/* =========================================================
 * LIST
 * ========================================================= */

function mosListBangLuong() {
    global $template;

    $keyword = mosGetParam($_REQUEST, 'keyword', '');
    $month   = mosGetParam($_REQUEST, 'month', date('m'));
    $year    = mosGetParam($_REQUEST, 'year', date('Y'));

    $data = getBangLuongData($month, $year, $keyword);

    $stt = 0;
    foreach ($data as $row) {
        $stt++;

        $detailLink = "main.php?option=common_lists/bangluong&mode=detail"
                    . "&month=" . urlencode($month)
                    . "&year=" . urlencode($year)
                    . "&member_id=" . urlencode($row['member_id'])
                    . "&member_name=" . urlencode($row['name']);

        $template->assign_block_vars('list', array(
            'className'        => ($stt % 2 == 1) ? 'alt' : 'inv',
            'order'            => $stt,
            'member_name'      => $row['name'],
            'detail_link'      => $detailLink,
            'base_salary'      => formatMoney($row['base_salary']),
            'responsibility_amount' => formatMoney($row['responsibility_amount']),
            'sales_revenue'    => formatMoney($row['sales_revenue']),
            'technical_revenue' => formatMoney($row['technical_revenue']),
            'sales_commission' => formatMoney($row['sales_commission']),
            'technical_commission' => formatMoney($row['technical_commission']),
            'manager_commission' => formatMoney($row['manager_commission']),
            'total_commission' => formatMoney($row['total_commission']),
            'actual_days'      => $row['actual_days'],
            'work_days'        => formatNumber2($row['work_days']),
            'total_hours'      => $row['total_time_text'],
            'absent_hours'     => $row['absent_time_text'],
            'late_count'       => $row['late_count'],
            'salary_per_hour'  => formatMoney($row['salary_per_hour']),
            'deduct_amount'    => formatMoney($row['deduct_amount']),
            'salary_work'      => formatMoney($row['salary_work']),
            'attendance_bonus' => formatMoney($row['attendance_bonus']),
            'total_salary'     => formatMoney($row['total_salary']),
            'leave_default'    => formatLeaveDays($row['leave_default']),
            'leave_used'       => formatLeaveDays($row['leave_used']),
            'leave_remain'     => formatLeaveDays($row['leave_remain']),
            'count_text'       => bangluongFormatNoteHtml($row['count_text']),
            'late_deduct_text' => bangluongFormatDeductHtml($row['late_deduct_text']),
        ));
    }

    $template->assign_vars(array(
        'keyword' => $keyword,
        'month'   => $month,
        'year'    => $year,
    ));

    for ($m = 1; $m <= 12; $m++) {
        $mm = str_pad($m, 2, '0', STR_PAD_LEFT);
        $template->assign_vars(array(
            'm' . $mm => ($month == $mm || intval($month) == intval($mm)) ? 'selected' : ''
        ));
    }

    for ($y = 2023; $y <= 2035; $y++) {
        $template->assign_vars(array(
            'y' . $y => ($year == $y) ? 'selected' : ''
        ));
    }

    $template->set_filenames_new(array(
        'bangluong' => 'common_lists/bangluong/bangluong_list.html'
    ));

    $template->pparse('bangluong');
}

/* =========================================================
 * DETAIL
 * ========================================================= */

function mosDetailBangLuong() {
    global $template;

    $memberId   = mosGetParam($_REQUEST, 'member_id', 0);
    $memberName = mosGetParam($_REQUEST, 'member_name', '');
    $month      = mosGetParam($_REQUEST, 'month', date('m'));
    $year       = mosGetParam($_REQUEST, 'year', date('Y'));

    $data = getBangLuongDetailData($memberId, $memberName, $month, $year);

    if (empty($data)) {
        die('Không tìm thấy nhân viên');
    }

    $stt = 0;
    foreach ($data['rows'] as $row) {
        $stt++;

        $template->assign_block_vars('detail', array(
            'className'   => ($stt % 2 == 1) ? 'alt' : 'inv',
            'order'       => $stt,
            'work_date'   => $row['work_date'],
            'thu'         => $row['thu'],
            'check_in'    => $row['check_in'],
            'check_out'   => $row['check_out'],
            'work_time'   => $row['work_time'],
            'absent_time' => $row['absent_time'],
            'status'      => h($row['status']),
            'note'        => h($row['note']),
            'count_text'  => h($row['count_text']),
            'late_text'   => ($row['is_late'] == 1) ? 'Có' : 'Không',
            'late_class'  => ($row['is_late'] == 1) ? ' is-late' : '',
            'used_leave'  => formatLeaveDays($row['used_leave']),
        ));
    }

    $backLink   = "main.php?option=common_lists/bangluong&mode=list&month=" . urlencode($month) . "&year=" . urlencode($year);
    $exportLink = "main.php?option=common_lists/bangluong&mode=export_detail&month=" . urlencode($month)
                . "&year=" . urlencode($year)
                . "&member_id=" . urlencode($data['member_id'])
                . "&member_name=" . urlencode($data['employee_name']);

    $template->assign_vars(array(
        'member_name'      => h($data['employee_name']),
        'month'            => $data['month'],
        'year'             => $data['year'],
        'base_salary'      => formatMoney($data['base_salary']),
        'responsibility_amount' => formatMoney($data['responsibility_amount']),
        'sales_revenue'    => formatMoney($data['sales_revenue']),
        'technical_revenue' => formatMoney($data['technical_revenue']),
        'sales_commission' => formatMoney($data['sales_commission']),
        'technical_commission' => formatMoney($data['technical_commission']),
        'manager_commission' => formatMoney($data['manager_commission']),
        'total_commission' => formatMoney($data['total_commission']),
        'working_days'     => $data['working_days'],
        'hours_per_day'    => $data['hours_per_day'],
        'salary_per_hour'  => formatMoney($data['salary_per_hour']),
        'salary_per_day'   => formatMoney($data['salary_per_day']),
        'actual_days'      => $data['actual_days'],
        'leave_default'    => formatLeaveDays($data['leave_default']),
        'leave_used'       => formatLeaveDays($data['leave_used']),
        'leave_remain'     => formatLeaveDays($data['leave_remain']),
        'total_paid_text'  => $data['total_paid_text'],
        'work_days_float'  => formatNumber2($data['work_days_float']),
        'absent_hours'     => $data['absent_time_text'],
        'deduct_amount'    => formatMoney($data['deduct_amount']),
        'late_count'       => $data['late_count'],
        'attendance_bonus' => formatMoney($data['attendance_bonus']),
        'salary_work'      => formatMoney($data['salary_work']),
        'total_salary'     => formatMoney($data['total_salary']),
        'back_link'        => $backLink,
        'export_link'      => $exportLink,
    ));

    $template->set_filenames_new(array(
        'bangluong_detail' => 'common_lists/bangluong/bangluong_detail.html'
    ));

    $template->pparse('bangluong_detail');
}

/* =========================================================
 * EXPORT LIST
 * ========================================================= */

function exportExcelBangLuong() {
    $month   = mosGetParam($_GET, 'month', date('m'));
    $year    = mosGetParam($_GET, 'year', date('Y'));
    $keyword = mosGetParam($_GET, 'keyword', '');

    $data = getBangLuongData($month, $year, $keyword);

    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=BangLuong_" . $month . "_" . $year . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "
    <meta charset='utf-8'>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; font-size: 13px; }
        th, td { border: 1px solid #000; padding: 6px; vertical-align: top; }
        th { background: #d9d9d9; font-weight: bold; text-align: center; }
        .title { font-size: 22px; font-weight: bold; text-align: center; margin-bottom: 15px; }
    </style>
    ";

    echo "<div class='title'>BẢNG LƯƠNG THÁNG " . intval($month) . " / " . intval($year) . "</div>";

    echo "<table>";
    echo "<tr>
            <th>STT</th>
            <th>Nhân viên</th>
            <th>Lương cơ bản</th>
            <th>Ngày đi làm</th>
            <th>Công quy đổi</th>
            <th>Tổng giờ</th>
            <th>Số giờ nghỉ</th>
            <th>Số lần đi trễ</th>
            <th>Lương / giờ</th>
            <th>Tiền trừ nghỉ</th>
            <th>Lương làm việc</th>
            <th>Trách nhiệm</th>
            <th>Ngày phép tháng</th>
            <th>Đã dùng phép</th>
            <th>Còn phép</th>
            <th>Đếm</th>
            <th>DT Kinh doanh</th>
            <th>DT Kỹ thuật</th>
            <th>HH cá nhân</th>
            <th>HH trưởng phòng</th>
            <th>Tổng HH</th>
            <th>Chuyên cần</th>
            <th>Tổng lương</th>
            <th>Số giờ bị trừ tiền</th>
          </tr>";

    $stt = 0;
    foreach ($data as $row) {
        $stt++;
        echo "<tr>
                <td>{$stt}</td>
                <td>" . h($row['name']) . "</td>
                <td>" . formatMoney($row['base_salary']) . "</td>
                <td>{$row['actual_days']}</td>
                <td>" . formatNumber2($row['work_days']) . "</td>
                <td>{$row['total_time_text']}</td>
                <td>" . $row['absent_time_text'] . "</td>
                <td>{$row['late_count']}</td>
                <td>" . formatMoney($row['salary_per_hour']) . "</td>
                <td>" . formatMoney($row['deduct_amount']) . "</td>
                <td>" . formatMoney($row['salary_work']) . "</td>
                <td>" . formatMoney($row['responsibility_amount']) . "</td>
                <td>" . formatLeaveDays($row['leave_default']) . "</td>
                <td>" . formatLeaveDays($row['leave_used']) . "</td>
                <td>" . formatLeaveDays($row['leave_remain']) . "</td>
                <td>" . h($row['count_text']) . "</td>
                <td>" . formatMoney($row['sales_revenue']) . "</td>
                <td>" . formatMoney($row['technical_revenue']) . "</td>
                <td>" . formatMoney($row['sales_commission']) . "</td>
                <td>" . formatMoney($row['manager_commission']) . "</td>
                <td>" . formatMoney($row['total_commission']) . "</td>
                <td>" . formatMoney($row['attendance_bonus']) . "</td>
                <td><b>" . formatMoney($row['total_salary']) . "</b></td>
                <td>" . h(str_replace(' || ', "\n", $row['late_deduct_text'])) . "</td>
              </tr>";
    }

    echo "</table>";
    exit;
}

/* =========================================================
 * EXPORT DETAIL
 * ========================================================= */

function exportExcelBangLuongDetail() {
    $memberId   = mosGetParam($_GET, 'member_id', 0);
    $memberName = mosGetParam($_GET, 'member_name', '');
    $month      = mosGetParam($_GET, 'month', date('m'));
    $year       = mosGetParam($_GET, 'year', date('Y'));

    $data = getBangLuongDetailData($memberId, $memberName, $month, $year);

    if (empty($data)) {
        die('Không tìm thấy nhân viên');
    }

    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=PhieuLuong_" . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data['employee_name']) . "_" . $month . "_" . $year . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "
    <html>
    <head>
        <meta charset='utf-8'>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12pt; color: #000; }
            table { border-collapse: collapse; width: 100%; }
            .sheet td, .sheet th { border: 1px solid #000; padding: 6px 8px; vertical-align: middle; }
            .title { font-size: 20pt; font-weight: bold; text-align: center; background: #d9ead3; padding: 14px 0; }
            .label { font-weight: bold; background: #f2f2f2; }
            .center { text-align: center; }
            .left { text-align: left; }
            .right { text-align: right; }
            .header { background: #b6d7a8; font-weight: bold; text-align: center; }
            .total-row { background: #fff2cc; font-weight: bold; }
            .section-gap { height: 12px; border: none !important; background: #fff; }
        </style>
    </head>
    <body>
    ";

    echo "<table class='sheet'>";

    echo "
    <tr>
        <td colspan='12' class='title'>PHIẾU LƯƠNG THÁNG " . intval($month) . " / " . intval($year) . "</td>
    </tr>
    ";

    echo "<tr><td colspan='12' class='section-gap'></td></tr>";

    echo "
    <tr>
        <td class='label left' colspan='2'>Nhân viên</td>
        <td class='left' colspan='4'>" . h($data['employee_name']) . "</td>
        <td class='label left' colspan='2'>Lương cơ bản</td>
        <td class='right' colspan='4'>" . formatMoney($data['base_salary']) . "</td>
    </tr>
    <tr>
        <td class='label left' colspan='2'>Ngày công chuẩn</td>
        <td class='center' colspan='4'>" . $data['working_days'] . "</td>
        <td class='label left' colspan='2'>Giờ công / ngày</td>
        <td class='center' colspan='4'>" . $data['hours_per_day'] . "</td>
    </tr>
    <tr>
        <td class='label left' colspan='2'>Lương / ngày</td>
        <td class='right' colspan='4'>" . formatMoney($data['salary_per_day']) . "</td>
        <td class='label left' colspan='2'>Lương / giờ</td>
        <td class='right' colspan='4'>" . formatMoney($data['salary_per_hour']) . "</td>
    </tr>
    <tr>
        <td class='label left' colspan='2'>Ngày đi làm</td>
        <td class='center' colspan='4'>" . $data['actual_days'] . "</td>
        <td class='label left' colspan='2'>Tổng giờ tính lương</td>
        <td class='center' colspan='4'>" . $data['total_paid_text'] . "</td>
    </tr>
    <tr>
        <td class='label left' colspan='2'>Ngày phép tháng</td>
        <td class='center' colspan='4'>" . formatLeaveDays($data['leave_default']) . "</td>
        <td class='label left' colspan='2'>Đã dùng phép</td>
        <td class='center' colspan='4'>" . formatLeaveDays($data['leave_used']) . "</td>
    </tr>
    <tr>
        <td class='label left' colspan='2'>Còn phép</td>
        <td class='center' colspan='4'>" . formatLeaveDays($data['leave_remain']) . "</td>
        <td class='label left' colspan='2'>Số giờ nghỉ</td>
        <td class='center' colspan='4'>" . $data['absent_time_text'] . "</td>
    </tr>
    <tr>
        <td class='label left' colspan='2'>Số lần đi trễ</td>
        <td class='center' colspan='4'>" . $data['late_count'] . "</td>
        <td class='label left' colspan='2'>Tiền trừ nghỉ</td>
        <td class='right' colspan='4'>" . formatMoney($data['deduct_amount']) . "</td>
    </tr>
    <tr>
        <td class='label left' colspan='2'>Lương làm việc</td>
        <td class='right' colspan='4'>" . formatMoney($data['salary_work']) . "</td>
        <td class='label left' colspan='2'>Chuyên cần</td>
        <td class='right' colspan='4'>" . formatMoney($data['attendance_bonus']) . "</td>
    </tr>
    <tr>
        <td class='label left' colspan='2'>Trách nhiệm</td>
        <td class='right' colspan='4'>" . formatMoney($data['responsibility_amount']) . "</td>
        <td class='label left' colspan='2'></td>
        <td class='right' colspan='4'></td>
    </tr>
    <tr>
        <td class='label left' colspan='2'>Doanh thu Kinh Doanh</td>
        <td class='right' colspan='4'>" . formatMoney($data['sales_revenue']) . "</td>
        <td class='label left' colspan='2'>Doanh thu Kỹ thuật</td>
        <td class='right' colspan='4'>" . formatMoney($data['technical_revenue']) . "</td>
    </tr>
    <tr>
        <td class='label left' colspan='2'>Hoa hồng cá nhân</td>
        <td class='right' colspan='4'>" . formatMoney($data['sales_commission']) . "</td>
        <td class='label left' colspan='2'>HH kỹ thuật 1%</td>
        <td class='right' colspan='4'>" . formatMoney($data['technical_commission']) . "</td>
    </tr>
    <tr>
        <td class='label left' colspan='2'>Hoa hồng trưởng phòng</td>
        <td class='right' colspan='4'>" . formatMoney($data['manager_commission']) . "</td>
        <td class='label left' colspan='2'>Tổng HH</td>
        <td class='right' colspan='4'>" . formatMoney($data['total_commission']) . "</td>
    </tr>
    <tr class='total-row'>
        <td class='left' colspan='2'>TỔNG LƯƠNG</td>
        <td class='right' colspan='10' style='font-size:16pt;'>" . formatMoney($data['total_salary']) . "</td>
    </tr>
    ";

    echo "<tr><td colspan='12' class='section-gap'></td></tr>";

    echo "
    <tr class='header'>
        <td style='width:5%;'>STT</td>
        <td style='width:10%;'>Ngày</td>
        <td style='width:8%;'>Thứ</td>
        <td style='width:10%;'>Check In</td>
        <td style='width:10%;'>Check Out</td>
        <td style='width:10%;'>Giờ tính lương</td>
        <td style='width:10%;'>Giờ nghỉ</td>
        <td style='width:8%;'>Đi trễ</td>
        <td style='width:8%;'>Dùng phép</td>
        <td style='width:10%;'>Trạng thái</td>
        <td style='width:14%;'>Note</td>
        <td style='width:17%;'>Đếm</td>
    </tr>
    ";

    $stt = 0;
    foreach ($data['rows'] as $row) {
        $stt++;
        echo "
        <tr>
            <td class='center'>{$stt}</td>
            <td class='center'>" . h($row['work_date']) . "</td>
            <td class='center'>" . h($row['thu']) . "</td>
            <td class='center'>" . h($row['check_in']) . "</td>
            <td class='center'>" . h($row['check_out']) . "</td>
            <td class='center'>" . h($row['work_time']) . "</td>
            <td class='center'>" . h($row['absent_time']) . "</td>
            <td class='center'>" . (($row['is_late'] == 1) ? 'Có' : 'Không') . "</td>
            <td class='center'>" . formatLeaveDays($row['used_leave']) . "</td>
            <td class='left'>" . h($row['status']) . "</td>
            <td class='left'>" . h($row['note']) . "</td>
            <td class='left'>" . h($row['count_text']) . "</td>
        </tr>
        ";
    }

    echo "</table>";
    echo "</body></html>";
    exit;
}
function mosSaveBangLuongNote() {
    global $db;

    header('Content-Type: application/json; charset=utf-8');

    $memberId = intval(mosGetParam($_POST, 'member_id', 0));
    $workDate = trim(mosGetParam($_POST, 'work_date', ''));
    $note     = trim(mosGetParam($_POST, 'note', ''));

    if ($memberId <= 0 || $workDate == '') {
        echo json_encode(array(
            'success' => false,
            'message' => 'Thiếu dữ liệu'
        ));
        exit;
    }

    $sqlMember = "
        SELECT attendance_user_id
        FROM tbl_member
        WHERE member_id = " . $memberId . "
        LIMIT 1
    ";
    $resultMember = $db->sql_query($sqlMember);
    $member = $db->sql_fetchrow($resultMember);

    if (empty($member) || trim($member['attendance_user_id']) == '') {
        echo json_encode(array(
            'success' => false,
            'message' => 'Không tìm thấy nhân viên'
        ));
        exit;
    }

    $attendanceUserId = trim($member['attendance_user_id']);
    $attendanceUserIdSafe = addslashes($attendanceUserId);
    $workDateSafe = addslashes($workDate);
    $noteSafe = addslashes($note);

    $sqlCheck = "
        SELECT id
        FROM tbl_chamcong
        WHERE TRIM(user_id) = '" . $attendanceUserIdSafe . "'
          AND work_date = '" . $workDateSafe . "'
        LIMIT 1
    ";
    $resultCheck = $db->sql_query($sqlCheck);
    $rowCheck = $db->sql_fetchrow($resultCheck);

    if (!empty($rowCheck['id'])) {
        $sqlUpdate = "
            UPDATE tbl_chamcong
            SET note = '" . $noteSafe . "'
            WHERE id = " . intval($rowCheck['id']) . "
        ";
        $db->sql_query($sqlUpdate);
    } else {
        $sqlInsert = "
            INSERT INTO tbl_chamcong (user_id, work_date, check_in, check_out, status, note)
            VALUES (
                '" . $attendanceUserIdSafe . "',
                '" . $workDateSafe . "',
                '',
                '',
                '',
                '" . $noteSafe . "'
            )
        ";
        $db->sql_query($sqlInsert);
    }

    echo json_encode(array(
        'success' => true,
        'message' => 'Đã lưu note',
        'note'    => $note
    ));
    exit;
}
?>
