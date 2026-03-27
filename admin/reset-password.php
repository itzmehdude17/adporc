<?php
require_once __DIR__ . '/auth.php';

if (admin_is_logged_in()) {
    header('Location: /admin/dashboard.php');
    exit;
}

$error   = '';
$success = '';
$token   = $_GET['token'] ?? $_POST['token'] ?? '';
$config  = admin_get_config();

// ── Validate the token ──────────────────────────────────────
function token_is_valid(array $config, string $token): bool {
    if (empty($config['reset_token']) || empty($config['reset_expires'])) return false;
    if (time() > (int) $config['reset_expires']) return false;
    return password_verify($token, $config['reset_token']);
}

$valid = $token !== '' && token_is_valid($config, $token);

// ── Handle new-password POST ────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid) {
    $pw  = $_POST['password']  ?? '';
    $pw2 = $_POST['password2'] ?? '';

    if (strlen($pw) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($pw !== $pw2) {
        $error = 'Passwords do not match.';
    } else {
        $config['password_hash'] = password_hash($pw, PASSWORD_BCRYPT);
        unset($config['reset_token'], $config['reset_expires']); // single-use: clear token
        write_json('config.json', $config);
        $success = 'Password updated! You can now log in.';
        $valid   = false; // hide the form
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password — ADPORC Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body class="login-body">
  <div class="login-card">
    <div class="login-logo">
      <h1>ADPORC</h1>
      <p>Admin Panel</p>
    </div>
    <h2>Set New Password</h2>

    <?php if ($error): ?>
      <div class="login-error"><?= h($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="login-success"><?= h($success) ?></div>
      <p style="text-align:center;margin-top:14px;">
        <a href="/admin/index.php" class="btn btn-primary" style="display:inline-block;padding:8px 24px;">Go to Login</a>
      </p>

    <?php elseif ($valid): ?>
      <form method="POST" autocomplete="off">
        <input type="hidden" name="token" value="<?= h($token) ?>">
        <div class="form-group">
          <label class="form-label" for="password">New Password</label>
          <input type="password" id="password" name="password" class="form-control"
                 placeholder="Min 8 characters" required autofocus>
        </div>
        <div class="form-group">
          <label class="form-label" for="password2">Confirm Password</label>
          <input type="password" id="password2" name="password2" class="form-control"
                 placeholder="Re-enter password" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Update Password</button>
      </form>

    <?php else: ?>
      <div class="login-error">
        This reset link is invalid or has expired (links expire after 1 hour).<br>
        Please <a href="/admin/forgot-password.php">request a new one</a>.
      </div>
    <?php endif; ?>

    <p style="text-align:center;margin-top:14px;font-size:.83rem;">
      <a href="/admin/index.php">← Back to Login</a>
    </p>
  </div>
</body>
</html>
