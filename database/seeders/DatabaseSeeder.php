<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/*
|--------------------------------------------------------------------------
| DatabaseSeeder
|--------------------------------------------------------------------------
| Los "seeders" insertan datos iniciales en la base de datos. Útil para
| no tener que registrarse a mano en clase: tras un `migrate --seed` ya
| tenemos un admin y un alumno listos para probar el login.
|
| Se ejecuta con:
|   php artisan db:seed
|   php artisan migrate --seed             (migra + ejecuta este seeder)
|   php artisan migrate:fresh --seed       (tira la BD, migra y siembra)
|
| Credenciales creadas (¡SOLO para esta demo educativa!):
|   - admin@viu.es  / admin1234   → rol 'admin'  → puede entrar a /admin
|   - alumno@viu.es / alumno1234  → rol 'user'   → /admin le dará 403
*/
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Usamos updateOrCreate (en vez de create) para que el seeder sea
     * IDEMPOTENTE: se puede ejecutar varias veces sin romper por email
     * duplicado. Si el usuario ya existe, actualiza sus datos; si no, lo crea.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@viu.es'],         // condición de búsqueda
            [                                    // valores a fijar / actualizar
                'name'     => 'Admin VIU',
                'password' => bcrypt('admin1234'),   // bcrypt() = hashear contraseña
                'role'     => 'admin',
            ],
        );

        User::updateOrCreate(
            ['email' => 'alumno@viu.es'],
            [
                'name'     => 'Alumno VIU',
                'password' => bcrypt('alumno1234'),
                'role'     => 'user',
            ],
        );
    }
}
