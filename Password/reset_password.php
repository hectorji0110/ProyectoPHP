<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Database.php';

$token = $_GET['token'] ?? '';
if (!$token) die("Token inválido");

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT id, reset_expira FROM usuarios WHERE reset_token=?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user || strtotime($user['reset_expira']) < time()) {
    die("⚠️ Token inválido o expirado");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>REcuperar contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<form method="post" action="reset_process.php" class="p-6 bg-white shadow rounded">
  <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
  <input type="password" name="password" placeholder="Nueva contraseña" required class="border p-2 block mb-3">
  <button class="bg-green-600 text-white px-4 py-2 rounded">Restablecer</button>
</form>
</body>
</html>



