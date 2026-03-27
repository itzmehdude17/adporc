<?php
// Admin auth guard — include this at the top of every admin page
// Starts session and redirects to login if not authenticated

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
    ]);
}

define('ADMIN_DATA_DIR', dirname(__DIR__) . '/data/');
define('ADMIN_CONFIG_FILE', ADMIN_DATA_DIR . 'config.json');
define('ADMIN_ASSETS_DIR', dirname(__DIR__) . '/assets/images/');

function admin_is_setup(): bool {
    return file_exists(ADMIN_CONFIG_FILE);
}

function admin_is_logged_in(): bool {
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function admin_require_login(): void {
    if (!admin_is_logged_in()) {
        header('Location: /admin/index.php');
        exit;
    }
}

function admin_get_config(): array {
    if (!file_exists(ADMIN_CONFIG_FILE)) return [];
    $json = file_get_contents(ADMIN_CONFIG_FILE);
    return json_decode($json, true) ?: [];
}

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

function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
