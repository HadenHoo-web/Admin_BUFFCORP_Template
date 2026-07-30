<?php
global $languageid, $template, $root_path, $db;
require_once dirname(__FILE__).'/../../includes/notifications.php';

$action = mosGetParam($_REQUEST, 'mode', 'list');
if (!isset($template)) $template = new Template();

$template->assign_vars(array(
    'ROOT' => $root_path,
    'funname' => 'common_lists/thongtinhethong',
    'LANGUAGEID' => $languageid,
));

switch ($action) {
    case 'view':
        mosThongTinHeThongView();
        break;
    case 'info':
        mosThongTinHeThongInfo();
        break;
    case 'save':
        mosThongTinHeThongSave();
        break;
    case 'delete':
        mosThongTinHeThongDelete();
        break;
    case 'download':
        mosThongTinHeThongDownload();
        break;
    case 'list':
    default:
        mosThongTinHeThongList();
        break;
}

function mosTtHtSql($value)
{
    return addslashes($value);
}

function mosTtHtHtml($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function mosTtHtRequestHtml($name)
{
    if (!isset($_REQUEST[$name]) || is_array($_REQUEST[$name])) return '';
    $value = (string)$_REQUEST[$name];
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    return trim($value);
}

function mosTtHtRequestText($name)
{
    if (!isset($_REQUEST[$name]) || is_array($_REQUEST[$name])) return '';
    $value = (string)$_REQUEST[$name];
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    return trim(strip_tags($value));
}

function mosTtHtCleanStoredHtml($value)
{
    $value = (string)$value;
    for ($i = 0; $i < 3; $i++) {
        $cleaned = str_replace(array('\\&quot;', '\\&#039;', '\\&apos;'), array('&quot;', '&#039;', '&apos;'), $value);
        if (preg_match('/\\\\+[\'"]/', $cleaned)) {
            $cleaned = stripcslashes($cleaned);
        }
        if ($cleaned === $value) break;
        $value = $cleaned;
    }
    return $value;
}

function mosTtHtIsAdmin()
{
    return (isset($_SESSION['loginname']) && $_SESSION['loginname'] == 'administrator')
        || intval(isset($_SESSION['login_id']) ? $_SESSION['login_id'] : 0) == 71;
}

function mosTtHtLoginId()
{
    return intval(isset($_SESSION['login_id']) ? $_SESSION['login_id'] : 0);
}

function mosTtHtCategoryValue($value)
{
    $text = trim(mb_strtolower((string)$value, 'UTF-8'));
    if ($text == 'quy trình' || $text == 'quy trinh') return 2;
    if ($text == 'quy định' || $text == 'quy dinh') return 3;
    if ($text == 'biểu mẫu' || $text == 'bieu mau') return 4;
    if ($text == 'chính sách' || $text == 'chinh sach') return 1;

    $value = intval($value);
    if ($value < 1 || $value > 4) return 1;
    return $value;
}

function mosTtHtCategoryName($value)
{
    $value = mosTtHtCategoryValue($value);
    if ($value == 2) return 'Quy trình';
    if ($value == 3) return 'Quy định';
    if ($value == 4) return 'Biểu mẫu';
    return 'Chính sách';
}

function mosTtHtHasStampOptions($category)
{
    $category = mosTtHtCategoryValue($category);
    return in_array($category, array(1, 2, 3, 4));
}

function mosTtHtCategorySqlValues($category)
{
    $category = mosTtHtCategoryValue($category);
    if ($category == 2) return "'2','Quy trình','Quy trinh'";
    if ($category == 3) return "'3','Quy định','Quy dinh'";
    if ($category == 4) return "'4','Biểu mẫu','Bieu mau'";
    return "'1','Chính sách','Chinh sach'";
}

function mosTtHtNotificationTitle($category)
{
    $category = mosTtHtCategoryValue($category);
    if ($category == 2) return 'Quy trình vừa được cập nhật';
    if ($category == 3) return 'Quy định vừa được cập nhật';
    if ($category == 4) return 'Biểu mẫu vừa được cập nhật';
    return 'Chính sách vừa được cập nhật';
}

function mosTtHtStampHtml()
{
    return '<div class="doc-stamp-wrap" data-doc-stamp="1" style="text-align:right;margin-top:28px;padding-right:200px;"><img src="templates/default/images/moc_do.png" alt="Mộc công ty" style="width:238px;height:150px;"></div>';
}

function mosTtHtDateValue($value)
{
    $value = trim((string)$value);
    if ($value == '') return '';
    if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $value)) return $value;
    return '';
}

function mosTtHtIsExpired($dateValue)
{
    $dateValue = mosTtHtDateValue($dateValue);
    if ($dateValue == '') return false;
    return ($dateValue < date('Y-m-d'));
}

function mosTtHtUploadDir()
{
    return dirname(__FILE__) . '/../../uploads/thongtinhethong/';
}

function mosTtHtDocxParagraphText($paragraphXml)
{
    $parts = array();
    if (preg_match_all('/<w:t\b[^>]*>(.*?)<\/w:t>|<w:tab\b[^\/]*\/>|<w:br\b[^\/]*\/>|<w:cr\b[^\/]*\/>/s', $paragraphXml, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            if (isset($match[1]) && $match[1] !== '') {
                $parts[] = html_entity_decode($match[1], ENT_QUOTES, 'UTF-8');
            } else if (strpos($match[0], '<w:tab') === 0) {
                $parts[] = '    ';
            } else {
                $parts[] = "\n";
            }
        }
    }
    return trim(implode('', $parts));
}

