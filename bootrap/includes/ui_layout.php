<?php
function buffcorpAddHtmlClass($tag, $className)
{
    if (preg_match('/\bclass\s*=\s*(["\'])(.*?)\1/i', $tag)) {
        return preg_replace_callback(
            '/\bclass\s*=\s*(["\'])(.*?)\1/i',
            function ($match) use ($className) {
                return 'class=' . $match[1] . trim($match[2] . ' ' . $className) . $match[1];
            },
            $tag,
            1
        );
    }
    return preg_replace('/\s*(\/?)>$/', ' class="' . $className . '"$1>', $tag, 1);
}

function buffcorpActionIcon($type)
{
    $paths = [
        'add' => '<path d="M12 5v14M5 12h14"></path>',
        'save' => '<path d="M5 3h12l2 2v16H5z"></path><path d="M8 3v6h8V3M8 21v-8h8v8"></path>',
        'back' => '<path d="M19 12H5M11 18l-6-6 6-6"></path>',
        'view' => '<path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"></path><circle cx="12" cy="12" r="2.5"></circle>',
        'edit' => '<path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4z"></path>',
        'delete' => '<path d="M3 6h18M8 6V4h8v2M19 6l-1 15H6L5 6M10 11v6M14 11v6"></path>',
        'move-up' => '<path d="M12 19V5M6 11l6-6 6 6"></path>',
        'move-down' => '<path d="M12 5v14M6 13l6 6 6-6"></path>',
        'permission' => '<path d="M12 3l7 3v5c0 4.5-2.8 8-7 10-4.2-2-7-5.5-7-10V6z"></path><path d="M9 12l2 2 4-4"></path>',
        'password' => '<circle cx="8" cy="15" r="4"></circle><path d="M11 12l8-8M15 8l2 2M17 6l2 2"></path>',
        'refresh' => '<path d="M20 6v5h-5M4 18v-5h5"></path><path d="M18 9a7 7 0 0 0-12-3L4 8M6 15a7 7 0 0 0 12 3l2-2"></path>',
    ];
    $path = isset($paths[$type]) ? $paths[$type] : $paths['view'];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
}

function buffcorpNormalizeToolbarAction($linkHtml, $index)
{
    $label = trim(preg_replace('/\s+/', ' ', strip_tags($linkHtml)));
    $normalized = function_exists('mb_strtolower') ? mb_strtolower($label, 'UTF-8') : strtolower($label);
    $translatedActions = [
        'create new' => 'Thêm mới',
        'add new' => 'Thêm mới',
        'save' => 'Lưu',
        'send' => 'Gửi',
        'back' => 'Trở về',
        'list' => 'Trở về',
        'return' => 'Trở về',
    ];
    $displayLabel = isset($translatedActions[$normalized]) ? $translatedActions[$normalized] : $label;
    $actionType = (strpos($normalized, 'save') !== false || strpos($normalized, 'lưu') !== false)
        ? 'save'
        : ((strpos($normalized, 'back') !== false || strpos($normalized, 'return') !== false || strpos($normalized, 'về') !== false || $normalized === 'list') ? 'back' : 'add');
    $className = $index === 0 ? 'buffcorp-primary-action' : 'buffcorp-secondary-action';
    $linkHtml = buffcorpAddHtmlClass($linkHtml, $className);
    return preg_replace(
        '/(<a\b[^>]*>).*?(<\/a>)/is',
        '$1' . buffcorpActionIcon($actionType) . '<span>' . htmlspecialchars($displayLabel, ENT_QUOTES, 'UTF-8') . '</span>$2',
        $linkHtml,
        1
    );
}

function buffcorpPrettyOptionLabel($option)
{
    $name = trim(preg_replace('#^.*/#', '', $option));
    $name = str_replace(['_', '-'], ' ', $name);
    return $name !== '' ? $name : 'dữ liệu';
}

