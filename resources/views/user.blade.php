{{--
  Vista: user.blade.php
  Mostrada en GET /user (ruta dentro del grupo 'auth'). Recibe la variable
  $user desde la ruta:
      Route::get('/user', fn () => view('user', ['user' => auth()->user()]));

  Detalles Blade:
   - {{ $user->name }} usa escape automático → seguro contra XSS aunque el
     usuario haya elegido un nombre con HTML/JS dentro.
   - $user->created_at es un objeto Carbon (gracias al cast 'datetime'
     definido en App\Models\User), por eso podemos llamar a ->format().
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white dark:bg-[#161615] rounded-3xl shadow-xl p-8">
        <h1 class="text-2xl font-semibold mb-4">Información de usuario</h1>

        <div class="space-y-3 rounded-3xl border border-gray-200 bg-gray-50 p-6 dark:border-[#3E3E3A] dark:bg-[#0a0a0a]/80">
            <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]"><strong>Nombre:</strong> {{ $user->name }}</p>
            <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]"><strong>Correo:</strong> {{ $user->email }}</p>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]"><strong>Registrado:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-between">
            <a href="{{ route('dashboard') }}" class="inline-block rounded-xl border border-gray-200 px-5 py-3 text-sm font-semibold text-[#1b1b18] hover:bg-gray-100 dark:border-[#3E3E3A] dark:hover:bg-white/5">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-xl bg-[#f53003] px-5 py-3 text-sm font-semibold text-white hover:bg-[#d62820]">Cerrar sesión</button>
            </form>
        </div>
    </div>
</body>
</html>