function mosTtHtDocxParagraphTag($paragraphXml)
{
    if (preg_match('/<w:pStyle\b[^>]*w:val="([^"]+)"/i', $paragraphXml, $match)) {
        $style = strtolower($match[1]);
        if (strpos($style, 'title') !== false || strpos($style, 'heading1') !== false) return 'h1';
        if (strpos($style, 'heading2') !== false) return 'h2';
        if (strpos($style, 'heading3') !== false) return 'h3';
    }
    return preg_match('/<w:numPr\b/i', $paragraphXml) ? 'li' : 'p';
}

function mosTtHtDocxToHtml($relativePath)
{
    if (!class_exists('ZipArchive')) return '';
    $relativePath = trim($relativePath);
    if ($relativePath == '' || strtolower(pathinfo($relativePath, PATHINFO_EXTENSION)) != 'docx') return '';

    $filePath = dirname(__FILE__) . '/../../' . $relativePath;
    if (!is_file($filePath)) return '';

    $zip = new ZipArchive();
    if ($zip->open($filePath) !== true) return '';
    $documentXml = $zip->getFromName('word/document.xml');
    $zip->close();
    if (!$documentXml) return '';

    if (!preg_match_all('/<w:p\b[^>]*>(.*?)<\/w:p>/s', $documentXml, $paragraphs)) return '';

    $html = array();
    $listOpen = false;
    foreach ($paragraphs[1] as $paragraphXml) {
        $text = mosTtHtDocxParagraphText($paragraphXml);
        if ($text == '') continue;

        $tag = mosTtHtDocxParagraphTag($paragraphXml);
        $safeText = nl2br(mosTtHtHtml($text));
        if ($tag == 'li') {
            if (!$listOpen) {
                $html[] = '<ul>';
                $listOpen = true;
            }
            $html[] = '<li>'.$safeText.'</li>';
            continue;
        }

        if ($listOpen) {
            $html[] = '</ul>';
            $listOpen = false;
        }
        $html[] = '<'.$tag.'>'.$safeText.'</'.$tag.'>';
    }

    if ($listOpen) $html[] = '</ul>';
    return implode("\n", $html);
}

function mosTtHtDocxToPlainText($relativePath)
{
    if (!class_exists('ZipArchive')) return '';
    $relativePath = trim($relativePath);
    if ($relativePath == '' || strtolower(pathinfo($relativePath, PATHINFO_EXTENSION)) != 'docx') return '';

    $filePath = dirname(__FILE__) . '/../../' . $relativePath;
    if (!is_file($filePath)) return '';

    $zip = new ZipArchive();
    if ($zip->open($filePath) !== true) return '';
    $documentXml = $zip->getFromName('word/document.xml');
    $zip->close();
    if (!$documentXml) return '';

    if (!preg_match_all('/<w:p\b[^>]*>(.*?)<\/w:p>/s', $documentXml, $paragraphs)) return '';

    $lines = array();
    foreach ($paragraphs[1] as $paragraphXml) {
        $text = mosTtHtDocxParagraphText($paragraphXml);
        if ($text == '') continue;
        if (preg_match('/<w:numPr\b/i', $paragraphXml) && !preg_match('/^\s*[-+•]/u', $text)) {
            $text = '- '.$text;
        }
        $lines[] = $text;
    }
    return implode("\n", $lines);
}

function mosTtHtDocxTableCellText($cellXml)
{
    $lines = array();
    if (preg_match_all('/<w:p\b[^>]*>(.*?)<\/w:p>/s', $cellXml, $paragraphs)) {
        foreach ($paragraphs[1] as $paragraphXml) {
            $text = mosTtHtDocxParagraphText($paragraphXml);
            if ($text != '') $lines[] = $text;
        }
    }
    return implode("\n", $lines);
}

function mosTtHtDocxTableToHtml($tableXml)
{
    if (!preg_match_all('/<w:tr\b[^>]*>(.*?)<\/w:tr>/s', $tableXml, $rows)) return '';

    $html = array();
    $html[] = '<table class="doc-standard-table">';
    $rowIndex = 0;
    foreach ($rows[1] as $rowXml) {
        if (!preg_match_all('/<w:tc\b[^>]*>(.*?)<\/w:tc>/s', $rowXml, $cells)) continue;

        $html[] = '<tr>';
        foreach ($cells[1] as $cellXml) {
            $text = mosTtHtDocxTableCellText($cellXml);
            $cellHtml = mosTtHtHtml($text);
            $cellHtml = str_replace("\n", '<br>', $cellHtml);
            $tag = ($rowIndex == 0) ? 'th' : 'td';
            $html[] = '<'.$tag.'>'.$cellHtml.'</'.$tag.'>';
        }
        $html[] = '</tr>';
        $rowIndex++;
    }
    $html[] = '</table>';

    return implode("\n", $html);
}

