<?php
global $languageid, $template, $root_path, $db;

$action = mosGetParam($_REQUEST, 'mode', 'list');

if (!isset($template)) {
    $template = new Template();
}

$template->assign_vars(array(
    'ROOT'       => $root_path,
    'funname'    => 'common_lists/thongkephep',
    'LANGUAGEID' => $languageid,
));

switch ($action) {
    case 'list':
    default:
        mosThongKePhepList();
        break;
}

function tkpIsAdmin()
{
    return (isset($_SESSION['membername']) && strtolower($_SESSION['membername']) == 'administrator')
        || (isset($_SESSION['loginname']) && strtolower($_SESSION['loginname']) == 'administrator');
}

function tkpHtml($text)
{
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

function tkpNumber($number)
{
    $number = (float)$number;
    if (abs($number - round($number)) < 0.00001) return (string)intval(round($number));
    if (abs($number * 2 - round($number * 2)) < 0.00001) return number_format($number, 1, '.', '');
    return number_format($number, 2, '.', '');
}

function tkpTimeToSeconds($timeStr)
{
    $timeStr = trim((string)$timeStr);
    if ($timeStr == '' || $timeStr == '-') return 0;
    if (strpos($timeStr, ' ') !== false) {
        $parts = explode(' ', $timeStr);
        $timeStr = trim(end($parts));
    }
    if (preg_match('/^(\d{1,2}):(\d{2})(?::(\d{2}))?$/', $timeStr, $m)) {
        return intval($m[1]) * 3600 + intval($m[2]) * 60 + (isset($m[3]) ? intval($m[3]) : 0);
    }
    return 0;
}

function tkpConfig()
{
    return array(
        'weekday_morning_start' => '08:30:00',
        'weekday_morning_end' => '12:00:00',
        'weekday_afternoon_start' => '13:00:00',
        'weekday_afternoon_end' => '17:30:00',
        'saturday_start' => '08:30:00',
        'saturday_end' => '12:00:00',
    );
}

function tkpWorkingDatesInYear($year)
{
    $year = intval($year);
    $currentYear = intval(date('Y'));
    $currentMonth = intval(date('m'));
    $currentDay = intval(date('d'));
    $dates = array();

    for ($month = 1; $month <= 12; $month++) {
        if ($year > $currentYear || ($year == $currentYear && $month > $currentMonth)) break;
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $maxDay = $daysInMonth;
        if ($year == $currentYear && $month == $currentMonth) $maxDay = $currentDay - 1;
        for ($day = 1; $day <= $maxDay; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            if (date('w', strtotime($date)) != 0) $dates[] = $date;
        }
    }

    return $dates;
}

function tkpLower($text)
{
    $text = trim((string)$text);
    return function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
}

function tkpHasAnyKeyword($text, $keywords)
{
    $text = tkpLower($text);
    foreach ($keywords as $keyword) {
        $keyword = tkpLower($keyword);
        if ($keyword != '' && strpos($text, $keyword) !== false) return true;
    }
    return false;
}

function tkpAttendanceNoteText($row)
{
    $status = isset($row['status']) ? $row['status'] : '';
    $note = isset($row['note']) ? $row['note'] : '';
    return trim(tkpLower($status.' '.$note));
}

function tkpHasCompanyWorkKeyword($text)
{
    return tkpHasAnyKeyword($text, array(
        'đi gặp khách hàng', 'gap khach hang', 'gặp khách hàng',
        'đi gửi hợp đồng', 'di gui hop dong', 'gửi hợp đồng', 'gui hop dong',
        'đi công tác', 'di cong tac', 'công tác', 'cong tac',
        'đi làm việc công ty', 'di lam viec cong ty',
        'làm việc bên ngoài', 'lam viec ben ngoai',
        'ra ngoài làm việc', 'ra ngoai lam viec',
        'đi mua giấy in', 'di mua giay in'
    ));
}

function tkpHasLateContext($text)
{
    return tkpHasAnyKeyword($text, array('trễ', 'tre', 'đi trễ', 'di tre', 'checkin', 'check in', 'vào trễ', 'vao tre', 'đầu giờ', 'dau gio'));
}

function tkpHasEarlyContext($text)
{
    return tkpHasAnyKeyword($text, array('về sớm', 've som', 'checkout', 'check out', 'ra sớm', 'ra som', 'cuối giờ', 'cuoi gio'));
}

function tkpNoteSegments($text)
{
    $text = tkpLower($text);
    $segments = preg_split('/[|;\\r\\n]+/u', $text);
    $clean = array();
    foreach ($segments as $segment) {
        $segment = trim($segment);
        if ($segment != '') $clean[] = $segment;
    }
    return $clean;
}

function tkpHasCompanyWorkInEventSegment($text, $event)
{
    foreach (tkpNoteSegments($text) as $segment) {
        if (!tkpHasCompanyWorkKeyword($segment)) continue;
        if ($event == 'late' && tkpHasLateContext($segment)) return true;
        if ($event == 'early' && tkpHasEarlyContext($segment)) return true;
    }
    return false;
}

function tkpIsLateCompanyWorkExempt($row)
{
    $text = tkpAttendanceNoteText($row);
    if (!tkpHasCompanyWorkKeyword($text)) return false;
    if (tkpHasCompanyWorkInEventSegment($text, 'late')) return true;
    return !tkpHasEarlyContext($text);
}

function tkpIsEarlyCompanyWorkExempt($row)
{
    return tkpHasCompanyWorkInEventSegment(tkpAttendanceNoteText($row), 'early');
}

function tkpLeaveSession($row)
{
    $workDate = isset($row['work_date']) ? $row['work_date'] : date('Y-m-d');
    $dayOfWeek = date('w', strtotime($workDate));
    $checkIn = isset($row['check_in']) ? trim($row['check_in']) : '';
    $checkOut = isset($row['check_out']) ? trim($row['check_out']) : '';
    $hasCheckIn = ($checkIn != '' && $checkIn != '-');
    $hasCheckOut = ($checkOut != '' && $checkOut != '-');

    if (!empty($row['_auto_missing']) || (!$hasCheckIn && !$hasCheckOut) || !$hasCheckIn || !$hasCheckOut) {
        return 'full';
    }

    if ($dayOfWeek == 6) return 'none';

    $checkInSec = tkpTimeToSeconds($checkIn);
    $checkOutSec = tkpTimeToSeconds($checkOut);
    $middaySec = tkpTimeToSeconds('12:00:00');
    $afternoonSec = tkpTimeToSeconds('13:00:00');

    if ($checkInSec > $middaySec && $checkOutSec < $afternoonSec) return 'full';
    if ($checkInSec > $middaySec) return tkpIsLateCompanyWorkExempt($row) ? 'none' : 'morning';
    if ($checkOutSec < $afternoonSec) return tkpIsEarlyCompanyWorkExempt($row) ? 'none' : 'afternoon';
    return 'none';
}

function tkpLeaveUnit($session)
{
    if ($session == 'full') return 1.0;
    if ($session == 'morning' || $session == 'afternoon') return 0.5;
    return 0.0;
}

function tkpLeaveText($session)
{
    if ($session == 'full') return 'Nghỉ cả ngày';
    if ($session == 'morning') return 'Nghỉ buổi sáng';
    if ($session == 'afternoon') return 'Nghỉ buổi chiều';
    return '';
}

function tkpMonthlyDefaultForMember($member)
{
    $nameParts = array();
    if (isset($member['display_name'])) $nameParts[] = $member['display_name'];
    if (isset($member['fullname'])) $nameParts[] = $member['fullname'];
    if (isset($member['loginname'])) $nameParts[] = $member['loginname'];
    if (isset($member['attendance_user_id'])) $nameParts[] = $member['attendance_user_id'];
    $name = tkpLower(implode(' ', $nameParts));

    if (
        strpos($name, 'trợ lý t.anh') !== false ||
        strpos($name, 'tro ly t.anh') !== false ||
        strpos($name, 't.anh') !== false ||
        strpos($name, 'ms.tú') !== false ||
        strpos($name, 'ms tú') !== false ||
        strpos($name, 'ms.tu') !== false ||
        strpos($name, 'ms tu') !== false
    ) {
        return 2.0;
    }

    return 1.0;
}

function tkpGetMembers($memberId)
{
    global $db;

    $cond = " where active = 1 and trim(ifnull(attendance_user_id, '')) <> ''";
    if (intval($memberId) > 0) $cond .= " and member_id = ".intval($memberId);

    $sql = "select member_id, fullname, loginname, attendance_user_id, ifnull(leave_days_default, 1) as leave_days_default
        from tbl_member
        $cond
        order by fullname, loginname";
    if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);

    $members = array();
    while ($row = $db->sql_fetchrow($result)) {
        $name = trim($row['fullname']) != '' ? trim($row['fullname']) : trim($row['loginname']);
        if ($name == '') $name = trim($row['attendance_user_id']);
        $row['display_name'] = $name;
        $members[] = $row;
    }
    return $members;
}

