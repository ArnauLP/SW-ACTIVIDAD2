{{--
  Vista: dashboard.blade.php
  Mostrada en GET /dashboard, ruta protegida por middleware('auth').
  Si llegas aquí es porque tienes sesión iniciada.

  Helpers usados:
   - auth()->user()   →  modelo User actualmente autenticado.
   - @can('admin')    →  consulta el Gate 'admin' definido en
                         AppServiceProvider. Devuelve true solo si el
                         usuario tiene role='admin'.
   - {{ route('admin') }} / {{ route('logout') }}  →  resolución por
                         nombre de ruta (más mantenible que escribir /admin).
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex items-center justify-center p-6">
    <div class="w-full max-w-2xl bg-white dark:bg-[#161615] rounded-3xl shadow-xl p-8">
        <div class="flex flex-col gap-6">
            <div>
                <h1 class="text-3xl font-semibold">Bienvenido, {{ auth()->user()->name }}!</h1>
                <p class="mt-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">Has iniciado sesión correctamente.</p>
            </div>

            <div class="grid gap-4 rounded-3xl border border-gray-200 bg-gray-50 p-6 dark:border-[#3E3E3A] dark:bg-[#0a0a0a]/80">
                <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]"><strong>Nombre:</strong> {{ auth()->user()->name }}</p>
                <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]"><strong>Correo:</strong> {{ auth()->user()->email }}</p>
                <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]"><strong>Rol:</strong> {{ auth()->user()->role }}</p>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]"><strong>Registrado:</strong> {{ auth()->user()->created_at->format('d/m/Y H:i') }}</p>
            </div>

            {{-- Demostración de @can: el botón solo aparece si el Gate 'admin'
                 devuelve true. OJO: esto es UI, no es seguridad. La protección
                 real está en el middleware('can:admin') de la ruta /admin. --}}
            @can('admin')
                <a href="{{ route('admin') }}" class="inline-block rounded-xl bg-[#f53003] text-white px-5 py-3 text-sm font-semibold hover:bg-[#d62820] text-center">
                    Ir al panel de administración
                </a>
            @else
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] italic">
                    (Si fueras admin, aquí verías un botón hacia /admin. Prueba a entrar igualmente: te dará 403.)
                </p>
            @endcan

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <a href="/" class="inline-block rounded-xl border border-gray-200 px-5 py-3 text-sm font-semibold text-[#1b1b18] transition hover:bg-gray-100 dark:border-[#3E3E3A] dark:hover:bg-white/5">Volver al inicio</a>

                {{-- El logout va por POST + @csrf a propósito: así un atacante
                     no puede cerrarte la sesión con un simple <img src="/logout">. --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-[#f53003] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#d62820] sm:w-auto">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
