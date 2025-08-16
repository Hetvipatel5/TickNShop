<?php
// auth.php
if (session_status() === PHP_SESSION_NONE) session_start();

function require_login_or_redirect() {
    if (!isset($_SESSION['user_id'])) {
        // where to return after login
        $dest = urlencode($_SERVER['REQUEST_URI']);
        header("Location: login.php?redirect=$dest");
        exit;
    }
}