function mosTtHtDocxToStandardHtml($relativePath)
{
    if (!class_exists('ZipArchive')) return '';
    $relativePath = trim($relativePath);
    if ($relativePath == '' || strtolower(pathinfo($relativePath, PATHINFO_EXTENSION)) != 'docx') return '';

    $filePath = dirname(__FILE__) . '/../../' . $relativePath;
    if (!is_file($filePath)) return '';

    $zip = new ZipArchive();
    if ($zip->open($filePath) !== true) return '';
    $documentXml = $zip->getFromName('word/document.xml');
    $zip->close();
    if (!$documentXml) return '';

    if (!preg_match('/<w:body\b[^>]*>(.*?)<\/w:body>/s', $documentXml, $bodyMatch)) return '';
    $bodyXml = $bodyMatch[1];
    if (!preg_match_all('/<w:(p|tbl)\b[^>]*>.*?<\/w:\1>/s', $bodyXml, $blocks)) return '';

    $html = array();
    $listOpen = false;
    foreach ($blocks[0] as $blockXml) {
        if (strpos($blockXml, '<w:tbl') === 0) {
            if ($listOpen) {
                $html[] = '</ul>';
                $listOpen = false;
            }
            $tableHtml = mosTtHtDocxTableToHtml($blockXml);
            if ($tableHtml != '') $html[] = $tableHtml;
            continue;
        }

        $text = mosTtHtDocxParagraphText($blockXml);
        if ($text == '') {
            if ($listOpen) {
                $html[] = '</ul>';
                $listOpen = false;
            }
            continue;
        }

        if (preg_match('/<w:numPr\b/i', $blockXml) && !preg_match('/^\s*[-+•]/u', $text)) {
            $text = '- '.$text;
        }

        $part = mosTtHtStandardLineHtml($text);
        if (strpos($part, '<li>') === 0) {
            if (!$listOpen) {
                $html[] = '<ul>';
                $listOpen = true;
            }
            $html[] = $part;
            continue;
        }

        if ($listOpen) {
            $html[] = '</ul>';
            $listOpen = false;
        }
        if ($part != '') $html[] = $part;
    }

    if ($listOpen) $html[] = '</ul>';
    return implode("\n", $html);
}

function mosTtHtStandardLineHtml($text)
{
    $line = trim(preg_replace('/\s+/u', ' ', str_replace("\xc2\xa0", ' ', $text)));
    if ($line == '') return '';

    $escaped = mosTtHtHtml($line);
    $upper = function_exists('mb_strtoupper') ? mb_strtoupper($line, 'UTF-8') : strtoupper($line);

    if (preg_match('/^\s*[-+•]\s+/u', $line)) {
        return '<li>'.mosTtHtHtml(preg_replace('/^\s*[-+•]\s+/u', '', $line)).'</li>';
    }
    if (preg_match('/^(NỘI QUY|CHÍNH SÁCH|QUY TRÌNH|QUY ĐỊNH|BIỂU MẪU|ĐƠN XIN|THỎA THUẬN|TIÊU CHÍ)/u', $upper)) {
        return '<h1>'.$escaped.'</h1>';
    }
    if (preg_match('/^CHƯƠNG\s+[IVX0-9]+/iu', $line)) {
        return '<h2>'.$escaped.'</h2>';
    }
    if (preg_match('/^ĐIỀU\s+[0-9]+/iu', $line)) {
        return '<h3>'.$escaped.'</h3>';
    }
    if (preg_match('/^(CỘNG HÒA|ĐỘC LẬP|[-–—]*O0O[-–—]*|TP\.?HCM|TP\. HCM|CÔNG TY TNHH BUFF CORP)/u', $upper)) {
        return '<p class="doc-centered">'.$escaped.'</p>';
    }
    if (preg_match('/^(Tên Công ty|Trụ sở chính|Website|Kính gửi|Bên A|Bên B|MSDN|MST|Người đại diện|Chức vụ|Địa chỉ|Điện thoại)\s*[:：]/iu', $line)) {
        return '<p class="doc-lead">'.$escaped.'</p>';
    }
    if (preg_match('/^(Căn cứ|Các văn bản|Dưới đây gọi là|\(|\[)/iu', $line)) {
        return '<p class="doc-note">'.$escaped.'</p>';
    }
    return '<p>'.$escaped.'</p>';
}

function mosTtHtStandardHtmlFromText($text)
{
    $text = str_replace("\r", '', (string)$text);
    $lines = explode("\n", $text);
    $html = array();
    $listOpen = false;

    foreach ($lines as $line) {
        $part = mosTtHtStandardLineHtml($line);
        if ($part == '') {
            if ($listOpen) {
                $html[] = '</ul>';
                $listOpen = false;
            }
            continue;
        }

        if (strpos($part, '<li>') === 0) {
            if (!$listOpen) {
                $html[] = '<ul>';
                $listOpen = true;
            }
            $html[] = $part;
            continue;
        }

        if ($listOpen) {
            $html[] = '</ul>';
            $listOpen = false;
        }
        $html[] = $part;
    }

    if ($listOpen) $html[] = '</ul>';
    return implode("\n", $html);
}

function mosTtHtWordToStandardHtml($relativePath)
{
    $html = mosTtHtDocxToStandardHtml($relativePath);
    if (trim($html) != '') return $html;

    $text = mosTtHtDocxToPlainText($relativePath);
    if (trim($text) == '') return '';
    return mosTtHtStandardHtmlFromText($text);
}

function mosTtHtCommandAvailable($functionName)
{
    if (!function_exists($functionName)) return false;
    $disabled = ini_get('disable_functions');
    if ($disabled == '') return true;
    $disabled = array_map('trim', explode(',', strtolower($disabled)));
    return !in_array(strtolower($functionName), $disabled);
}

