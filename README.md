# Demo VIU — Seguridad en Aplicaciones Web

Aplicación de ejemplo escrita en **Laravel 12** para la asignatura *Seguridad en Aplicaciones Web* (VIU). El objetivo no es un proyecto "producción-ready", sino mostrar — paso a paso y con código mínimo — los conceptos básicos de:

- Rutas, controladores y vistas (patrón **MVC**).
- **Autenticación** (login, registro, logout) con `Auth::attempt()`, sesión y `Hash`.
- **Autorización** mediante **Gates** y el middleware `can:`.
- Protección **CSRF** en formularios POST.
- Validación de entrada en servidor.
- Manejo de errores (página 404 propia).

Está pensado para alumnos que **no han programado web nunca**. Cada fichero modificado lleva comentarios explicando qué hace y por qué, para que pueda usarse como manual de prácticas.

---

## Índice

1. [Requisitos previos](#1-requisitos-previos)
2. [Cómo arrancar este repo en local](#2-cómo-arrancar-este-repo-en-local)
3. [Usuarios de prueba](#3-usuarios-de-prueba)
4. [Estructura del proyecto (qué mirar primero)](#4-estructura-del-proyecto-qué-mirar-primero)
5. [Cómo recrear esta app desde cero](#5-cómo-recrear-esta-app-desde-cero)
   - [5.1 Crear el proyecto](#51-crear-el-proyecto)
   - [5.2 Configurar la base de datos (SQLite)](#52-configurar-la-base-de-datos-sqlite)
   - [5.3 Crear rutas y vistas básicas](#53-crear-rutas-y-vistas-básicas)
   - [5.4 Crear un controlador (productos)](#54-crear-un-controlador-productos)
   - [5.5 Añadir autenticación (login, registro, logout)](#55-añadir-autenticación-login-registro-logout)
   - [5.6 Añadir autorización por roles (Gates)](#56-añadir-autorización-por-roles-gates)
   - [5.7 Seeders y datos de prueba](#57-seeders-y-datos-de-prueba)
   - [5.8 Página de error 404 personalizada](#58-página-de-error-404-personalizada)
6. [Chuleta de comandos `php artisan`](#6-chuleta-de-comandos-php-artisan)
7. [Conceptos de seguridad explicados en este proyecto](#7-conceptos-de-seguridad-explicados-en-este-proyecto)

---

## 1. Requisitos previos

- **PHP 8.2+** (`php -v`)
- **Composer 2** (`composer --version`)
- **Node.js 18+** y **npm** (para compilar assets con Vite)
- **SQLite** (viene incluido con PHP en la mayoría de instalaciones)

> En macOS lo más cómodo es instalarlo todo con [Homebrew](https://brew.sh):
> ```bash
> brew install php composer node
> ```

---

## 2. Cómo arrancar este repo en local

```bash
# 1. Instalar dependencias PHP
composer install

# 2. Instalar dependencias JS y compilar assets (CSS/JS)
npm install
npm run build              # o `npm run dev` mientras desarrollas

# 3. Copiar variables de entorno y generar APP_KEY
cp .env.example .env
php artisan key:generate

# 4. Crear el fichero SQLite vacío (si no existe)
touch database/database.sqlite

# 5. Ejecutar migraciones + seeders (crea tablas y usuarios demo)
php artisan migrate --seed

# 6. Levantar el servidor de desarrollo
php artisan serve
```

Abre <http://127.0.0.1:8000>.

---

## 3. Usuarios de prueba

Tras `php artisan migrate --seed` tendrás dos cuentas creadas por `DatabaseSeeder`:

| Email           | Contraseña    | Rol     | Puede acceder a `/admin` |
| --------------- | ------------- | ------- | :----------------------: |
| `admin@viu.es`  | `admin1234`   | `admin` | ✅                       |
| `alumno@viu.es` | `alumno1234`  | `user`  | ❌ (devuelve 403)        |

> **Aviso:** estas contraseñas son **públicas** y solo valen para esta demo educativa. Nunca uses contraseñas así en producción.

---

## 4. Estructura del proyecto (qué mirar primero)

Solo enumero los ficheros **modificados o creados** para este curso. Todo lo demás es lo que genera `laravel new` por defecto.

```
demo/
├── routes/
│   └── web.php                                 ← rutas HTTP (login, productos, /admin…)
├── app/
│   ├── Http/Controllers/
│   │   └── ProductoController.php              ← ejemplo de controller MVC
│   ├── Models/
│   │   └── User.php                            ← añadido método isAdmin() y campo 'role'
│   └── Providers/
│       └── AppServiceProvider.php              ← define el Gate 'admin'
├── database/
│   ├── migrations/
│   │   └── 2026_05_26_100000_add_role_to_users_table.php   ← columna role en users
│   └── seeders/
│       └── DatabaseSeeder.php                  ← crea usuario admin y alumno
└── resources/
    └── views/
        ├── auth/
        │   ├── login.blade.php                 ← formulario de login
        │   └── register.blade.php              ← formulario de registro
        ├── productos/
        │   ├── index.blade.php                 ← listado de productos
        │   └── show.blade.php                  ← detalle de un producto
        ├── errors/404.blade.php                ← página 404 propia
        ├── dashboard.blade.php                 ← panel del usuario logueado
        ├── admin.blade.php                     ← panel solo para admins
        └── user.blade.php                      ← info del usuario logueado
```

---

## 5. Cómo recrear esta app desde cero

Esta sección reproduce el proyecto **partiendo de una carpeta vacía**, comando a comando.

### 5.1 Crear el proyecto

```bash
# Crea un nuevo proyecto Laravel llamado "demo"
composer create-project laravel/laravel demo

cd demo
```

> Alternativa con el instalador oficial:
> ```bash
> composer global require laravel/installer
> laravel new demo
> ```

### 5.2 Configurar la base de datos (SQLite)

Usar **SQLite** simplifica la vida en clase: es un único fichero, no necesita servidor.

Edita `.env` y deja la sección DB así:

```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

Crea el fichero y ejecuta las migraciones iniciales (que ya vienen con Laravel: `users`, `cache`, `jobs`, `sessions`, …):

```bash
touch database/database.sqlite
php artisan migrate
```

### 5.3 Crear rutas y vistas básicas

Las rutas web viven en [routes/web.php](routes/web.php). Una ruta mínima:

```php
Route::get('/hello', function () {
    return view('welcome');
});
```

Las vistas viven en `resources/views/` y usan el motor **Blade** (`.blade.php`). Para crear una vista nueva basta con crear el fichero — no hay comando `artisan` específico, aunque existe `make:view` desde Laravel 11:

```bash
php artisan make:view hello
# → crea resources/views/hello.blade.php
```

Comprueba todas las rutas registradas con:

```bash
php artisan route:list
```

### 5.4 Crear un controlador (productos)

```bash
php artisan make:controller ProductoController
```

Esto crea [app/Http/Controllers/ProductoController.php](app/Http/Controllers/ProductoController.php). En este demo el controlador guarda los productos en un array en memoria (no en base de datos) para no mezclar conceptos.

Después, en [routes/web.php](routes/web.php) registramos las rutas que apuntan a los métodos del controlador:

```php
Route::controller(ProductoController::class)
    ->prefix('productos')
    ->name('productos.')
    ->group(function () {
        Route::get('/',     'index')->name('index');
        Route::get('/{id}', 'show')->name('show')->whereNumber('id');
    });
```

Y las vistas correspondientes en `resources/views/productos/index.blade.php` y `show.blade.php`.

### 5.5 Añadir autenticación (login, registro, logout)

Laravel trae el sistema de autenticación incorporado (facade `Auth`, modelo `User`, tabla `users`, hashing con `bcrypt`). En esta demo **no** usamos un starter kit (Breeze / Jetstream / Fortify) — escribimos a mano las rutas y vistas para que se vea **qué hace** cada pieza.

1. **Vistas**: crea los formularios en [resources/views/auth/login.blade.php](resources/views/auth/login.blade.php) y [resources/views/auth/register.blade.php](resources/views/auth/register.blade.php). Recuerda incluir `@csrf` dentro del `<form>` (token anti-CSRF).

2. **Rutas** (extracto de [routes/web.php](routes/web.php)):

   ```php
   // Rutas solo para invitados (no logueados)
   Route::middleware('guest')->group(function () {
       Route::get('/login', fn () => view('auth.login'))->name('login');

       Route::post('/login', function (Request $request) {
           $credentials = $request->validate([
               'email'    => ['required', 'email'],
               'password' => ['required'],
           ]);

           if (Auth::attempt($credentials, $request->boolean('remember'))) {
               $request->session()->regenerate();   // evita session fixation
               return redirect()->intended(route('dashboard'));
           }

           return back()->withErrors([
               'email' => 'Las credenciales no coinciden con nuestros registros.',
           ])->withInput();
       })->name('login.perform');

       Route::get('/register', fn () => view('auth.register'))->name('register');

       Route::post('/register', function (Request $request) {
           $data = $request->validate([
               'name'     => ['required', 'string', 'max:255'],
               'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
               'password' => ['required', 'confirmed', 'min:8'],
           ]);

           $user = User::create([
               'name'     => $data['name'],
               'email'    => $data['email'],
               'password' => Hash::make($data['password']),
           ]);

           Auth::login($user);

           return redirect()->route('dashboard');
       })->name('register.perform');
   });

   // Logout (solo si estás logueado)
   Route::post('/logout', function (Request $request) {
       Auth::logout();
       $request->session()->invalidate();
       $request->session()->regenerateToken();
       return redirect('/');
   })->middleware('auth')->name('logout');

   // Rutas solo para usuarios autenticados
   Route::middleware('auth')->group(function () {
       Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
   });
   ```

3. **¿Qué hace `Hash::make` / `password' => 'hashed'`?** Guarda la contraseña como hash bcrypt en la base de datos. **Nunca** guardes contraseñas en claro.

> 💡 **Atajo en proyectos reales:** Laravel Breeze genera todo esto (rutas, controladores, vistas Blade o React, tests) con dos comandos:
> ```bash
> composer require laravel/breeze --dev
> php artisan breeze:install
> ```
> Aquí lo evitamos a propósito para ver el "código auténtico".

### 5.6 Añadir autorización por roles (Gates)

La autenticación dice **quién eres**. La autorización dice **qué puedes hacer**. Aquí distinguimos `user` vs `admin` con un campo nuevo en la tabla `users`.

**Paso 1 — añadir la columna `role` con una migración:**

```bash
php artisan make:migration add_role_to_users_table --table=users
```

Edita la migración generada en `database/migrations/` (ver [2026_05_26_100000_add_role_to_users_table.php](database/migrations/2026_05_26_100000_add_role_to_users_table.php)) y dentro de `up()` añade:

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('role')->default('user')->after('email');
});
```

Aplica la migración:

```bash
php artisan migrate
```

**Paso 2 — exponer el campo en el modelo User** ([app/Models/User.php](app/Models/User.php)):

```php
#[Fillable(['name', 'email', 'password', 'role'])]   // añadimos 'role' a fillable
class User extends Authenticatable
{
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    // ...
}
```

**Paso 3 — definir el Gate `admin` en** [app/Providers/AppServiceProvider.php](app/Providers/AppServiceProvider.php):

```php
public function boot(): void
{
    Gate::define('admin', fn (User $user) => $user->isAdmin());
}
```

**Paso 4 — proteger una ruta con `middleware('can:admin')`:**

```php
Route::get('/admin', fn () => view('admin'))
    ->middleware(['auth', 'can:admin'])
    ->name('admin');
```

**Paso 5 — ocultar el botón en la vista** con `@can` (ver [dashboard.blade.php](resources/views/dashboard.blade.php)):

```blade
@can('admin')
    <a href="{{ route('admin') }}">Ir al panel de administración</a>
@endcan
```

> **¿Qué pasa si un usuario normal intenta entrar manualmente a `/admin`?** Recibe **403 Forbidden**. La protección **no** depende de que el botón esté oculto — la valida el middleware en el servidor.

### 5.7 Seeders y datos de prueba

Para arrancar con usuarios ya creados (admin + alumno) usamos un **seeder**.

Crea un seeder nuevo (en esta demo modificamos directamente `DatabaseSeeder.php`):

```bash
php artisan make:seeder UserSeeder
```

Edita [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php):

```php
public function run(): void
{
    User::updateOrCreate(
        ['email' => 'admin@viu.es'],
        [
            'name'     => 'Admin VIU',
            'password' => bcrypt('admin1234'),
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
```

Ejecuta los seeders:

```bash
php artisan db:seed
# o, todo a la vez (reset DB + migraciones + seeders):
php artisan migrate:fresh --seed
```

### 5.8 Página de error 404 personalizada

Crea `resources/views/errors/404.blade.php` y Laravel la usará automáticamente cuando se lance un `abort(404)` o cuando no se resuelva la ruta. Ver [resources/views/errors/404.blade.php](resources/views/errors/404.blade.php).

---

## 6. Chuleta de comandos `php artisan`

### Proyecto

| Comando | Para qué sirve |
|---|---|
| `php artisan serve` | Levanta el servidor de desarrollo en `127.0.0.1:8000`. |
| `php artisan tinker` | Consola interactiva (REPL) con la app cargada. Útil para probar consultas. |
| `php artisan route:list` | Lista todas las rutas registradas. |
| `php artisan about` | Muestra info del entorno, drivers, versiones, etc. |
| `php artisan key:generate` | Genera la `APP_KEY` en `.env`. |
| `php artisan config:clear` | Limpia la caché de configuración. |
| `php artisan view:clear` | Limpia la caché de las vistas Blade. |
| `php artisan optimize:clear` | Limpia todas las cachés (config, rutas, vistas, eventos). |

### Generar código (`make:`)

| Comando | Crea… |
|---|---|
| `php artisan make:controller NombreController` | Controlador en `app/Http/Controllers/`. |
| `php artisan make:controller NombreController --resource` | Controlador con los 7 métodos REST (index, create, store, show, edit, update, destroy). |
| `php artisan make:controller NombreController --invokable` | Controlador con un único método `__invoke`. |
| `php artisan make:model Producto` | Modelo Eloquent en `app/Models/`. |
| `php artisan make:model Producto -mcr` | Modelo + migración + controlador resource (atajo muy útil). |
| `php artisan make:migration create_productos_table` | Migración nueva (crear tabla). |
| `php artisan make:migration add_role_to_users_table --table=users` | Migración para **modificar** una tabla existente. |
| `php artisan make:seeder ProductoSeeder` | Seeder en `database/seeders/`. |
| `php artisan make:factory ProductoFactory` | Factory en `database/factories/` (datos falsos para tests). |
| `php artisan make:request StoreProductoRequest` | Form Request (validación encapsulada). |
| `php artisan make:middleware EnsureAdmin` | Middleware en `app/Http/Middleware/`. |
| `php artisan make:policy ProductoPolicy --model=Producto` | Policy (autorización a nivel modelo). |
| `php artisan make:view nombre` | Vista Blade en `resources/views/`. |
| `php artisan make:component Alert` | Componente Blade (`<x-alert />`). |

### Base de datos

| Comando | Qué hace |
|---|---|
| `php artisan migrate` | Aplica las migraciones pendientes. |
| `php artisan migrate:rollback` | Deshace el **último** batch de migraciones. |
| `php artisan migrate:reset` | Deshace **todas** las migraciones. |
| `php artisan migrate:refresh` | `reset` + `migrate` (mantiene la BD). |
| `php artisan migrate:fresh` | **Tira** todas las tablas y vuelve a migrar (más rápido). |
| `php artisan migrate:fresh --seed` | Combo: BD limpia + migraciones + seeders. ⭐ |
| `php artisan migrate:status` | Lista qué migraciones se han aplicado. |
| `php artisan db:seed` | Ejecuta `DatabaseSeeder`. |
| `php artisan db:seed --class=UserSeeder` | Ejecuta solo un seeder concreto. |
| `php artisan db` | Abre el cliente nativo (sqlite3 / mysql / psql) con la BD del proyecto. |

### Autenticación rápida (no usada en esta demo, pero útil saber)

```bash
composer require laravel/breeze --dev
php artisan breeze:install        # Genera rutas, controladores y vistas de auth
php artisan migrate
npm install && npm run dev
```

---

## 7. Conceptos de seguridad explicados en este proyecto

| Concepto | Dónde verlo |
|---|---|
| **CSRF** (token anti-falsificación de petición) | `@csrf` en cada `<form method="POST">`. |
| **Hashing de contraseñas** (bcrypt) | `Hash::make()` en el registro, `'password' => 'hashed'` en el modelo `User`. |
| **Validación en servidor** (no fiarse del cliente) | `$request->validate([...])` en login y registro. |
| **Mass assignment** | `#[Fillable([...])]` en `User` — solo los campos listados pueden venir del usuario. |
| **Session fixation** | `$request->session()->regenerate()` al loguear y `invalidate()` al cerrar sesión. |
| **Autenticación** | Middleware `auth` en grupos de rutas privadas. |
| **Autorización (Gates)** | `Gate::define('admin', ...)` + `middleware('can:admin')` + directiva `@can`. |
| **Defensa en profundidad** | `/admin` está protegida **a la vez** por `auth` (login obligatorio) y por `can:admin` (rol obligatorio). |
| **Página 404 propia** (no revelar stack traces) | `resources/views/errors/404.blade.php`. |

---

## Licencia

El framework Laravel se distribuye bajo licencia [MIT](https://opensource.org/licenses/MIT). El contenido específico de esta demo educativa (vistas, rutas, controladores) puede reutilizarse libremente con fines docentes.
