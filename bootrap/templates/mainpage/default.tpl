<html>
<head>
    <meta http-equiv="Content-Language" content="vi">
    <meta name="GENERATOR" content="Microsoft FrontPage 5.0">
    <meta name="ProgId" content="FrontPage.Editor.Document">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BUFFCORP - Operations Hub</title>

    <script type="text/javascript">
        (function () {
            var root = document.documentElement;
            function addClass(name) {
                if ((' ' + root.className + ' ').indexOf(' ' + name + ' ') < 0) {
                    root.className += (root.className ? ' ' : '') + name;
                }
            }
            try {
                if (sessionStorage.getItem('buffcorp-sidebar-collapsed') === '1') addClass('buffcorp-preload-sidebar-collapsed');
            } catch (e) { /* storage unavailable */ }
            try {
                if (sessionStorage.getItem('buffcorp-route-loading') === '1') addClass('buffcorp-preload-route-loading');
            } catch (e) { /* storage unavailable */ }
        })();
    </script>

    <link rel="stylesheet" type="text/css" href="templates/{skin}/css/{theme}">
    <link rel="stylesheet" type="text/css" href="css/admintool.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">

    <script src="js/commoncheck.js"></script>
    <script src="js/admintool.js"></script>

    <link rel="shortcut icon" href="../favico.png" type="image/x-icon">
    <link rel="icon" href="../favico.png" type="image/x-icon">

    <style>
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        body {
            margin: 0;
            font-family: Tahoma, Arial;
        }

        .layout {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .left-menu {
            width: 296px;
            flex: 0 0 296px;
            background: #ECE9D8;
            border-right: 1px solid #ccc;
            overflow: hidden !important;
        }

        html.buffcorp-preload-sidebar-collapsed .layout.sidebar-initializing .left-menu {
            width: 68px !important;
            flex: 0 0 68px !important;
        }

        html.buffcorp-preload-sidebar-collapsed .layout.sidebar-initializing .buffcorp-brand-copy,
        html.buffcorp-preload-sidebar-collapsed .layout.sidebar-initializing .buffcorp-collapse,
        html.buffcorp-preload-sidebar-collapsed .layout.sidebar-initializing .buffcorp-section-label,
        html.buffcorp-preload-sidebar-collapsed .layout.sidebar-initializing .app-parent-label,
        html.buffcorp-preload-sidebar-collapsed .layout.sidebar-initializing .app-parent-chevron,
        html.buffcorp-preload-sidebar-collapsed .layout.sidebar-initializing .app-nav-badge,
        html.buffcorp-preload-sidebar-collapsed .layout.sidebar-initializing .sidebar-support-text {
            display: none !important;
        }

        html.buffcorp-preload-route-loading .layout.sidebar-initializing .left-menu,
        html.buffcorp-preload-route-loading .layout.sidebar-initializing .main-content {
            opacity: 0;
        }

        @media (max-width: 1200px) and (min-width: 621px) {
            .left-menu { width: 280px !important; flex-basis: 280px !important; }
        }

        @media (max-width: 620px) {
            .left-menu { width: 280px !important; flex-basis: 280px !important; }
        }

        .main-content {
            position: relative;
            flex: 1;
            min-width: 0;
            overflow: hidden;
            padding: 0;
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
            z-index: auto;
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

        .payroll-wrap.open {
            z-index: 30000;
        }

        .admin-home-wrap {
            display: {ADMIN_HOME_DISPLAY};
            position: absolute;
            top: 13px;
            right: 84px;
            z-index: 9999;
            font-family: Tahoma, Arial;
        }

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

        .admin-home-button:hover,
        .payroll-button:hover {
            color: #111;
            animation: topIconPop .42s ease;
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

        .main-content.admin-dashboard-shell .admin-home-button,
        .main-content.admin-dashboard-shell .payroll-button {
            width: 40px;
            height: 40px;
            color: #253247;
        }

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
            display: none;
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
            opacity: 0;
            transform: scale(.72);
            transition: transform .18s ease;
        }

        .notify-count.show {
            display: block;
            opacity: 1;
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

        :root {
            --buff-bg: #f4f8fc;
            --buff-surface: #fff;
            --buff-text: #17324d;
            --buff-muted: #687b91;
            --buff-line: #d9e6f3;
            --buff-brand: #2e6cbf;
            --buff-brand-dark: #1f559c;
            --buff-shadow: 0 8px 24px rgba(46,108,191,.08), 0 2px 6px rgba(23,50,77,.04);
        }

        body {
            color: var(--buff-text);
            background: var(--buff-bg);
            font-family: Manrope, "Segoe UI", Arial, sans-serif;
        }

        .main-content,
        .main-content.admin-dashboard-shell {
            display: flex;
            min-width: 0;
            flex-direction: column;
            overflow: hidden;
            padding: 0;
            background: var(--buff-bg);
        }

        .buffcorp-topbar {
            position: relative;
            z-index: 100;
            display: flex;
            min-height: 72px;
            flex: 0 0 72px;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 0 28px;
            border-bottom: 1px solid var(--buff-line);
            background: var(--buff-surface);
            box-sizing: border-box;
        }

        .buffcorp-page-title {
            min-width: 0;
            color: var(--buff-text);
            color: #123f70;
            font-size: 18px;
            font-weight: 700;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .buffcorp-top-actions,
        .buffcorp-user-toggle {
            display: flex;
            align-items: center;
        }

        .buffcorp-top-actions { gap: 9px; }
        .buffcorp-user-menu { position: relative; margin-left: 3px; }
        .buffcorp-user-toggle {
            min-height: 44px;
            gap: 9px;
            padding: 4px;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: inherit;
            cursor: pointer;
            text-align: left;
        }
        .buffcorp-user-toggle:hover,
        .buffcorp-user-menu.open .buffcorp-user-toggle { background: #f2f7fd; }
        .buffcorp-user-toggle:focus-visible,
        .buffcorp-user-dropdown a:focus-visible { outline: 2px solid var(--buff-brand); outline-offset: 2px; }
        .buffcorp-avatar {
            display: grid;
            width: 36px;
            height: 36px;
            place-items: center;
            border-radius: 50%;
            background: #dceafa;
            color: var(--buff-brand-dark);
            font-size: 12px;
            font-weight: 800;
        }

        .buffcorp-user-copy strong,
        .buffcorp-user-copy small { display: block; }
        .buffcorp-user-copy strong { color: var(--buff-text); font-size: 12px; }
        .buffcorp-user-copy small { margin-top: 2px; color: var(--buff-muted); font-size: 10px; }
        .buffcorp-user-chevron { width: 16px; height: 16px; color: var(--buff-muted); transition: transform .18s ease; }
        .buffcorp-user-menu.open .buffcorp-user-chevron { transform: rotate(180deg); }
        .buffcorp-user-dropdown {
            position: absolute;
            z-index: 160;
            top: calc(100% + 8px);
            right: 0;
            width: 248px;
            padding: 8px;
            border: 1px solid var(--buff-line);
            border-radius: 14px;
            background: var(--buff-surface);
            box-shadow: 0 14px 30px rgba(18, 63, 112, .16);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(-5px);
            transition: opacity .18s ease, transform .18s ease, visibility .18s ease;
            box-sizing: border-box;
        }
        .buffcorp-user-menu.open .buffcorp-user-dropdown { opacity: 1; visibility: visible; pointer-events: auto; transform: translateY(0); }
        .buffcorp-user-summary { padding: 7px 9px 10px; }
        .buffcorp-user-summary strong,
        .buffcorp-user-summary small { display: block; }
        .buffcorp-user-summary strong { color: var(--buff-text); font-size: 13px; }
        .buffcorp-user-summary small { margin-top: 3px; color: var(--buff-muted); font-size: 11px; }
        .buffcorp-user-dropdown a {
            display: flex;
            min-height: 40px;
            align-items: center;
            gap: 10px;
            padding: 0 10px;
            border-radius: 9px;
            color: var(--buff-text);
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }
        .buffcorp-user-dropdown a:hover { background: #eef5ff; color: var(--buff-brand-dark); }
        .buffcorp-user-dropdown a svg { width: 19px; height: 19px; color: #6d819a; flex: 0 0 auto; }
        .buffcorp-user-dropdown .buffcorp-user-logout { color: #c93737; }
        .buffcorp-user-dropdown .buffcorp-user-logout:hover { background: #fff2f2; color: #b42318; }
        .buffcorp-user-divider { height: 1px; margin: 6px 2px; background: var(--buff-line); }

        .buffcorp-global-search {
            position: relative;
            display: flex;
            width: 310px;
            height: 40px;
            align-items: center;
            gap: 8px;
            padding: 0 11px;
            border: 1px solid var(--buff-line);
            border-radius: 8px;
            background: var(--buff-surface);
            box-sizing: border-box;
        }

        .buffcorp-global-search:focus-within {
            border-color: #8db7df;
            box-shadow: 0 0 0 3px rgba(46,108,191,.12);
        }

        .buffcorp-global-search svg {
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
            color: var(--buff-muted);
        }

        .buffcorp-global-search input {
            min-width: 0;
            width: 100%;
            height: 36px;
            padding: 0;
            border: 0;
            outline: 0;
            background: transparent;
            color: #111;
            font: 12px/36px Manrope, "Segoe UI", Arial, sans-serif;
        }

        .buffcorp-search-results {
            position: absolute;
            top: 46px;
            right: 0;
            left: 0;
            display: none;
            max-height: 320px;
            overflow: auto;
            padding: 6px;
            border: 1px solid var(--buff-line);
            border-radius: 8px;
            background: var(--buff-surface);
            box-shadow: 0 15px 35px rgba(16,24,40,.16);
        }

        .buffcorp-search-results.open { display: block; }
        .buffcorp-search-results a,
        .buffcorp-search-empty {
            display: block;
            padding: 9px;
            border-radius: 6px;
            color: var(--buff-text);
            font: 12px/18px Manrope, "Segoe UI", Arial, sans-serif;
            text-decoration: none;
        }
        .buffcorp-search-results a:hover { background: #f4f8fc; color: var(--buff-brand); }
        .buffcorp-search-empty { color: var(--buff-muted); text-align: center; }

        .buffcorp-top-actions .admin-home-wrap,
        .buffcorp-top-actions .payroll-wrap,
        .buffcorp-top-actions .notify-wrap,
        .main-content.admin-dashboard-shell .buffcorp-top-actions .admin-home-wrap,
        .main-content.admin-dashboard-shell .buffcorp-top-actions .payroll-wrap,
        .main-content.admin-dashboard-shell .buffcorp-top-actions .notify-wrap {
            position: relative;
            top: auto;
            right: auto;
            display: flex;
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            border: 1px solid var(--buff-line);
            border-radius: 8px;
            background: var(--buff-surface);
            box-shadow: none;
            align-items: center;
            justify-content: center;
            font-family: inherit;
        }

        .buffcorp-top-actions .payroll-wrap.open {
            z-index: 30000;
        }

        .main-content.dashboard-header-icons .buffcorp-top-actions .admin-home-wrap,
        .main-content.dashboard-header-icons .buffcorp-top-actions .payroll-wrap,
        .main-content.dashboard-header-icons .buffcorp-top-actions .notify-wrap {
            top: auto;
            right: auto;
        }

        .main-content.dashboard-header-icons .buffcorp-top-actions .admin-home-wrap,
        .main-content.dashboard-header-icons .buffcorp-top-actions .payroll-wrap,
        .main-content.dashboard-header-icons .buffcorp-top-actions .notify-wrap,
        .main-content.dashboard-header-icons .buffcorp-top-actions .admin-home-button,
        .main-content.dashboard-header-icons .buffcorp-top-actions .payroll-button,
        .main-content.dashboard-header-icons .buffcorp-top-actions .notify-bell {
            margin: 0;
            transform: none;
        }

        .buffcorp-top-actions .admin-home-button,
        .buffcorp-top-actions .payroll-button,
        .buffcorp-top-actions .notify-bell,
        .main-content.admin-dashboard-shell .buffcorp-top-actions .admin-home-button,
        .main-content.admin-dashboard-shell .buffcorp-top-actions .payroll-button,
        .main-content.admin-dashboard-shell .buffcorp-top-actions .notify-bell {
            display: grid;
            width: 38px;
            height: 38px;
            place-items: center;
            padding: 0;
            border: 0;
            background: transparent;
            color: var(--buff-text);
        }

        .buffcorp-top-actions .payroll-button,
        .buffcorp-top-actions .notify-bell {
            border-radius: 8px;
            transition: background .18s ease, color .18s ease, box-shadow .18s ease;
        }
        .buffcorp-top-actions .payroll-button:hover,
        .buffcorp-top-actions .notify-bell:hover,
        .buffcorp-top-actions .payroll-wrap.open .payroll-button,
        .buffcorp-top-actions .notify-wrap.open .notify-bell {
            background: #eef5ff;
            color: var(--buff-brand-dark);
        }

        .buffcorp-top-actions .admin-home-button svg,
        .buffcorp-top-actions .payroll-button svg,
        .buffcorp-top-actions .notify-bell svg,
        .main-content.admin-dashboard-shell .buffcorp-top-actions .admin-home-button svg,
        .main-content.admin-dashboard-shell .buffcorp-top-actions .payroll-button svg,
        .main-content.admin-dashboard-shell .buffcorp-top-actions .notify-bell svg {
            display: block;
            width: 22px;
            height: 22px;
            margin: 0;
            filter: none;
        }

        .buffcorp-top-actions .notify-count,
        .main-content.admin-dashboard-shell .buffcorp-top-actions .notify-count {
            top: -5px;
            right: -5px;
            border-color: #fff;
        }

        .buffcorp-page {
            min-width: 0;
            min-height: 0;
            flex: 1;
            overflow: auto;
            padding: 24px 28px 40px;
            background: var(--buff-bg);
            box-sizing: border-box;
        }

        .buffcorp-page:has(.list-ui) {
            padding: 16px 18px 34px;
        }

        .main-content.chat-shell .buffcorp-page {
            overflow: hidden;
            padding: 14px 18px;
        }

        .list-ui {
            --list-bg: #f4f8fc;
            --list-surface: #fff;
            --list-text: #17324d;
            --list-muted: #687b91;
            --list-line: #d9e6f3;
            --list-primary: #2e6cbf;
            --list-primary-dark: #1f559c;
            --list-success: #16a34a;
            --list-danger: #dc2626;
            --list-warning: #f59e0b;
            --list-shadow: 0 10px 30px rgba(46,108,191,.07);
            width: 100%;
            color: var(--list-text);
            font-family: Manrope, "Segoe UI", Arial, sans-serif;
        }

        .list-ui *,
        .list-ui *:before,
        .list-ui *:after { box-sizing: border-box; }

        .list-ui .list-page {
            display: grid;
            width: 100%;
            min-width: 0;
            overflow: visible;
            border: 1px solid var(--list-line);
            border-radius: 8px;
            background: var(--list-surface);
            box-shadow: 0 10px 30px rgba(46,108,191,.06);
        }

        .list-ui .list-header,
        .list-ui .list-filter-panel,
        .list-ui .list-content {
            width: 100%;
            min-width: 0;
            border: 0;
            border-radius: 0;
            background: var(--list-surface);
            box-shadow: none;
        }

        .list-ui .list-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--list-line);
            overflow: hidden;
        }

        .list-ui .list-title {
            min-width: 0;
        }

        .list-ui .list-title h1 {
            margin: 0;
            color: #123f70;
            font-size: 23px;
            font-weight: 800;
            line-height: 30px;
            letter-spacing: 0;
        }

        .list-ui .list-title p {
            margin: 4px 0 0;
            color: var(--list-muted);
            font-size: 12px;
            line-height: 18px;
        }

        .list-ui .list-header-actions {
            display: flex;
            flex: 0 1 auto;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 8px;
        }

        .list-ui .list-inline-actions {
            margin-top: 0;
        }

        .list-ui .list-btn {
            display: inline-flex;
            min-height: 48px;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 20px;
            border: 1px solid var(--list-line);
            border-radius: 8px;
            background: #fff;
            color: var(--list-text);
            cursor: pointer;
            font: 700 14px/20px Manrope, "Segoe UI", Arial, sans-serif;
            text-decoration: none;
            white-space: nowrap;
        }

        .list-ui .list-btn svg {
            width: 16px;
            height: 16px;
            flex: 0 0 16px;
        }

        .list-ui .list-btn-primary {
            border-color: var(--list-primary);
            background: var(--list-primary);
            color: #fff;
        }

        .list-ui .list-btn-primary:hover {
            background: var(--list-primary-dark);
            color: #fff;
        }

        .list-ui .list-btn-secondary:hover {
            border-color: #9fc0e3;
            background: #f5f9fe;
            color: var(--list-primary);
        }

        .list-ui .list-filter-panel {
            display: block;
            padding: 18px;
            border-bottom: 0;
            overflow: visible;
        }

        .list-ui .list-filter-card {
            display: grid;
            gap: 18px;
            width: 100%;
            padding: 22px;
            border: 1px solid var(--list-line);
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 12px 32px rgba(46,108,191,.08);
        }

        .filter-ui .filter-primary,
        .filter-ui .filter-grid,
        .filter-ui .list-filter-fields,
        .filter-ui .list-filter-form {
            min-width: 0;
        }

        .filter-ui .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            align-items: end;
            gap: 16px;
        }

        .filter-ui .filter-field--search {
            grid-column: span 2;
        }

        .filter-ui .filter-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-top: 16px;
            border-top: 1px solid #edf2f7;
        }

        .filter-ui .filter-actions__secondary,
        .filter-ui .filter-actions__primary {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .filter-ui .filter-actions__secondary {
            justify-content: flex-start;
        }

        .filter-ui .filter-actions__primary {
            margin-left: auto;
            justify-content: flex-end;
        }

        .list-ui .filter-field {
            display: grid;
            min-width: 0;
            gap: 7px;
        }

        .list-ui .filter-field-search {
            width: 100%;
            max-width: 440px;
        }

        .list-ui .filter-field label {
            color: var(--list-text);
            font-size: 13px;
            font-weight: 800;
            line-height: 18px;
        }

        .list-ui .list-search-control {
            display: flex;
            min-height: 52px;
            align-items: center;
            gap: 9px;
            padding: 0 16px;
            border: 1px solid var(--list-line);
            border-radius: 8px;
            background: #fff;
            overflow: hidden;
        }

        .list-ui .list-search-control svg {
            width: 17px;
            height: 17px;
            color: var(--list-muted);
        }

        .list-ui .list-search-control input {
            width: 100%;
            min-width: 0;
            height: 48px;
            padding: 0;
            border: 0 !important;
            outline: 0;
            background: transparent;
            color: var(--list-text);
            font: 14px/48px Manrope, "Segoe UI", Arial, sans-serif;
            box-shadow: none !important;
        }

        .list-ui .list-filter-toggle {
            display: none;
        }

        .list-ui .list-filter-fields {
            min-width: 0;
            display: contents;
        }

        .list-ui .list-filter-fields:empty {
            display: none;
        }

        .list-ui .list-filter-form {
            display: contents;
        }

        .list-ui .list-filter-form .filter-field-submit {
            align-self: end;
        }

        .list-ui .list-filter-form .filter-field-submit .list-btn {
            min-width: 220px;
            width: 100%;
        }

        .list-ui .list-filter-form > .filter-field {
            width: 100%;
            max-width: 360px;
        }

        .list-ui .list-filter-form > .filter-field-submit {
            max-width: none;
        }

        .list-ui .filter-field-checkbox,
        .list-ui .filter-field-radio {
            width: max-content;
            min-width: 0;
            max-width: none;
            align-self: end;
            justify-self: start;
            gap: 10px;
        }

        .filter-ui .filter-field-submit {
            justify-self: end;
        }

        .filter-ui .filter-panel.is-submitting {
            opacity: .72;
            pointer-events: none;
        }

        .filter-ui .list-btn.is-loading:before {
            content: "";
            width: 14px;
            height: 14px;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: filterButtonSpin .7s linear infinite;
        }

        @keyframes filterButtonSpin {
            to { transform: rotate(360deg); }
        }

        .list-ui .list-filter-form > a {
            display: inline-flex;
            min-height: 48px;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 20px;
            border: 1px solid var(--list-line);
            border-radius: 8px;
            background: #fff;
            color: var(--list-text);
            font: 700 14px/20px Manrope, "Segoe UI", Arial, sans-serif;
            text-decoration: none;
            white-space: nowrap;
        }

        .list-ui .list-filter-form > a input[type="button"] {
            width: auto;
            min-width: 0;
            height: auto;
            min-height: 0;
            padding: 0;
            border: 0;
            background: transparent;
            color: inherit;
            font: inherit;
            cursor: pointer;
        }

        .list-ui .list-inline-actions a input[type="button"] {
            width: auto;
            min-width: 0;
            height: auto;
            min-height: 0;
            padding: 0;
            border: 0;
            background: transparent;
            color: inherit;
            font: inherit;
            cursor: pointer;
        }

        .list-ui .list-export-icon {
            width: 22px;
            height: 22px;
            flex: 0 0 22px;
            object-fit: contain;
        }

        .hosting-type {
            display: inline-flex;
            min-height: 28px;
            align-items: center;
            justify-content: center;
            padding: 4px 10px;
            border-radius: 999px;
            font: 800 12px/18px Manrope, "Segoe UI", Arial, sans-serif;
            white-space: nowrap;
        }

        .hosting-type.customer {
            border: 1px solid #bfdbfe;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .hosting-type.demo {
            border: 1px solid #fed7aa;
            background: #fff7ed;
            color: #c2410c;
        }

        .hosting-type.internal {
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #15803d;
        }

        .list-ui .list-filter-form select,
        .list-ui .list-filter-form input:not([type="checkbox"]):not([type="radio"]):not([type="image"]) {
            width: 100%;
            min-width: 0;
            height: 52px;
            padding: 0 54px 0 54px !important;
            border: 1px solid var(--list-line);
            border-radius: 8px;
            outline: 0;
            background: #fff;
            color: var(--list-text);
            font: 14px/50px Manrope, "Segoe UI", Arial, sans-serif;
        }

        .list-ui .list-filter-form select {
            appearance: none;
            -webkit-appearance: none;
            background-image: linear-gradient(45deg, transparent 50%, #17324d 50%), linear-gradient(135deg, #17324d 50%, transparent 50%);
            background-position: calc(100% - 34px) 23px, calc(100% - 26px) 23px;
            background-size: 8px 8px, 8px 8px;
            background-repeat: no-repeat;
            padding-right: 68px !important;
            text-indent: 0;
        }

        .list-ui .filter-control-wrap > select,
        .list-ui .filter-control-wrap > input:not([type="checkbox"]):not([type="radio"]):not([type="image"]) {
            padding-left: 56px !important;
        }

        .list-ui .filter-control-wrap {
            position: relative;
            display: block;
        }

        .list-ui .filter-control-wrap > svg {
            position: absolute;
            left: 18px;
            top: 50%;
            width: 18px;
            height: 18px;
            color: #5f7894;
            pointer-events: none;
            transform: translateY(-50%);
        }

        .list-ui .filter-control-wrap:has(input[type="checkbox"]) > svg,
        .list-ui .filter-control-wrap:has(input[type="radio"]) > svg {
            display: none;
        }

        .list-ui .list-filter-form input[type="checkbox"],
        .list-ui .list-filter-form input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: var(--list-primary);
        }

        .list-ui .list-content {
            overflow: visible;
        }

        .list-ui .list-table-scroll {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            overflow-y: visible;
            -webkit-overflow-scrolling: touch;
            border: 1px solid var(--list-line);
            background: #fff;
        }

	        .list-ui .data-table {
	            width: max-content;
	            min-width: 100%;
	            table-layout: auto;
            border: 0;
            border-collapse: collapse;
            border-spacing: 0;
            background: #fff;
        }

        .list-ui .data-table tr.header td,
        .list-ui .data-table tr.header th {
            position: sticky;
            top: 0;
            z-index: 2;
            height: auto;
            padding: 13px 14px;
            border: 1px solid var(--list-line);
            background: #f6f9fd !important;
            color: #526a82 !important;
            font-size: 13px !important;
            font-weight: 800;
            line-height: 18px;
            white-space: nowrap;
        }

        .list-ui .data-table td {
            max-width: none;
            height: auto;
            padding: 13px 14px;
            border: 1px solid #edf2f7;
            background: #fff !important;
            color: var(--list-text) !important;
            font-size: 13px !important;
            line-height: 20px;
            vertical-align: middle;
            overflow: visible;
            text-overflow: clip;
            white-space: nowrap;
            word-break: normal;
        }

        .list-ui .data-table tr:hover td {
            background: #f8fbff !important;
        }

        .list-ui .data-table td a:not(.list-row-action) {
            display: inline-block;
            max-width: none;
            overflow: visible;
            text-overflow: clip;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .list-ui .list-page--common-lists-bangluong .data-table tr > :nth-child(21) {
            min-width: 560px;
            max-width: 620px;
            white-space: normal !important;
            vertical-align: top !important;
        }

        .list-ui .list-page--common-lists-bangluong .data-table tr > :nth-child(24) {
            min-width: 260px;
            max-width: 320px;
            background: #fffaf0 !important;
            white-space: normal !important;
            vertical-align: top !important;
        }

        .list-ui .list-page--common-lists-bangluong .data-table tr > :nth-child(10) {
            background: #fffaf0 !important;
            color: #c05621 !important;
            font-weight: 800 !important;
        }

        .list-ui .note-box {
            display: grid !important;
            gap: 8px !important;
            max-height: 224px !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 3px 6px 3px 0 !important;
            white-space: normal !important;
            scrollbar-width: thin;
            scrollbar-color: #b9c9dc #f2f6fb;
        }

        .list-ui .note-box::-webkit-scrollbar,
        .list-ui .attendance-note-list::-webkit-scrollbar {
            width: 7px;
        }

        .list-ui .note-box::-webkit-scrollbar-track,
        .list-ui .attendance-note-list::-webkit-scrollbar-track {
            background: #f2f6fb;
            border-radius: 10px;
        }

        .list-ui .note-box::-webkit-scrollbar-thumb,
        .list-ui .attendance-note-list::-webkit-scrollbar-thumb {
            background: #b9c9dc;
            border-radius: 10px;
        }

        .list-ui .note-empty,
        .list-ui .attendance-note-empty {
            display: inline-flex !important;
            align-items: center;
            min-height: 34px;
            width: fit-content;
            border: 1px dashed #cfdceb;
            border-radius: 8px;
            background: #f8fbff;
            color: #7a8da3 !important;
            font-size: 13px !important;
            font-style: italic;
            font-weight: 600 !important;
            line-height: 18px;
            padding: 7px 10px;
            white-space: normal !important;
        }

        .list-ui .note-item {
            display: grid !important;
            grid-template-columns: auto minmax(0, 1fr);
            gap: 8px;
            align-items: start;
            width: 100%;
            min-width: 0;
            margin: 0 0 8px;
            padding: 8px 10px;
            border: 1px solid #d9e7f5;
            border-left: 5px solid #7f92aa;
            border-radius: 8px;
            background: #f8fbff !important;
            box-shadow: 0 4px 12px rgba(31, 80, 128, .05);
            color: #17324d !important;
            font-size: 13px !important;
            font-weight: 700;
            line-height: 19px;
            white-space: normal !important;
        }

        .list-ui .note-item.note-late {
            border-color: #fde3b7;
            border-left-color: #f59e0b;
            background: #fff8ed !important;
            color: #9a4d00 !important;
        }

        .list-ui .note-item.note-leave {
            border-color: #d6e6fb;
            border-left-color: #2e6cbf;
            background: #f2f7ff !important;
            color: #174f91 !important;
        }

        .list-ui .note-item.note-danger {
            border-color: #ffd5d5;
            border-left-color: #dc2626;
            background: #fff3f3 !important;
            color: #b91c1c !important;
        }

        .list-ui .note-date {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 52px;
            height: 24px;
            border-radius: 999px;
            background: #e8f1fc;
            color: #0f4c8a !important;
            font-size: 12px !important;
            font-weight: 800;
            line-height: 1;
            white-space: nowrap !important;
        }

        .list-ui .note-content {
            display: block !important;
            min-width: 0;
            white-space: normal !important;
            word-break: break-word;
        }

        .list-ui .list-page--common-lists-chamcong .data-table tr > :nth-child(8) {
            min-width: 300px;
            max-width: 420px;
            white-space: normal !important;
            vertical-align: top !important;
        }

        .list-ui .attendance-note-list {
            display: flex !important;
            flex-wrap: wrap;
            gap: 6px;
            max-height: 112px;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 2px 4px 2px 0;
            white-space: normal !important;
            scrollbar-width: thin;
            scrollbar-color: #b9c9dc #f2f6fb;
        }

        .list-ui .attendance-note-chip {
            display: inline-flex !important;
            align-items: center;
            min-height: 30px;
            max-width: 100%;
            border: 1px solid #d8e7f5;
            border-left: 4px solid #7f92aa;
            border-radius: 8px;
            background: #f8fbff !important;
            color: #17324d !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            line-height: 18px;
            padding: 6px 9px;
            white-space: normal !important;
        }

        .list-ui .attendance-note-warning {
            border-color: #fde3b7;
            border-left-color: #f59e0b;
            background: #fff8ed !important;
            color: #9a4d00 !important;
        }

        .list-ui .attendance-note-danger {
            border-color: #ffd5d5;
            border-left-color: #dc2626;
            background: #fff3f3 !important;
            color: #b91c1c !important;
        }

        .list-ui .attendance-note-leave {
            border-color: #d6e6fb;
            border-left-color: #2e6cbf;
            background: #f2f7ff !important;
            color: #174f91 !important;
        }

        .list-ui .list-page--customer-customer .data-table {
            min-width: 1480px;
        }

        .list-ui .list-page--customer-customer .data-table tr > :nth-child(1) { min-width: 56px; text-align: center !important; }
        .list-ui .list-page--customer-customer .data-table tr > :nth-child(2) { min-width: 340px; }
        .list-ui .list-page--customer-customer .data-table tr > :nth-child(3) { min-width: 540px; }
        .list-ui .list-page--customer-customer .data-table tr > :nth-child(4) { min-width: 170px; text-align: center !important; }
        .list-ui .list-page--customer-customer .data-table tr > :nth-child(5) { min-width: 120px; text-align: center !important; }
        .list-ui .list-page--customer-customer .data-table tr > :nth-child(6) { min-width: 160px; text-align: center !important; }
        .list-ui .list-page--customer-customer .data-table tr > :nth-child(7) { min-width: 160px; text-align: center !important; }

        .list-ui .list-page--members-members .data-table {
            width: 100%;
            min-width: 980px;
        }

        .list-ui .list-page--members-members .data-table tr > :nth-child(1) { width: 56px; text-align: center !important; }
        .list-ui .list-page--members-members .data-table tr > :nth-child(4) { width: 100px; }
        .list-ui .list-page--members-members .data-table tr > :nth-child(5) { width: 170px; }
        .list-ui .list-page--members-members .data-table tr > :nth-child(7) { width: 86px; text-align: center !important; }
        .list-ui .list-page--members-members .data-table tr > .list-actions-head,
        .list-ui .list-page--members-members .data-table tr > .list-actions-cell {
            width: 96px !important;
            min-width: 96px !important;
            max-width: 96px !important;
            text-align: center !important;
        }

        .list-ui .list-page--members-members .list-row-actions {
            justify-content: center;
        }

        .list-ui .list-page--functionmenu-functionmenu .data-table {
            width: 100%;
            min-width: 1280px;
        }

        .list-ui .list-page--functionmenu-functionmenu .data-table tr > :nth-child(1) {
            width: 64px;
            text-align: center !important;
        }

        .list-ui .list-page--functionmenu-functionmenu .data-table tr > :nth-child(2) {
            min-width: 300px;
        }

        .list-ui .list-page--functionmenu-functionmenu .data-table tr > :nth-child(3) {
            min-width: 190px;
        }

        .list-ui .list-page--functionmenu-functionmenu .data-table tr > :nth-child(4) {
            min-width: 300px;
        }

        .list-ui .list-page--functionmenu-functionmenu .data-table tr > :nth-child(5) {
            width: 160px;
            text-align: center !important;
        }

        .list-ui .list-page--functionmenu-functionmenu .data-table tr > .list-actions-head,
        .list-ui .list-page--functionmenu-functionmenu .data-table tr > .list-actions-cell {
            width: 112px !important;
            min-width: 112px !important;
            max-width: 112px !important;
            text-align: center !important;
        }

        .list-ui .list-page--functionmenu-functionmenu .list-row-actions {
            justify-content: center;
        }

        .list-ui .list-page--customer-customer .data-table tr.customer-type-green td {
            background: #f2fbf5 !important;
        }

        .list-ui .list-page--customer-customer .data-table tr.customer-type-yellow td {
            background: #fffaf0 !important;
        }

        .list-ui .list-page--customer-customer .data-table tr.customer-type-grey td {
            background: #f7f9fc !important;
        }

        .list-ui .list-page--customer-customer .data-table tr.customer-type-green td:first-child,
        .list-ui .list-page--customer-customer .data-table td[style*="Green"] {
            border-left: 5px solid #22c55e !important;
            background: #dcfce7 !important;
            color: #166534 !important;
            font-weight: 900;
        }

        .list-ui .list-page--customer-customer .data-table tr.customer-type-yellow td:first-child,
        .list-ui .list-page--customer-customer .data-table td[style*="Yellow"] {
            border-left: 5px solid #f59e0b !important;
            background: #fef3c7 !important;
            color: #92400e !important;
            font-weight: 900;
        }

        .list-ui .list-page--customer-customer .data-table tr.customer-type-grey td:first-child,
        .list-ui .list-page--customer-customer .data-table td[style*="Grey"] {
            border-left: 5px solid #64748b !important;
            background: #e2e8f0 !important;
            color: #334155 !important;
            font-weight: 900;
        }

        .list-ui .list-page--customer-customer .data-table tr.customer-type-green:hover td {
            background: #e9f8ee !important;
        }

        .list-ui .list-page--customer-customer .data-table tr.customer-type-yellow:hover td {
            background: #fff4d6 !important;
        }

        .list-ui .list-page--customer-customer .data-table tr.customer-type-grey:hover td {
            background: #eef3f8 !important;
        }

        .list-ui .list-page--common-lists-website .data-table tr.website-type-yellow td {
            background: #fffaf0 !important;
        }

        .list-ui .list-page--common-lists-website .data-table tr.website-type-orange td {
            background: #fff7ed !important;
        }

        .list-ui .list-page--common-lists-website .data-table tr.website-type-lime td {
            background: #f2fbe8 !important;
        }

        .list-ui .list-page--common-lists-website .data-table tr.website-type-grey td {
            background: #f7f9fc !important;
        }

        .list-ui .list-page--common-lists-website .data-table tr.website-type-mint td {
            background: #effbf7 !important;
        }

        .list-ui .list-page--common-lists-website .data-table tr.website-type-green td {
            background: #f2fbf5 !important;
        }

        .list-ui .list-page--common-lists-website .data-table tr.website-type-yellow td:first-child,
        .list-ui .list-page--common-lists-website .data-table td[style*="Yellow"] {
            border-left: 5px solid #f59e0b !important;
            background: #fef3c7 !important;
            color: #92400e !important;
            font-weight: 900;
        }

        .list-ui .list-page--common-lists-website .data-table tr.website-type-orange td:first-child,
        .list-ui .list-page--common-lists-website .data-table td[style*="Orange"] {
            border-left: 5px solid #f97316 !important;
            background: #ffedd5 !important;
            color: #9a3412 !important;
            font-weight: 900;
        }

        .list-ui .list-page--common-lists-website .data-table tr.website-type-lime td:first-child,
        .list-ui .list-page--common-lists-website .data-table td[style*="AFD788"] {
            border-left: 5px solid #84cc16 !important;
            background: #ecfccb !important;
            color: #3f6212 !important;
            font-weight: 900;
        }

        .list-ui .list-page--common-lists-website .data-table tr.website-type-grey td:first-child,
        .list-ui .list-page--common-lists-website .data-table td[style*="D7D7D7"] {
            border-left: 5px solid #64748b !important;
            background: #e2e8f0 !important;
            color: #334155 !important;
            font-weight: 900;
        }

        .list-ui .list-page--common-lists-website .data-table tr.website-type-mint td:first-child,
        .list-ui .list-page--common-lists-website .data-table td[style*="98D0B9"] {
            border-left: 5px solid #14b8a6 !important;
            background: #ccfbf1 !important;
            color: #0f766e !important;
            font-weight: 900;
        }

        .list-ui .list-page--common-lists-website .data-table tr.website-type-green td:first-child,
        .list-ui .list-page--common-lists-website .data-table td[style*="Green"] {
            border-left: 5px solid #22c55e !important;
            background: #dcfce7 !important;
            color: #166534 !important;
            font-weight: 900;
        }

	        .list-ui .list-actions-cell {
	            position: relative;
	            overflow: visible !important;
	            text-align: center !important;
	            white-space: nowrap;
	            width: 82px !important;
	            min-width: 82px !important;
	            max-width: 82px !important;
	        }

	        .list-ui .list-actions-head {
	            width: 82px !important;
	            min-width: 82px !important;
	            max-width: 82px !important;
	            text-align: center !important;
	        }

	        .list-ui .list-row-actions {
	            display: flex;
	            position: relative;
	            justify-content: center;
	        }

        .list-ui .list-action-menu {
            position: relative;
            display: inline-flex;
        }

        .list-ui .list-action-menu-button {
            display: grid;
            width: 34px;
            height: 34px;
            padding: 0;
            place-items: center;
            border: 1px solid var(--list-line);
            border-radius: 7px;
            background: #fff;
            color: var(--list-text);
            cursor: pointer;
        }

        .list-ui .list-action-menu-button svg {
            width: 17px;
            height: 17px;
        }

        .list-ui .list-action-menu-panel {
            position: fixed;
            display: none;
            min-width: 170px;
            padding: 6px;
            border: 1px solid var(--list-line);
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 18px 42px rgba(16,24,40,.18);
            z-index: 30000;
        }

        .list-ui .list-action-menu.open .list-action-menu-panel {
            display: grid;
            gap: 4px;
        }

        .list-ui .list-row-action {
            display: flex;
            min-height: 36px;
            align-items: center;
            justify-content: flex-start;
            gap: 8px;
            padding: 0 10px;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: var(--list-text);
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        .list-ui .list-row-action:hover {
            background: #f4f8fc;
        }

        .list-ui .list-row-action svg {
            width: 15px;
            height: 15px;
            flex: 0 0 15px;
        }

        .list-ui .list-row-action:after {
            content: attr(title);
        }

        .list-ui .list-action-delete {
            color: var(--list-danger);
        }

        .list-ui .list-action-permission {
            color: var(--list-primary);
        }

        .list-ui .mobile-list-cards {
            display: none;
        }

        .list-ui .mobile-list-card {
            display: grid;
            gap: 10px;
            margin: 10px;
            padding: 14px;
            border: 1px solid var(--list-line);
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 22px rgba(46,108,191,.07);
        }

        .list-ui .mobile-list-card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .list-ui .mobile-list-card-title {
            min-width: 0;
            color: #173f67;
            font-size: 15px;
            font-weight: 800;
            line-height: 20px;
            overflow-wrap: anywhere;
        }

        .list-ui .mobile-list-card-meta {
            display: grid;
            gap: 7px;
        }

        .list-ui .mobile-list-card-field {
            display: grid;
            grid-template-columns: 100px minmax(0, 1fr);
            gap: 8px;
            color: var(--list-text);
            font-size: 12px;
            line-height: 17px;
        }

        .list-ui .mobile-list-card-field b {
            color: var(--list-muted);
            font-size: 11px;
        }

        .list-ui .list-empty-state {
            display: none;
            padding: 38px 18px;
            text-align: center;
            color: var(--list-muted);
        }

        .list-ui .list-empty-state strong {
            display: block;
            color: var(--list-text);
            font-size: 16px;
        }

        .list-ui .list-empty-state small {
            display: block;
            margin-top: 6px;
            font-size: 12px;
        }

        .list-ui .list-page.list-empty .list-empty-state {
            display: block;
        }

        @media (max-width: 1199px) {
            .filter-ui .filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .filter-ui .filter-field--search {
                grid-column: 1 / -1;
            }
            .list-ui .filter-field-search {
                max-width: none;
            }
        }

        @media (max-width: 767px) {
            .list-ui .list-filter-toggle {
                display: inline-flex;
                width: fit-content;
            }
            .list-ui .list-filter-fields {
                display: none;
            }
            .list-ui .list-filter-fields.open {
                display: contents;
            }
        }

        @media (max-width: 767px) {
            .list-ui .list-header {
                align-items: stretch;
                flex-direction: column;
                padding: 15px;
            }
            .list-ui .list-header-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }
            .filter-ui .filter-grid {
                grid-template-columns: 1fr;
            }
            .filter-ui .filter-field--search {
                grid-column: auto;
            }
            .filter-ui .filter-actions {
                align-items: stretch;
                flex-direction: column-reverse;
            }
            .filter-ui .filter-actions__secondary,
            .filter-ui .filter-actions__primary {
                display: grid;
                grid-template-columns: 1fr;
                width: 100%;
            }
            .filter-ui .list-btn,
            .filter-ui .list-filter-form .filter-field-submit .list-btn {
                width: 100%;
                min-width: 0;
            }
            .list-ui .list-filter-form > .filter-field,
            .list-ui .filter-field-search { max-width: none; }
            .list-ui .list-header-actions .list-btn-primary {
                grid-column: 1 / -1;
                width: 100%;
            }
            .list-ui .list-table-scroll {
                display: none;
            }
            .list-ui .mobile-list-cards {
                display: block;
                padding: 2px 0 8px;
            }
        }

        .buffcorp-route-loader {
            position: fixed;
            inset: 0;
            display: grid;
            place-items: center;
            background: rgba(244,248,252,.82);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .16s ease, visibility .16s ease;
            z-index: 20000;
            backdrop-filter: blur(2px);
        }

        .buffcorp-route-loader.show {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        html.buffcorp-preload-route-loading .buffcorp-route-loader {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .buffcorp-route-loader-card {
            display: grid;
            min-width: 0;
            min-height: 0;
            place-items: center;
            border: 0;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .buffcorp-route-loader-logo {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            animation: buffcorpRouteSpin .9s linear infinite;
        }

        @keyframes buffcorpRouteSpin {
            to { transform: rotate(360deg); }
        }

        .buffcorp-page div[style*="overflow:auto"][style*="height:80%"] {
            height: auto !important;
            overflow: visible !important;
        }

        .buffcorp-page input:not([type="checkbox"]):not([type="radio"]):not([type="image"]),
        .buffcorp-page select,
        .buffcorp-page textarea {
            max-width: 100%;
            min-height: 34px;
            padding: 7px 9px;
            border: 1px solid #cfddea;
            border-radius: 6px;
            background: #fff;
            color: #111;
            font: 11px/18px Manrope, "Segoe UI", Arial, sans-serif;
            box-sizing: border-box;
        }

        .buffcorp-page textarea { min-height: 90px; resize: vertical; }
        .buffcorp-page input:focus,
        .buffcorp-page select:focus,
        .buffcorp-page textarea:focus { border-color: #6fa7dc; outline: 0; box-shadow: 0 0 0 3px rgba(46,108,191,.1); }
        .buffcorp-page .list-ui .list-search-control input {
            min-height: 0;
            height: 38px;
            padding: 0;
            border: 0 !important;
            box-shadow: none !important;
        }
        .buffcorp-page .list-ui .list-filter-form select,
        .buffcorp-page .list-ui .list-filter-form input:not([type="checkbox"]):not([type="radio"]):not([type="image"]) {
            min-height: 42px;
            padding: 0 12px;
            border: 1px solid var(--list-line);
            border-radius: 7px;
            box-shadow: none;
        }

        .menu-tree-link { display: inline-flex; align-items: center; gap: 9px; color: #173f67; font-weight: 700; text-decoration: none; }
        .menu-tree-link:hover { color: var(--buff-brand); }
        .menu-tree-icon {
            display: none;
            width: 16px;
            height: 13px;
            border: 1.5px solid currentColor;
            border-radius: 3px;
            color: #397bc5;
            box-sizing: border-box;
        }
        .menu-tree-icon:before { display: block; width: 7px; height: 3px; margin: -4px 0 0 1px; border: 1.5px solid currentColor; border-bottom: 0; border-radius: 2px 2px 0 0; content: ""; }
        .menu-tree-link.has-children .menu-tree-icon { background: #eaf3fc; }
        .menu-code { padding: 4px 7px; border-radius: 5px; background: #f2f6fa; color: #344054; font: 10px/16px Manrope, "Segoe UI", Arial, sans-serif; }
        .menu-child-count { display: inline-grid; min-width: 24px; height: 24px; padding: 0 6px; place-items: center; border-radius: 12px; background: #eaf3fc; color: var(--buff-brand); font-size: 10px; font-weight: 800; box-sizing: border-box; }
        .permission-selector input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--buff-brand); cursor: pointer; }
        .buffcorp-page form > table:not(.selector) {
            border: 1px solid var(--buff-line);
            border-collapse: separate;
            border-spacing: 0 5px;
            border-radius: 8px;
            background: #fff;
            box-shadow: var(--buff-shadow);
        }
        .buffcorp-page form > table:not(.selector) td { padding: 6px 10px; color: #111; }

        .buffcorp-module-card {
            overflow: hidden;
            border: 0;
            border-radius: 8px;
            background: #fff;
            box-shadow: none;
        }
        .buffcorp-module-toolbar {
            display: flex;
            min-height: 64px;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--buff-line);
            background: #fff;
            box-sizing: border-box;
        }
        .buffcorp-list-head-copy strong {
            display: block;
            color: #173f67;
            font-size: 20px;
            line-height: 28px;
        }
        .buffcorp-list-head-copy small {
            display: block;
            margin-top: 3px;
            color: var(--buff-muted);
            font-size: 13px;
        }
        .buffcorp-module-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 8px;
        }
        .buffcorp-module-actions .list-btn {
            display: inline-flex;
            min-height: 42px;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 14px;
            border: 1px solid var(--buff-line);
            border-radius: 7px;
            background: #fff;
            color: var(--buff-text);
            font: 700 13px/19px Manrope, "Segoe UI", Arial, sans-serif;
            text-decoration: none;
            white-space: nowrap;
        }
        .buffcorp-module-actions .list-btn svg {
            width: 16px;
            height: 16px;
        }
        .buffcorp-module-actions .list-btn-primary {
            border-color: var(--buff-brand);
            background: var(--buff-brand);
            color: #fff;
        }
        .buffcorp-module-actions .list-btn-secondary:hover {
            border-color: #9fc0e3;
            background: #f5f9fe;
            color: var(--buff-brand);
        }
        .buffcorp-form-card {
            padding: 18px 24px 24px;
            border: 0;
            background: #fff;
        }
        .buffcorp-page form > table.buffcorp-form-table:not(.selector) {
            border: 0 !important;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            border-radius: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }
        .buffcorp-page form > table.buffcorp-form-table:not(.selector) td {
            padding: 0 !important;
        }
        .buffcorp-page .buffcorp-form-table {
            width: 100%;
            border: 0;
            border-collapse: collapse;
            border-spacing: 0;
            background: transparent;
            box-shadow: none;
        }
        .buffcorp-page .buffcorp-form-table > tbody {
            display: block;
        }
        .buffcorp-page .buffcorp-form-table.buffcorp-form-dense > tbody,
        .buffcorp-page .buffcorp-form-table.buffcorp-form-ultra > tbody { display: block; }
        .buffcorp-page .buffcorp-form-table > tbody > tr {
            display: grid;
            min-width: 0;
            grid-template-columns: minmax(150px, 260px) minmax(0, 1fr);
            align-items: start;
            gap: 18px;
            padding: 14px 0;
            border: 0;
            border-bottom: 1px solid #edf2f7;
            border-radius: 0;
            background: transparent;
        }
        .buffcorp-page .buffcorp-form-table > tbody > tr > td {
            display: block;
            width: auto !important;
            height: auto !important;
            padding: 0 !important;
            color: #111;
            font-size: 14px;
            line-height: 24px;
        }
        .buffcorp-page .buffcorp-form-table > tbody > tr > td:first-child {
            color: #344054;
            font-size: 14px;
            font-weight: 800;
            padding-top: 10px !important;
        }
        .buffcorp-page .buffcorp-form-table textarea { height: 110px; min-height: 110px; resize: vertical; }
        .buffcorp-page .buffcorp-form-table input:not([type="checkbox"]):not([type="radio"]):not([type="image"]),
        .buffcorp-page .buffcorp-form-table select,
        .buffcorp-page .buffcorp-form-table textarea {
            display: block;
            width: 100% !important;
            margin: 6px 0 10px;
            max-width: none;
            min-height: 42px;
            font-size: 14px;
            line-height: 22px;
        }

        .buffcorp-page .buffcorp-form-table input[type="checkbox"],
        .buffcorp-page .buffcorp-form-table input[type="radio"],
        .buffcorp-page .buffcorp-form-table input[type="image"] {
            display: inline-block;
            width: auto !important;
            margin: 4px 8px 4px 0;
            vertical-align: middle;
        }

        .buffcorp-page .admin-dashboard { padding: 20px; background: var(--buff-bg); font-family: "Segoe UI", Arial, sans-serif; }
        .buffcorp-page .admin-dashboard-header { padding-right: 0; }
        .buffcorp-page .admin-card,
        .buffcorp-page .admin-date-range,
        .buffcorp-page .admin-export-btn,
        .buffcorp-page .admin-select,
        .buffcorp-page .admin-outline-btn { border-color: var(--buff-line); background: #fff; box-shadow: var(--buff-shadow); }

        @media (max-width: 980px) {
            .buffcorp-global-search { width: 230px; }
            .buffcorp-user-copy,
            .buffcorp-user-chevron { display: none; }
            .buffcorp-page .buffcorp-form-table > tbody,
            .buffcorp-page .buffcorp-form-table.buffcorp-form-dense > tbody,
            .buffcorp-page .buffcorp-form-table.buffcorp-form-ultra > tbody { display: block; }
        }

        @media (max-width: 720px) {
            .buffcorp-topbar { min-height: 60px; flex-basis: 60px; padding: 0 12px; }
            .buffcorp-global-search { display: none; }
            .buffcorp-page { padding: 14px 12px 26px; }
            .buffcorp-user-menu { display: none; }
            .buffcorp-page .buffcorp-form-table > tbody,
            .buffcorp-page .buffcorp-form-table.buffcorp-form-dense > tbody,
            .buffcorp-page .buffcorp-form-table.buffcorp-form-ultra > tbody { display: block; }
            .buffcorp-page .buffcorp-form-table > tbody > tr { grid-template-columns: 1fr; }
            .buffcorp-page .buffcorp-form-table > tbody > tr > td:first-child { padding-top: 0 !important; }
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
    var closeBtn = document.getElementById('notify-close');
    if (!wrap || !bell || !countEl || !listEl) return;
    var notifyDebug = window.location && window.location.search && window.location.search.indexOf('debug_notify=1') >= 0;

    window.buffNotifyDebug = function (label) {
        if (!notifyDebug || !window.console || !console.log) return;
        var payrollWrap = document.getElementById('payroll-wrap');
        var payrollButton = document.getElementById('payroll-button');
        var wrapStyle = window.getComputedStyle ? window.getComputedStyle(wrap) : null;
        var bellStyle = window.getComputedStyle ? window.getComputedStyle(bell) : null;
        var countStyle = window.getComputedStyle ? window.getComputedStyle(countEl) : null;
        console.groupCollapsed('[payroll-notify-debug] ' + label);
	        console.log('notifyWrap.className:', wrap.className);
	        console.log('body.className:', document.body ? document.body.className : '(missing)');
	        console.log('notifyCount.className/text:', countEl.className, countEl.innerHTML);
        console.log('payrollWrap.className:', payrollWrap ? payrollWrap.className : '(missing)');
        console.log('activeElement:', document.activeElement ? (document.activeElement.id || document.activeElement.className || document.activeElement.tagName) : '(none)');
        console.log('notify bell matches :hover/:focus/:active:', bell.matches ? {
            hover: bell.matches(':hover'),
            focus: bell.matches(':focus'),
            active: bell.matches(':active')
        } : '(matches unsupported)');
        if (wrapStyle) {
            console.log('notify wrap computed:', {
                opacity: wrapStyle.opacity,
                visibility: wrapStyle.visibility,
                zIndex: wrapStyle.zIndex,
                transform: wrapStyle.transform,
                pointerEvents: wrapStyle.pointerEvents
            });
        }
        if (bellStyle) {
            console.log('notify bell computed:', {
                color: bellStyle.color,
                backgroundColor: bellStyle.backgroundColor,
                boxShadow: bellStyle.boxShadow,
                animationName: bellStyle.animationName,
                animationDuration: bellStyle.animationDuration,
                transform: bellStyle.transform,
                pointerEvents: bellStyle.pointerEvents
            });
        }
        if (countStyle) {
            console.log('notify count computed:', {
                display: countStyle.display,
                transform: countStyle.transform,
                transition: countStyle.transition,
                opacity: countStyle.opacity
            });
        }
        if (payrollButton && payrollButton.matches) {
            console.log('payroll button matches :hover/:focus/:active:', {
                hover: payrollButton.matches(':hover'),
                focus: payrollButton.matches(':focus'),
                active: payrollButton.matches(':active')
            });
        }
        console.groupEnd();
    };

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
        var isEmpty = !data.items || !data.items.length;
        if (isEmpty) {
            if (wrap.className.indexOf('is-empty') < 0) wrap.className += ' is-empty';
            listEl.innerHTML = '<div class="notify-empty">Chưa có thông báo</div>';
            return;
        }
        wrap.className = wrap.className.replace(/ ?is-empty/g, '');
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

    function setNotifyOpen(open) {
        var isOpen = wrap.className.indexOf('open') >= 0;
        if (open && !isOpen) wrap.className += ' open';
        if (!open && isOpen) wrap.className = wrap.className.replace(/ ?open/g, '');
        bell.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (!open && bell.blur) bell.blur();
    }

    function openNotify(e) {
        if (e && e.stopPropagation) e.stopPropagation();
        if (window.buffNotifyDebug) window.buffNotifyDebug('notify click before guard');
        var notifyTarget = e ? (e.target || e.srcElement) : null;
        while (notifyTarget && notifyTarget !== document) {
            if (notifyTarget.id === 'payroll-wrap' || notifyTarget.id === 'payroll-button') return false;
            notifyTarget = notifyTarget.parentNode;
        }
        var payrollWrap = document.getElementById('payroll-wrap');
        if (payrollWrap) payrollWrap.className = payrollWrap.className.replace(/ ?open/g, '');
        var payrollButton = document.getElementById('payroll-button');
        if (payrollButton) payrollButton.setAttribute('aria-expanded', 'false');
        if (payrollButton && payrollButton.blur) payrollButton.blur();
        var userMenu = document.getElementById('buffcorp-user-menu');
        var userButton = document.getElementById('buffcorp-user-toggle');
        if (userMenu) userMenu.className = userMenu.className.replace(/ ?open/g, '');
        if (userButton) userButton.setAttribute('aria-expanded', 'false');
        positionPanel();
        var wasOpen = wrap.className.indexOf('open') >= 0;
        setNotifyOpen(!wasOpen);
        if (!wasOpen) {
            load('list');
        }
        if (window.buffNotifyDebug) window.buffNotifyDebug('notify click after toggle');
    }

    function closeNotify() {
        if (window.buffNotifyDebug) window.buffNotifyDebug('notify close before');
        setNotifyOpen(false);
        if (window.buffNotifyDebug) window.buffNotifyDebug('notify close after');
    }

    if (bell.addEventListener) bell.addEventListener('click', openNotify, false);
    else bell.attachEvent && bell.attachEvent('onclick', openNotify);
    if (closeBtn) closeBtn.onclick = function (e) {
        if (e && e.stopPropagation) e.stopPropagation();
        closeNotify();
    };

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
    if (document.addEventListener) document.addEventListener('keydown', function (e) {
        if ((e.key === 'Escape' || e.keyCode === 27) && wrap.className.indexOf('open') >= 0) {
            closeNotify();
            bell.focus();
        }
    }, false);

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

        function clearTransientFocus() {
            var active = document.activeElement;
            if (active && active.blur) active.blur();
            if (document.body) {
                if (!document.body.getAttribute('tabindex')) document.body.setAttribute('tabindex', '-1');
                if (document.body.focus) {
                    try {
                        document.body.focus({ preventScroll: true });
                    } catch (e) {
                        document.body.focus();
                    }
                }
            }
        }

        function isPanelOpen() {
            return wrap.className.indexOf('open') >= 0;
        }

        function closePanel() {
            if (!isPanelOpen()) return;
            if (window.buffNotifyDebug) window.buffNotifyDebug('payroll close before');
            wrap.className = wrap.className.replace(/ ?open/g, '');
            button.setAttribute('aria-expanded', 'false');
            if (button && button.blur) button.blur();
            if (closeBtn && closeBtn.blur) closeBtn.blur();
            clearTransientFocus();
            if (window.buffNotifyDebug) window.buffNotifyDebug('payroll close after');
            if (window.setTimeout) {
                setTimeout(function () {
                    if (button && button.blur) button.blur();
                    if (closeBtn && closeBtn.blur) closeBtn.blur();
                    clearTransientFocus();
                    if (window.buffNotifyDebug) window.buffNotifyDebug('payroll close after 0ms');
                }, 0);
            }
        }

        function togglePanel(e) {
            if (e && e.preventDefault) e.preventDefault();
            if (e && e.stopPropagation) e.stopPropagation();
            if (e && e.stopImmediatePropagation) e.stopImmediatePropagation();
            if (window.buffNotifyDebug) window.buffNotifyDebug('payroll toggle before');
            var notifyWrap = document.getElementById('notify-wrap');
            var notifyBell = document.getElementById('notify-bell');
            if (notifyWrap) notifyWrap.className = notifyWrap.className.replace(/ ?open/g, '');
            if (notifyBell) notifyBell.setAttribute('aria-expanded', 'false');
            var userMenu = document.getElementById('buffcorp-user-menu');
            var userButton = document.getElementById('buffcorp-user-toggle');
            if (userMenu) userMenu.className = userMenu.className.replace(/ ?open/g, '');
            if (userButton) userButton.setAttribute('aria-expanded', 'false');
            positionPanel();
            if (wrap.className.indexOf('open') >= 0) {
                closePanel();
                return;
            }
            wrap.className += ' open';
            button.setAttribute('aria-expanded', 'true');
            if (window.buffNotifyDebug) window.buffNotifyDebug('payroll toggle opened');
            if (!loaded) {
                loaded = true;
                loadEmployees(loadPayroll);
            }
        }

        if (button.addEventListener) button.addEventListener('click', togglePanel, false);
        else button.attachEvent && button.attachEvent('onclick', togglePanel);

        if (wrap.addEventListener) {
            wrap.addEventListener('click', function (e) {
                if (e && e.stopPropagation) e.stopPropagation();
            }, false);
        } else if (wrap.attachEvent) {
            wrap.attachEvent('onclick', function () {
                if (window.event) window.event.cancelBubble = true;
            });
        }

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

<div class="layout sidebar-initializing">
    <div class="left-menu">
        {LEFT_MENU}
    </div>
    <button type="button" class="buffcorp-mobile-overlay" id="buffcorp-mobile-overlay" aria-label="Đóng menu"></button>
    <div class="buffcorp-route-loader" id="buffcorp-route-loader" aria-hidden="true">
        <div class="buffcorp-route-loader-card">
            <img class="buffcorp-route-loader-logo" src="templates/{skin}/images/menu/logo-xanh.png" alt="">
        </div>
    </div>

    <div class="{MAIN_CONTENT_CLASS}" id="main-content">
        <header class="buffcorp-topbar">
            <div class="buffcorp-page-title-wrap">
                <button type="button" class="buffcorp-mobile-menu" id="buffcorp-mobile-menu" aria-label="Mở menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                        <path d="M4 7h16M4 12h16M4 17h16"></path>
                    </svg>
                </button>
                <div class="buffcorp-page-title" id="buffcorp-page-title">{PAGE_TITLE}</div>
            </div>
            <div class="buffcorp-top-actions">
                <label class="buffcorp-global-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m20 20-4-4"></path>
                    </svg>
                    <input type="search" id="buffcorp-global-search" placeholder="Tìm chức năng..." autocomplete="off" aria-label="Tìm chức năng">
                    <span class="buffcorp-search-results" id="buffcorp-search-results"></span>
                </label>
        <div class="payroll-wrap" id="payroll-wrap">
            <button type="button" class="payroll-button" id="payroll-button" title="Bảng lương real time" aria-expanded="false" aria-controls="payroll-panel">
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
            <button type="button" class="notify-bell" id="notify-bell" title="Thông báo" aria-expanded="false" aria-controls="notify-panel">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="{NOTIFICATION_CLASS}" id="notify-count">{NOTIFICATION_COUNT}</span>
            </button>
            <div class="notify-panel" id="notify-panel">
                <div class="notify-head">
                    <div><small>TRUNG TÂM CẬP NHẬT</small><span>Thông báo</span></div>
                    <button type="button" class="notify-close" id="notify-close" aria-label="Đóng">×</button>
                </div>
                <div class="notify-list" id="notify-list">
                    <div class="notify-empty">Đang tải...</div>
                </div>
                <button type="button" class="notify-read-all" id="notify-read-all">Đánh dấu tất cả đã đọc</button>
            </div>
        </div>
                <div class="buffcorp-user-menu" id="buffcorp-user-menu">
                    <button class="buffcorp-user-toggle" id="buffcorp-user-toggle" type="button" aria-expanded="false" aria-controls="buffcorp-user-dropdown">
                        <span class="buffcorp-avatar">{USER_INITIAL}</span>
                        <span class="buffcorp-user-copy"><strong>{USER_DISPLAY_NAME}</strong><small>{USER_ROLE}</small></span>
                        <svg class="buffcorp-user-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m7 10 5 5 5-5"></path></svg>
                    </button>
                    <div class="buffcorp-user-dropdown" id="buffcorp-user-dropdown" aria-label="T&#224;i kho&#7843;n">
                        <div class="buffcorp-user-summary"><strong>{USER_DISPLAY_NAME}</strong><small>{USER_ROLE}</small></div>
                        <a href="{USER_PROFILE_URL}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4 21c.7-4 3.4-6 8-6s7.3 2 8 6"></path></svg>H&#7891; s&#417;</a>
                        <a href="{USER_ACCOUNT_URL}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-2.1 2.1-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.5v.2h-3v-.2a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.9.3l-.1.1-2.1-2.1.1-.1A1.7 1.7 0 0 0 7 15a1.7 1.7 0 0 0-1.5-1H5.3v-3h.2A1.7 1.7 0 0 0 7 10a1.7 1.7 0 0 0-.3-1.9l-.1-.1 2.1-2.1.1.1a1.7 1.7 0 0 0 1.9.3 1.7 1.7 0 0 0 1-1.5v-.2h3v.2a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.9-.3l.1-.1 2.1 2.1-.1.1A1.7 1.7 0 0 0 19.4 10a1.7 1.7 0 0 0 1.5 1h.2v3h-.2a1.7 1.7 0 0 0-1.5 1Z"></path></svg>C&#224;i &#273;&#7863;t t&#224;i kho&#7843;n</a>
                        <div class="buffcorp-user-divider" role="separator"></div>
                        <a class="buffcorp-user-logout" href="logout.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 5H5v14h5"></path><path d="m14 8 4 4-4 4M18 12H9"></path></svg>&#272;&#259;ng xu&#7845;t</a>
                    </div>
                </div>
            </div>
        </header>
        <main class="buffcorp-page" id="buffcorp-page">
        {MAIN_CONTENT}
        </main>
        <style id="buffcorp-demo-parity">
        .buffcorp-page-title-wrap { display: flex; min-width: 0; align-items: center; gap: 10px; }
        .buffcorp-mobile-menu,
        .buffcorp-mobile-overlay { display: none !important; }
        .buffcorp-mobile-menu {
            width: 40px;
            height: 40px;
            padding: 0;
            place-items: center;
            border: 1px solid var(--buff-line);
            border-radius: 8px;
            background: var(--buff-surface);
            color: var(--buff-text);
            cursor: pointer;
        }
        .buffcorp-mobile-menu { display: none !important; }
        .buffcorp-mobile-menu svg { width: 18px; height: 18px; }
        .buffcorp-top-actions .notify-wrap {
            display: flex;
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--buff-line);
            border-radius: 8px;
            background: var(--buff-surface);
        }
        .buffcorp-top-actions .notify-bell {
            display: grid;
            width: 38px;
            height: 38px;
            padding: 0;
            place-items: center;
            border: 0;
            background: transparent;
            color: var(--buff-text);
            cursor: pointer;
        }
        .buffcorp-top-actions .notify-bell svg { width: 18px; height: 18px; }
        .notify-panel {
            top: 0 !important;
            right: 0 !important;
            bottom: 0;
            left: auto !important;
            display: flex;
            width: 390px;
            max-width: 100%;
            max-height: none;
            flex-direction: column;
            overflow: hidden;
            border: 0;
            background: var(--buff-surface);
            color: var(--buff-text);
            box-shadow: -18px 0 45px rgba(16,24,40,.16);
            opacity: 1;
            transform: translateX(110%);
            transform-origin: right center;
            z-index: 10030;
        }
        .notify-wrap.open .notify-panel { transform: translateX(0); }
        .notify-wrap.is-empty .notify-panel {
            top: 80px !important;
            right: 16px !important;
            bottom: auto;
            width: 330px;
            max-width: calc(100vw - 32px);
            max-height: calc(100vh - 96px);
            border: 1px solid var(--buff-line);
            border-radius: 12px;
            box-shadow: 0 16px 34px rgba(16,24,40,.16);
        }
        .notify-head {
            min-height: 76px;
            padding: 18px 22px;
            border-bottom: 1px solid var(--buff-line);
            color: var(--buff-text);
        }
        .notify-head small,
        .notify-head span { display: block; }
        .notify-head small { margin-bottom: 4px; color: var(--buff-muted); font-size: 9px; letter-spacing: .8px; }
        .notify-head span { font-size: 18px; }
        .notify-close {
            display: grid;
            width: 32px;
            height: 32px;
            padding: 0;
            place-items: center;
            border: 0;
            background: transparent;
            color: var(--buff-muted);
            font-size: 27px;
            cursor: pointer;
        }
        .notify-list { min-height: 0; max-height: none; flex: 1; overflow: auto; padding: 0 20px; }
        .notify-wrap.is-empty .notify-list { min-height:126px; flex:0 0 126px; display:grid; place-items:center; padding:20px; }
        .notify-item { padding: 15px 3px; border-bottom: 1px solid var(--buff-line); color: var(--buff-text); }
        .notify-item:hover { background: var(--buff-bg); }
        .notify-item.unread { background: transparent; }
        .notify-title { color: var(--buff-text); font-size: 12px; }
        .notify-message { color: var(--buff-muted); font-size: 11px; line-height: 16px; }
        .notify-read-all {
            display: none;
            width: auto;
            height: 38px;
            margin: 16px 20px;
            border: 1px solid var(--buff-line);
            border-radius: 7px;
            background: var(--buff-bg);
            color: var(--buff-brand);
            font-size: 11px;
            font-weight: 700;
        }
        .notify-read-all.show { display:block; }

	        .payroll-panel {
	            top: 50% !important;
	            right: auto !important;
	            left: 50% !important;
	            display: flex;
	            width: min(1360px, calc(100vw - 48px));
	            max-width: calc(100vw - 48px);
	            max-height: calc(100vh - 32px);
	            flex-direction: column;
	            overflow: hidden;
	            border: 0;
	            border-radius: 12px;
	            background: var(--buff-surface);
	            color: var(--buff-text);
	            box-shadow: 0 0 0 100vmax rgba(16,24,40,.55), 0 24px 50px rgba(16,24,40,.25);
            transform: translate(-50%,-46%) scale(.97);
            transform-origin: center;
            z-index: 10040;
        }
        .payroll-wrap.open .payroll-panel { transform: translate(-50%,-50%) scale(1); }
        .payroll-head {
            display: flex;
            min-height: 46px;
            align-items: center;
            padding: 10px 52px 10px 22px;
            border-bottom: 1px solid var(--buff-line);
            background: var(--buff-surface);
            color: var(--buff-text);
	            font-size: 16px;
	            text-align: left;
	        }
	        .payroll-close {
	            top: 7px;
	            right: 14px;
	            width: 32px;
	            height: 32px;
	            color: var(--buff-muted);
	            line-height: 28px;
	        }
	        .payroll-body {
	            flex: 1 1 auto;
	            min-height: 0;
	            overflow: hidden;
	            padding: 14px 28px 16px;
	            color: var(--buff-text);
	        }
	        .payroll-filter {
	            display: flex;
	            min-height: 30px;
	            align-items: center;
	            justify-content: flex-end;
	            gap: 8px;
	            padding: 0 0 8px 0;
	            color: var(--buff-muted);
	            text-align: left;
	        }
	        .payroll-employee-filter {
	            float: none;
	            margin-right: auto;
	        }
	        .payroll-filter select { border-color: var(--buff-line); border-radius: 6px; background: var(--buff-surface); color: var(--buff-text); }
		        #payroll-content.payroll-content-ready {
		            display: grid;
		            grid-template-columns: minmax(420px,.95fr) minmax(620px,1.35fr);
		            grid-template-areas:
		                "hero stats"
		                "summary stats"
		                "table-title table-title"
		                "table table"
		                "footer footer";
		            align-items: start;
		            column-gap: 24px;
		            row-gap: 10px;
		        }
		        #payroll-content.payroll-content-ready .payroll-hero {
		            grid-area: hero;
		        }
		        #payroll-content.payroll-content-ready .payroll-summary {
		            grid-area: summary;
		        }
		        #payroll-content.payroll-content-ready .payroll-stat-grid {
		            grid-area: stats;
		        }
		        #payroll-content.payroll-content-ready .payroll-table-title {
		            grid-area: table-title;
		        }
		        #payroll-content.payroll-content-ready .payroll-table {
		            grid-area: table;
		        }
		        #payroll-content.payroll-content-ready .payroll-footer {
		            grid-area: footer;
		        }
	        #payroll-content.payroll-content-ready .payroll-hero {
	            padding: 0 0 9px 0;
	        }
	        #payroll-content.payroll-content-ready .payroll-summary {
	            border-bottom: 0;
	            padding: 8px 0 0;
	        }
		        #payroll-content.payroll-content-ready .payroll-stat-grid {
		            grid-template-columns: 1fr;
		            gap: 9px;
		            padding: 0;
		        }
	        #payroll-content.payroll-content-ready .payroll-table-title {
	            margin-top: 0;
	        }
	        #payroll-content.payroll-content-ready .payroll-hero-title {
	            margin-bottom: 5px;
	            font-size: 14px;
	            line-height: 18px;
	        }
	        #payroll-content.payroll-content-ready .payroll-amount {
	            font-size: 28px;
	            line-height: 32px;
	        }
	        #payroll-content.payroll-content-ready .payroll-badge {
	            margin-top: 3px;
	        }
	        #payroll-content.payroll-content-ready .payroll-line {
	            padding: 3px 0;
	        }
	        #payroll-content.payroll-content-ready .payroll-line.strong span,
	        #payroll-content.payroll-content-ready .payroll-line.strong b {
	            font-size: 13px;
	        }
	        #payroll-content.payroll-content-ready .payroll-stat {
	            min-height: 70px;
	            padding: 9px 11px;
	        }
	        #payroll-content.payroll-content-ready .payroll-table-title {
	            padding: 7px 9px;
	        }
	        #payroll-content.payroll-content-ready .payroll-table th,
	        #payroll-content.payroll-content-ready .payroll-table td {
	            padding: 6px 8px;
	        }
	        #payroll-content.payroll-content-ready .payroll-footer {
	            padding-top: 7px;
	        }
	        #payroll-content.payroll-content-ready .payroll-detail-btn {
	            height: 38px;
	        }
        .payroll-wrap:not(.open) .payroll-button:focus,
        .payroll-wrap:not(.open) .payroll-button:focus-visible {
	            outline: 0;
	            background: transparent !important;
	            box-shadow: none !important;
	        }
        .buffcorp-top-actions .notify-bell:focus-visible {
            outline: 2px solid var(--buff-brand);
            outline-offset: 2px;
        }
        body.buffcorp-dark .buffcorp-top-actions .notify-bell:focus-visible {
            outline-color: #8ec5ff;
        }

        .buffcorp-page .admin-dashboard,
        .sales-page,
        .kpi-page,
        .kpi-report,
        .org-page {
            min-height: 100%;
            padding: 0;
            background: transparent;
            color: var(--buff-text);
            font-family: Manrope, "Segoe UI", Arial, sans-serif;
            font-size: 12px;
        }
        .admin-dashboard-header,
        .sales-toolbar,
        .kpi-toolbar,
        .kpi-head,
        .org-head {
            margin-bottom: 18px;
            padding: 0;
            border: 0;
            background: transparent;
            box-shadow: none;
        }
        .admin-section-kicker {
            display: block;
            margin-bottom: 7px;
            color: var(--buff-brand);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.45px;
        }
        .admin-dashboard-title h1,
        .sales-toolbar h2,
        .kpi-toolbar h2,
        .kpi-head h2,
        .org-title {
            color: #123f70;
            font-family: inherit;
            font-size: 24px;
            line-height: 1.25;
        }
        .admin-dashboard-title h1 { margin-bottom: 0; font-size: 29px; letter-spacing: -.45px; }
        .admin-dashboard-actions { gap: 8px; }
        .admin-date-range,
        .admin-export-btn,
        .admin-select,
        .admin-outline-btn,
        .sales-filter,
        .kpi-filter,
        .org-actions a {
            border: 1px solid var(--buff-line);
            border-radius: 8px;
            background: var(--buff-surface);
            color: var(--buff-text);
            box-shadow: none;
        }
        .sales-filter,
        .kpi-filter { gap: 7px; padding: 8px 10px; }
        .sales-filter span,
        .kpi-filter span { color: var(--buff-muted); }
        .sales-filter select,
        .kpi-filter select,
        .sales-filter input,
        .kpi-filter input {
            min-height: 34px;
            border: 1px solid var(--buff-line);
            border-radius: 6px;
            background: var(--buff-surface);
            color: var(--buff-text);
            font: 11px Manrope, "Segoe UI", Arial, sans-serif;
        }
        .sales-filter input,
        .kpi-filter input { border-color: var(--buff-brand); background: var(--buff-brand); color: #fff; }

        .admin-card,
        .sales-card,
        .sales-panel,
        .kpi-page .kpi-card,
        .kpi-page .kpi-panel,
        .kpi-report .kpi-card,
        .kpi-report .kpi-panel,
        .kpi-report .kpi-top-panel,
        .org-card {
            overflow: hidden;
            border: 1px solid var(--buff-line);
            border-radius: 12px;
            background: var(--buff-surface);
            box-shadow: var(--buff-shadow);
        }
        .admin-kpi-grid,
        .sales-cards,
        .kpi-cards,
        .kpi-summary { gap: 14px; margin-bottom: 16px; }
        .sales-card,
        .kpi-page .kpi-card,
        .kpi-report .kpi-card { min-height: 112px; padding: 16px; }
        .sales-card .label,
        .kpi-page .kpi-card .label,
        .kpi-report .kpi-card .label {
            padding: 0;
            background: transparent;
            color: var(--buff-muted);
            font-size: 12px;
            text-align: left;
        }
        .sales-card .value,
        .kpi-page .kpi-card .value,
        .kpi-report .kpi-card .value {
            padding: 9px 0 4px;
            color: var(--buff-brand-dark);
            font-size: 25px;
            line-height: 31px;
            text-align: left;
        }
        .sales-card .note,
        .kpi-page .kpi-card .note,
        .kpi-report .kpi-card .note { padding: 0; color: var(--buff-muted); text-align: left; }
        .kpi-report .kpi-card.good .value { color: #137a58; }
        .kpi-report .kpi-card.slow .value { color: #b35f00; }
        .kpi-report .kpi-card.bad .value { color: #bb3434; }

        .admin-card-head,
        .sales-panel h3,
        .kpi-page .kpi-panel h3,
        .kpi-report .kpi-panel h3,
        .kpi-report .kpi-top-panel h3,
        .org-card-title {
            margin: 0;
            padding: 15px 18px;
            border-bottom: 1px solid var(--buff-line);
            background: linear-gradient(180deg,var(--buff-surface),var(--buff-bg));
            color: #173f67;
            font-family: inherit;
            font-size: 14px;
            line-height: 20px;
            text-align: left;
        }
        .admin-card-title { color: #173f67; font-size: 14px; }
        .admin-kpi-card { min-height: 128px; padding: 17px; }
        .admin-kpi-name { color: var(--buff-muted); font-size: 12px; }
        .admin-kpi-value { color: var(--buff-brand-dark); font-size: 25px; }
        .admin-card,
        .admin-date-range,
        .admin-export-btn,
        .admin-select,
        .admin-outline-btn { border-color: var(--buff-line); background: var(--buff-surface); box-shadow: var(--buff-shadow); }

        .sales-table,
        .kpi-table,
        .admin-table { border-collapse: collapse; background: var(--buff-surface); color: var(--buff-text); }
        .sales-table th,
        .kpi-table th,
        .admin-table th {
            padding: 11px 14px;
            border: 0;
            border-bottom: 1px solid var(--buff-line);
            background: #f1f6fb;
            color: #526a82;
            font-size: 11px;
        }
        .sales-table td,
        .kpi-table td,
        .admin-table td {
            padding: 12px 14px;
            border: 0;
            border-bottom: 1px solid var(--buff-line);
            color: var(--buff-text);
            font-size: 12px;
        }
        .sales-table tr:hover td,
        .kpi-table tr:hover td,
        .admin-table tr:hover td { background: var(--buff-bg); }
        .sales-progress,
        .kpi-progress { height: 8px; overflow: hidden; border: 0; border-radius: 5px; background: #e5edf5; }
        .sales-progress span,
        .kpi-progress span { height: 100%; border-radius: 5px; background: var(--buff-brand); }
        .org-chart-wrap { border-top: 1px solid var(--buff-line); background: var(--buff-surface); }
        .org-detail { background: var(--buff-surface); color: var(--buff-text); }

        .buffcorp-page .buffcorp-form-table.mail-form-table > tbody { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .buffcorp-page .mail-form-table .mail-content-row { grid-column: 1 / -1; }
        .buffcorp-page .mail-form-table .mail-content-row > td:last-child { min-height: 260px; }

        .buffcorp-page .config-form .config-grid-table.buffcorp-form-table > tbody { display: block; }
        .buffcorp-page .config-form .config-grid-table.buffcorp-form-table > tbody > tr {
            display: grid;
            grid-template-columns: repeat(2,minmax(0,1fr));
            gap: 14px;
            padding: 0;
            border: 0;
            background: transparent;
        }
        .buffcorp-page .config-form .config-grid-table.buffcorp-form-table > tbody > tr > td { display: block; width: auto !important; padding: 0; }
        .buffcorp-page .config-form fieldset {
            width: auto !important;
            margin: 0 0 12px !important;
            padding: 14px !important;
            border: 1px solid var(--buff-line);
            border-radius: 8px;
            background: var(--buff-bg);
        }
        .buffcorp-page .config-form legend { padding: 0 6px; color: #173f67; font-size: 12px; font-weight: 800; }
        .buffcorp-page .config-form fieldset table { width: 100%; }
        .buffcorp-page .config-form fieldset td { padding: 5px 6px !important; color: var(--buff-text); }
        .buffcorp-page .config-form fieldset input,
        .buffcorp-page .config-form fieldset textarea { width: 100%; }

        .image-library-content { min-height: 160px; padding: 16px; border-top: 1px solid var(--buff-line); box-sizing: border-box; }
        .image-library-folders td,
        .image-library-items td { padding: 10px; color: var(--buff-text); font-size: 11px; }
        .image-upload-form { display: flex; align-items: center; gap: 8px; padding: 12px 16px; border-top: 1px solid var(--buff-line); }
        .image-upload-form p { display: flex; align-items: center; gap: 8px; margin: 0; color: var(--buff-text); }

        .getpass-source-wrap {
            height: auto;
            padding: 18px;
            border: 1px solid var(--buff-line);
            border-radius: 12px;
            background: var(--buff-surface);
            color: var(--buff-text);
            font-family: Manrope, "Segoe UI", Arial, sans-serif;
            box-shadow: var(--buff-shadow);
        }
        .getpass-source-head { margin-bottom: 14px; }
        .getpass-source-wrap h2 { color: #173f67; font-family: inherit; font-size: 19px; }
        .getpass-source-total { margin: 0 0 16px; color: var(--buff-brand); font-size: 14px; }
        .getpass-source-search { margin: 0 0 14px; }
        .getpass-source-search input { width: 145px; height: 36px; color: #111; font-size: 11px; }
        .getpass-source-search button,
        .getpass-share-panel button { height: 36px; border-color: var(--buff-brand); background: var(--buff-brand); font-size: 11px; }
        .getpass-share-panel { margin: 0 0 14px; border-color: var(--buff-line); border-radius: 8px; background: var(--buff-bg); }
        .getpass-share-panel b { color: var(--buff-text); }
        .getpass-share-list { margin: 0 0 14px; }
        .getpass-table-scroll { height: auto; min-height: 0; overflow-x: auto; overflow-y: visible; border-top-color: var(--buff-line); }

        .cuttpw-wrap { padding: 0; color: var(--buff-text); font-family: Manrope, "Segoe UI", Arial, sans-serif; }
        .cuttpw-head,
        .cuttpw-card { border-color: var(--buff-line); border-radius: 12px; background: var(--buff-surface); box-shadow: var(--buff-shadow); }
        .cuttpw-head { padding: 16px; margin-bottom: 14px; }
        .cuttpw-title { color: #173f67; font-size: 19px; }
        .cuttpw-grid { gap: 14px; }
        .cuttpw-card { min-height: 104px; padding: 16px; }
        .cuttpw-label,
        .cuttpw-note { color: var(--buff-muted); }
        .cuttpw-num { color: var(--buff-brand); }

	        body.buffcorp-dark {
	            --buff-bg: #0d1117;
	            --buff-surface: #161b22;
	            --buff-text: #f2f4f7;
	            --buff-muted: #98a2b3;
            --buff-line: #30363d;
            --buff-brand: #4c8fd8;
	            --buff-brand-dark: #7fb0e6;
	            --buff-shadow: none;
	        }
	        body.buffcorp-dark .list-ui {
	            --list-bg: #0d1117;
	            --list-surface: #141b24;
	            --list-text: #edf5ff;
	            --list-muted: #a8b6c7;
	            --list-line: #334155;
	            --list-primary: #5b9cf0;
	            --list-primary-dark: #7bb3ff;
	            --list-shadow: none;
	        }
	        body.buffcorp-dark,
	        body.buffcorp-dark .main-content,
	        body.buffcorp-dark .buffcorp-page { background: var(--buff-bg); color: var(--buff-text); }
        body.buffcorp-dark .buffcorp-topbar,
        body.buffcorp-dark .buffcorp-module-card,
        body.buffcorp-dark .buffcorp-form-card,
        body.buffcorp-dark .buffcorp-module-toolbar,
        body.buffcorp-dark .left-menu,
        body.buffcorp-dark .buffcorp-sidebar,
        body.buffcorp-dark .buffcorp-brand { background: var(--buff-surface) !important; color: var(--buff-text); }
        body.buffcorp-dark .buffcorp-menu .header,
        body.buffcorp-dark .buffcorp-menu .children > a,
        body.buffcorp-dark .sidebar-support-item,
        body.buffcorp-dark .buffcorp-brand-copy strong { color: #c8dbed !important; }
        body.buffcorp-dark .buffcorp-menu .header:hover,
        body.buffcorp-dark .buffcorp-menu .mainrow > .header,
        body.buffcorp-dark .buffcorp-menu .children > a:hover,
        body.buffcorp-dark .buffcorp-menu .children > a.active,
        body.buffcorp-dark .sidebar-support-item:hover { background: #173f64 !important; color: #fff !important; }
	        body.buffcorp-dark .buffcorp-page input,
	        body.buffcorp-dark .buffcorp-page select,
	        body.buffcorp-dark .buffcorp-page textarea,
	        body.buffcorp-dark .buffcorp-global-search,
	        body.buffcorp-dark .buffcorp-global-search input { border-color: var(--buff-line); background: var(--buff-surface); color: var(--buff-text); }
	        body.buffcorp-dark .list-ui,
	        body.buffcorp-dark .list-ui .list-page,
	        body.buffcorp-dark .list-ui .list-header,
	        body.buffcorp-dark .list-ui .list-filter-panel,
	        body.buffcorp-dark .list-ui .list-content {
	            background: var(--list-bg) !important;
	            color: var(--list-text) !important;
	        }
	        body.buffcorp-dark .list-ui .list-page,
	        body.buffcorp-dark .list-ui .list-filter-card,
	        body.buffcorp-dark .list-ui .list-table-scroll {
	            border-color: var(--list-line) !important;
	            background: var(--list-surface) !important;
	            box-shadow: none !important;
	        }
	        body.buffcorp-dark .list-ui .list-title h1,
	        body.buffcorp-dark .list-ui .filter-field label {
	            color: #d8eaff !important;
	        }
	        body.buffcorp-dark .list-ui .list-title p {
	            color: var(--list-muted) !important;
	        }
	        body.buffcorp-dark .list-ui .list-search-control,
	        body.buffcorp-dark .list-ui .list-filter-form select,
	        body.buffcorp-dark .list-ui .list-filter-form input:not([type="checkbox"]):not([type="radio"]):not([type="image"]),
	        body.buffcorp-dark .list-ui .list-filter-form textarea {
	            border-color: var(--list-line) !important;
	            background: #101822 !important;
	            color: var(--list-text) !important;
	        }
	        body.buffcorp-dark .list-ui .list-search-control input {
	            color: var(--list-text) !important;
	        }
	        body.buffcorp-dark .list-ui .list-search-control input::placeholder,
	        body.buffcorp-dark .list-ui .list-filter-form input::placeholder,
	        body.buffcorp-dark .list-ui .list-filter-form textarea::placeholder {
	            color: #8394a8 !important;
	        }
	        body.buffcorp-dark .list-ui .list-filter-form select {
	            background-image: linear-gradient(45deg, transparent 50%, #d8eaff 50%), linear-gradient(135deg, #d8eaff 50%, transparent 50%) !important;
	        }
	        body.buffcorp-dark .list-ui .filter-control-wrap > svg,
	        body.buffcorp-dark .list-ui .list-search-control svg {
	            color: #8fb6e8 !important;
	        }
	        body.buffcorp-dark .list-ui .list-btn-secondary,
	        body.buffcorp-dark .list-ui .list-filter-form > a {
	            border-color: var(--list-line) !important;
	            background: #101822 !important;
	            color: #dcecff !important;
	        }
	        body.buffcorp-dark .list-ui .list-btn-secondary:hover,
	        body.buffcorp-dark .list-ui .list-filter-form > a:hover {
	            border-color: #5b9cf0 !important;
	            background: #16263a !important;
	            color: #fff !important;
	        }
	        body.buffcorp-dark .list-ui .data-table {
	            background: var(--list-surface) !important;
	            color: var(--list-text) !important;
	        }
	        body.buffcorp-dark .list-ui .data-table tr.header td,
	        body.buffcorp-dark .list-ui .data-table tr.header th {
	            border-color: var(--list-line) !important;
	            background: #1b2635 !important;
	            color: #c8d7ea !important;
	        }
	        body.buffcorp-dark .list-ui .data-table td {
	            border-color: #273445 !important;
	            background: var(--list-surface) !important;
	            color: var(--list-text) !important;
	        }
	        body.buffcorp-dark .list-ui .data-table tr:nth-child(even) td {
	            background: #111a25 !important;
	        }
	        body.buffcorp-dark .list-ui .data-table tr:hover td {
	            background: #18283a !important;
	        }
	        body.buffcorp-dark .list-ui .data-table a:not(.list-row-action) {
	            color: #8ec5ff !important;
	        }
	        body.buffcorp-dark .list-ui .note-box,
	        body.buffcorp-dark .list-ui .attendance-note-list {
	            scrollbar-color: #52657c #101822;
	        }
	        body.buffcorp-dark .sales-table th,
	        body.buffcorp-dark .kpi-table th,
	        body.buffcorp-dark .admin-table th { background: #1c222b !important; color: #b8c9da !important; }
        body.buffcorp-dark .buffcorp-user-toggle:hover,
        body.buffcorp-dark .buffcorp-user-menu.open .buffcorp-user-toggle,
        body.buffcorp-dark .buffcorp-user-dropdown a:hover { background: #1c334a; color: #d8eaff; }
        body.buffcorp-dark .buffcorp-top-actions .payroll-button:hover,
        body.buffcorp-dark .buffcorp-top-actions .notify-bell:hover,
        body.buffcorp-dark .buffcorp-top-actions .payroll-wrap.open .payroll-button,
        body.buffcorp-dark .buffcorp-top-actions .notify-wrap.open .notify-bell { background: #1c334a; color: #d8eaff; }
        body.buffcorp-dark .buffcorp-user-dropdown { box-shadow: 0 14px 30px rgba(0, 0, 0, .35); }
        body.buffcorp-dark .buffcorp-user-dropdown .buffcorp-user-logout:hover { background: #3a2024; color: #ffb4b4; }
        body.buffcorp-dark .admin-dashboard-title h1,
        body.buffcorp-dark .sales-toolbar h2,
        body.buffcorp-dark .kpi-toolbar h2,
        body.buffcorp-dark .kpi-head h2,
        body.buffcorp-dark .org-title,
        body.buffcorp-dark .admin-card-title,
        body.buffcorp-dark .sales-panel h3,
        body.buffcorp-dark .kpi-panel h3,
	        body.buffcorp-dark .org-card-title { color: #d8eaff; }
        @media (max-width: 1280px) {
            .admin-kpi-grid,
            .admin-finance-grid { grid-template-columns: repeat(3,minmax(0,1fr)); }
        }
        @media (max-width: 1050px) {
            .admin-kpi-grid,
            .admin-finance-grid { grid-template-columns: repeat(2,minmax(0,1fr)); }
        }
	        @media (max-width: 820px) {
            .left-menu {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                width: 280px !important;
                flex-basis: 280px !important;
                transform: translateX(-110%);
                transition: transform .22s ease;
                z-index: 10020;
            }
            .layout.menu-open .left-menu { transform: translateX(0); }
            .layout.menu-open .buffcorp-mobile-overlay {
                position: fixed;
                display: block;
                inset: 0;
                padding: 0;
                border: 0;
                background: rgba(16,24,40,.5);
                z-index: 10010;
            }
            .buffcorp-mobile-menu { display: grid !important; }
            .layout.menu-open .buffcorp-mobile-overlay { display: block !important; }
            .layout.sidebar-collapsed .left-menu { width: 280px !important; flex-basis: 280px !important; }
            .layout.sidebar-collapsed .buffcorp-brand-copy,
            .layout.sidebar-collapsed .buffcorp-section-label,
            .layout.sidebar-collapsed .app-parent-label,
            .layout.sidebar-collapsed .sidebar-support-text { display: block !important; }
            .layout.sidebar-collapsed .app-parent-chevron,
            .layout.sidebar-collapsed .app-nav-badge { display: grid !important; }
            .layout.sidebar-collapsed .buffcorp-collapse { display: grid !important; }
            .layout.sidebar-collapsed .buffcorp-menu .header,
            .layout.sidebar-collapsed .sidebar-support-item { justify-content: space-between; }
	            .buffcorp-page-title { font-size: 15px; }
	            .payroll-panel {
	                width: calc(100vw - 24px);
	                max-width: calc(100vw - 24px);
	            }
	            #payroll-content.payroll-content-ready {
	                display: block;
	            }
	            #payroll-content.payroll-content-ready .payroll-summary {
	                border-bottom: 1px solid var(--buff-line);
	            }
	            #payroll-content.payroll-content-ready .payroll-stat-grid {
	                grid-template-columns: 1fr 1fr;
	                padding-top: 10px;
	            }
	        }
        @media (max-width: 620px) {
            .buffcorp-topbar { gap: 8px; }
            .buffcorp-top-actions { gap: 5px; }
            .buffcorp-top-actions .payroll-wrap,
            .buffcorp-top-actions .notify-wrap { width: 37px; height: 37px; flex-basis: 37px; }
            .admin-dashboard-header,
            .sales-toolbar,
            .kpi-toolbar,
            .kpi-head { align-items: stretch; flex-direction: column; }
            .admin-kpi-grid,
            .admin-finance-grid { grid-template-columns: 1fr; }
            .buffcorp-page .buffcorp-form-table.mail-form-table > tbody,
            .buffcorp-page .config-form .config-grid-table.buffcorp-form-table > tbody > tr { grid-template-columns: 1fr; }
        }
        </style>
        <script type="text/javascript">
        (function () {
            function initializeMainShell() {
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

                var currentOption = '{CURRENT_OPTION}';
                var currentMode = '{CURRENT_MODE}';
                var currentUrl = null;
                try {
                    currentUrl = new URL(window.location.href);
                    currentOption = currentUrl.searchParams.get('option') || currentOption;
                    currentMode = currentUrl.searchParams.get('mode') || currentMode;
                } catch (e) { /* keep back-end route */ }

                var links = document.querySelectorAll('#buffcorp-menu .children a[href]');
                var currentLink = null;
                var currentLinkScore = -1;
                for (var i = 0; i < links.length; i++) {
                    try {
                        var targetUrl = new URL(links[i].href, window.location.href);
                        if ((targetUrl.searchParams.get('option') || '') !== currentOption) continue;
                        var targetMode = targetUrl.searchParams.get('mode');
                        if (targetMode && targetMode !== currentMode) continue;
                        var score = targetMode ? 3 : 1;
                        var routeKeys = ['menu', 'category', 'cid'];
                        var routeMatches = true;
                        for (var routeIndex = 0; routeIndex < routeKeys.length; routeIndex++) {
                            var targetValue = targetUrl.searchParams.get(routeKeys[routeIndex]);
                            if (!targetValue) continue;
                            if (!currentUrl || currentUrl.searchParams.get(routeKeys[routeIndex]) !== targetValue) {
                                routeMatches = false;
                                break;
                            }
                            score++;
                        }
                        if (routeMatches && score > currentLinkScore) {
                            currentLink = links[i];
                            currentLinkScore = score;
                        }
                    } catch (e) { /* ignore invalid legacy link */ }
                }
                if (currentLink && currentLink.className.indexOf('active') < 0) currentLink.className += (currentLink.className ? ' ' : '') + 'active';

                function initializeModernLists() {
                    var modules = document.querySelectorAll('.list-ui .list-page[data-layout="list"]');
                    function textOf(node) {
                        return String((node && (node.textContent || node.innerText)) || '').replace(/\s+/g, ' ').replace(/^\s+|\s+$/g, '');
                    }
                    function addClass(node, name) {
                        if (node && (' ' + node.className + ' ').indexOf(' ' + name + ' ') < 0) node.className += (node.className ? ' ' : '') + name;
                    }
                    function removeClass(node, name) {
                        if (node) node.className = node.className.replace(new RegExp('(^|\\s)' + name + '(?=\\s|$)', 'g'), ' ').replace(/\s+/g, ' ').replace(/^\s+|\s+$/g, '');
                    }
                    function makeNode(tag, className, text) {
                        var node = document.createElement(tag);
                        if (className) node.className = className;
                        if (typeof text !== 'undefined') node.textContent = text;
                        return node;
                    }
                    function closestMenu(node) {
                        while (node && node !== document) {
                            if ((' ' + (node.className || '') + ' ').indexOf(' list-action-menu ') >= 0) return node;
                            node = node.parentNode;
                        }
                        return null;
                    }
                    function positionActionMenu(menu) {
                        if (!menu) return;
                        var button = menu.querySelector('.list-action-menu-button');
                        var panel = menu.querySelector('.list-action-menu-panel');
                        if (!button || !panel) return;
                        var rect = button.getBoundingClientRect();
                        panel.style.top = '0px';
                        panel.style.left = 'auto';
                        panel.style.right = Math.max(12, Math.round(window.innerWidth - rect.right)) + 'px';
                        var panelRect = panel.getBoundingClientRect();
                        var top = rect.bottom + 6;
                        if (top + panelRect.height > window.innerHeight - 12) {
                            top = Math.max(12, rect.top - panelRect.height - 6);
                        }
                        panel.style.top = Math.round(top) + 'px';
                    }
	                    function closeActionMenus(exceptMenu) {
	                        var openMenus = document.querySelectorAll('.list-action-menu.open');
	                        for (var closeIndex = 0; closeIndex < openMenus.length; closeIndex++) {
	                            if (openMenus[closeIndex] !== exceptMenu) removeClass(openMenus[closeIndex], 'open');
	                        }
	                    }
	                    function closestTableCell(node) {
	                        while (node && node !== document) {
	                            var tag = node.tagName ? node.tagName.toLowerCase() : '';
	                            if (tag === 'td' || tag === 'th') return node;
	                            node = node.parentNode;
	                        }
	                        return null;
	                    }
	                    function isEmptyTrailingActionCell(cell) {
	                        if (!cell || textOf(cell)) return false;
	                        return !cell.querySelector('a,button,input,select,textarea,img,svg,.list-row-actions,.list-action-menu');
	                    }
	                    function normalizeActionHeader(headerRow) {
	                        if (!headerRow) return;
	                        var cells = headerRow.querySelectorAll('td,th');
	                        var actionCell = null;
	                        for (var i = 0; i < cells.length; i++) {
	                            var label = textOf(cells[i]).toLowerCase();
	                            if ((' ' + (cells[i].className || '') + ' ').indexOf(' list-actions-head ') >= 0 || label === 'thao tác') actionCell = cells[i];
	                        }
	                        if (!actionCell) return;
	                        addClass(actionCell, 'list-actions-head');
	                        addClass(actionCell, 'buffcorp-actions-head');
	                        var sibling = actionCell.nextElementSibling;
	                        while (sibling) {
	                            var next = sibling.nextElementSibling;
	                            if (isEmptyTrailingActionCell(sibling)) sibling.parentNode.removeChild(sibling);
	                            sibling = next;
	                        }
	                    }
	                    function normalizeActionTail(row, actionHost) {
	                        if (!row || !actionHost) return;
	                        var actionCell = closestTableCell(actionHost);
	                        if (!actionCell) return;
	                        addClass(actionCell, 'list-actions-cell');
	                        var sibling = actionCell.nextElementSibling;
	                        while (sibling) {
	                            var next = sibling.nextElementSibling;
	                            if (isEmptyTrailingActionCell(sibling)) sibling.parentNode.removeChild(sibling);
	                            sibling = next;
	                        }
	                    }
	                    document.addEventListener('click', function (event) {
	                        var menu = closestMenu(event.target);
	                        closeActionMenus(menu);
	                    });
                    for (var moduleIndex = 0; moduleIndex < modules.length; moduleIndex++) {
                        (function (module) {
                            var table = module.querySelector('table.data-table');
                            if (!table) return;

                            var headerRow = table.querySelector('tr.header');
	                            normalizeActionHeader(headerRow);
	                            var headerCells = headerRow ? headerRow.querySelectorAll('td,th') : [];
                            var headers = [];
                            for (var h = 0; h < headerCells.length; h++) {
                                headers.push(textOf(headerCells[h]) || (h === 0 ? '#' : 'Thông tin'));
                            }

                            var rows = [];
                            var tableRows = table.getElementsByTagName('tr');
                            for (var rowIndex = 0; rowIndex < tableRows.length; rowIndex++) {
                                if ((' ' + tableRows[rowIndex].className + ' ').indexOf(' header ') >= 0) continue;
                                rows.push(tableRows[rowIndex]);
                            }

                            for (var rowSetup = 0; rowSetup < rows.length; rowSetup++) {
                                var cells = rows[rowSetup].getElementsByTagName('td');
                                if (module.className && module.className.indexOf('list-page--customer-customer') >= 0 && cells[0]) {
                                    var markerStyle = (cells[0].getAttribute('style') || '').toLowerCase();
                                    if (markerStyle.indexOf('green') >= 0) addClass(rows[rowSetup], 'customer-type-green');
                                    else if (markerStyle.indexOf('yellow') >= 0) addClass(rows[rowSetup], 'customer-type-yellow');
                                    else if (markerStyle.indexOf('grey') >= 0 || markerStyle.indexOf('gray') >= 0) addClass(rows[rowSetup], 'customer-type-grey');
                                }
                                if (module.className && module.className.indexOf('list-page--common-lists-website') >= 0 && cells[0]) {
                                    var websiteMarkerStyle = (cells[0].getAttribute('style') || '').toLowerCase();
                                    if (websiteMarkerStyle.indexOf('yellow') >= 0) addClass(rows[rowSetup], 'website-type-yellow');
                                    else if (websiteMarkerStyle.indexOf('orange') >= 0) addClass(rows[rowSetup], 'website-type-orange');
                                    else if (websiteMarkerStyle.indexOf('afd788') >= 0) addClass(rows[rowSetup], 'website-type-lime');
                                    else if (websiteMarkerStyle.indexOf('d7d7d7') >= 0) addClass(rows[rowSetup], 'website-type-grey');
                                    else if (websiteMarkerStyle.indexOf('98d0b9') >= 0) addClass(rows[rowSetup], 'website-type-mint');
                                    else if (websiteMarkerStyle.indexOf('green') >= 0) addClass(rows[rowSetup], 'website-type-green');
                                }
	                                var actionHost = rows[rowSetup].querySelector('.list-row-actions');
	                                normalizeActionTail(rows[rowSetup], actionHost);
	                                cells = rows[rowSetup].getElementsByTagName('td');
	                                for (var cellIndex = 0; cellIndex < cells.length; cellIndex++) {
	                                    cells[cellIndex].setAttribute('data-label', headers[cellIndex] || 'Thông tin');
	                                    if (!cells[cellIndex].getAttribute('title')) cells[cellIndex].setAttribute('title', textOf(cells[cellIndex]));
	                                }
	                                if (actionHost && actionHost.parentNode && !actionHost.querySelector('.list-action-menu')) {
                                    var menuWrap = makeNode('span', 'list-action-menu');
                                    var menuButton = makeNode('button', 'list-action-menu-button', '⋯');
                                    menuButton.type = 'button';
                                    menuButton.setAttribute('aria-label', 'Mở thao tác');
                                    var menuPanel = makeNode('span', 'list-action-menu-panel');
                                    while (actionHost.firstChild) menuPanel.appendChild(actionHost.firstChild);
                                    menuButton.onclick = function (event) {
                                        if (event && event.stopPropagation) event.stopPropagation();
                                        var parent = this.parentNode;
                                        if ((' ' + parent.className + ' ').indexOf(' open ') >= 0) removeClass(parent, 'open');
                                        else {
                                            closeActionMenus(parent);
                                            addClass(parent, 'open');
                                            positionActionMenu(parent);
                                        }
                                    };
                                    menuWrap.appendChild(menuButton);
                                    menuWrap.appendChild(menuPanel);
                                    actionHost.appendChild(menuWrap);
                                }
                            }

                            var searchInput = module.querySelector('.list-search-control input[type="search"]');
                            var filterToggle = module.querySelector('.list-filter-toggle');
                            var filterPanel = module.querySelector('.list-filter-fields');
                            var cards = module.querySelector('.mobile-list-cards');
                            var refreshButton = module.querySelector('[data-list-refresh]');
                            var clearButton = module.querySelector('[data-list-clear]');
                            var sortIndex = -1;
                            var sortDir = 1;

                            function keepFilterInteraction(event) {
                                if (event && event.stopPropagation) event.stopPropagation();
                                else if (event) event.cancelBubble = true;
                            }

                            if (filterToggle && filterPanel) {
                                filterToggle.onclick = function (event) {
                                    keepFilterInteraction(event);
                                    var isOpen = (' ' + filterPanel.className + ' ').indexOf(' open ') >= 0;
                                    if (isOpen) removeClass(filterPanel, 'open');
                                    else addClass(filterPanel, 'open');
                                    filterToggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
                                };
                                filterPanel.onclick = keepFilterInteraction;
                                filterPanel.onmousedown = keepFilterInteraction;
                                filterPanel.ontouchstart = keepFilterInteraction;
                            }

                            function rowText(row) {
                                return textOf(row).toLowerCase();
                            }
                            function filteredRows() {
                                var query = searchInput ? String(searchInput.value || '').toLowerCase().replace(/^\s+|\s+$/g, '') : '';
                                var matched = [];
                                for (var i = 0; i < rows.length; i++) {
                                    if (!query || rowText(rows[i]).indexOf(query) >= 0) matched.push(rows[i]);
                                }
                                if (sortIndex >= 0) {
                                    matched.sort(function (a, b) {
                                        var aText = textOf(a.getElementsByTagName('td')[sortIndex]).toLowerCase();
                                        var bText = textOf(b.getElementsByTagName('td')[sortIndex]).toLowerCase();
                                        var aNum = parseFloat(aText.replace(/[^\d.-]/g, ''));
                                        var bNum = parseFloat(bText.replace(/[^\d.-]/g, ''));
                                        if (!isNaN(aNum) && !isNaN(bNum) && aText.match(/\d/) && bText.match(/\d/)) return (aNum - bNum) * sortDir;
                                        return aText.localeCompare(bText) * sortDir;
                                    });
                                }
                                return matched;
                            }
                            function filterLabelFor(control) {
                                var key = (control.getAttribute('name') || control.getAttribute('id') || '').toLowerCase();
                                var labels = {
                                    customer_id1: 'Dự án',
                                    member_id1: 'NV Quản lý',
                                    kt_id1: 'NV Kỹ thuật',
                                    content_id1: 'NV Content',
                                    code_id1: 'Code',
                                    website_type_id1: 'Phân loại',
                                    dat_kpi1: 'Đạt KPI',
                                    created_month: 'Tháng tạo',
                                    created_year: 'Năm tạo',
                                    expire_month: 'Tháng hết hạn',
                                    expire_year: 'Năm hết hạn',
                                    active: 'Active'
                                };
                                if (labels[key]) return labels[key];
                                return key ? key.replace(/[_-]+/g, ' ').replace(/\b\w/g, function (ch) { return ch.toUpperCase(); }) : 'Bộ lọc';
                            }
                            function previousTextLabel(control) {
                                var node = control.previousSibling;
                                while (node) {
                                    if (node.nodeType === 3) {
                                        var value = String(node.nodeValue || '').replace(/\s+/g, ' ').replace(/^\s+|\s+$/g, '');
                                        if (value && value.length < 28) {
                                            node.nodeValue = '';
                                            value = value.replace(/^Danh\s+sách\s*[:：]\s*/i, '');
                                            return value.replace(/[:：]+$/g, '');
                                        }
                                    }
                                    if (node.nodeType === 1 && node.tagName && node.tagName.toLowerCase() === 'label') {
                                        var labelText = textOf(node).replace(/[:：]+$/g, '');
                                        node.parentNode.removeChild(node);
                                        return labelText;
                                    }
                                    node = node.previousSibling;
                                }
                                return '';
                            }
                            function filterControlIcon(control) {
                                var key = (control.getAttribute('name') || control.getAttribute('id') || '').toLowerCase();
                                var tag = control.tagName ? control.tagName.toLowerCase() : '';
                                if (key.indexOf('month') >= 0 || key.indexOf('year') >= 0 || key.indexOf('date') >= 0 || key === 'month' || key === 'year') {
                                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4M16 2v4M3 10h18"></path><rect x="4" y="4" width="16" height="18" rx="2"></rect></svg>';
                                }
                                if (tag === 'select') {
                                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 6h16M7 12h10M10 18h4"></path></svg>';
                                }
                                if (key.indexOf('member') >= 0 || key.indexOf('name') >= 0 || key.indexOf('user') >= 0 || key.indexOf('keyword') >= 0) {
                                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>';
                                }
                                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg>';
                            }
                            function actionIconForText(text) {
                                var normalized = String(text || '').toLowerCase();
                                if (normalized.indexOf('excel') >= 0 || normalized.indexOf('export') >= 0 || normalized.indexOf('xuất') >= 0) {
                                    return '<img class="list-export-icon" src="templates/default/images/excel-xlsx-icon.png" alt="" aria-hidden="true">';
                                }
                                return '';
                            }
                            function activeFilterCount(form) {
                                if (!form) return 0;
                                var count = 0;
                                var controls = form.querySelectorAll('input:not([type="hidden"]):not([type="image"]):not([type="button"]),select,textarea');
                                for (var i = 0; i < controls.length; i++) {
                                    var control = controls[i];
                                    var type = (control.getAttribute('type') || '').toLowerCase();
                                    if (type === 'submit' || control.tagName.toLowerCase() === 'button') continue;
                                    if ((type === 'checkbox' || type === 'radio')) {
                                        if (control.checked) count++;
                                        continue;
                                    }
                                    var value = String(control.value || '').replace(/^\s+|\s+$/g, '');
                                    if (!value || value === '0' || value.toLowerCase() === 'all' || value.toLowerCase() === 'tất cả') continue;
                                    count++;
                                }
                                return count;
                            }
                            function updateFilterBadge(form) {
                                if (!filterToggle) return;
                                var label = filterToggle.querySelector('span');
                                if (!label) return;
                                var count = activeFilterCount(form);
                                label.textContent = count ? 'Bộ lọc (' + count + ')' : 'Bộ lọc';
                            }
                            function enhanceFilterForm() {
                                var form = module.querySelector('.list-filter-form');
                                if (!form || form.getAttribute('data-list-enhanced') === '1') return;
                                form.setAttribute('data-list-enhanced', '1');
                                var secondaryActionRow = module.querySelector('.filter-actions__secondary');
                                var primaryActionRow = module.querySelector('.filter-actions__primary');
                                var submitButton = form.querySelector('button[type="submit"],input[type="submit"]');
                                if (submitButton && primaryActionRow) {
                                    var formId = form.getAttribute('id');
                                    if (!formId) {
                                        formId = 'list-filter-form-' + moduleIndex;
                                        form.setAttribute('id', formId);
                                    }
                                    submitButton.setAttribute('form', formId);
                                    submitButton.setAttribute('type', 'submit');
                                    addClass(submitButton, 'list-btn');
                                    addClass(submitButton, 'list-btn-primary');
                                    primaryActionRow.insertBefore(submitButton, primaryActionRow.firstChild);
                                }
                                if (secondaryActionRow) {
                                    var nativeActions = form.querySelectorAll('a');
                                    for (var actionIndex = nativeActions.length - 1; actionIndex >= 0; actionIndex--) {
                                        var actionLink = nativeActions[actionIndex];
                                        if (actionLink.parentNode !== form) continue;
                                        addClass(actionLink, 'list-btn');
                                        addClass(actionLink, 'list-btn-secondary');
                                        var nestedButton = actionLink.querySelector('input[type="button"]');
                                        if (nestedButton) {
                                            var nestedText = nestedButton.value || textOf(actionLink);
                                            nestedButton.parentNode.removeChild(nestedButton);
                                            actionLink.appendChild(document.createTextNode(nestedText));
                                        }
                                        var icon = actionIconForText(textOf(actionLink));
                                        if (icon && !actionLink.querySelector('svg') && !actionLink.querySelector('img')) {
                                            var iconHost = makeNode('span', '');
                                            iconHost.innerHTML = icon;
                                            actionLink.insertBefore(iconHost.firstChild, actionLink.firstChild);
                                        }
                                        secondaryActionRow.insertBefore(actionLink, secondaryActionRow.firstChild);
                                    }
                                }
                                var controls = form.querySelectorAll('select,input:not([type="hidden"]):not([type="image"]):not([type="button"]),button[type="submit"]');
                                for (var i = 0; i < controls.length; i++) {
                                    var control = controls[i];
                                    if (control.closest && control.closest('.filter-field')) continue;
                                    var type = (control.getAttribute('type') || '').toLowerCase();
                                    var wrapClass = type === 'submit' || control.tagName.toLowerCase() === 'button'
                                        ? 'filter-field filter-field-submit'
                                        : 'filter-field';
                                    if (type === 'checkbox') wrapClass += ' filter-field-checkbox';
                                    if (type === 'radio') wrapClass += ' filter-field-radio';
                                    if (type === 'submit' || control.tagName.toLowerCase() === 'button') submitButton = control;
                                    var wrap = makeNode('div', wrapClass);
                                    if (type !== 'submit' && control.tagName.toLowerCase() !== 'button') {
                                        var label = makeNode('label', '', previousTextLabel(control) || filterLabelFor(control));
                                        var id = control.getAttribute('id');
                                        if (!id) {
                                            id = 'list-filter-' + moduleIndex + '-' + i;
                                            control.setAttribute('id', id);
                                        }
                                        label.setAttribute('for', id);
                                        wrap.appendChild(label);
                                    }
                                    control.parentNode.insertBefore(wrap, control);
                                    if (type !== 'checkbox' && type !== 'radio' && type !== 'submit' && control.tagName.toLowerCase() !== 'button') {
                                        var controlWrap = makeNode('span', 'filter-control-wrap');
                                        controlWrap.innerHTML = filterControlIcon(control);
                                        controlWrap.onclick = keepFilterInteraction;
                                        controlWrap.onmousedown = keepFilterInteraction;
                                        controlWrap.ontouchstart = keepFilterInteraction;
                                        control.onclick = keepFilterInteraction;
                                        control.onmousedown = keepFilterInteraction;
                                        control.ontouchstart = keepFilterInteraction;
                                        controlWrap.appendChild(control);
                                        wrap.appendChild(controlWrap);
                                    } else {
                                        wrap.appendChild(control);
                                    }
                                }
                                form.onsubmit = function () {
                                    var panel = module.querySelector('.filter-panel');
                                    if (panel) addClass(panel, 'is-submitting');
                                    if (submitButton) {
                                        submitButton.disabled = true;
                                        addClass(submitButton, 'is-loading');
                                    }
                                };
                                form.onchange = function () { updateFilterBadge(form); };
                                form.oninput = function () { updateFilterBadge(form); };
                                if (clearButton) {
                                    clearButton.onclick = function () {
                                        form.reset();
                                        if (searchInput) searchInput.value = '';
                                        updateFilterBadge(form);
                                        form.submit();
                                    };
                                }
                                var child = form.firstChild;
                                while (child) {
                                    var next = child.nextSibling;
                                    if (child.nodeType === 3 && String(child.nodeValue || '').replace(/\s+/g, '') !== '') {
                                        child.nodeValue = '';
                                    }
                                    child = next;
                                }
                                updateFilterBadge(form);
                            }
                            function addCardField(cardMeta, label, valueNode) {
                                if (!valueNode || !textOf(valueNode)) return;
                                var field = makeNode('div', 'mobile-list-card-field');
                                field.appendChild(makeNode('b', '', label));
                                var value = makeNode('span', '');
                                value.innerHTML = valueNode.innerHTML;
                                field.appendChild(value);
                                cardMeta.appendChild(field);
                            }
                            function buildCard(row) {
                                var cells = row.getElementsByTagName('td');
                                var card = makeNode('article', 'mobile-list-card');
                                var head = makeNode('div', 'mobile-list-card-head');
                                var titleIndex = cells.length > 2 ? 2 : (cells.length > 1 ? 1 : 0);
                                var title = makeNode('div', 'mobile-list-card-title');
                                title.innerHTML = cells[titleIndex] ? cells[titleIndex].innerHTML : textOf(row);
                                head.appendChild(title);
                                var actionMenu = row.querySelector('.list-action-menu');
                                if (actionMenu) head.appendChild(actionMenu.cloneNode(true));
                                card.appendChild(head);
                                var metaWrap = makeNode('div', 'mobile-list-card-meta');
                                var shown = 0;
                                for (var i = 0; i < cells.length && shown < 5; i++) {
                                    if (i === titleIndex) continue;
                                    if ((' ' + cells[i].className + ' ').indexOf(' list-actions-cell ') >= 0) continue;
                                    var label = headers[i] || 'Thông tin';
                                    if (!label || label === '#') continue;
                                    addCardField(metaWrap, label, cells[i]);
                                    shown++;
                                }
                                card.appendChild(metaWrap);
                                return card;
                            }
                            function renderCards(pageRows) {
                                if (!cards) return;
                                cards.innerHTML = '';
                                for (var i = 0; i < pageRows.length; i++) cards.appendChild(buildCard(pageRows[i]));
                                var menuButtons = cards.querySelectorAll('.list-action-menu-button');
                                for (var j = 0; j < menuButtons.length; j++) {
                                    menuButtons[j].onclick = function (event) {
                                        if (event && event.stopPropagation) event.stopPropagation();
                                        var parent = this.parentNode;
                                        if ((' ' + parent.className + ' ').indexOf(' open ') >= 0) removeClass(parent, 'open');
                                        else {
                                            closeActionMenus(parent);
                                            addClass(parent, 'open');
                                            positionActionMenu(parent);
                                        }
                                    };
                                }
                            }
                            function render() {
                                var visibleRows = filteredRows();

                                for (var i = 0; i < rows.length; i++) rows[i].style.display = 'none';
                                for (var j = 0; j < visibleRows.length; j++) visibleRows[j].style.display = '';
                                renderCards(visibleRows);

                                if (visibleRows.length) removeClass(module, 'list-empty');
                                else addClass(module, 'list-empty');
                                addClass(module, 'list-ready');
                                module._listVisibleRows = visibleRows;
                            }

                            for (var headIndex = 0; headIndex < headerCells.length; headIndex++) {
                                (function (index) {
	                                    if ((' ' + headerCells[index].className + ' ').indexOf(' buffcorp-actions-head ') >= 0 || (' ' + headerCells[index].className + ' ').indexOf(' list-actions-head ') >= 0) return;
                                    headerCells[index].style.cursor = 'pointer';
                                    headerCells[index].setAttribute('title', 'Sắp xếp theo ' + (headers[index] || 'cột này'));
                                    headerCells[index].onclick = function () {
                                        sortDir = sortIndex === index ? sortDir * -1 : 1;
                                        sortIndex = index;
                                        render();
                                    };
                                })(headIndex);
                            }
                            if (searchInput) searchInput.oninput = render;
                            if (refreshButton) refreshButton.onclick = function () { window.location.reload(); };
                            enhanceFilterForm();
                            render();
                        })(modules[moduleIndex]);
                    }
                }
                initializeModernLists();

                var search = document.getElementById('buffcorp-global-search');
                var results = document.getElementById('buffcorp-search-results');
                function closeSearch() {
                    if (results) results.className = 'buffcorp-search-results';
                }
                if (search && results) {
                    search.oninput = function () {
                        var query = String(search.value || '').toLowerCase().replace(/^\s+|\s+$/g, '');
                        results.innerHTML = '';
                        if (!query) return closeSearch();
                        var found = 0;
                        for (var n = 0; n < links.length && found < 8; n++) {
                            var label = (links[n].textContent || '').replace(/^\s+|\s+$/g, '');
                            if (label.toLowerCase().indexOf(query) < 0) continue;
                            var item = document.createElement('a');
                            item.href = links[n].href;
                            item.textContent = label;
                            results.appendChild(item);
                            found++;
                        }
                        if (!found) {
                            var empty = document.createElement('span');
                            empty.className = 'buffcorp-search-empty';
                            empty.textContent = 'Không tìm thấy chức năng';
                            results.appendChild(empty);
                        }
                        results.className = 'buffcorp-search-results open';
                    };
                    document.addEventListener('click', function (event) {
                        if (!results.contains(event.target) && event.target !== search) closeSearch();
                    });
                }
            }

            if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initializeMainShell);
            else initializeMainShell();
        })();
        </script>
        <script type="text/javascript">
        (function () {
            function initializeDemoParity() {
                var body = document.body;
                var layout = document.querySelector('.layout');
                var menuButton = document.getElementById('buffcorp-mobile-menu');
                var overlay = document.getElementById('buffcorp-mobile-overlay');
                if (!body || !layout) return;

                function hasClass(node, name) {
                    return (' ' + node.className + ' ').indexOf(' ' + name + ' ') >= 0;
                }
                function setClass(node, name, enabled) {
                    if (enabled && !hasClass(node, name)) node.className += ' ' + name;
                    if (!enabled) node.className = node.className.replace(new RegExp('(^|\\s)' + name + '(?=\\s|$)', 'g'), ' ').replace(/\s+/g, ' ').replace(/^\s+|\s+$/g, '');
                }
                function initializeRouteLoader() {
                    var loader = document.getElementById('buffcorp-route-loader');
                    if (!loader) return;
                    function showLoader() {
                        try { sessionStorage.setItem('buffcorp-route-loading', '1'); } catch (e) { /* storage unavailable */ }
                        loader.className = 'buffcorp-route-loader show';
                        loader.setAttribute('aria-hidden', 'false');
                        loader.offsetHeight;
                    }
                    function hideLoader() {
                        try { sessionStorage.removeItem('buffcorp-route-loading'); } catch (e) { /* storage unavailable */ }
                        setClass(document.documentElement, 'buffcorp-preload-route-loading', false);
                        loader.className = 'buffcorp-route-loader';
                        loader.setAttribute('aria-hidden', 'true');
                    }
                    function closestLink(node) {
                        while (node && node !== document) {
                            if (node.tagName && node.tagName.toLowerCase() === 'a') return node;
                            node = node.parentNode;
                        }
                        return null;
                    }
                    function shouldShowForLink(link, event) {
                        if (!link || (event && (event.defaultPrevented || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey))) return false;
                        if (link.target && link.target !== '_self') return false;
                        var href = link.getAttribute('href') || '';
                        if (!href || href === '#' || href.indexOf('javascript:') === 0) return false;
                        if (href.charAt(0) === '#' || href.indexOf('mailto:') === 0 || href.indexOf('tel:') === 0) return false;
                        var routeHost = link.closest ? link.closest('.left-menu,.buffcorp-search-results') : null;
                        if (!routeHost) return false;
                        try {
                            var targetUrl = new URL(link.href, window.location.href);
                            var currentUrl = new URL(window.location.href);
                            if (targetUrl.href === currentUrl.href) return false;
                        } catch (e) { /* allow legacy relative urls */ }
                        return true;
                    }
                    document.addEventListener('click', function (event) {
                        var link = closestLink(event.target);
                        if (!shouldShowForLink(link, event)) return;
                        event.preventDefault();
                        showLoader();
                        window.setTimeout(function () {
                            window.location.href = link.href;
                        }, 1000);
                    }, true);
                    window.buffcorpShowRouteLoader = showLoader;
                    window.buffcorpHideRouteLoader = hideLoader;
                    if (window.addEventListener) {
                        window.addEventListener('pageshow', hideLoader, false);
                        window.addEventListener('beforeunload', function () {
                            if (document.activeElement && closestLink(document.activeElement)) showLoader();
                        }, false);
                    }
                }
                function setMenuOpen(open) {
                    setClass(layout, 'menu-open', open);
                    if (menuButton) menuButton.setAttribute('aria-expanded', open ? 'true' : 'false');
                }
                function initUserMenu() {
                    var menu = document.getElementById('buffcorp-user-menu');
                    var button = document.getElementById('buffcorp-user-toggle');
                    if (!menu || !button) return;
                    function isOpen() { return (' ' + menu.className + ' ').indexOf(' open ') >= 0; }
                    function close() {
                        menu.className = menu.className.replace(/ ?open/g, '');
                        button.setAttribute('aria-expanded', 'false');
                    }
                    function closeTopPanels() {
                        var notify = document.getElementById('notify-wrap');
                        var notifyButton = document.getElementById('notify-bell');
                        var payroll = document.getElementById('payroll-wrap');
                        var payrollButton = document.getElementById('payroll-button');
                        if (notify) notify.className = notify.className.replace(/ ?open/g, '');
                        if (notifyButton) notifyButton.setAttribute('aria-expanded', 'false');
                        if (payroll) payroll.className = payroll.className.replace(/ ?open/g, '');
                        if (payrollButton) payrollButton.setAttribute('aria-expanded', 'false');
                    }
                    button.onclick = function () {
                        if (isOpen()) close();
                        else {
                            closeTopPanels();
                            menu.className += ' open';
                            button.setAttribute('aria-expanded', 'true');
                        }
                    };
                    document.addEventListener('click', function (event) {
                        if (!menu.contains(event.target)) close();
                    }, false);
                    document.addEventListener('keydown', function (event) {
                        if (event.key !== 'Escape' || !isOpen()) return;
                        close();
                        button.focus();
                    }, false);
                }
                function syncViewport() {
                    if (window.innerWidth <= 820) setClass(layout, 'sidebar-collapsed', false);
                    else setMenuOpen(false);
                }

                initializeRouteLoader();
                initUserMenu();
                if (menuButton) menuButton.onclick = function () { setMenuOpen(!hasClass(layout, 'menu-open')); };
                if (overlay) overlay.onclick = function () { setMenuOpen(false); };

                var menuLinks = document.querySelectorAll('.left-menu a');
                for (var i = 0; i < menuLinks.length; i++) {
                    menuLinks[i].addEventListener('click', function () {
                        if (window.innerWidth <= 820) setMenuOpen(false);
                    });
                }
                if (window.addEventListener) window.addEventListener('resize', syncViewport, false);
                syncViewport();
                setClass(document.documentElement, 'buffcorp-preload-sidebar-collapsed', false);
                window.setTimeout(function () {
                    if (window.buffcorpHideRouteLoader) window.buffcorpHideRouteLoader();
                }, 1000);
            }

            if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initializeDemoParity);
            else initializeDemoParity();
        })();
        </script>
    </div>
</div>
</body>
</html>
