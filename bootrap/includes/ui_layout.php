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
        'permission' => '<path d="M12 3l7 3v5c0 4.5-2.8 8-7 10-4.2-2-7-5.5-7-10V6z"></path><path d="M9 12l2 2 4-4"></path>',
        'password' => '<circle cx="8" cy="15" r="4"></circle><path d="M11 12l8-8M15 8l2 2M17 6l2 2"></path>',
        'refresh' => '<path d="M20 6v5h-5M4 18v-5h5"></path><path d="M18 9a7 7 0 0 0-12-3L4 8M6 15a7 7 0 0 0 12 3l2-2"></path>',
        'export' => '<path d="M12 3v12M7 10l5 5 5-5"></path><path d="M5 21h14"></path>',
        'search' => '<circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path>',
        'filter' => '<path d="M4 6h16M7 12h10M10 18h4"></path>',
        'more' => '<circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle>',
        'stats' => '<path d="M4 19V5"></path><path d="M4 19h16"></path><rect x="7" y="11" width="3" height="5" rx="1"></rect><rect x="12" y="7" width="3" height="9" rx="1"></rect><rect x="17" y="3" width="3" height="13" rx="1"></rect>',
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
        : ((strpos($normalized, 'back') !== false || strpos($normalized, 'return') !== false || strpos($normalized, 'về') !== false || $normalized === 'list') ? 'back' : ((strpos($normalized, 'thống kê') !== false || strpos($normalized, 'thong ke') !== false) ? 'stats' : 'add'));
    $className = $index === 0 ? 'list-btn list-btn-primary' : 'list-btn list-btn-secondary';
    return preg_replace_callback('/<a\b([^>]*)>.*?<\/a>/is', function ($match) use ($className, $actionType, $displayLabel) {
        $openTag = buffcorpAddHtmlClass('<a' . $match[1] . '>', $className);
        return $openTag . buffcorpActionIcon($actionType) . '<span>' . htmlspecialchars($displayLabel, ENT_QUOTES, 'UTF-8') . '</span></a>';
    }, $linkHtml, 1);
}

function buffcorpIsRunnableToolbarAction($linkHtml, $pageHtml)
{
    if (preg_match('/\bstyle\s*=\s*(["\'])(.*?)\1/is', $linkHtml, $styleMatch)) {
        if (preg_match('/display\s*:\s*none/i', $styleMatch[2])) return false;
    }
    if (preg_match('/\bhref\s*=\s*(["\'])\s*(?:javascript|JavaScript)\s*:\s*([a-zA-Z_][a-zA-Z0-9_]*)\s*\(/i', $linkHtml, $hrefMatch)) {
        $fn = preg_quote($hrefMatch[2], '/');
        return preg_match('/\bfunction\s+' . $fn . '\s*\(/i', $pageHtml)
            || preg_match('/\b' . $fn . '\s*=\s*function\b/i', $pageHtml);
    }
    if (preg_match('/\bhref\s*=\s*(["\'])\s*(?:#|javascript\s*:\s*;?|javascript\s*:\s*void\s*\(\s*0\s*\))\s*\1/i', $linkHtml)) {
        return false;
    }
    return true;
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
    if (strpos($key, 'down') !== false) return 'skip';
    if (strpos($key, 'up.') !== false || strpos($key, 'up_') !== false || strpos($key, 'moveup') !== false) return 'skip';
    if (strpos($key, 'permission') !== false || strpos($key, 'perms') !== false || strpos($key, 'db_user') !== false) return 'permission';
    if (strpos($key, 'password') !== false || strpos($key, 'securityroles') !== false || strpos($key, 'pass') !== false) return 'password';
    return 'view';
}

function buffcorpShouldKeepListAction($type)
{
    return $type === 'edit' || $type === 'delete';
}

function buffcorpActionLabel($type)
{
    $labels = [
        'delete' => 'Xóa',
        'edit' => 'Sửa',
        'permission' => 'Phân quyền',
        'password' => 'Đổi mật khẩu',
        'view' => 'Xem',
    ];
    return isset($labels[$type]) ? $labels[$type] : 'Xem';
}

function buffcorpListUiTable($table)
{
    return preg_replace_callback('/<table\b[^>]*>/i', function ($match) {
        $tag = buffcorpAddHtmlClass($match[0], 'data-table');
        $tag = preg_replace_callback('/\bclass\s*=\s*(["\'])(.*?)\1/i', function ($classMatch) {
            $classes = preg_split('/\s+/', trim($classMatch[2]));
            $classes = array_filter($classes, function ($className) {
                return $className !== 'selector' && $className !== 'buffcorp-server-table';
            });
            if (!in_array('data-table', $classes)) $classes[] = 'data-table';
            $classes = array_unique($classes);
            return 'class=' . $classMatch[1] . trim(implode(' ', $classes)) . $classMatch[1];
        }, $tag, 1);
        return $tag;
    }, $table, 1);
}

