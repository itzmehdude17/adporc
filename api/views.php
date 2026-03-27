<?php
// Blog page view counter API
// GET  /api/views.php?slug=/blogs/some-blog  → increments count, returns { ok: true, count: N }
// GET  /api/views.php                        → returns all counts { ok: true, views: {...} }

header('Content-Type: application/json; charset=utf-8');

$viewsFile = __DIR__ . '/views.json';

// Load existing views
$views = [];
if (is_file($viewsFile)) {
    $raw = file_get_contents($viewsFile);
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) $views = $decoded;
}

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

// If no slug, return all views (for admin/dashboard use)
if ($slug === '') {
    echo json_encode(['ok' => true, 'views' => $views], JSON_UNESCAPED_SLASHES);
    exit;
}

// Sanitize: only allow /blogs/... paths
$slug = rtrim($slug, '/');
if (strpos($slug, '/blogs/') !== 0 || $slug === '/blogs/') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid slug']);
    exit;
}

// Increment view count
if (!isset($views[$slug])) {
    $views[$slug] = 1;
} else {
    $views[$slug]++;
}

// Save back to file
$json = json_encode($views, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
if ($json !== false) {
    file_put_contents($viewsFile, $json, LOCK_EX);
}

echo json_encode(['ok' => true, 'count' => $views[$slug]], JSON_UNESCAPED_SLASHES);
