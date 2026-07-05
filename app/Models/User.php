<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/*
|--------------------------------------------------------------------------
| Modelo User
|--------------------------------------------------------------------------
| Es el Modelo Eloquent que representa la tabla 'users'. Hereda de
| Authenticatable, que le da soporte para login/sesión.
|
| Personalización propia de esta demo (no viene en el new-app):
|   - Se añade 'role' al #[Fillable] (lo creó la migración add_role_to_users_table).
|   - Método isAdmin(): helper usado por el Gate 'admin' en AppServiceProvider.
*/

// #[Fillable]: única lista de campos asignables masivamente desde
// User::create([...]). Protege contra "mass assignment": si un atacante
// envía role=admin en el formulario de registro, este atributo le impide
// escalar privilegios SOLO porque 'role' esté aquí — por eso, en una app
// real, NO añadiríamos 'role' al fillable y lo asignaríamos en el seeder
// o desde el panel admin. Aquí lo dejamos para simplificar la demo.
#[Fillable(['name', 'email', 'password', 'role'])]
// #[Hidden]: campos que NUNCA se serializan al devolver el modelo (por
// ejemplo en una API JSON). Evita filtrar el hash de la contraseña.
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * ¿Este usuario tiene rol de administrador?
     * Lo usa el Gate 'admin' en AppServiceProvider::boot().
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Casts automáticos al leer/escribir atributos:
     *   - 'password' => 'hashed'  → al asignar $user->password = 'algo',
     *     Eloquent guarda automáticamente el HASH bcrypt (no texto plano).
     *     Esto es CLAVE para la seguridad de la cuenta.
     *   - 'email_verified_at' => 'datetime' → se devuelve como objeto Carbon.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
