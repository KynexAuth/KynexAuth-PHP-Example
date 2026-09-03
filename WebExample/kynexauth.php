<?php
/**
 * KynexAuth PHP Client SDK
 * ─────────────────────────────────────────────────────────────────────────────
 * Zero-dependency, lightweight, official PHP SDK for KynexAuth API.
 * 100% compatible with C#, C++, Python, and JavaScript specification.
 * ─────────────────────────────────────────────────────────────────────────────
 */

namespace KynexAuth;

class Subscription
{
    public string $name;
    public string $expiry;

    public function __construct(array $data = [])
    {
        $this->name = $data['subscription'] ?? $data['name'] ?? 'default';
        $this->expiry = $data['expiry'] ?? 'Lifetime / Active';
    }
}

class UserData
{
    public string $username = '';
    public string $ip = '';
    public string $hwid = '';
    public string $createdate = '';
    public string $lastlogin = '';
    public string $area = '';
    public string $rank = '';
    public string $role = '';
    public string $owner = '';
    /** @var Subscription[] */
    public array $subscriptions = [];

    public function __construct(array $data = [])
    {
        $this->username = $data['username'] ?? '';
        $this->ip = $data['ip'] ?? '';
        $this->hwid = $data['hwid'] ?? '';
        $this->createdate = $data['createdate'] ?? '';
        $this->lastlogin = $data['lastlogin'] ?? '';
        $this->area = $data['area'] ?? '';
        $this->rank = $data['rank'] ?? '';
        $this->role = $data['role'] ?? '';
        $this->owner = $data['owner'] ?? '';

        if (!empty($data['subscriptions']) && is_array($data['subscriptions'])) {
            foreach ($data['subscriptions'] as $sub) {
                $this->subscriptions[] = new Subscription(is_array($sub) ? $sub : ['name' => (string)$sub]);
            }
        }
    }
}

class AppData
{
    public string $numUsers = '0';
    public string $numOnlineUsers = '0';
    public string $numKeys = '0';
    public string $version = '1.0';
    public string $customerPanelLink = '';
    public string $downloadLink = '';
    public string $serverTime = '';

    public function __construct(array $data = [])
    {
        $this->numUsers = (string)($data['numUsers'] ?? '0');
        $this->numOnlineUsers = (string)($data['numOnlineUsers'] ?? '0');
        $this->numKeys = (string)($data['numKeys'] ?? '0');
        $this->version = (string)($data['version'] ?? '1.0');
        $this->customerPanelLink = (string)($data['customerPanelLink'] ?? '');
        $this->downloadLink = (string)($data['downloadLink'] ?? '');
        $this->serverTime = (string)($data['serverTime'] ?? '');
    }
}

class ResponseData
{
    public bool $success;
    public string $message;

    public function __construct(bool $success = false, string $message = '')
    {
        $this->success = $success;
        $this->message = $message;
    }
}

class Api
{
    public string $name;
    public string $ownerid;
    public string $version;
    public string $url;
    public bool $debug;

    public string $sessionid = '';
    public bool $initialized = false;
    public UserData $user_data;
    public AppData $app_data;
    public ResponseData $response;
    private string $hwidCache = '';

    /**
     * Initialize KynexAuth API Client
     * @param string $name Application Name from dashboard
     * @param string $ownerid App Key / Owner ID from dashboard
     * @param string $version Application Version
     * @param string $url API Endpoint URL
     * @param bool $debug Print raw request/response logs
     */
    public function __construct(
        string $name,
        string $ownerid,
        string $version = '1.0',
        string $url = 'https://kynexauth.com/api/v1/client',
        bool $debug = false
    ) {
        $this->name = $name;
        $this->ownerid = $ownerid;
        $this->version = $version;
        $this->url = rtrim($url, '/');
        $this->debug = $debug;

        $this->user_data = new UserData();
        $this->app_data = new AppData();
        $this->response = new ResponseData();
    }

