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
    // Actualizar producto
    $id = (int)($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $precio = (float)($_POST['precio'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $descripcion = trim($_POST['descripcion'] ?? ''); // 🆕 Nuevo campo

    if ($nombre === '' || $precio <= 0 || $stock < 0) {
        die("Datos inválidos");
    }

        // Obtener la imagen actual
    $stmt = $db->prepare("SELECT imagen FROM productos WHERE id=?");
    $stmt->execute([$id]);
    $productoActual = $stmt->fetch();
    $imagenActual = $productoActual['imagen'] ?? null;

    // Procesar nueva imagen si existe
    if (!empty($_FILES['imagen']['name'])) {
        $nombreImagen = time() . "_" . basename($_FILES['imagen']['name']);
        $rutaDestino = __DIR__ . "/../public/" . $nombreImagen;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            // Borrar imagen vieja si existe
            if ($imagenActual && file_exists(__DIR__ . "/../public/" . $imagenActual)) {
                unlink(__DIR__ . "/../public/" . $imagenActual);
            }
            $imagenActual = $nombreImagen;
        }
    }

    $stmt = $db->prepare("UPDATE productos SET nombre=?, precio=?, stock=?, descripcion=?, imagen=? WHERE id=?");
    $stmt->execute([$nombre, $precio, $stock, $descripcion, $imagenActual, $id]);

    header("Location: products.php?msg=Producto actualizado");
    exit;
}

// Mostrar producto
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
<head><meta charset="utf-8"><title>Editar Producto</title></head>
<body>
<h1>Editar Producto</h1>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $producto['id'] ?>">

    <label>Nombre:
        <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>">
    </label><br>

    <label>Precio:
        <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>">
    </label><br>

    <label>Stock:
        <input type="number" name="stock" value="<?= $producto['stock'] ?>">
    </label><br>

       <!-- 🆕 Campo descripción -->
    <label>Descripción:<br>
        <textarea name="descripcion" rows="4" cols="40"><?= htmlspecialchars($producto['descripcion']) ?></textarea>
    </label><br>

    <label>Imagen actual:<br>
    <?php if (!empty($producto['imagen'])): ?>
    <img src="../public/<?= htmlspecialchars($producto['imagen']) ?>"  
        alt="Imagen de <?= htmlspecialchars($producto['nombre']) ?>" 
        class="w-16 h-16 object-cover rounded">
<?php else: ?>
<em>Sin imagen</em><br>
<?php endif; ?>
    </label><br>

    <label>Nueva imagen (opcional):
        <input type="file" name="imagen" accept="image/*">
    </label><br>

    <button type="submit">Guardar cambios</button>
</form>
</body>
</html>