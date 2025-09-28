<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/security.php';
require_once __DIR__ . '/../app/Database.php';

$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 12;
$offset = ($page - 1) * $perPage;

$buscar = trim($_GET['buscar'] ?? '');
$min = (float)($_GET['min'] ?? 0);
$max = (float)($_GET['max'] ?? 0);

$db = Database::getInstance()->getConnection();

// Construir consulta dinámica
$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM productos WHERE estado='activo'";
$params = [];

if ($buscar !== '') {
    $sql .= " AND nombre LIKE ?";
    $params[] = "%$buscar%";
}

if ($min > 0) {
    $sql .= " AND precio >= ?";
    $params[] = $min;
}

if ($max > 0) {
    $sql .= " AND precio <= ?";
    $params[] = $max;
}

$sql .= " ORDER BY fecha_creacion DESC LIMIT ? OFFSET ?";
$params[] = $perPage;
$params[] = $offset;

$stmt = $db->prepare($sql);

// bind dinámico
foreach ($params as $i => $p) {
    $type = is_int($p) || is_float($p) ? PDO::PARAM_INT : PDO::PARAM_STR;
    $stmt->bindValue($i+1, $p, $type);
}

$stmt->execute();
$productos = $stmt->fetchAll();

$total = (int)$db->query("SELECT FOUND_ROWS()")->fetchColumn();
$totalPages = ceil($total / $perPage);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Catálogo de Productos</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="max-w-6xl mx-auto p-6">
  <div class="flex justify-between items-center mb-6">
  <h1 class="text-3xl font-bold text-indigo-600">Catalago de Productos</h1>
  <a href="../public/logout.php" 
    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
    Cerrar sesión
  </a>
</div>

    <!-- Buscador / Filtros -->
    <form method="get" class="mb-6 flex gap-4 flex-wrap">
      <input type="text" name="buscar" placeholder="Buscar por nombre"
        value="<?= htmlspecialchars($buscar) ?>"
        class="px-4 py-2 border rounded w-60">
      <input type="number" name="min" placeholder="Precio mínimo"
        value="<?= htmlspecialchars($min) ?>"
        class="px-4 py-2 border rounded w-32">
      <input type="number" name="max" placeholder="Precio máximo"
        value="<?= htmlspecialchars($max) ?>"
        class="px-4 py-2 border rounded w-32">
      <button class="bg-blue-600 text-white px-4 py-2 rounded">Filtrar</button>
    </form>

    <!-- Grid productos -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php foreach ($productos as $p): ?>
  <div class="border p-4 rounded mb-3">
    <h3 class="font-bold"><?php echo htmlspecialchars($p['nombre']); ?></h3>
    <p>$<?php echo number_format($p['precio'], 2); ?> | Stock: <?php echo $p['stock']; ?></p>
    <!-- Nueva descripción -->
        <?php if (!empty($p['descripcion'])): ?>
          <p class="text-gray-700 mb-2"><?= nl2br(htmlspecialchars($p['descripcion'])); ?></p>
        <?php endif; ?>
    <?php if (!empty($p['imagen'])): ?>
      <img src="../public/<?php echo htmlspecialchars($p['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($p['nombre']); ?>" width="150">
    <?php endif; ?>
  </div>
<?php endforeach; ?>
    </div>

    <!-- Paginación -->
    <div class="flex justify-center mt-6 gap-2">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>&buscar=<?= urlencode($buscar) ?>&min=<?= $min ?>&max=<?= $max ?>"
           class="px-3 py-1 border rounded <?= $i == $page ? 'bg-indigo-600 text-white' : 'bg-white' ?>">
          <?= $i ?>
        </a>
      <?php endfor; ?>
    </div>
  </div>
</body>
</html>