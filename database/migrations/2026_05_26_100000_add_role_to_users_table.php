<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Migración: add_role_to_users_table
|--------------------------------------------------------------------------
| Añade la columna 'role' a la tabla 'users' (que ya existía). Es la base
| de la autorización por roles que usa el Gate 'admin'.
|
| Generada con:
|   php artisan make:migration add_role_to_users_table --table=users
|
| Se aplica con:
|   php artisan migrate
|
| Y se puede revertir con:
|   php artisan migrate:rollback
|
| El nombre de la clase no importa: el "return new class extends Migration"
| devuelve una clase anónima — Laravel la identifica por el nombre del archivo.
*/
return new class extends Migration
{
    /**
     * Se ejecuta al hacer `php artisan migrate`.
     * Añade la nueva columna a una tabla existente con Schema::table().
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // string('role')  → columna VARCHAR llamada 'role'
            // ->default('user') → si no se indica, los usuarios serán 'user'
            // ->after('email')  → en MySQL coloca la columna tras 'email'
            //                     (en SQLite es solo informativo).
            $table->string('role')->default('user')->after('email');
        });
    }

    /**
     * Se ejecuta al hacer `php artisan migrate:rollback`.
     * Debe deshacer EXACTAMENTE lo que hizo up().
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
