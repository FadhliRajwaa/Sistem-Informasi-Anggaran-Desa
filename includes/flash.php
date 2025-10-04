<?php
// Simple flash message helper using session
require_once __DIR__ . '/session.php';

if (!function_exists('flash_set')) {
    function flash_set(string $type, string $title, string $message): void {
        $_SESSION['flash'] = [
            'type' => $type,       // success | error | warning | info
            'title' => $title,
            'message' => $message,
        ];
    }
}

if (!function_exists('flash_get')) {
    function flash_get(): array {
        $f = $_SESSION['flash'] ?? [];
        if (!empty($f)) {
            unset($_SESSION['flash']);
        }
        return $f;
    }
}
