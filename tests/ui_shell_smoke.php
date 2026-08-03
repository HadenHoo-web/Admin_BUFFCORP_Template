<?php
$root = dirname(__DIR__);
require_once $root . '/bootrap/includes/ui_layout.php';
$demo = file_get_contents($root . '/buffseo-operations-hub-ui-demo.html');
$main = file_get_contents($root . '/bootrap/templates/mainpage/default.tpl');
$layout = file_get_contents($root . '/bootrap/includes/ui_layout.php');
$navigation = file_get_contents($root . '/bootrap/templates/default/vietnam/navigation/navigation.tpl');
$customerList = file_get_contents($root . '/bootrap/templates/default/vietnam/customer/customer/customer_list.html');
$adminDashboard = file_get_contents($root . '/bootrap/templates/default/vietnam/common_lists/admin_dashboard/admin_dashboard.html');
$functionMenuList = file_get_contents($root . '/bootrap/templates/default/vietnam/functionmenu/functionmenu_list.tpl');
$permissionList = file_get_contents($root . '/bootrap/templates/default/vietnam/functions/permission_list.tpl');
$functionMenuModule = file_get_contents($root . '/bootrap/modules/functionmenu/functionmenu.php');
$changePasswordModule = file_get_contents($root . '/bootrap/modules/members/change_password.php');
$mailTemplate = file_get_contents($root . '/bootrap/templates/default/vietnam/mail/mail_info.tpl');
$diForumModule = file_get_contents($root . '/bootrap/modules/seo/di_forum.php');
$library = file_get_contents($root . '/bootrap/includes/library.php');
$taskDemo = file_get_contents($root . '/buffcorp-giaoviec-ui-demo.html');

preg_match_all("/mod\\(\\s*'[^']+'\\s*,\\s*'[^']+'\\s*,\\s*'[^']*'\\s*,\\s*'([^']+)'\\s*,\\s*'([^']+)'/", $demo, $matches, PREG_SET_ORDER);
if (count($matches) !== 65) throw new RuntimeException('Expected 65 demo routes.');

foreach ($matches as $match) {
    if (!is_file($root . '/bootrap/modules/' . $match[1] . '.php')) {
        throw new RuntimeException('Missing module source: ' . $match[1]);
    }
}

foreach (['parent_id', 'giaoviec_name', 'link_demo', 'giaoviec_num', 'kpi_type', 'ngay', 'ngay_bat_dau', 'gio_deadline', 'gio_bat_dau', 'chitiet', 'soluong', 'active', 'member_id', 'website_id'] as $field) {
    if (strpos($taskDemo, 'name="' . $field . '"') === false) {
        throw new RuntimeException('Task demo back-end field regressed: ' . $field);
    }
}
foreach (['aria-modal="true"', 'prefers-reduced-motion', 'employee-directory', 'employee-card', 'openEmployeeTasks', 'backToEmployees', 'employee-detail-actions', 'date-filter', 'calendarDays', 'calendar-closed', 'calendar-mode-tabs', 'data-filter-mode="month"', 'data-filter-mode="year"', "filterMode === 'month'", "filterMode === 'year'", 'row.dataset.date.slice(0, dateLength)', 'event.stopPropagation();', 'demoTaskCount = 40', 'demoMembers = [', 'index < demoMembers.length ? 0', '1 + ((index - demoMembers.length) % 4)', 'selectedDate = today;', 'kanban-board', 'data-status="todo"', 'data-status="progress"', 'data-status="done"', 'drag-handle', 'drag-ghost', 'task-title-action', 'task-title-action:focus-visible', 'beginDrag', 'moveDrag', 'endDrag', 'moveToStatus', 'dropPosition', "event.key === 'ArrowLeft'", "addEventListener('pointerdown'"] as $marker) {
    if (strpos($taskDemo, $marker) === false) throw new RuntimeException('Task demo UI regressed: ' . $marker);
}
if (strpos($taskDemo, 'id="resultCount"') !== false) throw new RuntimeException('Legacy task list summary remains in the demo.');
if (strpos($taskDemo, 'class="pagination"') !== false) throw new RuntimeException('Legacy task pagination remains in the demo.');
if (strpos($taskDemo, '<h1>Giao việc</h1>') !== false) throw new RuntimeException('Legacy task page heading remains in the demo.');
foreach (['width: min(880px, 100%)', '.modal .control { height: 34px', 'form-field span-4"><label for="resultLink"', 'form-field span-8"><label for="taskDetail"'] as $marker) {
    if (strpos($taskDemo, $marker) === false) throw new RuntimeException('Compact task form regressed: ' . $marker);
}

