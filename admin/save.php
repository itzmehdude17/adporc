<?php
require_once __DIR__ . '/auth.php';

// Only accept POST, require auth and CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['ok' => false, 'error' => 'Method not allowed']));
}

header('Content-Type: application/json; charset=utf-8');

admin_require_login();

if (!admin_verify_csrf()) {
    http_response_code(403);
    exit(json_encode(['ok' => false, 'error' => 'Invalid CSRF token']));
}

$raw = file_get_contents('php://input');
$body = json_decode($raw, true);

if (!$body || empty($body['section'])) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'error' => 'Missing section']));
}

$section = $body['section'];
$data    = $body['data'] ?? null;

// Whitelist of allowed sections → target JSON file and merge key
$sectionMap = [
    'site'         => ['file' => 'site.json',  'key' => null],
    'home_hero'    => ['file' => 'home.json',   'key' => 'hero'],
    'home_about'   => ['file' => 'home.json',   'key' => 'about'],
    'home_cta'     => ['file' => 'home.json',   'key' => 'cta'],
    'home_services'=> ['file' => 'home.json',   'key' => 'services'],
    'team'         => ['file' => 'team.json',        'key' => null],
    'faqs'         => ['file' => 'faqs.json',        'key' => null],
    'blogs'        => ['file' => 'blogs.json',       'key' => null],
    'about_page'   => ['file' => 'about.json',       'key' => null],
    'services_page'=> ['file' => 'services_page.json','key' => null],
];

if (!array_key_exists($section, $sectionMap)) {
    // Handle blog_single: update one blog entry by serial position
    if ($section === 'blog_single') {
        if (!is_array($data) || !isset($data['serial'])) {
            http_response_code(400);
            exit(json_encode(['ok' => false, 'error' => 'Invalid blog data']));
        }
        $serial = (int)$data['serial'];
        $views = isset($data['views']) ? max(0, (int)$data['views']) : null;
        unset($data['serial'], $data['views']);
        $blogs = read_json('blogs.json') ?: [];
        $total = count($blogs);
        $index = $total - $serial; // serial N = index 0 (newest), serial 1 = last index
        if ($index < 0 || $index >= $total) {
            http_response_code(400);
            exit(json_encode(['ok' => false, 'error' => 'Blog not found at serial #' . $serial]));
        }
        $blogs[$index] = $data;
        $ok = write_json('blogs.json', $blogs);
        // Also update views if provided
        if ($ok && $views !== null && isset($data['slug'])) {
            $viewsFile = dirname(dirname(__DIR__)) . '/sitedata/views.json';
            $allViews = [];
            if (is_file($viewsFile)) {
                $decoded = json_decode(file_get_contents($viewsFile), true);
                if (is_array($decoded)) $allViews = $decoded;
            }
            $allViews['/blogs/' . $data['slug']] = $views;
            file_put_contents($viewsFile, json_encode($allViews, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), LOCK_EX);
        }
        if ($ok) {
            echo json_encode(['ok' => true, 'message' => 'Blog #' . $serial . ' saved!']);
        } else {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'Failed to write blogs.json']);
        }
        exit;
    }

    // Handle blog_views separately (stored in sitedata/, outside public_html)
    if ($section === 'blog_views') {
        if (!is_array($data)) {
            http_response_code(400);
            exit(json_encode(['ok' => false, 'error' => 'Invalid views data']));
        }
        $viewsFile = dirname(dirname(__DIR__)) . '/sitedata/views.json';
        // Sanitize: only allow /blogs/... keys with integer values
        $clean = [];
        foreach ($data as $slug => $count) {
            $slug = trim((string)$slug);
            if (strpos($slug, '/blogs/') === 0 && $slug !== '/blogs/') {
                $clean[$slug] = max(0, (int)$count);
            }
        }
        $json = json_encode($clean, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $ok = ($json !== false) && (file_put_contents($viewsFile, $json, LOCK_EX) !== false);
        if ($ok) {
            echo json_encode(['ok' => true, 'message' => 'Views saved!']);
        } else {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'Failed to write views.json']);
        }
        exit;
    }

    http_response_code(400);
    exit(json_encode(['ok' => false, 'error' => 'Unknown section']));
}

if ($data === null) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'error' => 'No data provided']));
}

$map  = $sectionMap[$section];
$file = $map['file'];
$key  = $map['key'];

if ($key !== null) {
    // Merge into existing JSON under a specific key
    $existing = read_json($file) ?: [];
    $existing[$key] = $data;
    $ok = write_json($file, $existing);
} else {
    // Replace the entire JSON file
    $ok = write_json($file, $data);
}

if ($ok) {
    echo json_encode(['ok' => true, 'message' => 'Saved successfully!']);
} else {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Failed to write file. Check folder permissions.']);
}
