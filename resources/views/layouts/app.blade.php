<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'TaskManager')</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
        }

        .container {
            max-width: 850px;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }

        input, textarea, button {
            padding: 8px;
            margin: 5px 0;
        }

        input, textarea {
            width: 100%;
            box-sizing: border-box;
        }

        button {
            cursor: pointer;
        }

        .task {
            border: 1px solid #ddd;
            padding: 15px;
            margin-top: 10px;
        }

        .error {
            color: darkred;
        }

        .success {
            color: green;
        }

        nav {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <nav>
            @auth
                Usuario: {{ auth()->user()->name }}
            @endauth

            @guest
                <a href="{{ route('login') }}">Iniciar sesión</a>
            @endguest
        </nav>

        @yield('content')
    </div>
</body>
</html>
