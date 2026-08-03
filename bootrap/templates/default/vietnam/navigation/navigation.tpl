<style>
.left-menu {
    width: 296px !important;
    flex: 0 0 296px;
    overflow: hidden !important;
    border-right: 1px solid #d9e6f3 !important;
    background: #fff !important;
    color: #111827;
    font-family: Manrope, "Segoe UI", Arial, sans-serif;
    transition: width .3s cubic-bezier(.2,.8,.2,1), flex-basis .3s cubic-bezier(.2,.8,.2,1);
}
.layout > .main-content { min-width: 0; }
.buffcorp-sidebar { display: flex; height: 100%; flex-direction: column; background: #fff; }
.buffcorp-brand { display: flex; min-height: 72px; align-items: center; gap: 10px; padding: 14px 16px; border-bottom: 1px solid #edf2f7; }
.buffcorp-logo-button { display: grid; width: 40px; height: 40px; flex: 0 0 40px; padding: 0; place-items: center; border: 0; border-radius: 50%; background: transparent; cursor: default; }
.buffcorp-logo-button img { display: block; width: 40px; height: 40px; border-radius: 50%; object-fit: contain; }
.buffcorp-brand-copy { min-width: 0; flex: 1; overflow: hidden; white-space: nowrap; transition: max-width .24s ease, opacity .16s ease; }
.buffcorp-brand-copy strong,.buffcorp-brand-copy small { display: block; }
.buffcorp-brand-copy strong { color: #102a43; font-size: 17px; line-height: 21px; }
.buffcorp-brand-copy small { color: #687b91; font-size: 12px; line-height: 16px; }
.buffcorp-collapse { display: grid; width: 31px; height: 31px; flex: 0 0 31px; padding: 0; place-items: center; border: 1px solid #d9e6f3; border-radius: 8px; background: #fff; color: #2e6cbf; cursor: pointer; }
.buffcorp-collapse svg { width: 16px; height: 16px; }
.buffcorp-nav-scroll { min-height: 0; flex: 1; overflow-x: hidden; overflow-y: auto; padding: 8px 12px 14px; scrollbar-width: none; -ms-overflow-style: none; }
.buffcorp-nav-scroll::-webkit-scrollbar { width: 0; height: 0; }
.buffcorp-section-label { margin: 9px 10px 8px; color: #94a3b8; font-size: 10px; font-weight: 700; letter-spacing: .7px; }
.buffcorp-section-label.support { margin-top: 18px; }
.buffcorp-menu { width: 100% !important; height: auto !important; border: 0 !important; border-collapse: collapse; background: transparent !important; table-layout: auto !important; }
.buffcorp-menu tbody,.buffcorp-menu tr,.buffcorp-menu td { display: block; width: 100% !important; height: auto !important; padding: 0 !important; border: 0 !important; background: transparent !important; }
.buffcorp-menu td { margin-bottom: 3px; }
.buffcorp-menu .header {
    display: flex !important;
    min-height: 40px !important;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 9px 14px 9px 12px !important;
    border: 0 !important;
    border-radius: 9px;
    background: transparent !important;
    color: #111827 !important;
    cursor: pointer;
    font-family: Manrope, "Segoe UI", Arial, sans-serif !important;
    font-size: 12px !important;
    font-weight: 700;
    line-height: 18px;
    white-space: nowrap;
}
.buffcorp-menu .header:hover,.buffcorp-menu .mainrow > .header { background: #eaf3fc !important; color: #2e6cbf !important; }
.app-parent-main { display: flex; min-width: 0; align-items: center; gap: 10px; }
.app-parent-label { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 14px; }
.app-parent-icon { position: relative; display: grid; width: 19px; height: 19px; flex: 0 0 19px; place-items: center; }
.app-parent-icon svg { width: 18px; height: 18px; }
.app-parent-meta { display: flex; align-items: center; gap: 5px; margin-right: 4px; }
.app-parent-chevron { display: grid; width: 16px; height: 16px; place-items: center; transition: transform .18s ease; }
.app-parent-chevron svg { width: 15px; height: 15px; }
.buffcorp-menu .mainrow .app-parent-chevron { transform: rotate(180deg); }
.app-nav-badge { display: grid; min-width: 19px; height: 19px; padding: 0 5px; place-items: center; border-radius: 10px; background: #d64545; color: #fff; font-size: 9px; font-style: normal; font-weight: 800; line-height: 19px; }
.buffcorp-menu td > .children {
    display: block !important;
    max-height: 0;
    overflow: hidden;
    overflow-anchor: none;
    padding: 0 0 0 13px;
    opacity: 0;
    transform: translateY(-4px);
    visibility: visible !important;
    transition: max-height .24s cubic-bezier(.2,.8,.2,1), padding .24s cubic-bezier(.2,.8,.2,1), opacity .16s ease, transform .24s cubic-bezier(.2,.8,.2,1);
    will-change: max-height, opacity, transform;
}
.buffcorp-menu .mainrow > .children { max-height: var(--sidebar-children-height, 1200px); padding: 5px 0 7px 13px; opacity: 1; transform: translateY(0); }
.buffcorp-menu .children > a,.page-tree a {
    display: flex;
    min-height: 38px;
    align-items: center;
    gap: 9px;
    padding: 8px 12px 8px 9px;
    border-radius: 8px;
    color: #111827 !important;
    font-family: Manrope, "Segoe UI", Arial, sans-serif;
    font-size: 13px;
    font-weight: 500;
    line-height: 17px;
    text-decoration: none !important;
}
.buffcorp-menu .children > a:hover,.buffcorp-menu .children > a.active,.page-tree a:hover,.page-tree a.active { background: #eaf3fc !important; color: #2e6cbf !important; }
.buffcorp-menu .children > a > img { display: none; }
.app-child-icon { display: grid; width: 17px; height: 17px; flex: 0 0 17px; place-items: center; color: #3b82d0; }
.app-child-icon svg { width: 16px; height: 16px; }
.buffcorp-menu .children > a > span:not(.app-child-icon) { min-width: 0; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.buffcorp-menu .children > a > .app-nav-badge { margin-left: auto; color: #fff !important; }
.page-id-search { display: flex; align-items: center; gap: 6px; margin: 0 8px 5px; padding: 7px; border: 1px solid #d9e6f3; border-radius: 8px; }
.page-id-search input { min-width: 0; width: 100%; height: 28px; padding: 0 7px; border: 1px solid #d9e6f3; border-radius: 6px; color: #111827; font-size: 10px; outline: 0; }
.page-id-search button { display: grid; width: 28px; height: 28px; flex: 0 0 28px; padding: 0; place-items: center; border: 0; border-radius: 6px; background: #eaf3fc; color: #2e6cbf; cursor: pointer; }
.page-id-search button svg { width: 14px; height: 14px; }
.page-tree { padding: 0 6px; }
.page-tree-node { position: relative; }
.page-tree-row { display: flex; align-items: center; }
.page-tree-row > a { min-width: 0; flex: 1; }
.page-tree-toggle { display: grid; width: 24px; height: 28px; flex: 0 0 24px; padding: 0; place-items: center; border: 0; background: transparent; color: #687b91; cursor: pointer; }
.page-tree-toggle svg { width: 13px; height: 13px; transition: transform .18s ease; }
.page-tree-node.tree-closed > .page-tree-row .page-tree-toggle svg { transform: rotate(-90deg); }
.page-tree-node.tree-closed > .subdir { display: none; }
.page-tree .subdir { margin-left: 16px !important; }
.buffcorp-support { display: grid; gap: 3px; }
.sidebar-support-item {
    display: flex !important;
    width: 100%;
    min-height: 40px !important;
    align-items: center;
    justify-content: flex-start;
    gap: 8px;
    padding: 9px 14px 9px 12px !important;
    border: 0 !important;
    border-radius: 9px;
    background: transparent !important;
    color: #111827 !important;
    cursor: pointer;
    box-sizing: border-box;
    font-family: Manrope, "Segoe UI", Arial, sans-serif !important;
    font-size: 14px !important;
    font-weight: 700;
    line-height: 18px;
    text-align: left;
    text-decoration: none !important;
    white-space: nowrap;
}
.sidebar-support-item:hover,.sidebar-support-item.active { background: #eaf3fc; color: #2e6cbf; text-decoration: none; }
.sidebar-support-icon { display: grid; width: 19px; height: 19px; flex: 0 0 19px; place-items: center; }
.sidebar-support-icon svg { width: 18px; height: 18px; }
.sidebar-support-text { min-width: 0; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.sidebar-language select { max-width: 112px; height: 27px; border: 1px solid #d9e6f3; border-radius: 6px; background: #fff; color: #111827; font-size: 10px; }
.layout.sidebar-collapsed .left-menu { width: 68px !important; flex-basis: 68px; }
.layout.sidebar-collapsed .buffcorp-brand { justify-content: center; gap: 0; padding-right: 10px; padding-left: 10px; }
.layout.sidebar-collapsed .buffcorp-brand-copy,.layout.sidebar-collapsed .buffcorp-collapse,.layout.sidebar-collapsed .buffcorp-section-label,.layout.sidebar-collapsed .app-parent-label,.layout.sidebar-collapsed .app-parent-chevron,.layout.sidebar-collapsed .app-nav-badge,.layout.sidebar-collapsed .sidebar-support-text { display: none !important; }
.layout.sidebar-collapsed .buffcorp-logo-button { cursor: pointer; }
.layout.sidebar-collapsed .buffcorp-nav-scroll { padding-right: 9px; padding-left: 9px; }
.layout.sidebar-collapsed .buffcorp-menu { width: 100% !important; table-layout: fixed !important; }
.layout.sidebar-collapsed .buffcorp-menu td { overflow: hidden; }
.layout.sidebar-collapsed .buffcorp-menu .header,
.layout.sidebar-collapsed .sidebar-support-item,
.layout.sidebar-collapsed .sidebar-language {
    width: 50px;
    height: 40px;
    min-height: 40px;
    max-width: 50px;
    align-items: center;
    justify-content: center;
    gap: 0;
    padding-right: 0 !important;
    padding-left: 0 !important;
    box-sizing: border-box;
}
.layout.sidebar-collapsed .buffcorp-support { justify-items: center; }
.layout.sidebar-collapsed .sidebar-support-icon { width: 22px; height: 22px; flex-basis: 22px; }
.layout.sidebar-collapsed .sidebar-support-icon svg { width: 20px; height: 20px; }
.layout.sidebar-collapsed .buffcorp-menu td > .children { display: none !important; max-height: 0 !important; padding-top: 0 !important; padding-bottom: 0 !important; opacity: 0 !important; }
.layout.sidebar-collapsed .buffcorp-menu td.has-notifications .app-parent-icon:after { position: absolute; top: -4px; right: -4px; width: 8px; height: 8px; border: 2px solid #fff; border-radius: 50%; background: #d64545; content: ""; }
.layout.sidebar-initializing .left-menu,
.layout.sidebar-initializing .buffcorp-menu td > .children,
.layout.sidebar-initializing .buffcorp-brand-copy,
.layout.sidebar-initializing .buffcorp-collapse,
.layout.sidebar-initializing .app-parent-label,
.layout.sidebar-initializing .app-parent-chevron,
.layout.sidebar-initializing .sidebar-support-item {
    transition: none !important;
    animation: none !important;
}
@media (max-width: 1200px) and (min-width: 621px) {
    .left-menu { width: 280px !important; flex-basis: 280px; }
}
@media (max-width: 620px) {
    .left-menu { width: 280px !important; flex-basis: 280px; }
}
</style>

<script type="text/javascript">
var arrNavigation =
<!-- CODE echo mosNavigation(0, "Root"); -->
;
</script>

<div class="buffcorp-sidebar" id="buffcorp-sidebar" data-task-count="{giaoviec_sum}" data-login-id="{login_id}">
    <div class="buffcorp-brand">
        <button type="button" class="buffcorp-logo-button" id="buffcorp-logo-button" aria-label="Logo BUFFCORP">
            <img src="templates/{skin}/images/menu/logo-xanh.png" alt="">
        </button>
        <div class="buffcorp-brand-copy">
            <strong>BUFFCORP</strong>
            <small>Version 1.0</small>
        </div>
        <button type="button" class="buffcorp-collapse" id="buffcorp-collapse" aria-label="Thu gọn sidebar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                <path d="M9 4v16M15 9l-3 3 3 3"></path>
            </svg>
        </button>
    </div>

    <div class="buffcorp-nav-scroll">
        <div class="buffcorp-section-label">MENU</div>
        <table class="leftnav buffcorp-menu" id="buffcorp-menu" cellpadding="0" cellspacing="0">
            <tbody>
                <tr style="display:{allow_menu}">
	                    <td class="normalrow">
	                        <div class="header"><span class="app-parent-main"><span class="app-parent-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect></svg></span><span class="app-parent-label">Tổng quan</span></span><span class="app-parent-meta"><span class="app-parent-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m7 10 5 5 5-5"></path></svg></span></span></div>
	                        <div class="children">
	                            <a href="main.php?option=common_lists/admin_dashboard&amp;mode=dashboard&amp;l={LANGUAGEID}"><span class="app-child-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 20V10M10 20V4M16 20v-7M22 20V7"></path></svg></span><span>Dashboard tổng thể</span></a>
	                        </div>
	                    </td>
                </tr>
                <!-- CODE echo mosFunctionMenu(0, "Root"); -->
                <tr>
	                    <td class="normalrow legacy-news-group">
	                        <div class="header"><span class="app-parent-main"><span class="app-parent-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="4" rx="1"></rect><path d="M5 7v14h14V7M10 11h4"></path></svg></span><span class="app-parent-label">Quản lý Tin tức</span></span><span class="app-parent-meta"><span class="app-parent-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m7 10 5 5 5-5"></path></svg></span></span></div>
                        <div class="children">
                            <form class="page-id-search" method="post" action="?option=product/product&amp;mode=info" name="search">
                                <input name="id" type="text" maxlength="6" placeholder="Nhập ID trang">
                                <button type="button" id="page-tree-refresh" aria-label="Làm mới" title="Làm mới"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6v5h-5M4 18v-5h5"></path><path d="M18 9a7 7 0 0 0-12-3L4 8M6 15a7 7 0 0 0 12 3l2-2"></path></svg></button>
                            </form>
                            <div class="page-tree" id="page-tree"></div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="buffcorp-section-label support">SUPPORT</div>
        <div class="buffcorp-support">
            <a class="sidebar-support-item" id="sidebar-chat-button" href="?option=chat/chat">
                <span class="sidebar-support-icon" data-sidebar-icon="chat"></span>
                <span class="sidebar-support-text">Chat</span>
            </a>
            <a class="sidebar-support-item" style="display:{allow_menu}" href="?option=functionmenu/functionmenu&amp;mode=list&amp;id=0">
                <span class="sidebar-support-icon" data-sidebar-icon="settings"></span>
                <span class="sidebar-support-text">Quản lý menu</span>
            </a>
            <label class="sidebar-support-item sidebar-language">
                <span class="sidebar-support-icon" data-sidebar-icon="language"></span>
                <span class="sidebar-support-text">
                    <!-- DO ComboFromTable("language_id", "tbl_languages", "language_id", "language_name", "language_id", 0, "" , "" , "isactive = 1" , "reShow(this.value)", 1) -->
                </span>
            </label>
            <a class="sidebar-support-item" href="logout.php">
                <span class="sidebar-support-icon" data-sidebar-icon="logout"></span>
                <span class="sidebar-support-text">Đăng xuất</span>
            </a>
        </div>
    </div>
</div>

<script type="text/javascript">
(function () {
    function iconSvg(name) {
        if (name === 'chat') {
            return '<svg viewBox="0 -960 960 960" fill="currentColor" aria-hidden="true"><path d="M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"></path></svg>';
        }
        var paths = {
            grid: '<rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect>',
            users: '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path>',
            briefcase: '<rect x="3" y="7" width="18" height="13" rx="2"></rect><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18M10 12v2h4v-2"></path>',
            wallet: '<path d="M20 7V5a2 2 0 0 0-2-2H5a3 3 0 0 0 0 6h16v10a2 2 0 0 1-2 2H5a3 3 0 0 1-3-3V6"></path><path d="M16 13h2"></path>',
            database: '<ellipse cx="12" cy="5" rx="8" ry="3"></ellipse><path d="M4 5v6c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 11v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"></path>',
            file: '<path d="M6 2h9l5 5v15H6z"></path><path d="M14 2v6h6M9 13h6M9 17h6"></path>',
            search: '<circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4M8 11l2 2 4-4"></path>',
            settings: '<circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-2.83 2.83-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21h-4v-.09A1.7 1.7 0 0 0 8.6 19.4a1.7 1.7 0 0 0-1.88.34l-.06.06-2.83-2.83.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3v-4h.09A1.7 1.7 0 0 0 4.6 8.6a1.7 1.7 0 0 0-.34-1.88l-.06-.06 2.83-2.83.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3h4v.09A1.7 1.7 0 0 0 15.4 4a1.7 1.7 0 0 0 1.88-.34l.06-.06 2.83 2.83-.06.06A1.7 1.7 0 0 0 19.4 8c.14.37.35.71.6 1 .29.28.68.43 1.09.4H21v4h-.09A1.7 1.7 0 0 0 19.4 15z"></path>',
            folder: '<path d="M3 6h6l2 2h10v11H3z"></path>',
            chevron: '<path d="m7 10 5 5 5-5"></path>',
            chat: '<path d="M21 15a4 4 0 0 1-4 4H8l-5 3 1.5-4A8 8 0 1 1 21 15z"></path><path d="M8 12h.01M12 12h.01M16 12h.01"></path>',
            language: '<circle cx="12" cy="12" r="9"></circle><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"></path>',
            logout: '<path d="M10 17l5-5-5-5M15 12H3"></path><path d="M14 3h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5"></path>',
            close: '<path d="M6 6l12 12M18 6 6 18"></path>',
            send: '<path d="m22 2-7 20-4-9-9-4zM22 2 11 13"></path>',
            refresh: '<path d="M20 6v5h-5M4 18v-5h5"></path><path d="M18 9a7 7 0 0 0-12-3L4 8M6 15a7 7 0 0 0 12 3l2-2"></path>',
            globe: '<circle cx="12" cy="12" r="9"></circle><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"></path>',
            clipboard: '<rect x="5" y="4" width="14" height="17" rx="2"></rect><path d="M9 4V2h6v2M9 12l2 2 4-4"></path>',
            user: '<circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path>',
            chart: '<path d="M4 20V10M10 20V4M16 20v-7M22 20V7"></path>',
            check: '<circle cx="12" cy="12" r="9"></circle><path d="m8 12 3 3 5-6"></path>',
            calendar: '<rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M8 3v4M16 3v4M3 10h18"></path>',
            money: '<circle cx="12" cy="12" r="9"></circle><path d="M16 8.5c-.8-.8-1.9-1.2-3.3-1.2-1.7 0-3 .8-3 2.2 0 1.5 1.2 2 3.1 2.4 1.9.4 3 .9 3 2.4 0 1.4-1.3 2.4-3.2 2.4-1.5 0-2.8-.5-3.7-1.4M12 5v14"></path>',
            building: '<path d="M4 21V5l8-3v19M12 8h8v13M7 7h2M7 11h2M7 15h2M15 11h2M15 15h2M3 21h18"></path>',
            box: '<path d="m4 7 8-4 8 4-8 4zM4 7v10l8 4 8-4V7M12 11v10"></path>',
            server: '<rect x="3" y="4" width="18" height="6" rx="2"></rect><rect x="3" y="14" width="18" height="6" rx="2"></rect><path d="M7 7h.01M7 17h.01"></path>',
            mail: '<rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path>',
            image: '<rect x="3" y="4" width="18" height="16" rx="2"></rect><circle cx="8" cy="9" r="2"></circle><path d="m4 18 5-5 4 4 3-3 4 4"></path>',
            link: '<path d="M10 13a5 5 0 0 0 7.5.5l2-2a5 5 0 0 0-7-7l-1.1 1.1M14 11a5 5 0 0 0-7.5-.5l-2 2a5 5 0 0 0 7 7l1.1-1.1"></path>',
            key: '<circle cx="8" cy="15" r="4"></circle><path d="m11 12 8-8M15 8l2 2M17 6l2 2"></path>',
            shield: '<path d="M12 3 4 6v5c0 5 3.4 8.5 8 10 4.6-1.5 8-5 8-10V6z"></path><path d="m9 12 2 2 4-5"></path>',
            map: '<path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3zM9 3v15M15 6v15"></path>',
            tag: '<path d="M20 13 13 20 4 11V4h7z"></path><circle cx="8.5" cy="8.5" r="1.5"></circle>',
            layers: '<path d="m12 2 9 5-9 5-9-5z"></path><path d="m3 12 9 5 9-5"></path><path d="m3 17 9 5 9-5"></path>',
            monitor: '<rect x="3" y="4" width="18" height="12" rx="2"></rect><path d="M8 20h8M12 16v4"></path>',
            sitemap: '<rect x="9" y="3" width="6" height="4" rx="1"></rect><rect x="3" y="17" width="6" height="4" rx="1"></rect><rect x="15" y="17" width="6" height="4" rx="1"></rect><path d="M12 7v5M6 17v-3h12v3"></path>',
            archive: '<rect x="3" y="3" width="18" height="4" rx="1"></rect><path d="M5 7v14h14V7M10 11h4"></path>',
            circle: '<circle cx="12" cy="12" r="4"></circle>'
        };
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + (paths[name] || paths.circle) + '</svg>';
    }

    function normalized(text) {
        text = String(text || '').toLowerCase().replace(/\u0111/g, 'd');
        try { text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, ''); } catch (e) { /* keep original text */ }
        return text;
    }

    function parentIcon(label) {
        label = normalized(label);
        if (label.indexOf('tong quan') >= 0) return 'grid';
        if (label.indexOf('kinh doanh') >= 0) return 'briefcase';
        if (label.indexOf('nhan su') >= 0) return 'users';
        if (label.indexOf('khach hang') >= 0) return 'briefcase';
        if (label.indexOf('tai chinh') >= 0 || label.indexOf('kho') >= 0) return 'wallet';
        if (label.indexOf('tai nguyen') >= 0) return 'database';
        if (label.indexOf('ky thuat web') >= 0 || label.indexOf('thuáº­t web') >= 0 || label.indexOf('thuật web') >= 0) return 'monitor';
        if (label.indexOf('so do cong ty') >= 0 || label.indexOf('sæ') >= 0 || label.indexOf('cã') >= 0 || label.indexOf('công ty') >= 0) return 'sitemap';
        if (label === 'chung') return 'grid';
        if (label.indexOf('noi dung') >= 0 || label.indexOf('tin tuc') >= 0 || label.indexOf('há»‡ thá»‘ng') >= 0 || label.indexOf('hệ thống') >= 0) return 'archive';
        if (label.indexOf('seo') >= 0) return 'search';
        if (label.indexOf('he thong') >= 0) return 'settings';
        return 'layers';
    }

    function childIcon(anchor) {
        var value = normalized((anchor.getAttribute('href') || '') + ' ' + (anchor.textContent || ''));
        if (value.indexOf('dashboard') >= 0 || value.indexOf('kpi') >= 0 || value.indexOf('thong ke') >= 0) return 'chart';
        if (value.indexOf('xacnhan') >= 0 || value.indexOf('xac nhan') >= 0) return 'check';
        if (value.indexOf('giaoviec') >= 0 || value.indexOf('nhiemvu') >= 0) return 'clipboard';
        if (value.indexOf('chamcong') >= 0 || value.indexOf('nghiphep') >= 0 || value.indexOf('nghi phep') >= 0) return 'calendar';
        if (value.indexOf('bangluong') >= 0 || value.indexOf('congno') >= 0 || value.indexOf('hoa don') >= 0 || value.indexOf('thu chi') >= 0) return 'money';
        if (value.indexOf('sodocongty') >= 0 || value.indexOf('department') >= 0 || value.indexOf('phong ban') >= 0) return 'building';
        if (value.indexOf('customer') >= 0 || value.indexOf('khach hang') >= 0 || value.indexOf('group') >= 0 || value.indexOf('nhom') >= 0) return 'users';
        if (value.indexOf('member') >= 0 || value.indexOf('nhan vien') >= 0) return 'user';
        if (value.indexOf('server') >= 0 || value.indexOf('may chu') >= 0 || value.indexOf('hosting') >= 0) return 'server';
        if (value.indexOf('goidichvu') >= 0 || value.indexOf('goi dich vu') >= 0 || value.indexOf('kho') >= 0) return 'box';
        if (value.indexOf('website') >= 0 || value.indexOf('domain') >= 0 || value.indexOf('ten mien') >= 0) return 'globe';
        if (value.indexOf('mail') >= 0 || value.indexOf('email') >= 0) return 'mail';
        if (value.indexOf('image') >= 0 || value.indexOf('banner') >= 0 || value.indexOf('hinh anh') >= 0) return 'image';
        if (value.indexOf('backlink') >= 0 || value.indexOf('link') >= 0) return 'link';
        if (value.indexOf('password') >= 0 || value.indexOf('cuttpw') >= 0 || value.indexOf('mat khau') >= 0) return 'key';
        if (value.indexOf('role') >= 0 || value.indexOf('permission') >= 0 || value.indexOf('phan quyen') >= 0) return 'shield';
        if (value.indexOf('map') >= 0 || value.indexOf('dia diem') >= 0) return 'map';
        if (value.indexOf('type') >= 0 || value.indexOf('category') >= 0 || value.indexOf('loai') >= 0) return 'tag';
        if (value.indexOf('page') >= 0 || value.indexOf('news') >= 0 || value.indexOf('bai viet') >= 0 || value.indexOf('noi dung') >= 0) return 'file';
        if (value.indexOf('function') >= 0 || value.indexOf('menu') >= 0 || value.indexOf('cau hinh') >= 0) return 'settings';
        return 'circle';
    }

    function nearestGroup(node) {
        while (node && node !== document) {
            if (node.tagName && node.tagName.toLowerCase() === 'td' && /(^| )(mainrow|normalrow)( |$)/.test(node.className)) return node;
            node = node.parentNode;
        }
        return null;
    }

    function directChildrenPanel(group) {
        if (!group) return null;
        for (var i = 0; i < group.children.length; i++) {
            if (/(^| )children( |$)/.test(group.children[i].className || '')) return group.children[i];
        }
        return null;
    }

    function measureChildrenPanel(panel) {
        if (!panel) return 0;
        var oldMaxHeight = panel.style.maxHeight;
        panel.style.maxHeight = 'none';
        var height = panel.scrollHeight;
        panel.style.maxHeight = oldMaxHeight;
        return height;
    }

    function syncGroupHeight(group) {
        var panel = directChildrenPanel(group);
        if (!panel) return;
        if (/(^| )mainrow( |$)/.test(group.className || '')) {
            panel.style.setProperty('--sidebar-children-height', measureChildrenPanel(panel) + 'px');
        } else {
            panel.style.setProperty('--sidebar-children-height', '0px');
        }
    }

    function setGroupOpen(group, open) {
        if (!group) return;
        var panel = directChildrenPanel(group);
        if (panel && open) panel.style.setProperty('--sidebar-children-height', measureChildrenPanel(panel) + 'px');
        group.className = group.className.replace(/\b(mainrow|normalrow)\b/g, '').replace(/\s+/g, ' ').replace(/^\s+|\s+$/g, '');
        group.className += (group.className ? ' ' : '') + (open ? 'mainrow' : 'normalrow');
        if (panel && !open) panel.style.setProperty('--sidebar-children-height', '0px');
        var header = group.querySelector('.header');
        if (header) header.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    function closeAllGroups(except) {
        var groups = document.querySelectorAll('#buffcorp-menu td.mainrow, #buffcorp-menu td.normalrow');
        for (var i = 0; i < groups.length; i++) {
            if (groups[i] !== except) setGroupOpen(groups[i], false);
        }
    }

    function syncAllGroupHeights() {
        var groups = document.querySelectorAll('#buffcorp-menu td.mainrow, #buffcorp-menu td.normalrow');
        for (var i = 0; i < groups.length; i++) syncGroupHeight(groups[i]);
    }

    window.changeClass = function (group) {
        var layout = document.querySelector('.layout');
        var wasCollapsed = layout && layout.className.indexOf('sidebar-collapsed') >= 0;
        if (wasCollapsed) setSidebarCollapsed(false);
        var willOpen = wasCollapsed || group.className.indexOf('mainrow') < 0;
        closeAllGroups(group);
        setGroupOpen(group, willOpen);
    };

    function buildPageNode(obj) {
        if (!obj || !obj.length) return null;
        var node = document.createElement('div');
        node.className = 'page-tree-node';
        var row = document.createElement('div');
        row.className = 'page-tree-row';
        var hasChildren = obj.length > 3;
        if (hasChildren) {
            var toggle = document.createElement('button');
            toggle.type = 'button';
            toggle.className = 'page-tree-toggle';
            toggle.setAttribute('aria-label', 'Thu gọn');
            toggle.setAttribute('aria-expanded', 'true');
            toggle.innerHTML = iconSvg('chevron');
            toggle.onclick = function () {
                var closed = node.className.indexOf('tree-closed') < 0;
                node.className = closed ? 'page-tree-node tree-closed' : 'page-tree-node';
                toggle.setAttribute('aria-expanded', closed ? 'false' : 'true');
            };
            row.appendChild(toggle);
        }
        var link = document.createElement('a');
        link.href = obj[0] == 1
            ? '?option=pages/pages&mode=list&l={LANGUAGEID}&cid=' + encodeURIComponent(obj[1])
            : '?option=pages/pages&mode=info&l={LANGUAGEID}&id=' + encodeURIComponent(obj[1]);
        var icon = document.createElement('span');
        icon.className = 'app-child-icon';
        icon.innerHTML = iconSvg(obj[0] == 1 ? 'folder' : 'file');
        var label = document.createElement('span');
        label.textContent = obj[2] || '';
        link.appendChild(icon);
        link.appendChild(label);
        link.onclick = function (event) {
            if (event && event.stopPropagation) event.stopPropagation();
            try { sessionStorage.setItem('buffcorp-pending-menu-href', this.href); } catch (e) { /* storage unavailable */ }
            saveSidebarScroll();
        };
        row.appendChild(link);
        node.appendChild(row);
        if (hasChildren) {
            var children = document.createElement('div');
            children.className = 'subdir';
            for (var i = 3; i < obj.length; i++) {
                var child = buildPageNode(obj[i]);
                if (child) children.appendChild(child);
            }
            node.appendChild(children);
        }
        return node;
    }

    function markActiveLinks() {
        var current;
        try { current = new URL(window.location.href); } catch (e) { return; }
        var pending;
        try {
            pending = sessionStorage.getItem('buffcorp-pending-menu-href');
            if (pending) {
                var pendingUrl = new URL(pending, window.location.href);
                if (pendingUrl.searchParams.get('option') === current.searchParams.get('option')) current = pendingUrl;
                sessionStorage.removeItem('buffcorp-pending-menu-href');
            }
        } catch (e) { /* storage unavailable */ }
        var currentOption = current.searchParams.get('option') || '{CURRENT_OPTION}';
        var currentMode = current.searchParams.get('mode') || '{CURRENT_MODE}';
        var currentMenu = current.searchParams.get('menu');
        var links = document.querySelectorAll('#buffcorp-menu a[href], .buffcorp-support a[href]');
        var activeLink = null;
        var activeScore = -1;
        for (var i = 0; i < links.length; i++) {
            links[i].className = links[i].className.replace(/ ?active/g, '');
            try {
                var target = new URL(links[i].href, window.location.href);
                var sameOption = target.searchParams.get('option') === currentOption;
                var targetMode = target.searchParams.get('mode');
                var sameMode = !targetMode || targetMode === currentMode;
                var sameMenu = !currentMenu || target.searchParams.get('menu') === currentMenu;
                var targetCategory = target.searchParams.get('category');
                var sameCategory = !targetCategory || targetCategory === current.searchParams.get('category');
                var targetCid = target.searchParams.get('cid');
                var sameCid = !targetCid || targetCid === current.searchParams.get('cid');
                if (sameOption && sameMode && sameMenu && sameCategory && sameCid) {
                    var score = 1;
                    if (targetMode) score += 2;
                    if (target.searchParams.get('menu')) score += 2;
                    if (targetCategory) score += 2;
                    if (targetCid) score += 2;
                    if (target.href === current.href) score += 4;
                    if (score > activeScore) {
                        activeScore = score;
                        activeLink = links[i];
                    }
                }
            } catch (e) { /* ignore invalid legacy link */ }
        }
        if (activeLink) {
            var activeGroup = nearestGroup(activeLink);
            closeAllGroups(activeGroup);
            activeLink.className += (activeLink.className ? ' ' : '') + 'active';
            setGroupOpen(activeGroup, true);
        }
    }

    function enhanceGroups() {
        var groups = document.querySelectorAll('#buffcorp-menu td.mainrow, #buffcorp-menu td.normalrow');
        for (var i = 0; i < groups.length; i++) {
            var group = groups[i];
            group.onclick = null;
            group.removeAttribute('onclick');
            var header = group.querySelector('.header');
            if (!header) continue;
            var existingLabel = header.querySelector('.app-parent-label');
            var label = ((existingLabel ? existingLabel.textContent : header.textContent) || '').replace(/^\s+|\s+$/g, '');
            header.setAttribute('role', 'button');
            header.setAttribute('tabindex', '0');
            header.setAttribute('aria-expanded', group.className.indexOf('mainrow') >= 0 ? 'true' : 'false');
            if (!header.querySelector('.app-parent-main')) {
                header.innerHTML = '<span class="app-parent-main"><span class="app-parent-icon">' + iconSvg(parentIcon(label)) + '</span><span class="app-parent-label"></span></span><span class="app-parent-meta"><span class="app-parent-chevron">' + iconSvg('chevron') + '</span></span>';
                header.querySelector('.app-parent-label').textContent = label;
            }
            header.onclick = (function (currentGroup) {
                return function (event) {
                    if (event && event.stopPropagation) event.stopPropagation();
                    window.changeClass(currentGroup);
                };
            })(group);
            header.onkeydown = (function (currentGroup) {
                return function (event) {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        window.changeClass(currentGroup);
                    }
                };
            })(group);
            var links = group.querySelectorAll('.children > a');
            for (var j = 0; j < links.length; j++) {
                var oldImages = links[j].querySelectorAll('img');
                for (var k = 0; k < oldImages.length; k++) oldImages[k].parentNode.removeChild(oldImages[k]);
                var child = links[j].querySelector('.app-child-icon');
                if (!child) {
                    child = document.createElement('span');
                    child.className = 'app-child-icon';
                    child.innerHTML = iconSvg(childIcon(links[j]));
                    links[j].insertBefore(child, links[j].firstChild);
                }
                links[j].onclick = function (event) {
                    if (event && event.stopPropagation) event.stopPropagation();
                    try { sessionStorage.setItem('buffcorp-pending-menu-href', this.href); } catch (e) { /* storage unavailable */ }
                    saveSidebarScroll();
                };
            }
        }
    }

    function saveSidebarScroll() {
        var scroller = document.querySelector('.buffcorp-nav-scroll');
        if (!scroller) return;
        try { sessionStorage.setItem('buffcorp-sidebar-scroll-top', String(scroller.scrollTop || 0)); } catch (e) { /* storage unavailable */ }
    }

    function restoreSidebarScroll() {
        var scroller = document.querySelector('.buffcorp-nav-scroll');
        if (!scroller) return;
        var scrollTop = 0;
        try { scrollTop = parseInt(sessionStorage.getItem('buffcorp-sidebar-scroll-top'), 10) || 0; } catch (e) { /* storage unavailable */ }
        if (scrollTop <= 0) return;
        scroller.scrollTop = scrollTop;
        window.setTimeout(function () { scroller.scrollTop = scrollTop; }, 0);
        window.setTimeout(function () { scroller.scrollTop = scrollTop; }, 80);
    }

    function rememberSidebarTarget(target) {
        saveSidebarScroll();
        try { sessionStorage.setItem('buffcorp-sidebar-collapsed', '0'); } catch (e) { /* storage unavailable */ }
        if (sidebarLayout) sidebarLayout.className = sidebarLayout.className.replace(/ ?sidebar-collapsed/g, '');
        var url = target && (target.href || target.action);
        if (!url) return;
        try { sessionStorage.setItem('buffcorp-pending-menu-href', url); } catch (e) { /* storage unavailable */ }
    }

    function addTaskBadge() {
        var shell = document.getElementById('buffcorp-sidebar');
        var taskLink = document.querySelector('#buffcorp-menu a[href*="common_lists/giaoviec"]');
        if (!shell || !taskLink) return;
        var rawCount = parseInt(shell.getAttribute('data-task-count'), 10) || 0;
        var loginId = shell.getAttribute('data-login-id') || '0';
        var storageKey = 'buffcorp-task-seen-' + loginId;
        var seenCount = 0;
        try { seenCount = parseInt(sessionStorage.getItem(storageKey), 10) || 0; } catch (e) { /* storage unavailable */ }
        var count = Math.max(0, rawCount - seenCount);
        var group = nearestGroup(taskLink);
        if (count > 0) {
            var childBadge = document.createElement('em');
            childBadge.className = 'app-nav-badge';
            childBadge.textContent = count > 99 ? '99+' : count;
            taskLink.appendChild(childBadge);
            var parentMeta = group && group.querySelector('.app-parent-meta');
            if (parentMeta) {
                var parentBadge = childBadge.cloneNode(true);
                parentMeta.insertBefore(parentBadge, parentMeta.firstChild);
                group.className += ' has-notifications';
            }
        }
        taskLink.onclick = function (event) {
            if (event && event.stopPropagation) event.stopPropagation();
            try { sessionStorage.setItem('buffcorp-pending-menu-href', this.href); } catch (e) { /* storage unavailable */ }
            saveSidebarScroll();
            try { sessionStorage.setItem(storageKey, String(rawCount)); } catch (e) { /* storage unavailable */ }
            var badges = group ? group.querySelectorAll('.app-nav-badge') : [];
            for (var i = 0; i < badges.length; i++) badges[i].parentNode.removeChild(badges[i]);
            if (group) group.className = group.className.replace(/ ?has-notifications/g, '');
        };
    }

    var sidebarLayout = null;
    function setSidebarCollapsed(collapsed) {
        if (!sidebarLayout) sidebarLayout = document.querySelector('.layout');
        if (!sidebarLayout) return;
        if (collapsed) {
            closeAllGroups();
            if (sidebarLayout.className.indexOf('sidebar-collapsed') < 0) sidebarLayout.className += ' sidebar-collapsed';
        } else {
            sidebarLayout.className = sidebarLayout.className.replace(/ ?sidebar-collapsed/g, '');
        }
        var logo = document.getElementById('buffcorp-logo-button');
        var button = document.getElementById('buffcorp-collapse');
        if (logo) logo.setAttribute('aria-label', collapsed ? 'Mở rộng sidebar' : 'Logo BUFFCORP');
        if (button) button.setAttribute('aria-label', collapsed ? 'Mở rộng sidebar' : 'Thu gọn sidebar');
        try { sessionStorage.setItem('buffcorp-sidebar-collapsed', collapsed ? '1' : '0'); } catch (e) { /* storage unavailable */ }
    }

    function closeMobileMenu() {
        if (!sidebarLayout) sidebarLayout = document.querySelector('.layout');
        if (!sidebarLayout) return;
        sidebarLayout.className = sidebarLayout.className
            .replace(/ ?menu-open/g, '')
            .replace(/ ?sidebar-collapsed/g, '');
        var mobileButton = document.getElementById('buffcorp-mobile-menu');
        if (mobileButton) mobileButton.setAttribute('aria-expanded', 'false');
        try { sessionStorage.setItem('buffcorp-sidebar-collapsed', '0'); } catch (e) { /* storage unavailable */ }
    }

    function initializeSidebar() {
        sidebarLayout = document.querySelector('.layout');
        if (sidebarLayout && sidebarLayout.className.indexOf('sidebar-initializing') < 0) sidebarLayout.className += ' sidebar-initializing';
        var tree = document.getElementById('page-tree');
        var treeRoot = buildPageNode(arrNavigation);
        if (tree && treeRoot) tree.appendChild(treeRoot);
        enhanceGroups();
        markActiveLinks();
        syncAllGroupHeights();
        addTaskBadge();

        var staticIcons = document.querySelectorAll('[data-sidebar-icon]');
        for (var i = 0; i < staticIcons.length; i++) staticIcons[i].innerHTML = iconSvg(staticIcons[i].getAttribute('data-sidebar-icon'));
        var pageTreeRefresh = document.getElementById('page-tree-refresh');
        if (pageTreeRefresh) pageTreeRefresh.innerHTML = iconSvg('refresh');

        var collapseButton = document.getElementById('buffcorp-collapse');
        var logoButton = document.getElementById('buffcorp-logo-button');
        collapseButton.onclick = function (event) {
            if (event && event.stopPropagation) event.stopPropagation();
            if (window.innerWidth <= 820) {
                closeMobileMenu();
                return;
            }
            setSidebarCollapsed(sidebarLayout.className.indexOf('sidebar-collapsed') < 0);
        };
        logoButton.onclick = function () {
            if (sidebarLayout.className.indexOf('sidebar-collapsed') >= 0) setSidebarCollapsed(false);
        };
        if (pageTreeRefresh) pageTreeRefresh.onclick = function () { window.location.reload(); };
        var sidebar = document.getElementById('buffcorp-sidebar');
        if (sidebar && sidebar.addEventListener) {
            sidebar.addEventListener('click', function (event) {
                var node = event.target;
                while (node && node !== sidebar) {
                    if (node.tagName && node.tagName.toLowerCase() === 'a') {
                        rememberSidebarTarget(node);
                        break;
                    }
                    node = node.parentNode;
                }
            }, true);
            sidebar.addEventListener('submit', function (event) {
                rememberSidebarTarget(event.target);
            }, true);
        }
        var scrollArea = document.querySelector('.buffcorp-nav-scroll');
        if (scrollArea && window.addEventListener) {
            scrollArea.addEventListener('scroll', saveSidebarScroll, false);
            window.addEventListener('beforeunload', saveSidebarScroll, false);
            window.addEventListener('resize', syncAllGroupHeights, false);
        }
        var language = document.getElementsByName('language_id')[0];
        if (language) language.value = '{LANGUAGEID}';
        var collapsed = false;
        try { collapsed = sessionStorage.getItem('buffcorp-sidebar-collapsed') === '1'; } catch (e) { /* storage unavailable */ }
        setSidebarCollapsed(collapsed);
        restoreSidebarScroll();
        window.setTimeout(function () {
            if (sidebarLayout) sidebarLayout.className = sidebarLayout.className.replace(/ ?sidebar-initializing/g, '');
        }, 60);
    }

    window.reShow = function (languageId) {
        if (window.buffcorpShowRouteLoader) window.buffcorpShowRouteLoader();
        window.setTimeout(function () {
            window.location.href = 'main.php?l=' + encodeURIComponent(languageId);
        }, 1000);
    };
    window.refresh = function () {
        if (window.buffcorpShowRouteLoader) window.buffcorpShowRouteLoader();
        window.setTimeout(function () {
            window.location.reload();
        }, 1000);
    };

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initializeSidebar);
    else initializeSidebar();
})();
</script>
