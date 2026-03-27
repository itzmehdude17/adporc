<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$pageTitle = 'Change Password';
$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf()) {
        $error = 'Invalid request. Please try again.';
    } else {
        $current = $_POST['current_password'] ?? '';
        $newPass = $_POST['new_password']     ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $user = admin_find_user($_SESSION['admin_username'] ?? '');

        if (!$user || !password_verify($current, $user['password_hash'] ?? '')) {
            $error = 'Current password is incorrect.';
        } elseif (strlen($newPass) < 8) {
            $error = 'New password must be at least 8 characters.';
        } elseif ($newPass !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $user['password_hash'] = password_hash($newPass, PASSWORD_BCRYPT, ['cost' => 12]);
            // Clear the temp_password (master can no longer see their old initial password)
            $user['temp_password'] = null;
            if (admin_update_user($user)) {
                $success = 'Password changed successfully.';
            } else {
                $error = 'Could not save new password. Check server write permissions.';
            }
        }
    }
}

include __DIR__ . '/_layout-top.php';
?>

<div class="card" style="max-width:480px;">
  <div class="card-header">
    <h2>🔒 Change Password</h2>
  </div>
  <p style="font-size:.85rem;color:#666;margin-bottom:16px;">
    Change your own password. There is no password recovery — contact the master admin if you get locked out.
  </p>

  <?php if ($error): ?>
    <div class="login-error" style="margin-bottom:14px;"><?= h($error) ?></div>
  <?php endif; ?>
  <?php if ($success): ?>
    <div class="login-success" style="margin-bottom:14px;"><?= h($success) ?></div>
  <?php endif; ?>

  <form method="POST">
    <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">
    <div class="form-group">
      <label class="form-label">Current Password</label>
      <input type="password" name="current_password" class="form-control" required autocomplete="current-password">
    </div>
    <div class="form-group">
      <label class="form-label">New Password <small style="color:#999">(min. 8 characters)</small></label>
      <input type="password" name="new_password" class="form-control" required minlength="8" autocomplete="new-password">
    </div>
    <div class="form-group">
      <label class="form-label">Confirm New Password</label>
      <input type="password" name="confirm_password" class="form-control" required minlength="8" autocomplete="new-password">
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Update Password</button>
  </form>
</div>

<?php include __DIR__ . '/_layout-bottom.php'; ?>


<div class="card" style="max-width:480px;">
  <div class="card-header">
    <h2>🔒 Change Password</h2>
  </div>

  <?php if ($error): ?>
    <div class="login-error"><?= h($error) ?></div>
  <?php endif; ?>
  <?php if ($success): ?>
    <div class="login-success"><?= h($success) ?></div>
  <?php endif; ?>

  <form method="POST">
    <input type="hidden" name="csrf_token" value="<?= admin_csrf_token() ?>">

    <div class="form-group">
      <label class="form-label">Current Password</label>
      <input type="password" name="current_password" class="form-control" required autocomplete="current-password">
    </div>
    <div class="form-group">
      <label class="form-label">New Password <small style="color:#999">(min. 8 characters)</small></label>
      <input type="password" name="new_password" class="form-control" required minlength="8" autocomplete="new-password">
    </div>
    <div class="form-group">
      <label class="form-label">Confirm New Password</label>
      <input type="password" name="confirm_password" class="form-control" required minlength="8" autocomplete="new-password">
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Update Password</button>
  </form>
</div>

<?php include __DIR__ . '/_layout-bottom.php'; ?>
