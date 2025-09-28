<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Olvido de clave</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <form action="forgot_process.php" method="post" class="max-w-md mx-auto mt-10 p-8 bg-white shadow-lg rounded-xl border border-gray-200">
  <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Recuperar contraseña</h2>

  <label for="email" class="block text-gray-700 font-medium mb-2">Correo electrónico</label>
  <input 
    type="email" 
    name="email" 
    id="email" 
    placeholder="Tu correo" 
    required 
    class="w-full px-4 py-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
  >

  <button 
    type="submit" 
    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg shadow-md transition"
  >
    Recuperar clave
  </button>
</form>
</body>
</html>