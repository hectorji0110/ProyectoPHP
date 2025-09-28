<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/security.php';
start_secure_session();
$csrf = generate_csrf_token();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Registro</title></head>
<script src="https://cdn.tailwindcss.com"></script>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
    <h1 class="text-2xl font-bold text-center text-indigo-600 mb-6">Registro</h1>

    <!-- Mensajes -->
    <?php if (!empty($_SESSION['success'])): ?>
      <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
        <?= $_SESSION['success']; ?>
        <a href="login.php" class="text-blue-600 font-semibold hover:underline ml-2">Iniciar sesión</a>
      </div>
      <?php unset($_SESSION['success']); ?>
    <?php elseif (!empty($_SESSION['error'])): ?>
      <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
        <?= $_SESSION['error']; ?>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Formulario -->
    <form action="register_process.php" method="post" class="space-y-4">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

      <div>
        <label class="block text-gray-700 font-medium mb-1">Nombre</label>
        <input name="nombre" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none">
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Email</label>
        <input name="email" type="email" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none">
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Contraseña</label>
        <input name="password" type="password" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none">
      </div>
      <!-- Link registro -->
    <p class="mt-6 text-center text-gray-600 text-sm">
      ¿Ya tienes cuenta?
      <a href="login.php" class="text-blue-600 font-semibold hover:underline">Inicia sesion aquí</a>
    </p>

      <button
        class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition">
        Registrarme
      </button>
    </form>
  </div>
</body>
</html>