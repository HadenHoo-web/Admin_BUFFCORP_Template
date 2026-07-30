<html>
<head>
    <meta http-equiv="Content-Language" content="vi">
    <meta name="GENERATOR" content="Microsoft FrontPage 5.0">
    <meta name="ProgId" content="FrontPage.Editor.Document">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>HK - Control Panel</title>

    <link rel="stylesheet" type="text/css" href="templates/{skin}/css/{theme}">
    <link rel="stylesheet" type="text/css" href="css/admintool.css">

    <script src="js/commoncheck.js"></script>
    <script src="js/admintool.js"></script>

    <link rel="shortcut icon" href="../favico.png" type="image/x-icon">
    <link rel="icon" href="../favico.png" type="image/x-icon">

    <style>
        body {
            margin: 0;
            font-family: Tahoma, Arial;
        }

        .layout {
            display: flex;
            height: 100vh;
        }

        .left-menu {
            width: 210px;
            background: #ECE9D8;
            border-right: 1px solid #ccc;
            overflow: auto;
        }

        .main-content {
            position: relative;
            flex: 1;
            overflow: auto;
            padding: 6px;
            background: #f5f5f5;
        }

        .main-content.admin-dashboard-shell {
            padding: 0;
            background: #ece9d8;
        }

        /* ===== ACTIVE MENU ===== */
        .ct a.active {
            font-weight: bold;
            color: #c00;
            background: #fff3cd;
            display: block;
            padding: 2px 4px;
        }

        .notify-wrap {
            position: absolute;
            top: 13px;
            right: 28px;
            z-index: 9999;
            font-family: Tahoma, Arial;
        }

        .payroll-wrap {
            display: {PAYROLL_PREVIEW_DISPLAY};
            position: absolute;
            top: 13px;
            right: 56px;
            z-index: 9999;
            font-family: Tahoma, Arial;
        }

        .admin-home-wrap {
            display: {ADMIN_HOME_DISPLAY};
            position: absolute;
            top: 13px;
            right: 84px;
            z-index: 9999;
            font-family: Tahoma, Arial;
        }

        .notify-bell,
        .admin-home-button,
        .payroll-button {
            position: relative;
            width: 24px;
            height: 24px;
            border: 0;
            background: transparent;
            color: #4b4b4b;
            box-shadow: none;
            cursor: pointer;
            padding: 0;
            transition: transform .18s ease, color .18s ease;
        }

        .notify-bell:hover,
        .admin-home-button:hover,
        .payroll-button:hover {
            color: #111;
            animation: topIconPop .42s ease;
        }

        .notify-wrap.has-unread .notify-bell {
            animation: notifyBellShake 1.4s ease-in-out infinite;
            transform-origin: 50% 4px;
        }

        .notify-bell svg {
            width: 22px;
            height: 22px;
            margin-top: 1px;
            filter: drop-shadow(0 1px 1px rgba(0,0,0,.25));
        }

        .payroll-button {
            width: 24px;
            height: 24px;
            border: 0;
            border-radius: 0;
            background: transparent;
        }

        .admin-home-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .admin-home-button svg {
            width: 23px;
            height: 23px;
            margin-top: 0;
            filter: drop-shadow(0 1px 1px rgba(0,0,0,.18));
        }

        .payroll-button svg {
            width: 23px;
            height: 23px;
            margin-top: 0;
            filter: drop-shadow(0 1px 1px rgba(0,0,0,.18));
        }

        .main-content.admin-dashboard-shell .admin-home-wrap,
        .main-content.admin-dashboard-shell .notify-wrap,
        .main-content.admin-dashboard-shell .payroll-wrap {
            top: 24px;
            width: 42px;
            height: 42px;
            border-radius: 22px;
            background: rgba(255, 254, 248, .88);
            border: 1px solid #cfc8b6;
            box-shadow: 0 8px 20px rgba(61,57,39,.16);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-content.admin-dashboard-shell .notify-wrap {
            right: 24px;
        }

        .main-content.admin-dashboard-shell .payroll-wrap {
            right: 78px;
        }

        .main-content.admin-dashboard-shell .admin-home-wrap {
            right: 132px;
        }

        .main-content.admin-dashboard-shell .notify-bell,
        .main-content.admin-dashboard-shell .admin-home-button,
        .main-content.admin-dashboard-shell .payroll-button {
            width: 40px;
            height: 40px;
            color: #253247;
        }

        .main-content.admin-dashboard-shell .notify-bell svg,
        .main-content.admin-dashboard-shell .admin-home-button svg,
        .main-content.admin-dashboard-shell .payroll-button svg {
            width: 21px;
            height: 21px;
            margin: 0;
            filter: none;
        }

        .main-content.admin-dashboard-shell .notify-count {
            top: -10px;
            right: -7px;
            min-width: 21px;
            height: 21px;
            line-height: 20px;
            border: 2px solid #ece9d8;
        }

        .main-content.admin-dashboard-shell .notify-panel {
            top: 58px;
            right: 18px;
            border-color: #cfc8b6;
            background: #fffef8;
            box-shadow: 0 18px 42px rgba(61,57,39,.22);
        }

        .main-content.dashboard-header-icons .notify-wrap,
        .main-content.dashboard-header-icons .admin-home-wrap,
        .main-content.dashboard-header-icons .payroll-wrap {
            top: 24px;
        }

        .main-content .sales-toolbar,
        .main-content .kpi-toolbar,
        .main-content .kpi-head {
            padding-right: 92px !important;
            box-sizing: border-box;
        }

        .main-content .sales-filter,
        .main-content .kpi-filter {
            max-width: calc(100% - 92px);
            box-sizing: border-box;
        }

        .payroll-panel {
            display: block;
            position: fixed;
            top: 48px;
            right: 18px;
            width: 430px;
            max-width: calc(100vw - 24px);
            max-height: calc(100vh - 64px);
            overflow: auto;
            background: #fff;
            border: 1px solid #cfd7e2;
            box-shadow: 0 18px 45px rgba(31,45,61,.24);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(-12px) scale(.97);
            transform-origin: top right;
            transition: opacity .22s ease, transform .22s ease, visibility .22s ease;
            color: #333;
        }

        .payroll-wrap.open .payroll-panel {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0) scale(1);
        }

        .payroll-head {
            position: relative;
            padding: 11px 42px 11px 12px;
            border-bottom: 1px solid #e4e8ee;
            background: #f9fafc;
            color: #202a38;
            font-size: 13px;
            font-weight: bold;
            text-align: center;
        }

        .payroll-close {
            position: absolute;
            top: 6px;
            right: 8px;
            width: 25px;
            height: 25px;
            border: 0;
            background: transparent;
            color: #333;
            font-size: 22px;
            line-height: 22px;
            cursor: pointer;
        }

        .payroll-body {
            padding: 10px;
            font-size: 12px;
        }

        #payroll-content {
            opacity: 1;
            transform: translateY(0);
            transition: opacity .18s ease, transform .18s ease;
        }

        #payroll-content.payroll-content-switching {
            opacity: .35;
            transform: translateY(8px);
        }

        #payroll-content.payroll-content-ready {
            animation: payrollContentIn .24s ease both;
        }

        .payroll-filter {
            padding: 0 0 8px 0;
            text-align: right;
            color: #5a6472;
            font-size: 11px;
        }

        .payroll-employee-filter {
            display: {PAYROLL_ADMIN_DISPLAY};
            float: left;
            text-align: left;
        }

        .payroll-filter select {
            height: 24px;
            border: 1px solid #cbd5e1;
            background: #fff;
            font-size: 11px;
        }

        #payroll-employee {
            max-width: 160px;
        }

        .payroll-loading,
        .payroll-empty,
        .payroll-error {
            padding: 28px 12px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }

        .payroll-error { color: #c62828; }

        .payroll-hero {
            display: block;
            width: 100%;
            border-bottom: 1px solid #e4e8ee;
            padding: 10px 6px 13px 6px;
            box-sizing: border-box;
        }

        .payroll-hero-title {
            color: #4b5563;
            font-size: 15px;
            margin-bottom: 8px;
        }

        .payroll-amount {
            color: #2563eb;
            font-size: 28px;
            line-height: 32px;
            font-weight: bold;
        }

        .payroll-badge {
            display: inline-block;
            margin-top: 6px;
            padding: 2px 7px;
            border: 1px solid #bfdbfe;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 10px;
            font-weight: bold;
        }

        .payroll-summary {
            padding: 10px 6px;
            border-bottom: 1px solid #e4e8ee;
        }

        .payroll-line {
            display: table;
            width: 100%;
            padding: 4px 0;
        }

        .payroll-line span {
            display: table-cell;
        }

        .payroll-line b {
            display: table-cell;
            text-align: right;
            font-weight: normal;
        }

        .payroll-line.strong span,
        .payroll-line.strong b {
            color: #2563eb;
            font-weight: bold;
            font-size: 14px;
        }

        .payroll-money-plus { color: #16a34a !important; font-weight: bold !important; }
        .payroll-money-minus { color: #ef4444 !important; font-weight: bold !important; }

        .payroll-stat-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            padding: 10px 0;
        }

        .payroll-stat {
            border: 1px solid #e2e8f0;
            background: #fff;
            padding: 9px 10px;
            min-height: 62px;
            box-sizing: border-box;
        }

        .payroll-stat-label {
            display: block;
            color: #555;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .payroll-stat-value {
            display: block;
            color: #2563eb;
            font-size: 16px;
            font-weight: bold;
        }

        .payroll-stat small {
            display: block;
            margin-top: 3px;
            color: #777;
        }

        .payroll-progress {
            display: block;
            height: 8px;
            margin-top: 8px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .payroll-progress i {
            display: block;
            height: 8px;
            background: #3b82f6;
            border-radius: 10px;
        }

        .payroll-table-title {
            padding: 8px 8px;
            border: 1px solid #e4e8ee;
            border-bottom: 0;
            background: #f9fafc;
            font-weight: bold;
            color: #333;
        }

        .payroll-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .payroll-table th,
        .payroll-table td {
            border: 1px solid #e4e8ee;
            padding: 7px 8px;
        }

        .payroll-table th {
            background: #f1f5f9;
            color: #4b5563;
            text-align: left;
        }

        .payroll-table th:last-child,
        .payroll-table td:last-child {
            text-align: right;
        }

        .payroll-table-total td {
            color: #2563eb;
            font-weight: bold;
        }

        .payroll-footer {
            padding-top: 10px;
            text-align: center;
        }

        .payroll-updated {
            margin-bottom: 9px;
            color: #777;
            font-size: 11px;
        }

        .payroll-detail-btn {
            display: block;
            width: 100%;
            height: 38px;
            border: 0;
            background: #2855e8;
            color: #fff;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
        }

        .payroll-detail-btn:hover {
            background: #1d4ed8;
        }

        .notify-count {
            position: absolute;
            right: -6px;
            top: -7px;
            min-width: 17px;
            height: 17px;
            padding: 0 4px;
            border-radius: 12px;
            background: #d92332;
            color: #fff;
            font-size: 10px;
            line-height: 17px;
            font-weight: bold;
            text-align: center;
            box-sizing: border-box;
            border: 1px solid #fff;
            transform: scale(0);
            transition: transform .18s ease;
        }

        .notify-count.show {
            transform: scale(1);
        }

        .notify-panel {
            display: block;
            position: fixed;
            top: 48px;
            right: 18px;
            width: 330px;
            max-width: calc(100vw - 24px);
            background: #fff;
            border: 1px solid #d7dee8;
            box-shadow: 0 18px 45px rgba(31,45,61,.24);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(-12px) scale(.97);
            transform-origin: top right;
            transition: opacity .22s ease, transform .22s ease, visibility .22s ease;
        }

        .notify-wrap.open .notify-panel {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0) scale(1);
        }

        .notify-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 10px 12px;
            border-bottom: 1px solid #edf0f5;
            color: #26384d;
            font-size: 13px;
            font-weight: bold;
        }

        .notify-read-all {
            display: none;
            height: 22px;
            padding: 0 8px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #1d4ed8;
            font-size: 11px;
            font-weight: bold;
            cursor: pointer;
        }

        .notify-read-all.show {
            display: inline-block;
        }

        .notify-read-all:hover {
            background: #eef5ff;
        }

        .notify-list {
            display: block;
            max-height: 420px;
            overflow: auto;
        }

        .notify-item {
            display: block;
            padding: 10px 12px;
            border-bottom: 1px solid #f0f2f5;
            color: #2b2f36;
            text-decoration: none;
            animation: notifyIn .22s ease both;
        }

        .notify-item:hover {
            background: #f6f9fc;
        }

        .notify-item.unread {
            background: #fff8e8;
        }

        .notify-title {
            display: block;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 4px;
            color: #172334;
        }

        .notify-state {
            float: right;
            padding: 1px 6px;
            border-radius: 10px;
            background: #e9f0f8;
            color: #5a6b7f;
            font-size: 10px;
            font-weight: normal;
        }

        .notify-item.unread .notify-state {
            background: #d92332;
            color: #fff;
        }

        .notify-message {
            display: block;
            font-size: 11px;
            line-height: 16px;
            color: #4d5b6a;
        }

        .notify-action {
            display: inline-block;
            margin-top: 6px;
            padding: 2px 7px;
            border: 1px solid #ccd7e4;
            background: #f7f9fc;
            color: #204d7a;
            font-size: 10px;
            cursor: pointer;
        }

        .notify-action:hover {
            background: #eaf2fb;
        }

        .notify-time {
            display: block;
            margin-top: 5px;
            font-size: 10px;
            color: #8a96a3;
        }

        .notify-empty {
            display: block;
            padding: 18px 12px;
            text-align: center;
            color: #7a8794;
            font-size: 12px;
        }

        @keyframes notifyIn {
            from { opacity: 0; transform: translateY(7px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes notifyBellShake {
            0%, 42%, 100% { transform: rotate(0deg); }
            6% { transform: rotate(12deg); }
            12% { transform: rotate(-10deg); }
            18% { transform: rotate(8deg); }
            24% { transform: rotate(-6deg); }
            30% { transform: rotate(4deg); }
            36% { transform: rotate(-2deg); }
        }

        @keyframes topIconPop {
            0% { transform: translateY(0) scale(1); }
            45% { transform: translateY(-2px) scale(1.16); }
            100% { transform: translateY(0) scale(1); }
        }

        @keyframes payrollContentIn {
            from { opacity: .2; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>
<script type="text/javascript">
(function () {
    if (window.name !== 'main') {
        window.name = 'main';
    }

    function isMainTarget(el) {
        if (!el || !el.getAttribute) return false;
        var t = el.getAttribute('target');
        return t && String(t).toLowerCase() === 'main';
    }

    function findAnchor(node) {
        while (node && node !== document) {
            if (node.tagName && node.tagName.toLowerCase() === 'a') return node;
            node = node.parentNode;
        }
        return null;
    }

    function normalizeMainTargets() {
        var i, el, t;
        var tags = ['a', 'form', 'base'];
        for (var k = 0; k < tags.length; k++) {
            var list = document.getElementsByTagName(tags[k]);
            for (i = 0; i < list.length; i++) {
                el = list[i];
                if (!el || !el.getAttribute) continue;
                t = el.getAttribute('target');
                if (t && String(t).toLowerCase() === 'main') {
                    el.removeAttribute('target');
                }
            }
        }
    }

    if (document.addEventListener) {
        document.addEventListener('click', function (e) {
            var a = findAnchor(e.target);
            if (!a || !isMainTarget(a)) return;
            a.removeAttribute('target');
        }, true);

        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (!form || !form.tagName || form.tagName.toLowerCase() !== 'form') return;
            if (!isMainTarget(form)) return;
            form.removeAttribute('target');
        }, true);
    } else if (document.attachEvent) {
        document.attachEvent('onclick', function () {
            var e = window.event;
            var a = findAnchor(e.srcElement);
            if (!a || !isMainTarget(a)) return;
            a.removeAttribute('target');
        });

        document.attachEvent('onsubmit', function () {
            var e = window.event;
            var form = e.srcElement;
            if (!form || !form.tagName || form.tagName.toLowerCase() !== 'form') return;
            if (!isMainTarget(form)) return;
            form.removeAttribute('target');
        });
    }

    if (window.HTMLFormElement && HTMLFormElement.prototype && HTMLFormElement.prototype.submit) {
        var nativeSubmit = HTMLFormElement.prototype.submit;
        HTMLFormElement.prototype.submit = function () {
            if (isMainTarget(this)) {
                this.removeAttribute('target');
            }
            return nativeSubmit.apply(this, arguments);
        };
    }

    normalizeMainTargets();
    if (window.setInterval) {
        setInterval(normalizeMainTargets, 1000);
    }
})();
</script>

<script type="text/javascript">
(function () {
    function initNotify() {
    var wrap = document.getElementById('notify-wrap');
    var bell = document.getElementById('notify-bell');
    var countEl = document.getElementById('notify-count');
    var listEl = document.getElementById('notify-list');
    var panel = document.getElementById('notify-panel');
    var readAllBtn = document.getElementById('notify-read-all');
    if (!wrap || !bell || !countEl || !listEl) return;

    function htmlEscape(text) {
        text = text == null ? '' : String(text);
        return text.replace(/[&<>"']/g, function (ch) {
            switch (ch) {
                case '&': return '&amp;';
                case '<': return '&lt;';
                case '>': return '&gt;';
                case '"': return '&quot;';
                case "'": return '&#039;';
                default: return ch;
            }
        });
    }

    function setCount(total) {
        total = parseInt(total, 10) || 0;
        countEl.innerHTML = total > 99 ? '99+' : total;
        if (total > 0) {
            countEl.className = 'notify-count show';
            if (readAllBtn) readAllBtn.className = 'notify-read-all show';
            if (wrap.className.indexOf('has-unread') < 0) wrap.className += ' has-unread';
        } else {
            countEl.className = 'notify-count';
            if (readAllBtn) readAllBtn.className = 'notify-read-all';
            wrap.className = wrap.className.replace(/ ?has-unread/g, '');
        }
    }

    function render(data) {
        setCount(data.unread);
        if (!data.items || !data.items.length) {
            listEl.innerHTML = '<div class="notify-empty">Chưa có thông báo</div>';
            return;
        }
        var html = '';
        for (var i = 0; i < data.items.length; i++) {
            var item = data.items[i];
            var isRead = parseInt(item.is_read, 10) ? 1 : 0;
            var itemClass = isRead ? 'notify-item' : 'notify-item unread';
            var stateText = isRead ? 'Đã đọc' : 'Chưa đọc';
            html += '<div class="' + itemClass + '" data-id="' + htmlEscape(item.id) + '" data-link="' + htmlEscape(item.link || '') + '">'
                + '<div class="notify-title"><span class="notify-state">' + stateText + '</span>' + htmlEscape(item.title) + '</div>'
                + '<div class="notify-message">' + htmlEscape(item.message) + '</div>'
                + '<div class="notify-time">' + htmlEscape(item.created_date) + '</div>'
                + (isRead ? '' : '<button type="button" class="notify-action" data-id="' + htmlEscape(item.id) + '">Đánh dấu đã đọc</button>')
                + '</div>';
        }
        listEl.innerHTML = html;
    }

    function load(action, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'notifications.php?action=' + encodeURIComponent(action || 'list') + '&_=' + new Date().getTime(), true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4 || xhr.status !== 200) return;
            try {
                var data = JSON.parse(xhr.responseText);
                if (data && data.ok) render(data);
                if (callback) callback(data);
            } catch (e) {
                return false;
            }
        };
        xhr.send(null);
    }

    function markOne(id, callback) {
        if (!id) return;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'notifications.php?action=read_one&id=' + encodeURIComponent(id) + '&_=' + new Date().getTime(), true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4 || xhr.status !== 200) return;
            if (callback) callback();
        };
        xhr.send(null);
    }

    function markAll() {
        setCount(0);
        load('read');
    }

    function positionPanel() {
        if (!panel || !bell.getBoundingClientRect) return;
        var rect = bell.getBoundingClientRect();
        var width = 330;
        var left = rect.right - width;
        if (left < 8) left = 8;
        panel.style.left = left + 'px';
        panel.style.right = 'auto';
        panel.style.top = (rect.bottom + 10) + 'px';
    }

    function openNotify(e) {
        if (e && e.stopPropagation) e.stopPropagation();
        var payrollWrap = document.getElementById('payroll-wrap');
        if (payrollWrap) payrollWrap.className = payrollWrap.className.replace(/ ?open/g, '');
        positionPanel();
        wrap.className = wrap.className.indexOf('open') >= 0 ? wrap.className.replace(/ ?open/g, '') : wrap.className + ' open';
        if (wrap.className.indexOf('open') >= 0) {
            load('list');
        }
    }

    function closeNotify() {
        wrap.className = wrap.className.replace(/ ?open/g, '');
    }

    if (bell.addEventListener) bell.addEventListener('click', openNotify, false);
    else bell.attachEvent && bell.attachEvent('onclick', openNotify);

    if (readAllBtn) {
        if (readAllBtn.addEventListener) readAllBtn.addEventListener('click', function (e) {
            if (e && e.stopPropagation) e.stopPropagation();
            markAll();
            return false;
        }, false);
        else readAllBtn.attachEvent && readAllBtn.attachEvent('onclick', function () {
            markAll();
            return false;
        });
    }

    if (document.addEventListener) document.addEventListener('click', closeNotify, false);
    else document.attachEvent && document.attachEvent('onclick', closeNotify);

    if (window.addEventListener) window.addEventListener('resize', positionPanel, false);

    if (panel) {
        panel.onclick = function (e) {
            if (e && e.stopPropagation) e.stopPropagation();
            var target = e ? (e.target || e.srcElement) : null;
            var node = target;
            while (node && node !== panel && (!node.className || String(node.className).indexOf('notify-item') < 0)) {
                if (node.className && String(node.className).indexOf('notify-action') >= 0) break;
                node = node.parentNode;
            }
            if (target && target.className && String(target.className).indexOf('notify-action') >= 0) {
                markOne(target.getAttribute('data-id'), function () { load('list'); });
                return false;
            }
            if (node && node.className && String(node.className).indexOf('notify-item') >= 0) {
                var id = node.getAttribute('data-id');
                var link = node.getAttribute('data-link');
                markOne(id, function () {
                    if (link) window.location.href = link;
                });
                return false;
            }
        };
    }

    load('list');
    setInterval(function () { load('list'); }, 30000);
    }

    if (document.readyState === 'loading') {
        if (document.addEventListener) document.addEventListener('DOMContentLoaded', initNotify);
        else window.attachEvent && window.attachEvent('onload', initNotify);
    } else {
        initNotify();
    }
})();
</script>

