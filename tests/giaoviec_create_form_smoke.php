<?php
$root = dirname(__DIR__);
$module = file_get_contents($root.'/bootrap/modules/common_lists/giaoviec.php');
chdir($root.'/bootrap');
session_id('giaoviec-create-form-smoke');
session_start();
$_SESSION['login_id'] = 1;
$_SESSION['membername'] = 'Administrator';
$_SESSION['loginname'] = 'administrator';
$_REQUEST = array('mode' => 'info', 'member_id' => 34, 'month' => '07', 'year' => '2026', 'day' => '2026-07-31', 'view' => 'day');
require 'common.php';
$skin = 'default';
$langpath = 'vietnam';
ob_start();
require 'modules/common_lists/giaoviec.php';
$html = ob_get_clean();
if (!preg_match('/name="id" value="0"/', $html)) throw new RuntimeException('New-task form must submit id=0.');
if (!preg_match('/name="member_id1" value="34"/', $html)) throw new RuntimeException('New-task form must retain the selected employee.');
if (!preg_match('/<option[^>]*value="34"[^>]*selected/', $html)) throw new RuntimeException('Selected employee must be rendered server-side.');
if (!preg_match('/name="ngay"[^>]*value="2026-07-31"/', $html)) throw new RuntimeException('New-task form must default to the viewed date.');
if (!preg_match('/<input type="hidden" name="soluong" value="0">/', $html) || !preg_match('/<input type="hidden" name="active" value="1">/', $html)) throw new RuntimeException('New-task form must submit the default status and visibility.');
if (preg_match('/<select[^>]+name="soluong"/', $html) || preg_match('/<input[^>]+name="active"[^>]+type="checkbox"/', $html)) throw new RuntimeException('Task status and visibility controls must not be shown in the form.');
if (!preg_match('/if \(\$giaoviec_id == \'0\'\) \{\s*\$soluong = 0;\s*\$active = 1;\s*\}/', $module)) throw new RuntimeException('New tasks must enforce the default status and visibility on save.');
if (strpos($html, '<div class="gv-field span-8"><label for="link_demo">Link hoàn thành</label>') === false) throw new RuntimeException('Completion link must share the KPI row in the compact form.');
if (strpos($html, '<div class="gv-form-inline-row">') === false || strpos($html, 'grid-template-columns:repeat(5,minmax(0,1fr))') === false) throw new RuntimeException('Task assignment and timing fields must share one desktop row.');
if (strpos($html, 'max-width:none; margin:0 auto;') === false) throw new RuntimeException('Task form must use the available content width.');
if (strpos($html, '@media (min-width:621px) { .buffcorp-page:has(.gv-form-ui) { padding:14px 16px 24px; } }') === false) throw new RuntimeException('Task form must use compact page gutters on desktop.');
foreach (array('class="gv-rich-editor"', 'src="js/ckeditor/ckeditor.js"', "CKEDITOR.replace('chitiet'", "height:112", "removePlugins:'elementspath,resize'", "detailEditor.updateElement()", "'Styles','Format','Font','FontSize'", "'Image','Table','HorizontalRule','SpecialChar','Smiley'") as $marker) {
    if (strpos($html, $marker) === false) throw new RuntimeException('Task detail editor is missing: '.$marker);
}
if (!preg_match("/toolbar:\[\s*\['Undo','Redo'/", $html)) throw new RuntimeException('Task detail editor must use the bundled CKEditor toolbar syntax.');
if (strpos($html, '.gv-form-ui .gv-field label,.gv-form-ui .gv-form-button,.gv-form-ui .gv-control') === false || strpos($html, '.gv-form-button.primary { border-color:var(--gv-brand); color:#fff !important;') === false || strpos($html, 'body.buffcorp-dark .gv-form-ui') === false) throw new RuntimeException('Task form controls and primary action need stable readable typography in both themes.');
echo "Task create form smoke OK\n";
