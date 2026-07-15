<?php

/**
 * Proxy front controller for running Laravel in a XAMPP subfolder.
 *
 * Keeps REQUEST_URI intact and sets SCRIPT_NAME so that Laravel's Symfony
 * Request component derives the correct base path (/resume-builder).
 * This way route matching AND URL generation both work automatically.
 */

$_SERVER['SCRIPT_NAME']     = '/resume-builder/index.php';
$_SERVER['PHP_SELF']        = '/resume-builder/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/public/index.php';

require __DIR__ . '/public/index.php';
