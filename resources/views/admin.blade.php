{{--
  Vista: admin.blade.php
  Mostrada en GET /admin. La ruta está protegida por DOS middlewares en
  cascada (defensa en profundidad):
     1) 'auth'      → exige sesión iniciada (si no, redirige a /login).
     2) 'can:admin' → exige que el Gate 'admin' devuelva true (si no, 403).
  Por tanto, llegar a esta vista YA garantiza que el usuario es admin.
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel admin - VIU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] flex items-center justify-center p-6">
    <div class="w-full max-w-xl bg-white dark:bg-[#161615] rounded-3xl shadow-xl p-8">
        <p class="text-xs uppercase tracking-wide text-[#f53003] font-semibold">Zona restringida</p>
        <h1 class="text-3xl font-semibold mt-1">Panel de administración</h1>
        <p class="mt-3 text-sm text-[#706f6c] dark:text-[#A1A09A]">
            Solo accesible para usuarios con rol <code class="px-1 rounded bg-gray-100 dark:bg-[#0a0a0a]">admin</code>.
        </p>

        <div class="mt-6 p-4 rounded-xl bg-gray-50 dark:bg-[#0a0a0a]/80 border border-gray-200 dark:border-[#3E3E3A] text-sm">
            <p><strong>Usuario actual:</strong> {{ auth()->user()->name }}</p>
            <p><strong>Rol:</strong> {{ auth()->user()->role }}</p>
        </div>

        <a href="{{ route('dashboard') }}"
           class="inline-block mt-6 text-sm text-[#706f6c] dark:text-[#A1A09A] hover:underline">
            ← Volver al dashboard
        </a>
    </div>
</body>
</html>
