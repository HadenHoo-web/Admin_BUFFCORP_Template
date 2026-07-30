<?php
global $languageid, $template, $root_path, $db;

$action = mosGetParam($_REQUEST, 'mode', 'dashboard');

if (!isset($template)) {
    $template = new Template();
}

$isAdminDashboardUser = (
    (isset($_SESSION["loginname"]) && strtolower($_SESSION["loginname"]) == 'administrator')
    || (isset($_SESSION["membername"]) && strtolower($_SESSION["membername"]) == 'administrator')
    || (isset($_SESSION["login_id"]) && (int)$_SESSION["login_id"] == 71)
);

if (!$isAdminDashboardUser) {
    echo '<script type="text/javascript">window.location.href="main.php?option=common_lists/giaoviec&mode=list&l='.intval($languageid).'";</script>';
    echo '<p>Bạn không có quyền xem dashboard này.</p>';
    exit;
}

switch ($action) {
    case 'dashboard':
    default:
        adminDashboardRender();
        break;
}

function adminDashHtml($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

function adminDashMoneyValue($value) {
    $value = preg_replace('/[^0-9\-]/', '', (string)$value);
    return (int)$value;
}

function adminDashMoneyExpr($alias) {
    return "CAST(REPLACE(REPLACE(IFNULL(".$alias.".congno_name,0),'.',''),',','') AS UNSIGNED)";
}

function adminDashFormatMoney($value) {
    return number_format((int)$value, 0, ',', '.').' đ';
}

function adminDashFormatCompactMoney($value) {
    $value = (int)$value;
    if ($value >= 1000000000) return number_format($value / 1000000000, 2, ',', '.').' Tỷ';
    if ($value >= 1000000) return number_format($value / 1000000, 2, ',', '.').' Tr';
    return number_format($value, 0, ',', '.').' đ';
}

function adminDashPercent($current, $previous) {
    if ((int)$previous == 0) return ((int)$current > 0) ? 100 : 0;
    return round((($current - $previous) / $previous) * 100, 1);
}

function adminDashTrendHtml($percent, $positiveIsGood) {
    $percent = (float)$percent;
    $arrow = ($percent >= 0) ? '▲' : '▼';
    $good = $positiveIsGood ? ($percent >= 0) : ($percent <= 0);
    $class = $good ? '' : ' danger';
    return '<div class="admin-kpi-note'.$class.'"><span>'.$arrow.'</span><strong>'.abs($percent).'%</strong><span>so với tháng trước</span></div>';
}

function adminDashDbRow($sql) {
    global $db;
    if (!isset($db) || !$db) return false;
    $result = $db->sql_query($sql);
    if (!$result) return false;
    return $db->sql_fetchrow($result);
}

function adminDashDbRows($sql, $limit) {
    global $db;
    $rows = array();
    if (!isset($db) || !$db) return $rows;
    $result = $db->sql_query($sql);
    if (!$result) return $rows;
    while ($row = $db->sql_fetchrow($result)) {
        $rows[] = $row;
        if ($limit > 0 && count($rows) >= $limit) break;
    }
    return $rows;
}

function adminDashScalar($sql, $field, $default) {
    $row = adminDashDbRow($sql);
    if (!$row || !isset($row[$field])) return $default;
    return $row[$field];
}

function adminDashDateCondCongno($alias, $month, $year) {
    return "SUBSTRING(".$alias.".ngay, 4, 2) = '".sprintf('%02d', $month)."' AND SUBSTRING(".$alias.".ngay, 7, 4) = '".intval($year)."'";
}

function adminDashDateCondString($alias, $field, $month, $year) {
    return "SUBSTRING(".$alias.".".$field.", 4, 2) = '".sprintf('%02d', $month)."' AND SUBSTRING(".$alias.".".$field.", 7, 4) = '".intval($year)."'";
}

function adminDashDateCondDatetime($alias, $field, $month, $year) {
    return "MONTH(".$alias.".".$field.") = ".intval($month)." AND YEAR(".$alias.".".$field.") = ".intval($year);
}

function adminDashUrl($option, $params = array()) {
    global $languageid;
    $query = array('option' => $option, 'mode' => 'list', 'l' => $languageid);
    foreach ($params as $key => $value) $query[$key] = $value;
    $parts = array();
    foreach ($query as $key => $value) $parts[] = urlencode($key).'='.urlencode($value);
    return 'main.php?'.implode('&', $parts);
}

function adminDashInfoUrl($option, $id) {
    global $languageid;
    return 'main.php?option='.urlencode($option).'&mode=info&l='.urlencode($languageid).'&id='.urlencode($id);
}

function adminDashMonthParams($month, $year) {
    return array('month' => sprintf('%02d', $month), 'year' => intval($year));
}

function adminDashMonthInfo($offset, $baseTime = null) {
    if ($baseTime === null) $baseTime = strtotime(date('Y-m-01'));
    $time = strtotime($offset.' month', $baseTime);
    return array('month' => (int)date('m', $time), 'year' => (int)date('Y', $time), 'label' => date('m/Y', $time));
}

function adminDashSelectedMonth() {
    $month = (int)mosGetParam($_REQUEST, 'month', date('m'));
    $year = (int)mosGetParam($_REQUEST, 'year', date('Y'));
    if ($month < 1 || $month > 12) $month = (int)date('m');
    if ($year < 2020 || $year > 2035) $year = (int)date('Y');
    $baseTime = strtotime(sprintf('%04d-%02d-01', $year, $month));
    $start = date('d/m/Y', $baseTime);
    $end = date('t/m/Y', $baseTime);
    return array('month' => $month, 'year' => $year, 'base_time' => $baseTime, 'range' => $start.' - '.$end, 'label' => sprintf('%02d/%04d', $month, $year));
}

function adminDashMonthOptions($selectedMonth) {
    $html = '';
    for ($month = 1; $month <= 12; $month++) {
        $value = sprintf('%02d', $month);
        $selected = ((int)$selectedMonth == $month) ? ' selected' : '';
        $html .= '<option value="'.$value.'"'.$selected.'>Tháng '.$value.'</option>';
    }
    return $html;
}

function adminDashYearOptions($selectedYear) {
    $html = '';
    $currentYear = (int)date('Y');
    for ($year = $currentYear + 1; $year >= $currentYear - 5; $year--) {
        $selected = ((int)$selectedYear == $year) ? ' selected' : '';
        $html .= '<option value="'.$year.'"'.$selected.'>'.$year.'</option>';
    }
    return $html;
}

function adminDashWorkDaysInMonth($month, $year) {
    $days = (int)date('t', strtotime(sprintf('%04d-%02d-01', $year, $month)));
    $todayMonth = ((int)date('m') == (int)$month && (int)date('Y') == (int)$year);
    $limitDay = $todayMonth ? (int)date('d') : $days;
    $workDays = 0;
    for ($day = 1; $day <= $limitDay; $day++) {
        $weekDay = (int)date('N', strtotime(sprintf('%04d-%02d-%02d', $year, $month, $day)));
        if ($weekDay != 7) $workDays++;
    }
    return $workDays;
}

function adminDashFinanceTotal($month, $year, $thuchi) {
    global $languageid;
    $moneyExpr = adminDashMoneyExpr('c');
    $sql = "
        SELECT SUM(".$moneyExpr.") AS total
        FROM tbl_congno c
        WHERE c.active = 1
          AND c.language_id = ".intval($languageid)."
          AND c.thuchi = ".intval($thuchi)."
          AND ".adminDashDateCondCongno('c', $month, $year)."
    ";
    return (int)adminDashScalar($sql, 'total', 0);
}

function adminDashMonthlyFinanceRows($month, $year, $thuchi, $limit) {
    global $languageid;
    $sql = "
        SELECT c.*, w.website_name, m.fullname
        FROM tbl_congno c
        LEFT JOIN tbl_website w ON c.website_id = w.website_id
        LEFT JOIN tbl_member m ON c.member_id = m.member_id
        WHERE c.active = 1
          AND c.language_id = ".intval($languageid)."
          AND c.thuchi = ".intval($thuchi)."
          AND ".adminDashDateCondCongno('c', $month, $year)."
        ORDER BY c.congno_id DESC
        LIMIT ".intval($limit)."
    ";
    return adminDashDbRows($sql, $limit);
}

function adminDashOfficialCustomerSqlCond($customerAlias, $month, $year) {
    global $languageid;
    return "
        ".$customerAlias.".active = 1
        AND ".adminDashDateCondDatetime($customerAlias, 'created_date', $month, $year)."
        AND EXISTS (
            SELECT 1
            FROM tbl_website w
            INNER JOIN tbl_congno cn ON cn.website_id = w.website_id
            WHERE w.customer_id = ".$customerAlias.".customer_id
              AND cn.active = 1
              AND cn.language_id = ".intval($languageid)."
              AND cn.thuchi = 0
              AND ".adminDashDateCondCongno('cn', $month, $year)."
        )
    ";
}

function adminDashBuildActivityHtml($month, $year, $attendanceCheckedIn, $attendanceTotal) {
    $items = array();
    $incomeRows = adminDashMonthlyFinanceRows($month, $year, 0, 2);
    foreach ($incomeRows as $row) {
        $name = trim($row['website_name']) != '' ? $row['website_name'] : (trim($row['fullname']) != '' ? $row['fullname'] : 'Khách hàng');
        $items[] = array('type' => 'pay', 'title' => 'Thanh toán từ '.$name, 'sub' => '+ '.adminDashFormatMoney(adminDashMoneyValue($row['congno_name'])), 'time' => $row['ngay'], 'color' => 'green', 'bg' => 'bg-green', 'href' => adminDashInfoUrl('congno/congno', $row['congno_id']));
    }

    $customerRows = adminDashDbRows("
        SELECT c.customer_id, c.customer_name, c.created_date
        FROM tbl_customer c
        WHERE ".adminDashOfficialCustomerSqlCond('c', $month, $year)."
        ORDER BY c.customer_id DESC
        LIMIT 2
    ", 2);
    foreach ($customerRows as $row) {
        $items[] = array('type' => 'user', 'title' => 'Khách hàng mới', 'sub' => $row['customer_name'], 'time' => date('d/m/Y', strtotime($row['created_date'])), 'color' => 'blue', 'bg' => 'bg-blue', 'href' => adminDashInfoUrl('customer/customer', $row['customer_id']));
    }

    $websiteRows = adminDashDbRows("
        SELECT website_id, website_name, ngay
        FROM tbl_website
        WHERE active = 1 AND ".adminDashDateCondString('tbl_website', 'ngay', $month, $year)."
        ORDER BY website_id DESC
        LIMIT 1
    ", 1);
    foreach ($websiteRows as $row) {
        $items[] = array('type' => 'doc', 'title' => 'Dự án SEO mới', 'sub' => $row['website_name'], 'time' => $row['ngay'], 'color' => 'orange', 'bg' => 'bg-orange', 'href' => adminDashInfoUrl('common_lists/website', $row['website_id']));
    }

    if ($attendanceTotal > 0) {
        $params = adminDashMonthParams($month, $year);
        $items[] = array('type' => 'clock', 'title' => 'Lượt check-in trong tháng', 'sub' => $attendanceCheckedIn.' / '.$attendanceTotal.' lượt dự kiến', 'time' => sprintf('%02d/%04d', $month, $year), 'color' => 'purple', 'bg' => 'bg-purple', 'href' => adminDashUrl('common_lists/chamcong', $params));
    }

    $costRows = adminDashMonthlyFinanceRows($month, $year, 1, 2);
    foreach ($costRows as $row) {
        $items[] = array('type' => 'cost', 'title' => 'Chi phí phát sinh', 'sub' => '- '.adminDashFormatMoney(adminDashMoneyValue($row['congno_name'])), 'time' => $row['ngay'], 'color' => 'red', 'bg' => 'bg-red', 'href' => adminDashInfoUrl('congno/congno', $row['congno_id']));
    }

    if (count($items) == 0) {
        return '<div class="admin-activity-item"><span class="admin-mini-icon bg-blue blue">'.adminDashIcon('doc').'</span><div><div class="admin-row-title">Chưa có hoạt động trong tháng</div><div class="admin-row-sub">Dữ liệu sẽ tự cập nhật khi có phát sinh mới</div></div><div class="admin-row-time">--/--</div></div>';
    }

    $html = '';
    for ($i = 0; $i < count($items) && $i < 5; $i++) {
        $item = $items[$i];
        $time = trim($item['time']) != '' ? $item['time'] : '--:--';
        $href = isset($item['href']) ? adminDashHtml($item['href']) : '#';
        $html .= '<a class="admin-activity-item admin-clickable-row" href="'.$href.'"><span class="admin-mini-icon '.$item['bg'].' '.$item['color'].'">'.adminDashIcon($item['type']).'</span><div><div class="admin-row-title">'.adminDashHtml($item['title']).'</div><div class="admin-row-sub '.$item['color'].'">'.adminDashHtml($item['sub']).'</div></div><div class="admin-row-time">'.adminDashHtml($time).'</div></a>';
    }
    return $html;
}

function adminDashIcon($type) {
    switch ($type) {
        case 'pay': return '<svg viewBox="0 0 24 24"><path d="M3 7h18v10H3zM7 11h6M7 15h4"/></svg>';
        case 'user': return '<svg viewBox="0 0 24 24"><path d="M20 21a8 8 0 0 0-16 0M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/></svg>';
        case 'clock': return '<svg viewBox="0 0 24 24"><path d="M12 8v5l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>';
        case 'cost': return '<svg viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/></svg>';
        case 'warn': return '<svg viewBox="0 0 24 24"><path d="M12 9v4M12 17h.01M10.3 3.9 2.8 17a2 2 0 0 0 1.7 3h15a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/></svg>';
        default: return '<svg viewBox="0 0 24 24"><path d="M7 3h7l5 5v13H7zM14 3v5h5M10 13h6M10 17h6"/></svg>';
    }
}

function adminDashSeoProjectRows() {
    $rows = adminDashDbRows("
        SELECT w.website_id, w.website_name, w.soluong, w.traffic, w.dat_kpi,
               COUNT(g.groupkey_id) AS keyword_count
        FROM tbl_website w
        LEFT JOIN tbl_groupkeys g ON g.website_id = w.website_id AND g.active = 1
        WHERE w.active = 1
        GROUP BY w.website_id, w.website_name, w.soluong, w.traffic, w.dat_kpi
        ORDER BY CAST(REPLACE(REPLACE(IFNULL(w.traffic,0),'.',''),',','') AS UNSIGNED) DESC, w.website_id DESC
        LIMIT 5
    ", 5);
    if (count($rows) == 0) {
        return '<tr><td colspan="4">Chưa có dữ liệu dự án SEO</td></tr>';
    }
    $html = '';
    foreach ($rows as $row) {
        $traffic = adminDashMoneyValue($row['traffic']);
        $growthClass = ((int)$row['dat_kpi'] == 1) ? 'green' : 'red';
        $growthText = ((int)$row['dat_kpi'] == 1) ? '▲ Đạt KPI' : '▼ Chưa đạt';
        $href = adminDashInfoUrl('common_lists/website', $row['website_id']);
        $html .= '<tr><td><a class="admin-table-link" href="'.adminDashHtml($href).'">'.adminDashHtml($row['website_name']).'</a></td><td>'.(int)$row['keyword_count'].'</td><td>'.number_format($traffic, 0, ',', '.').'</td><td class="'.$growthClass.'">'.$growthText.'</td></tr>';
    }
    return $html;
}

function adminDashKeywordRows() {
    $rows = adminDashDbRows("
        SELECT g.groupkey_id, g.groupkey_name, w.website_name,
               (SELECT COUNT(*) FROM tbl_di_forums f WHERE f.groupkey_id = g.groupkey_id) AS forum_count,
               (SELECT COUNT(*) FROM tbl_di_profiles p WHERE p.groupkey_id = g.groupkey_id) AS profile_count
        FROM tbl_groupkeys g
        LEFT JOIN tbl_website w ON g.website_id = w.website_id
        WHERE g.active = 1
        ORDER BY (forum_count + profile_count) DESC, g.groupkey_id DESC
        LIMIT 5
    ", 5);
    if (count($rows) == 0) {
        return '<tr><td colspan="3">Chưa có dữ liệu từ khóa</td></tr>';
    }
    $html = '';
    foreach ($rows as $row) {
        $linkCount = (int)$row['forum_count'] + (int)$row['profile_count'];
        $site = trim($row['website_name']) != '' ? $row['website_name'] : '-';
        $href = adminDashInfoUrl('seo/groupkey', $row['groupkey_id']);
        $html .= '<tr><td><a class="admin-table-link" href="'.adminDashHtml($href).'">'.adminDashHtml($row['groupkey_name']).'</a></td><td>'.adminDashHtml($site).'</td><td>'.number_format($linkCount, 0, ',', '.').' backlink</td></tr>';
    }
    return $html;
}

function adminDashAlertRows($month, $year) {
    $monthParams = adminDashMonthParams($month, $year);
    $contractExpiring = (int)adminDashScalar("
        SELECT COUNT(*) AS total
        FROM tbl_website
        WHERE active = 1
          AND ngay_kh IS NOT NULL
          AND MONTH(ngay_kh) = ".intval($month)."
          AND YEAR(ngay_kh) = ".intval($year)."
    ", 'total', 0);
    $olderReceipts = (int)adminDashScalar("
        SELECT COUNT(*) AS total
        FROM tbl_congno c
        WHERE c.active = 1
          AND c.thuchi = 0
          AND ".adminDashDateCondCongno('c', $month, $year)."
    ", 'total', 0);
    $seoNoGrowth = (int)adminDashScalar("
        SELECT COUNT(*) AS total
        FROM tbl_website
        WHERE active = 1 AND IFNULL(dat_kpi, 0) = 0
    ", 'total', 0);

    return
        '<a class="admin-alert-item admin-clickable-row" href="'.adminDashHtml(adminDashUrl('common_lists/website', array('expire_month' => sprintf('%02d', $month), 'expire_year' => intval($year)))).'"><span class="admin-mini-icon bg-red red">'.adminDashIcon('warn').'</span><div class="admin-row-title">Hợp đồng hết hạn trong tháng</div><span class="admin-alert-badge" style="--badge:#ef4444">'.$contractExpiring.'</span></a>'.
        '<a class="admin-alert-item admin-clickable-row" href="'.adminDashHtml(adminDashUrl('congno/congno', array_merge($monthParams, array('thuchi1' => 0)))).'"><span class="admin-mini-icon bg-orange orange">'.adminDashIcon('warn').'</span><div class="admin-row-title">Khoản thu trong tháng</div><span class="admin-alert-badge" style="--badge:#f97316">'.$olderReceipts.'</span></a>'.
        '<a class="admin-alert-item admin-clickable-row" href="'.adminDashHtml(adminDashUrl('common_lists/website', array('dat_kpi1' => 0, 'active' => 1))).'"><span class="admin-mini-icon bg-blue blue">'.adminDashIcon('doc').'</span><div class="admin-row-title">Dự án SEO chưa đạt KPI</div><span class="admin-alert-badge" style="--badge:#3b82f6">'.$seoNoGrowth.'</span></a>';
}

function adminDashboardRender() {
    global $languageid, $template;

    $selected = adminDashSelectedMonth();
    $current = adminDashMonthInfo(0, $selected['base_time']);
    $previous = adminDashMonthInfo(-1, $selected['base_time']);

    $revenue = adminDashFinanceTotal($current['month'], $current['year'], 0);
    $cost = adminDashFinanceTotal($current['month'], $current['year'], 1);
    $profit = $revenue - $cost;
    $prevRevenue = adminDashFinanceTotal($previous['month'], $previous['year'], 0);
    $prevCost = adminDashFinanceTotal($previous['month'], $previous['year'], 1);
    $prevProfit = $prevRevenue - $prevCost;

    $customerCount = (int)adminDashScalar("
        SELECT COUNT(*) AS total
        FROM tbl_customer c
        WHERE ".adminDashOfficialCustomerSqlCond('c', $current['month'], $current['year'])."
    ", 'total', 0);
    $prevCustomerCount = (int)adminDashScalar("
        SELECT COUNT(*) AS total
        FROM tbl_customer c
        WHERE ".adminDashOfficialCustomerSqlCond('c', $previous['month'], $previous['year'])."
    ", 'total', 0);

    $seoProjectCount = (int)adminDashScalar("SELECT COUNT(*) AS total FROM tbl_website WHERE active = 1", 'total', 0);
    $seoNewProjectCount = (int)adminDashScalar("
        SELECT COUNT(*) AS total
        FROM tbl_website w
        WHERE w.active = 1 AND ".adminDashDateCondString('w', 'ngay', $current['month'], $current['year'])."
    ", 'total', 0);

    $attendanceTotal = (int)adminDashScalar("SELECT COUNT(*) AS total FROM tbl_member WHERE active = 1 AND member_id <> 1", 'total', 0);
    $workDays = adminDashWorkDaysInMonth($current['month'], $current['year']);
    $attendanceExpected = $attendanceTotal * $workDays;
    $attendanceCheckedIn = (int)adminDashScalar("
        SELECT COUNT(DISTINCT CONCAT(TRIM(user_id), '-', work_date)) AS total
        FROM tbl_chamcong
        WHERE MONTH(work_date) = ".intval($current['month'])."
          AND YEAR(work_date) = ".intval($current['year'])."
          AND TRIM(IFNULL(check_in, '')) <> ''
          AND TRIM(IFNULL(check_in, '')) <> '-'
    ", 'total', 0);
    $attendanceAbsent = max(0, $attendanceExpected - $attendanceCheckedIn);
    $leaveToday = (int)adminDashScalar("
        SELECT COUNT(*) AS total
        FROM tbl_nghiphep
        WHERE active = 1
          AND MONTH(STR_TO_DATE(ngay, '%d/%m/%Y')) = ".intval($current['month'])."
          AND YEAR(STR_TO_DATE(ngay, '%d/%m/%Y')) = ".intval($current['year'])."
    ", 'total', 0);
    $attendancePercent = $attendanceExpected > 0 ? round(($attendanceCheckedIn / $attendanceExpected) * 100) : 0;

    $months = array();
    $revenueChart = array();
    $costChart = array();
    $sparkRevenue = array();
    $sparkCost = array();
    for ($i = -5; $i <= 0; $i++) {
        $info = adminDashMonthInfo($i, $selected['base_time']);
        $monthRevenue = adminDashFinanceTotal($info['month'], $info['year'], 0);
        $monthCost = adminDashFinanceTotal($info['month'], $info['year'], 1);
        $months[] = $info['label'];
        $revenueChart[] = round($monthRevenue / 1000000, 2);
        $costChart[] = round($monthCost / 1000000, 2);
        $sparkRevenue[] = round($monthRevenue / 1000000, 2);
        $sparkCost[] = round($monthCost / 1000000, 2);
    }

    $sourceRows = adminDashDbRows("
        SELECT IFNULL(l.loaikho_name, 'Khác') AS source_name,
               SUM(".adminDashMoneyExpr('c').") AS amount
        FROM tbl_congno c
        LEFT JOIN tbl_loaikho l ON c.loai = l.loaikho_id
        WHERE c.active = 1
          AND c.language_id = ".intval($languageid)."
          AND c.thuchi = 0
          AND ".adminDashDateCondCongno('c', $current['month'], $current['year'])."
        GROUP BY IFNULL(l.loaikho_name, 'Khác')
        ORDER BY amount DESC
        LIMIT 5
    ", 5);
    $sourceColors = array('#2385e8', '#2ec44f', '#8b45d7', '#ff9f1c', '#14b8b8');
    $sources = array();
    $sourceTotal = 0;
    foreach ($sourceRows as $row) $sourceTotal += (int)$row['amount'];
    for ($i = 0; $i < count($sourceRows); $i++) {
        $amount = (int)$sourceRows[$i]['amount'];
        $sources[] = array(
            'name' => $sourceRows[$i]['source_name'],
            'percent' => $sourceTotal > 0 ? round(($amount / $sourceTotal) * 100, 1) : 0,
            'amount' => adminDashFormatCompactMoney($amount),
            'color' => $sourceColors[$i % count($sourceColors)]
        );
    }
    if (count($sources) == 0) {
        $sources[] = array('name' => 'Chưa có doanh thu', 'percent' => 100, 'amount' => '0 đ', 'color' => '#cbd5e1');
    }

    $monthParams = adminDashMonthParams($current['month'], $current['year']);
    $receivableCustomers = (int)adminDashScalar("
        SELECT COUNT(DISTINCT website_id) AS total
        FROM tbl_congno c
        WHERE c.active = 1
          AND c.language_id = ".intval($languageid)."
          AND c.thuchi = 0
          AND ".adminDashDateCondCongno('c', $current['month'], $current['year'])."
    ", 'total', 0);
    $payableSuppliers = (int)adminDashScalar("
        SELECT COUNT(DISTINCT loai) AS total
        FROM tbl_congno c
        WHERE c.active = 1
          AND c.language_id = ".intval($languageid)."
          AND c.thuchi = 1
          AND ".adminDashDateCondCongno('c', $current['month'], $current['year'])."
    ", 'total', 0);

    $dashboardData = array(
        'months' => $months,
        'revenue' => $revenueChart,
        'cost' => $costChart,
        'sources' => $sources,
        'attendancePercent' => $attendancePercent
    );

    $template->assign_vars(array(
        'ROOT' => '',
        'funname' => 'common_lists/admin_dashboard',
        'LANGUAGEID' => $languageid,
        'TODAY_RANGE' => $selected['range'],
        'FILTER_LABEL' => 'Tháng '.$selected['label'],
        'MONTH_OPTIONS' => adminDashMonthOptions($current['month']),
        'YEAR_OPTIONS' => adminDashYearOptions($current['year']),
        'REVENUE_URL' => adminDashUrl('congno/congno', array_merge($monthParams, array('thuchi1' => 0))),
        'CUSTOMER_URL' => adminDashUrl('customer/customer', array('created_month' => sprintf('%02d', $current['month']), 'created_year' => $current['year'], 'official_new' => 1)),
        'SEO_PROJECT_URL' => adminDashUrl('common_lists/website', array('active' => 1)),
        'SEO_PROJECT_NEW_URL' => adminDashUrl('common_lists/website', array('created_month' => sprintf('%02d', $current['month']), 'created_year' => $current['year'])),
        'COST_URL' => adminDashUrl('congno/congno', array_merge($monthParams, array('thuchi1' => 1))),
        'PROFIT_URL' => adminDashUrl('congno/congno', $monthParams),
        'ATTENDANCE_URL' => adminDashUrl('common_lists/chamcong', $monthParams),
        'REVENUE_VALUE' => adminDashFormatMoney($revenue),
        'REVENUE_TREND' => adminDashTrendHtml(adminDashPercent($revenue, $prevRevenue), true),
        'REVENUE_SPARK' => implode(',', $sparkRevenue),
        'CUSTOMER_VALUE' => number_format($customerCount, 0, ',', '.'),
        'CUSTOMER_TREND' => adminDashTrendHtml(adminDashPercent($customerCount, $prevCustomerCount), true),
        'CUSTOMER_SPARK' => implode(',', array(max(0, $prevCustomerCount), $customerCount)),
        'SEO_PROJECT_VALUE' => number_format($seoProjectCount, 0, ',', '.'),
        'SEO_PROJECT_NEW' => number_format($seoNewProjectCount, 0, ',', '.'),
        'SEO_PROJECT_SPARK' => implode(',', array(max(0, $seoProjectCount - $seoNewProjectCount), $seoProjectCount)),
        'COST_VALUE' => adminDashFormatMoney($cost),
        'COST_TREND' => adminDashTrendHtml(adminDashPercent($cost, $prevCost), false),
        'COST_SPARK' => implode(',', $sparkCost),
        'PROFIT_VALUE' => adminDashFormatMoney($profit),
        'PROFIT_TREND' => adminDashTrendHtml(adminDashPercent($profit, $prevProfit), true),
        'PROFIT_SPARK' => implode(',', array_map(function($rev, $co) { return $rev - $co; }, $revenueChart, $costChart)),
        'DONUT_TOTAL' => number_format($revenue / 1000000000, 2, ',', '.'),
        'SEO_PROJECT_ROWS' => adminDashSeoProjectRows(),
        'KEYWORD_ROWS' => adminDashKeywordRows(),
        'ATTENDANCE_PERCENT' => $attendancePercent,
        'ATTENDANCE_TOTAL' => number_format($attendanceTotal, 0, ',', '.'),
        'ATTENDANCE_EXPECTED' => number_format($attendanceExpected, 0, ',', '.'),
        'ATTENDANCE_CHECKED_IN' => number_format($attendanceCheckedIn, 0, ',', '.'),
        'ATTENDANCE_ABSENT' => number_format($attendanceAbsent, 0, ',', '.'),
        'ATTENDANCE_LEAVE' => number_format($leaveToday, 0, ',', '.'),
        'ACTIVITY_ROWS' => adminDashBuildActivityHtml($current['month'], $current['year'], $attendanceCheckedIn, $attendanceExpected),
        'ALERT_ROWS' => adminDashAlertRows($current['month'], $current['year']),
        'FINANCE_REVENUE_VALUE' => adminDashFormatMoney($revenue),
        'FINANCE_REVENUE_TREND' => adminDashTrendHtml(adminDashPercent($revenue, $prevRevenue), true),
        'FINANCE_COST_VALUE' => adminDashFormatMoney($cost),
        'FINANCE_COST_TREND' => adminDashTrendHtml(adminDashPercent($cost, $prevCost), false),
        'FINANCE_PROFIT_VALUE' => adminDashFormatMoney($profit),
        'FINANCE_PROFIT_TREND' => adminDashTrendHtml(adminDashPercent($profit, $prevProfit), true),
        'RECEIVABLE_VALUE' => adminDashFormatMoney($revenue),
        'RECEIVABLE_NOTE' => number_format($receivableCustomers, 0, ',', '.').' website/khách hàng',
        'PAYABLE_VALUE' => adminDashFormatMoney($cost),
        'PAYABLE_NOTE' => number_format($payableSuppliers, 0, ',', '.').' loại chi',
        'DASHBOARD_JSON' => json_encode($dashboardData)
    ));

    $template->set_filenames_new(array(
        'admin_dashboard' => 'common_lists/admin_dashboard/admin_dashboard.html'
    ));
    $template->pparse('admin_dashboard');
}
?>
