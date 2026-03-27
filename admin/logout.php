<?php
require_once __DIR__ . '/auth.php';

if (session_status() !== PHP_SESSION_NONE) {
    session_unset();
    session_destroy();
}

header('Location: /admin/index.php');
exit;
