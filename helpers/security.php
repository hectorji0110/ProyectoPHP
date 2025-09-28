<?php
// start_secure_session()
function start_secure_session() {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax' // o 'Strict' según tu caso
    ]);
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // timeout simple
    $timeout = 60 * 60; // 60 min
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        session_unset();
        session_destroy();
        session_start();
    }
    $_SESSION['last_activity'] = time();
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function esc($str) {
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function is_valid_password($pwd) {
    // min 8 chars, 1 mayúsc, 1 minúsc, 1 número o carácter especial
    return preg_match('/^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).*$/', $pwd);
}

function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}