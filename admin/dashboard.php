<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$pageTitle = 'Dashboard';
$blogs  = read_json('blogs.json') ?: [];
$team   = read_json('team.json')  ?: [];
$faqs   = read_json('faqs.json')  ?: [];

include __DIR__ . '/_layout-top.php';
?>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background:#e8f5f5;">
      <svg viewBox="0 0 24 24" fill="none" stroke="#2b7a78" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    </div>
    <div>
      <div class="stat-value"><?= count($blogs) ?></div>
      <div class="stat-label">Blog Posts</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#e8f5f5;">
      <svg viewBox="0 0 24 24" fill="none" stroke="#2b7a78" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    </div>
    <div>
      <div class="stat-value"><?= count($team) ?></div>
      <div class="stat-label">Team Members</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#e8f5f5;">
      <svg viewBox="0 0 24 24" fill="none" stroke="#2b7a78" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    </div>
    <div>
      <div class="stat-value"><?= count($faqs) ?></div>
      <div class="stat-label">FAQs</div>
    </div>
  </div>
</div>

<h2 style="font-size:1rem;font-weight:600;margin-bottom:16px;color:var(--text-light);">QUICK EDIT SECTIONS</h2>
<div class="section-nav-grid">
  <a href="/admin/settings.php" class="section-nav-card">
    <div class="icon">⚙️</div>
    <h3>Site Settings</h3>
    <p>Contact info, social links, schedule</p>
  </a>
  <a href="/admin/home-editor.php" class="section-nav-card">
    <div class="icon">🏠</div>
    <h3>Home Page</h3>
    <p>Hero, services, about, CTA sections</p>
  </a>
  <a href="/admin/team-editor.php" class="section-nav-card">
    <div class="icon">👥</div>
    <h3>Team Members</h3>
    <p>Add, edit or remove team members</p>
  </a>
  <a href="/admin/faq-editor.php" class="section-nav-card">
    <div class="icon">❓</div>
    <h3>FAQs</h3>
    <p>Add, edit or remove FAQ items</p>
  </a>
  <a href="/admin/blogs-manager.php" class="section-nav-card">
    <div class="icon">📝</div>
    <h3>Blog Manager</h3>
    <p>Manage blog listing cards</p>
  </a>
  <a href="/admin/change-password.php" class="section-nav-card">
    <div class="icon">🔒</div>
    <h3>Change Password</h3>
    <p>Update your admin password</p>
  </a>
</div>

<?php include __DIR__ . '/_layout-bottom.php'; ?>
