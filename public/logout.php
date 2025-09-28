<?php
require_once __DIR__ . '/../helpers/security.php';
start_secure_session();
session_unset();
session_destroy();
header('Location: /ProyectoPHP/public/login.php');
exit;