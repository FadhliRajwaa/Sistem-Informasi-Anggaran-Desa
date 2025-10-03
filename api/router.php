<?php
// Vercel PHP entry: route all requests to the right PHP file under project root
// This keeps your existing structure working without moving files into /api.

// Detect project root (parent of /api)
$root = dirname(__DIR__);

// Resolve request path
$reqPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if ($reqPath === '' || $reqPath === '/') {
    $target = $root . DIRECTORY_SEPARATOR . 'index.php';
} else {
    // Normalize path, prevent path traversal
    $path = '/' . ltrim($reqPath, '/');
    $candidate = realpath($root . $path);

    // If path ends without extension, try append index.php
    if (!$candidate) {
        $noTrail = rtrim($path, '/');
        $try = realpath($root . $noTrail . '/index.php');
        if ($try && strpos($try, $root) === 0) {
            $candidate = $try;
        }
    }

    // Accept only files under root
    if ($candidate && strpos($candidate, $root) === 0 && is_file($candidate)) {
        $target = $candidate;
    } else {
        // If request looks like a PHP file path under root
        $tryPhp = realpath($root . $path);
        if ($tryPhp && strpos($tryPhp, $root) === 0 && is_file($tryPhp)) {
            $target = $tryPhp;
        } else {
            http_response_code(404);
            echo '404 Not Found';
            exit;
        }
    }
}

// Change current working dir to the target's directory so relative includes work
chdir(dirname($target));

// Serve the target script
require $target;
