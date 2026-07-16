<?php

/**
 * Front controller proxy for Laravel in a subfolder (cPanel / XAMPP).
 *
 * Auto-detects the URL base path from SCRIPT_NAME so the same code works at
 * /resume-builder/ or at the domain root when the docroot points here.
 */

$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/');
if ($basePath === '/' || $basePath === '.') {
    $basePath = '';
}

$scriptName = ($basePath === '' ? '' : $basePath).'/index.php';

$_SERVER['SCRIPT_NAME']     = $scriptName;
$_SERVER['PHP_SELF']        = $scriptName;
$_SERVER['SCRIPT_FILENAME'] = __DIR__.'/public/index.php';

require __DIR__.'/public/index.php';
