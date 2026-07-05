{{--
  Vista: auth/login.blade.php
  Mostrada por GET /login. Envía un POST a /login (login.perform).

  Puntos de seguridad a notar:
   - @csrf dentro del <form>: imprime un <input type="hidden" name="_token">
     con el token CSRF de la sesión. Sin él Laravel devuelve 419 y bloquea
     el envío. Es la defensa contra Cross-Site Request Forgery.
   - {{ $error }}: la doble llave de Blade ESCAPA el HTML (anti-XSS).
     Si quisiéramos pintar HTML crudo usaríamos {!! $error !!} — pero NO
     se debe hacer con datos que vengan del usuario.
   - value="{{ old('email') }}": al fallar el login, recupera el email
     escrito antes (mejora UX) sin volver a pedirlo. La contraseña NO se
     repuebla a propósito.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Iniciar sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white dark:bg-[#161615] rounded-3xl shadow-xl p-8">
        <h1 class="text-2xl font-semibold mb-6">Iniciar sesión</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-700 dark:bg-red-900/10 dark:text-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- method="POST" + @csrf  →  Laravel valida automáticamente el token.
             action="{{ route('login.perform') }}"  →  resolución por NOMBRE de ruta:
             si mañana cambiamos la URL, no hace falta tocar las vistas. --}}
        <form method="POST" action="{{ route('login.perform') }}" class="space-y-4">
            @csrf

            <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                Correo electrónico
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="mt-1 block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-[#1b1b18] shadow-sm focus:border-[#f53003] focus:outline-none focus:ring-2 focus:ring-[#f53003]/20 dark:border-[#3E3E3A] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                />
            </label>

            <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                Contraseña
                <input
                    type="password"
                    name="password"
                    required
                    class="mt-1 block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-[#1b1b18] shadow-sm focus:border-[#f53003] focus:outline-none focus:ring-2 focus:ring-[#f53003]/20 dark:border-[#3E3E3A] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                />
            </label>

            <label class="inline-flex items-center gap-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-[#f53003] focus:ring-[#f53003]/50" />
                Recordarme
            </label>

            <button type="submit" class="w-full rounded-xl bg-[#1b1b18] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#2a2a2a]">
                Entrar
            </button>
        </form>

        <p class="mt-6 text-sm text-[#706f6c] dark:text-[#A1A09A]">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="font-semibold text-[#f53003] hover:underline">Regístrate aquí</a>
        </p>
    </div>
</body>
</html>
