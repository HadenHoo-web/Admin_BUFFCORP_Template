<html>
<head>
    <meta http-equiv="Content-Language" content="vi">
    <meta name="GENERATOR" content="Microsoft FrontPage 5.0">
    <meta name="ProgId" content="FrontPage.Editor.Document">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BUFFCORP - Operations Hub</title>

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
        .buffcorp-user {
            display: flex;
            align-items: center;
        }

        .buffcorp-top-actions { gap: 9px; }
        .buffcorp-user { gap: 9px; margin-left: 3px; }
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

        .buffcorp-user strong,
        .buffcorp-user small { display: block; }
        .buffcorp-user strong { color: var(--buff-text); font-size: 12px; }
        .buffcorp-user small { margin-top: 2px; color: var(--buff-muted); font-size: 10px; }

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

        .buffcorp-top-actions .admin-home-button,
        .buffcorp-top-actions .payroll-button,
        .buffcorp-top-actions .notify-bell,
        .main-content.admin-dashboard-shell .buffcorp-top-actions .admin-home-button,
        .main-content.admin-dashboard-shell .buffcorp-top-actions .payroll-button,
        .main-content.admin-dashboard-shell .buffcorp-top-actions .notify-bell {
            width: 38px;
            height: 38px;
            color: var(--buff-text);
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

        .buffcorp-page div[style*="overflow:auto"][style*="height:80%"] {
            height: auto !important;
            overflow: visible !important;
        }

        .buffcorp-page .toolbar {
            display: flex;
            width: auto;
            height: auto;
            min-height: 42px;
            align-items: center;
            gap: 8px;
            margin: 0;
            padding: 8px 10px;
            overflow: visible;
            border: 1px solid var(--buff-line);
            border-radius: 9px 9px 0 0;
            background: var(--buff-surface);
            box-sizing: border-box;
        }

        .buffcorp-page .toolbar a {
            display: inline-flex;
            min-height: 34px;
            align-items: center;
            gap: 6px;
            margin: 0;
            padding: 0 10px;
            border: 1px solid var(--buff-line);
            border-radius: 7px;
            background: #fff;
            color: var(--buff-text);
            cursor: pointer;
            font-size: 11px;
            font-weight: 700;
        }

        .buffcorp-page .toolbar a:first-child {
            border-color: var(--buff-brand);
            background: var(--buff-brand);
            color: #fff;
        }
        .buffcorp-page .toolbar a:hover { padding: 0 10px; border-color: #8db7df; background: #eaf3fc; color: var(--buff-brand); }
        .buffcorp-page .toolbar a:first-child:hover { background: var(--buff-brand-dark); color: #fff; }
        .buffcorp-page .toolbar a img { width: 16px; height: 16px; object-fit: contain; }
        .buffcorp-page .toolbar a span,
        .buffcorp-page .toolbar a:link span,
        .buffcorp-page .toolbar a:visited span,
        .buffcorp-page .toolbar a:hover span { padding: 0; color: inherit; }

        .buffcorp-page .tabtitle {
            width: auto;
            min-height: 38px;
            padding: 9px 12px;
            border: 1px solid var(--buff-line);
            border-top: 0;
            background: #f8fbfe;
            color: var(--buff-text);
            font-size: 12px;
            line-height: 20px;
            box-sizing: border-box;
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

        .buffcorp-page .selector {
            width: 100%;
            min-width: 100%;
            table-layout: auto !important;
            border: 1px solid var(--buff-line);
            border-collapse: collapse !important;
            border-spacing: 0;
            border-radius: 0;
            background: #fff;
            box-shadow: none;
            overflow: hidden;
        }
        .buffcorp-page .selector td { height: auto; padding: 12px 14px; border: 0; border-top: 1px solid var(--buff-line); background: #fff !important; color: var(--buff-text) !important; font-size: 12px !important; vertical-align: middle; white-space: nowrap; }
        .buffcorp-page .selector .header td { height: auto; padding: 11px 14px; border: 0; border-top: 0; border-bottom: 1px solid var(--buff-line); background: #f1f6fb !important; color: #526a82 !important; font-size: 11px !important; font-weight: 700; letter-spacing: .2px; }
        .buffcorp-page .selector tr:last-child td { border-bottom: 0; }
        .buffcorp-page .selector tr:not(.header):hover td { background: #f8fbfe !important; }
        .buffcorp-page .selector tr:not(.header) > td:first-child { color: #234f7d !important; font-weight: 700; }
        .buffcorp-page .selector .buffcorp-actions-head,
        .buffcorp-page .selector .buffcorp-actions-cell { width: 1%; min-width: 132px; text-align: right !important; }
        .menu-tree-link { display: inline-flex; align-items: center; gap: 9px; color: #173f67; font-weight: 700; text-decoration: none; }
        .menu-tree-link:hover { color: var(--buff-brand); }
        .menu-tree-icon {
            display: inline-block;
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
        .buffcorp-row-actions { display: flex; align-items: center; justify-content: flex-end; gap: 5px; }
        .buffcorp-page .buffcorp-row-action {
            display: inline-grid;
            width: 28px;
            height: 28px;
            padding: 0;
            place-items: center;
            border: 1px solid var(--buff-line);
            border-radius: 5px;
            box-sizing: border-box;
        }
        .buffcorp-page .buffcorp-row-action svg { width: 15px; height: 15px; }
        .buffcorp-page .buffcorp-action-view { border-color: #c9ddf2; background: #edf5fd; color: #2e6cbf; }
        .buffcorp-page .buffcorp-action-edit { border-color: #f2d7a8; background: #fff5e6; color: #b35f00; }
        .buffcorp-page .buffcorp-action-delete { border-color: #f1c5c5; background: #fdecec; color: #c43f3f; }
        .buffcorp-page .buffcorp-action-move-up,
        .buffcorp-page .buffcorp-action-move-down { border-color: #c9ddf2; background: #edf5fd; color: #2e6cbf; }
        .buffcorp-page .buffcorp-action-permission { border-color: #d9cef7; background: #f4f0ff; color: #6941c6; }
        .buffcorp-page .buffcorp-action-password { border-color: #bde3d1; background: #ecfdf3; color: #027a48; }
        .buffcorp-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 7px;
            border-radius: 12px;
            background: #ecfdf3;
            color: #027a48;
            font-size: 10px;
            font-weight: 700;
        }
        .buffcorp-status:before { width: 5px; height: 5px; border-radius: 50%; background: currentColor; content: ""; }
        .buffcorp-status.warning { background: #fffaeb; color: #b54708; }
        .buffcorp-status.danger { background: #fef3f2; color: #b42318; }
        .buffcorp-status.neutral { background: #f2f4f7; color: #475467; }

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
            border: 1px solid var(--buff-line);
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(46,108,191,.07);
        }
        .buffcorp-server-source { display: contents; }

        .buffcorp-module-toolbar {
            display: flex;
            min-height: 64px;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--buff-line);
            background: linear-gradient(180deg,#fff,#fbfdff);
            box-sizing: border-box;
        }

        .buffcorp-client-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .buffcorp-client-controls input {
            width: 260px;
            min-width: 220px;
        }
        .buffcorp-client-controls select { min-width: 105px; }

        .buffcorp-module-filter {
            display: flex;
            min-width: 0;
            flex: 1 1 auto;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            color: var(--buff-text);
            font-size: 11px;
        }

        .buffcorp-module-filter select { min-width: 145px; }
        .buffcorp-module-filter input[type="submit"] {
            min-height: 36px;
            padding: 0 13px;
            border: 1px solid var(--buff-line);
            background: #fff;
            color: var(--buff-text);
            cursor: pointer;
            font-weight: 700;
        }

        .buffcorp-module-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .buffcorp-module-actions .buffcorp-primary-action {
            display: inline-flex;
            min-height: 38px;
            align-items: center;
            gap: 7px;
            padding: 0 13px;
            border: 1px solid var(--buff-brand);
            border-radius: 7px;
            background: var(--buff-brand);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
        }

        .buffcorp-module-actions .buffcorp-secondary-action {
            display: inline-flex;
            min-height: 38px;
            align-items: center;
            gap: 7px;
            padding: 0 13px;
            border: 1px solid var(--buff-line);
            border-radius: 7px;
            background: #fff;
            color: var(--buff-text);
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
        }
        .buffcorp-module-actions .buffcorp-primary-action img,
        .buffcorp-module-actions .buffcorp-secondary-action img { width: 16px; height: 16px; }
        .buffcorp-module-actions .buffcorp-primary-action svg,
        .buffcorp-module-actions .buffcorp-secondary-action svg { width: 16px; height: 16px; }
        .buffcorp-refresh {
            display: grid;
            width: 38px;
            height: 38px;
            padding: 0;
            place-items: center;
            border: 1px solid var(--buff-line);
            border-radius: 7px;
            background: #fff;
            color: var(--buff-text);
            cursor: pointer;
        }
        .buffcorp-refresh:hover { border-color: #9fc0e3; background: #f5f9fe; color: var(--buff-brand); }
        .buffcorp-refresh svg { width: 17px; height: 17px; }

        .buffcorp-list-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 15px 18px;
            border-bottom: 1px solid var(--buff-line);
            background: #fff;
        }
        .buffcorp-list-head strong { color: #173f67; font-size: 14px; }
        .buffcorp-list-head-copy small {
            display: block;
            margin-top: 4px;
            color: var(--buff-muted);
            font-size: 10px;
        }
        .buffcorp-record-count {
            display: inline-flex;
            min-height: 28px;
            align-items: center;
            padding: 5px 10px;
            border: 1px solid #cfe0f2;
            border-radius: 999px;
            background: #edf5fd;
            color: var(--buff-brand-dark);
            font-size: 10px;
            font-weight: 800;
        }

        .buffcorp-table-wrap { width: 100%; overflow-x: auto; }
        .buffcorp-module-card .selector { border: 0; border-radius: 0; box-shadow: none; }
        .buffcorp-pagination {
            display: flex;
            min-height: 52px;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 16px;
            border-top: 1px solid var(--buff-line);
            color: var(--buff-muted);
            font-size: 10px;
        }
        .buffcorp-pagination div { display: flex; gap: 5px; }
        .buffcorp-pagination button {
            width: 30px;
            height: 30px;
            padding: 0;
            border: 1px solid var(--buff-line);
            border-radius: 6px;
            background: #fff;
            color: var(--buff-text);
            cursor: pointer;
        }
        .buffcorp-pagination button.active { border-color: var(--buff-brand); background: var(--buff-brand); color: #fff; }
        .buffcorp-pagination button:disabled { cursor: default; opacity: .4; }

        .buffcorp-form-card {
            padding: 14px;
            border: 0;
            background: #fff;
        }
        .buffcorp-page .buffcorp-form-table {
            width: 100%;
            border: 0;
            box-shadow: none;
        }
        .buffcorp-page .buffcorp-form-table > tbody {
            display: grid;
            grid-template-columns: repeat(4,minmax(0,1fr));
            gap: 10px;
        }
        .buffcorp-page .buffcorp-form-table.buffcorp-form-dense > tbody { grid-template-columns: repeat(6,minmax(0,1fr)); }
        .buffcorp-page .buffcorp-form-table.buffcorp-form-ultra > tbody { grid-template-columns: repeat(8,minmax(0,1fr)); }
        .buffcorp-page .buffcorp-form-table > tbody > tr {
            display: flex;
            min-width: 0;
            flex-direction: column;
            gap: 4px;
            padding: 9px;
            border: 1px solid #e3edf6;
            border-radius: 8px;
            background: #fbfdff;
        }
        .buffcorp-page .buffcorp-form-table > tbody > tr > td {
            display: block;
            width: auto !important;
            height: auto !important;
            padding: 0;
            color: #111;
        }
        .buffcorp-page .buffcorp-form-table > tbody > tr > td:first-child {
            color: #344054;
            font-size: 10px;
            font-weight: 700;
        }
        .buffcorp-page .buffcorp-form-table textarea { height: 64px; min-height: 64px; resize: none; }

        .buffcorp-page .admin-dashboard { padding: 20px; background: var(--buff-bg); font-family: "Segoe UI", Arial, sans-serif; }
        .buffcorp-page .admin-dashboard-header { padding-right: 0; }
        .buffcorp-page .admin-card,
        .buffcorp-page .admin-date-range,
        .buffcorp-page .admin-export-btn,
        .buffcorp-page .admin-select,
        .buffcorp-page .admin-outline-btn { border-color: var(--buff-line); background: #fff; box-shadow: var(--buff-shadow); }

        @media (max-width: 980px) {
            .buffcorp-global-search { width: 230px; }
            .buffcorp-user div { display: none; }
            .buffcorp-page .buffcorp-form-table > tbody,
            .buffcorp-page .buffcorp-form-table.buffcorp-form-dense > tbody,
            .buffcorp-page .buffcorp-form-table.buffcorp-form-ultra > tbody { grid-template-columns: repeat(3,minmax(0,1fr)); }
        }

        @media (max-width: 720px) {
            .buffcorp-topbar { min-height: 60px; flex-basis: 60px; padding: 0 12px; }
            .buffcorp-global-search { display: none; }
            .buffcorp-page { padding: 14px 12px 26px; }
            .buffcorp-user { display: none; }
            .buffcorp-module-toolbar { align-items: stretch; flex-direction: column; }
            .buffcorp-module-actions { margin-left: 0; }
            .buffcorp-client-controls { align-items: stretch; flex-direction: column; }
            .buffcorp-client-controls input { width: 100%; min-width: 0; }
            .buffcorp-page .buffcorp-form-table > tbody,
            .buffcorp-page .buffcorp-form-table.buffcorp-form-dense > tbody,
            .buffcorp-page .buffcorp-form-table.buffcorp-form-ultra > tbody { grid-template-columns: repeat(2,minmax(0,1fr)); }
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
    <button type="button" class="buffcorp-mobile-overlay" id="buffcorp-mobile-overlay" aria-label="Đóng menu"></button>

    <div class="{MAIN_CONTENT_CLASS}" id="main-content">
        <header class="buffcorp-topbar">
            <div class="buffcorp-page-title-wrap">
                <button type="button" class="buffcorp-mobile-menu" id="buffcorp-mobile-menu" aria-label="Mở menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                        <path d="M4 7h16M4 12h16M4 17h16"></path>
                    </svg>
                </button>
                <div class="buffcorp-page-title" id="buffcorp-page-title">Tổng quan</div>
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
        <div class="buffcorp-theme-wrap">
            <button type="button" class="buffcorp-theme-button" id="buffcorp-theme-button" title="Chế độ sáng/tối" aria-label="Chế độ sáng/tối">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                    <circle cx="12" cy="12" r="4"></circle>
                    <path d="M12 2v2M12 20v2M4.93 4.93l1.42 1.42M17.65 17.65l1.42 1.42M2 12h2M20 12h2M4.93 19.07l1.42-1.42M17.65 6.35l1.42-1.42"></path>
                </svg>
            </button>
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
                    <div><small>TRUNG TÂM CẬP NHẬT</small><span>Thông báo</span></div>
                    <button type="button" class="notify-close" id="notify-close" aria-label="Đóng">×</button>
                </div>
                <div class="notify-list" id="notify-list">
                    <div class="notify-empty">Đang tải...</div>
                </div>
                <button type="button" class="notify-read-all" id="notify-read-all">Đánh dấu tất cả đã đọc</button>
            </div>
        </div>
                <div class="buffcorp-user">
                    <span class="buffcorp-avatar">{USER_INITIAL}</span>
                    <div><strong>{USER_DISPLAY_NAME}</strong><small>{USER_ROLE}</small></div>
                </div>
            </div>
        </header>
        <main class="buffcorp-page" id="buffcorp-page">
        {MAIN_CONTENT}
        </main>
        <style id="buffcorp-demo-parity">
        .buffcorp-page-title-wrap { display: flex; min-width: 0; align-items: center; gap: 10px; }
        .buffcorp-mobile-menu,
        .buffcorp-mobile-overlay { display: none; }
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
        .buffcorp-mobile-menu svg { width: 18px; height: 18px; }
        .buffcorp-theme-wrap {
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
        .buffcorp-theme-button {
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
        .buffcorp-theme-button svg { width: 18px; height: 18px; }

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
        .notify-item { padding: 15px 3px; border-bottom: 1px solid var(--buff-line); color: var(--buff-text); }
        .notify-item:hover { background: var(--buff-bg); }
        .notify-item.unread { background: transparent; }
        .notify-title { color: var(--buff-text); font-size: 12px; }
        .notify-message { color: var(--buff-muted); font-size: 11px; line-height: 16px; }
        .notify-read-all,
        .notify-read-all.show {
            display: block;
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

        .payroll-panel {
            top: 50% !important;
            right: auto !important;
            left: 50% !important;
            width: 520px;
            max-width: calc(100vw - 24px);
            max-height: calc(100vh - 32px);
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
            min-height: 68px;
            padding: 22px 52px 18px 22px;
            border-bottom: 1px solid var(--buff-line);
            background: var(--buff-surface);
            color: var(--buff-text);
            font-size: 17px;
            text-align: left;
        }
        .payroll-close { top: 16px; right: 16px; color: var(--buff-muted); }
        .payroll-body { padding: 18px 22px 22px; color: var(--buff-text); }
        .payroll-filter { color: var(--buff-muted); }
        .payroll-filter select { border-color: var(--buff-line); border-radius: 6px; background: var(--buff-surface); color: var(--buff-text); }

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
        body.buffcorp-dark,
        body.buffcorp-dark .main-content,
        body.buffcorp-dark .buffcorp-page { background: var(--buff-bg); color: var(--buff-text); }
        body.buffcorp-dark .buffcorp-topbar,
        body.buffcorp-dark .buffcorp-module-card,
        body.buffcorp-dark .buffcorp-form-card,
        body.buffcorp-dark .buffcorp-list-head,
        body.buffcorp-dark .buffcorp-module-toolbar,
        body.buffcorp-dark .buffcorp-theme-wrap,
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
        body.buffcorp-dark .buffcorp-page .selector td { border-color: var(--buff-line); background: var(--buff-surface) !important; color: var(--buff-text) !important; }
        body.buffcorp-dark .buffcorp-page .selector .header td,
        body.buffcorp-dark .sales-table th,
        body.buffcorp-dark .kpi-table th,
        body.buffcorp-dark .admin-table th { background: #1c222b !important; color: #b8c9da !important; }
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
                width: 236px !important;
                flex-basis: 236px !important;
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
            .buffcorp-mobile-menu { display: grid; }
            .layout.sidebar-collapsed .left-menu { width: 236px !important; flex-basis: 236px !important; }
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
        }
        @media (max-width: 620px) {
            .buffcorp-topbar { gap: 8px; }
            .buffcorp-top-actions { gap: 5px; }
            .buffcorp-theme-wrap,
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

            var pageTitle = document.getElementById('buffcorp-page-title');
            var currentOption = '{CURRENT_OPTION}';
            var currentMode = '{CURRENT_MODE}';
            var currentUrl = null;
            try {
                currentUrl = new URL(window.location.href);
                currentOption = currentUrl.searchParams.get('option') || currentOption;
                currentMode = currentUrl.searchParams.get('mode') || currentMode;
            } catch (e) { /* keep back-end route */ }
            var links = document.querySelectorAll('#buffcorp-menu .children a[href]');
            var currentLink = document.querySelector('#buffcorp-menu .children a.active[href]');
            var currentLinkScore = currentLink ? 100 : -1;
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
            var heading = document.querySelector('#buffcorp-page h1, #buffcorp-page .tabtitle');
            var currentLabel = currentLink && currentLink.querySelector('span:not(.app-child-icon)');
            if (pageTitle) pageTitle.textContent = currentLink
                ? ((currentLabel ? currentLabel.textContent : currentLink.textContent) || '').replace(/^\s+|\s+$/g, '')
                : (heading ? (heading.textContent || '').replace(/^\s+|\s+$/g, '') : 'Tổng quan');

            function actionIcon(type) {
                var path = '<path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"></path><circle cx="12" cy="12" r="2.5"></circle>';
                if (type === 'edit') path = '<path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4z"></path>';
                if (type === 'delete') path = '<path d="M3 6h18M8 6V4h8v2M19 6l-1 15H6L5 6M10 11v6M14 11v6"></path>';
                if (type === 'add') path = '<path d="M12 5v14M5 12h14"></path>';
                if (type === 'save') path = '<path d="M5 3h12l2 2v16H5z"></path><path d="M8 3v6h8V3M8 21v-8h8v8"></path>';
                if (type === 'back') path = '<path d="M19 12H5M11 18l-6-6 6-6"></path>';
                if (type === 'move-up') path = '<path d="M12 19V5M6 11l6-6 6 6"></path>';
                if (type === 'move-down') path = '<path d="M12 5v14M6 13l6 6 6-6"></path>';
                if (type === 'permission') path = '<path d="M12 3l7 3v5c0 4.5-2.8 8-7 10-4.2-2-7-5.5-7-10V6z"></path><path d="M9 12l2 2 4-4"></path>';
                if (type === 'password') path = '<circle cx="8" cy="15" r="4"></circle><path d="M11 12l8-8M15 8l2 2M17 6l2 2"></path>';
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + path + '</svg>';
            }

            function topLevelChild(node, root) {
                while (node && node.parentNode !== root) node = node.parentNode;
                return node;
            }

            function enhanceLegacyModule(label) {
                var page = document.getElementById('buffcorp-page');
                if (!page || page.querySelector('.admin-dashboard,.sales-page,.kpi-page,.kpi-report,.org-chart')) return;
                var card = page.querySelector('.buffcorp-server-module');
                var contentRoot = card ? (card.querySelector('.buffcorp-server-source') || card) : page;
                var selector = contentRoot.querySelector('table.selector');
                var toolbar = contentRoot.querySelector('.toolbar');
                var tabtitle = contentRoot.querySelector('.tabtitle');
                var filterForm = contentRoot.querySelector('form[name="filterForm"]') || (tabtitle && tabtitle.querySelector('form'));
                var mainForm = contentRoot.querySelector('form[name="mainForm"]') || (!selector ? contentRoot.querySelector('form') : null);
                if (!selector && !mainForm && !toolbar) return;

                if (!card) {
                    var first = toolbar || tabtitle || topLevelChild(selector || mainForm, page);
                    card = document.createElement('section');
                    card.className = 'buffcorp-module-card';
                    first.parentNode.insertBefore(card, first);
                }
                var cardAnchor = contentRoot !== page && contentRoot.parentNode === card ? contentRoot : null;
                var appendToCard = function (node) {
                    if (cardAnchor) card.insertBefore(node, cardAnchor);
                    else card.appendChild(node);
                };

                var moduleToolbar = document.createElement('div');
                moduleToolbar.className = 'buffcorp-module-toolbar';
                var listSearch = null;
                var sizeSelect = null;
                if (selector) {
                    var clientControls = document.createElement('div');
                    clientControls.className = 'buffcorp-client-controls';
                    listSearch = document.createElement('input');
                    listSearch.type = 'search';
                    listSearch.placeholder = 'Tìm trong ' + String(label || 'dữ liệu').toLowerCase() + '...';
                    listSearch.setAttribute('aria-label', 'Tìm trong bảng');
                    sizeSelect = document.createElement('select');
                    sizeSelect.setAttribute('aria-label', 'Số dòng');
                    var sizes = [5, 10, 20];
                    for (var s = 0; s < sizes.length; s++) {
                        var sizeOption = document.createElement('option');
                        sizeOption.value = sizes[s];
                        sizeOption.textContent = sizes[s] + ' dòng';
                        sizeSelect.appendChild(sizeOption);
                    }
                    clientControls.appendChild(listSearch);
                    clientControls.appendChild(sizeSelect);
                    moduleToolbar.appendChild(clientControls);
                }
                if (filterForm) {
                    filterForm.className += (filterForm.className ? ' ' : '') + 'buffcorp-module-filter';
                    moduleToolbar.appendChild(filterForm);
                }
                var actions = document.createElement('div');
                actions.className = 'buffcorp-module-actions';
                if (toolbar) {
                    var actionLinks = toolbar.querySelectorAll('a');
                    for (var a = 0; a < actionLinks.length; a++) {
                        if (window.getComputedStyle(actionLinks[a]).display === 'none' || actionLinks[a].offsetParent === null) continue;
                        var actionLabel = String(actionLinks[a].textContent || '').replace(/^\s+|\s+$/g, '');
                        var normalizedLabel = actionLabel.toLowerCase();
                        var translatedActions = {
                            'create new': 'Thêm mới',
                            'add new': 'Thêm mới',
                            'save': 'Lưu',
                            'send': 'Gửi',
                            'back': 'Trở về',
                            'list': 'Trở về',
                            'return': 'Trở về'
                        };
                        var displayActionLabel = translatedActions[normalizedLabel] || actionLabel;
                        var actionType = normalizedLabel.indexOf('lưu') >= 0 || normalizedLabel.indexOf('save') >= 0
                            ? 'save'
                            : (normalizedLabel.indexOf('về') >= 0 || normalizedLabel.indexOf('back') >= 0 || normalizedLabel.indexOf('return') >= 0 || normalizedLabel === 'list' ? 'back' : 'add');
                        actionLinks[a].innerHTML = actionIcon(actionType);
                        var actionText = document.createElement('span');
                        actionText.textContent = displayActionLabel;
                        actionLinks[a].appendChild(actionText);
                        actionLinks[a].className += (actionLinks[a].className ? ' ' : '') + (a === 0 ? 'buffcorp-primary-action' : 'buffcorp-secondary-action');
                        actions.appendChild(actionLinks[a]);
                    }
                    toolbar.parentNode.removeChild(toolbar);
                }
                if (selector) {
                    var refresh = document.createElement('button');
                    refresh.type = 'button';
                    refresh.className = 'buffcorp-refresh';
                    refresh.title = 'Làm mới';
                    refresh.setAttribute('aria-label', 'Làm mới');
                    refresh.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6v5h-5M4 18v-5h5"></path><path d="M18 9a7 7 0 0 0-12-3L4 8M6 15a7 7 0 0 0 12 3l2-2"></path></svg>';
                    refresh.onclick = function () { window.location.reload(); };
                    actions.appendChild(refresh);
                }
                if (actions.children.length) moduleToolbar.appendChild(actions);
                if (moduleToolbar.children.length) appendToCard(moduleToolbar);
                if (tabtitle && tabtitle.parentNode) tabtitle.parentNode.removeChild(tabtitle);

                if (selector) {
                    var rows = [];
                    var allRows = selector.getElementsByTagName('tr');
                    for (var r = 0; r < allRows.length; r++) {
                        if ((' ' + allRows[r].className + ' ').indexOf(' header ') < 0) rows.push(allRows[r]);
                    }
                    var listHead = document.createElement('div');
                    listHead.className = 'buffcorp-list-head';
                    var listCopy = document.createElement('div');
                    listCopy.className = 'buffcorp-list-head-copy';
                    var listTitle = document.createElement('strong');
                    listTitle.textContent = 'Danh sách ' + String(label || 'dữ liệu').toLowerCase();
                    var listMeta = document.createElement('small');
                    listMeta.textContent = rows.length + ' bản ghi phù hợp';
                    var count = document.createElement('span');
                    count.className = 'buffcorp-record-count';
                    count.textContent = rows.length + ' mục';
                    listCopy.appendChild(listTitle);
                    listCopy.appendChild(listMeta);
                    listHead.appendChild(listCopy);
                    listHead.appendChild(count);
                    appendToCard(listHead);

                    var tableWrap = topLevelChild(selector, contentRoot);
                    tableWrap.className += (tableWrap.className ? ' ' : '') + 'buffcorp-table-wrap';
                    tableWrap.style.height = 'auto';
                    tableWrap.style.overflowX = 'auto';
                    tableWrap.style.overflowY = 'hidden';
                    appendToCard(tableWrap);

                    var imageLinks = selector.querySelectorAll('a img');
                    for (var x = 0; x < imageLinks.length; x++) {
                        var link = imageLinks[x].parentNode;
                        if (imageLinks[x].style.display === 'none' || window.getComputedStyle(imageLinks[x]).display === 'none') {
                            var hiddenCell = link.parentNode;
                            if (hiddenCell && hiddenCell.tagName && hiddenCell.tagName.toLowerCase() === 'td') hiddenCell.parentNode.removeChild(hiddenCell);
                            else link.style.display = 'none';
                            continue;
                        }
                        var src = String(imageLinks[x].getAttribute('src') || '').toLowerCase();
                        var alt = String(imageLinks[x].getAttribute('alt') || '').toLowerCase();
                        var type = src.indexOf('delete') >= 0 ? 'delete'
                            : (src.indexOf('edit') >= 0 ? 'edit'
                                : (src.indexOf('down') >= 0 ? 'move-down'
                                    : (src.indexOf('up.') >= 0 ? 'move-up'
                                        : (src.indexOf('db_user') >= 0 || src.indexOf('perms') >= 0 || alt.indexOf('permission') >= 0 ? 'permission'
                                            : (src.indexOf('securityroles') >= 0 || alt.indexOf('password') >= 0 ? 'password' : 'view')))));
                        link.className += (link.className ? ' ' : '') + 'buffcorp-row-action buffcorp-action-' + type;
                        var actionLabel = type === 'delete' ? 'Xóa'
                            : (type === 'edit' ? 'Sửa'
                                : (type === 'move-up' ? 'Đưa lên'
                                    : (type === 'move-down' ? 'Đưa xuống'
                                        : (type === 'permission' ? 'Phân quyền'
                                            : (type === 'password' ? 'Đổi mật khẩu' : 'Xem')))));
                        link.title = actionLabel;
                        link.setAttribute('aria-label', actionLabel);
                        link.innerHTML = actionIcon(type);
                    }

                    var maxActionCount = 0;
                    for (var modernRowIndex = 0; modernRowIndex < rows.length; modernRowIndex++) {
                        var actionCells = [];
                        var cells = rows[modernRowIndex].getElementsByTagName('td');
                        for (var cellIndex = 0; cellIndex < cells.length; cellIndex++) {
                            if (cells[cellIndex].querySelector('.buffcorp-row-action')) actionCells.push(cells[cellIndex]);
                        }
                        if (actionCells.length) {
                            maxActionCount = Math.max(maxActionCount, actionCells.length);
                            var actionHost = actionCells[0];
                            var rowActions = document.createElement('div');
                            rowActions.className = 'buffcorp-row-actions';
                            for (var actionCellIndex = 0; actionCellIndex < actionCells.length; actionCellIndex++) {
                                var cellActions = actionCells[actionCellIndex].querySelectorAll('.buffcorp-row-action');
                                for (var actionIndex = 0; actionIndex < cellActions.length; actionIndex++) rowActions.appendChild(cellActions[actionIndex]);
                            }
                            actionHost.innerHTML = '';
                            actionHost.className += (actionHost.className ? ' ' : '') + 'buffcorp-actions-cell';
                            actionHost.removeAttribute('width');
                            actionHost.removeAttribute('align');
                            actionHost.appendChild(rowActions);
                            for (var removeIndex = 1; removeIndex < actionCells.length; removeIndex++) actionCells[removeIndex].parentNode.removeChild(actionCells[removeIndex]);
                        }

                        var statusCells = rows[modernRowIndex].getElementsByTagName('td');
                        for (var statusIndex = 0; statusIndex < statusCells.length; statusIndex++) {
                            if (statusCells[statusIndex].querySelector('a,button,input,select,img')) continue;
                            var statusText = String(statusCells[statusIndex].textContent || '').replace(/^\s+|\s+$/g, '');
                            var statusKey = statusText.toLowerCase().replace(/đ/g, 'd');
                            try { statusKey = statusKey.normalize('NFD').replace(/[\u0300-\u036f]/g, ''); } catch (e) { /* keep original status */ }
                            var statusType = '';
                            if (/^(inactive|disabled|khong hoat dong|chua dat|qua han|huy|no)$/.test(statusKey)) statusType = 'danger';
                            else if (/^(pending|cho|cho duyet|dang xu ly|dang thuc hien|can theo doi)$/.test(statusKey)) statusType = 'warning';
                            else if (/^(draft|nhap|moi)$/.test(statusKey)) statusType = 'neutral';
                            else if (/^(active|actived|hoat dong|da hoan thanh|hoan thanh|da duyet|dat kpi|yes|co)$/.test(statusKey)) statusType = 'success';
                            if (!statusType) continue;
                            var status = document.createElement('span');
                            status.className = 'buffcorp-status' + (statusType === 'success' ? '' : ' ' + statusType);
                            status.textContent = statusText;
                            statusCells[statusIndex].innerHTML = '';
                            statusCells[statusIndex].appendChild(status);
                        }
                    }
                    var headerRow = selector.querySelector('tr.header');
                    var headerCells = headerRow ? headerRow.querySelectorAll('td,th') : [];
                    var possibleActionHead = headerCells.length ? headerCells[headerCells.length - 1] : null;
                    var declaredActionColumns = possibleActionHead
                        && !String(possibleActionHead.textContent || '').replace(/\s+/g, '')
                        && parseInt(possibleActionHead.getAttribute('colspan') || '1', 10) > 1;
                    if ((maxActionCount || declaredActionColumns) && possibleActionHead) {
                            var actionHead = possibleActionHead;
                            actionHead.textContent = 'Thao tác';
                            actionHead.className += (actionHead.className ? ' ' : '') + 'buffcorp-actions-head';
                            actionHead.removeAttribute('colspan');
                            actionHead.removeAttribute('width');
                    }

                    var pageSize = 5;
                    var footer = document.createElement('footer');
                    footer.className = 'buffcorp-pagination';
                    var status = document.createElement('span');
                    var buttons = document.createElement('div');
                    footer.appendChild(status);
                    footer.appendChild(buttons);
                    appendToCard(footer);
                    var showPage;
                    var addPageButton = function (pageNumber, text, disabled, activePage) {
                        var pageButton = document.createElement('button');
                        pageButton.type = 'button';
                        pageButton.textContent = text;
                        pageButton.disabled = disabled;
                        pageButton.setAttribute('data-page', pageNumber);
                        pageButton.className = pageNumber === activePage ? 'active' : '';
                        pageButton.onclick = function () { showPage(pageNumber); };
                        buttons.appendChild(pageButton);
                    };
                    showPage = function (activePage) {
                        var query = String(listSearch.value || '').toLowerCase().replace(/^\s+|\s+$/g, '');
                        var filteredRows = [];
                        for (var y = 0; y < rows.length; y++) {
                            rows[y].style.display = 'none';
                            if (!query || String(rows[y].textContent || '').toLowerCase().indexOf(query) >= 0) filteredRows.push(rows[y]);
                        }
                        var pages = Math.max(1, Math.ceil(filteredRows.length / pageSize));
                        activePage = Math.max(1, Math.min(activePage, pages));
                        var firstRow = (activePage - 1) * pageSize;
                        for (var visible = firstRow; visible < Math.min(firstRow + pageSize, filteredRows.length); visible++) filteredRows[visible].style.display = '';
                        listMeta.textContent = filteredRows.length + ' bản ghi phù hợp';
                        count.textContent = filteredRows.length + ' mục';
                        status.textContent = 'Trang ' + activePage + ' / ' + pages;
                        buttons.innerHTML = '';
                        addPageButton(Math.max(1, activePage - 1), '‹', activePage === 1, activePage);
                        var startPage = Math.max(1, Math.min(activePage - 2, pages - 4));
                        var endPage = Math.min(pages, startPage + 4);
                        for (var p = startPage; p <= endPage; p++) addPageButton(p, p, false, activePage);
                        addPageButton(Math.min(pages, activePage + 1), '›', activePage === pages, activePage);
                    };
                    listSearch.oninput = function () { showPage(1); };
                    sizeSelect.onchange = function () {
                        pageSize = parseInt(sizeSelect.value, 10) || 5;
                        showPage(1);
                    };
                    showPage(1);
                } else if (mainForm) {
                    var formWrap = topLevelChild(mainForm, contentRoot);
                    formWrap.className += (formWrap.className ? ' ' : '') + 'buffcorp-form-card';
                    formWrap.style.height = 'auto';
                    formWrap.style.overflow = 'visible';
                    appendToCard(formWrap);
                    var formTable = mainForm.querySelector('table');
                    if (formTable) {
                        var formRowsList = formTable.getElementsByTagName('tr');
                        var formRows = 0;
                        for (var z = 0; z < formRowsList.length; z++) {
                            var hasContent = String(formRowsList[z].textContent || '').replace(/\s+/g, '') !== '' || formRowsList[z].querySelector('input,select,textarea,button');
                            if (!hasContent || formRowsList[z].style.visibility === 'hidden') formRowsList[z].style.display = 'none';
                            else formRows++;
                        }
                        formTable.className += (formTable.className ? ' ' : '') + 'buffcorp-form-table' + (formRows > 40 ? ' buffcorp-form-ultra' : (formRows > 24 ? ' buffcorp-form-dense' : ''));
                    }
                }
                card.className += (card.className ? ' ' : '') + 'buffcorp-module-ready';
            }

            enhanceLegacyModule(pageTitle ? pageTitle.textContent : '');

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
                var themeButton = document.getElementById('buffcorp-theme-button');
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
                function applyTheme(dark) {
                    setClass(body, 'buffcorp-dark', dark);
                    if (!themeButton) return;
                    themeButton.setAttribute('aria-pressed', dark ? 'true' : 'false');
                    themeButton.innerHTML = dark
                        ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8Z"></path></svg>'
                        : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M4.93 4.93l1.42 1.42M17.65 17.65l1.42 1.42M2 12h2M20 12h2M4.93 19.07l1.42-1.42M17.65 6.35l1.42-1.42"></path></svg>';
                }
                function setMenuOpen(open) {
                    setClass(layout, 'menu-open', open);
                    if (menuButton) menuButton.setAttribute('aria-expanded', open ? 'true' : 'false');
                }
                function syncViewport() {
                    if (window.innerWidth <= 820) setClass(layout, 'sidebar-collapsed', false);
                    else setMenuOpen(false);
                }

                var dark = false;
                try { dark = localStorage.getItem('buffcorp-theme') === 'dark'; } catch (e) { /* storage unavailable */ }
                applyTheme(dark);
                if (themeButton) themeButton.onclick = function () {
                    dark = !hasClass(body, 'buffcorp-dark');
                    applyTheme(dark);
                    try { localStorage.setItem('buffcorp-theme', dark ? 'dark' : 'light'); } catch (e) { /* storage unavailable */ }
                };
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
            }

            if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initializeDemoParity);
            else initializeDemoParity();
        })();
        </script>
    </div>
</div>
</body>
</html>
