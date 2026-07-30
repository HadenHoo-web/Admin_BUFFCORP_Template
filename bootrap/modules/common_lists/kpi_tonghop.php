<?php
global $languageid, $template, $root_path, $db;

$action = mosGetParam($_REQUEST, 'mode', 'dashboard');

if (!isset($template)) {
    $template = new Template();
}

$template->assign_vars(array(
    'ROOT'       => $root_path,
    'funname'    => 'common_lists/kpi_tonghop',
    'LANGUAGEID' => $languageid,
));

switch ($action) {
    case 'export':
        mosKpiTongHopExport();
        break;

    case 'dashboard':
    default:
        mosKpiTongHopDashboard();
        break;
}

function kpiTongHopCanView() {
    if (isset($_SESSION["loginname"]) && $_SESSION["loginname"] == 'administrator') return true;
    $loginId = intval(isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : 0);
    return ($loginId == 71);
}

function kpiTongHopDepartmentId($departmentName) {
    global $db;
    $departmentName = addslashes(trim($departmentName));
    $sql = "select customer_type_id from tbl_customer_type where lower(customer_type_name) = lower('".$departmentName."') limit 1";
    if ($result = $db->sql_query($sql)) {
        if ($row = $db->sql_fetchrow($result)) return intval($row['customer_type_id']);
    }
    return 0;
}

function kpiTongHopPercent($done, $target) {
    if ($target <= 0) return 0;
    return round(($done / $target) * 100, 1);
}

function kpiTongHopStatusClass($percent) {
    if ($percent >= 100) return 'kpi-good';
    if ($percent >= 80) return 'kpi-slow';
    return 'kpi-bad';
}

function kpiTongHopStatusText($percent) {
    if ($percent >= 100) return 'Hiệu suất tốt';
    if ($percent >= 80) return 'Đang chậm';
    return 'Quá chậm';
}

function kpiTongHopMoney($number) {
    return number_format(floatval($number), 0, ',', '.');
}

function kpiTongHopWorkDayTarget($workDate) {
    $weekDay = intval(date('N', strtotime($workDate)));
    if ($weekDay >= 1 && $weekDay <= 5) return 4;
    if ($weekDay == 6) return 2;
    return 0;
}

function kpiTongHopContentTaskTypesSql() {
    return "'viet_content','audit_content','nghien_cuu_tu_khoa'";
}

function kpiTongHopCleanText($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

function kpiTongHopCompanyWorkExempt($status, $note) {
    $text = trim($status.' '.$note);
    if (function_exists('mb_strtolower')) {
        $text = mb_strtolower($text, 'UTF-8');
    } else {
        $text = strtolower($text);
    }

    $keywords = array(
        'đi gặp khách hàng', 'gap khach hang', 'gặp khách hàng',
        'đi gửi hợp đồng', 'di gui hop dong', 'gửi hợp đồng', 'gui hop dong',
        'đi công tác', 'di cong tac', 'công tác', 'cong tac',
        'đi làm việc công ty', 'di lam viec cong ty',
        'làm việc bên ngoài', 'lam viec ben ngoai',
        'ra ngoài làm việc', 'ra ngoai lam viec'
    );

    foreach ($keywords as $keyword) {
        if ($keyword != '' && strpos($text, $keyword) !== false) return true;
    }
    return false;
}

function kpiTongHopAttendanceRowIsLeave($row) {
    $checkIn = isset($row['check_in']) ? trim($row['check_in']) : '';
    $checkOut = isset($row['check_out']) ? trim($row['check_out']) : '';
    $status = isset($row['status']) ? trim($row['status']) : '';
    $note = isset($row['note']) ? trim($row['note']) : '';
    if (kpiTongHopCompanyWorkExempt($status, $note)) return false;

    $hasCheckIn = ($checkIn != '' && $checkIn != '-');
    $hasCheckOut = ($checkOut != '' && $checkOut != '-');
    return (!$hasCheckIn || !$hasCheckOut);
}

function kpiTongHopLeaveDaysMap($members, $month, $year) {
    global $db;

    $leaveDays = array();
    $attendanceUsers = array();
    foreach ($members as $member) {
        $memberId = intval($member['member_id']);
        $leaveDays[$memberId] = array();
        $attendanceUserId = trim($member['attendance_user_id']);
        if ($attendanceUserId != '') $attendanceUsers[$memberId] = $attendanceUserId;
    }

    if (empty($attendanceUsers)) return $leaveDays;

    $userIds = array();
    foreach ($attendanceUsers as $attendanceUserId) $userIds[] = "'".addslashes($attendanceUserId)."'";

    $sql = "
        select *
        from tbl_chamcong
        where month(work_date) = ".intval($month)."
          and year(work_date) = ".intval($year)."
          and trim(user_id) in (".implode(',', $userIds).")
    ";
    $attendanceMap = array();
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) {
            $userId = trim($row['user_id']);
            if (!isset($attendanceMap[$userId])) $attendanceMap[$userId] = array();
            $attendanceMap[$userId][$row['work_date']] = $row;
        }
    }

    $daysInMonth = intval(date('t', strtotime(sprintf('%04d-%02d-01', $year, $month))));
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $workDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $weekDay = intval(date('N', strtotime($workDate)));
        if ($weekDay == 7) continue;
        if (strtotime($workDate) >= strtotime(date('Y-m-d'))) continue;

        foreach ($attendanceUsers as $memberId => $attendanceUserId) {
            $isLeave = false;
            if (!isset($attendanceMap[$attendanceUserId]) || !isset($attendanceMap[$attendanceUserId][$workDate])) {
                $isLeave = true;
            } else {
                $isLeave = kpiTongHopAttendanceRowIsLeave($attendanceMap[$attendanceUserId][$workDate]);
            }
            if ($isLeave) $leaveDays[$memberId][$day] = true;
        }
    }

    return $leaveDays;
}