function mosTtHtFindLibreOffice()
{
    $paths = array(
        '/usr/bin/libreoffice',
        '/usr/local/bin/libreoffice',
        '/opt/libreoffice/program/soffice',
        '/usr/bin/soffice',
        '/usr/local/bin/soffice',
        '/Applications/LibreOffice.app/Contents/MacOS/soffice'
    );
    foreach ($paths as $path) {
        if (is_executable($path)) return $path;
    }

    if (!mosTtHtCommandAvailable('exec')) return '';
    $output = array();
    @exec('command -v libreoffice 2>/dev/null', $output);
    if (isset($output[0]) && trim($output[0]) != '') return trim($output[0]);

    $output = array();
    @exec('command -v soffice 2>/dev/null', $output);
    if (isset($output[0]) && trim($output[0]) != '') return trim($output[0]);

    return '';
}

function mosTtHtRewriteWordHtmlAssets($html, $assetBase)
{
    if (!preg_match_all('/\s(src|href)=([\'"])([^\'"]+)\2/i', $html, $matches, PREG_SET_ORDER)) return $html;
    foreach ($matches as $match) {
        $url = trim($match[3]);
        if ($url == '' || preg_match('/^(https?:|data:|mailto:|#|\/)/i', $url)) continue;
        $newUrl = $assetBase . '/' . str_replace('%2F', '/', rawurlencode($url));
        $html = str_replace($match[0], ' '.$match[1].'='.$match[2].$newUrl.$match[2], $html);
    }
    return $html;
}

function mosTtHtExtractWordHtmlContent($html, $assetBase)
{
    $styles = '';
    if (preg_match_all('/<style\b[^>]*>.*?<\/style>/is', $html, $matches)) {
        $styles = implode("\n", $matches[0]);
    }
    if (preg_match('/<body\b[^>]*>(.*?)<\/body>/is', $html, $match)) {
        $html = $match[1];
    }
    $html = mosTtHtRewriteWordHtmlAssets($html, $assetBase);
    return trim($styles."\n".$html);
}

function mosTtHtLibreOfficeToHtml($relativePath)
{
    if (!mosTtHtCommandAvailable('exec')) return '';

    $relativePath = trim($relativePath);
    $ext = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));
    if ($relativePath == '' || !in_array($ext, array('doc', 'docx'))) return '';

    $filePath = dirname(__FILE__) . '/../../' . $relativePath;
    if (!is_file($filePath)) return '';

    $bin = mosTtHtFindLibreOffice();
    if ($bin == '') return '';

    $folderName = 'word_html/import_' . date('YmdHis') . '_' . mt_rand(1000, 9999);
    $outputDir = mosTtHtUploadDir() . $folderName;
    if (!is_dir($outputDir)) @mkdir($outputDir, 0777, true);
    if (!is_dir($outputDir)) return '';

    $command = escapeshellarg($bin) . ' --headless --convert-to html --outdir ' . escapeshellarg($outputDir) . ' ' . escapeshellarg($filePath) . ' 2>&1';
    $output = array();
    $returnCode = 1;
    @exec($command, $output, $returnCode);
    if ($returnCode !== 0) return '';

    $htmlFile = '';
    $files = scandir($outputDir);
    if (!is_array($files)) return '';
    foreach ($files as $file) {
        if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) == 'html') {
            $htmlFile = $outputDir . '/' . $file;
            break;
        }
    }
    if ($htmlFile == '' || !is_file($htmlFile)) return '';

    $html = file_get_contents($htmlFile);
    if ($html === false || trim($html) == '') return '';

    return mosTtHtExtractWordHtmlContent($html, 'uploads/thongtinhethong/' . $folderName);
}

function mosTtHtWordToHtml($relativePath)
{
    $html = mosTtHtLibreOfficeToHtml($relativePath);
    if ($html != '') return $html;
    return mosTtHtDocxToHtml($relativePath);
}

function mosTtHtContentIsBlank($content)
{
    $content = preg_replace('/&nbsp;|\s+/i', '', strip_tags((string)$content));
    return $content == '';
}

