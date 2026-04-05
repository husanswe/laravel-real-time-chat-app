<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Real Time Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { 
            font-family: 'Inter', sans-serif; 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
        }
 
        body {
            background: #0f0f0f;
            height: 100vh;
            overflow: hidden;
            display: flex;
        }
 
        /* ── Sidebar ── */
        .sidebar {
            width: 280px;
            min-width: 280px;
            background: #111111;
            border-right: 1px solid #1f1f1f;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        .sidebar-header {
            padding: 22px 20px 18px;
            border-bottom: 1px solid #1f1f1f;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo-text {
            font-family: 'Fira Code', monospace;
            font-size: 17px;
            font-weight: 500;
            color: #fff;
        }
        .logo-text span { color: #ff2d20; }
 
        .section-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #374151;
            padding: 16px 20px 8px;
        }
 
        .room-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            cursor: pointer;
            transition: background 0.15s;
            font-size: 15px;
            color: #6b7280;
        }
        .room-item:hover { background: rgba(255,255,255,0.03); color: #d1d5db; }
        .room-item.active { background: rgba(255,45,32,0.08); color: #fff; }
        .room-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #ff2d20;
            flex-shrink: 0;
        }
 
        .online-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 7px 20px;
        }
        .u-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: #ff2d20;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 600; color: #fff;
            flex-shrink: 0;
        }
        .u-name { font-size: 14px; color: #9ca3af; }
        .online-pip {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #22c55e;
            margin-left: auto;
            flex-shrink: 0;
        }
 
        /* Current user footer */
        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid #1f1f1f;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .leave-btn {
            background: rgba(255,45,32,0.08);
            border: 1px solid rgba(255,45,32,0.18);
            color: #ff2d20;
            font-size: 13px;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .leave-btn:hover { background: rgba(255,45,32,0.15); }
 
        /* ── Chat area ── */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background: #0f0f0f;
        }
 
        .chat-header {
            padding: 18px 28px;
            border-bottom: 1px solid #1f1f1f;
            background: #111111;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .chat-header-title {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
        }
        .chat-header-sub {
            font-size: 13px;
            color: #4b5563;
            margin-top: 2px;
        }
        .online-badge {
            display: flex;
            align-items: center;
            gap: 7px;
            background: rgba(34,197,94,0.07);
            border: 1px solid rgba(34,197,94,0.15);
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 13px;
            color: #4ade80;
        }
        .green-dot { width: 7px; height: 7px; border-radius: 50%; background: #22c55e; }
 
        /* Messages */
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        .messages-container::-webkit-scrollbar { width: 4px; }
        .messages-container::-webkit-scrollbar-thumb { background: #1f1f1f; border-radius: 2px; }
 
        .date-divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 16px 0;
        }
        .date-divider::before, .date-divider::after {
            content: ''; flex: 1; height: 1px; background: #1f1f1f;
        }
        .date-divider span { font-size: 12px; color: #374151; white-space: nowrap; font-family: 'Fira Code', monospace; }
 
        .system-msg {
            text-align: center;
            font-size: 13px;
            color: #374151;
            padding: 6px 0 16px;
            font-style: italic;
        }
 
        .message-group {
            display: flex;
            gap: 12px;
            margin-bottom: 14px;
        }
        .message-group.own { flex-direction: row-reverse; }
 
        .msg-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 600; color: #fff;
            flex-shrink: 0;
            align-self: flex-end;
        }
        .msg-content { max-width: 62%; }
        .msg-name {
            font-size: 12px;
            color: #4b5563;
            margin-bottom: 5px;
            padding-left: 3px;
        }
        .message-group.own .msg-name { text-align: right; padding-right: 3px; }
 
        .bubble {
            padding: 11px 16px;
            border-radius: 16px;
            font-size: 15px;
            line-height: 1.55;
            word-break: break-word;
        }
        .bubble.other {
            background: #1a1a1a;
            color: #d1d5db;
            border: 1px solid #2a2a2a;
            border-bottom-left-radius: 4px;
        }
        .bubble.own {
            background: #ff2d20;
            color: #fff;
            border-bottom-right-radius: 4px;
        }
        .msg-time {
            font-size: 11px;
            color: #374151;
            margin-top: 4px;
            padding: 0 3px;
            font-family: 'Fira Code', monospace;
        }
        .message-group.own .msg-time { text-align: right; }
 
        /* Input */
        .input-area {
            padding: 18px 28px;
            border-top: 1px solid #1f1f1f;
            background: #111111;
        }
        .input-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #0f0f0f;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 5px 5px 5px 18px;
            transition: border-color 0.2s;
        }
        .input-wrapper:focus-within { border-color: #ff2d20; box-shadow: 0 0 0 3px rgba(255,45,32,0.08); }
 
        .msg-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: #e5e7eb;
            font-size: 15px;
            padding: 10px 0;
            resize: none;
            font-family: 'Inter', sans-serif;
        }
        .msg-input::placeholder { color: #374151; }
 
        .send-btn {
            width: 40px; height: 40px;
            background: #ff2d20;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .send-btn:hover { background: #e82516; transform: scale(1.05); }
        .send-btn:active { transform: scale(0.97); }
 
        .fade-in { animation: fadeIn 0.25s ease; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
 
        .typing-indicator {
            display: flex; gap: 4px; align-items: center;
            padding: 9px 14px;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 16px;
            border-bottom-left-radius: 4px;
            width: fit-content;
        }
        .typing-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #4b5563;
            animation: typingBounce 1.2s infinite;
        }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typingBounce {
            0%,60%,100% { transform: translateY(0); opacity: 0.4; }
            30% { transform: translateY(-4px); opacity: 1; }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-text">Laravel Real Time Chat App</div>
        </div>
 
        <div class="section-label">Channels</div>
        <div class="room-item active">
            <div class="room-dot"></div>
            # general
        </div>
 
        <div class="section-label">Online — <span id="onlineCount">1</span></div>
        <div id="onlineUsers"></div>
 
        <div class="sidebar-footer">
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="u-avatar" id="myAvatar" style="background:#ff2d20;"></div>
                <div>
                    <div style="font-size:15px;font-weight:500;color:#fff;"> {{ session('username') }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('chat.leave') }}">
                @csrf
                <button type="submit" class="leave-btn">Leave</button>
            </form>
        </div>
    </div>
 
    <!-- Chat -->
    <div class="chat-area">
        <div class="chat-header">
            <div>
                <div class="chat-header-sub">Group chat</div>
            </div>
            <div class="online-badge">
                <div class="green-dot"></div>
                <span id="headerOnlineCount">1 online</span>
            </div>
        </div>
 
        <div class="messages-container" id="messagesContainer">
            <div class="date-divider"><span>Today</span></div>
        </div>
 
        <div class="input-area">
            <div class="input-wrapper">
                <textarea
                    class="msg-input"
                    id="messageInput"
                    placeholder="Message #general..."
                    rows="1"
                    onkeydown="handleKeydown(event)"
                    oninput="autoResize(this)"
                ></textarea>
                <button class="send-btn" onclick="sendMessage()">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                        <path d="M22 2L11 13M22 2L15 22L11 13M22 2L2 9L11 13" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    </div>
</body>
</html>