function kpiTongHopGetMembers($departmentIds) {
    global $db;

    $members = array();
    if (empty($departmentIds)) return $members;
    $departmentSql = implode(',', array_map('intval', $departmentIds));
    $sql = "
        select m.member_id, m.fullname, m.loginname, m.attendance_user_id,
               m.member_type_id, m.extra_member_type_id,
               d1.customer_type_name as main_department,
               d2.customer_type_name as extra_department
        from tbl_member m
        left join tbl_customer_type d1 on m.member_type_id = d1.customer_type_id
        left join tbl_customer_type d2 on m.extra_member_type_id = d2.customer_type_id
        where m.active = 1
          and (m.member_type_id in (".$departmentSql.") or m.extra_member_type_id in (".$departmentSql."))
        order by
          case
            when m.member_type_id = ".intval(isset($departmentIds['sales']) ? $departmentIds['sales'] : 0)." then 1
            when m.extra_member_type_id = ".intval(isset($departmentIds['sales']) ? $departmentIds['sales'] : 0)." then 1
            when m.member_type_id = ".intval(isset($departmentIds['seo']) ? $departmentIds['seo'] : 0)." then 2
            when m.extra_member_type_id = ".intval(isset($departmentIds['seo']) ? $departmentIds['seo'] : 0)." then 2
            else 3
          end,
          m.fullname
    ";
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) {
            $members[intval($row['member_id'])] = $row;
        }
    }
    return $members;
}

