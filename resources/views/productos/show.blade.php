{{--
  Vista: productos/show.blade.php
  Mostrada por ProductoController@show (GET /productos/{id}).
  Recibe $id (entero) y $producto (array con 'nombre' y 'precio').

  Si {id} no existe en el array, el controlador llama a abort(404), que
  dispara automáticamente la vista resources/views/errors/404.blade.php.
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $producto['nombre'] }} - VIU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] p-6">
    <div class="max-w-md mx-auto bg-white dark:bg-[#161615] rounded-3xl shadow-xl p-8">
        <p class="text-xs uppercase text-[#706f6c] dark:text-[#A1A09A]">Producto #{{ $id }}</p>
        <h1 class="text-2xl font-semibold mt-1">{{ $producto['nombre'] }}</h1>
        <p class="mt-4 text-3xl font-bold text-[#f53003]">
            {{ number_format($producto['precio'], 2, ',', '.') }} €
        </p>

        <a href="{{ route('productos.index') }}"
           class="inline-block mt-6 text-sm text-[#706f6c] dark:text-[#A1A09A] hover:underline">
            ← Volver al catálogo
        </a>
    </div>
</body>
</html>
