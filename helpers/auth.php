<?php
function require_admin() {
    session_start();
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        die("🚫 Acceso denegado. Solo administradores.");
    }
}