# 🛡️ KynexAuth PHP SDK, CLI & Web Example

[![PHP Version](https://img.shields.io/badge/PHP-7.4%20%7C%208.0%20%7C%208.1%20%7C%208.2%20%7C%208.3-777BB4.svg?logo=php&logoColor=white)](https://www.php.net/)
[![Platform](https://img.shields.io/badge/Platform-Windows%20%7C%20Linux%20%7C%20macOS-lightgrey.svg)](https://kynexauth.com)
[![Dependencies](https://img.shields.io/badge/Dependencies-Zero%20(Pure%20Native%20PHP)-brightgreen.svg)]()
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Official, zero-dependency PHP client library, CLI terminal application, and modern Web authentication portal for the **[KynexAuth](https://kynexauth.com)** security and licensing cloud API.

---

## 📁 Repository Structure

```
Php Example/
├── 📂 ConsoleExample/       # Next-Gen Futuristic CLI Terminal Application
│   ├── 📄 kynexauth.php     # Core PHP SDK Client Library
│   ├── 📄 index.php         # Matrix Card Terminal Loader
│   └── 📄 run.bat           # 1-Click CLI Console Launcher for Windows
│
├── 📂 WebExample/           # Full-Featured Dark Glassmorphism Web Portal
│   ├── 📄 kynexauth.php     # Core PHP SDK Client Library
│   ├── 📄 index.php         # Glassmorphism Web Login Portal (Login, Register, Key, Upgrade)
│   ├── 📄 dashboard.php     # Protected Member Dashboard with Micro-Animations
│   └── 📄 start_web.bat     # 1-Click Local Web Server Launcher
│
├── 📄 composer.json         # Composer manifest for autoloading
├── 📄 .gitignore            # Standard git ignore file
└── 📄 README.md             # Integration Documentation
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

## 🖥️ Option A: Running the CLI Console Example

1. Navigate to the `ConsoleExample/` directory:
   ```bash
   cd "Php Example/ConsoleExample"
   ```
2. Run directly:
   ```bash
   php index.php
   # or double-click run.bat on Windows
   ```
3. **Features:**
   * Matrix/Card HUD layout with rotating braille loading animation.
   * `[1] LOGIN` — Authenticate using username and password.
   * `[2] REGISTER` — Create a new account with a license key.
   * `[3] UPGRADE` — Extend an existing user's subscription.
   * `[4] LICENSE KEY` — Instant fast-access via license key.
   * Automatic **Windows User SID** (`S-1-5-21-...`) hardware ID detection.
   * Real public IPv4 resolution.

---

## 🌐 Option B: Running the Web Portal Example

1. Navigate to the `WebExample/` directory:
   ```bash
   cd "Php Example/WebExample"
   ```
2. Start the web server:
   * Double-click **`start_web.bat`** on Windows (automatically launches web server and opens browser at `http://localhost:8000/index.php`).
   * Or run manually:
     ```bash
     php -S localhost:8000
     ```
3. **Features:**
   * Modern **Dark Glassmorphism UI** with multi-tab interface (Login, Register, License Key, Upgrade).
   * **`dashboard.php`** protected user area with live pulse radar, gold VIP subscription badges, real IP telemetry, and session management.

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
