<?php
require_once __DIR__ . '/auth.php';
admin_require_master();

$pageTitle = 'Manage Users';
$error   = '';
$success = '';

// ── Handle POST actions ──────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf()) {
        $error = 'Security token mismatch. Please refresh and try again.';
    } else {
        $action = $_POST['action'] ?? '';

        // Create new user
        if ($action === 'create_user') {
            $username = trim($_POST['username'] ?? '');
            $pw       = $_POST['password'] ?? '';

            if (!preg_match('/^[a-zA-Z0-9_]{3,32}$/', $username)) {
                $error = 'Username must be 3–32 characters (letters, numbers, underscore).';
            } elseif (admin_find_user($username)) {
                $error = 'Username already exists.';
            } elseif (strlen($pw) < 8) {
                $error = 'Initial password must be at least 8 characters.';
            } else {
                $cfg   = admin_get_config();
                $newId = 'usr_' . bin2hex(random_bytes(4));
                $cfg['users'][] = [
                    'id'            => $newId,
                    'username'      => $username,
                    'role'          => 'editor',
                    'email'         => '',
                    'password_hash' => password_hash($pw, PASSWORD_BCRYPT, ['cost' => 12]),
                    'created_at'    => date('Y-m-d'),
                    'last_login'    => null,
                    'is_active'     => true,
                    'temp_password' => $pw,   // master can see initial password until user changes it
                ];
                admin_save_config($cfg);
                $success = "User <strong>" . h($username) . "</strong> created. Share the initial password with them.";
            }
        }

        // Reset user password (master sets new password for any user)
        if ($action === 'reset_password') {
            $uid  = $_POST['user_id'] ?? '';
            $pw   = $_POST['new_password'] ?? '';

            if (strlen($pw) < 8) {
                $error = 'Password must be at least 8 characters.';
            } else {
                $cfg = admin_get_config();
                $found = false;
                foreach ($cfg['users'] as &$user) {
                    if ($user['id'] === $uid) {
                        $user['password_hash'] = password_hash($pw, PASSWORD_BCRYPT, ['cost' => 12]);
                        $user['temp_password'] = $pw;  // master can see the password they just set
                        $found = true;
                        $success = "Password reset for <strong>" . h($user['username']) . "</strong>.";
                        break;
                    }
                }
                unset($user);
                if ($found) admin_save_config($cfg);
                else $error = 'User not found.';
            }
        }

        // Toggle active/inactive
        if ($action === 'toggle_active') {
            $uid = $_POST['user_id'] ?? '';
            $cfg = admin_get_config();
            foreach ($cfg['users'] as &$user) {
                if ($user['id'] === $uid && $user['id'] !== 'master') {
                    $user['is_active'] = !($user['is_active'] ?? true);
                    $success = "User <strong>" . h($user['username']) . "</strong> " . ($user['is_active'] ? 'activated' : 'deactivated') . ".";
                    break;
                }
            }
            unset($user);
            admin_save_config($cfg);
        }

        // Delete user (not master)
        if ($action === 'delete_user') {
            $uid = $_POST['user_id'] ?? '';
            $cfg = admin_get_config();
            $before = count($cfg['users']);
            $cfg['users'] = array_values(array_filter($cfg['users'], fn($u) => $u['id'] !== $uid || $u['id'] === 'master'));
            if (count($cfg['users']) < $before) {
                admin_save_config($cfg);
                $success = 'User deleted.';
            } else {
                $error = 'Could not delete user (master account cannot be deleted).';
            }
        }
    }
}

$users = admin_all_users();

include __DIR__ . '/_layout-top.php';
?>

<?php if ($error): ?>
  <div style="background:#fff0f0;border:1px solid #ffcdd2;border-radius:var(--radius);padding:12px 16px;margin-bottom:16px;color:var(--danger);font-size:.875rem;"><?= $error ?></div>
<?php endif; ?>
<?php if ($success): ?>
  <div style="background:#f0fff4;border:1px solid #c3e6cb;border-radius:var(--radius);padding:12px 16px;margin-bottom:16px;color:var(--success);font-size:.875rem;"><?= $success ?></div>
<?php endif; ?>

<!-- Create New User -->
<div class="card">
  <div class="card-header">
    <h2>👤 Create New User</h2>
  </div>
  <p style="font-size:.85rem;color:#666;margin-bottom:16px;">
    Create a new editor account. Set an initial password and share it with them — they can change it after first login.
    Their initial password will remain visible here until they change it themselves.
  </p>
  <form method="POST" autocomplete="off">
    <input type="hidden" name="action" value="create_user">
    <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label" for="new_username">Username</label>
        <input type="text" id="new_username" name="username" class="form-control"
               placeholder="e.g. john_editor" required
               pattern="[a-zA-Z0-9_]{3,32}" title="3–32 chars: letters, numbers, underscore">
        <small style="color:#999;font-size:.78rem;">3–32 characters. Letters, numbers, underscore only.</small>
      </div>
      <div class="form-group">
        <label class="form-label" for="init_password">Initial Password</label>
        <input type="text" id="init_password" name="password" class="form-control"
               placeholder="Set their first password" required minlength="8">
        <small style="color:#999;font-size:.78rem;">Minimum 8 characters. Share this with the user.</small>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Create User</button>
  </form>
