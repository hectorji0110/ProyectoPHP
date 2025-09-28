<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Database.php';

$email = trim($_POST['email'] ?? '');
if (!$email) die("⚠️ Email requerido");

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    // Generar token seguro
    $token = bin2hex(random_bytes(32));
    $expira = date("Y-m-d H:i:s", time() + 3600); // 1 hora válido

    $stmt = $db->prepare("UPDATE usuarios SET reset_token=?, reset_expira=? WHERE id=?");
    $stmt->execute([$token, $expira, $user['id']]);

    // Crear enlace
    $link = "http://localhost/ProyectoPHP/Password/reset_password.php?token=$token";

    // ⚠️ Aquí deberías enviar email, de momento mostramos el link:
    echo "📧 Revisa tu correo. Enlace: <a href='$link'>$link</a>";
} else {
    echo "⚠️ Email no registrado";
}