function mosKpiTongHopDashboard() {
    global $db, $languageid, $template;

    $month = intval(mosGetParam($_REQUEST, 'month', date('m')));
    $year = intval(mosGetParam($_REQUEST, 'year', date('Y')));
    $departmentFilter = trim(mosGetParam($_REQUEST, 'department', 'all'));
    $selectedMemberId = intval(mosGetParam($_REQUEST, 'member_id1', 0));
    $tab = trim(mosGetParam($_REQUEST, 'tab', 'overview'));
    $allowedTabs = array('overview', 'progress', 'table', 'export');
    if (!in_array($tab, $allowedTabs)) $tab = 'overview';
    if ($month < 1 || $month > 12) $month = intval(date('m'));
    if ($year < 2020 || $year > 2100) $year = intval(date('Y'));

    if (!kpiTongHopCanView()) {
        $template->assign_vars(array(
            'funname' => 'common_lists/kpi_tonghop',
            'LANGUAGEID' => $languageid,
            'month' => sprintf('%02d', $month),
            'year' => $year,
            'department' => $departmentFilter,
            'member_id' => $selectedMemberId,
            'tab' => $tab,
            'MESSAGE_DISPLAY' => 'block',
            'MESSAGE' => 'Bạn không có quyền xem dashboard tổng hợp này.',
            'overview_display' => ($tab == 'overview' ? 'block' : 'none'),
            'progress_display' => ($tab == 'progress' ? 'block' : 'none'),
            'table_display' => ($tab == 'table' ? 'block' : 'none'),
            'export_display' => ($tab == 'export' ? 'block' : 'none'),
            'tab_overview_url' => '#',
            'tab_progress_url' => '#',
            'tab_table_url' => '#',
            'tab_export_url' => '#',
            'tab_overview_class' => ($tab == 'overview' ? 'active' : ''),
            'tab_progress_class' => ($tab == 'progress' ? 'active' : ''),
            'tab_table_class' => ($tab == 'table' ? 'active' : ''),
            'tab_export_class' => ($tab == 'export' ? 'active' : ''),
            'dashboard_title' => 'Dashboard cá nhân',
            'dashboard_note' => 'Chỉ admin và id 71 được xem dashboard này',
            'detail_name' => '-',
            'detail_department' => '-',
            'detail_main_value' => '0',
            'detail_target_value' => '0',
            'detail_missing_value' => '0',
            'detail_off_days' => 0,
            'detail_percent' => 0,
            'detail_status_text' => '-',
            'detail_status_class' => 'kpi-bad',
            'sales_panel_display' => 'none',
            'content_panel_display' => 'none',
            'top_sales_display' => 'none',
            'top_content_display' => 'none',
            'top_seo_display' => 'none',
            'total_members' => 0,
            'good_count' => 0,
            'slow_count' => 0,
            'bad_count' => 0,
            'avg_percent' => 0,
            'total_off_days' => 0,
            'chart_names' => '[]',
            'chart_percent' => '[]',
            'chart_target' => '[]',
            'chart_status_colors' => '[]',
            'daily_labels' => '[]',
            'daily_sales' => '[]',
            'daily_seo_done' => '[]',
            'dept_labels' => '[]',
            'dept_values' => '[]',
            'export_link' => '#',
        ));
        $template->set_filenames_new(array('dashboard' => 'common_lists/kpi_tonghop/kpi_tonghop_dashboard.html'));
        $template->pparse('dashboard');
        return;
    }

    $salesDepartmentId = kpiTongHopDepartmentId('Kinh Doanh');
    $contentDepartmentId = kpiTongHopDepartmentId('Content');
    $seoDepartmentId = kpiTongHopDepartmentId('KT SEO');
    $departmentIds = array();
    if ($salesDepartmentId > 0) $departmentIds['sales'] = $salesDepartmentId;
    if ($seoDepartmentId > 0) $departmentIds['seo'] = $seoDepartmentId;
    if ($contentDepartmentId > 0) $departmentIds['content'] = $contentDepartmentId;

    $members = kpiTongHopGetMembers($departmentIds);
    if ($departmentFilter == 'sales' && $salesDepartmentId > 0) {
        foreach ($members as $memberId => $member) {
            if (intval($member['member_type_id']) != $salesDepartmentId && intval($member['extra_member_type_id']) != $salesDepartmentId) unset($members[$memberId]);
        }
    }
    if ($departmentFilter == 'content' && $contentDepartmentId > 0) {
        foreach ($members as $memberId => $member) {
            if (intval($member['member_type_id']) != $contentDepartmentId && intval($member['extra_member_type_id']) != $contentDepartmentId) unset($members[$memberId]);
        }
    }
    if ($departmentFilter == 'seo' && $seoDepartmentId > 0) {
        foreach ($members as $memberId => $member) {
            if (intval($member['member_type_id']) != $seoDepartmentId && intval($member['extra_member_type_id']) != $seoDepartmentId) unset($members[$memberId]);
        }
    }

    foreach ($members as $memberId => $member) {
        $template->assign_block_vars('member_list', array(
            'member_id' => intval($memberId),
            'member_name' => htmlspecialchars(trim($member['fullname']) != '' ? $member['fullname'] : $member['loginname']),
        ));
    }

    if ($selectedMemberId > 0) {
        if (isset($members[$selectedMemberId])) {
            $members = array($selectedMemberId => $members[$selectedMemberId]);
        } else {
            $selectedMemberId = 0;
        }
    }

    $memberIds = array_keys($members);
    $memberSql = empty($memberIds) ? '0' : implode(',', array_map('intval', $memberIds));
    $leaveDays = array();

    $salesRevenue = array();
    $salesReceiptCount = array();
    $dailySales = array();
    $seoRevenue = array();
    $seoReceiptCount = array();
    $dailySeoRevenue = array();
    $moneyExpr = "cast(replace(replace(ifnull(c.congno_name,0),'.',''),',','') as unsigned)";
    $sql = "
        select c.member_id,
               cast(substring(c.ngay, 1, 2) as unsigned) as day_num,
               sum(case when c.thuchi = 0 then ".$moneyExpr." else 0 end) as revenue,
               sum(case when c.thuchi = 0 then 1 else 0 end) as receipt_count
        from tbl_congno c
        where c.active = 1
          and c.language_id = ".intval($languageid)."
          and c.member_id in (".$memberSql.")
          and substring(c.ngay, 4, 2) = '".sprintf('%02d', $month)."'
          and substring(c.ngay, 7, 4) = '".intval($year)."'
        group by c.member_id, cast(substring(c.ngay, 1, 2) as unsigned)
    ";
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) {
            $memberId = intval($row['member_id']);
            $dayNum = intval($row['day_num']);
            if (!isset($salesRevenue[$memberId])) $salesRevenue[$memberId] = 0;
            if (!isset($salesReceiptCount[$memberId])) $salesReceiptCount[$memberId] = 0;
            if (!isset($dailySales[$dayNum])) $dailySales[$dayNum] = 0;
            $salesRevenue[$memberId] += intval($row['revenue']);
            $salesReceiptCount[$memberId] += intval($row['receipt_count']);
            $dailySales[$dayNum] += intval($row['revenue']);
        }
    }

    if ($seoDepartmentId > 0) {
        $sql = "
            select w.kt_id as member_id,
                   cast(substring(c.ngay, 1, 2) as unsigned) as day_num,
                   sum(case when c.thuchi = 0 then ".$moneyExpr." else 0 end) as revenue,
                   sum(case when c.thuchi = 0 then 1 else 0 end) as receipt_count
            from tbl_congno c
            inner join tbl_website w on c.website_id = w.website_id
            where c.active = 1
              and c.language_id = ".intval($languageid)."
              and w.kt_id in (".$memberSql.")
              and substring(c.ngay, 4, 2) = '".sprintf('%02d', $month)."'
              and substring(c.ngay, 7, 4) = '".intval($year)."'
            group by w.kt_id, cast(substring(c.ngay, 1, 2) as unsigned)
        ";
        if ($result = $db->sql_query($sql)) {
            while ($row = $db->sql_fetchrow($result)) {
                $memberId = intval($row['member_id']);
                $dayNum = intval($row['day_num']);
                if (!isset($seoRevenue[$memberId])) $seoRevenue[$memberId] = 0;
                if (!isset($seoReceiptCount[$memberId])) $seoReceiptCount[$memberId] = 0;
                if (!isset($dailySeoRevenue[$dayNum])) $dailySeoRevenue[$dayNum] = 0;
                $seoRevenue[$memberId] += intval($row['revenue']);
                $seoReceiptCount[$memberId] += intval($row['receipt_count']);
                $dailySeoRevenue[$dayNum] += intval($row['revenue']);
            }
        }
    }

    $contentTasks = array();
    $dailyContentDone = array();
    $sql = "
        select gv.member_id,
               day(gv.created_date) as day_num,
               count(gv.giaoviec_id) as total_tasks,
               sum(case when gv.soluong = 2 then 1 else 0 end) as done_tasks,
               sum(case when trim(gv.giaoviec_name) <> '' and ifnull(gv.giaoviec_num,0) > 0 and trim(gv.link_demo) <> '' and gv.soluong = 2 then gv.giaoviec_num else 0 end) as kpi_done,
               sum(case when gv.soluong <> 2 and str_to_date(gv.ngay, '%d-%m-%Y') < curdate() then 1 else 0 end) as late_tasks,
               sum(case when gv.giaoviec_id is not null and not (trim(gv.giaoviec_name) <> '' and ifnull(gv.giaoviec_num,0) > 0 and trim(gv.link_demo) <> '' and gv.soluong = 2) then 1 else 0 end) as invalid_tasks
        from tbl_giaoviec gv
        where gv.active = 1
          and gv.member_id in (".$memberSql.")
          and gv.kpi_type in (".kpiTongHopContentTaskTypesSql().")
          and year(gv.created_date) = ".intval($year)."
          and month(gv.created_date) = ".intval($month)."
        group by gv.member_id, day(gv.created_date)
    ";
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) {
            $memberId = intval($row['member_id']);
            $dayNum = intval($row['day_num']);
            if (!isset($contentTasks[$memberId])) $contentTasks[$memberId] = array('total' => 0, 'done' => 0, 'kpi_done' => 0, 'late' => 0, 'invalid' => 0);
            if (!isset($dailyContentDone[$dayNum])) $dailyContentDone[$dayNum] = 0;
            $contentTasks[$memberId]['total'] += intval($row['total_tasks']);
            $contentTasks[$memberId]['done'] += intval($row['done_tasks']);
            $contentTasks[$memberId]['kpi_done'] += intval($row['kpi_done']);
            $contentTasks[$memberId]['late'] += intval($row['late_tasks']);
            $contentTasks[$memberId]['invalid'] += intval($row['invalid_tasks']);
            $dailyContentDone[$dayNum] += intval($row['kpi_done']);
        }
    }

    $rows = array();
    $goodCount = 0;
    $slowCount = 0;
    $badCount = 0;
    $percentSum = 0;
    $totalOffDays = 0;
    $deptStats = array(
        'Kinh Doanh' => array('sum' => 0, 'count' => 0),
        'KT SEO' => array('sum' => 0, 'count' => 0),
        'Content' => array('sum' => 0, 'count' => 0)
    );
    $chartNames = array();
    $chartPercent = array();
    $chartTarget = array();
    $chartStatusColors = array();
    $totalSalesDone = 0;
    $totalSalesTarget = 0;
    $totalSeoDone = 0;
    $totalSeoTarget = 0;
    $totalContentDone = 0;
    $totalContentTarget = 0;
    $detailDepartment = 'Xem tất cả';
    if ($departmentFilter == 'sales') $detailDepartment = 'Kinh Doanh';
    elseif ($departmentFilter == 'seo') $detailDepartment = 'KT SEO';
    elseif ($departmentFilter == 'content') $detailDepartment = 'Content';
    $detail = array(
        'name' => 'Tất cả nhân viên',
        'department' => $detailDepartment,
        'main_value' => 'Xem bảng bên dưới',
        'target_value' => '-',
        'missing_value' => '-',
        'off_days' => 0,
        'percent' => 0,
        'status_class' => 'kpi-bad',
        'status_text' => '-',
    );

    foreach ($members as $memberId => $member) {
        $isSales = ($salesDepartmentId > 0 && (intval($member['member_type_id']) == $salesDepartmentId || intval($member['extra_member_type_id']) == $salesDepartmentId));
        $isSeo = ($seoDepartmentId > 0 && (intval($member['member_type_id']) == $seoDepartmentId || intval($member['extra_member_type_id']) == $seoDepartmentId));
        $isContent = ($contentDepartmentId > 0 && (intval($member['member_type_id']) == $contentDepartmentId || intval($member['extra_member_type_id']) == $contentDepartmentId));
        if ($departmentFilter == 'sales' && $isSales) $departmentName = 'Kinh Doanh';
        elseif ($departmentFilter == 'seo' && $isSeo) $departmentName = 'KT SEO';
        elseif ($departmentFilter == 'content' && $isContent) $departmentName = 'Content';
        else $departmentName = $isSales ? 'Kinh Doanh' : ($isSeo ? 'KT SEO' : 'Content');

        $offCount = 0;
        $totalOffDays += $offCount;

        if ($departmentName == 'Kinh Doanh' || $departmentName == 'KT SEO') {
            $target = 75000000;
            $done = ($departmentName == 'KT SEO')
                ? (isset($seoRevenue[$memberId]) ? intval($seoRevenue[$memberId]) : 0)
                : (isset($salesRevenue[$memberId]) ? intval($salesRevenue[$memberId]) : 0);
            $percent = kpiTongHopPercent($done, $target);
            $mainValue = kpiTongHopMoney($done).'đ';
            $targetValue = kpiTongHopMoney($target).'đ';
            $missingValue = kpiTongHopMoney(max(0, $target - $done)).'đ';
            $receiptCount = ($departmentName == 'KT SEO')
                ? (isset($seoReceiptCount[$memberId]) ? intval($seoReceiptCount[$memberId]) : 0)
                : (isset($salesReceiptCount[$memberId]) ? intval($salesReceiptCount[$memberId]) : 0);
            $extraText = 'Số lần thu: '.$receiptCount;
            if ($departmentName == 'KT SEO') {
                $totalSeoDone += $done;
                $totalSeoTarget += $target;
            } else {
                $totalSalesDone += $done;
                $totalSalesTarget += $target;
            }
        } else {
            $contentTarget = 0;
            $daysInMonthForMember = intval(date('t', strtotime(sprintf('%04d-%02d-01', $year, $month))));
            for ($day = 1; $day <= $daysInMonthForMember; $day++) {
                $contentTarget += kpiTongHopWorkDayTarget(sprintf('%04d-%02d-%02d', $year, $month, $day));
            }
            $totalTasks = isset($contentTasks[$memberId]) ? intval($contentTasks[$memberId]['total']) : 0;
            $doneTasks = isset($contentTasks[$memberId]) ? intval($contentTasks[$memberId]['done']) : 0;
            $kpiDone = isset($contentTasks[$memberId]) ? intval($contentTasks[$memberId]['kpi_done']) : 0;
            $lateTasks = isset($contentTasks[$memberId]) ? intval($contentTasks[$memberId]['late']) : 0;
            $invalidTasks = isset($contentTasks[$memberId]) ? intval($contentTasks[$memberId]['invalid']) : 0;
            $target = $contentTarget;
            $done = $kpiDone;
            $percent = kpiTongHopPercent($kpiDone, $contentTarget);
            $mainValue = $kpiDone.' bài';
            $targetValue = $contentTarget.' bài';
            $missingValue = max(0, $contentTarget - $kpiDone).' bài';
            $extraText = 'Task xong: '.$doneTasks.'/'.$totalTasks.' | Trễ: '.$lateTasks.' | Chưa đủ điều kiện: '.$invalidTasks;
            $totalContentDone += $done;
            $totalContentTarget += $target;
        }

        $statusClass = kpiTongHopStatusClass($percent);
        $statusText = kpiTongHopStatusText($percent);
        if ($statusClass == 'kpi-good') $goodCount++;
        elseif ($statusClass == 'kpi-slow') $slowCount++;
        else $badCount++;

        $percentSum += $percent;
        if (isset($deptStats[$departmentName])) {
            $deptStats[$departmentName]['sum'] += $percent;
            $deptStats[$departmentName]['count']++;
        }

        $name = trim($member['fullname']) != '' ? $member['fullname'] : $member['loginname'];
        $chartNames[] = $name;
        $chartPercent[] = $percent;
        $chartTarget[] = 100;
        $chartStatusColors[] = ($statusClass == 'kpi-good') ? '#27ae60' : (($statusClass == 'kpi-slow') ? '#f39c12' : '#e74c3c');

        $rows[] = array(
            'member_id' => $memberId,
            'name' => $name,
            'department' => $departmentName,
            'main_value' => $mainValue,
            'target_value' => $targetValue,
            'missing_value' => $missingValue,
            'extra_text' => $extraText,
            'off_days' => $offCount,
            'percent' => $percent,
            'status_class' => $statusClass,
            'status_text' => $statusText,
        );
    }

    if ($selectedMemberId > 0 && count($rows) == 1) {
        $detail = $rows[0];
    }

    usort($rows, 'kpiTongHopSortRows');
    $topCounts = array('Kinh Doanh' => 0, 'KT SEO' => 0, 'Content' => 0);
    foreach ($rows as $row) {
        if (isset($topCounts[$row['department']]) && $topCounts[$row['department']] < 3) {
            $topCounts[$row['department']]++;
            $topBlock = ($row['department'] == 'Kinh Doanh') ? 'top_sales' : (($row['department'] == 'KT SEO') ? 'top_seo' : 'top_content');
            $template->assign_block_vars($topBlock, array(
                'rank' => $topCounts[$row['department']],
                'rank_text' => sprintf('%02d', $topCounts[$row['department']]),
                'fullname' => htmlspecialchars($row['name']),
                'department' => htmlspecialchars($row['department']),
                'percent' => $row['percent'],
                'status_text' => htmlspecialchars($row['status_text']),
                'main_value' => htmlspecialchars($row['main_value']),
                'target_value' => htmlspecialchars($row['target_value']),
            ));
        }
        $template->assign_block_vars('quick_kpi', array(
            'fullname' => htmlspecialchars($row['name']),
            'department' => htmlspecialchars($row['department']),
            'percent' => $row['percent'],
            'bar_width' => min(100, $row['percent']),
            'status_class' => $row['status_class'],
            'status_text' => $row['status_text'],
        ));
        $template->assign_block_vars('member_kpi', array(
            'fullname' => htmlspecialchars($row['name']),
            'department' => htmlspecialchars($row['department']),
            'main_value' => htmlspecialchars($row['main_value']),
            'target_value' => htmlspecialchars($row['target_value']),
            'missing_value' => htmlspecialchars($row['missing_value']),
            'extra_text' => htmlspecialchars($row['extra_text']),
            'off_days' => intval($row['off_days']),
            'percent' => $row['percent'],
            'bar_width' => min(100, $row['percent']),
            'status_class' => $row['status_class'],
            'status_text' => $row['status_text'],
        ));
    }

    $daysInMonth = intval(date('t', strtotime(sprintf('%04d-%02d-01', $year, $month))));
    $dailyLabels = array();
    $dailySalesValues = array();
    $dailySeoValues = array();
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $dailyLabels[] = sprintf('%02d', $day);
        $dailyMoneyValue = (isset($dailySales[$day]) ? intval($dailySales[$day]) : 0) + (isset($dailySeoRevenue[$day]) ? intval($dailySeoRevenue[$day]) : 0);
        $dailySalesValues[] = $dailyMoneyValue;
        $dailySeoValues[] = isset($dailyContentDone[$day]) ? intval($dailyContentDone[$day]) : 0;
    }

    $deptLabels = array();
    $deptValues = array();
    foreach ($deptStats as $deptName => $stat) {
        if ($stat['count'] <= 0) continue;
        $deptLabels[] = $deptName;
        $deptValues[] = round($stat['sum'] / $stat['count'], 1);
    }

    $totalMembers = count($rows);
    $avgPercent = $totalMembers > 0 ? round($percentSum / $totalMembers, 1) : 0;
    if ($selectedMemberId <= 0) {
        $detail['percent'] = $avgPercent;
        $detail['status_class'] = kpiTongHopStatusClass($avgPercent);
        $detail['status_text'] = kpiTongHopStatusText($avgPercent);
        $detail['off_days'] = $totalOffDays;
        if ($departmentFilter == 'sales') {
            $detail['main_value'] = kpiTongHopMoney($totalSalesDone).'đ';
            $detail['target_value'] = kpiTongHopMoney($totalSalesTarget).'đ';
            $detail['missing_value'] = kpiTongHopMoney(max(0, $totalSalesTarget - $totalSalesDone)).'đ';
        } elseif ($departmentFilter == 'seo') {
            $detail['main_value'] = kpiTongHopMoney($totalSeoDone).'đ';
            $detail['target_value'] = kpiTongHopMoney($totalSeoTarget).'đ';
            $detail['missing_value'] = kpiTongHopMoney(max(0, $totalSeoTarget - $totalSeoDone)).'đ';
        } elseif ($departmentFilter == 'content') {
            $detail['main_value'] = $totalContentDone.' bài';
            $detail['target_value'] = $totalContentTarget.' bài';
            $detail['missing_value'] = max(0, $totalContentTarget - $totalContentDone).' bài';
        } else {
            $detail['main_value'] = 'KD: '.kpiTongHopMoney($totalSalesDone).'đ | SEO: '.kpiTongHopMoney($totalSeoDone).'đ | CT: '.$totalContentDone.' bài';
            $detail['target_value'] = 'KD: '.kpiTongHopMoney($totalSalesTarget).'đ | SEO: '.kpiTongHopMoney($totalSeoTarget).'đ | CT: '.$totalContentTarget.' bài';
            $detail['missing_value'] = 'KD: '.kpiTongHopMoney(max(0, $totalSalesTarget - $totalSalesDone)).'đ | SEO: '.kpiTongHopMoney(max(0, $totalSeoTarget - $totalSeoDone)).'đ | CT: '.max(0, $totalContentTarget - $totalContentDone).' bài';
        }
    }
    $detailDeptAvg = $avgPercent;
    if (isset($deptStats[$detail['department']]) && $deptStats[$detail['department']]['count'] > 0) {
        $detailDeptAvg = round($deptStats[$detail['department']]['sum'] / $deptStats[$detail['department']]['count'], 1);
    }
    $salesPanelDisplay = 'block';
    $contentPanelDisplay = 'block';
    if ($departmentFilter == 'sales') $contentPanelDisplay = 'none';
    if ($departmentFilter == 'seo') $contentPanelDisplay = 'none';
    if ($departmentFilter == 'content') $salesPanelDisplay = 'none';
    if ($selectedMemberId > 0) {
        if ($detail['department'] == 'Kinh Doanh' || $detail['department'] == 'KT SEO') $contentPanelDisplay = 'none';
        if ($detail['department'] == 'Content') $salesPanelDisplay = 'none';
    }
    $topSalesDisplay = ($selectedMemberId > 0 || $departmentFilter == 'content' || $departmentFilter == 'seo') ? 'none' : 'block';
    $topSeoDisplay = ($selectedMemberId > 0 || $departmentFilter == 'content' || $departmentFilter == 'sales') ? 'none' : 'block';
    $topContentDisplay = ($selectedMemberId > 0 || $departmentFilter == 'sales' || $departmentFilter == 'seo') ? 'none' : 'block';
    $baseTabUrl = 'main.php?option=common_lists/kpi_tonghop&mode=dashboard'
        .'&month='.sprintf('%02d', $month)
        .'&year='.$year
        .'&department='.urlencode($departmentFilter)
        .'&member_id1='.$selectedMemberId
        .'&l='.$languageid;

    $template->assign_vars(array(
        'funname' => 'common_lists/kpi_tonghop',
        'LANGUAGEID' => $languageid,
        'month' => sprintf('%02d', $month),
        'year' => $year,
        'department' => $departmentFilter,
        'member_id' => $selectedMemberId,
        'tab' => $tab,
        'MESSAGE_DISPLAY' => 'none',
        'MESSAGE' => '',
        'overview_display' => ($tab == 'overview' ? 'block' : 'none'),
        'progress_display' => ($tab == 'progress' ? 'block' : 'none'),
        'table_display' => ($tab == 'table' ? 'block' : 'none'),
        'export_display' => ($tab == 'export' ? 'block' : 'none'),
        'tab_overview_url' => $baseTabUrl.'&tab=overview',
        'tab_progress_url' => $baseTabUrl.'&tab=progress',
        'tab_table_url' => $baseTabUrl.'&tab=table',
        'tab_export_url' => $baseTabUrl.'&tab=export',
        'tab_overview_class' => ($tab == 'overview' ? 'active' : ''),
        'tab_progress_class' => ($tab == 'progress' ? 'active' : ''),
        'tab_table_class' => ($tab == 'table' ? 'active' : ''),
        'tab_export_class' => ($tab == 'export' ? 'active' : ''),
        'dashboard_title' => ($selectedMemberId > 0 ? 'Dashboard cá nhân' : 'Dashboard tổng quan'),
        'dashboard_note' => ($selectedMemberId > 0 ? 'Đang xem riêng một nhân viên' : 'Chọn nhân viên để xem dashboard riêng'),
        'detail_name' => htmlspecialchars($detail['name']),
        'detail_department' => htmlspecialchars($detail['department']),
        'detail_main_value' => htmlspecialchars($detail['main_value']),
        'detail_target_value' => htmlspecialchars($detail['target_value']),
        'detail_missing_value' => htmlspecialchars($detail['missing_value']),
        'detail_off_days' => intval($detail['off_days']),
        'detail_percent' => $detail['percent'],
        'detail_dept_avg' => $detailDeptAvg,
        'detail_status_text' => htmlspecialchars($detail['status_text']),
        'detail_status_class' => $detail['status_class'],
        'sales_panel_display' => $salesPanelDisplay,
        'content_panel_display' => $contentPanelDisplay,
        'top_sales_display' => $topSalesDisplay,
        'top_content_display' => $topContentDisplay,
        'top_seo_display' => $topSeoDisplay,
        'export_link' => 'main.php?option=common_lists/kpi_tonghop&mode=export&month='.sprintf('%02d', $month).'&year='.$year.'&department='.urlencode($departmentFilter).'&member_id1='.$selectedMemberId.'&l='.$languageid,
        'total_members' => $totalMembers,
        'good_count' => $goodCount,
        'slow_count' => $slowCount,
        'bad_count' => $badCount,
        'avg_percent' => $avgPercent,
        'total_off_days' => $totalOffDays,
        'chart_names' => json_encode($chartNames),
        'chart_percent' => json_encode($chartPercent),
        'chart_target' => json_encode($chartTarget),
        'chart_status_colors' => json_encode($chartStatusColors),
        'daily_labels' => json_encode($dailyLabels),
        'daily_sales' => json_encode($dailySalesValues),
        'daily_seo_done' => json_encode($dailySeoValues),
        'dept_labels' => json_encode($deptLabels),
        'dept_values' => json_encode($deptValues),
    ));

    $template->set_filenames_new(array('dashboard' => 'common_lists/kpi_tonghop/kpi_tonghop_dashboard.html'));
    $template->pparse('dashboard');
}

