<?php
// ============================================================
// includes/auth.php — Session Authentication Guard
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function loginAdmin($email, $name, $access_token) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_email'] = $email;
    $_SESSION['admin_name'] = $name;
    $_SESSION['access_token'] = $access_token;
    $_SESSION['login_time'] = time();
}

function logoutAdmin() {
    session_destroy();
    header('Location: /admin/login.php');
    exit;
}

function getAdminName() {
    return $_SESSION['admin_name'] ?? 'Admin';
}

function getAdminEmail() {
    return $_SESSION['admin_email'] ?? '';
}