function mosTtHtTableReady()
{
    global $db;
    $sql = "
        CREATE TABLE IF NOT EXISTS tbl_system_information (
            info_id int(11) NOT NULL AUTO_INCREMENT,
            info_title varchar(255) NOT NULL DEFAULT '',
            info_category varchar(180) NOT NULL DEFAULT '',
            content_html text,
            file_name varchar(255) NOT NULL DEFAULT '',
            file_path varchar(255) NOT NULL DEFAULT '',
            content_imported_from_file tinyint(1) NOT NULL DEFAULT 0,
            dong_moc tinyint(1) NOT NULL DEFAULT 0,
            hieu_luc_den date DEFAULT NULL,
            editable_member_ids varchar(255) NOT NULL DEFAULT '',
            share_user_ids varchar(255) NOT NULL DEFAULT '',
            active tinyint(1) NOT NULL DEFAULT 1,
            priority int(11) NOT NULL DEFAULT 0,
            language_id int(11) NOT NULL DEFAULT 2,
            created_member_id int(11) NOT NULL DEFAULT 0,
            created_by varchar(120) NOT NULL DEFAULT '',
            created_date datetime DEFAULT NULL,
            modified_by varchar(120) NOT NULL DEFAULT '',
            last_modified datetime DEFAULT NULL,
            PRIMARY KEY (info_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    if (!$db->sql_query($sql)) return false;

    mosTtHtEnsureColumn('dong_moc', "ALTER TABLE tbl_system_information ADD COLUMN dong_moc tinyint(1) NOT NULL DEFAULT 0");
    mosTtHtEnsureColumn('hieu_luc_den', "ALTER TABLE tbl_system_information ADD COLUMN hieu_luc_den date DEFAULT NULL");
    mosTtHtEnsureColumn('editable_member_ids', "ALTER TABLE tbl_system_information ADD COLUMN editable_member_ids varchar(255) NOT NULL DEFAULT ''");
    mosTtHtEnsureColumn('content_imported_from_file', "ALTER TABLE tbl_system_information ADD COLUMN content_imported_from_file tinyint(1) NOT NULL DEFAULT 0");
    $db->sql_query("ALTER TABLE tbl_system_information MODIFY content_html LONGTEXT");

    return true;
}

function mosTtHtEnsureColumn($column, $alterSql)
{
    global $db;
    $column = mosTtHtSql($column);
    if (!($result = $db->sql_query("show columns from tbl_system_information like '".$column."'"))) return false;
    if ($db->sql_fetchrow($result)) return true;
    return $db->sql_query($alterSql) ? true : false;
}

function mosTtHtCanEdit($row)
{
    if (mosTtHtIsAdmin()) return true;
    $loginId = mosTtHtLoginId();
    return (strpos(',' . str_replace(' ', '', $row['editable_member_ids']) . ',', ',' . $loginId . ',') !== false);
}

function mosTtHtCanView($row)
{
    if (mosTtHtIsAdmin()) return true;
    $loginId = mosTtHtLoginId();
    if (intval($row['created_member_id']) == $loginId) return true;
    if (strpos(',' . str_replace(' ', '', $row['editable_member_ids']) . ',', ',' . $loginId . ',') !== false) return true;
    return (strpos(',' . str_replace(' ', '', $row['share_user_ids']) . ',', ',' . $loginId . ',') !== false);
}

function mosTtHtCanCreate($category)
{
    if (mosTtHtIsAdmin()) return true;
    $loginId = mosTtHtLoginId();
    return in_array($loginId, array(34, 50, 63));
}

function mosTtHtRedirectList($msg)
{
    global $languageid;
    $category = mosTtHtCategoryValue(mosGetParam($_REQUEST, 'category', 1));
    $url = 'main.php?option=common_lists/thongtinhethong&mode=list&category=' . $category . '&l=' . intval($languageid);
    if ($msg != '') $url .= '&msg=' . urlencode($msg);
    if (!headers_sent()) {
        header('Location: ' . $url);
        exit;
    }
    echo '<script type="text/javascript">window.location.href="' . mosTtHtHtml($url) . '";</script>';
    exit;
}

function mosTtHtNormalizeShareIds($value)
{
    $parts = explode(',', (string)$value);
    $ids = array();
    foreach ($parts as $part) {
        $id = intval(trim($part));
        if ($id > 0 && !in_array($id, $ids)) $ids[] = $id;
    }
    return implode(',', $ids);
}

function mosTtHtIdsFromCsv($value)
{
    $normalized = mosTtHtNormalizeShareIds($value);
    if ($normalized == '') return array();
    $ids = array();
    foreach (explode(',', $normalized) as $id) {
        $id = intval($id);
        if ($id > 0) $ids[] = $id;
    }
    return $ids;
}

function mosTtHtSaveUpload($currentPath, $currentName)
{
    if (!isset($_FILES['policy_file']) || empty($_FILES['policy_file']['name'])) {
        return array($currentPath, $currentName);
    }

    if (intval($_FILES['policy_file']['error']) != 0) {
        return array($currentPath, $currentName);
    }

    $uploadDir = mosTtHtUploadDir();
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0777, true);
    }

    $originalName = basename($_FILES['policy_file']['name']);
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowed = array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx');
    if (!in_array($ext, $allowed)) {
        return array($currentPath, $currentName);
    }

    $safeName = 'policy_' . date('YmdHis') . '_' . mt_rand(1000, 9999) . '.' . $ext;
    $target = $uploadDir . $safeName;
    if (move_uploaded_file($_FILES['policy_file']['tmp_name'], $target)) {
        return array('uploads/thongtinhethong/' . $safeName, $originalName);
    }

    return array($currentPath, $currentName);
}

