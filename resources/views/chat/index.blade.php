<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Real Time Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; box-sizing: border-box; }
        body {
            background: #0a0e1a;
            height: 100vh;
            overflow: hidden;
            display: flex;
        }
 
        /* Sidebar */
        .sidebar {
            width: 260px;
            min-width: 260px;
            background: rgba(12, 17, 30, 0.95);
            border-right: 1px solid rgba(255,255,255,0.05);
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        .sidebar-header {
            padding: 20px 18px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .logo-icon {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, #2979ff, #00d4aa);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .room-item {
            padding: 10px 18px;
            cursor: pointer;
            transition: background 0.15s;
            border-radius: 0;
        }
        .room-item:hover { background: rgba(255,255,255,0.04); }
        .room-item.active { background: rgba(41, 121, 255, 0.1); }
        .room-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #2979ff;
            flex-shrink: 0;
        }
        .user-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 18px;
        }
        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            flex-shrink: 0;
        }
        .online-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #00d4aa;
            flex-shrink: 0;
        }
 
        /* Main chat area */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background: #0d1120;
        }
        .chat-header {
            padding: 16px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            background: rgba(12, 17, 30, 0.8);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .messages-container::-webkit-scrollbar { width: 4px; }
        .messages-container::-webkit-scrollbar-track { background: transparent; }
        .messages-container::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 2px; }
 
        .message-group {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
        }
        .message-group.own {
            flex-direction: row-reverse;
        }
        .msg-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            flex-shrink: 0;
            align-self: flex-end;
        }
        .msg-content { max-width: 65%; }
        .msg-name {
            font-size: 11px;
            color: rgba(255,255,255,0.35);
            margin-bottom: 4px;
            padding-left: 2px;
        }
        .message-group.own .msg-name { text-align: right; padding-right: 2px; }
        .bubble {
            padding: 10px 14px;
            border-radius: 16px;
            font-size: 14px;
            line-height: 1.5;
            word-break: break-word;
        }
        .bubble.other {
            background: rgba(255,255,255,0.07);
            color: #e0e4f0;
            border-bottom-left-radius: 4px;
        }
        .bubble.own {
            background: linear-gradient(135deg, #2979ff, #1a56e8);
            color: #fff;
            border-bottom-right-radius: 4px;
        }
        .msg-time {
            font-size: 10px;
            color: rgba(255,255,255,0.2);
            margin-top: 3px;
            padding: 0 2px;
        }
        .message-group.own .msg-time { text-align: right; }
 
        /* Input area */
        .input-area {
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,0.05);
            background: rgba(12, 17, 30, 0.8);
        }
        .input-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 4px 4px 4px 16px;
            transition: border-color 0.2s;
        }
        .input-wrapper:focus-within {
            border-color: rgba(41, 121, 255, 0.4);
        }
        .msg-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: #e0e4f0;
            font-size: 14px;
            padding: 10px 0;
            resize: none;
            font-family: 'DM Sans', sans-serif;
        }
        .msg-input::placeholder { color: rgba(255,255,255,0.2); }
        .send-btn {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #2979ff, #1a56e8);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .send-btn:hover { transform: scale(1.05); box-shadow: 0 4px 16px rgba(41,121,255,0.4); }
        .send-btn:active { transform: scale(0.97); }
 
        /* System message */
        .system-msg {
            text-align: center;
            font-size: 11px;
            color: rgba(255,255,255,0.2);
            padding: 8px 0;
            font-style: italic;
        }
 
        /* Date divider */
        .date-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 12px 0;
        }
        .date-divider::before, .date-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.06);
        }
        .date-divider span {
            font-size: 11px;
            color: rgba(255,255,255,0.2);
            white-space: nowrap;
        }
 
        /* Logout btn */
        .logout-btn {
            background: rgba(255,80,80,0.08);
            border: 1px solid rgba(255,80,80,0.15);
            color: rgba(255,100,100,0.7);
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .logout-btn:hover {
            background: rgba(255,80,80,0.15);
            color: #ff6464;
        }
 
        .fade-in { animation: fadeIn 0.3s ease; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
 
        /* Typing indicator */
        .typing-indicator {
            display: flex;
            gap: 4px;
            align-items: center;
            padding: 8px 14px;
            background: rgba(255,255,255,0.05);
            border-radius: 16px;
            border-bottom-left-radius: 4px;
            width: fit-content;
        }
        .typing-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            animation: typingBounce 1.2s infinite;
        }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typingBounce {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.3; }
            30% { transform: translateY(-4px); opacity: 1; }
        }
</style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="flex items-center gap-2.5">
                <div class="logo-icon">
                    
                </div>
                <span style="font-family:'DM Mono',monospace;color:#fff;font-size:15px;font-weight:500;letter-spacing:-0.3px">
                    Laravel Real Time Chat App 
                </span>
            </div>
        </div>
 
        <!-- Rooms -->
        <div class="py-3">
            <div class="px-5 mb-2">
                <span class="text-xs font-medium tracking-widest uppercase" style="color:rgba(255,255,255,0.2)">Rooms</span>
            </div>
            <div class="room-item active flex items-center gap-3">
                <div class="room-dot"></div>
                <span class="text-sm" style="color:#fff"># general</span>
            </div>
        </div>
 
        <div style="height:1px;background:rgba(255,255,255,0.04);margin:0 18px"></div>
 
        <!-- Online users -->
        <div class="py-3 flex-1">
            <div class="px-5 mb-2">
                <span class="text-xs font-medium tracking-widest uppercase" style="color:rgba(255,255,255,0.2)">Online — <span id="onlineCount">1</span></span>
            </div>
            <div id="onlineUsers"></div>
        </div>
 
        <!-- Current user -->
        <div style="border-top:1px solid rgba(255,255,255,0.05);padding:14px 18px;">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="avatar" id="myAvatarSidebar" style="background:linear-gradient(135deg,#2979ff,#00d4aa);color:#fff;width:32px;height:32px;font-size:13px;"></div>
                    <div>
                        <div class="text-sm font-medium" style="color:#fff" id="myNameSidebar"></div>
                        <div class="text-xs" style="color:rgba(255,255,255,0.3)">You</div>
                    </div>
                </div>
                <button class="logout-btn" onclick="logout()">Leave</button>
            </div>
        </div>
    </div>
 
    <!-- Chat Area -->
    <div class="chat-area">
        <div class="chat-header">
            <div class="flex items-center gap-3">
                <div>
                    <div class="font-medium text-sm" style="color:#fff"># general</div>
                    <div class="text-xs" style="color:rgba(255,255,255,0.3)">Group chat</div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="online-dot"></div>
                <span class="text-xs" style="color:rgba(255,255,255,0.3)" id="headerOnlineCount">1 online</span>
            </div>
        </div>
 
        <div class="messages-container" id="messagesContainer">
            <div class="date-divider"><span>Today</span></div>
            <div class="system-msg">Welcome to #general — start chatting!</div>
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
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M22 2L11 13M22 2L15 22L11 13M22 2L2 9L11 13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</html>