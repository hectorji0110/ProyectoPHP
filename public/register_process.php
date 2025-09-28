<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/security.php';
require_once __DIR__ . '/../app/Database.php';
start_secure_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); exit;
}
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    die('CSRF token inválido');
}

$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!is_valid_email($email)) {
    die('Email inválido');
}
if (!is_valid_password($password)) {
    die('Contraseña no cumple requisitos (mín 8, 1 may, 1 min, 1 num/char)');
}

// comprobar email único
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    die('Email ya registrado');
}

// insertar usuario
$hash = password_hash($password, PASSWORD_DEFAULT);
$insert = $db->prepare("INSERT INTO usuarios (nombre, email, password_hash) VALUES (?, ?, ?)");
$insert->execute([$nombre, $email, $hash]);

// Guardar mensaje de éxito en sesión
$_SESSION['success'] = "✅ Registro exitoso. Ahora puedes iniciar sesión.";

// Redirigir de vuelta al formulario
header("Location: register.php");
exit;