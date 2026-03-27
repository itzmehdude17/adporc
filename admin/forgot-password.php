<?php
require_once __DIR__ . '/auth.php';

// Already logged in — no need to be here
if (admin_is_logged_in()) {
    header('Location: /admin/dashboard.php');
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email  = trim($_POST['email'] ?? '');
    $config = admin_get_config();
    $stored = $config['recovery_email'] ?? '';

    // Always wait a moment to prevent email-enumeration timing attacks
    usleep(random_int(150000, 350000));

    if ($email !== '' && $stored !== '' && hash_equals(strtolower($stored), strtolower($email))) {
        // Generate a secure single-use token valid for 1 hour
        $token   = bin2hex(random_bytes(32));
        $expires = time() + 3600;

        $config['reset_token']   = password_hash($token, PASSWORD_BCRYPT);
        $config['reset_expires'] = $expires;
        write_json('config.json', $config);

        $resetLink = 'https://' . $_SERVER['HTTP_HOST'] . '/admin/reset-password.php?token=' . urlencode($token);

        $subject = 'ADPORC Admin — Password Reset';
        $body    = "Hello,\n\nA password reset was requested for the ADPORC admin panel.\n\n"
                 . "Click the link below to set a new password (valid for 1 hour):\n\n"
                 . $resetLink . "\n\n"
                 . "If you did not request this, ignore this email — your password has not changed.\n\n"
                 . "— ADPORC Admin System";

        $headers = "From: noreply@adporc.com\r\nX-Mailer: PHP/" . PHP_VERSION;
        mail($stored, $subject, $body, $headers);
    }

    // Always show the same message regardless of whether email matched
    // (prevents leaking whether the email is registered)
    $success = 'If that email is on record, a reset link has been sent. Check your inbox (and spam folder).';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password — ADPORC Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body class="login-body">
  <div class="login-card">
    <div class="login-logo">
      <h1>ADPORC</h1>
      <p>Admin Panel</p>
    </div>
    <h2>Reset Password</h2>
    <p style="font-size:.85rem;color:#666;text-align:center;margin-bottom:20px;">
      Enter your recovery email and we'll send you a reset link.
    </p>

    <?php if ($error): ?>
      <div class="login-error"><?= h($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="login-success"><?= h($success) ?></div>
    <?php else: ?>
    <form method="POST" autocomplete="off">
      <div class="form-group">
        <label class="form-label" for="email">Recovery Email</label>
        <input type="email" id="email" name="email" class="form-control"
               placeholder="your@email.com" required autofocus>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Send Reset Link</button>
    </form>
    <?php endif; ?>

    <p style="text-align:center;margin-top:14px;font-size:.83rem;">
      <a href="/admin/index.php">← Back to Login</a>
    </p>
  </div>
</body>
</html>
