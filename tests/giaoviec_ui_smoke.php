<?php
$root = dirname(__DIR__);
$files = array(
    $root.'/bootrap/modules/common_lists/giaoviec.php',
    $root.'/bootrap/templates/default/vietnam/common_lists/giaoviec/giaoviec_list.html',
    $root.'/bootrap/templates/default/vietnam/common_lists/giaoviec/giaoviec_info.html',
);
$module = file_get_contents($files[0]);
$source = implode("\n", array_map('file_get_contents', $files));
if (strpos($source, 'id="gvTaskToast"') === false || strpos($source, 'toastStates =') === false || strpos($source, 'function showTaskToast') === false || strpos($source, 'id="gvDeleteDialog"') === false || strpos($source, "deleteDialog.showModal()") === false || strpos($source, "link.removeAttribute('onclick')") === false || strpos($source, 'Manrope,"Segoe UI",Arial,sans-serif') === false || strpos($module, "\$_REQUEST['task_toast'] = 'saved';") === false || strpos($module, "\$_REQUEST['task_toast'] = 'deleted';") === false || strpos($module, "['MESSAGE' => '']") === false) throw new RuntimeException('Task feedback must use auto-dismissing toasts and an in-app delete confirmation.');
if (strpos($source, '.gv-button-primary { border-color:var(--gv-brand); color:#fff !important;') === false) throw new RuntimeException('Add-task primary action needs a stable high-contrast text color.');
if (strpos($source, 'body.buffcorp-dark .gv-task-ui') === false || strpos($source, 'body.buffcorp-dark .gv-form-ui') === false || strpos($source, 'body.buffcorp-dark .gv-directory,body.buffcorp-dark .gv-panel') === false) throw new RuntimeException('Task UI must provide scoped dark-mode surfaces and readable text.');
if (strpos($module, "\$month = \$selectedDate ? substr(\$selectedDate, 5, 2) : date('m');") === false || strpos($module, "\$year = \$selectedDate ? substr(\$selectedDate, 0, 4) : date('Y');") === false || strpos($module, "date('Y-m-d')") === false || strpos($module, 'order by STR_TO_DATE(LEFT(ngay, 10)') !== false) {
    throw new RuntimeException('Task directory must default to the current date, not the newest task date.');
}
foreach (array('name="day" value="{selected_date}"', 'name="period" value="{period}"', 'name="year" min="2000" max="2100"', 'gv-directory-compact-filter', 'gv-directory-time-button', 'gv-directory-filter-panel', 'data-directory-compact-mode="day"', 'data-directory-compact-mode="month"', 'data-directory-compact-mode="year"', 'showDirectoryFilter', 'goDirectoryView', 'loadDirectoryView', 'window.fetch(url', 'grid.innerHTML = nextGrid.innerHTML', "mode === directoryFilter.dataset.view", 'updateDirectoryTimeLabel', '{directory_day_hidden}', '{directory_month_hidden}', '{directory_year_hidden}', "\$view == 'year'") as $marker) {
    if (strpos($source, $marker) === false) throw new RuntimeException('Missing employee date or month filter marker: '.$marker);
}
foreach (array('.buffcorp-page:has(.gv-task-ui) { padding:14px 16px 24px; }', 'overflow-x:clip', 'min-height:44px', 'width:min(280px,calc(100vw - 28px))', '.gv-detail-actions { order:3; width:100%', 'scrollbar-width:none') as $marker) {
    if (strpos($source, $marker) === false) throw new RuntimeException('Missing mobile task UI marker: '.$marker);
}
foreach (array('function mosListNew', 'buffcorp-server-rendered', 'gv-status-form', 'gv-drag-ghost', 'dragCandidate', 'data-task-status', 'scrollbar-width:none', 'aria-label="Công việc chưa thực hiện"', 'name="member_id"', 'name="website_id"', 'name="soluong"', 'name="ngay"') as $marker) {
    if (strpos($source, $marker) === false) throw new RuntimeException('Missing task UI marker: '.$marker);
}
foreach (array('gv-employee-count', 'gvEmployeeCount', 'updateEmployeeCount', 'employeeView && !employeeView.hidden') as $marker) {
    if (strpos($source, $marker) === false) throw new RuntimeException('Missing employee counter marker: '.$marker);
}
foreach (array('function gvTaskIsAdministrator', 'function gvTaskHasTeamPermission', 'function gvTaskEnsureViewScopeTable', 'tbl_giaoviec_member_access', 'function gvTaskCanViewTeam', "code = 'giaoviec'", 'function gvTaskCanViewMember', 'function gvTaskTeamScopeSql', 'viewer_member_id', 'if ($member_id > 0 && !gvTaskCanViewMember($member_id))', '$accessCond = $member_id > 0 ?', 'if (!gvTaskCanViewMember((int)$row[\'member_id\']))') as $marker) {
    if (strpos($module, $marker) === false) throw new RuntimeException('Missing server-side employee-directory permission marker: '.$marker);
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
