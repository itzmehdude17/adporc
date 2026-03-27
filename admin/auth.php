<?php
// Admin auth guard — multi-user system
// Include at the top of every admin page before any output.

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
    ]);
}

define('ADMIN_DATA_DIR', dirname(__DIR__) . '/data/');
define('ADMIN_CONFIG_FILE', ADMIN_DATA_DIR . 'config.json');
define('ADMIN_ASSETS_DIR', dirname(__DIR__) . '/assets/images/');

// ── Config helpers ──────────────────────────────────────────
function admin_get_config(): array {
    if (!file_exists(ADMIN_CONFIG_FILE)) return [];
    return json_decode(file_get_contents(ADMIN_CONFIG_FILE), true) ?: [];
}
function admin_save_config(array $config): bool {
    $json = json_encode($config, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $tmp  = ADMIN_CONFIG_FILE . '.tmp';
    if (file_put_contents($tmp, $json, LOCK_EX) === false) return false;
    return rename($tmp, ADMIN_CONFIG_FILE);
}

// ── Setup detection ─────────────────────────────────────────
// Returns true if there is at least one user in config (or legacy format).
function admin_is_setup(): bool {
    $cfg = admin_get_config();
    return !empty($cfg['users']) || !empty($cfg['password_hash']);
}

// ── Migrate legacy single-password format ───────────────────
// Called automatically on first login attempt after upgrade.
function admin_migrate_legacy(): void {
    $cfg = admin_get_config();
    if (empty($cfg['users']) && !empty($cfg['password_hash'])) {
        $cfg['users'] = [[
            'id'            => 'master',
            'username'      => 'master',
            'role'          => 'master',
            'email'         => 'contact.itzmehdude@gmail.com',
            'password_hash' => $cfg['password_hash'],
            'created_at'    => date('Y-m-d'),
            'last_login'    => null,
            'is_active'     => true,
            'temp_password' => null,
        ]];
        unset($cfg['password_hash'], $cfg['recovery_email'], $cfg['reset_token'], $cfg['reset_expires']);
        admin_save_config($cfg);
    }
}

// ── Auth state ──────────────────────────────────────────────
function admin_is_logged_in(): bool {
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}
function admin_require_login(): void {
    if (!admin_is_logged_in()) {
        header('Location: /admin/index.php');
        exit;
    }
}
function admin_current_user(): array {
    return [
        'id'       => $_SESSION['admin_user_id']  ?? '',
        'username' => $_SESSION['admin_username']  ?? '',
        'role'     => $_SESSION['admin_role']      ?? 'editor',
    ];
}
function admin_is_master(): bool {
    return ($_SESSION['admin_role'] ?? '') === 'master';
}
function admin_require_master(): void {
    admin_require_login();
    if (!admin_is_master()) {
        header('Location: /admin/dashboard.php');
        exit;
    }
}

// ── User lookup & update ────────────────────────────────────
function admin_find_user(string $username): ?array {
    $cfg = admin_get_config();
    foreach ($cfg['users'] ?? [] as $user) {
        if (strtolower($user['username']) === strtolower(trim($username))) return $user;
    }
    return null;
}
function admin_update_user(array $updated): bool {
    $cfg = admin_get_config();
    foreach ($cfg['users'] as &$user) {
        if ($user['id'] === $updated['id']) {
            $user = $updated;
            return admin_save_config($cfg);
        }
    }
    return false;
}
function admin_all_users(): array {
    return admin_get_config()['users'] ?? [];
}

// ── Session login ───────────────────────────────────────────
function admin_do_login(array $user): void {
    session_regenerate_id(true);
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user_id']   = $user['id'];
    $_SESSION['admin_username']  = $user['username'];
    $_SESSION['admin_role']      = $user['role'];
}

// ── CSRF ────────────────────────────────────────────────────
function admin_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function admin_verify_csrf(): bool {
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? '';
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// ── JSON data helpers ───────────────────────────────────────
function read_json(string $file): mixed {
    $path = ADMIN_DATA_DIR . $file;
    if (!file_exists($path)) return null;
    return json_decode(file_get_contents($path), true);
}
function write_json(string $file, mixed $data): bool {
    $path = ADMIN_DATA_DIR . $file;
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($json === false) return false;
    $tmp = $path . '.tmp';
    if (file_put_contents($tmp, $json, LOCK_EX) === false) return false;
    return rename($tmp, $path);
}

// ── HTML escape ─────────────────────────────────────────────
function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