<script type="text/javascript">
(function () {
    function initPayrollRealtime() {
        var wrap = document.getElementById('payroll-wrap');
        var button = document.getElementById('payroll-button');
        var panel = document.getElementById('payroll-panel');
        var body = document.getElementById('payroll-content');
        var closeBtn = document.getElementById('payroll-close');
        var monthEl = document.getElementById('payroll-month');
        var yearEl = document.getElementById('payroll-year');
        var employeeEl = document.getElementById('payroll-employee');
        if (!wrap || !button || !panel || !body) return;

        var detailUrl = '';
        var loaded = false;
        var employeesLoaded = false;
        var canChooseEmployee = '{PAYROLL_ADMIN_DISPLAY}' != 'none';
        var switchTimer = null;
        var payrollRequestSeq = 0;
        if (monthEl) monthEl.value = '{CURRENT_MONTH}';
        if (yearEl) yearEl.value = '{CURRENT_YEAR}';

        function htmlEscape(text) {
            text = text == null ? '' : String(text);
            return text.replace(/[&<>"']/g, function (ch) {
                switch (ch) {
                    case '&': return '&amp;';
                    case '<': return '&lt;';
                    case '>': return '&gt;';
                    case '"': return '&quot;';
                    case "'": return '&#039;';
                    default: return ch;
                }
            });
        }

        function formatMoney(value) {
            value = parseInt(value, 10) || 0;
            var sign = value < 0 ? '-' : '';
            var text = String(Math.abs(value));
            var parts = [];
            while (text.length > 3) {
                parts.unshift(text.substr(text.length - 3));
                text = text.substr(0, text.length - 3);
            }
            if (text.length) parts.unshift(text);
            return sign + parts.join('.') + ' đ';
        }

        function formatHour(value) {
            value = parseFloat(value) || 0;
            if (Math.abs(value - Math.round(value)) < 0.001) return String(Math.round(value));
            return String(Math.round(value * 10) / 10);
        }

        function percent(value, total) {
            value = parseFloat(value) || 0;
            total = parseFloat(total) || 0;
            if (total <= 0) return 0;
            var p = Math.round(value * 100 / total);
            if (p < 0) p = 0;
            if (p > 100) p = 100;
            return p;
        }

        function formatPenalty(value) {
            value = Math.abs(parseInt(value, 10) || 0);
            return value > 0 ? '-' + formatMoney(value) : formatMoney(0);
        }

        function showLoading() {
            body.className = 'payroll-content-switching';
            body.innerHTML = '<div class="payroll-loading">Đang tải dữ liệu lương...</div>';
        }

        function showError() {
            body.innerHTML = '<div class="payroll-error">Không thể tải dữ liệu lương, vui lòng thử lại</div>';
            body.className = 'payroll-content-ready';
        }

        function showEmpty(message) {
            body.innerHTML = '<div class="payroll-empty">' + htmlEscape(message || 'Chưa có dữ liệu lương trong tháng này') + '</div>';
            body.className = 'payroll-content-ready';
        }

        function render(data) {
            if (!data || !data.ok) {
                showError();
                return;
            }
            if (data.empty) {
                showEmpty(data.message);
                return;
            }

            detailUrl = data.detail_url || '';
            var workPercent = percent(data.working_days, data.total_working_days);
            var hourPercent = percent(data.working_hours, (parseFloat(data.total_working_days) || 26) * 8);
            var netPercent = percent(data.net_salary, data.estimated_salary);
            var rows = [
                ['Lương theo công đã chấm', data.base_earned || 0, ''],
                ['Chuyên cần', data.bonus, data.bonus > 0 ? 'payroll-money-plus' : ''],
                ['Hoa hồng', data.commission || 0, (parseInt(data.commission, 10) || 0) > 0 ? 'payroll-money-plus' : '']
            ];
            rows.push(['Phạt', -Math.abs(parseInt(data.penalty, 10) || 0), 'payroll-money-minus']);
            rows.push(['Tiền trừ khi nghỉ', -Math.abs(parseInt(data.leave_deduct_amount, 10) || 0), 'payroll-money-minus']);
            rows.push(['Đã kiếm được đến hiện tại', data.net_salary, 'payroll-money-total']);

            var html = ''
                + '<div class="payroll-hero">'
                + '<div class="payroll-hero-text">'
                + '<div class="payroll-hero-title">Từ đầu tháng đến hiện tại bạn đã kiếm được</div>'
                + '<div class="payroll-amount">' + formatMoney(data.earned_to_date || data.today_earned) + '</div>'
                + '<span class="payroll-badge">Realtime theo chấm công</span>'
                + '</div>'
                + '</div>'
                + '<div class="payroll-summary">'
                + '<div class="payroll-line strong"><span>Đã kiếm được đến hiện tại</span><b>' + formatMoney(data.estimated_salary) + '</b></div>'
                + '<div class="payroll-line"><span>Lương cơ bản</span><b>' + formatMoney(data.base_salary) + '</b></div>'
                + '<div class="payroll-line"><span>Ngày công đã chấm</span><b>' + formatHour(data.working_days) + ' / ' + htmlEscape(data.total_working_days) + ' ngày</b></div>'
                + '<div class="payroll-line"><span>Phép còn lại tháng này</span><b>' + formatHour(data.leave_remain) + ' ngày</b></div>'
                + '<div class="payroll-line"><span>Lương theo công đã chấm</span><b>' + formatMoney(data.base_earned || 0) + '</b></div>'
                + '<div class="payroll-line"><span>Chuyên cần</span><b class="payroll-money-plus">' + formatMoney(data.bonus) + '</b></div>'
                + '<div class="payroll-line"><span>Hoa hồng</span><b class="payroll-money-plus">' + formatMoney(data.commission || 0) + '</b></div>'
                + '<div class="payroll-line"><span>Phạt</span><b class="payroll-money-minus">' + formatPenalty(data.penalty) + '</b></div>'
                + '<div class="payroll-line"><span>Tiền trừ khi nghỉ</span><b class="payroll-money-minus">' + formatPenalty(data.leave_deduct_amount) + '</b></div>'
                + '</div>'
                + '<div class="payroll-stat-grid">'
                + '<div class="payroll-stat"><span class="payroll-stat-label">Ngày công</span><span class="payroll-stat-value">' + formatHour(data.working_days) + ' / ' + htmlEscape(data.total_working_days) + '</span><small>(' + workPercent + '%)</small><span class="payroll-progress"><i style="width:' + workPercent + '%;background:#65c85f"></i></span></div>'
                + '<div class="payroll-stat"><span class="payroll-stat-label">Số giờ làm</span><span class="payroll-stat-value">' + formatHour(data.working_hours) + 'h</span><small>(' + hourPercent + '%)</small><span class="payroll-progress"><i style="width:' + hourPercent + '%"></i></span></div>'
                + '<div class="payroll-stat"><span class="payroll-stat-label">Đã kiếm được</span><span class="payroll-stat-value" style="color:#22a63a">' + formatMoney(data.net_salary) + '</span><span class="payroll-progress"><i style="width:' + netPercent + '%;background:#65c85f"></i></span></div>'
                + '</div>'
                + '<div class="payroll-table-title">CHI TIẾT THU NHẬP</div>'
                + '<table class="payroll-table"><thead><tr><th>Khoản mục</th><th>Số tiền</th></tr></thead><tbody>';

            for (var i = 0; i < rows.length; i++) {
                var itemClass = rows[i][2] || '';
                var rowClass = itemClass == 'payroll-money-total' ? ' class="payroll-table-total"' : '';
                var cellClass = itemClass == 'payroll-money-total' ? '' : itemClass;
                html += '<tr' + rowClass + '><td>' + htmlEscape(rows[i][0]) + '</td><td class="' + cellClass + '">' + formatMoney(rows[i][1]) + '</td></tr>';
            }

            html += '</tbody></table>'
                + '<div class="payroll-footer">'
                + '<div class="payroll-updated">Cập nhật lần cuối: ' + htmlEscape(data.last_updated_at || '') + '</div>'
                + '<button type="button" class="payroll-detail-btn" id="payroll-detail-btn">Xem chi tiết bảng lương</button>'
                + '</div>';

            body.innerHTML = html;
            body.className = 'payroll-content-ready';
            var detailBtn = document.getElementById('payroll-detail-btn');
            if (detailBtn) {
                detailBtn.onclick = function () {
                    if (detailUrl) window.location.href = detailUrl;
                };
            }
        }

        function loadPayroll() {
            if (switchTimer) {
                clearTimeout(switchTimer);
                switchTimer = null;
            }
            payrollRequestSeq++;
            var requestSeq = payrollRequestSeq;
            body.className = 'payroll-content-switching';
            var month = monthEl ? monthEl.value : '';
            var year = yearEl ? yearEl.value : '';
            var employeeId = employeeEl ? employeeEl.value : '';
            switchTimer = setTimeout(function () {
                showLoading();
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '../api/salary/realtime.php?month=' + encodeURIComponent(month) + '&year=' + encodeURIComponent(year) + '&employee_id=' + encodeURIComponent(employeeId) + '&_=' + new Date().getTime(), true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState !== 4) return;
                    if (requestSeq != payrollRequestSeq) return;
                    if (xhr.status !== 200) {
                        showError();
                        return;
                    }
                    try {
                        render(JSON.parse(xhr.responseText));
                    } catch (e) {
                        showError();
                    }
                };
                xhr.send(null);
            }, 140);
        }

        function loadEmployees(callback) {
            if (!canChooseEmployee || !employeeEl || employeesLoaded) {
                if (callback) callback();
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('GET', '../api/salary/realtime.php?action=employees&_=' + new Date().getTime(), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState !== 4) return;
                employeesLoaded = true;
                if (xhr.status !== 200) {
                    if (callback) callback();
                    return;
                }

                try {
                    var data = JSON.parse(xhr.responseText);
                    if (!data || !data.ok || !data.items) {
                        if (callback) callback();
                        return;
                    }

                    var html = '';
                    var selectedId = '';
                    for (var i = 0; i < data.items.length; i++) {
                        if (!selectedId && parseInt(data.items[i].employee_id, 10) == parseInt(data.login_id, 10)) {
                            selectedId = String(data.items[i].employee_id);
                        }
                    }
                    if (!selectedId && data.items.length) selectedId = String(data.items[0].employee_id);

                    for (var j = 0; j < data.items.length; j++) {
                        var id = String(data.items[j].employee_id);
                        html += '<option value="' + htmlEscape(id) + '"' + (id == selectedId ? ' selected' : '') + '>' + htmlEscape(data.items[j].employee_name) + '</option>';
                    }
                    employeeEl.innerHTML = html || '<option value="">Không có nhân viên</option>';
                    if (callback) callback();
                } catch (e) {
                    if (callback) callback();
                }
            };
            xhr.send(null);
        }

        function positionPanel() {
            if (!button.getBoundingClientRect) return;
            var rect = button.getBoundingClientRect();
            var width = 430;
            var left = rect.right - width;
            if (left < 8) left = 8;
            panel.style.left = left + 'px';
            panel.style.right = 'auto';
            panel.style.top = (rect.bottom + 7) + 'px';
        }

        function closePanel() {
            wrap.className = wrap.className.replace(/ ?open/g, '');
        }

        function togglePanel(e) {
            if (e && e.stopPropagation) e.stopPropagation();
            var notifyWrap = document.getElementById('notify-wrap');
            if (notifyWrap) notifyWrap.className = notifyWrap.className.replace(/ ?open/g, '');
            positionPanel();
            if (wrap.className.indexOf('open') >= 0) {
                closePanel();
                return;
            }
            wrap.className += ' open';
            if (!loaded) {
                loaded = true;
                loadEmployees(loadPayroll);
            }
        }

        if (button.addEventListener) button.addEventListener('click', togglePanel, false);
        else button.attachEvent && button.attachEvent('onclick', togglePanel);

        if (closeBtn) closeBtn.onclick = function (e) {
            if (e && e.stopPropagation) e.stopPropagation();
            closePanel();
        };

        panel.onclick = function (e) {
            if (e && e.stopPropagation) e.stopPropagation();
        };

        if (monthEl) monthEl.onchange = loadPayroll;
        if (yearEl) yearEl.onchange = loadPayroll;
        if (employeeEl) employeeEl.onchange = loadPayroll;

        if (document.addEventListener) document.addEventListener('click', closePanel, false);
        else document.attachEvent && document.attachEvent('onclick', closePanel);
        if (window.addEventListener) window.addEventListener('resize', positionPanel, false);
    }

    if (document.readyState === 'loading') {
        if (document.addEventListener) document.addEventListener('DOMContentLoaded', initPayrollRealtime);
        else window.attachEvent && window.attachEvent('onload', initPayrollRealtime);
    } else {
        initPayrollRealtime();
    }
})();
</script>

