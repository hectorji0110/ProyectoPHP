<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Database.php';
require_once __DIR__ . '/../helpers/security.php';
start_secure_session();

// Solo admin
if ($_SESSION['user_role'] !== 'admin') {
    die("Acceso denegado");
}

$db = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);

    $stmt = $db->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: products.php?msg=Producto eliminado");
    exit;
}

// Mostrar confirmación
$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch();

if (!$producto) {
    die("Producto no encontrado");
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Eliminar Producto</title></head>
<body>
<h1>¿Seguro que quieres eliminar este producto?</h1>
<p><strong><?= htmlspecialchars($producto['nombre']) ?></strong></p>

<form method="post">
    <input type="hidden" name="id" value="<?= $producto['id'] ?>">
    <button type="submit">Sí, eliminar</button>
    <a href="products.php">Cancelar</a>
</form>
</body>
</html>