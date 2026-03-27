<?php
require_once __DIR__ . '/auth.php';

$error   = '';
$success = '';
$mode    = admin_is_setup() ? 'login' : 'setup';

// Redirect if already logged in
if (admin_is_logged_in()) {
    header('Location: /admin/dashboard.php');
    exit;
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ── First-time setup: create master account ──
    if ($action === 'setup' && $mode === 'setup') {
        $username = trim($_POST['username'] ?? '');
        $pw       = $_POST['password']  ?? '';
        $pw2      = $_POST['password2'] ?? '';

        if ($username === '' || !preg_match('/^[a-zA-Z0-9_]{3,32}$/', $username)) {
            $error = 'Username must be 3–32 characters (letters, numbers, underscore).';
        } elseif (strlen($pw) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($pw !== $pw2) {
            $error = 'Passwords do not match.';
        } else {
            $config = ['users' => [[
                'id'            => 'master',
                'username'      => $username,
                'role'          => 'master',
                'email'         => 'contact.itzmehdude@gmail.com',
                'password_hash' => password_hash($pw, PASSWORD_BCRYPT, ['cost' => 12]),
                'created_at'    => date('Y-m-d'),
                'last_login'    => null,
                'is_active'     => true,
                'temp_password' => null,
            ]]];
            if (write_json('config.json', $config)) {
                $success = 'Master account created! Please sign in.';
                $mode    = 'login';
            } else {
                $error = 'Could not write config file. Check that /data/ exists and is writable.';
            }
        }

    // ── Login ──
    } elseif ($action === 'login' && $mode === 'login') {
        // Migrate legacy single-password config if needed
        admin_migrate_legacy();

        $username = trim($_POST['username'] ?? '');
        $pw       = $_POST['password']    ?? '';
        $user     = admin_find_user($username);

        if ($user && ($user['is_active'] ?? true) && password_verify($pw, $user['password_hash'] ?? '')) {
            // Clear temp_password once they've used it to log in (if they haven't changed it yet, it stays)
            admin_do_login($user);
            // Update last_login timestamp
            $user['last_login'] = date('Y-m-d H:i:s');
            admin_update_user($user);
            header('Location: /admin/dashboard.php');
            exit;
        } else {
            usleep(random_int(150000, 350000));
            $error = 'Incorrect username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $mode === 'setup' ? 'Setup — ADPORC Admin' : 'Sign In — ADPORC Admin' ?></title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="/admin/assets/admin.css">
  <style>
    body.login-body {
      background: linear-gradient(135deg, hsl(0,85%,7%) 0%, hsl(0,60%,20%) 100%);
    }
    .login-logo h1 { color: var(--primary); letter-spacing: 2px; }
    .login-logo .brand-dot { color: var(--accent); }
    .login-divider {
      display: flex; align-items: center; gap: 10px;
      margin: 18px 0 20px; font-size: .78rem; color: var(--text-light);
    }
    .login-divider::before, .login-divider::after {
      content: ''; flex: 1; height: 1px; background: var(--border);
    }
    .btn-login {
      display: block;
      width: 100%; margin-top: 10px;
      padding: 11px 18px;
      background: hsl(0, 92%, 47%);
      color: #fff !important;
      font-size: .95rem; font-weight: 600;
      border: none; border-radius: 8px;
      cursor: pointer; font-family: inherit;
      transition: background .15s, transform .1s;
      -webkit-appearance: none;
      appearance: none;
      text-align: center;
      line-height: 1.4;
    }
    .btn-login:hover { background: hsl(0, 92%, 38%); }
    .btn-login:active { transform: scale(0.98); }
  </style>
</head>
<body class="login-body">
  <div class="login-card">
    <div class="login-logo">
      <h1>ADPORC</h1>
      <p>Admin Panel</p>
    </div>

    <?php if ($mode === 'setup'): ?>
      <h2>First-Time Setup</h2>
      <p style="font-size:.85rem;color:#666;text-align:center;margin-bottom:20px;">Create the master admin account.</p>
    <?php else: ?>
      <h2>Sign In</h2>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="login-error"><?= h($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="login-success"><?= h($success) ?></div>
    <?php endif; ?>

    <?php if ($mode === 'setup'): ?>
    <form method="POST" autocomplete="off">
      <input type="hidden" name="action" value="setup">
      <div class="form-group">
        <label class="form-label" for="username">Username</label>
        <input type="text" id="username" name="username" class="form-control"
               placeholder="Choose a username" required autofocus
               pattern="[a-zA-Z0-9_]{3,32}" title="3–32 characters: letters, numbers, underscore">
      </div>
      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control"
               placeholder="Min 8 characters" required minlength="8">
      </div>
      <div class="form-group">
        <label class="form-label" for="password2">Confirm Password</label>
        <input type="password" id="password2" name="password2" class="form-control"
               placeholder="Re-enter password" required>
      </div>
      <button type="submit" class="btn-login">Create Master Account</button>
    </form>

    <?php else: ?>
    <form method="POST" autocomplete="off">
      <input type="hidden" name="action" value="login">
      <div class="form-group">
        <label class="form-label" for="username">Username</label>
        <input type="text" id="username" name="username" class="form-control"
               placeholder="Enter your username" required autofocus autocomplete="username">
      </div>
      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control"
               placeholder="Enter your password" required autocomplete="current-password">
      </div>
      <button type="submit" class="btn-login">Sign In</button>
    </form>
    <?php endif; ?>

    <p class="login-note">ADPORC Admin — Restricted Access Only</p>
  </div>
</body>
</html>