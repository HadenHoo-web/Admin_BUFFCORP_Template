<?php
$root = dirname(__DIR__);
require_once $root . '/bootrap/includes/ui_layout.php';
$demo = file_get_contents($root . '/buffseo-operations-hub-ui-demo.html');
$main = file_get_contents($root . '/bootrap/templates/mainpage/default.tpl');
$navigation = file_get_contents($root . '/bootrap/templates/default/vietnam/navigation/navigation.tpl');
$customerList = file_get_contents($root . '/bootrap/templates/default/vietnam/customer/customer/customer_list.html');

preg_match_all("/mod\\(\\s*'[^']+'\\s*,\\s*'[^']+'\\s*,\\s*'[^']*'\\s*,\\s*'([^']+)'\\s*,\\s*'([^']+)'/", $demo, $matches, PREG_SET_ORDER);
if (count($matches) !== 65) throw new RuntimeException('Expected 65 demo routes.');

foreach ($matches as $match) {
    if (!is_file($root . '/bootrap/modules/' . $match[1] . '.php')) {
        throw new RuntimeException('Missing module source: ' . $match[1]);
    }
}

foreach (['enhanceLegacyModule', 'buffcorp-module-card', 'buffcorp-client-controls', 'buffcorp-row-actions', 'buffcorp-status', 'Số dòng', 'move-up', 'permission', 'password'] as $marker) {
    if (strpos($main, $marker) === false) throw new RuntimeException('Missing main shell marker: ' . $marker);
}
foreach (['regroupNavigation', 'Tổng quan', 'Khách hàng & dịch vụ', 'Hệ thống', 'CURRENT_OPTION', 'CURRENT_MODE', '268px'] as $marker) {
    if (strpos($navigation, $marker) === false) throw new RuntimeException('Missing navigation marker: ' . $marker);
}
if (strpos($customerList, "getElementById('customer_type')") === false) {
    throw new RuntimeException('Customer filter field mapping regressed.');
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
    $prepared = buffcorpPrepareModuleHtml($source, 'smoke/module', 'list');
    if (preg_match('/<table\b[^>]*\bclass\s*=\s*(["\'])[^"\']*\bselector\b/i', $source)) {
        if (strpos($prepared, 'data-layout="list"') === false) {
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

echo "UI shell smoke OK: 65 routes, $adaptedLists lists, $adaptedForms forms.\n";
