<?php
require_once __DIR__ . '/auth.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['ok' => false, 'error' => 'Method not allowed']));
}

admin_require_login();

if (!admin_verify_csrf()) {
    http_response_code(403);
    exit(json_encode(['ok' => false, 'error' => 'Invalid CSRF token']));
}

if (empty($_FILES['image'])) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'error' => 'No file uploaded']));
}

$file    = $_FILES['image'];
$maxSize = 5 * 1024 * 1024; // 5 MB

if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'error' => 'Upload error code: ' . $file['error']]));
}

if ($file['size'] > $maxSize) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'error' => 'File too large. Max 5 MB allowed.']));
}

// Verify it is actually an image by checking content
$imageInfo = @getimagesize($file['tmp_name']);
if ($imageInfo === false) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'error' => 'File is not a valid image']));
}

$allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
if (!in_array($imageInfo['mime'], $allowedMimes, true)) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'error' => 'Only JPG, PNG, WEBP, and GIF images are allowed']));
}

$mimeToExt = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
    'image/gif'  => 'gif',
];
$ext      = $mimeToExt[$imageInfo['mime']];
$safeName = bin2hex(random_bytes(8)) . '.' . $ext;

$uploadDir = dirname(__DIR__) . '/assets/images/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$dest = $uploadDir . $safeName;
if (!move_uploaded_file($file['tmp_name'], $dest)) {
    http_response_code(500);
    exit(json_encode(['ok' => false, 'error' => 'Failed to save file. Check folder permissions.']));
}

echo json_encode(['ok' => true, 'url' => '/assets/images/uploads/' . $safeName]);
