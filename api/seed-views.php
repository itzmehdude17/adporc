<?php
// One-time seeder script
// Reads sitemap.xml and seeds initial view counts for existing blog pages
// Intended to be run once, then deleted

header('Content-Type: application/json; charset=utf-8');

$initialSeed = 400;

// Path to views storage
$viewsFile = __DIR__ . '/views.json';

// Possible sitemap locations
$sitemapCandidates = [
  $_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml',
  dirname(__DIR__) . '/sitemap.xml',
];

$sitemapPath = null;
foreach ($sitemapCandidates as $path) {
  if (is_file($path)) {
    $sitemapPath = $path;
    break;
  }
}

if (!$sitemapPath) {
  http_response_code(500);
  echo json_encode([
    'ok' => false,
    'error' => 'sitemap.xml not found',
    'checked_paths' => $sitemapCandidates
  ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
  exit;
}

// Load existing views.json if present
$views = [];
if (is_file($viewsFile)) {
  $raw = file_get_contents($viewsFile);
  $decoded = json_decode($raw, true);
  if (is_array($decoded)) {
    $views = $decoded;
  }
}

// Parse sitemap (namespace-aware)
libxml_use_internal_errors(true);
$xml = simplexml_load_file($sitemapPath);
if (!$xml) {
  http_response_code(500);
  echo json_encode([
    'ok' => false,
    'error' => 'Failed to parse sitemap.xml'
  ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
  exit;
}

$namespaces = $xml->getNamespaces(true);
if (isset($namespaces[''])) {
  $xml->registerXPathNamespace('sm', $namespaces['']);
  $locNodes = $xml->xpath('//sm:url/sm:loc');
} else {
  $locNodes = $xml->xpath('//url/loc');
}

$foundBlogs = 0;
$seeded = 0;
$skipped = 0;

if ($locNodes) {
  foreach ($locNodes as $node) {
    $url = trim((string)$node);
    if (!$url) continue;

    $path = parse_url($url, PHP_URL_PATH);
    if (!$path) continue;

    // Only seed individual blog detail pages
    if (strpos($path, '/blogs/') === 0 && $path !== '/blogs/') {
      $foundBlogs++;

      if (!isset($views[$path])) {
        $views[$path] = $initialSeed;
        $seeded++;
      } else {
        $skipped++;
      }
    }
  }
}

// Save updated views.json
$json = json_encode($views, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
if ($json === false) {
  http_response_code(500);
  echo json_encode([
    'ok' => false,
    'error' => 'JSON encoding failed'
  ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
  exit;
}

if (file_put_contents($viewsFile, $json, LOCK_EX) === false) {
  http_response_code(500);
  echo json_encode([
    'ok' => false,
    'error' => 'Unable to write views.json (check file permissions)'
  ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
  exit;
}

echo json_encode([
  'ok' => true,
  'initial_seed' => $initialSeed,
  'blogs_found_in_sitemap' => $foundBlogs,
  'newly_seeded' => $seeded,
  'already_existing' => $skipped,
  'total_records' => count($views)
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
