<?php
// Router script for PHP built-in development server
// This handles routing for the development server and ignores .htaccess directives

// Get the requested URI
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Remove any base path if present
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($scriptDir !== '' && $scriptDir !== '/' && str_starts_with($uri, $scriptDir)) {
    $uri = substr($uri, strlen($scriptDir)) ?: '/';
}

// Check if the request is for a static file in the public directory
$publicPath = __DIR__ . '/public' . $uri;

// Serve static files directly (CSS, JS, images, etc.)
if ($uri !== '/' && file_exists($publicPath) && is_file($publicPath)) {
    // Let PHP's built-in server handle static files
    return false;
}

// For all other requests, route through index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/public/index.php';