    /**
     * Retrieve unique Windows User SID as HWID
     * @return string
     */
    public function get_hwid(): string
    {
        if (!empty($this->hwidCache)) {
            return $this->hwidCache;
        }

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            try {
                $output = @shell_exec('whoami /user 2>nul');
                if ($output && preg_match('/S-1-5-21-[\d-]+/', $output, $matches)) {
                    $this->hwidCache = $matches[0];
                    return $this->hwidCache;
                }
            } catch (\Throwable $e) {}

            try {
                $username = getenv('USERNAME') ?: '';
                if ($username) {
                    $output = @shell_exec("wmic useraccount where name=\"{$username}\" get sid 2>nul");
                    if ($output && preg_match('/S-1-5-21-[\d-]+/', $output, $matches)) {
                        $this->hwidCache = $matches[0];
                        return $this->hwidCache;
                    }
                }
            } catch (\Throwable $e) {}
        }

        $fallback = php_uname('n') . '-' . PHP_OS . '-' . (getenv('USERNAME') ?: get_current_user());
        $this->hwidCache = substr(hash('sha256', $fallback), 0, 32);
        return $this->hwidCache;
    }

    /**
     * Generate MD5 checksum of running script
     * @return string
     */
    public function get_checksum(): string
    {
        $file = get_included_files()[0] ?? '';
        if ($file && file_exists($file)) {
            return md5_file($file) ?: '';
        }
        return '';
    }

    /**
     * Send HTTP POST request to API endpoint
     * @param string $endpoint
     * @param array $payload
     * @return string
     */
    private function req(string $endpoint, array $payload = []): string
    {
        $fullUrl = $this->url . '/' . ltrim($endpoint, '/');
        $postData = json_encode($payload);

        if ($this->debug) {
            echo "[DEBUG >>] POST {$fullUrl} | Payload: {$postData}\n";
        }

        // 1. Try cURL if extension is loaded
        if (function_exists('curl_init')) {
            $ch = curl_init($fullUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData),
                'User-Agent: KynexAuth-PHP/1.0',
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            $response = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($this->debug) {
                echo "[DEBUG <<] Status {$statusCode} | Raw: {$response}\n";
            }

            if ($response !== false) {
                return (string)$response;
            }
        }

        // 2. Fallback to stream_context if cURL is unavailable
        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n" .
                            "Content-Length: " . strlen($postData) . "\r\n" .
                            "User-Agent: KynexAuth-PHP/1.0\r\n",
                'content' => $postData,
                'timeout' => 15,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];

        $context = stream_context_create($opts);
        $response = @file_get_contents($fullUrl, false, $context);

        return $response !== false ? (string)$response : json_encode([
            'success' => false,
            'message' => 'Failed to connect to KynexAuth server'
        ]);
    }

    /**
     * Fetch real public IP address of current machine
     * @return string
     */
    public function get_public_ip(): string
    {
        $services = [
            'https://api.ipify.org',
            'https://icanhazip.com',
            'https://checkip.amazonaws.com',
            'https://ifconfig.me/ip',
        ];

        foreach ($services as $url) {
            try {
                if (function_exists('curl_init')) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    $ip = curl_exec($ch);
                    curl_close($ch);
                    if ($ip && filter_var(trim((string)$ip), FILTER_VALIDATE_IP)) {
                        return trim((string)$ip);
                    }
                }

                $ctx = stream_context_create([
                    'http' => ['timeout' => 3],
                    'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
                ]);
                $ip = @file_get_contents($url, false, $ctx);
                if ($ip && filter_var(trim((string)$ip), FILTER_VALIDATE_IP)) {
                    return trim((string)$ip);
                }
            } catch (\Throwable $e) {}
        }

        return '127.0.0.1';
    }

    /**
     * Helper to parse login/register/license responses
     * @param string $resStr
     * @param string $defaultUsername
     * @return bool
     */
    private function parseLoginResponse(string $resStr, string $defaultUsername = ''): bool
    {
        $data = json_decode($resStr, true);
        if (!is_array($data)) {
            $this->response = new ResponseData(false, $resStr ?: 'Invalid server response');
            return false;
        }

        $this->response = new ResponseData(
            (bool)($data['success'] ?? false),
            (string)($data['message'] ?? '')
        );

        if ($this->response->success) {
            $info = $data['userInfo'] ?? $data['user'] ?? $data['info'] ?? $data['userData'] ?? [];

            $formatDate = function ($ts) {
                if (!$ts || $ts === '0') return 'Lifetime / Active';
                $num = (int)$ts;
                if ($num <= 0) return (string)$ts;
                $timestamp = $num > 10000000000 ? (int)($num / 1000) : $num;
                return date('m/d/Y, h:i:s A', $timestamp);
            };

            $expiryFormatted = $formatDate($info['expiresAt'] ?? $info['expiry'] ?? null);
            $createdFormatted = $formatDate($info['createdAt'] ?? $info['createdate'] ?? null);

            $subsList = [];
            if (!empty($info['subscriptions']) && is_array($info['subscriptions'])) {
                foreach ($info['subscriptions'] as $s) {
                    $sName = is_array($s) ? ($s['subscription'] ?? $s['name'] ?? 'Premium Plan') : (string)$s;
                    $sExp = is_array($s) ? ($s['expiry'] ?? $s['expiresAt'] ?? $expiryFormatted) : $expiryFormatted;
                    $subsList[] = [
                        'subscription' => $sName,
                        'expiry' => $formatDate($sExp),
                    ];
                }
            } else {
                $subsList[] = [
                    'subscription' => $info['subscription'] ?? (isset($data['subscription']['name']) ? $data['subscription']['name'] : 'Premium Plan'),
                    'expiry' => $expiryFormatted,
                ];
            }

            $userIp = (string)($info['ip'] ?? $data['ip'] ?? '');
            if (empty($userIp) || $userIp === '127.0.0.1') {
                $userIp = $this->get_public_ip();
            }

            $this->user_data = new UserData([
                'username' => $info['username'] ?? $defaultUsername,
                'ip' => $userIp,
                'hwid' => $info['hwid'] ?? $this->get_hwid(),
                'createdate' => $createdFormatted,
                'lastlogin' => $info['lastLogin'] ?? $info['lastlogin'] ?? 'Just now',
                'area' => $info['area'] ?? '',
                'rank' => $info['rank'] ?? '',
                'role' => $info['role'] ?? '',
                'owner' => $info['owner'] ?? '',
                'subscriptions' => $subsList,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Initialize connection with KynexAuth server
     * @return bool
     */
    public function init(): bool
    {
        $payload = [
            'name' => $this->name,
            'appKey' => $this->ownerid,
            'version' => $this->version,
            'hash' => $this->get_checksum(),
        ];

        $resStr = $this->req('/init', $payload);
        $data = json_decode($resStr, true);

        if (!is_array($data)) {
            $this->response = new ResponseData(false, $resStr ?: 'Failed to connect');
            return false;
        }

        $this->response = new ResponseData(
            (bool)($data['success'] ?? false),
            (string)($data['message'] ?? '')
        );

        if ($this->response->success) {
            $this->initialized = true;
            $this->sessionid = (string)($data['sessionToken'] ?? '');
            $appInfo = $data['appInfo'] ?? [];
            if (is_array($appInfo)) {
                $this->app_data->version = (string)($appInfo['version'] ?? $this->version);
                if (!empty($appInfo['name']) && empty($this->name)) {
                    $this->name = (string)$appInfo['name'];
                }
            }
        }

        if (!empty($data['downloadLink'])) {
            $this->app_data->downloadLink = (string)$data['downloadLink'];
        }

        return $this->response->success;
    }

    /**
     * Authenticate with username and password
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login(string $username, string $password): bool
    {
        $payload = [
            'username' => $username,
            'password' => $password,
            'hwid' => $this->get_hwid(),
            'sessionToken' => $this->sessionid,
        ];

        $resStr = $this->req('/login', $payload);
        return $this->parseLoginResponse($resStr, $username);
    }

    /**
     * Register a new account with a license key
     * @param string $username
     * @param string $password
     * @param string $key
     * @param string $email
     * @return bool
     */
    public function register(string $username, string $password, string $key, string $email = ''): bool
    {
        $payload = [
            'username' => $username,
            'password' => $password,
            'licenseKey' => $key,
            'email' => $email,
            'hwid' => $this->get_hwid(),
            'sessionToken' => $this->sessionid,
        ];

        $resStr = $this->req('/register', $payload);
        return $this->parseLoginResponse($resStr, $username);
    }

    /**
     * Instant authentication using license key only
     * @param string $key
     * @return bool
     */
    public function license(string $key): bool
    {
        $payload = [
            'licenseKey' => $key,
            'hwid' => $this->get_hwid(),
            'sessionToken' => $this->sessionid,
        ];

        $resStr = $this->req('/license', $payload);
        return $this->parseLoginResponse($resStr, 'Key_' . substr($key, 0, 6));
    }

    /**
     * Extend/upgrade subscription for an existing user
     * @param string $username
     * @param string $key
     * @return bool
     */
    public function upgrade(string $username, string $key): bool
    {
        $payload = [
            'username' => $username,
            'licenseKey' => $key,
            'sessionToken' => $this->sessionid,
        ];

        $resStr = $this->req('/upgrade', $payload);
        $data = json_decode($resStr, true);

        if (is_array($data)) {
            $this->response = new ResponseData((bool)($data['success'] ?? false), (string)($data['message'] ?? ''));
            return $this->response->success;
        }

        return false;
    }

    /**
     * Verify that active session token is still valid
     * @return bool
     */
    public function check(): bool
    {
        $payload = [
            'sessionToken' => $this->sessionid,
        ];

        $resStr = $this->req('/check', $payload);
        $data = json_decode($resStr, true);

        if (is_array($data)) {
            $this->response = new ResponseData((bool)($data['success'] ?? false), (string)($data['message'] ?? ''));
            return $this->response->success;
        }

        return false;
    }

    /**
     * Fetch a secure server-side secret variable
     * @param string $var_name
     * @return string
     */
    public function getvar(string $var_name): string
    {
        $payload = [
            'varid' => $var_name,
            'sessionToken' => $this->sessionid,
        ];

        $resStr = $this->req('/var', $payload);
        $data = json_decode($resStr, true);

        if (is_array($data)) {
            $this->response = new ResponseData((bool)($data['success'] ?? false), (string)($data['message'] ?? ''));
            if ($this->response->success) {
                return (string)($data['response'] ?? $data['value'] ?? '');
            }
        }

        return '';
    }

    /**
     * Set a server-side variable
     * @param string $var_name
     * @param string $var_data
     * @return bool
     */
    public function setvar(string $var_name, string $var_data): bool
    {
        $payload = [
            'varid' => $var_name,
            'vardata' => $var_data,
            'sessionToken' => $this->sessionid,
        ];

        $resStr = $this->req('/setvar', $payload);
        $data = json_decode($resStr, true);

        if (is_array($data)) {
            $this->response = new ResponseData((bool)($data['success'] ?? false), (string)($data['message'] ?? ''));
            return $this->response->success;
        }

        return false;
    }

    /**
     * Transmit activity / security log to dashboard
     * @param string $message
     * @return bool
     */
    public function log(string $message): bool
    {
        $payload = [
            'message' => $message,
            'sessionToken' => $this->sessionid,
        ];

        $resStr = $this->req('/log', $payload);
        $data = json_decode($resStr, true);

        if (is_array($data)) {
            $this->response = new ResponseData((bool)($data['success'] ?? false), (string)($data['message'] ?? ''));
            return $this->response->success;
        }

        return false;
    }

    /**
     * Instantly ban current user & HWID
     * @param string $reason
     * @return bool
     */
    public function ban(string $reason = 'Security violation detected'): bool
    {
        $payload = [
            'reason' => $reason,
            'sessionToken' => $this->sessionid,
        ];

        $resStr = $this->req('/ban', $payload);
        $data = json_decode($resStr, true);

        if (is_array($data)) {
            $this->response = new ResponseData((bool)($data['success'] ?? false), (string)($data['message'] ?? ''));
            return $this->response->success;
        }

        return false;
    }

    /**
     * Trigger a server-side webhook securely
     * @param string $id
     * @param string $params
     * @return string
     */
    public function webhook(string $id, string $params = ''): string
    {
        $payload = [
            'webid' => $id,
            'params' => $params,
            'sessionToken' => $this->sessionid,
        ];

        $resStr = $this->req('/webhook', $payload);
        $data = json_decode($resStr, true);

        if (is_array($data)) {
            $this->response = new ResponseData((bool)($data['success'] ?? false), (string)($data['message'] ?? ''));
            if ($this->response->success) {
                return (string)($data['response'] ?? '');
            }
        }

        return '';
    }

    /**
     * Invalidate active session
     * @return bool
     */
    public function logout(): bool
    {
        $payload = [
            'sessionToken' => $this->sessionid,
        ];

        $resStr = $this->req('/logout', $payload);
        $this->sessionid = '';
        $this->initialized = false;
        $this->user_data = new UserData();

        $data = json_decode($resStr, true);
        if (is_array($data)) {
            $this->response = new ResponseData((bool)($data['success'] ?? false), (string)($data['message'] ?? ''));
            return $this->response->success;
        }

        return false;
    }
}
