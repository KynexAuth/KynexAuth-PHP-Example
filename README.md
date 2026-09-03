# 🛡️ KynexAuth PHP SDK & Integration Example

[![PHP Version](https://img.shields.io/badge/PHP-7.4%20%7C%208.0%20%7C%208.1%20%7C%208.2%20%7C%208.3-777BB4.svg?logo=php&logoColor=white)](https://www.php.net/)
[![Platform](https://img.shields.io/badge/Platform-Windows%20%7C%20Linux%20%7C%20macOS-lightgrey.svg)](https://kynexauth.com)
[![Dependencies](https://img.shields.io/badge/Dependencies-Zero%20(Pure%20Native%20PHP)-brightgreen.svg)]()
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Official, zero-dependency PHP client library and CLI implementation for the **[KynexAuth](https://kynexauth.com)** security, authentication, and licensing API. 100% compatible with C#, C++, Python, and JavaScript SDK specifications.

---

## 📁 Repository Structure

```
Php Example/
├── 📄 kynexauth.php    # Core PHP SDK Client Library (Zero external dependencies)
├── 📄 index.php        # Next-Gen Matrix Console Loader (CLI)
├── 📄 run.bat          # 1-Click Console Launcher for Windows
├── 📄 composer.json    # Composer manifest for autoloading
├── 📄 .gitignore       # Standard git ignore file
└── 📄 README.md        # Setup & Integration Documentation
```

---

## 🔑 Step 1: Obtain Credentials from KynexAuth Dashboard

1. Sign in to your **[KynexAuth Developer Dashboard](https://kynexauth.com)**.
2. Navigate to the **Applications** page.
3. Retrieve your application credentials:
   * **Application Name** (e.g., `MyApplication`)
   * **Application Key / Owner ID** (e.g., `Z2zapMIjyB2nkw7ahr`)
   * **Application Version** (e.g., `1.0`)

---

## ⚙️ Step 2: Configure Credentials in Code

Include `kynexauth.php` and initialize the SDK client:

```php
require_once 'kynexauth.php';

// -------------------------------------------------------------
// CONFIGURE YOUR APPLICATION CREDENTIALS
// -------------------------------------------------------------
$KynexAuthApp = new \KynexAuth\Api(
    "YOUR_APP_NAME",       // Application Name from dashboard
    "YOUR_APP_KEY",        // Application Key (Owner ID)
    "1.0",                 // Application Version
    "https://kynexauth.com/api/v1/client" // API Endpoint
);
```

---

## 🖥️ Running the CLI Console Example

1. Open your terminal in the `Php Example` directory:
   ```bash
   cd "Php Example"
   ```
2. Start the interactive console loader:
   ```bash
   php index.php
   ```
3. **Features:**
   * Modern transparent ANSI styling with rotating braille loading animation.
   * `[1] LOGIN` — Authenticate using username and password.
   * `[2] REGISTER` — Create a new account with a license key.
   * `[3] UPGRADE` — Extend an existing user's subscription.
   * `[4] LICENSE` — Instant fast-access via license key.
   * Automatic **Windows User SID** (`S-1-5-21-...`) hardware ID detection.
   * Expiration date and time conversion with subscription status indicator.

---

## 🌐 Web Integration Example (PHP Web Apps)

To protect any PHP web page or admin dashboard:

```php
<?php
session_start();
require_once __DIR__ . '/kynexauth.php';

$KynexAuthApp = new \KynexAuth\Api("YOUR_APP_NAME", "YOUR_APP_KEY", "1.0");
$KynexAuthApp->init();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($KynexAuthApp->login($username, $password)) {
        $_SESSION['authenticated'] = true;
        $_SESSION['user'] = $KynexAuthApp->user_data->username;
        header('Location: /dashboard.php');
        exit;
    } else {
        $error = $KynexAuthApp->response->message;
    }
}
?>
```

---

## 📚 Complete SDK API Reference

| Method | Description | Example Usage |
| :--- | :--- | :--- |
| `init()` | Establishes secure connection & session handshake | `$KynexAuthApp->init();` |
| `login($user, $pass)` | Logs in user & binds unique machine HWID | `$KynexAuthApp->login($user, $pass);` |
| `register($user, $pass, $key, $email)` | Registers new account with a license key | `$KynexAuthApp->register($user, $pass, $key, $email);` |
| `license($key)` | Instant authentication with license key only | `$KynexAuthApp->license($key);` |
| `upgrade($user, $key)` | Upgrades/extends subscription using license key | `$KynexAuthApp->upgrade($user, $key);` |
| `check()` | Verifies that active session is valid & not expired | `$KynexAuthApp->check();` |
| `getvar($varId)` | Fetches a protected server-side secret variable | `$secret = $KynexAuthApp->getvar("secret_key");` |
| `setvar($varId, $val)` | Updates a server-side variable value | `$KynexAuthApp->setvar("config_val", "123");` |
| `log($message)` | Transmits security / activity log to dashboard | `$KynexAuthApp->log("User accessed sensitive data");` |
| `ban($reason)` | Instantly bans the active user and locks HWID | `$KynexAuthApp->ban("Debugger detected");` |
| `webhook($webId, $params)` | Executes configured server-side webhook | `$KynexAuthApp->webhook("WH_ID", "action=ping");` |
| `get_hwid()` | Returns unique hardware ID (SID on Windows) | `$hwid = $KynexAuthApp->get_hwid();` |
| `logout()` | Terminates and destroys active session | `$KynexAuthApp->logout();` |

---

## 🔒 Security Architecture

* **Multi-Point Cryptographic Binding:** HWID is locked on first authentication to prevent unauthorized sharing.
* **Tamper Resistance:** Automatic file checksum verification during `init()`.
* **Zero Overhead:** Built exclusively with pure native PHP networking (`cURL` and fallback `stream_context`).

---

## 📄 License
This SDK is released under the [MIT License](LICENSE).
