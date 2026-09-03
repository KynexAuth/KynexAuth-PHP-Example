<?php
/**
 * ╔════════════════════════════════════════════════════════════════════════════╗
 * ║                         KYNEXAUTH CLI MATRIX                               ║
 * ║                  Next-Gen Futuristic PHP Terminal Loader                   ║
 * ║                       Edition 2026 - Ultra Modern                          ║
 * ╚════════════════════════════════════════════════════════════════════════════╝
 */

require_once __DIR__ . '/kynexauth.php';

// ╔════════════════════════════════════════════════════════════════════════════╗
// ║                    NEXT-GEN NEON COLOR PALETTE                            ║
// ╚════════════════════════════════════════════════════════════════════════════╝
class Theme
{
    const RESET       = "\033[0m";
    const BOLD        = "\033[1m";
    const DIM         = "\033[2m";
    const ITALIC      = "\033[3m";

    // Neon Gradients & Accents
    const CYAN        = "\033[38;5;51m";   // Electric Cyan
    const TEAL        = "\033[38;5;44m";   // Deep Teal
    const PURPLE      = "\033[38;5;141m";  // Neon Violet
    const MAGENTA     = "\033[38;5;198m";  // Hot Pink
    const GOLD        = "\033[38;5;220m";  // Cyber Gold
    const GREEN       = "\033[38;5;48m";   // Matrix Green
    const RED         = "\033[38;5;203m";  // Coral Red
    const WHITE       = "\033[38;5;255m";  // Crisp White
    const SLATE       = "\033[38;5;244m";  // Slate Gray
    const DARK_BORDER = "\033[38;5;238m";  // Subtle Border
    const MID_BORDER  = "\033[38;5;241m";  // Active Border

    // Background Badges
    const BG_CARD     = "\033[48;5;235m";
    const BG_BADGE    = "\033[48;5;237m";
    const BG_ACTIVE   = "\033[48;5;24m";
}

// ╔════════════════════════════════════════════════════════════════════════════╗
// ║                         CONFIGURATION SECTION                             ║
// ║      (Copy and paste snippet directly from KynexAuth Dashboard)           ║
// ╚════════════════════════════════════════════════════════════════════════════╝
$KynexAuthApp = new \KynexAuth\Api(
    "YOUR_APP_NAME",       // Application Name from dashboard
    "YOUR_APP_KEY",        // Application Key (Owner ID)
    "1.0",                 // Application Version
    "https://kynexauth.com/api/v1/client" // API Endpoint
);
// ╔════════════════════════════════════════════════════════════════════════════╗
// ║                         TERMINAL UI HELPERS                                ║
// ╚════════════════════════════════════════════════════════════════════════════╝

function clearTerminal(): void
{
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        echo "\033c";
    } else {
        echo "\033[2J\033[3J\033[H";
    }
}

function setTerminalTitle(string $title): void
{
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        echo "\033]0;{$title}\007";
    }
}

function renderHeader(): void
{
    $date = date('Y.m.d');
    $time = date('H:i');

    echo "\n";
    echo "  " . Theme::DARK_BORDER . "╭────────────────────────────────────────────────────────────────────────╮" . Theme::RESET . "\n";
    echo "  " . Theme::DARK_BORDER . "│" . Theme::RESET . "  " . Theme::CYAN . Theme::BOLD . "⚡ KYNEXAUTH" . Theme::RESET . " " . Theme::SLATE . "· SECURITY & LICENSING CLOUD" . Theme::RESET . str_repeat(' ', 21) . Theme::PURPLE . "[v2.0]" . Theme::RESET . " " . Theme::DARK_BORDER . "│" . Theme::RESET . "\n";
    echo "  " . Theme::DARK_BORDER . "│" . Theme::RESET . "  " . Theme::SLATE . "Status: " . Theme::GREEN . "● Online" . Theme::RESET . "  " . Theme::SLATE . "· Node: " . Theme::WHITE . "Secure Cluster" . Theme::RESET . "  " . Theme::SLATE . "· Time: " . Theme::GOLD . "{$date} {$time}" . Theme::RESET . str_repeat(' ', 7) . Theme::DARK_BORDER . "│" . Theme::RESET . "\n";
    echo "  " . Theme::DARK_BORDER . "╰────────────────────────────────────────────────────────────────────────╯" . Theme::RESET . "\n";

    echo Theme::CYAN . Theme::BOLD;
    echo "     ██╗  ██╗██╗   ██╗███╗   ██╗███████╗██╗  ██╗ █████╗ ██╗   ██╗████████╗██╗  ██╗\n";
    echo "     ██║ ██╔╝╚██╗ ██╔╝████╗  ██║██╔════╝╚██╗██╔╝██╔══██╗██║   ██║╚══██╔══╝██║  ██║\n";
    echo "     █████╔╝  ╚████╔╝ ██╔██╗ ██║█████╗   ╚███╔╝ ███████║██║   ██║   ██║   ███████║\n";
    echo "     ██╔═██╗   ╚██╔╝  ██║╚██╗██║██╔══╝   ██╔██╗ ██╔══██║██║   ██║   ██║   ██╔══██║\n";
    echo "     ██║  ██╗   ██║   ██║ ╚████║███████╗██╔╝ ██╗██║  ██║╚██████╔╝   ██║   ██║  ██║\n";
    echo "     ╚═╝  ╚═╝   ╚═╝   ╚═╝  ╚═══╝╚══════╝╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝    ╚═╝   ╚═╝  ╚═╝\n";
    echo Theme::RESET;
    echo "\n";
}