function mosThongTinHeThongList()
{
    global $db, $template;
    mosTtHtTableReady();

    $category = mosTtHtCategoryValue(mosGetParam($_REQUEST, 'category', 1));
    $loginId = mosTtHtLoginId();
    $canManageCategory = mosTtHtCanCreate($category);
    $permissionColDisplay = $canManageCategory ? '' : 'none';
    $cond = " where active = 1 and info_category in (".mosTtHtCategorySqlValues($category).")";
    if (!mosTtHtIsAdmin()) {
        $cond .= " and (created_member_id = ".$loginId." or concat(',', replace(editable_member_ids, ' ', ''), ',') like '%,".$loginId.",%' or concat(',', replace(share_user_ids, ' ', ''), ',') like '%,".$loginId.",%')";
    }

    $sql = "select * from tbl_system_information ".$cond." order by priority, info_id desc";
    if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);

    $order = 0;
    while ($row = $db->sql_fetchrow($result)) {
        $order++;
        $downloadLink = '';
        if (trim($row['file_path']) != '') {
            $downloadLink = '?option=common_lists/thongtinhethong&mode=download&id=' . intval($row['info_id']);
        }

        $template->assign_block_vars('list', array(
            'className' => ($order % 2 == 1) ? 'alt' : 'inv',
            'order' => $order,
            'info_id' => intval($row['info_id']),
            'info_title' => mosTtHtHtml($row['info_title']),
            'view_link' => '?option=common_lists/thongtinhethong&mode=view&category=' . $category . '&id=' . intval($row['info_id']),
            'file_name' => mosTtHtHtml($row['file_name']),
            'download_link' => mosTtHtHtml($downloadLink . ($downloadLink != '' ? '&category=' . $category : '')),
            'download_display' => ($downloadLink != '') ? '' : 'none',
            'permission_col_display' => $permissionColDisplay,
            'editable_member_ids' => mosTtHtHtml($row['editable_member_ids']),
            'share_user_ids' => mosTtHtHtml($row['share_user_ids']),
            'created_by' => mosTtHtHtml($row['created_by']),
            'last_modified' => mosTtHtHtml($row['last_modified']),
            'is_owner' => mosTtHtCanEdit($row) ? '' : 'none',
        ));
    }

    $msg = mosGetParam($_REQUEST, 'msg', '');
    $message = '';
    if ($msg == 'save') $message = SAVE_SUCCESS;
    if ($msg == 'delete') $message = DELETE_SUCCESS;

    $template->assign_vars(array(
        'MESSAGE' => $message,
        'CATEGORY' => $category,
        'CATEGORY_TITLE' => mosTtHtHtml(mosTtHtCategoryName($category)),
        'CREATE_DISPLAY' => $canManageCategory ? '' : 'none',
        'PERMISSION_COL_DISPLAY' => $permissionColDisplay,
    ));
    $template->set_filenames_new(array(
        'thongtinhethong' => 'common_lists/thongtinhethong/thongtinhethong_list.html'
    ));
    $template->pparse('thongtinhethong');
}

function mosThongTinHeThongView()
{
    global $db, $template, $languageid;
    mosTtHtTableReady();

    $category = mosTtHtCategoryValue(mosGetParam($_REQUEST, 'category', 1));
    $infoId = intval(mosGetParam($_REQUEST, 'id', 0));
    $sql = "select * from tbl_system_information where info_id = ".$infoId." and active = 1 limit 1";
    if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
    if (!($row = $db->sql_fetchrow($result))) message_die(ID_NOTFOUND);
    if (!mosTtHtCanView($row)) {
        mosInvalidURL();
        exit;
    }

    $downloadLink = '';
    if (trim($row['file_path']) != '') {
        $downloadLink = '?option=common_lists/thongtinhethong&mode=download&id=' . intval($row['info_id']) . '&l=' . intval($languageid);
    }

    $content = trim(mosTtHtCleanStoredHtml($row['content_html']));
    if ($content == '') {
        $content = 'Chưa nhập nội dung hiển thị. Vui lòng tải file đính kèm để xem chính sách.';
    }
    $categoryOfRow = mosTtHtCategoryValue($row['info_category']);
    if (mosTtHtHasStampOptions($categoryOfRow) && intval(isset($row['dong_moc']) ? $row['dong_moc'] : 0) == 1) {
        $content .= "\n" . mosTtHtStampHtml();
    }

    $hieuLucDen = isset($row['hieu_luc_den']) ? mosTtHtDateValue($row['hieu_luc_den']) : '';
    $isExpired = mosTtHtHasStampOptions($categoryOfRow) && mosTtHtIsExpired($hieuLucDen);

    $template->assign_vars(array(
        'funname' => 'common_lists/thongtinhethong',
        'LANGUAGEID' => $languageid,
        'info_title' => mosTtHtHtml($row['info_title']),
        'info_category' => mosTtHtHtml($row['info_category']),
        'content_html' => $content,
        'expiry_display' => $isExpired ? 'block' : 'none',
        'expiry_message' => $isExpired ? 'Tài liệu này đã hết hiệu lực từ ngày '.$hieuLucDen.'.' : '',
        'download_link' => mosTtHtHtml($downloadLink),
        'download_display' => ($downloadLink != '') ? '' : 'none',
        'file_name' => mosTtHtHtml($row['file_name']),
        'back_link' => '?option=common_lists/thongtinhethong&mode=list&category=' . $category . '&l=' . intval($languageid),
    ));

    $template->set_filenames_new(array(
        'thongtinhethong' => 'common_lists/thongtinhethong/thongtinhethong_view.html'
    ));
    $template->pparse('thongtinhethong');
}

