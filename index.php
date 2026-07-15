<?php

/**
 * Proxy front controller for running Laravel in a XAMPP subfolder.
 * Rewrites REQUEST_URI so Laravel sees clean paths (e.g. "/" instead of "/resume-builder/").
 */

$basePath = '/resume-builder';

$uri = $_SERVER['REQUEST_URI'] ?? '/';

// Strip the subfolder prefix so Laravel routes match
if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath)) ?: '/';
}

$_SERVER['REQUEST_URI']     = $uri;
$_SERVER['SCRIPT_NAME']     = '/index.php';
$_SERVER['PHP_SELF']        = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/public/index.php';

// Serve static files from public/ if they exist
$publicFile = __DIR__ . '/public' . parse_url($uri, PHP_URL_PATH);
if ($uri !== '/' && is_file($publicFile)) {
    return false;
}

// Boot Laravel
require __DIR__ . '/public/index.php';