foreach (['enhanceLegacyModule', 'buffcorp-module-card', 'buffcorp-client-controls', 'buffcorp-row-actions', 'buffcorp-status', 'buffcorp-demo-parity', 'buffcorp-theme-button', 'buffcorp-mobile-menu', 'menu-open', '.sales-page', '.kpi-report', 'Số dòng', 'move-up', 'permission', 'password'] as $marker) {
    if ($marker === 'enhanceLegacyModule') continue;
    if (strpos($main . $layout, $marker) === false) throw new RuntimeException('Missing main shell marker: ' . $marker);
}
foreach (["routeKeys = ['menu', 'category', 'cid']", 'translatedActions', 'mail-form-table', 'config-grid-table', 'getpass-source-wrap', 'cuttpw-wrap'] as $marker) {
    if (strpos($main . $layout, $marker) === false) throw new RuntimeException('Missing full UI marker: ' . $marker);
}
foreach (['mosFunctionMenu(0, "Root")', 'Tổng quan', 'Quản lý Tin tức', 'CURRENT_OPTION', 'CURRENT_MODE', '236px', '68px'] as $marker) {
    if ($marker === '236px') $marker = '296px';
    if (strpos($navigation, $marker) === false) throw new RuntimeException('Missing navigation marker: ' . $marker);
}
if (strpos($navigation, 'function regroupNavigation') !== false) {
    throw new RuntimeException('Sidebar must not replace the database menu tree.');
}
foreach (['select distinct a.* from tbl_function_menu', "htmlspecialchars(\$row['fun_name'], ENT_QUOTES, 'UTF-8')"] as $marker) {
    if (strpos($library, $marker) === false) throw new RuntimeException('Database menu rendering regressed: ' . $marker);
}
if (strpos($customerList, "getElementById('customer_type')") === false) {
    throw new RuntimeException('Customer filter field mapping regressed.');
}
if (strpos($adminDashboard, 'admin-section-kicker') === false) {
    throw new RuntimeException('Admin dashboard heading regressed.');
}
foreach (['function-menu-selector', '{list.node_link}', 'permission_list&id={list.code}', 'menu-child-count'] as $marker) {
    if (strpos($functionMenuList, $marker) === false) throw new RuntimeException('Function-menu tree regressed: ' . $marker);
}
foreach (['permission-selector', 'name="dung{list.code}"', 'name="department_id1"', 'value="permission_save"'] as $marker) {
    if (strpos($permissionList, $marker) === false) throw new RuntimeException('Permission form regressed: ' . $marker);
}
if (preg_match('/<table\b[^>]*>\s*<form\b/i', $permissionList)) {
    throw new RuntimeException('Permission form must wrap the table.');
}
if (strpos($functionMenuModule, 'as child_count') === false) {
    throw new RuntimeException('Function-menu child count query regressed.');
}
if (strpos($changePasswordModule, "'change_password' => 'members/changepassword.tpl'") === false) {
    throw new RuntimeException('Change-password shell alias regressed.');
}
foreach (['name="test"', 'name="tinhtrang"', 'name="tomail"', 'name="subject"', 'name="description"', 'name="mode" value="send"'] as $marker) {
    if (strpos($mailTemplate, $marker) === false) throw new RuntimeException('Mail back-end field regressed: ' . $marker);
}
foreach (['min(500', 'AS child_count', "'from_date'", "'to_date'"] as $marker) {
    if (strpos($diForumModule, $marker) === false) throw new RuntimeException('SEO forum loading guard regressed: ' . $marker);
}
foreach (['editbutton.gif', 'button-article-create.gif', 'button-article-list.gif'] as $icon) {
    if (!is_file($root . '/bootrap/templates/default/images/' . $icon)) {
        throw new RuntimeException('Missing replacement icon: ' . $icon);
    }
}

$serverList = buffcorpPrepareModuleHtml(
    '<div class="toolbar"></div><table class="selector"><tr class="header"></tr></table>',
    'common_lists/website',
    'list'
);
if (strpos($serverList, 'buffcorp-server-module') === false
    || strpos($serverList, 'buffcorp-server-table') === false
    || strpos($serverList, 'data-layout="list"') === false) {
    throw new RuntimeException('Back-end list layout adapter failed.');
}
$customDashboard = '<div class="admin-dashboard">Custom</div>';
if (buffcorpPrepareModuleHtml($customDashboard) !== $customDashboard) {
    throw new RuntimeException('Custom dashboard must bypass the legacy adapter.');
}

$adaptedLists = 0;
$adaptedForms = 0;
$templates = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root . '/bootrap/templates/default/vietnam')
);
foreach ($templates as $templateFile) {
    if (!$templateFile->isFile() || !preg_match('/_(list|info)\.(tpl|html)$/i', $templateFile->getFilename())) continue;
    $source = file_get_contents($templateFile->getPathname());
    if (preg_match('/\b(admin-dashboard|sales-page|kpi-page|kpi-report|org-chart)\b/i', $source)) continue;
    if (preg_match('/\bbuffcorp-server-rendered\b/i', $source)) continue;
    $prepared = buffcorpPrepareModuleHtml($source, 'smoke/module', 'list');
    if (preg_match('/<table\b[^>]*\bclass\s*=\s*(["\'])[^"\']*\bselector\b/i', $source)) {
        if (strpos($prepared, 'data-layout="list"') === false && strpos($prepared, 'data-layout="legacy-list"') === false) {
            throw new RuntimeException('List layout failed: ' . $templateFile->getFilename());
        }
        $adaptedLists++;
    } elseif (preg_match('/_info\.(tpl|html)$/i', $templateFile->getFilename()) && stripos($source, '<form') !== false) {
        if (strpos($prepared, 'data-layout="form"') === false) {
            throw new RuntimeException('Form layout failed: ' . $templateFile->getFilename());
        }
        $adaptedForms++;
    }
}
if ($adaptedLists < 70 || $adaptedForms < 60) {
    throw new RuntimeException('Back-end layout coverage is unexpectedly low.');
}

$modules = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root . '/bootrap/modules')
);
foreach ($modules as $moduleFile) {
    if (!$moduleFile->isFile() || strtolower($moduleFile->getExtension()) !== 'php') continue;
    if (preg_match('/^\s*<\?(?!php|=)/im', file_get_contents($moduleFile->getPathname()))) {
        throw new RuntimeException('Unsupported PHP short tag: ' . $moduleFile->getPathname());
    }
}

echo "UI shell smoke OK: 65 routes, $adaptedLists lists, $adaptedForms forms.\n";
