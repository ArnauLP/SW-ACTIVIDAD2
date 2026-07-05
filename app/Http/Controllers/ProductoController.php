<?php

namespace App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| ProductoController
|--------------------------------------------------------------------------
| Ejemplo MÍNIMO de "controlador" dentro del patrón MVC:
|
|   ┌──────────┐   HTTP    ┌───────────────┐   datos   ┌──────────┐
|   │ Browser  │ ────────> │  Controller   │ ────────> │  Vista   │
|   │ (URL)    │ <──────── │ (este fichero)│ <──────── │ (Blade)  │
|   └──────────┘   HTML    └───────────────┘           └──────────┘
|
| Para no mezclar conceptos, los productos están "cableados" en un array
| en memoria. En un proyecto real vendrían de la BD a través de un Model
| Eloquent (App\Models\Producto::all()).
|
| Generado con:   php artisan make:controller ProductoController
*/
class ProductoController extends Controller
{
    /**
     * "Base de datos" de juguete: array asociativo id → producto.
     * En un caso real esto sería el Modelo Producto y una migración
     * que cree la tabla productos.
     */
    private array $productos = [
        1 => ['nombre' => 'Portátil VIU',     'precio' => 1200.00],
        2 => ['nombre' => 'Monitor 27"',      'precio' => 320.50],
        3 => ['nombre' => 'Teclado mecánico', 'precio' => 95.00],
    ];

    /**
     * GET /productos
     * Listado de todos los productos.
     * view() devuelve la respuesta HTML a partir de la plantilla Blade
     * resources/views/productos/index.blade.php pasándole $productos.
     */
    public function index()
    {
        return view('productos.index', ['productos' => $this->productos]);
    }

    /**
     * GET /productos/{id}
     * Detalle de un producto concreto.
     *
     * Laravel pasa el {id} de la URL como argumento del método.
     * Si no existe en nuestro array → abort(404) lanza la respuesta 404
     * (y dispara la vista personalizada resources/views/errors/404.blade.php).
     */
    public function show(int $id)
    {
        $producto = $this->productos[$id] ?? abort(404, 'Producto no encontrado');

        return view('productos.show', ['id' => $id, 'producto' => $producto]);
    }
}
