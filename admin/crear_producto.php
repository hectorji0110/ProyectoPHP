<?php
require_once __DIR__ . '/../helpers/auth.php';
require_admin();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $descripcion = trim($_POST['descripcion']); // 🆕 Nuevo campo

    if ($precio <= 0 || $stock < 0) {
        die("⚠️ Precio debe ser mayor a 0 y stock no negativo");
    }

  $imagenRuta = null;

    if (!empty($_FILES['imagen']['name'])) {
        $directorio = __DIR__ . "/../public/uploads/";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreArchivo = time() . "_" . basename($_FILES['imagen']['name']);
        $rutaCompleta = $directorio . $nombreArchivo;
        $imagenRuta = "uploads/" . $nombreArchivo; // esta ruta es la que guardamos en la BD

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
            die("⚠️ Error al subir la imagen");
        }
    }

    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("INSERT INTO productos (nombre, precio, stock, descripcion, estado, imagen) VALUES (?, ?, ?, ?, 'activo', ?)");
    $stmt->execute([$nombre, $precio, $stock, $descripcion, $imagenRuta]);

    header("Location: products.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <form method="post" enctype="multipart/form-data" class="p-6 bg-white shadow rounded">
  <input type="text" name="nombre" placeholder="Nombre" required class="border p-2 block mb-3">
  <input type="number" step="0.01" name="precio" placeholder="Precio" required class="border p-2 block mb-3">
  <input type="number" name="stock" placeholder="Stock" required class="border p-2 block mb-3">
   <!-- 🆕 Campo descripción -->
  <textarea name="descripcion" placeholder="Descripción del producto" rows="4" required 
            class="border p-2 block mb-3 w-full"></textarea>

  <!-- Nuevo campo imagen -->
  <input type="file" name="imagen" accept="image/*" class="border p-2 block mb-3">

  <button class="bg-green-600 text-white px-4 py-2 rounded">Guardar</button>
</form>
</form>
</body>
</html>