</div>

<!-- All Users Table -->
<div class="card">
  <div class="card-header">
    <h2>👥 All Users (<?= count($users) ?>)</h2>
  </div>

  <div style="overflow-x:auto;">
    <table class="user-table">
      <thead>
        <tr>
          <th>Username</th>
          <th>Role</th>
          <th>Status</th>
          <th>Created</th>
          <th>Last Login</th>
          <th>Password (initial / reset)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
          <td><strong><?= h($user['username']) ?></strong></td>
          <td><span class="badge-role <?= h($user['role']) ?>"><?= h($user['role']) ?></span></td>
          <td>
            <?php $active = $user['is_active'] ?? true; ?>
            <span class="<?= $active ? 'badge-active' : 'badge-inactive' ?>"></span>
            <?= $active ? 'Active' : 'Inactive' ?>
          </td>
          <td style="font-size:.82rem;color:#666;"><?= h($user['created_at'] ?? '—') ?></td>
          <td style="font-size:.82rem;color:#666;"><?= $user['last_login'] ? h($user['last_login']) : '<span style="color:#ccc;">Never</span>' ?></td>
          <td>
            <?php if (!empty($user['temp_password'])): ?>
              <div class="pass-reveal">
                <input type="password" value="<?= h($user['temp_password']) ?>" id="pw_<?= h($user['id']) ?>" readonly>
                <button type="button" class="btn btn-secondary btn-sm" onclick="togglePw('<?= h($user['id']) ?>')">Show</button>
              </div>
            <?php elseif ($user['id'] === 'master'): ?>
              <span style="font-size:.78rem;color:#999;">Master account</span>
            <?php else: ?>
              <span style="font-size:.78rem;color:#999;">Changed by user</span>
            <?php endif; ?>
          </td>
          <td>
            <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
              <!-- Reset Password -->
              <button type="button" class="btn btn-secondary btn-sm"
                      onclick="openResetModal('<?= h($user['id']) ?>', '<?= h($user['username']) ?>')">
                Reset PW
              </button>
              <?php if ($user['id'] !== 'master'): ?>
              <!-- Toggle Active -->
              <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="toggle_active">
                <input type="hidden" name="user_id" value="<?= h($user['id']) ?>">
                <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">
                <button type="submit" class="btn btn-secondary btn-sm"
                        onclick="return confirm('<?= $active ? 'Deactivate' : 'Activate' ?> this user?')">
                  <?= $active ? 'Deactivate' : 'Activate' ?>
                </button>
              </form>
              <!-- Delete -->
              <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="delete_user">
                <input type="hidden" name="user_id" value="<?= h($user['id']) ?>">
                <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">
                <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Permanently delete user <?= h($user['username']) ?>? This cannot be undone.')">
                  Delete
                </button>
              </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Reset Password Modal -->
<div id="resetModal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius);padding:28px;width:100%;max-width:400px;box-shadow:var(--shadow);">
    <h3 style="margin-bottom:16px;font-size:1rem;">Reset Password for <span id="resetUsername" style="color:var(--primary);"></span></h3>
    <form method="POST" id="resetForm">
      <input type="hidden" name="action" value="reset_password">
      <input type="hidden" name="user_id" id="resetUserId">
      <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">
      <div class="form-group">
        <label class="form-label">New Password</label>
        <input type="text" name="new_password" id="resetPwInput" class="form-control"
               placeholder="Enter new password" required minlength="8">
        <small style="color:#999;font-size:.78rem;">Min 8 characters. You'll be able to see it in the table.</small>
      </div>
      <div style="display:flex;gap:10px;margin-top:16px;">
        <button type="submit" class="btn btn-primary">Set Password</button>
        <button type="button" class="btn btn-secondary" onclick="closeResetModal()">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
function togglePw(id) {
  const inp = document.getElementById('pw_' + id);
  const btn = inp.nextElementSibling;
  if (inp.type === 'password') { inp.type = 'text'; btn.textContent = 'Hide'; }
  else { inp.type = 'password'; btn.textContent = 'Show'; }
}

function openResetModal(id, username) {
  document.getElementById('resetUserId').value  = id;
  document.getElementById('resetUsername').textContent = username;
  document.getElementById('resetPwInput').value = '';
  const modal = document.getElementById('resetModal');
  modal.style.display = 'flex';
}
function closeResetModal() {
  document.getElementById('resetModal').style.display = 'none';
}
document.getElementById('resetModal').addEventListener('click', function(e) {
  if (e.target === this) closeResetModal();
});
</script>

<?php include __DIR__ . '/_layout-bottom.php'; ?>