function renderCard(string $tag, string $title, string $subtitle, string $color = Theme::CYAN): void
{
    echo "  " . Theme::DARK_BORDER . "╭── " . Theme::RESET . Theme::BG_BADGE . $color . Theme::BOLD . " [ {$tag} ] " . Theme::RESET . " " . Theme::DARK_BORDER . str_repeat('─', 55 - strlen($tag)) . "╮" . Theme::RESET . "\n";
    echo "  " . Theme::DARK_BORDER . "│" . Theme::RESET . "  " . Theme::WHITE . Theme::BOLD . str_pad($title, 20, ' ') . Theme::RESET . Theme::SLATE . "│ " . $subtitle . str_repeat(' ', max(0, 46 - strlen($subtitle))) . Theme::DARK_BORDER . "│" . Theme::RESET . "\n";
    echo "  " . Theme::DARK_BORDER . "╰────────────────────────────────────────────────────────────────────────╯" . Theme::RESET . "\n";
}

function modernInput(string $label, string $icon = '›'): string
{
    echo "\n";
    echo "  " . Theme::MID_BORDER . "┌─ " . Theme::CYAN . Theme::BOLD . "[ {$icon} ]" . Theme::RESET . " " . Theme::WHITE . Theme::BOLD . $label . Theme::RESET . " " . Theme::MID_BORDER . str_repeat('─', max(2, 60 - strlen($label))) . "┐" . Theme::RESET . "\n";
    echo "  " . Theme::MID_BORDER . "│" . Theme::RESET . "  " . Theme::GOLD . "Enter " . $label . Theme::SLATE . " : " . Theme::WHITE;
    
    $handle = fopen('php://stdin', 'r');
    $val = trim((string)fgets($handle));
    fclose($handle);

    echo Theme::RESET;
    echo "  " . Theme::MID_BORDER . "└" . str_repeat('─', 72) . "┘" . Theme::RESET . "\n";
    return $val;
}

function modernSpinner(string $task, float $seconds = 1.3): void
{
    $frames = ['⠋', '⠙', '⠹', '⠸', '⠼', '⠴', '⠦', '⠧', '⠇', '⠏'];
    $bars = ['░', '▒', '▓', '█'];
    $start = microtime(true);
    $i = 0;

    while ((microtime(true) - $start) < $seconds) {
        $progress = min(1.0, (microtime(true) - $start) / $seconds);
        $filled = (int)($progress * 20);
        $barStr = str_repeat('█', $filled) . str_repeat('░', 20 - $filled);
        $pct = (int)($progress * 100);

        $f = $frames[$i % count($frames)];
        echo "\r  " . Theme::CYAN . $f . Theme::RESET . " " . Theme::WHITE . $task . Theme::RESET . " " . Theme::SLATE . "[" . Theme::PURPLE . $barStr . Theme::SLATE . "] " . Theme::GOLD . "{$pct}%" . Theme::RESET . "  ";
        usleep(60000);
        $i++;
    }

    echo "\r  " . Theme::GREEN . "✓" . Theme::RESET . " " . Theme::WHITE . $task . Theme::RESET . " " . Theme::SLATE . "[" . Theme::GREEN . str_repeat('█', 20) . Theme::SLATE . "] " . Theme::GREEN . "100%" . Theme::RESET . str_repeat(' ', 10) . "\n";
}

