<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Real Time Chat — Join</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body {
            background: #0f0f0f;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .bg-noise {
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 15% 50%, rgba(255, 45, 32, 0.07) 0%, transparent 65%),
                radial-gradient(ellipse 50% 70% at 85% 20%, rgba(255, 45, 32, 0.04) 0%, transparent 60%);
            pointer-events: none;
        }
        .grid-bg {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }
        .card {
            background: #161616;
            border: 1px solid #2a2a2a;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.03), 0 32px 64px rgba(0,0,0,0.6);
        }
        .input-field {
            background: #0f0f0f;
            border: 1px solid #2a2a2a;
            color: #e5e7eb;
            font-size: 16px;
            transition: all 0.2s;
            outline: none;
            width: 100%;
            border-radius: 8px;
            padding: 14px 16px;
        }
        .input-field:focus {
            border-color: #ff2d20;
            box-shadow: 0 0 0 3px rgba(255, 45, 32, 0.1);
        }
        .input-field::placeholder { color: #4b5563; }
        .btn-primary {
            background: #ff2d20;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 14px;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background: #e82516;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(255, 45, 32, 0.35);
        }
        .btn-primary:active { transform: translateY(0); }
        .fade-in { animation: fadeIn 0.45s ease forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .logo-text { font-family: 'Fira Code', monospace; font-size: 22px; font-weight: 500; color: #fff; }
        .logo-text span { color: #ff2d20; }
        .badge {
            background: rgba(255,45,32,0.1);
            border: 1px solid rgba(255,45,32,0.2);
            color: #ff2d20;
            font-size: 12px;
            padding: 3px 10px;
            border-radius: 999px;
            font-family: 'Fira Code', monospace;
        }
        .divider { height: 1px; background: #2a2a2a; }
    </style>
</head>
<body>
    <div class="bg-noise"></div>
    <div class="grid-bg"></div>

    <div class="card rounded-2xl fade-in relative z-10" style="width:100%;max-width:420px;margin:16px;padding:40px;">

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:36px;">
            <div>
                <div class="logo-text">Laravel Real Time Chat App</div>
                <div style="color:#4b5563;font-size:14px;margin-top:4px;">Real-time group messaging</div>
            </div>
            <div class="badge">Reverb</div>
        </div>

        <form method="POST" action="{{ route('chat.enter') }}">
            @csrf
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:15px;font-weight:500;color:#9ca3af;margin-bottom:8px;">Your display name</label>
                <input name="username" type="text" placeholder="e.g. Husan" class="input-field" autofocus required>
            </div>
            <button type="submit" class="btn-primary">Join Chat →</button>
        </form>

        <div class="divider" style="margin:28px 0;"></div>
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="color:#4b5563;font-size:14px;">Powered by Laravel Reverb</span>
            <span style="color:#374151;font-size:13px;font-family:'Fira Code',monospace;">ws://</span>
        </div>
    </div>
</body>
</html>