<div class="layout">
    <div class="left-menu">
        {LEFT_MENU}
    </div>

    <div class="{MAIN_CONTENT_CLASS}" id="main-content">
        <div class="admin-home-wrap" id="admin-home-wrap">
            <a class="admin-home-button" href="main.php?option=common_lists/admin_dashboard&mode=dashboard&l={LANGUAGEID}" title="Về Dashboard Admin">
                <svg viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 11.5 12 4l9 7.5"></path>
                    <path d="M5.5 10.5V20h13v-9.5"></path>
                    <path d="M9.5 20v-6h5v6"></path>
                </svg>
            </a>
        </div>
        <div class="payroll-wrap" id="payroll-wrap">
            <button type="button" class="payroll-button" id="payroll-button" title="Bảng lương real time">
                <svg viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="1.8">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 6v12"></path>
                    <path d="M16 8.5c-.8-.8-1.9-1.2-3.3-1.2-1.7 0-3 .8-3 2.2 0 1.5 1.2 2 3.1 2.4 1.9.4 3 .9 3 2.4 0 1.4-1.3 2.4-3.2 2.4-1.5 0-2.8-.5-3.7-1.4"></path>
                </svg>
            </button>
            <div class="payroll-panel" id="payroll-panel">
                <div class="payroll-head">
                    BẢNG LƯƠNG REAL TIME
                    <button type="button" class="payroll-close" id="payroll-close" title="Đóng">X</button>
                </div>
                <div class="payroll-body">
                    <div class="payroll-filter">
                        <span class="payroll-employee-filter">
                            Nhân viên
                            <select id="payroll-employee">
                                <option value="">Đang tải...</option>
                            </select>
                        </span>
                        Tháng
                        <select id="payroll-month">
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                        Năm
                        <select id="payroll-year">
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                        </select>
                    </div>
                    <div id="payroll-content">
                        <div class="payroll-loading">Đang tải dữ liệu lương...</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="{NOTIFICATION_WRAP_CLASS}" id="notify-wrap">
            <button type="button" class="notify-bell" id="notify-bell" title="Thông báo">
                <svg viewBox="0 0 24 24" fill="#e5e5e5" stroke="currentColor" stroke-width="1.8">
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="{NOTIFICATION_CLASS}" id="notify-count">{NOTIFICATION_COUNT}</span>
            </button>
            <div class="notify-panel" id="notify-panel">
                <div class="notify-head">
                    <span>Thông báo</span>
                    <button type="button" class="notify-read-all" id="notify-read-all">Đọc hết</button>
                </div>
                <div class="notify-list" id="notify-list">
                    <div class="notify-empty">Đang tải...</div>
                </div>
            </div>
        </div>
        {MAIN_CONTENT}
        <script type="text/javascript">
        (function () {
            var main = document.getElementById('main-content');
            if (!main) return;
            if (main.getElementsByClassName && (
                main.getElementsByClassName('sales-page').length ||
                main.getElementsByClassName('kpi-page').length ||
                main.getElementsByClassName('kpi-report').length
            )) {
                main.className += ' dashboard-header-icons';
            }
            if (main.getElementsByClassName && main.getElementsByClassName('admin-dashboard').length && main.className.indexOf('admin-dashboard-shell') < 0) {
                main.className += ' admin-dashboard-shell';
            }
        })();
        </script>
    </div>
</div>
</body>
</html>
