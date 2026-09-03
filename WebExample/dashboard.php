<?php
/**
 * ╔════════════════════════════════════════════════════════════════════════════╗
 * ║                    KYNEXAUTH PHP ANIMATED DASHBOARD                        ║
 * ║              Secured User Dashboard Interface with Micro-Animations        ║
 * ╚════════════════════════════════════════════════════════════════════════════╝
 */

session_start();
require_once __DIR__ . '/kynexauth.php';

// Authentication guard
if (empty($_SESSION['kynex_auth_user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['kynex_auth_user'];

// Handle Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['kynex_auth_user']);
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Security Dashboard · KynexAuth Cloud</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #07090E;
            --card-bg: rgba(14, 18, 28, 0.85);
            --card-border: rgba(45, 55, 75, 0.7);
            --primary: #6366F1;
            --cyan: #06B6D4;
            --green: #10B981;
            --gold: #F59E0B;
            --purple: #A855F7;
            --red: #EF4444;
            --text-main: #F8FAFC;
            --text-muted: #94A3B8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            background-image: 
                radial-gradient(circle at 10% 10%, rgba(99, 102, 241, 0.18) 0%, transparent 45%),
                radial-gradient(circle at 90% 90%, rgba(6, 182, 212, 0.15) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(168, 85, 247, 0.08) 0%, transparent 60%);
            padding: 40px 20px;
            overflow-x: hidden;
        }

        .dashboard-container {
            max-width: 960px;
            margin: 0 auto;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            padding: 18px 28px;
            border-radius: 18px;
            margin-bottom: 28px;
            backdrop-filter: blur(16px);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--cyan), var(--primary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.4);
            animation: pulseGlow 3s infinite alternate;
        }

        @keyframes pulseGlow {
            0% { box-shadow: 0 0 15px rgba(6, 182, 212, 0.4); }
            100% { box-shadow: 0 0 25px rgba(99, 102, 241, 0.8); }
        }

        .brand-pill {
            padding: 4px 10px;
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: var(--green);
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .live-dot {
            width: 6px;
            height: 6px;
            background: var(--green);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--green);
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(0.8); }
        }

        .logout-btn {
            padding: 10px 20px;
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #FCA5A5;
            border-radius: 12px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.25);
            color: #FFFFFF;
            border-color: rgba(239, 68, 68, 0.6);
            transform: translateY(-1px);
        }

        .hero-banner {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(6, 182, 212, 0.12) 50%, rgba(168, 85, 247, 0.15) 100%);
            border: 1px solid rgba(99, 102, 241, 0.4);
            border-radius: 22px;
            padding: 36px 32px;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.3), transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            animation: floatCircle 6s infinite ease-in-out alternate;
        }

        @keyframes floatCircle {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-30px, 30px); }
        }

        .hero-title {
            font-size: 32px;
            font-weight: 900;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #FFFFFF 0%, #E2E8F0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-sub {
            color: #CBD5E1;
            font-size: 15px;
            max-width: 600px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            padding: 24px;
            backdrop-filter: blur(14px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-4px);
            border-color: rgba(99, 102, 241, 0.6);
            box-shadow: 0 15px 30px -10px rgba(99, 102, 241, 0.2);
        }

        .card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .card-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .card-badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 700;
        }

        .card-value {
            font-size: 22px;
            font-weight: 800;
            color: #FFFFFF;
            font-family: 'JetBrains Mono', monospace;
            word-break: break-all;
        }

        .hwid-card {
            grid-column: 1 / -1;
            background: linear-gradient(180deg, rgba(14, 18, 28, 0.9) 0%, rgba(10, 13, 20, 0.95) 100%);
        }

        .hwid-text {
            font-size: 15px;
            color: #CBD5E1;
            background: rgba(0, 0, 0, 0.4);
            padding: 12px 16px;
            border-radius: 10px;
            border: 1px solid rgba(51, 65, 85, 0.6);
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .payload-box {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 32px;
            text-align: center;
            position: relative;
        }

        .payload-icon-wrapper {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px auto;
            border-radius: 16px;
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            animation: pulseGreen 2.5s infinite alternate;
        }

        @keyframes pulseGreen {
            0% { transform: scale(1); box-shadow: 0 0 10px rgba(16, 185, 129, 0.2); }
            100% { transform: scale(1.05); box-shadow: 0 0 25px rgba(16, 185, 129, 0.5); }
        }

        .payload-title {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .payload-desc {
            color: var(--text-muted);
            font-size: 14px;
            max-width: 640px;
            margin: 0 auto 20px auto;
            line-height: 1.6;
        }

        .action-grid {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .feature-chip {
            padding: 8px 16px;
            background: rgba(10, 13, 20, 0.6);
            border: 1px solid var(--card-border);
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            color: #CBD5E1;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .feature-chip span {
            color: var(--green);
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="navbar">
        <div class="brand">
            <div class="brand-icon">⚡</div>
            <span>KynexAuth</span>
            <div class="brand-pill">
                <div class="live-dot"></div>
                AUTHENTICATED
            </div>
        </div>
        <a href="?logout=1" class="logout-btn">
            <span>Log Out</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        </a>
    </div>

    <div class="hero-banner">
        <h1 class="hero-title">Welcome back, <?= htmlspecialchars($user['username']) ?>!</h1>
        <p class="hero-sub">Session handshake authenticated · Multi-point hardware lock active.</p>
    </div>

    <div class="grid">
        <!-- Account Card -->
        <div class="card">
            <div class="card-top">
                <span class="card-label">Verified Account</span>
                <span class="card-badge" style="background: rgba(99, 102, 241, 0.15); color: var(--cyan); border: 1px solid rgba(99, 102, 241, 0.3);">LEVEL 99</span>
            </div>
            <div class="card-value" style="color: var(--cyan);"><?= htmlspecialchars($user['username']) ?></div>
        </div>

        <!-- Node IP Card -->
        <div class="card">
            <div class="card-top">
                <span class="card-label">Public IPv4 Node</span>
                <span class="card-badge" style="background: rgba(245, 158, 11, 0.15); color: var(--gold); border: 1px solid rgba(245, 158, 11, 0.3);">SECURE NODE</span>
            </div>
            <div class="card-value" style="color: var(--gold);"><?= htmlspecialchars($user['ip'] ?: '127.0.0.1') ?></div>
        </div>

        <!-- Subscription Tier Card -->
        <div class="card">
            <div class="card-top">
                <span class="card-label">Active Subscription</span>
                <span class="card-badge" style="background: rgba(16, 185, 129, 0.15); color: var(--green); border: 1px solid rgba(16, 185, 129, 0.3);">VIP ACCESS</span>
            </div>
            <div class="card-value" style="color: var(--green); font-size: 19px;">
                <?php 
                    if (!empty($user['subscriptions']) && is_array($user['subscriptions'])) {
                        echo htmlspecialchars($user['subscriptions'][0]->name ?? 'Lifetime Plan');
                    } else {
                        echo "Lifetime VIP";
                    }
                ?>
            </div>
        </div>

        <!-- HWID Hardware Card -->
        <div class="card hwid-card">
            <div class="card-top">
                <span class="card-label">Locked Cryptographic Hardware SID</span>
                <span class="card-badge" style="background: rgba(168, 85, 247, 0.15); color: var(--purple); border: 1px solid rgba(168, 85, 247, 0.3);">ANTI-SHARE LOCKED</span>
            </div>
            <div class="hwid-text">
                <span><?= htmlspecialchars($user['hwid'] ?: 'Not bound yet') ?></span>
                <span style="color: var(--green); font-size: 12px; font-weight: 700;">● BOUND TO MACHINE</span>
            </div>
        </div>
    </div>

    <!-- Application Payload Section -->
    <div class="payload-box">
        <div class="payload-icon-wrapper">🛡️</div>
        <h2 class="payload-title">Protected Web Application Core</h2>
        <p class="payload-desc">
            Your web application logic, premium customer tools, and licensed assets can now be embedded securely here. Any unauthorized attempts to access this page without a valid KynexAuth session token will be immediately terminated.
        </p>
        <div class="action-grid">
            <div class="feature-chip"><span>✓</span> Multi-Point HWID Lock</div>
            <div class="feature-chip"><span>✓</span> Anti-Cracking Token Protection</div>
            <div class="feature-chip"><span>✓</span> Live Expiry Watchdog</div>
        </div>
    </div>
</div>

</body>
</html>
