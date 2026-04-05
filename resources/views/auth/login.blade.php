<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Real Time Chat App — Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; }
        body {
            background: #0a0e1a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .bg-mesh {
            position: fixed;
            inset: 0;
            background: 
                radial-gradient(ellipse 80% 60% at 20% 50%, rgba(41, 121, 255, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 80% 20%, rgba(0, 212, 170, 0.06) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 60% 80%, rgba(120, 80, 255, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }
        .grid-bg {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }
        .card {
            background: rgba(15, 20, 35, 0.85);
            border: 1px solid rgba(255,255,255,0.07);
            backdrop-filter: blur(20px);
            box-shadow: 0 40px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.03);
        }
        .input-field {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            color: #e8eaf0;
            transition: all 0.2s ease;
            outline: none;
        }
        .input-field:focus {
            border-color: rgba(41, 121, 255, 0.5);
            background: rgba(41, 121, 255, 0.05);
            box-shadow: 0 0 0 3px rgba(41, 121, 255, 0.08);
        }
        .input-field::placeholder { color: rgba(255,255,255,0.2); }
        .btn-primary {
            background: linear-gradient(135deg, #2979ff 0%, #1a56e8 100%);
            transition: all 0.2s ease;
            box-shadow: 0 4px 20px rgba(41, 121, 255, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(41, 121, 255, 0.45);
        }
        .btn-primary:active { transform: translateY(0); }
        .tab-btn {
            transition: all 0.2s ease;
            color: rgba(255,255,255,0.35);
        }
        .tab-btn.active {
            color: #fff;
            border-bottom: 2px solid #2979ff;
        }
        .logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #2979ff, #00d4aa);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .fade-in {
            animation: fadeIn 0.4s ease forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
        }
        .error-msg {
            color: #ff6b6b;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }
        .success-msg {
            background: rgba(0, 212, 170, 0.08);
            border: 1px solid rgba(0, 212, 170, 0.2);
            color: #00d4aa;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="bg-mesh"></div>
    <div class="grid-bg"></div>

    <div class="card rounded-2xl w-full max-w-sm mx-4 p-8 fade-in relative z-10">
        <!-- Logo -->
        <div class="flex items-center gap-3 mb-8">
            <div class="logo-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" fill="white" opacity="0.9"/>
                </svg>
            </div>
            <div>
                <div style="font-family:'DM Mono',monospace" class="text-white font-medium text-lg tracking-tight">Nexus</div>
                <div class="text-xs" style="color:rgba(255,255,255,0.3)">Real-time chat</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex gap-6 mb-6 border-b border-white/5 pb-0">
            <button class="tab-btn active pb-3 text-sm font-medium" onclick="switchTab('login')">Sign in</button>
            <button class="tab-btn pb-3 text-sm font-medium" onclick="switchTab('signup')">Create account</button>
        </div>

        <!-- Success message -->
        <div class="success-msg mb-4" id="successMsg">Account created! You can now sign in.</div>

        <!-- Login Form -->
        <form class="space-y-4" method="post" action="{{ route('authenticate') }}">
            <div>
                <label class="block text-xs mb-1.5" style="color:rgba(255,255,255,0.4)">Email</label>
                <input name="email" type="email" id="loginEmail" placeholder="yourname@example.com" 
                    class="input-field w-full rounded-xl px-4 py-3 text-sm">
                <div class="error-msg" id="loginEmailError">Enter a valid email</div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label class="block text-xs" style="color:rgba(255,255,255,0.4)">Password</label>
                    <a href="#" class="text-xs" style="color:#2979ff">Forgot?</a>
                </div>
                <input name="password" type="password" placeholder="••••••••" 
                    class="input-field w-full rounded-xl px-4 py-3 text-sm">
                <div class="error-msg">Invalid email or password</div>
            </div>
            <button type="submit" class="btn-primary w-full rounded-xl py-3 text-sm font-medium text-white mt-2">
                Sign in
            </button>
        </form>

        <!-- Signup Form -->
        <form id="signupForm" class="space-y-4 hidden" onsubmit="handleSignup(event)">
            <div>
                <label class="block text-xs mb-1.5" style="color:rgba(255,255,255,0.4)">Full name</label>
                <input type="text" id="signupName" placeholder="John Doe" 
                    class="input-field w-full rounded-xl px-4 py-3 text-sm">
                <div class="error-msg" id="signupNameError">Name is required</div>
            </div>
            <div>
                <label class="block text-xs mb-1.5" style="color:rgba(255,255,255,0.4)">Email</label>
                <input type="email" id="signupEmail" placeholder="you@example.com" 
                    class="input-field w-full rounded-xl px-4 py-3 text-sm">
                <div class="error-msg" id="signupEmailError">Enter a valid email</div>
            </div>
            <div>
                <label class="block text-xs mb-1.5" style="color:rgba(255,255,255,0.4)">Password</label>
                <input type="password" id="signupPassword" placeholder="Min. 6 characters" 
                    class="input-field w-full rounded-xl px-4 py-3 text-sm">
                <div class="error-msg" id="signupPasswordError">Password must be at least 6 characters</div>
            </div>
            <button type="submit" class="btn-primary w-full rounded-xl py-3 text-sm font-medium text-white mt-2">
                Create account
            </button>
        </form>

        <div class="divider mt-6 mb-4"></div>
        <p class="text-center text-xs" style="color:rgba(255,255,255,0.2)">Up to 5 users · End-to-end encrypted</p>
    </div>

</body>
</html>