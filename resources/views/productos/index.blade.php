{{--
  Vista: productos/index.blade.php
  Mostrada por ProductoController@index (GET /productos).
  Recibe $productos (array id => ['nombre' => ..., 'precio' => ...]).

  Estructuras Blade usadas:
   - @foreach ... @endforeach: itera sobre el array.
   - route('productos.show', $id): construye /productos/{id} a partir del
     NOMBRE de la ruta. Si mañana cambiamos el prefix, no rompe.
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Productos - VIU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] p-6">
    <div class="max-w-2xl mx-auto bg-white dark:bg-[#161615] rounded-3xl shadow-xl p-8">
        <h1 class="text-2xl font-semibold mb-6">Catálogo de productos</h1>

        <ul class="divide-y divide-gray-200 dark:divide-[#3E3E3A]">
            @foreach ($productos as $id => $producto)
                <li class="py-3 flex justify-between items-center">
                    <a href="{{ route('productos.show', $id) }}"
                       class="text-[#f53003] hover:underline font-medium">
                        {{ $producto['nombre'] }}
                    </a>
                    <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        {{ number_format($producto['precio'], 2, ',', '.') }} €
                    </span>
                </li>
            @endforeach
        </ul>

        <a href="/" class="inline-block mt-6 text-sm text-[#706f6c] dark:text-[#A1A09A] hover:underline">
            ← Volver al inicio
        </a>
    </div>
</body>
</html>