function tkpAttendanceMap($year, $members)
{
    global $db;

    $ids = array();
    foreach ($members as $member) {
        $attendanceId = trim($member['attendance_user_id']);
        if ($attendanceId != '') $ids[] = "'".addslashes($attendanceId)."'";
    }
    $ids = array_unique($ids);
    if (count($ids) <= 0) return array();

    $sql = "select * from tbl_chamcong
        where year(work_date) = ".intval($year)."
          and trim(user_id) in (".implode(',', $ids).")
        order by work_date";
    if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);

    $map = array();
    while ($row = $db->sql_fetchrow($result)) {
        $uid = trim($row['user_id']);
        if (!isset($map[$uid])) $map[$uid] = array();
        $map[$uid][$row['work_date']] = $row;
    }
    return $map;
}

function tkpAnalyzeMember($member, $attendanceMap, $dates, $selectedMonth)
{
    $attendanceId = trim($member['attendance_user_id']);
    $monthlyDefault = tkpMonthlyDefaultForMember($member);
    $annualDefault = $monthlyDefault * 12;
    $annualUsed = 0.0;
    $monthDetails = array();
    $months = array();

    for ($i = 1; $i <= 12; $i++) {
        $months[$i] = array(
            'default' => $monthlyDefault,
            'used' => 0.0,
            'remain' => $monthlyDefault,
            'detail_count' => 0,
        );
    }

    foreach ($dates as $date) {
        $monthIndex = intval(date('m', strtotime($date)));

        if (isset($attendanceMap[$attendanceId]) && isset($attendanceMap[$attendanceId][$date])) {
            $row = $attendanceMap[$attendanceId][$date];
            $row['_auto_missing'] = 0;
        } else {
            $row = array(
                'work_date' => $date,
                'check_in' => '',
                'check_out' => '',
                'status' => '',
                'note' => '',
                '_auto_missing' => 1,
            );
        }

        $session = tkpLeaveSession($row);
        $needLeave = tkpLeaveUnit($session);
        if ($needLeave <= 0) continue;

        $monthRemainBefore = $months[$monthIndex]['remain'];
        $usedLeave = min($needLeave, $monthRemainBefore);
        $months[$monthIndex]['used'] += $usedLeave;
        $months[$monthIndex]['remain'] = max(0, $months[$monthIndex]['remain'] - $usedLeave);
        $months[$monthIndex]['detail_count']++;

        if ($usedLeave >= $needLeave) {
            $note = 'Đã trừ phép';
        } elseif ($usedLeave > 0) {
            $note = 'Trừ '.tkpNumber($usedLeave).' phép còn lại trong tháng, sau đó hết phép tháng';
        } else {
            $note = 'Tháng này đã hết phép, không còn phép để trừ';
        }

        $annualUsed += $usedLeave;

        if ($monthIndex == intval($selectedMonth)) {
            $monthDetails[] = array(
                'date' => date('d-m-Y', strtotime($date)),
                'session' => tkpLeaveText($session),
                'used_leave' => $usedLeave,
                'remain_after' => $months[$monthIndex]['remain'],
                'check_in' => trim($row['check_in']) != '' ? trim($row['check_in']) : '-',
                'check_out' => trim($row['check_out']) != '' ? trim($row['check_out']) : '-',
                'note' => $note.(trim($row['note']) != '' ? ' | '.trim(strip_tags($row['note'])) : ''),
            );
        }
    }

    $annualRemain = 0.0;
    foreach ($months as $monthData) {
        $annualRemain += floatval($monthData['remain']);
    }

    return array(
        'annual_default' => $annualDefault,
        'monthly_default' => $monthlyDefault,
        'annual_used' => $annualUsed,
        'annual_remain' => $annualRemain,
        'month_used' => $months[intval($selectedMonth)]['used'],
        'months' => $months,
        'details' => $monthDetails,
    );
}

