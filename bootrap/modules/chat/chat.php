<?php
global $template, $root_path;

$loginId = isset($_SESSION["login_id"]) ? (int)$_SESSION["login_id"] : 0;
if ($loginId <= 0) {
    mosInvalidURL();
    exit;
}

require_once __DIR__ . '/chat_helpers.php';
chatEnsureSchema();
chatTouchOnline($loginId);

$currentUser = chatCurrentUser($loginId);
?>
<div id="bc-chat-app"
     class="bc-chat"
     data-current-user="<?php echo (int)$loginId; ?>"
     data-api="modules/chat/chat_api.php">
    <link rel="stylesheet" href="css/chat/chat.css?v=20260803a">

    <aside class="bc-chat-sidebar">
        <div class="bc-chat-sidebar-head">
            <div>
                <h1>Chats</h1>
                <p>Trao đổi nội bộ realtime</p>
            </div>
        </div>

        <label class="bc-chat-search">
            <span>⌕</span>
            <input type="search" id="bc-chat-user-search" placeholder="Tìm user, bộ phận...">
        </label>

        <div id="bc-chat-users" class="bc-chat-users">
            <div class="bc-chat-empty">Đang tải danh sách user...</div>
        </div>
    </aside>

    <section class="bc-chat-panel">
        <header class="bc-chat-room-head">
            <div class="bc-chat-peer">
                <div class="bc-avatar bc-avatar-lg" id="bc-chat-peer-avatar">
                    <?php echo chatInitials($currentUser['name']); ?>
                </div>
                <div>
                    <h2 id="bc-chat-peer-name">Chọn người để chat</h2>
                    <p id="bc-chat-peer-department">Online/offline, avatar, tên và bộ phận sẽ hiển thị tại đây</p>
                </div>
            </div>
        </header>

        <main id="bc-chat-messages" class="bc-chat-messages">
            <div class="bc-chat-welcome">
                <div class="bc-avatar bc-avatar-xl"><?php echo chatInitials($currentUser['name']); ?></div>
                <h3>Xin chào, <?php echo chatHtml($currentUser['name']); ?></h3>
                <p>Chọn một user bên trái để bắt đầu trao đổi, gửi file đính kèm và theo dõi thời gian nhắn.</p>
            </div>
        </main>

        <form id="bc-chat-form" class="bc-chat-compose" enctype="multipart/form-data">
            <input type="hidden" name="receiver_id" id="bc-chat-receiver-id" value="">
            <input type="text" name="message" id="bc-chat-message-input" placeholder="Nhập tin nhắn" autocomplete="off" disabled>
            <label class="bc-file-btn" title="Đính kèm file">
                <input type="file" name="attachment" id="bc-chat-file-input" disabled>
                <span></span>
            </label>
            <div id="bc-chat-file-name" class="bc-file-name"></div>
        </form>
    </section>

    <script src="js/chat/chat.js?v=20260731f"></script>
</div>
