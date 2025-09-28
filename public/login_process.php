<?php
// login.php (vista): formulario con csrf token igual que registro
// login_process.php:
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/security.php';
require_once __DIR__ . '/../app/Database.php';
start_secure_session();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) die('CSRF');

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT id, password_hash, rol, nombre FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();
if (!$user || !password_verify($password, $user['password_hash'])) {
    // opción: message genérico "email o contraseña inválida"
    die('Email o contraseña inválida');
}

// login exitoso: regenerar id de sesión y guardar info mínima
session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['nombre'];
$_SESSION['user_role'] = $user['rol'];

// 🔹 Redirigir según rol
if ($user['rol'] === 'admin') {
    header('Location: ../admin/products.php'); // CRUD
} else {
    header('Location: ../product/products.php'); // solo vista
}
exit;