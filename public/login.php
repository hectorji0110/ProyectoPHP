<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/security.php';

start_secure_session();
$csrf = generate_csrf_token();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Iniciar sesión</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md">
    <!-- Título -->
    <h1 class="text-2xl font-bold text-center text-blue-600 mb-6">
      Iniciar sesión
    </h1>

    <!-- Formulario -->
    <form action="login_process.php" method="post" class="space-y-4">
      <!-- CSRF -->
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

      <!-- Email -->
      <div>
        <label class="block text-gray-700 font-medium mb-1">Email</label>
        <input name="email" type="email" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none" />
      </div>

      <!-- Password -->
      <div>
        <label class="block text-gray-700 font-medium mb-1">Contraseña</label>
        <input name="password" type="password" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none" />
      </div>

      <!-- Botón -->
      <button
        class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-indigo-700 transition">
        Entrar
      </button>

       <!-- Botón recuperar contraseña -->
    <div class="text-center mt-4">
      <a href="../Password/forgot_password.php" 
         class="text-sm text-blue-600 hover:underline">
         ¿Olvidaste tu contraseña?
      </a>
    </div>
    </form>

    <!-- Link registro -->
    <p class="mt-6 text-center text-gray-600 text-sm">
      ¿No tienes cuenta?
      <a href="register.php" class="text-blue-600 font-semibold hover:underline">Regístrate aquí</a>
    </p>
  </div>

</body>
</html>