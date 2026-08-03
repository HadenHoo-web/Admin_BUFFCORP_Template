<?php
global $languageid, $template, $root_path, $db;

$action = mosGetParam($_REQUEST, 'mode', 'list');

if (!isset($template))
    $template = new Template();

$template->assign_vars(array(
    'ROOT'        => $root_path,
    'funname'     => 'common_lists/chamcong',
    'LANGUAGEID'  => $languageid,
));

switch($action){
    case 'export':
        exportExcel();
        break;

    case 'list':
    default:
        mosList();
        break;
}

function chamcongGetDepartmentId($departmentName){
    global $db;

    $departmentName = addslashes(trim($departmentName));
    $sql = "SELECT customer_type_id FROM tbl_customer_type WHERE LOWER(customer_type_name) = LOWER('".$departmentName."') LIMIT 1";
    if($result = $db->sql_query($sql)){
        if($row = $db->sql_fetchrow($result)){
            return intval($row['customer_type_id']);
        }
    }

    return 0;
}

function chamcongCurrentUserCanViewAll(){
    if(isset($_SESSION["loginname"]) && $_SESSION["loginname"] == 'administrator') return true;
    $loginId = intval(isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : 0);
    return in_array($loginId, array(34, 71, 76));
}

function chamcongHtml($text){
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

function chamcongNoteClass($text){
    $lower = function_exists('mb_strtolower') ? mb_strtolower((string)$text, 'UTF-8') : strtolower((string)$text);
    $class = 'attendance-note-chip';

    if (strpos($lower, 'trễ') !== false || strpos($lower, 'tre') !== false || strpos($lower, 'về sớm') !== false || strpos($lower, 've som') !== false) {
        $class .= ' attendance-note-warning';
    }
    if (strpos($lower, 'lỗi') !== false || strpos($lower, 'loi') !== false || strpos($lower, 'thiếu') !== false || strpos($lower, 'thieu') !== false) {
        $class .= ' attendance-note-danger';
    }
    if (strpos($lower, 'phép') !== false || strpos($lower, 'phep') !== false || strpos($lower, 'nghỉ') !== false || strpos($lower, 'nghi') !== false) {
        $class .= ' attendance-note-leave';
    }

    return $class;
}

function chamcongFormatNoteHtml($note){
    $note = trim((string)$note);
    if ($note === '') {
        return '<span class="attendance-note-empty">Không có ghi chú</span>';
    }

    $note = str_replace(array("\r\n", "\r"), "\n", $note);
    $note = preg_replace('/<br\s*\/?>/i', "\n", $note);
    $parts = preg_split('/\s*\|\|\s*|\n+/u', $note);
    $items = array();
    foreach ($parts as $part) {
        $part = trim($part);
        if ($part !== '') $items[] = $part;
    }

    if (count($items) === 0) {
        return '<span class="attendance-note-empty">Không có ghi chú</span>';
    }

    $html = '<div class="attendance-note-list">';
    foreach ($items as $item) {
        $html .= '<span class="'.chamcongNoteClass($item).'">'.chamcongHtml($item).'</span>';
    }
    $html .= '</div>';
    return $html;
}

function chamcongManagedDepartmentIds(){
    $loginId = intval(isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : 0);
    if($loginId == 50){
        $departmentIds = array();
        $ktSeoId = chamcongGetDepartmentId('KT SEO');
        $ktWebsiteId = chamcongGetDepartmentId('KT Website');
        if($ktSeoId > 0) $departmentIds[] = $ktSeoId;
        if($ktWebsiteId > 0) $departmentIds[] = $ktWebsiteId;
        return $departmentIds;
    }
    return array();
}

function chamcongBuildMemberScopeCondition(){
    global $db;

    if(chamcongCurrentUserCanViewAll()) return '';

    $loginId = intval(isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : 0);
    $departmentIds = chamcongManagedDepartmentIds();

    $memberCond = "active = 1";
    if(count($departmentIds) > 0){
        $departmentSql = implode(',', array_map('intval', $departmentIds));
        $memberCond .= " AND (member_type_id IN (".$departmentSql.") OR extra_member_type_id IN (".$departmentSql."))";
    } else {
        $memberCond .= " AND member_id = ".$loginId;
    }

    $attendanceIds = array();
    $names = array();
    $sql = "
        SELECT attendance_user_id, fullname, loginname
        FROM tbl_member
        WHERE ".$memberCond."
    ";
    if($result = $db->sql_query($sql)){
        while($row = $db->sql_fetchrow($result)){
            if(trim($row['attendance_user_id']) != '') $attendanceIds[] = "'".addslashes(trim($row['attendance_user_id']))."'";
            if(trim($row['fullname']) != '') $names[] = "'".addslashes(trim($row['fullname']))."'";
            if(trim($row['loginname']) != '') $names[] = "'".addslashes(trim($row['loginname']))."'";
        }
    }

    $parts = array();
    if(count($attendanceIds) > 0) $parts[] = "TRIM(user_id) IN (".implode(',', array_unique($attendanceIds)).")";
    if(count($names) > 0) $parts[] = "TRIM(name) IN (".implode(',', array_unique($names)).")";

    if(count($parts) <= 0) return " AND 1 = 0";

    return " AND (".implode(' OR ', $parts).")";
}

function mosList(){
    global $template, $db;

    $keyword = mosGetParam($_REQUEST, 'keyword', '');
    $month   = intval(mosGetParam($_REQUEST, 'month', date('m')));
    $year    = intval(mosGetParam($_REQUEST, 'year', date('Y')));

    if($month < 1 || $month > 12) $month = intval(date('m'));
    if($year < 2023 || $year > 2030) $year = intval(date('Y'));
    $month = str_pad($month, 2, '0', STR_PAD_LEFT);

    $cond = " WHERE 1 ";
    $cond .= chamcongBuildMemberScopeCondition();

    if($keyword){
        $keyword = trim($keyword);
        $cond .= " AND TRIM(name) = '".addslashes($keyword)."'";
    }

    $cond .= " AND MONTH(work_date) = '".intval($month)."'";
    $cond .= " AND YEAR(work_date) = '".intval($year)."'";

    $sql = "SELECT * FROM tbl_chamcong $cond ORDER BY work_date DESC";
    $result = $db->sql_query($sql);

    $order = 0;

    while($row = $db->sql_fetchrow($result)){
    $order++;

    $note = trim(strip_tags($row['note'] ?: ''));

    if (function_exists('mb_strtolower')) {
        $noteLower = mb_strtolower($note, 'UTF-8');
    } else {
        $noteLower = strtolower($note);
    }

    $noteClass = '';
    if (
        strpos($noteLower, 'trễ') !== false ||
        strpos($noteLower, 'đi trễ') !== false ||
        strpos($noteLower, 'tre') !== false ||
        strpos($noteLower, 'lỗi chấm công') !== false ||
        strpos($noteLower, 'loi cham cong') !== false
    ) {
        $noteClass = 'note-late';
    }

    $checkTimeClass = '';
    if (strpos($noteLower, 'lỗi chấm công') !== false || strpos($noteLower, 'loi cham cong') !== false) {
        $checkTimeClass = 'time-error';
    }

    $template->assign_block_vars('list', array(
        'className'        => ($order % 2 == 1) ? 'alt' : 'inv',
        'order'            => $order,
        'created_date'     => date('d-m-Y', strtotime($row['work_date'])),
        'member_name'      => $row['name'],
        'check_in'         => $row['check_in'] ?: '-',
        'check_out'        => $row['check_out'] ?: '-',
        'check_time_class' => $checkTimeClass,
        'work_time'        => $row['work_time'],
        'soluong'          => $row['status'],
        'note'             => chamcongFormatNoteHtml($note),
        'note_class'       => $noteClass
    ));
}

    $template->assign_vars(array(
        'keyword' => $keyword,
        'month'   => $month,
        'year'    => $year,
        'filter_label' => 'Đang lọc tháng '.$month.'/'.$year,
    ));

    for($m=1;$m<=12;$m++){
        $mm = str_pad($m,2,'0',STR_PAD_LEFT);
        $template->assign_vars(array(
            'm'.$mm => ($month == $mm)?'selected':''
        ));
    }

    for($y=2023;$y<=2030;$y++){
        $template->assign_vars(array(
            'y'.$y => ($year == $y)?'selected':''
        ));
    }

    $template->set_filenames_new(array(
        'chamcong' => 'common_lists/chamcong/chamcong_list.html'
    ));

    $template->pparse('chamcong');
}

function exportExcel(){
    global $db;

    $month = intval(isset($_GET['month']) ? $_GET['month'] : date('m'));
    $year  = intval(isset($_GET['year']) ? $_GET['year'] : date('Y'));

    if($month < 1 || $month > 12) $month = intval(date('m'));
    if($year < 2023 || $year > 2030) $year = intval(date('Y'));

    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=ChamCong_".$month."_".$year.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "
    <style>
        body { font-family: 'Times New Roman'; }
        table { border-collapse: collapse; width: 100%; font-size: 20px; }
        th, td { border: 1px solid #000; padding: 10px; text-align: center; }
        th { background: #d9d9d9; font-size: 22px; font-weight: bold; }
        .title { font-size: 28px; font-weight: bold; text-align: center; margin-bottom: 20px; }
        .total { background: #f2f2f2; font-weight: bold; font-size: 22px; }
    </style>
    ";

    echo "<div class='title'>BẢNG CHẤM CÔNG THÁNG $month / $year</div>";

    echo "<table>
            <tr>
                <th>STT</th>
                <th>Tên nhân viên</th>
                <th>Thứ</th>
                <th>Ngày</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Tổng giờ</th>
                <th>Trạng thái</th>
                <th>Note</th>
            </tr>";

    $scopeCond = chamcongBuildMemberScopeCondition();
    $sql = "
        SELECT * 
        FROM tbl_chamcong 
        WHERE MONTH(work_date) = $month 
        AND YEAR(work_date) = $year
        $scopeCond
        ORDER BY name ASC, work_date ASC
    ";

    $result = $db->sql_query($sql);

    // Lưu dữ liệu
    $rows = array();
    while($row = $db->sql_fetchrow($result)){
        $rows[] = $row;
    }

    // Gom theo nhân viên
    $grouped = array();
    foreach($rows as $row){
        $grouped[$row['name']][] = $row;
    }

    foreach($grouped as $name => $data){

        // Tính tổng giờ trước
        $totalTime = 0;
        foreach($data as $r){
            $totalTime += floatval($r['work_time']);
        }

        // In tổng giờ ở đầu
        echo "<tr class='total'>
                <td colspan='9'>
                    Tổng giờ tháng của $name: <b>".$totalTime." giờ</b>
                </td>
              </tr>";

        $stt = 0;

        foreach($data as $row){

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
            $thu = $thuArr[$dayOfWeek];

            $stt++;

            echo "<tr>
                    <td>".$stt."</td>
                    <td>".$row['name']."</td>
                    <td>".$thu."</td>
                    <td>".date('d-m-Y', strtotime($row['work_date']))."</td>
                    <td>".($row['check_in'] ?: '-')."</td>
                    <td>".($row['check_out'] ?: '-')."</td>
                    <td>".$row['work_time']."</td>
                    <td>".$row['status']."</td>
                    <td>".($row['note'] ?: '')."</td>
                  </tr>";
        }

        echo "<tr><td colspan='9'></td></tr>";
    }

    echo "</table>";
    exit;
}
?>
