<?php
define('_CMS_', true);
$rootDir = dirname(dirname(dirname(__FILE__)));
$bootrapDir = $rootDir . '/bootrap';
session_name('admintool');
session_start();

header('Content-Type: application/json; charset=utf-8');

function salaryRealtimeJson($data, $statusCode = 200) {
    if (function_exists('http_response_code')) {
        http_response_code($statusCode);
    }
    echo json_encode($data);
    exit;
}

function salaryRealtimeIsAdmin() {
    return (isset($_SESSION['loginname']) && strtolower($_SESSION['loginname']) == 'administrator')
        || (isset($_SESSION['membername']) && strtolower($_SESSION['membername']) == 'administrator');
}

function salaryRealtimeFeatureAllowed($loginId) {
    return salaryRealtimeIsAdmin() || in_array(intval($loginId), array(71, 28));
}

function salaryRealtimeCanViewEmployees($loginId) {
    return salaryRealtimeIsAdmin() || intval($loginId) == 71;
}

function salaryRealtimeIntParam($name, $default) {
    if (!isset($_REQUEST[$name]) || $_REQUEST[$name] === '') return $default;
    return intval($_REQUEST[$name]);
}

$loginId = isset($_SESSION['login_id']) ? intval($_SESSION['login_id']) : 0;
if ($loginId <= 0) {
    salaryRealtimeJson(array('ok' => false, 'message' => 'NO_LOGIN'), 401);
}

if (!salaryRealtimeFeatureAllowed($loginId)) {
    salaryRealtimeJson(array('ok' => false, 'message' => 'FORBIDDEN'), 403);
}

$action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : 'realtime';

if ($action == 'employees') {
    if (!salaryRealtimeCanViewEmployees($loginId)) {
        salaryRealtimeJson(array('ok' => false, 'message' => 'FORBIDDEN'), 403);
    }

    chdir($bootrapDir);
    require_once($bootrapDir . '/common.php');

    $items = array();
    $sql = "
        SELECT member_id, fullname, loginname, attendance_user_id
        FROM tbl_member
        WHERE active = 1
          AND TRIM(IFNULL(attendance_user_id, '')) <> ''
        ORDER BY
            CASE
                WHEN TRIM(IFNULL(fullname, '')) <> '' THEN TRIM(fullname)
                WHEN TRIM(IFNULL(loginname, '')) <> '' THEN TRIM(loginname)
                ELSE TRIM(attendance_user_id)
            END ASC
    ";
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) {
            $name = trim($row['fullname']);
            if ($name == '') $name = trim($row['loginname']);
            if ($name == '') $name = trim($row['attendance_user_id']);
            $items[] = array(
                'employee_id' => intval($row['member_id']),
                'employee_name' => $name
            );
        }
    }

    salaryRealtimeJson(array(
        'ok' => true,
        'login_id' => $loginId,
        'items' => $items
    ));
}

$month = salaryRealtimeIntParam('month', intval(date('m')));
$year  = salaryRealtimeIntParam('year', intval(date('Y')));
if ($month < 1 || $month > 12) $month = intval(date('m'));
if ($year < 2023 || $year > 2035) $year = intval(date('Y'));

$employeeId = salaryRealtimeIntParam('employee_id', $loginId);
if ($employeeId <= 0) $employeeId = $loginId;

if (!salaryRealtimeCanViewEmployees($loginId) && $employeeId != $loginId) {
    salaryRealtimeJson(array('ok' => false, 'message' => 'FORBIDDEN_EMPLOYEE'), 403);
}

chdir($bootrapDir);
require_once($bootrapDir . '/common.php');

$_REQUEST['mode'] = 'realtime_api';
require_once($bootrapDir . '/modules/common_lists/bangluong.php');

$data = getBangLuongDetailData($employeeId, '', $month, $year);
if (empty($data)) {
    salaryRealtimeJson(array(
        'ok' => true,
        'empty' => true,
        'message' => 'Chưa có dữ liệu lương trong tháng này'
    ));
}

$workDays = isset($data['work_days_float']) ? floatval($data['work_days_float']) : 0;
$hoursPerDay = isset($data['hours_per_day']) ? floatval($data['hours_per_day']) : 8;
$workingHours = $workDays * $hoursPerDay;
$baseSalary = intval($data['base_salary']);
$workingDaysConfig = intval($data['working_days']);
$attendanceBonus = intval($data['attendance_bonus']);
$commission = intval($data['total_commission']);
$bonus = $attendanceBonus;
$penalty = isset($data['late_early_deduct_amount']) ? intval($data['late_early_deduct_amount']) : 0;
$leaveDeductAmount = isset($data['leave_deduct_amount']) ? intval($data['leave_deduct_amount']) : 0;
$totalDeductAmount = $penalty + $leaveDeductAmount;
$salaryPerDay = isset($data['salary_per_day']) ? intval($data['salary_per_day']) : 0;
if ($salaryPerDay <= 0 && $workingDaysConfig > 0) {
    $salaryPerDay = round($baseSalary / $workingDaysConfig);
}
$baseEarned = round($workDays * $salaryPerDay);
$baseEarnedBeforeDeduct = $baseEarned + $totalDeductAmount;
$earnedToDate = max(0, $baseEarnedBeforeDeduct + $bonus + $commission - $totalDeductAmount);
$detailUrl = 'main.php?option=common_lists/bangluong&mode=detail'
    . '&month=' . urlencode(str_pad($month, 2, '0', STR_PAD_LEFT))
    . '&year=' . urlencode($year)
    . '&member_id=' . urlencode($data['member_id'])
    . '&member_name=' . urlencode($data['employee_name']);

salaryRealtimeJson(array(
    'ok' => true,
    'empty' => false,
    'employee_id' => intval($data['member_id']),
    'employee_name' => $data['employee_name'],
    'month' => intval($month),
    'year' => intval($year),
    'today_earned' => intval($earnedToDate),
    'earned_to_date' => intval($earnedToDate),
    'estimated_salary' => intval($earnedToDate),
    'base_salary' => $baseSalary,
    'base_earned' => intval($baseEarnedBeforeDeduct),
    'working_days' => floatval($data['work_days_float']),
    'total_working_days' => $workingDaysConfig,
    'working_hours' => round($workingHours, 2),
    'overtime_hours' => 0,
    'overtime_amount' => 0,
    'bonus' => $bonus,
    'attendance_bonus' => $attendanceBonus,
    'commission' => $commission,
    'penalty' => $penalty,
    'leave_deduct_amount' => $leaveDeductAmount,
    'leave_remain' => isset($data['leave_remain']) ? floatval($data['leave_remain']) : 0,
    'net_salary' => intval($earnedToDate),
    'last_updated_at' => date('Y-m-d H:i:s'),
    'detail_url' => $detailUrl
));
?>
