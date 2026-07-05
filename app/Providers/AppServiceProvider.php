<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/*
|--------------------------------------------------------------------------
| AppServiceProvider
|--------------------------------------------------------------------------
| Service Provider principal de la app. boot() se ejecuta una vez al
| arrancar Laravel y es el sitio típico para registrar:
|   - Gates / Policies (autorización)
|   - Macros, observers, view composers, etc.
|
| Personalización propia de esta demo: definimos el Gate 'admin'.
*/
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /*
        | Gate 'admin'
        |
        | Define una regla de autorización con el nombre 'admin'. Cualquier
        | parte de la app puede preguntar "¿puede este usuario hacer X?":
        |
        |   - En rutas:   ->middleware('can:admin')
        |   - En vistas:  @can('admin') ... @endcan
        |   - En código:  if (Gate::allows('admin')) { ... }
        |
        | El primer argumento del closure es el usuario autenticado
        | (Laravel lo inyecta solo). Si no hay sesión, el Gate devuelve
        | false sin llegar a ejecutar el closure.
        */
        Gate::define('admin', fn (User $user) => $user->isAdmin());
    }
}