function buffcorpActionTypeFromHtml($html)
{
    $key = strtolower(strip_tags((string)$html) . ' ' . (string)$html);
    if (strpos($key, 'delete') !== false || strpos($key, 'trash') !== false || strpos($key, 'xoa') !== false) return 'delete';
    if (strpos($key, 'edit') !== false || strpos($key, 'pencil') !== false || strpos($key, 'sua') !== false) return 'edit';
    if (strpos($key, 'down') !== false) return 'move-down';
    if (strpos($key, 'up.') !== false || strpos($key, 'up_') !== false || strpos($key, 'moveup') !== false) return 'move-up';
    if (strpos($key, 'permission') !== false || strpos($key, 'perms') !== false || strpos($key, 'db_user') !== false) return 'permission';
    if (strpos($key, 'password') !== false || strpos($key, 'securityroles') !== false || strpos($key, 'pass') !== false) return 'password';
    return 'view';
}

function buffcorpActionLabel($type)
{
    $labels = [
        'delete' => 'Xóa',
        'edit' => 'Sửa',
        'move-up' => 'Đưa lên',
        'move-down' => 'Đưa xuống',
        'permission' => 'Phân quyền',
        'password' => 'Đổi mật khẩu',
        'view' => 'Xem',
    ];
    return isset($labels[$type]) ? $labels[$type] : 'Xem';
}