function renderUserHUD(\KynexAuth\Api $app): void
{
    $u = $app->user_data;

    echo "\n";
    echo "  " . Theme::CYAN . "╭── 👤 VERIFIED IDENTITY CARD ───────────────────────────────────────────╮" . Theme::RESET . "\n";
    echo "  " . Theme::CYAN . "│" . Theme::RESET . "  " . Theme::SLATE . "Account User : " . Theme::WHITE . Theme::BOLD . str_pad($u->username ?: 'Anonymous', 22, ' ') . Theme::SLATE . "│ Node IP : " . Theme::GOLD . str_pad($u->ip ?: '127.0.0.1', 19, ' ') . Theme::CYAN . "│" . Theme::RESET . "\n";
    echo "  " . Theme::CYAN . "│" . Theme::RESET . "  " . Theme::SLATE . "Hardware SID : " . Theme::PURPLE . str_pad($u->hwid ?: 'N/A', 54, ' ') . Theme::CYAN . "│" . Theme::RESET . "\n";
    echo "  " . Theme::CYAN . "│" . Theme::RESET . "  " . Theme::SLATE . "Member Since : " . Theme::WHITE . str_pad($u->createdate ?: 'N/A', 22, ' ') . Theme::SLATE . "│ Status  : " . Theme::GREEN . Theme::BOLD . str_pad("[ACTIVE ●]", 19, ' ') . Theme::CYAN . "│" . Theme::RESET . "\n";
    echo "  " . Theme::CYAN . "╰────────────────────────────────────────────────────────────────────────╯" . Theme::RESET . "\n";

    echo "  " . Theme::PURPLE . "╭── 💎 SUBSCRIPTION STATUS ──────────────────────────────────────────────╮" . Theme::RESET . "\n";
    if (!empty($u->subscriptions)) {
        foreach ($u->subscriptions as $sub) {
            echo "  " . Theme::PURPLE . "│" . Theme::RESET . "  " . Theme::SLATE . "Plan Name : " . Theme::GOLD . Theme::BOLD . str_pad($sub->name, 25, ' ') . Theme::SLATE . "│ Expiry : " . Theme::WHITE . str_pad($sub->expiry, 20, ' ') . Theme::PURPLE . "│" . Theme::RESET . "\n";
        }
    } else {
        echo "  " . Theme::PURPLE . "│" . Theme::RESET . "  " . Theme::SLATE . "Plan Name : " . Theme::GOLD . Theme::BOLD . str_pad("Lifetime VIP Access", 25, ' ') . Theme::SLATE . "│ Expiry : " . Theme::GREEN . Theme::BOLD . str_pad("NEVER EXPIRES", 20, ' ') . Theme::PURPLE . "│" . Theme::RESET . "\n";
    }
    echo "  " . Theme::PURPLE . "╰────────────────────────────────────────────────────────────────────────╯" . Theme::RESET . "\n";
}

// ╔════════════════════════════════════════════════════════════════════════════╗
// ║                         MAIN EXECUTION ROUTINE                             ║
// ╚════════════════════════════════════════════════════════════════════════════╝

