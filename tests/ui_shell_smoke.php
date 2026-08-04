<?php
$root = dirname(__DIR__);
require_once $root . '/bootrap/includes/ui_layout.php';
$main = file_get_contents($root . '/bootrap/templates/mainpage/default.tpl');
$mainpage = file_get_contents($root . '/bootrap/mainpage.php');
$layout = file_get_contents($root . '/bootrap/includes/ui_layout.php');
$navigation = file_get_contents($root . '/bootrap/templates/default/vietnam/navigation/navigation.tpl');
$library = file_get_contents($root . '/bootrap/includes/library.php');

foreach (['initializeModernLists', 'buffcorp-module-card', 'buffcorp-mobile-menu', 'menu-open', '.sales-page', '.kpi-report', 'setNotifyOpen', 'aria-controls="notify-panel"', 'aria-controls="payroll-panel"', 'closeTopPanels'] as $marker) {
    if (strpos($main . $layout, $marker) === false) throw new RuntimeException('Missing main shell marker: ' . $marker);
}
if (strpos($main, 'buffcorp-theme-button') !== false || strpos($main, 'buffcorp-theme-wrap') !== false) {
    throw new RuntimeException('Theme toggle must stay removed to match main.');
}
if (preg_match('/\.payroll-wrap\.open ~ \.notify-wrap\s*\{[^}]*pointer-events\s*:\s*none/s', $main)) {
    throw new RuntimeException('Notification button must remain clickable while payroll is open.');
}
if (preg_match('/suppressNotifyFlash|payroll-popup-settling|notify-suppress-flash/', $main)) {
    throw new RuntimeException('Closing payroll must not apply a temporary notification layer.');
}
if (!preg_match('/\.buffcorp-top-actions \.notify-bell\s*\{[^}]*width\s*:\s*38px/s', $main)) {
    throw new RuntimeException('Notification bell must share the top action button styling.');
}
if (!preg_match('/\.notify-wrap\.open \.notify-bell\s*\{[^}]*z-index\s*:\s*10031/s', $main)) {
    throw new RuntimeException('Notification bell must remain clickable above its open panel.');
}
if (preg_match('/\.notify-wrap\s*\{[^}]*z-index\s*:\s*9999/s', $main)) {
    throw new RuntimeException('Notification wrapper must not sit above normal UI.');
}

foreach (['buffcorp-user-menu', 'buffcorp-user-toggle', 'buffcorp-user-dropdown', 'initUserMenu', 'USER_PROFILE_URL', 'USER_ACCOUNT_URL', 'logout.php'] as $marker) {
    if (strpos($main . $mainpage, $marker) === false) throw new RuntimeException('Missing account menu marker: ' . $marker);
}
if (strpos($navigation, 'function regroupNavigation') !== false) {
    throw new RuntimeException('Sidebar must not replace the database menu tree.');
}
foreach (['mosFunctionMenu(0, "Root")', 'CURRENT_OPTION', 'CURRENT_MODE', '296px'] as $marker) {
    if (strpos($navigation, $marker) === false) throw new RuntimeException('Missing navigation marker: ' . $marker);
}
foreach (['select distinct a.* from tbl_function_menu', "htmlspecialchars(\$label, ENT_QUOTES, 'UTF-8')"] as $marker) {
    if (strpos($library, $marker) === false) throw new RuntimeException('Database menu rendering regressed: ' . $marker);
}

$serverList = buffcorpPrepareModuleHtml(
    '<div class="toolbar"></div><table class="selector"><tr class="header"></tr></table>',
    'common_lists/website',
    'list'
);
if (strpos($serverList, 'buffcorp-server-rendered') === false || strpos($serverList, 'data-layout="list"') === false) {
    throw new RuntimeException('Back-end list layout adapter failed.');
}

$adaptedLists = 0;
$adaptedForms = 0;
$templates = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/bootrap/templates/default/vietnam'));
foreach ($templates as $templateFile) {
    if (!$templateFile->isFile() || !preg_match('/_(list|info)\.(tpl|html)$/i', $templateFile->getFilename())) continue;
    $source = file_get_contents($templateFile->getPathname());
    if (preg_match('/\b(admin-dashboard|sales-page|kpi-page|kpi-report|org-chart|org-edit|member-edit|leave-report-modern|bc-chat)\b/i', $source) || preg_match('/\bbuffcorp-server-rendered\b/i', $source)) continue;
    $prepared = buffcorpPrepareModuleHtml($source, 'smoke/module', 'list');
    if (preg_match('/<table\b[^>]*\bclass\s*=\s*(["\'])[^"\']*\bselector\b/i', $source)) {
        if (strpos($prepared, 'data-layout="list"') === false && strpos($prepared, 'data-layout="legacy-list"') === false) throw new RuntimeException('List layout failed: ' . $templateFile->getFilename());
        $adaptedLists++;
    } elseif (preg_match('/_info\.(tpl|html)$/i', $templateFile->getFilename()) && stripos($source, '<form') !== false) {
        if (strpos($prepared, 'data-layout="form"') === false) throw new RuntimeException('Form layout failed: ' . $templateFile->getFilename());
        $adaptedForms++;
    }
}
if ($adaptedLists < 70 || $adaptedForms < 60) throw new RuntimeException('Back-end layout coverage is unexpectedly low.');

$modules = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/bootrap/modules'));
foreach ($modules as $moduleFile) {
    if (!$moduleFile->isFile() || strtolower($moduleFile->getExtension()) !== 'php') continue;
    if (preg_match('/^\s*<\?(?!php|=)/im', file_get_contents($moduleFile->getPathname()))) throw new RuntimeException('Unsupported PHP short tag: ' . $moduleFile->getPathname());
}

echo "UI shell smoke OK: $adaptedLists lists, $adaptedForms forms.\n";
