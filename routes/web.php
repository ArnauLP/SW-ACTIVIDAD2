<?php

/*
|--------------------------------------------------------------------------
| Rutas web (HTTP)
|--------------------------------------------------------------------------
| Este fichero define a qué responde la aplicación cuando llega una petición
| HTTP a una URL determinada. Es el "índice" público de la app.
|
| Cada Route::get / Route::post asocia:
|   - un VERBO HTTP (GET, POST, PUT, DELETE…)
|   - una URL (p.ej. "/login")
|   - una ACCIÓN: o bien una función anónima (closure) o un método de un
|     controlador (ej. ProductoController@index).
|
| Comprueba el listado completo con:   php artisan route:list
*/

use App\Http\Controllers\ProductoController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// Página de inicio: muestra la vista welcome.blade.php
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Ejemplo didáctico de rutas con Controller
|--------------------------------------------------------------------------
| - GET /productos       → listado          (productos.index)
| - GET /productos/{id}  → detalle por id   (productos.show)
|
| ->prefix('productos')  : todas las URLs empiezan por /productos
| ->name('productos.')   : todos los nombres empiezan por "productos."
| ->whereNumber('id')    : restringe {id} a valores numéricos (evita que
|                          /productos/abc dispare el detalle).
*/
Route::controller(ProductoController::class)->prefix('productos')->name('productos.')->group(function () {
    Route::get('/',      'index')->name('index');
    Route::get('/{id}',  'show')->name('show')->whereNumber('id');
});

/*
|--------------------------------------------------------------------------
| Rutas de autenticación (solo invitados)
|--------------------------------------------------------------------------
| El middleware 'guest' bloquea estas rutas si YA estás logueado y
| redirige al dashboard. Así un usuario autenticado no vuelve a ver el
| formulario de login/registro.
*/
Route::middleware('guest')->group(function () {

    // GET /login  → muestra el formulario
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    // POST /login → procesa el envío del formulario
    Route::post('/login', function (Request $request) {
        // 1) Validación de entrada en servidor.
        //    NUNCA fiarse solo del HTML5 'required'/'type=email' del cliente:
        //    eso lo puede saltar cualquiera con curl o las DevTools.
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2) Auth::attempt() compara las credenciales contra la tabla 'users'.
        //    Internamente hashea la contraseña recibida con bcrypt y la
        //    compara contra el hash almacenado (NUNCA con la contraseña en claro).
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // 3) Regenerar el ID de sesión tras un login válido es una
            //    defensa estándar contra "session fixation": evita que un
            //    atacante reutilice el session ID con el que llegó la víctima.
            $request->session()->regenerate();

            // intended() vuelve a la URL que el usuario quería visitar antes
            // de ser redirigido al login (o al dashboard por defecto).
            return redirect()->intended(route('dashboard'));
        }

        // 4) Si falla el login, volvemos al formulario con un error.
        //    Mensaje genérico ("no coinciden") para no chivar si el email
        //    existe o no (evita enumeración de cuentas).
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->withInput();
    })->name('login.perform');

    // GET /register → formulario de registro
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    // POST /register → crea la cuenta
    Route::post('/register', function (Request $request) {
        // 'confirmed' obliga a que exista un campo 'password_confirmation'
        // con el mismo valor. 'unique:users,email' evita registros duplicados.
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Hash::make() aplica bcrypt. JAMÁS guardar contraseñas en claro.
        // (En este modelo además 'password' está casteado como 'hashed',
        // así que User::create también lo hashearía automáticamente.)
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    })->name('register.perform');
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
| Usamos POST (no GET) para que el logout requiera token CSRF: así un
| enlace malicioso <img src="/logout"> no puede cerrarle la sesión al
| usuario sin querer (CSRF).
*/
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();         // destruye los datos de sesión
    $request->session()->regenerateToken();    // rota el token CSRF

    return redirect('/');
})->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas privadas: requieren estar logueado (middleware 'auth')
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Panel principal una vez logueado
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Vista informativa del usuario actual
    Route::get('/user', function () {
        return view('user', ['user' => auth()->user()]);
    });

    /*
    | Ruta protegida por AUTORIZACIÓN (Gate 'admin', definido en
    | AppServiceProvider::boot()).
    |
    | Comportamiento esperado:
    |   - Si NO estás logueado:                middleware('auth') → redirect a /login
    |   - Si estás logueado pero NO eres admin: middleware('can:admin') → 403 Forbidden
    |   - Si eres admin:                        muestra la vista.
    |
    | OJO: la protección la hace el SERVIDOR. No basta con ocultar el botón
    | en la vista — un atacante puede teclear /admin a mano. Por eso aquí
    | duplicamos: 'auth' (estar logueado) + 'can:admin' (rol correcto).
    */
    Route::get('/admin', function () {
        return view('admin');
    })->middleware('can:admin')->name('admin');
});

// Ruta de prueba (no documentada en el README, sirve para demos en clase)
Route::get('/hello', function () {
    return view('welcome');
});