function buffcorpModernizeTable($table)
{
    $table = preg_replace_callback('/<table\b[^>]*>/i', function ($match) {
        return buffcorpAddHtmlClass($match[0], 'data-table');
    }, $table, 1);

    $table = preg_replace_callback('/<a\b([^>]*)>\s*<img\b([^>]*)>\s*<\/a>/is', function ($match) {
        $source = $match[0];
        if (preg_match('/display\s*:\s*none/i', $source)) return '';
        $type = buffcorpActionTypeFromHtml($source);
        if (!buffcorpShouldKeepListAction($type)) return '<span class="list-skip-action"></span>';
        $label = buffcorpActionLabel($type);
        $attrs = buffcorpAddHtmlClass('<a' . $match[1] . '>', 'list-row-action list-action-' . $type);
        $attrs = preg_replace('/\s*target\s*=\s*(["\'])main\1/i', '', $attrs);
        if (!preg_match('/\btitle\s*=/i', $attrs)) {
            $attrs = preg_replace('/>$/', ' title="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '">', $attrs, 1);
        }
        if (!preg_match('/\baria-label\s*=/i', $attrs)) {
            $attrs = preg_replace('/>$/', ' aria-label="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '">', $attrs, 1);
        }
        return $attrs . buffcorpActionIcon($type) . '</a>';
    }, $table);

    $table = buffcorpNormalizeListActionRows($table);
    return buffcorpListUiTable($table);
}

function buffcorpNormalizeListActionRows($table)
{
    $hasActions = preg_match('/\blist-row-action\b/i', $table);
    return preg_replace_callback('/<tr\b([^>]*)>(.*?)<\/tr>/is', function ($match) use ($hasActions) {
        $attrs = $match[1];
        $body = $match[2];
        $isHeader = preg_match('/\bclass\s*=\s*(["\'])[^"\']*\bheader\b/i', $attrs);
        if ($isHeader) {
            $body = preg_replace('/(?:\s*<t[dh]\b[^>]*>\s*<\/t[dh]>)+\s*$/is', '', $body);
            if ($hasActions && preg_match('/<t[dh]\b[^>]*>\s*(?:&nbsp;|\s)*(?:Thao tác|Action|Actions)(?:&nbsp;|\s)*<\/t[dh]>\s*$/iu', $body)) {
                $body = preg_replace('/<t[dh]\b[^>]*>\s*(?:&nbsp;|\s)*(?:Thao tác|Action|Actions)(?:&nbsp;|\s)*<\/t[dh]>\s*$/iu', '<td class="list-actions-head">Thao tác</td>', $body, 1);
                return '<tr' . $attrs . '>' . $body . '</tr>';
            }
            return '<tr' . $attrs . '>' . $body . ($hasActions ? '<td class="list-actions-head">Thao tác</td>' : '') . '</tr>';
        }

        $actions = '';
        $body = preg_replace_callback('/<td\b[^>]*>(.*?)<\/td>/is', function ($cellMatch) use (&$actions) {
            if (preg_match('/\blist-skip-action\b/i', $cellMatch[1])) return '';
            if (!preg_match('/\blist-row-action\b/i', $cellMatch[1])) return $cellMatch[0];
            if (preg_match_all('/<a\b[^>]*\blist-row-action\b.*?<\/a>/is', $cellMatch[1], $actionMatches)) {
                $actions .= implode('', $actionMatches[0]);
            }
            return '';
        }, $body);
        if ($actions === '') return '<tr' . $attrs . '>' . $body . '</tr>';
        return '<tr' . $attrs . '>' . $body . '<td class="list-actions-cell"><div class="list-row-actions">' . $actions . '</div></td></tr>';
    }, $table);
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
            $visibleIndex = 0;
            foreach ($actionMatches[0] as $actionHtml) {
                if (!buffcorpIsRunnableToolbarAction($actionHtml, $html)) continue;
                $toolbarActions .= buffcorpNormalizeToolbarAction($actionHtml, $visibleIndex);
                $visibleIndex++;
            }
        }
    }
    return $toolbarActions;
}

function buffcorpSanitizeCustomerFilterForm($form)
{
    $form = preg_replace('/\s*<input\b(?=[^>]*\btype\s*=\s*(["\']?)checkbox\1)(?=[^>]*\bname\s*=\s*(["\']?)(?:ishc|iskh|istp)\2)[^>]*\/?>\s*(?:HC|KH|TP)?/i', '', $form);
    $form = preg_replace('/(?:&nbsp;|\s)*Sum\s*:\s*\{sum\}(?:&nbsp;|\s)*/i', ' ', $form);
    $form = preg_replace('/(?:&nbsp;|\s)*Actived\s*:\s*<font\b[^>]*>\s*(?:\{actived\}|\d+(?:[.,]\d+)?)%\s*<\/font>(?:&nbsp;|\s)*/i', ' ', $form);
    $form = preg_replace('/(?:&nbsp;|\s)*<font\b[^>]*>\s*(?:\{actived\}|\d+(?:[.,]\d+)?)%\s*<\/font>(?:&nbsp;|\s)*/i', ' ', $form);
    return $form;
}

