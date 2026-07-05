{{--
  Vista: errors/404.blade.php
  Laravel busca automáticamente las vistas en resources/views/errors/
  cuando se produce un HTTP status:
     - 404 → recurso no encontrado (abort(404) o ruta inexistente)
     - 403 → prohibido (gate/policy denegada)
     - 500 → error interno
  Si existe, sustituye la página por defecto. Es buena práctica de
  seguridad mostrar páginas propias para no filtrar stack traces ni
  versiones del framework.

  La variable $exception está disponible siempre que estamos en una vista
  de error y permite acceder al mensaje (sin volcar datos sensibles).
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Página no encontrada | VIU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white dark:bg-[#161615] rounded-3xl shadow-xl p-10 text-center">
        <p class="text-7xl font-black text-[#f53003] leading-none">404</p>
        <h1 class="text-2xl font-semibold mt-4">Página no encontrada</h1>
        <p class="mt-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
            {{ $exception->getMessage() ?: 'El recurso que buscas no existe o ha sido movido.' }}
        </p>

        <a href="/" class="inline-block mt-6 rounded-xl bg-[#1b1b18] dark:bg-white text-white dark:text-[#1b1b18] px-5 py-3 text-sm font-semibold hover:opacity-90">
            Volver al inicio
        </a>
    </div>
</body>
</html>