function mosKpiTongHopExport() {
    global $db, $languageid;

    if (!kpiTongHopCanView()) {
        die('Bạn không có quyền xuất dữ liệu này.');
    }

    $month = intval(mosGetParam($_REQUEST, 'month', date('m')));
    $year = intval(mosGetParam($_REQUEST, 'year', date('Y')));
    $departmentFilter = trim(mosGetParam($_REQUEST, 'department', 'all'));
    $selectedMemberId = intval(mosGetParam($_REQUEST, 'member_id1', 0));
    if ($month < 1 || $month > 12) $month = intval(date('m'));
    if ($year < 2020 || $year > 2100) $year = intval(date('Y'));

    $salesDepartmentId = kpiTongHopDepartmentId('Kinh Doanh');
    $contentDepartmentId = kpiTongHopDepartmentId('Content');
    $seoDepartmentId = kpiTongHopDepartmentId('KT SEO');
    $departmentIds = array();
    if ($salesDepartmentId > 0) $departmentIds['sales'] = $salesDepartmentId;
    if ($seoDepartmentId > 0) $departmentIds['seo'] = $seoDepartmentId;
    if ($contentDepartmentId > 0) $departmentIds['content'] = $contentDepartmentId;

    $members = kpiTongHopGetMembers($departmentIds);
    foreach ($members as $memberId => $member) {
        $isSales = ($salesDepartmentId > 0 && (intval($member['member_type_id']) == $salesDepartmentId || intval($member['extra_member_type_id']) == $salesDepartmentId));
        $isSeo = ($seoDepartmentId > 0 && (intval($member['member_type_id']) == $seoDepartmentId || intval($member['extra_member_type_id']) == $seoDepartmentId));
        $isContent = ($contentDepartmentId > 0 && (intval($member['member_type_id']) == $contentDepartmentId || intval($member['extra_member_type_id']) == $contentDepartmentId));
        if ($departmentFilter == 'sales' && !$isSales) unset($members[$memberId]);
        if ($departmentFilter == 'seo' && !$isSeo) unset($members[$memberId]);
        if ($departmentFilter == 'content' && !$isContent) unset($members[$memberId]);
    }
    if ($selectedMemberId > 0) {
        if (isset($members[$selectedMemberId])) {
            $members = array($selectedMemberId => $members[$selectedMemberId]);
        } else {
            $members = array();
        }
    }

    $memberIds = array_keys($members);
    $memberSql = empty($memberIds) ? '0' : implode(',', array_map('intval', $memberIds));
    $leaveDays = array();

    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=HieuSuatKPI_".sprintf('%02d', $month)."_".$year.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'></head><body>";
    echo "<h2>Hiệu suất KPI tháng ".sprintf('%02d', $month)."/".$year."</h2>";

    echo "<h3>Bảng hiệu suất</h3>";
    echo "<table border='1' cellspacing='0' cellpadding='5'>";
    echo "<tr><th>Nhân viên</th><th>Phòng ban</th><th>Kết quả</th><th>KPI tháng</th><th>Còn thiếu</th><th>Tỷ lệ</th><th>Trạng thái</th></tr>";

    $moneyExpr = "cast(replace(replace(ifnull(c.congno_name,0),'.',''),',','') as unsigned)";
    $salesRevenue = array();
    $seoRevenue = array();
    $sql = "
        select c.member_id, sum(case when c.thuchi = 0 then ".$moneyExpr." else 0 end) as revenue
        from tbl_congno c
        where c.active = 1 and c.language_id = ".intval($languageid)."
          and c.member_id in (".$memberSql.")
          and substring(c.ngay, 4, 2) = '".sprintf('%02d', $month)."'
          and substring(c.ngay, 7, 4) = '".intval($year)."'
        group by c.member_id
    ";
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) $salesRevenue[intval($row['member_id'])] = intval($row['revenue']);
    }
    if ($seoDepartmentId > 0) {
        $sql = "
            select w.kt_id as member_id, sum(case when c.thuchi = 0 then ".$moneyExpr." else 0 end) as revenue
            from tbl_congno c
            inner join tbl_website w on c.website_id = w.website_id
            where c.active = 1 and c.language_id = ".intval($languageid)."
              and w.kt_id in (".$memberSql.")
              and substring(c.ngay, 4, 2) = '".sprintf('%02d', $month)."'
              and substring(c.ngay, 7, 4) = '".intval($year)."'
            group by w.kt_id
        ";
        if ($result = $db->sql_query($sql)) {
            while ($row = $db->sql_fetchrow($result)) $seoRevenue[intval($row['member_id'])] = intval($row['revenue']);
        }
    }

    $contentTasks = array();
    $sql = "
        select gv.member_id,
               count(gv.giaoviec_id) as total_tasks,
               sum(case when trim(gv.giaoviec_name) <> '' and ifnull(gv.giaoviec_num,0) > 0 and trim(gv.link_demo) <> '' and gv.soluong = 2 then gv.giaoviec_num else 0 end) as kpi_done,
               sum(case when gv.soluong <> 2 and str_to_date(gv.ngay, '%d-%m-%Y') < curdate() then 1 else 0 end) as late_tasks
        from tbl_giaoviec gv
        where gv.active = 1 and gv.member_id in (".$memberSql.")
          and gv.kpi_type in (".kpiTongHopContentTaskTypesSql().")
          and year(gv.created_date) = ".intval($year)."
          and month(gv.created_date) = ".intval($month)."
        group by gv.member_id
    ";
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) $contentTasks[intval($row['member_id'])] = $row;
    }

    foreach ($members as $memberId => $member) {
        $isSales = ($salesDepartmentId > 0 && (intval($member['member_type_id']) == $salesDepartmentId || intval($member['extra_member_type_id']) == $salesDepartmentId));
        $isSeo = ($seoDepartmentId > 0 && (intval($member['member_type_id']) == $seoDepartmentId || intval($member['extra_member_type_id']) == $seoDepartmentId));
        $isContent = ($contentDepartmentId > 0 && (intval($member['member_type_id']) == $contentDepartmentId || intval($member['extra_member_type_id']) == $contentDepartmentId));
        if ($departmentFilter == 'sales' && $isSales) $departmentName = 'Kinh Doanh';
        elseif ($departmentFilter == 'seo' && $isSeo) $departmentName = 'KT SEO';
        elseif ($departmentFilter == 'content' && $isContent) $departmentName = 'Content';
        else $departmentName = $isSales ? 'Kinh Doanh' : ($isSeo ? 'KT SEO' : 'Content');
        $name = trim($member['fullname']) != '' ? $member['fullname'] : $member['loginname'];
        $offCount = 0;

        if ($departmentName == 'Kinh Doanh' || $departmentName == 'KT SEO') {
            $target = 75000000;
            $done = ($departmentName == 'KT SEO')
                ? (isset($seoRevenue[$memberId]) ? intval($seoRevenue[$memberId]) : 0)
                : (isset($salesRevenue[$memberId]) ? intval($salesRevenue[$memberId]) : 0);
            $resultText = kpiTongHopMoney($done).'đ';
            $targetText = kpiTongHopMoney($target).'đ';
            $missingText = kpiTongHopMoney(max(0, $target - $done)).'đ';
            $percent = kpiTongHopPercent($done, $target);
        } else {
            $target = 0;
            $daysInMonth = intval(date('t', strtotime(sprintf('%04d-%02d-01', $year, $month))));
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $target += kpiTongHopWorkDayTarget(sprintf('%04d-%02d-%02d', $year, $month, $day));
            }
            $done = isset($contentTasks[$memberId]) ? intval($contentTasks[$memberId]['kpi_done']) : 0;
            $resultText = $done.' bài';
            $targetText = $target.' bài';
            $missingText = max(0, $target - $done).' bài';
            $percent = kpiTongHopPercent($done, $target);
        }

        echo "<tr>";
        echo "<td>".kpiTongHopCleanText($name)."</td>";
        echo "<td>".kpiTongHopCleanText($departmentName)."</td>";
        echo "<td>".$resultText."</td>";
        echo "<td>".$targetText."</td>";
        echo "<td>".$missingText."</td>";
        echo "<td>".$percent."%</td>";
        echo "<td>".kpiTongHopCleanText(kpiTongHopStatusText($percent))."</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<h3>Dữ liệu Thu tiền Kinh Doanh / KT SEO</h3>";
    echo "<table border='1' cellspacing='0' cellpadding='5'>";
    echo "<tr><th>Ngày</th><th>Nhân viên</th><th>Số tiền</th><th>Loại</th></tr>";
    $sql = "
        select c.ngay, c.congno_name, c.thuchi, m.fullname
        from tbl_congno c
        left join tbl_member m on c.member_id = m.member_id
        where c.active = 1 and c.language_id = ".intval($languageid)."
          and c.member_id in (".$memberSql.")
          and substring(c.ngay, 4, 2) = '".sprintf('%02d', $month)."'
          and substring(c.ngay, 7, 4) = '".intval($year)."'
        order by c.ngay, m.fullname
    ";
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) {
            echo "<tr><td>".kpiTongHopCleanText($row['ngay'])."</td><td>".kpiTongHopCleanText($row['fullname'])."</td><td>".kpiTongHopCleanText($row['congno_name'])."</td><td>".($row['thuchi'] == 0 ? 'Thu' : 'Chi')."</td></tr>";
        }
    }
    echo "</table>";

    echo "<h3>Dữ liệu task Content hoàn thành / trễ</h3>";
    echo "<table border='1' cellspacing='0' cellpadding='5'>";
    echo "<tr><th>Ngày tạo</th><th>Nhân viên</th><th>Công việc</th><th>Loại KPI</th><th>Số lượng</th><th>Trạng thái</th><th>Deadline</th><th>Link</th></tr>";
    $sql = "
        select gv.created_date, gv.giaoviec_name, gv.kpi_type, gv.giaoviec_num, gv.soluong, gv.ngay, gv.link_demo, m.fullname
        from tbl_giaoviec gv
        left join tbl_member m on gv.member_id = m.member_id
        where gv.active = 1 and gv.member_id in (".$memberSql.")
          and gv.kpi_type in (".kpiTongHopContentTaskTypesSql().")
          and year(gv.created_date) = ".intval($year)."
          and month(gv.created_date) = ".intval($month)."
        order by gv.created_date, m.fullname
    ";
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) {
            $status = intval($row['soluong']) == 2 ? 'Đã xong' : (intval($row['soluong']) == 1 ? 'Đang thực hiện' : 'Chưa thực hiện');
            echo "<tr><td>".kpiTongHopCleanText($row['created_date'])."</td><td>".kpiTongHopCleanText($row['fullname'])."</td><td>".kpiTongHopCleanText($row['giaoviec_name'])."</td><td>".kpiTongHopCleanText($row['kpi_type'])."</td><td>".intval($row['giaoviec_num'])."</td><td>".$status."</td><td>".kpiTongHopCleanText($row['ngay'])."</td><td>".kpiTongHopCleanText($row['link_demo'])."</td></tr>";
        }
    }
    echo "</table>";

    echo "</body></html>";
    exit;
}

function kpiTongHopSortRows($a, $b) {
    $deptOrder = array('Kinh Doanh' => 1, 'KT SEO' => 2, 'Content' => 3);
    $aDept = isset($deptOrder[$a['department']]) ? $deptOrder[$a['department']] : 9;
    $bDept = isset($deptOrder[$b['department']]) ? $deptOrder[$b['department']] : 9;
    if ($aDept != $bDept) return ($aDept < $bDept) ? -1 : 1;
    if ($a['percent'] == $b['percent']) return strcmp($a['name'], $b['name']);
    return ($a['percent'] > $b['percent']) ? -1 : 1;
}
?>
