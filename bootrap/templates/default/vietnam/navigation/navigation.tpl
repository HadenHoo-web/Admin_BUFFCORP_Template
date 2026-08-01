<style>
.left-menu {
    width: 236px !important;
    flex: 0 0 236px;
    overflow: hidden !important;
    border-right: 1px solid #d9e6f3 !important;
    background: #fff !important;
    color: #111827;
    font-family: Manrope, "Segoe UI", Arial, sans-serif;
    transition: width .3s cubic-bezier(.2,.8,.2,1), flex-basis .3s cubic-bezier(.2,.8,.2,1);
}
.layout > .main-content { min-width: 0; }
.buffcorp-sidebar { display: flex; height: 100%; flex-direction: column; background: #fff; }
.buffcorp-brand { display: flex; min-height: 72px; align-items: center; gap: 10px; padding: 14px 12px; border-bottom: 1px solid #edf2f7; }
.buffcorp-logo-button { display: grid; width: 40px; height: 40px; flex: 0 0 40px; padding: 0; place-items: center; border: 0; border-radius: 50%; background: transparent; cursor: default; }
.buffcorp-logo-button img { display: block; width: 40px; height: 40px; border-radius: 50%; object-fit: contain; }
.buffcorp-brand-copy { min-width: 0; flex: 1; overflow: hidden; white-space: nowrap; transition: max-width .24s ease, opacity .16s ease; }
.buffcorp-brand-copy strong,.buffcorp-brand-copy small { display: block; }
.buffcorp-brand-copy strong { color: #102a43; font-size: 17px; line-height: 21px; }
.buffcorp-brand-copy small { color: #687b91; font-size: 12px; line-height: 16px; }
.buffcorp-collapse { display: grid; width: 31px; height: 31px; flex: 0 0 31px; padding: 0; place-items: center; border: 1px solid #d9e6f3; border-radius: 8px; background: #fff; color: #2e6cbf; cursor: pointer; }
.buffcorp-collapse svg { width: 16px; height: 16px; }
.buffcorp-nav-scroll { min-height: 0; flex: 1; overflow-x: hidden; overflow-y: auto; padding: 8px 10px 14px; scrollbar-width: none; -ms-overflow-style: none; }
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
    padding: 9px 10px !important;
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
.app-parent-label { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.app-parent-icon { position: relative; display: grid; width: 19px; height: 19px; flex: 0 0 19px; place-items: center; }
.app-parent-icon svg { width: 18px; height: 18px; }
.app-parent-meta { display: flex; align-items: center; gap: 5px; }
.app-parent-chevron { display: grid; width: 16px; height: 16px; place-items: center; transition: transform .18s ease; }
.app-parent-chevron svg { width: 15px; height: 15px; }
.buffcorp-menu .mainrow .app-parent-chevron { transform: rotate(180deg); }
.app-nav-badge { display: grid; min-width: 19px; height: 19px; padding: 0 5px; place-items: center; border-radius: 10px; background: #d64545; color: #fff; font-size: 9px; font-style: normal; font-weight: 800; line-height: 19px; }
.buffcorp-menu td > .children {
    display: block !important;
    max-height: 0;
    overflow: hidden;
    padding: 0 0 0 13px;
    opacity: 0;
    visibility: visible !important;
    transition: max-height .28s ease, padding .28s ease, opacity .18s ease;
}
.buffcorp-menu .mainrow > .children { max-height: 1500px; padding: 5px 0 7px 13px; opacity: 1; }
.buffcorp-menu .children > a,.page-tree a {
    display: flex;
    min-height: 38px;
    align-items: center;
    gap: 9px;
    padding: 8px 9px;
    border-radius: 8px;
    color: #111827 !important;
    font-family: Manrope, "Segoe UI", Arial, sans-serif;
    font-size: 11px;
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
    display: flex;
    width: 100%;
    min-height: 39px;
    align-items: center;
    gap: 10px;
    padding: 9px 10px;
    border: 0;
    border-radius: 9px;
    background: transparent;
    color: #111827;
    cursor: pointer;
    font: 600 12px/18px Manrope, "Segoe UI", Arial, sans-serif;
    text-align: left;
    text-decoration: none;
}
.sidebar-support-item:hover,.sidebar-support-item.active { background: #eaf3fc; color: #2e6cbf; text-decoration: none; }
.sidebar-support-icon { display: grid; width: 18px; height: 18px; flex: 0 0 18px; place-items: center; }
.sidebar-support-icon svg { width: 17px; height: 17px; }
.sidebar-support-text { min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.sidebar-language select { max-width: 112px; height: 27px; border: 1px solid #d9e6f3; border-radius: 6px; background: #fff; color: #111827; font-size: 10px; }
.sidebar-chat-drawer {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    display: flex;
    width: 380px;
    max-width: 100%;
    flex-direction: column;
    background: #fff;
    box-shadow: -18px 0 45px rgba(16,24,40,.16);
    color: #17324d;
    transform: translateX(110%);
    transition: transform .22s ease;
    z-index: 10050;
}
.sidebar-chat-drawer.open { transform: translateX(0); }
.sidebar-chat-head { display: flex; min-height: 68px; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid #d9e6f3; }
.sidebar-chat-head strong { font-size: 16px; }
.sidebar-chat-close { display: grid; width: 32px; height: 32px; padding: 0; place-items: center; border: 0; background: transparent; color: #687b91; cursor: pointer; }
.sidebar-chat-close svg { width: 19px; height: 19px; }
.sidebar-chat-messages { display: flex; min-height: 0; flex: 1; flex-direction: column; gap: 12px; overflow: auto; padding: 20px; }
.sidebar-chat-message { display: flex; align-items: flex-end; gap: 8px; }
.sidebar-chat-message > span { display: grid; width: 28px; height: 28px; flex: 0 0 28px; place-items: center; border-radius: 50%; background: #eaf3fc; color: #2e6cbf; font-size: 10px; font-weight: 800; }
.sidebar-chat-message p { max-width: 78%; margin: 0; padding: 10px 12px; border-radius: 12px 12px 12px 3px; background: #f4f8fc; color: #17324d; font-size: 11px; line-height: 17px; }
.sidebar-chat-message.user { justify-content: flex-end; }
.sidebar-chat-message.user p { border-radius: 12px 12px 3px 12px; background: #2e6cbf; color: #fff; }
.sidebar-chat-form { display: flex; gap: 8px; padding: 14px 16px; border-top: 1px solid #d9e6f3; }
.sidebar-chat-form input { min-width: 0; height: 40px; flex: 1; padding: 0 12px; border: 1px solid #d9e6f3; border-radius: 8px; color: #111827; font-size: 11px; outline: 0; }
.sidebar-chat-form input:focus { border-color: #2e6cbf; box-shadow: 0 0 0 3px rgba(46,108,191,.12); }
.sidebar-chat-send { display: grid; width: 40px; height: 40px; padding: 0; place-items: center; border: 0; border-radius: 8px; background: #2e6cbf; color: #fff; cursor: pointer; }
.sidebar-chat-send svg { width: 17px; height: 17px; }
.layout.sidebar-collapsed .left-menu { width: 68px !important; flex-basis: 68px; }
.layout.sidebar-collapsed .buffcorp-brand { justify-content: center; gap: 0; padding-right: 10px; padding-left: 10px; }
.layout.sidebar-collapsed .buffcorp-brand-copy,.layout.sidebar-collapsed .buffcorp-collapse,.layout.sidebar-collapsed .buffcorp-section-label,.layout.sidebar-collapsed .app-parent-label,.layout.sidebar-collapsed .app-parent-chevron,.layout.sidebar-collapsed .app-nav-badge,.layout.sidebar-collapsed .sidebar-support-text { display: none !important; }
.layout.sidebar-collapsed .buffcorp-logo-button { cursor: pointer; }
.layout.sidebar-collapsed .buffcorp-nav-scroll { padding-right: 9px; padding-left: 9px; }
.layout.sidebar-collapsed .buffcorp-menu { width: 100% !important; table-layout: fixed !important; }
.layout.sidebar-collapsed .buffcorp-menu td { overflow: hidden; }
.layout.sidebar-collapsed .buffcorp-menu .header,.layout.sidebar-collapsed .sidebar-support-item { justify-content: center; padding-right: 8px !important; padding-left: 8px !important; }
.layout.sidebar-collapsed .buffcorp-menu td > .children { display: none !important; max-height: 0 !important; padding-top: 0 !important; padding-bottom: 0 !important; opacity: 0 !important; }
.layout.sidebar-collapsed .buffcorp-menu td.has-notifications .app-parent-icon:after { position: absolute; top: -4px; right: -4px; width: 8px; height: 8px; border: 2px solid #fff; border-radius: 50%; background: #d64545; content: ""; }
@media (max-width: 1200px) and (min-width: 621px) {
    .left-menu { width: 236px !important; flex-basis: 236px; }
}
@media (max-width: 620px) {
    .left-menu { width: 236px !important; flex-basis: 236px; }
    .sidebar-chat-drawer { width: 100%; }
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
            <small>Operations Hub</small>
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
                        <div class="header">Tổng quan</div>
                        <div class="children">
                            <a href="main.php?option=common_lists/admin_dashboard&amp;mode=dashboard&amp;l={LANGUAGEID}">Dashboard tổng thể</a>
                        </div>
                    </td>
                </tr>
                <!-- CODE echo mosFunctionMenu(0, "Root"); -->
                <tr>
                    <td class="normalrow legacy-news-group">
                        <div class="header">Quản lý Tin tức</div>
                        <div class="children">
                            <form class="page-id-search" method="post" action="?option=product/product&amp;mode=info" name="search" target="main">
                                <input name="id" type="text" maxlength="6" placeholder="Nhập ID trang">
                                <button type="button" id="page-tree-refresh" aria-label="Làm mới" title="Làm mới"></button>
                            </form>
                            <div class="page-tree" id="page-tree"></div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="buffcorp-section-label support">SUPPORT</div>
        <div class="buffcorp-support">
            <button type="button" class="sidebar-support-item" id="sidebar-chat-button">
                <span class="sidebar-support-icon" data-sidebar-icon="chat"></span>
                <span class="sidebar-support-text">Chat</span>
            </button>
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

<aside class="sidebar-chat-drawer" id="sidebar-chat-drawer" aria-hidden="true" aria-labelledby="sidebar-chat-title">
    <div class="sidebar-chat-head">
        <strong id="sidebar-chat-title">Chat hỗ trợ</strong>
        <button type="button" class="sidebar-chat-close" id="sidebar-chat-close" aria-label="Đóng" data-sidebar-icon="close"></button>
    </div>
    <div class="sidebar-chat-messages" id="sidebar-chat-messages" aria-live="polite">
        <div class="sidebar-chat-message"><span>B</span><p>Xin chào! Bạn cần hỗ trợ gì?</p></div>
    </div>
    <form class="sidebar-chat-form" id="sidebar-chat-form">
        <input id="sidebar-chat-input" type="text" autocomplete="off" placeholder="Nhập tin nhắn..." aria-label="Tin nhắn">
        <button type="submit" class="sidebar-chat-send" aria-label="Gửi tin nhắn" data-sidebar-icon="send"></button>
    </form>
</aside>

<script type="text/javascript">
(function () {
    function iconSvg(name) {
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
        if (label.indexOf('ky thuat web') >= 0) return 'server';
        if (label.indexOf('so do cong ty') >= 0) return 'building';
        if (label === 'chung') return 'grid';
        if (label.indexOf('noi dung') >= 0 || label.indexOf('tin tuc') >= 0) return 'file';
        if (label.indexOf('seo') >= 0) return 'search';
        if (label.indexOf('he thong') >= 0) return 'settings';
        return 'folder';
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

    function setGroupOpen(group, open) {
        if (!group) return;
        group.className = group.className.replace(/\b(mainrow|normalrow)\b/g, '').replace(/\s+/g, ' ').replace(/^\s+|\s+$/g, '');
        group.className += (group.className ? ' ' : '') + (open ? 'mainrow' : 'normalrow');
        var header = group.querySelector('.header');
        if (header) header.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    function closeAllGroups(except) {
        var groups = document.querySelectorAll('#buffcorp-menu td.mainrow, #buffcorp-menu td.normalrow');
        for (var i = 0; i < groups.length; i++) {
            if (groups[i] !== except) setGroupOpen(groups[i], false);
        }
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
        var currentOption = current.searchParams.get('option') || '{CURRENT_OPTION}';
        var currentMode = current.searchParams.get('mode') || '{CURRENT_MODE}';
        var currentMenu = current.searchParams.get('menu');
        var links = document.querySelectorAll('#buffcorp-menu a[href]');
        var activeLink = null;
        for (var i = 0; i < links.length; i++) {
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
                    activeLink = links[i];
                    break;
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
            var label = (header.textContent || '').replace(/^\s+|\s+$/g, '');
            header.setAttribute('role', 'button');
            header.setAttribute('tabindex', '0');
            header.setAttribute('aria-expanded', group.className.indexOf('mainrow') >= 0 ? 'true' : 'false');
            header.innerHTML = '<span class="app-parent-main"><span class="app-parent-icon">' + iconSvg(parentIcon(label)) + '</span><span class="app-parent-label"></span></span><span class="app-parent-meta"><span class="app-parent-chevron">' + iconSvg('chevron') + '</span></span>';
            header.querySelector('.app-parent-label').textContent = label;
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
                var child = document.createElement('span');
                child.className = 'app-child-icon';
                child.innerHTML = iconSvg(childIcon(links[j]));
                links[j].insertBefore(child, links[j].firstChild);
            }
        }
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
        taskLink.onclick = function () {
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

    function appendChatMessage(message, own) {
        var list = document.getElementById('sidebar-chat-messages');
        var row = document.createElement('div');
        row.className = 'sidebar-chat-message' + (own ? ' user' : '');
        if (!own) {
            var avatar = document.createElement('span');
            avatar.textContent = 'B';
            row.appendChild(avatar);
        }
        var bubble = document.createElement('p');
        bubble.textContent = message;
        row.appendChild(bubble);
        list.appendChild(row);
        list.scrollTop = list.scrollHeight;
    }

    function openChat() {
        var drawer = document.getElementById('sidebar-chat-drawer');
        var button = document.getElementById('sidebar-chat-button');
        var notify = document.getElementById('notify-wrap');
        var payroll = document.getElementById('payroll-wrap');
        if (notify) notify.className = notify.className.replace(/ ?open/g, '');
        if (payroll) payroll.className = payroll.className.replace(/ ?open/g, '');
        drawer.className += drawer.className.indexOf('open') >= 0 ? '' : ' open';
        drawer.setAttribute('aria-hidden', 'false');
        button.className += button.className.indexOf('active') >= 0 ? '' : ' active';
        window.setTimeout(function () { document.getElementById('sidebar-chat-input').focus(); }, 180);
    }

    function closeChat() {
        var drawer = document.getElementById('sidebar-chat-drawer');
        var button = document.getElementById('sidebar-chat-button');
        drawer.className = drawer.className.replace(/ ?open/g, '');
        drawer.setAttribute('aria-hidden', 'true');
        button.className = button.className.replace(/ ?active/g, '');
    }

    function initializeSidebar() {
        sidebarLayout = document.querySelector('.layout');
        var tree = document.getElementById('page-tree');
        var treeRoot = buildPageNode(arrNavigation);
        if (tree && treeRoot) tree.appendChild(treeRoot);
        enhanceGroups();
        markActiveLinks();
        addTaskBadge();

        var staticIcons = document.querySelectorAll('[data-sidebar-icon]');
        for (var i = 0; i < staticIcons.length; i++) staticIcons[i].innerHTML = iconSvg(staticIcons[i].getAttribute('data-sidebar-icon'));
        document.getElementById('page-tree-refresh').innerHTML = iconSvg('refresh');

        var collapseButton = document.getElementById('buffcorp-collapse');
        var logoButton = document.getElementById('buffcorp-logo-button');
        collapseButton.onclick = function () { setSidebarCollapsed(sidebarLayout.className.indexOf('sidebar-collapsed') < 0); };
        logoButton.onclick = function () {
            if (sidebarLayout.className.indexOf('sidebar-collapsed') >= 0) setSidebarCollapsed(false);
        };
        document.getElementById('page-tree-refresh').onclick = function () { window.location.reload(); };
        document.getElementById('sidebar-chat-button').onclick = openChat;
        document.getElementById('sidebar-chat-close').onclick = closeChat;
        document.getElementById('sidebar-chat-form').onsubmit = function (event) {
            event.preventDefault();
            var input = document.getElementById('sidebar-chat-input');
            var message = input.value.replace(/^\s+|\s+$/g, '');
            if (!message) return;
            appendChatMessage(message, true);
            input.value = '';
            window.setTimeout(function () { appendChatMessage('BUFFCORP đã nhận tin nhắn của bạn.', false); }, 350);
        };
        document.addEventListener('keydown', function (event) { if (event.key === 'Escape') closeChat(); });

        var language = document.getElementsByName('language_id')[0];
        if (language) language.value = '{LANGUAGEID}';
        var collapsed = false;
        try { collapsed = sessionStorage.getItem('buffcorp-sidebar-collapsed') === '1'; } catch (e) { /* storage unavailable */ }
        setSidebarCollapsed(collapsed);
    }

    window.reShow = function (languageId) { window.location.href = 'main.php?l=' + encodeURIComponent(languageId); };
    window.refresh = function () { window.location.reload(); };

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initializeSidebar);
    else initializeSidebar();
})();
</script>
