<?php
$root = dirname(__DIR__);
$files = array(
    $root.'/bootrap/modules/common_lists/giaoviec.php',
    $root.'/bootrap/templates/default/vietnam/common_lists/giaoviec/giaoviec_list.html',
    $root.'/bootrap/templates/default/vietnam/common_lists/giaoviec/giaoviec_info.html',
);
$source = implode("\n", array_map('file_get_contents', $files));
foreach (array('function mosListNew', 'buffcorp-server-rendered', 'gv-status-form', 'gv-drag-ghost', 'dragCandidate', 'data-task-status', 'scrollbar-width:none', 'aria-label="Công việc chưa thực hiện"', 'name="member_id"', 'name="website_id"', 'name="soluong"', 'name="ngay"') as $marker) {
    if (strpos($source, $marker) === false) throw new RuntimeException('Missing task UI marker: '.$marker);
}
foreach (array('gv-employee-count', 'gvEmployeeCount', 'updateEmployeeCount', 'employeeView && !employeeView.hidden') as $marker) {
    if (strpos($source, $marker) === false) throw new RuntimeException('Missing employee counter marker: '.$marker);
}
foreach (array('data-status-filter="todo"', "statusFilter = 'all'", 'gv-status-filtered', 'column.hidden = statusFilter !== \'all\'') as $marker) {
    if (strpos($source, $marker) === false) throw new RuntimeException('Missing task status filter marker: '.$marker);
}
foreach (array('padding:0 11px 0 42px !important', 'gv-task-card.saving', 'name="ajax_status" value="1"', 'new FormData(form)', 'GV_STATUS_SAVED', 'saveTaskStatus(card, oldList, oldStatus)') as $marker) {
    if (strpos($source, $marker) === false) throw new RuntimeException('Missing non-reloading task update marker: '.$marker);
}
if (strpos($source, '.gv-board.gv-status-filtered .gv-task-list { display:grid; grid-template-columns:repeat(3,minmax(0,1fr));') === false) {
    throw new RuntimeException('Focused-status cards must use a three-column grid.');
}
if (strpos($source, 'mode=delete&amp;l={LANGUAGEID}&amp;id={list.giaoviec_id}&amp;member_id1={member_id}') === false) {
    throw new RuntimeException('Delete action must retain the employee detail context.');
}
require_once $root.'/bootrap/includes/ui_layout.php';
$taskUi = '<div class="gv-task-ui buffcorp-server-rendered"><form class="gv-status-form"></form><a class="gv-task-title">Chi tiết</a></div>';
if (buffcorpPrepareModuleHtml($taskUi, 'common_lists/giaoviec', 'list') !== $taskUi) {
    throw new RuntimeException('Task UI must bypass the legacy form wrapper.');
}
echo "Task UI smoke OK\n";
