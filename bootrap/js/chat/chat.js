(function () {
    var app = document.getElementById('bc-chat-app');
    if (!app) return;

    var api = app.getAttribute('data-api');
    var currentUserId = parseInt(app.getAttribute('data-current-user'), 10);
    var usersEl = document.getElementById('bc-chat-users');
    var searchEl = document.getElementById('bc-chat-user-search');
    var messagesEl = document.getElementById('bc-chat-messages');
    var formEl = document.getElementById('bc-chat-form');
    var inputEl = document.getElementById('bc-chat-message-input');
    var receiverEl = document.getElementById('bc-chat-receiver-id');
    var fileEl = document.getElementById('bc-chat-file-input');
    var fileNameEl = document.getElementById('bc-chat-file-name');
    var peerNameEl = document.getElementById('bc-chat-peer-name');
    var peerDeptEl = document.getElementById('bc-chat-peer-department');
    var peerAvatarEl = document.getElementById('bc-chat-peer-avatar');

    var selectedPeerId = 0;
    var selectedPeer = null;
    var lastMessageId = 0;
    var lastGroupTs = 0;
    var lastGroupDay = '';
    var userTimer = null;
    var messageTimer = null;
    var loadingMessages = false;

    function escapeHtml(value) {
        return String(value || '').replace(/[&<>"']/g, function (ch) {
            return ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'})[ch];
        });
    }

    function renderAvatar(user, extraClass) {
        var avatar = user && user.avatar ? String(user.avatar) : '';
        var classes = 'bc-avatar' + (extraClass ? ' ' + extraClass : '');
        var initials = escapeHtml(user && user.initials ? user.initials : '');
        if (avatar) {
            return '<span class="' + classes + ' has-image"><img src="' + escapeHtml(avatar) + '" alt="' + escapeHtml(user.name || '') + '"></span>';
        }
        return '<span class="' + classes + '">' + initials + '</span>';
    }

    function fetchJson(url, options) {
        return fetch(url, options || {}).then(function (resp) {
            return resp.json();
        });
    }

    function renderUsers(users) {
        if (!users.length) {
            usersEl.innerHTML = '<div class="bc-chat-empty">Không tìm thấy user phù hợp.</div>';
            return;
        }

        usersEl.innerHTML = users.map(function (user) {
            var active = user.id === selectedPeerId ? ' active' : '';
            var onlineClass = user.online ? 'online' : 'away';
            var statusTitle = user.online ? 'Đang trực tuyến' : 'Offline: ' + user.last_seen;
            var last = user.last_message ? escapeHtml(user.last_message) : escapeHtml(user.department);
            var time = user.last_message_at || (user.online ? 'online' : user.last_seen);
            var unread = user.unread > 0 ? '<span class="bc-unread">' + user.unread + '</span>' : '';
            return '' +
                '<button type="button" class="bc-user-item' + active + '" data-user-id="' + user.id + '">' +
                    renderAvatar(user, '') +
                    '<i class="bc-status ' + onlineClass + '" title="' + escapeHtml(statusTitle) + '"></i>' +
                    '<span class="bc-user-main">' +
                        '<span class="bc-user-name">' + escapeHtml(user.name) + '</span>' +
                        '<span class="bc-user-dept">' + escapeHtml(user.department) + '</span>' +
                        '<span class="bc-user-last">' + last + '</span>' +
                    '</span>' +
                    '<span class="bc-user-meta"><span>' + escapeHtml(time) + '</span>' + unread + '</span>' +
                '</button>';
        }).join('');

        Array.prototype.forEach.call(usersEl.querySelectorAll('.bc-user-item'), function (btn) {
            btn.addEventListener('click', function () {
                var id = parseInt(btn.getAttribute('data-user-id'), 10);
                var user = users.filter(function (item) { return item.id === id; })[0];
                selectPeer(user);
            });
        });
    }

    function loadUsers() {
        var q = encodeURIComponent(searchEl.value || '');
        fetchJson(api + '?action=users&q=' + q)
            .then(function (data) {
                if (!data.ok) throw new Error(data.message || 'Không tải được user.');
                renderUsers(data.users || []);
            })
            .catch(function (err) {
                usersEl.innerHTML = '<div class="bc-chat-error">' + escapeHtml(err.message) + '</div>';
            });
    }

    function selectPeer(user) {
        if (!user) return;
        selectedPeerId = user.id;
        selectedPeer = user;
        lastMessageId = 0;
        lastGroupTs = 0;
        lastGroupDay = '';
        receiverEl.value = selectedPeerId;
        inputEl.disabled = false;
        fileEl.disabled = false;
        peerNameEl.textContent = user.name;
        peerDeptEl.textContent = user.online ? 'Đang trực tuyến' : 'Offline: ' + user.last_seen;
        peerAvatarEl.className = 'bc-avatar bc-avatar-lg' + (user.avatar ? ' has-image' : '');
        peerAvatarEl.innerHTML = user.avatar ? '<img src="' + escapeHtml(user.avatar) + '" alt="' + escapeHtml(user.name) + '">' : escapeHtml(user.initials);
        inputEl.focus();
        messagesEl.innerHTML = '<div class="bc-chat-empty">Đang tải tin nhắn...</div>';
        loadMessages(true);
        loadUsers();
    }

    function renderMessage(message) {
        var mine = message.mine ? ' mine' : '';
        var title = escapeHtml(message.hover_time || message.created_at || message.time_ago || '');
        var textHtml = message.text ? '<div class="bc-bubble" title="' + title + '">' + escapeHtml(message.text) + renderAttachment(message) + '</div>' : '<div class="bc-bubble" title="' + title + '">' + renderAttachment(message) + '</div>';
        var avatar = message.mine ? '' : renderAvatar(selectedPeer, '');
        return '<div class="bc-message-row' + mine + '" data-message-id="' + message.id + '">' +
            avatar +
            '<div class="bc-message-body">' +
                textHtml +
            '</div>' +
        '</div>';
    }

    function maybeRenderTimeDivider(message) {
        var ts = parseInt(message.created_ts || 0, 10);
        if (!ts) return '';
        var day = new Date(ts * 1000).toDateString();
        var shouldShow = !lastGroupTs || day !== lastGroupDay || (ts - lastGroupTs) > 900;
        if (!shouldShow) return '';
        lastGroupTs = ts;
        lastGroupDay = day;
        return '<div class="bc-time-divider">' + escapeHtml(message.group_time || message.time_ago || '') + '</div>';
    }

    function renderAttachment(message) {
        if (!message.attachment) return '';
        var file = message.attachment;
        return '<a class="bc-attachment" href="' + escapeHtml(file.url) + '" target="_blank" rel="noopener">' +
            '<span>▤</span>' +
            '<span><strong>' + escapeHtml(file.name) + '</strong><small>' + formatSize(file.size) + '</small></span>' +
        '</a>';
    }

    function formatSize(bytes) {
        bytes = parseInt(bytes || 0, 10);
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return Math.round(bytes / 1024) + ' KB';
        return (bytes / 1024 / 1024).toFixed(1) + ' MB';
    }

    function loadMessages(reset) {
        if (!selectedPeerId || loadingMessages) return;
        loadingMessages = true;
        var after = reset ? 0 : lastMessageId;
        fetchJson(api + '?action=messages&peer_id=' + selectedPeerId + '&after_id=' + after)
            .then(function (data) {
                if (!data.ok) throw new Error(data.message || 'Không tải được tin nhắn.');
                if (data.peer) {
                    peerNameEl.textContent = data.peer.name;
                    peerAvatarEl.className = 'bc-avatar bc-avatar-lg' + (data.peer.avatar ? ' has-image' : '');
                    peerAvatarEl.innerHTML = data.peer.avatar ? '<img src="' + escapeHtml(data.peer.avatar) + '" alt="' + escapeHtml(data.peer.name) + '">' : escapeHtml(data.peer.initials);
                    if (selectedPeer) peerDeptEl.textContent = selectedPeer.online ? 'Đang trực tuyến' : 'Offline: ' + selectedPeer.last_seen;
                }
                var messages = data.messages || [];
                if (reset) {
                    messagesEl.innerHTML = '';
                    lastGroupTs = 0;
                    lastGroupDay = '';
                }
                if (!messages.length && reset) {
                    messagesEl.innerHTML = '<div class="bc-chat-empty">Chưa có tin nhắn. Gửi lời chào đầu tiên nhé.</div>';
                    return;
                }
                if (messages.length && messagesEl.querySelector('.bc-chat-empty')) messagesEl.innerHTML = '';
                messages.forEach(function (message) {
                    if (!messagesEl.querySelector('[data-message-id="' + message.id + '"]')) {
                        messagesEl.insertAdjacentHTML('beforeend', maybeRenderTimeDivider(message));
                        messagesEl.insertAdjacentHTML('beforeend', renderMessage(message));
                    }
                    if (message.id > lastMessageId) lastMessageId = message.id;
                });
                if (messages.length) messagesEl.scrollTop = messagesEl.scrollHeight;
            })
            .catch(function (err) {
                if (reset) messagesEl.innerHTML = '<div class="bc-chat-error">' + escapeHtml(err.message) + '</div>';
            })
            .finally(function () {
                loadingMessages = false;
            });
    }

    function sendMessage(event) {
        event.preventDefault();
        if (!selectedPeerId) return;
        if (!inputEl.value.trim() && !fileEl.files.length) return;

        var data = new FormData(formEl);
        inputEl.disabled = true;
        fileEl.disabled = true;
        fetchJson(api + '?action=send', { method: 'POST', body: data })
            .then(function (resp) {
                if (!resp.ok) throw new Error(resp.message || 'Không gửi được tin nhắn.');
                inputEl.value = '';
                fileEl.value = '';
                fileNameEl.textContent = '';
                loadMessages(false);
                loadUsers();
            })
            .catch(function (err) {
                alert(err.message);
            })
            .finally(function () {
                inputEl.disabled = false;
                fileEl.disabled = false;
                inputEl.focus();
            });
    }

    function debounce(fn, delay) {
        var timer = null;
        return function () {
            clearTimeout(timer);
            timer = setTimeout(fn, delay);
        };
    }

    searchEl.addEventListener('input', debounce(loadUsers, 240));
    formEl.addEventListener('submit', sendMessage);
    inputEl.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            formEl.dispatchEvent(new Event('submit', { cancelable: true }));
        }
    });
    fileEl.addEventListener('change', function () {
        fileNameEl.textContent = fileEl.files.length ? fileEl.files[0].name : '';
        if (fileEl.files.length && selectedPeerId) {
            formEl.dispatchEvent(new Event('submit', { cancelable: true }));
        }
    });
    loadUsers();
    userTimer = setInterval(loadUsers, 5000);
    messageTimer = setInterval(function () { loadMessages(false); }, 2200);
    setInterval(function () { fetchJson(api + '?action=heartbeat').catch(function () {}); }, 30000);

    window.addEventListener('beforeunload', function () {
        clearInterval(userTimer);
        clearInterval(messageTimer);
    });
})();
