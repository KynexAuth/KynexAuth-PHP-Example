<?php
/**
 * ╔════════════════════════════════════════════════════════════════════════════╗
 * ║                      KYNEXAUTH PHP WEB LOGIN PORTAL                        ║
 * ║              Modern Dark Glassmorphism Authentication Interface            ║
 * ╚════════════════════════════════════════════════════════════════════════════╝
 */

session_start();
require_once __DIR__ . '/kynexauth.php';

// If already logged in, redirect to dashboard
if (!empty($_SESSION['kynex_auth_user'])) {
    header('Location: dashboard.php');
    exit;
}

// -------------------------------------------------------------
// CONFIGURE YOUR APPLICATION CREDENTIALS
// -------------------------------------------------------------
$KynexAuthApp = new \KynexAuth\Api(
    "YOUR_APP_NAME",       // Application Name from dashboard
    "YOUR_APP_KEY",        // Application Key (Owner ID)
    "1.0",                 // Application Version
    "https://kynexauth.com/api/v1/client" // API Endpoint
);

$errorMsg = '';
$successMsg = '';
$activeTab = 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'login';
    $activeTab = $action;

    // Step 1: Initialize API Session
    if (!$KynexAuthApp->init()) {
        $errorMsg = "Initialization Failed: " . $KynexAuthApp->response->message;
    } else {
        // Step 2: Handle Actions
        if ($action === 'login') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                $errorMsg = "Please enter both username and password.";
            } elseif ($KynexAuthApp->login($username, $password)) {
                $_SESSION['kynex_auth_user'] = [
                    'username'      => $KynexAuthApp->user_data->username,
                    'ip'            => $KynexAuthApp->user_data->ip,
                    'hwid'          => $KynexAuthApp->user_data->hwid,
                    'createdate'    => $KynexAuthApp->user_data->createdate,
                    'lastlogin'     => $KynexAuthApp->user_data->lastlogin,
                    'subscriptions' => $KynexAuthApp->user_data->subscriptions,
                    'sessionToken'  => $KynexAuthApp->sessionid,
                ];
                header('Location: dashboard.php');
                exit;
            } else {
                $errorMsg = $KynexAuthApp->response->message;
            }
        } elseif ($action === 'register') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $key      = trim($_POST['license'] ?? '');
            $email    = trim($_POST['email'] ?? '');

            if (empty($username) || empty($password) || empty($key)) {
                $errorMsg = "Username, password, and license key are required.";
            } elseif ($KynexAuthApp->register($username, $password, $key, $email)) {
                $successMsg = "Registration successful! You can now log in.";
                $activeTab = 'login';
            } else {
                $errorMsg = $KynexAuthApp->response->message;
            }
        } elseif ($action === 'license') {
            $key = trim($_POST['license'] ?? '');

            if (empty($key)) {
                $errorMsg = "Please enter your license key.";
            } elseif ($KynexAuthApp->license($key)) {
                $_SESSION['kynex_auth_user'] = [
                    'username'      => $KynexAuthApp->user_data->username,
                    'ip'            => $KynexAuthApp->user_data->ip,
                    'hwid'          => $KynexAuthApp->user_data->hwid,
                    'createdate'    => $KynexAuthApp->user_data->createdate,
                    'lastlogin'     => $KynexAuthApp->user_data->lastlogin,
                    'subscriptions' => $KynexAuthApp->user_data->subscriptions,
                    'sessionToken'  => $KynexAuthApp->sessionid,
                ];
                header('Location: dashboard.php');
                exit;
            } else {
                $errorMsg = $KynexAuthApp->response->message;
            }
        } elseif ($action === 'upgrade') {
            $username = trim($_POST['username'] ?? '');
            $key      = trim($_POST['license'] ?? '');

            if (empty($username) || empty($key)) {
                $errorMsg = "Please enter both username and renewal license key.";
            } elseif ($KynexAuthApp->upgrade($username, $key)) {
                $successMsg = "Subscription upgraded successfully! Please log in.";
                $activeTab = 'login';
            } else {
                $errorMsg = $KynexAuthApp->response->message;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KynexAuth · Web Security Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #07090E;
            --card-bg: rgba(14, 18, 28, 0.75);
            --card-border: rgba(45, 55, 75, 0.6);
            --primary: #6366F1;
            --primary-hover: #4F46E5;
            --primary-glow: rgba(99, 102, 241, 0.25);
            --cyan: #06B6D4;
            --green: #10B981;
            --red: #EF4444;
            --text-main: #F8FAFC;
            --text-muted: #94A3B8;
            --input-bg: rgba(10, 13, 20, 0.8);
            --input-border: rgba(51, 65, 85, 0.8);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(99, 102, 241, 0.14) 0%, transparent 40%),
                radial-gradient(circle at 80% 80%, rgba(6, 182, 212, 0.12) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(16, 185, 129, 0.05) 0%, transparent 60%);
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 480px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 40px rgba(99, 102, 241, 0.08);
            position: relative;
            overflow: hidden;
        }

        .auth-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan), var(--primary), transparent);
        }

        .header {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.25);
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            color: var(--cyan);
            margin-bottom: 14px;
            letter-spacing: 0.5px;
        }

        .logo-badge span {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 10px var(--green);
        }

        .title {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #FFFFFF 0%, #CBD5E1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 400;
        }

        .tabs-nav {
            display: flex;
            background: rgba(10, 13, 20, 0.7);
            border: 1px solid rgba(51, 65, 85, 0.5);
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 24px;
            gap: 4px;
        }

        .tab-btn {
            flex: 1;
            padding: 10px 8px;
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }

        .tab-btn.active {
            background: var(--primary);
            color: #FFFFFF;
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        .tab-btn:hover:not(.active) {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.04);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #CBD5E1;
            margin-bottom: 7px;
            letter-spacing: 0.3px;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 10px;
            color: var(--text-main);
            font-size: 14px;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
            background: rgba(14, 18, 28, 0.95);
        }

        .form-input::placeholder {
            color: #475569;
        }

        .submit-btn {
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
            border: none;
            border-radius: 10px;
            color: #FFFFFF;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px var(--primary-glow);
            margin-top: 6px;
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #FCA5A5;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6EE7B7;
        }

        .footer {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #64748B;
            font-family: 'JetBrains Mono', monospace;
        }

        .footer a {
            color: var(--cyan);
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="header">
        <div class="logo-badge">
            <span></span> KYNEXAUTH SECURE
        </div>
        <h1 class="title">Authentication Cloud</h1>
        <p class="subtitle">Enter your credentials or license key to continue</p>
    </div>

    <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-error">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            <div><?= htmlspecialchars($errorMsg) ?></div>
        </div>
    <?php endif; ?>

    <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="9 11 12 14 22 4"></polyline></svg>
            <div><?= htmlspecialchars($successMsg) ?></div>
        </div>
    <?php endif; ?>

    <div class="tabs-nav">
        <button type="button" class="tab-btn <?= $activeTab === 'login' ? 'active' : '' ?>" onclick="switchTab('login')">Login</button>
        <button type="button" class="tab-btn <?= $activeTab === 'register' ? 'active' : '' ?>" onclick="switchTab('register')">Register</button>
        <button type="button" class="tab-btn <?= $activeTab === 'license' ? 'active' : '' ?>" onclick="switchTab('license')">License Key</button>
        <button type="button" class="tab-btn <?= $activeTab === 'upgrade' ? 'active' : '' ?>" onclick="switchTab('upgrade')">Upgrade</button>
    </div>

    <!-- 1. LOGIN TAB -->
    <div id="tab-login" class="tab-content <?= $activeTab === 'login' ? 'active' : '' ?>">
        <form method="POST" action="">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-input" placeholder="Enter your username" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••••••" required>
            </div>
            <button type="submit" class="submit-btn">Authorize Session →</button>
        </form>
    </div>

    <!-- 2. REGISTER TAB -->
    <div id="tab-register" class="tab-content <?= $activeTab === 'register' ? 'active' : '' ?>">
        <form method="POST" action="">
            <input type="hidden" name="action" value="register">
            <div class="form-group">
                <label class="form-label">Desired Username</label>
                <input type="text" name="username" class="form-input" placeholder="Choose a username" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Choose a strong password" required>
            </div>
            <div class="form-group">
                <label class="form-label">KynexAuth License Key</label>
                <input type="text" name="license" class="form-input" placeholder="XXXX-XXXX-XXXX-XXXX" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address (Optional)</label>
                <input type="email" name="email" class="form-input" placeholder="name@example.com">
            </div>
            <button type="submit" class="submit-btn">Create & Activate Account →</button>
        </form>
    </div>

    <!-- 3. LICENSE KEY ONLY TAB -->
    <div id="tab-license" class="tab-content <?= $activeTab === 'license' ? 'active' : '' ?>">
        <form method="POST" action="">
            <input type="hidden" name="action" value="license">
            <div class="form-group">
                <label class="form-label">License Key</label>
                <input type="text" name="license" class="form-input" placeholder="Paste your instant access license key" required>
            </div>
            <button type="submit" class="submit-btn">Unlock with License Key →</button>
        </form>
    </div>

    <!-- 4. UPGRADE TAB -->
    <div id="tab-upgrade" class="tab-content <?= $activeTab === 'upgrade' ? 'active' : '' ?>">
        <form method="POST" action="">
            <input type="hidden" name="action" value="upgrade">
            <div class="form-group">
                <label class="form-label">Existing Username</label>
                <input type="text" name="username" class="form-input" placeholder="Enter your username to extend" required>
            </div>
            <div class="form-group">
                <label class="form-label">Renewal License Key</label>
                <input type="text" name="license" class="form-input" placeholder="XXXX-XXXX-XXXX-XXXX" required>
            </div>
            <button type="submit" class="submit-btn">Extend Subscription Duration →</button>
        </form>
    </div>

    <div class="footer">
        Protected by <a href="https://kynexauth.com" target="_blank">KynexAuth Engine v2.0</a>
    </div>
</div>

<script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

        const targetBtn = Array.from(document.querySelectorAll('.tab-btn')).find(b => b.textContent.toLowerCase().includes(tabId));
        if (targetBtn) targetBtn.classList.add('active');

        const targetContent = document.getElementById('tab-' + tabId);
        if (targetContent) targetContent.classList.add('active');
    }
</script>

</body>
</html>
