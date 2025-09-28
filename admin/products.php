<?php
require_once __DIR__ . '/../helpers/auth.php';
require_admin();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Database.php';

$db = Database::getInstance()->getConnection();
$productos = $db->query("SELECT * FROM productos ORDER BY fecha_creacion DESC")->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel Admin - Productos</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <div class="flex justify-between items-center mb-6">
  <h1 class="text-3xl font-bold text-indigo-600">Panel de Administración - Productos</h1>
  <a href="../public/logout.php" 
    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
    Cerrar sesión
  </a>
</div>
  
  <a href="crear_producto.php" class="bg-green-600 text-white px-4 py-2 rounded">+ Crear Producto</a>
  

  <table class="w-full mt-6 bg-white shadow-md rounded">
    <thead class="bg-gray-200">
      <tr>
        <th class="px-4 py-2">ID</th>
        <th class="px-4 py-2">Nombre</th>
        <th class="px-4 py-2">Precio</th>
        <th class="px-4 py-2">Stock</th>
        <th class="px-4 py-2">Acciones</th>
        <th class="px-4 py-2">Imagen</th> <!-- Nueva columna -->
        <th class="px-4 py-2">Descripción</th> <!-- Nueva columna -->
      </tr>
    </thead>
    <tbody>
      <?php foreach ($productos as $p): ?>
      <tr class="border-t text-center ">
        <td class="px-4 py-2"><?= $p['id'] ?></td>
        <td class="px-4 py-2"><?= htmlspecialchars($p['nombre']) ?></td>
        <td class="px-4 py-2">$<?= number_format($p['precio'], 2) ?></td>
        <td class="px-4 py-2"><?= $p['stock'] ?></td>
        <td class="px-4 py-2">
          <a href="editar_producto.php?id=<?= $p['id'] ?>" class="bg-blue-500 text-white px-2 py-1 rounded">Editar</a>
          <a href="eliminar_producto.php?id=<?= $p['id'] ?>" class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
        </td>
              <!-- Mostrar la imagen -->
      <td class="px-4 py-2">
        <?php if (!empty($p['imagen'])): ?>
          <img src="../public/<?= htmlspecialchars($p['imagen']) ?>" 
              alt="Imagen de <?= htmlspecialchars($p['nombre']) ?>" 
              class="w-16 h-16 object-cover rounded">
        <?php else: ?>
          <span class="text-gray-400">Sin imagen</span>
        <?php endif; ?>
      </td>
       <td class="px-4 py-2 text-sm text-gray-700">
          <?= htmlspecialchars($p['descripcion'] ?? 'Sin descripción') ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>