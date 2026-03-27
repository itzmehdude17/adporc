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