function mosThongKePhepList()
{
    global $template, $db;

    $month = intval(mosGetParam($_REQUEST, 'month', date('m')));
    $year = intval(mosGetParam($_REQUEST, 'year', date('Y')));
    $memberId = intval(mosGetParam($_REQUEST, 'member_id1', 0));

    if ($month < 1 || $month > 12) $month = intval(date('m'));
    if ($year < 2023 || $year > 2035) $year = intval(date('Y'));

    for ($i = 1; $i <= 12; $i++) {
        $template->assign_vars(array('m'.sprintf('%02d', $i) => ($i == $month ? 'selected="selected"' : '')));
    }
    for ($y = 2023; $y <= 2035; $y++) {
        $template->assign_vars(array('y'.$y => ($y == $year ? 'selected="selected"' : '')));
    }

    $memberCond = "where active = 1 and trim(ifnull(attendance_user_id, '')) <> ''";
    $sql = "select member_id, fullname, loginname from tbl_member $memberCond order by fullname, loginname";
    if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
    while ($row = $db->sql_fetchrow($result)) {
        $name = trim($row['fullname']) != '' ? $row['fullname'] : $row['loginname'];
        $template->assign_block_vars('member_list', array(
            'member_id' => intval($row['member_id']),
            'member_name' => tkpHtml($name),
            'selected' => (intval($row['member_id']) == $memberId ? 'selected="selected"' : ''),
        ));
    }

    $members = tkpGetMembers($memberId);
    $attendanceMap = tkpAttendanceMap($year, $members);
    $dates = tkpWorkingDatesInYear($year);

    $order = 0;
    $totalAnnualDefault = 0.0;
    $totalAnnualUsed = 0.0;
    $totalAnnualRemain = 0.0;
    $totalMonthUsed = 0.0;
    $detailOrder = 0;

    foreach ($members as $member) {
        $order++;
        $data = tkpAnalyzeMember($member, $attendanceMap, $dates, $month);
        $totalAnnualDefault += $data['annual_default'];
        $totalAnnualUsed += $data['annual_used'];
        $totalAnnualRemain += $data['annual_remain'];
        $totalMonthUsed += $data['month_used'];

        $monthVars = array();
        for ($i = 1; $i <= 12; $i++) {
            $key = sprintf('m%02d', $i);
            $remain = $data['months'][$i]['remain'];
            $used = $data['months'][$i]['used'];
            $monthVars[$key.'_remain'] = tkpNumber($remain);
            $monthVars[$key.'_used'] = tkpNumber($used);
            $monthVars[$key.'_class'] = ($remain <= 0 ? 'leave-zero' : ($remain < 1 ? 'leave-half' : 'leave-remain'));
        }

        $template->assign_block_vars('list', array(
            'className' => ($order % 2 == 1) ? 'alt' : 'inv',
            'order' => $order,
            'member_name' => tkpHtml($member['display_name']),
            'attendance_user_id' => tkpHtml($member['attendance_user_id']),
            'monthly_default' => tkpNumber($data['monthly_default']),
            'annual_default' => tkpNumber($data['annual_default']),
            'month_used' => tkpNumber($data['month_used']),
            'annual_used' => tkpNumber($data['annual_used']),
            'annual_remain' => tkpNumber($data['annual_remain']),
            'detail_count' => count($data['details']),
        ) + $monthVars);

        foreach ($data['details'] as $detail) {
            $detailOrder++;
            $template->assign_block_vars('detail_list', array(
                'className' => ($detailOrder % 2 == 1) ? 'alt' : 'inv',
                'order' => $detailOrder,
                'member_name' => tkpHtml($member['display_name']),
                'date' => $detail['date'],
                'session' => tkpHtml($detail['session']),
                'used_leave' => tkpNumber($detail['used_leave']),
                'remain_after' => tkpNumber($detail['remain_after']),
                'check_in' => tkpHtml($detail['check_in']),
                'check_out' => tkpHtml($detail['check_out']),
                'note' => tkpHtml($detail['note']),
            ));
        }
    }

    $template->assign_vars(array(
        'month' => sprintf('%02d', $month),
        'year' => $year,
        'member_id' => $memberId,
        'total_members' => count($members),
        'total_annual_default' => tkpNumber($totalAnnualDefault),
        'total_month_used' => tkpNumber($totalMonthUsed),
        'total_annual_used' => tkpNumber($totalAnnualUsed),
        'total_annual_remain' => tkpNumber($totalAnnualRemain),
        'filter_member_display' => '',
    ));

    $template->set_filenames_new(array(
        'content' => 'common_lists/thongkephep/thongkephep_list.html'
    ));
    $template->pparse('content');
}
?>