function mosThongTinHeThongInfo()
{
    global $db, $template, $languageid;
    mosTtHtTableReady();

    $category = mosTtHtCategoryValue(mosGetParam($_REQUEST, 'category', 1));
    $infoId = intval(mosGetParam($_REQUEST, 'id', 0));
    if ($infoId <= 0 && !mosTtHtCanCreate($category)) {
        mosInvalidURL();
        exit;
    }

    $sql = "select * from tbl_member where active = 1 and member_id not in (1, 2, 62, 73, 81) order by fullname";
    if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
    while ($row = $db->sql_fetchrow($result)) {
        $template->assign_block_vars('edit_member_note', array(
            'member_id' => intval($row['member_id']),
            'member_name' => mosTtHtHtml($row['fullname']),
        ));
        $template->assign_block_vars('share_member_note', array(
            'member_id' => intval($row['member_id']),
            'member_name' => mosTtHtHtml($row['fullname']),
        ));
        $template->assign_block_vars('notify_member_note', array(
            'member_id' => intval($row['member_id']),
            'member_name' => mosTtHtHtml($row['fullname']),
        ));
    }

    if ($infoId > 0) {
        $sql = "select * from tbl_system_information where info_id = ".$infoId." limit 1";
        if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
        if (!($row = $db->sql_fetchrow($result))) message_die(ID_NOTFOUND);
        if (!mosTtHtCanEdit($row)) {
            mosInvalidURL();
            exit;
        }
        $category = mosTtHtCategoryValue($row['info_category']);
    } else {
        $row = array(
            'info_id' => 0,
            'info_title' => '',
            'info_category' => $category,
            'content_html' => '',
            'file_name' => '',
            'file_path' => '',
            'dong_moc' => 0,
            'hieu_luc_den' => '',
            'editable_member_ids' => '',
            'share_user_ids' => '',
            'active' => 1,
            'created_date' => '',
            'created_by' => '',
            'last_modified' => '',
            'modified_by' => '',
        );
    }

    $template->assign_vars(array(
        'funname' => 'common_lists/thongtinhethong',
        'LANGUAGEID' => $languageid,
        'category' => $category,
        'category_title' => mosTtHtHtml(mosTtHtCategoryName($category)),
        'info_id' => intval($row['info_id']),
        'info_title' => mosTtHtHtml($row['info_title']),
        'info_category' => mosTtHtCategoryValue($row['info_category']),
        'content_html' => mosTtHtHtml(mosTtHtCleanStoredHtml($row['content_html'])),
        'file_name' => mosTtHtHtml($row['file_name']),
        'file_display' => trim($row['file_path']) != '' ? '' : 'none',
        'stamp_display' => mosTtHtHasStampOptions($category) ? '' : 'none',
        'stamp_checked' => intval(isset($row['dong_moc']) ? $row['dong_moc'] : 0) == 1 ? 'checked' : '',
        'hieu_luc_den' => mosTtHtHtml(isset($row['hieu_luc_den']) ? mosTtHtDateValue($row['hieu_luc_den']) : ''),
        'editable_member_ids' => mosTtHtHtml($row['editable_member_ids']),
        'share_user_ids' => mosTtHtHtml($row['share_user_ids']),
        'active' => intval($row['active']) == 1 ? 'checked' : '',
        'created_date' => mosTtHtHtml($row['created_date']),
        'created_by' => mosTtHtHtml($row['created_by']),
        'last_modified' => mosTtHtHtml($row['last_modified']),
        'modified_by' => mosTtHtHtml($row['modified_by']),
        'is_new' => intval($row['info_id']) > 0 ? '0' : '1',
    ));

    $template->set_filenames_new(array(
        'thongtinhethong' => 'common_lists/thongtinhethong/thongtinhethong_info.html'
    ));
    $template->pparse('thongtinhethong');
}