function buffcorpExtractFilterForm($html, $option = '')
{
    if (preg_match('/<form\b[^>]*\bname\s*=\s*(["\'])filterForm\1[^>]*>.*?<\/form>/is', $html, $formMatch)) {
        $form = $option === 'customer/customer' ? buffcorpSanitizeCustomerFilterForm($formMatch[0]) : $formMatch[0];
        return buffcorpListUiFilterForm($form);
    }
    if (preg_match('/<div\b[^>]*\bclass\s*=\s*(["\'])[^"\']*\btabtitle\b[^"\']*\1[^>]*>.*?(<form\b[^>]*>.*?<\/form>).*?<\/div>/is', $html, $formMatch)) {
        $form = $option === 'customer/customer' ? buffcorpSanitizeCustomerFilterForm($formMatch[2]) : $formMatch[2];
        return buffcorpListUiFilterForm($form);
    }
    return '';
}

function buffcorpListUiFilterForm($form)
{
    $form = preg_replace_callback('/<form\b[^>]*>/i', function ($match) {
        $tag = buffcorpAddHtmlClass($match[0], 'list-filter-form');
        $tag = preg_replace('/\s*target\s*=\s*(["\'])main\1/i', '', $tag);
        return $tag;
    }, $form, 1);
    $form = preg_replace('/<input\b([^>]*)\btype\s*=\s*(["\'])submit\2([^>]*)>/i', '<button type="submit" class="list-btn list-btn-primary">' . buffcorpActionIcon('search') . '<span>Tìm kiếm</span></button>', $form);
    return $form;
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
    $filterForm = buffcorpExtractFilterForm($html, $option);
    $scripts = buffcorpExtractScripts($html);
    $message = '';
    if (preg_match('/<p\b[^>]*>.*?\{MESSAGE\}.*?<\/p>/is', $html, $messageMatch)) {
        $message = $messageMatch[0];
    }

	$label = htmlspecialchars(buffcorpPrettyOptionLabel($option), ENT_QUOTES, 'UTF-8');
    $optionClass = preg_replace('/[^a-z0-9]+/i', '-', strtolower($option));
    $optionClass = trim($optionClass, '-');
    $optionClass = $optionClass !== '' ? ' list-page--' . htmlspecialchars($optionClass, ENT_QUOTES, 'UTF-8') : '';
	$searchControl = '<div class="filter-field filter-field--search filter-field-search"><label>Tìm kiếm</label><div class="list-search-control filter-control">' . buffcorpActionIcon('search') . '<input type="search" placeholder="Tìm trong ' . $label . '..." aria-label="Tìm trong danh sách"></div></div>';
	return '<div class="list-ui filter-ui">'
		. '<section class="list-page buffcorp-server-rendered buffcorp-module-ready' . $optionClass . '"'
        . ' data-layout="list"'
        . ' data-option="' . htmlspecialchars($option, ENT_QUOTES, 'UTF-8') . '"'
        . ' data-mode="' . htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') . '">'
        . '<section class="list-filter-panel"><div class="list-filter-card filter-panel">'
        . '<div class="filter-primary"><div class="filter-grid">'
        . $searchControl
        . '<button type="button" class="list-btn list-btn-secondary list-filter-toggle" aria-expanded="false">' . buffcorpActionIcon('filter') . '<span>Bộ lọc</span></button>'
        . '<div class="list-filter-fields">' . $filterForm . '</div>'
        . '</div></div>'
        . '<div class="filter-actions list-inline-actions"><div class="filter-actions__secondary"><button type="button" class="list-btn list-btn-secondary" data-list-refresh>' . buffcorpActionIcon('refresh') . '<span>Làm mới</span></button>' . $toolbarActions . '</div><div class="filter-actions__primary"><button type="button" class="list-btn list-btn-secondary" data-list-clear><span>Xóa bộ lọc</span></button></div></div>'
        . '</div></section>'
        . '<section class="list-content"><div class="list-table-scroll">' . $table . '</div><div class="mobile-list-cards" aria-live="polite"></div><div class="list-empty-state"><strong>Không tìm thấy kết quả</strong><small>Hãy thử thay đổi từ khóa hoặc xóa bớt bộ lọc.</small></div></section>'
        . '</section></div>' . $message . $scripts;
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
    if ($html === '' || preg_match('/\b(admin-dashboard|sales-page|kpi-page|kpi-report|org-chart|org-edit|member-edit|leave-report-modern|bc-chat)\b/i', $html)) {
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
        $label = htmlspecialchars(buffcorpPrettyOptionLabel($option), ENT_QUOTES, 'UTF-8');
        return '<div class="list-ui filter-ui"><section class="list-page buffcorp-module-ready"'
            . ' data-layout="legacy-list"'
            . ' data-option="' . htmlspecialchars($option, ENT_QUOTES, 'UTF-8') . '"'
            . ' data-mode="' . htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') . '">'
            . '<section class="list-filter-panel"><div class="list-filter-card filter-panel"><div class="filter-actions list-inline-actions"><div class="filter-actions__secondary"><button type="button" class="list-btn list-btn-secondary" onclick="window.location.reload()">' . buffcorpActionIcon('refresh') . '<span>Làm mới</span></button></div></div></div></section>'
            . '<section class="list-content"><div class="list-table-scroll">' . $html . '</div></section></section></div>';
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
