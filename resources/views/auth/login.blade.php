@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <h1>TaskManager</h1>

    <h2>Iniciar sesión</h2>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.perform') }}">
        @csrf

        <label>Email</label>
        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
        >

        <label>Contraseña</label>
        <input
            type="password"
            name="password"
            required
        >

        <button type="submit">Entrar</button>
    </form>
@endsection
