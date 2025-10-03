<?php
require_once __DIR__ . '/session.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>