function buffcorpModernizeTable($table)
{
    $table = preg_replace_callback('/<table\b[^>]*>/i', function ($match) {
        return buffcorpAddHtmlClass($match[0], 'buffcorp-server-table');
    }, $table, 1);

    $table = preg_replace_callback('/<a\b([^>]*)>\s*<img\b([^>]*)>\s*<\/a>/is', function ($match) {
        $source = $match[0];
        if (preg_match('/display\s*:\s*none/i', $source)) return '';
        $type = buffcorpActionTypeFromHtml($source);
        $label = buffcorpActionLabel($type);
        $attrs = buffcorpAddHtmlClass('<a' . $match[1] . '>', 'buffcorp-row-action buffcorp-action-' . $type);
        $attrs = preg_replace('/\s*target\s*=\s*(["\'])main\1/i', '', $attrs);
        if (!preg_match('/\btitle\s*=/i', $attrs)) {
            $attrs = preg_replace('/>$/', ' title="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '">', $attrs, 1);
        }
        if (!preg_match('/\baria-label\s*=/i', $attrs)) {
            $attrs = preg_replace('/>$/', ' aria-label="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '">', $attrs, 1);
        }
        return $attrs . buffcorpActionIcon($type) . '</a>';
    }, $table);

    $table = preg_replace('/<td\b([^>]*)\bcolspan\s*=\s*(["\']?)[2-9]\2([^>]*)>\s*<\/td>/i', '<td$1$3 class="buffcorp-actions-head">Thao tác</td>', $table, 1);
    $table = preg_replace('/<td\b([^>]*)>\s*(<a\b[^>]*\bbuffcorp-row-action\b.*?<\/a>\s*)+<\/td>/is', '<td$1 class="buffcorp-actions-cell"><div class="buffcorp-row-actions">$2</div></td>', $table);
    return $table;
}

function buffcorpExtractScripts($html)
{
    if (!preg_match_all('/<script\b[^>]*>.*?<\/script>/is', $html, $scriptMatches)) return '';
    return implode('', $scriptMatches[0]);
}

function buffcorpExtractToolbarActions($html)
{
    $toolbarActions = '';
    if (preg_match('/<div\b[^>]*\bclass\s*=\s*(["\'])[^"\']*\btoolbar\b[^"\']*\1[^>]*>(.*?)<\/div>/is', $html, $toolbarMatch)) {
        if (preg_match_all('/<a\b[^>]*>.*?<\/a>/is', $toolbarMatch[2], $actionMatches)) {
            foreach ($actionMatches[0] as $index => $actionHtml) {
                $toolbarActions .= buffcorpNormalizeToolbarAction($actionHtml, $index);
            }
        }
    }
    return $toolbarActions;
}

function buffcorpExtractFilterForm($html)
{
    if (preg_match('/<form\b[^>]*\bname\s*=\s*(["\'])filterForm\1[^>]*>.*?<\/form>/is', $html, $formMatch)) {
        return preg_replace_callback('/<form\b[^>]*>/i', function ($match) {
            return buffcorpAddHtmlClass($match[0], 'buffcorp-module-filter');
        }, $formMatch[0], 1);
    }
    if (preg_match('/<div\b[^>]*\bclass\s*=\s*(["\'])[^"\']*\btabtitle\b[^"\']*\1[^>]*>.*?(<form\b[^>]*>.*?<\/form>).*?<\/div>/is', $html, $formMatch)) {
        return preg_replace_callback('/<form\b[^>]*>/i', function ($match) {
            return buffcorpAddHtmlClass($match[0], 'buffcorp-module-filter');
        }, $formMatch[2], 1);
    }
    return '';
}

function buffcorpBuildServerListLayout($html, $option, $mode)
{
    if (preg_match_all('/<table\b[^>]*\bclass\s*=\s*(["\'])[^"\']*\bselector\b[^"\']*\1[^>]*>.*?<\/table>/is', $html, $selectorMatches)) {
        if (count($selectorMatches[0]) !== 1) return null;
        $tableMatch = [$selectorMatches[0][0]];
    } else {
        if (!preg_match_all('/<table\b[^>]*>.*?<tr\b[^>]*\bclass\s*=\s*(["\'])[^"\']*\bheader\b[^"\']*\1[^>]*>.*?<\/table>/is', $html, $headerMatches)) {
            return null;
        }
        if (count($headerMatches[0]) !== 1) return null;
        $tableMatch = [$headerMatches[0][0]];
        $tableMatch[0] = preg_replace_callback('/<table\b[^>]*>/i', function ($match) {
            return buffcorpAddHtmlClass($match[0], 'selector');
        }, $tableMatch[0], 1);
    }

    if (!preg_match('/<tr\b[^>]*\bclass\s*=\s*(["\'])[^"\']*\bheader\b[^"\']*\1/i', $tableMatch[0])) {
        return null;
    }

    $table = buffcorpModernizeTable($tableMatch[0]);
    $rows = 0;
    if (preg_match_all('/<tr\b(?![^>]*\bclass\s*=\s*(["\'])[^"\']*\bheader\b[^"\']*\1)[^>]*>/i', $table, $rowMatches)) {
        $rows = count($rowMatches[0]);
    }

    $toolbarActions = buffcorpExtractToolbarActions($html);
    $filterForm = buffcorpExtractFilterForm($html);
    $scripts = buffcorpExtractScripts($html);
    $message = '';
    if (preg_match('/<p\b[^>]*>.*?\{MESSAGE\}.*?<\/p>/is', $html, $messageMatch)) {
        $message = $messageMatch[0];
    }

    $label = htmlspecialchars(buffcorpPrettyOptionLabel($option), ENT_QUOTES, 'UTF-8');
    $recordText = $rows . ' bản ghi phù hợp';
    $countText = $rows . ' mục';

    return '<section class="buffcorp-module-card buffcorp-server-module buffcorp-server-rendered buffcorp-module-ready"'
        . ' data-layout="list"'
        . ' data-option="' . htmlspecialchars($option, ENT_QUOTES, 'UTF-8') . '"'
        . ' data-mode="' . htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') . '">'
        . '<div class="buffcorp-list-header">'
        . '<div class="buffcorp-list-title"><strong>Danh sách ' . $label . '</strong><small>' . $recordText . '</small></div>'
        . '<div class="buffcorp-list-header-actions">' . $toolbarActions . '<button type="button" class="buffcorp-refresh" title="Làm mới" aria-label="Làm mới" onclick="window.location.reload()">' . buffcorpActionIcon('refresh') . '</button></div>'
        . '</div>'
        . '<div class="buffcorp-module-toolbar">'
        . '<div class="buffcorp-client-controls"><input type="search" placeholder="Tìm trong ' . $label . '..." aria-label="Tìm trong bảng"><select aria-label="Số dòng"><option value="5">5 dòng</option><option value="10">10 dòng</option><option value="20">20 dòng</option><option value="50">50 dòng</option></select><button type="button" class="buffcorp-filter-toggle" aria-expanded="false">Bộ lọc</button></div>'
        . '<div class="buffcorp-filter-panel">' . $filterForm . '</div>'
        . '<div class="buffcorp-module-actions"></div>'
        . '</div>'
        . '<div class="buffcorp-list-head"><div class="buffcorp-list-head-copy"><strong>' . $countText . '</strong><small class="buffcorp-visible-range">Đang chuẩn bị danh sách</small></div><div class="buffcorp-list-summary-actions"><span class="buffcorp-record-count">' . $countText . '</span></div></div>'
        . '<div class="buffcorp-table-wrap" style="height:auto;overflow-x:auto;overflow-y:hidden">' . $table . '</div>'
        . '<div class="buffcorp-mobile-cards" aria-live="polite"></div>'
        . '<div class="buffcorp-empty-state"><strong>Không tìm thấy kết quả</strong><small>Hãy thử thay đổi từ khóa hoặc xóa bớt bộ lọc.</small></div>'
        . '<footer class="buffcorp-pagination"><span>Trang 1 / 1</span><div></div></footer>'
        . '</section>' . $message . $scripts;
}

function buffcorpBuildServerFormLayout($html, $option, $mode)
{
    if (!preg_match('/<form\b[^>]*>.*?<\/form>/is', $html, $formMatch)) return null;
    $form = preg_replace_callback('/<form\b[^>]*>/i', function ($match) {
        return buffcorpAddHtmlClass($match[0], 'buffcorp-server-form');
    }, $formMatch[0], 1);
    $form = preg_replace_callback('/<table\b[^>]*>/i', function ($match) {
        return buffcorpAddHtmlClass($match[0], 'buffcorp-form-table');
    }, $form, 1);
    $toolbarActions = buffcorpExtractToolbarActions($html);
    $scripts = buffcorpExtractScripts($html);
    $label = htmlspecialchars(buffcorpPrettyOptionLabel($option), ENT_QUOTES, 'UTF-8');

    return '<section class="buffcorp-module-card buffcorp-server-module buffcorp-server-rendered buffcorp-module-ready"'
        . ' data-layout="form"'
        . ' data-option="' . htmlspecialchars($option, ENT_QUOTES, 'UTF-8') . '"'
        . ' data-mode="' . htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') . '">'
        . '<div class="buffcorp-module-toolbar"><div class="buffcorp-list-head-copy"><strong>Thông tin ' . $label . '</strong><small>Biểu mẫu chỉnh sửa</small></div>'
        . '<div class="buffcorp-module-actions">' . $toolbarActions . '</div></div>'
        . '<div class="buffcorp-form-card">' . $form . '</div>'
        . '</section>' . $scripts;
}

function buffcorpPrepareModuleHtml($html, $option = '', $mode = '')
{
    if ($html === '' || preg_match('/\b(admin-dashboard|sales-page|kpi-page|kpi-report|org-chart|bc-chat)\b/i', $html)) {
        return $html;
    }
    if (preg_match('/\bbuffcorp-server-rendered\b/i', $html)) {
        return $html;
    }

    $isList = preg_match('/<table\b[^>]*\bclass\s*=\s*(["\'])[^"\']*\bselector\b[^"\']*\1/i', $html)
        || preg_match('/<table\b[^>]*>.*?<tr\b[^>]*\bclass\s*=\s*(["\'])[^"\']*\bheader\b[^"\']*\1/is', $html);
    $isForm = !$isList && preg_match('/<form\b/i', $html);
    if (!$isList && !$isForm) return $html;

    if ($isList) {
        $serverLayout = buffcorpBuildServerListLayout($html, $option, $mode);
        if ($serverLayout !== null) return $serverLayout;
        return '<section class="buffcorp-module-card buffcorp-server-module buffcorp-module-ready"'
            . ' data-layout="legacy-list"'
            . ' data-option="' . htmlspecialchars($option, ENT_QUOTES, 'UTF-8') . '"'
            . ' data-mode="' . htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') . '">'
            . '<div class="buffcorp-legacy-list">' . $html . '</div></section>';
    }
    if ($isForm) {
        $serverLayout = buffcorpBuildServerFormLayout($html, $option, $mode);
        if ($serverLayout !== null) return $serverLayout;
    }

    $layout = $isList ? 'list' : 'form';
    return '<section class="buffcorp-module-card buffcorp-server-module buffcorp-server-rendered buffcorp-module-ready"'
        . ' data-layout="' . $layout . '"'
        . ' data-option="' . htmlspecialchars($option, ENT_QUOTES, 'UTF-8') . '"'
        . ' data-mode="' . htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') . '">'
        . $html . '</section>';
}
