{{--
  Vista: auth/register.blade.php
  Mostrada por GET /register. Envía POST a /register (register.perform).

  Campos:
   - name, email, password, password_confirmation
   - El nombre password_confirmation es FIJO: lo espera la regla 'confirmed'
     del validador en el backend (debe coincidir con 'password').

  Seguridad:
   - @csrf obligatorio (anti-CSRF).
   - 'role' NO se envía desde aquí. El usuario no puede auto-asignarse rol
     admin desde el formulario aunque inyecte un campo extra: el backend
     solo lee name/email/password al hacer User::create().
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Registrarse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white dark:bg-[#161615] rounded-3xl shadow-xl p-8">
        <h1 class="text-2xl font-semibold mb-6">Crear cuenta</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-700 dark:bg-red-900/10 dark:text-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.perform') }}" class="space-y-4">
            @csrf

            <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                Nombre completo
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="mt-1 block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-[#1b1b18] shadow-sm focus:border-[#f53003] focus:outline-none focus:ring-2 focus:ring-[#f53003]/20 dark:border-[#3E3E3A] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                />
            </label>

            <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                Correo electrónico
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
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

            <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                Confirmar contraseña
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="mt-1 block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-[#1b1b18] shadow-sm focus:border-[#f53003] focus:outline-none focus:ring-2 focus:ring-[#f53003]/20 dark:border-[#3E3E3A] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                />
            </label>

            <button type="submit" class="w-full rounded-xl bg-[#1b1b18] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#2a2a2a]">
                Registrarme
            </button>
        </form>

        <p class="mt-6 text-sm text-[#706f6c] dark:text-[#A1A09A]">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="font-semibold text-[#f53003] hover:underline">Inicia sesión</a>
        </p>
    </div>
</body>
</html>
