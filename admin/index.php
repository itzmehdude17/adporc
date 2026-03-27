<?php
require_once __DIR__ . '/auth.php';

$error   = '';
$success = '';
$mode    = admin_is_setup() ? 'login' : 'setup';

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'setup' && $mode === 'setup') {
        $pw  = $_POST['password']  ?? '';
        $pw2 = $_POST['password2'] ?? '';

        if (strlen($pw) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($pw !== $pw2) {
            $error = 'Passwords do not match.';
        } else {
            $config = ['password_hash' => password_hash($pw, PASSWORD_BCRYPT)];
            if (write_json('config.json', $config)) {
                $success = 'Admin account created! Please log in.';
                $mode    = 'login';
            } else {
                $error = 'Could not write config file. Check that the /data/ folder exists and is writable.';
            }
        }
    } elseif ($action === 'login' && $mode === 'login') {
        $pw     = $_POST['password'] ?? '';
        $config = admin_get_config();

        if (password_verify($pw, $config['password_hash'] ?? '')) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            header('Location: /admin/dashboard.php');
            exit;
        } else {
            // Prevent timing attacks by always taking ~same time
            usleep(random_int(100000, 300000));
            $error = 'Incorrect password. Please try again.';
        }
    }
}

// If already logged in, go to dashboard
if (admin_is_logged_in()) {
    header('Location: /admin/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $mode === 'setup' ? 'Setup Admin — ADPORC' : 'Admin Login — ADPORC' ?></title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body class="login-body">
  <div class="login-card">
    <div class="login-logo">
      <h1>ADPORC</h1>
      <p>Admin Panel</p>
    </div>

    <?php if ($mode === 'setup'): ?>
      <h2>First-Time Setup</h2>
      <p style="font-size:.85rem;color:#666;text-align:center;margin-bottom:20px;">Create your master admin password to get started.</p>
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
        <label class="form-label" for="password">New Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Min 8 characters" required autofocus>
      </div>
      <div class="form-group">
        <label class="form-label" for="password2">Confirm Password</label>
        <input type="password" id="password2" name="password2" class="form-control" placeholder="Re-enter password" required>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Create Admin Account</button>
    </form>
    <?php else: ?>
    <form method="POST" autocomplete="off">
      <input type="hidden" name="action" value="login">
      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required autofocus>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Sign In</button>
    </form>
    <?php endif; ?>

    <p class="login-note">ADPORC Admin — Restricted Access Only</p>
  </div>
</body>
</html>
