<?php
// Shared admin layout — top of every protected page
// Usage: include_once this after requiring auth.php and calling admin_require_login()
// Set $pageTitle before including.
$pageTitle = $pageTitle ?? 'Dashboard';
$csrf = admin_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= h($pageTitle) ?> — ADPORC Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <meta name="csrf-token" content="<?= h($csrf) ?>">
  <link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body>
<div class="admin-layout">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <div class="logo">AD<span>PORC</span></div>
      <div class="sidebar-subtitle">Admin Panel</div>
    </div>
    <nav class="sidebar-nav">
      <a href="/admin/dashboard.php"       <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php'     ? 'class="active"' : '' ?>>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Dashboard
      </a>
      <a href="/admin/settings.php"        <?= basename($_SERVER['PHP_SELF']) === 'settings.php'      ? 'class="active"' : '' ?>>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
        Site Settings
      </a>
      <a href="/admin/home-editor.php"     <?= basename($_SERVER['PHP_SELF']) === 'home-editor.php'   ? 'class="active"' : '' ?>>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        Home Page
      </a>
      <a href="/admin/team-editor.php"     <?= basename($_SERVER['PHP_SELF']) === 'team-editor.php'   ? 'class="active"' : '' ?>>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        Team
      </a>
      <a href="/admin/faq-editor.php"      <?= basename($_SERVER['PHP_SELF']) === 'faq-editor.php'    ? 'class="active"' : '' ?>>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        FAQs
      </a>
      <a href="/admin/blogs-manager.php"   <?= basename($_SERVER['PHP_SELF']) === 'blogs-manager.php' ? 'class="active"' : '' ?>>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        Blogs
      </a>
      <a href="/admin/change-password.php" <?= basename($_SERVER['PHP_SELF']) === 'change-password.php' ? 'class="active"' : '' ?>>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        Change Password
      </a>
    </nav>
    <div class="sidebar-footer">
      <a href="/admin/logout.php">⏻ Sign Out</a>
    </div>
  </aside>

  <!-- Main area -->
  <div class="admin-main">
    <div class="admin-topbar">
      <span class="topbar-title"><?= h($pageTitle) ?></span>
      <div class="topbar-actions">
        <a href="/home" target="_blank" rel="noopener" class="btn-view-site">View Site ↗</a>
      </div>
    </div>
    <div class="admin-content">
