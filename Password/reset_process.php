<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Database.php';

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';

if (!$token || !$password) die("⚠️ Datos incompletos");

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT id FROM usuarios WHERE reset_token=? AND reset_expira > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) die("⚠️ Token inválido o expirado");

$hash = password_hash($password, PASSWORD_DEFAULT);

// Actualizar y limpiar token
$stmt = $db->prepare("UPDATE usuarios SET password_hash=?, reset_token=NULL, reset_expira=NULL WHERE id=?");
$stmt->execute([$hash, $user['id']]);

echo "✅ Contraseña restablecida. <a href='../public/login.php'>Iniciar sesión</a>";