function mosThongTinHeThongSave()
{
    global $db, $languageid;
    mosTtHtTableReady();

    $infoId = intval(mosGetParam($_REQUEST, 'id', 0));
    $category = mosTtHtCategoryValue(mosGetParam($_REQUEST, 'category', 1));
    $title = mosGetParam($_REQUEST, 'info_title', '');
    $content = mosTtHtRequestHtml('content_html');
    $editableIds = mosTtHtNormalizeShareIds(mosGetParam($_REQUEST, 'editable_member_ids', ''));
    $shareIds = mosTtHtNormalizeShareIds(mosGetParam($_REQUEST, 'share_user_ids', ''));
    $active = intval(mosGetParam($_REQUEST, 'active', 0)) == 1 ? 1 : 0;
    $sendNotification = intval(mosGetParam($_REQUEST, 'send_notification', 0)) == 1 ? 1 : 0;
    $notificationMessage = mosTtHtRequestText('notification_message');
    $notificationMemberIds = mosTtHtIdsFromCsv(mosTtHtRequestText('notification_member_ids'));
    $dongMoc = (mosTtHtHasStampOptions($category) && intval(mosGetParam($_REQUEST, 'dong_moc', 0)) == 1) ? 1 : 0;
    $hieuLucDen = mosTtHtHasStampOptions($category) ? mosTtHtDateValue(mosGetParam($_REQUEST, 'hieu_luc_den', '')) : '';
    $memberName = isset($_SESSION['membername']) ? $_SESSION['membername'] : '';
    $loginId = mosTtHtLoginId();
    $languageId = intval($languageid) > 0 ? intval($languageid) : 2;
    $filePath = '';
    $fileName = '';
    $oldFilePath = '';
    $contentImportedFromFile = 0;

    if ($infoId > 0) {
        $sql = "select * from tbl_system_information where info_id = ".$infoId." limit 1";
        if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
        if (!($row = $db->sql_fetchrow($result))) message_die(ID_NOTFOUND);
        if (!mosTtHtCanEdit($row)) {
            mosInvalidURL();
            exit;
        }
        $filePath = $row['file_path'];
        $fileName = $row['file_name'];
        $oldFilePath = $filePath;
        $contentImportedFromFile = intval(isset($row['content_imported_from_file']) ? $row['content_imported_from_file'] : 0);
    } else if (!mosTtHtCanCreate($category)) {
        mosInvalidURL();
        exit;
    }

    list($filePath, $fileName) = mosTtHtSaveUpload($filePath, $fileName);
    $uploadedNewFile = ($filePath != '' && $filePath != $oldFilePath);
    if ($uploadedNewFile) $contentImportedFromFile = 0;

    if ($infoId > 0) {
        $sql = "
            update tbl_system_information set
                info_title = '".mosTtHtSql($title)."',
                info_category = '".mosTtHtSql($category)."',
                content_html = '".mosTtHtSql($content)."',
                file_name = '".mosTtHtSql($fileName)."',
                file_path = '".mosTtHtSql($filePath)."',
                content_imported_from_file = ".$contentImportedFromFile.",
                dong_moc = ".$dongMoc.",
                hieu_luc_den = ".($hieuLucDen == '' ? "null" : "'".mosTtHtSql($hieuLucDen)."'").",
                editable_member_ids = '".mosTtHtSql($editableIds)."',
                share_user_ids = '".mosTtHtSql($shareIds)."',
                active = ".$active.",
                modified_by = '".mosTtHtSql($memberName)."',
                last_modified = now()
            where info_id = ".$infoId."
        ";
    } else {
        $priority = mosGetPriority('tbl_system_information', 'priority', '');
        $sql = "
            insert into tbl_system_information
                (info_title, info_category, content_html, file_name, file_path, content_imported_from_file, dong_moc, hieu_luc_den, editable_member_ids, share_user_ids, active, priority, language_id, created_member_id, created_by, created_date, modified_by, last_modified)
            values
                ('".mosTtHtSql($title)."', '".mosTtHtSql($category)."', '".mosTtHtSql($content)."', '".mosTtHtSql($fileName)."', '".mosTtHtSql($filePath)."', ".$contentImportedFromFile.", ".$dongMoc.", ".($hieuLucDen == '' ? "null" : "'".mosTtHtSql($hieuLucDen)."'").", '".mosTtHtSql($editableIds)."', '".mosTtHtSql($shareIds)."', ".$active.", ".$priority.", ".$languageId.", ".$loginId.", '".mosTtHtSql($memberName)."', now(), '".mosTtHtSql($memberName)."', now())
        ";
    }

    if (!$db->sql_query($sql)) message_die(DATABASE_BUSY);
    if ($infoId <= 0) {
        if ($resultNewId = $db->sql_query("SELECT LAST_INSERT_ID() AS new_id")) {
            if ($rowNewId = $db->sql_fetchrow($resultNewId)) $infoId = (int)$rowNewId['new_id'];
        }
    }
    if ($active == 1 && $sendNotification == 1) {
        $notifyTitle = mosTtHtNotificationTitle($category);
        $notifyMessage = $title.' - Cập nhật bởi: '.$memberName;
        if ($notificationMessage != '') $notifyMessage .= ' - Chi tiết: '.$notificationMessage;
        $notifyLink = 'main.php?option=common_lists/thongtinhethong&mode=view&category='.intval($category).'&id='.intval($infoId).'&l='.intval($languageid);
        if (count($notificationMemberIds) > 0) {
            notificationCreateMany($notificationMemberIds, 'company_document_update', $notifyTitle, $notifyMessage, $notifyLink, $loginId);
        } else {
            notificationCreateForActiveMembers('company_document_update', $notifyTitle, $notifyMessage, $notifyLink, $loginId);
        }
    }
    mosTtHtRedirectList('save');
}

function mosThongTinHeThongDelete()
{
    global $db;
    mosTtHtTableReady();
    $infoId = intval(mosGetParam($_REQUEST, 'id', 0));
    if ($infoId <= 0) mosTtHtRedirectList('');

    $sql = "select * from tbl_system_information where info_id = ".$infoId." limit 1";
    if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
    if (!($row = $db->sql_fetchrow($result))) message_die(ID_NOTFOUND);
    if (!mosTtHtCanEdit($row)) {
        mosInvalidURL();
        exit;
    }

    $sql = "delete from tbl_system_information where info_id = ".$infoId;
    if (!$db->sql_query($sql)) message_die(DATABASE_BUSY);
    mosTtHtRedirectList('delete');
}

function mosThongTinHeThongDownload()
{
    global $db;
    mosTtHtTableReady();
    $infoId = intval(mosGetParam($_REQUEST, 'id', 0));
    $sql = "select * from tbl_system_information where info_id = ".$infoId." and active = 1 limit 1";
    if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
    if (!($row = $db->sql_fetchrow($result))) message_die(ID_NOTFOUND);
    if (!mosTtHtCanView($row)) {
        mosInvalidURL();
        exit;
    }

    $relativePath = trim($row['file_path']);
    $filePath = dirname(__FILE__) . '/../../' . $relativePath;
    if ($relativePath == '' || !is_file($filePath)) {
        die('Không tìm thấy file chính sách');
    }

    $downloadName = trim($row['file_name']) != '' ? $row['file_name'] : basename($filePath);
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . str_replace('"', '', $downloadName) . '"');
    header('Content-Length: ' . filesize($filePath));
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    readfile($filePath);
    exit;
}
?>
