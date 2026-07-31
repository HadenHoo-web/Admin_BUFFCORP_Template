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

function buffcorpPrepareModuleHtml($html, $option = '', $mode = '')
{
    if ($html === '' || preg_match('/\b(admin-dashboard|sales-page|kpi-page|kpi-report|org-chart)\b/i', $html)) {
        return $html;
    }

    $isList = preg_match('/<table\b[^>]*\bclass\s*=\s*(["\'])[^"\']*\bselector\b[^"\']*\1/i', $html);
    $isForm = !$isList && preg_match('/<form\b/i', $html);
    if (!$isList && !$isForm) return $html;

    $html = preg_replace_callback('/<table\b[^>]*>/i', function ($match) {
        return preg_match('/\bclass\s*=\s*(["\'])[^"\']*\bselector\b[^"\']*\1/i', $match[0])
            ? buffcorpAddHtmlClass($match[0], 'buffcorp-server-table')
            : $match[0];
    }, $html);

    if ($isForm) {
        $html = preg_replace_callback('/<form\b[^>]*>/i', function ($match) {
            return buffcorpAddHtmlClass($match[0], 'buffcorp-server-form');
        }, $html, 1);
    }

    $layout = $isList ? 'list' : 'form';
    return '<section class="buffcorp-module-card buffcorp-server-module"'
        . ' data-layout="' . $layout . '"'
        . ' data-option="' . htmlspecialchars($option, ENT_QUOTES, 'UTF-8') . '"'
        . ' data-mode="' . htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') . '">'
        . '<div class="buffcorp-server-source">' . $html . '</div></section>';
}