function runMatrixLoader(\KynexAuth\Api $app): void
{
    clearTerminal();
    setTerminalTitle('KynexAuth Matrix Client - ' . date('Y-m-d'));

    renderHeader();

    // 1. Connection Handshake
    modernSpinner('Connecting to KynexAuth Cloud Infrastructure', 1.2);

    $initOk = $app->init();
    if (!$initOk) {
        echo "\n  " . Theme::RED . Theme::BOLD . "[✗] Initialization Failed: " . Theme::RESET . Theme::WHITE . $app->response->message . Theme::RESET . "\n";
        if (!empty($app->app_data->downloadLink)) {
            echo "  " . Theme::GOLD . "[!] Update Available: " . Theme::WHITE . $app->app_data->downloadLink . Theme::RESET . "\n";
        }
        exit(1);
    }

    echo "  " . Theme::GREEN . Theme::BOLD . "[✓] Handshake Established. Session Secured." . Theme::RESET . "\n\n";

    // 2. Futuristic Card Menu
    echo "  " . Theme::SLATE . "─── SELECT AUTHENTICATION METHOD ──────────────────────────────────────" . Theme::RESET . "\n\n";
    renderCard('1', 'ACCOUNT LOGIN', 'Enter username and password to unlock', Theme::CYAN);
    renderCard('2', 'NEW REGISTRATION', 'Activate new account using a license key', Theme::PURPLE);
    renderCard('3', 'LICENSE UPGRADE', 'Extend/Renew existing account subscription', Theme::GOLD);
    renderCard('4', 'INSTANT KEY AUTH', '1-Click instant execution using license key', Theme::GREEN);

    $selection = modernInput('Option (1 - 4)', '★');

    $isAuth = false;
    $actionName = '';

    if ($selection === '1') {
        $actionName = 'Account Login';
        $user = modernInput('Account Username', '👤');
        $pass = modernInput('Account Password', '🔒');
        modernSpinner('Authenticating cryptographic credentials', 1.3);
        $isAuth = $app->login($user, $pass);
    } elseif ($selection === '2') {
        $actionName = 'Account Registration';
        $user = modernInput('Desired Username', '👤');
        $pass = modernInput('Desired Password', '🔒');
        $key  = modernInput('KynexAuth License Key', '🔑');
        $mail = modernInput('Email Address [Optional]', '✉');
        modernSpinner('Registering new cryptographic license', 1.3);
        $isAuth = $app->register($user, $pass, $key, $mail);
    } elseif ($selection === '3') {
        $actionName = 'License Upgrade';
        $user = modernInput('Account Username', '👤');
        $key  = modernInput('Renewal License Key', '🔑');
        modernSpinner('Processing subscription extension', 1.3);
        $isAuth = $app->upgrade($user, $key);
    } elseif ($selection === '4') {
        $actionName = 'Instant Key Verification';
        $key = modernInput('KynexAuth License Key', '⚡');
        modernSpinner('Verifying multi-point hardware binding', 1.3);
        $isAuth = $app->license($key);
    } else {
        echo "\n  " . Theme::RED . Theme::BOLD . "[!] Invalid selection! Please enter 1, 2, 3, or 4." . Theme::RESET . "\n\n";
        exit(1);
    }

    if (!$isAuth) {
        echo "\n  " . Theme::RED . Theme::BOLD . "[✗] {$actionName} Failed: " . Theme::RESET . Theme::WHITE . $app->response->message . Theme::RESET . "\n\n";
        exit(1);
    }

    echo "\n  " . Theme::GREEN . Theme::BOLD . "[✓] {$actionName} Successful: " . Theme::RESET . Theme::WHITE . $app->response->message . Theme::RESET . "\n";

    // 3. User HUD Dashboard
    renderUserHUD($app);

    // 4. Secured Application Payload Banner
    echo "\n";
    echo "  " . Theme::GREEN . "╭────────────────────────────────────────────────────────────────────────╮" . Theme::RESET . "\n";
    echo "  " . Theme::GREEN . "│" . Theme::RESET . "  " . Theme::WHITE . Theme::BOLD . "🚀 APPLICATION PAYLOAD UNLOCKED & ACTIVE" . Theme::RESET . str_repeat(' ', 30) . Theme::GREEN . "│" . Theme::RESET . "\n";
    echo "  " . Theme::GREEN . "│" . Theme::RESET . "  " . Theme::SLATE . "All hardware security verifications and session integrity passed." . Theme::RESET . str_repeat(' ', 6) . Theme::GREEN . "│" . Theme::RESET . "\n";
    echo "  " . Theme::GREEN . "│" . Theme::RESET . "  " . Theme::SLATE . "You can now embed and execute your main PHP code securely here." . Theme::RESET . str_repeat(' ', 7) . Theme::GREEN . "│" . Theme::RESET . "\n";
    echo "  " . Theme::GREEN . "╰────────────────────────────────────────────────────────────────────────╯" . Theme::RESET . "\n\n";

    modernInput('Press ENTER to exit application', '✔');
    exit(0);
}

try {
    runMatrixLoader($KynexAuthApp);
} catch (\Throwable $e) {
    echo "\n  " . Theme::RED . "[!!] Fatal Exception: " . $e->getMessage() . Theme::RESET . "\n\n";
    exit(1);
}
