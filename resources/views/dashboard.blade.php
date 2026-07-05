@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Mis tareas</h1>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <h2>Buscar tareas</h2>

    <form method="GET" action="{{ route('tasks.search') }}">
        <input
            type="text"
            name="q"
            value="{{ $query ?? '' }}"
            placeholder="Buscar..."
        >

        <button type="submit">Buscar</button>

        <a href="{{ route('dashboard') }}">Mostrar todas</a>
    </form>

    <h2>Nueva tarea</h2>

    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf

        <input
            type="text"
            name="title"
            placeholder="Título"
            required
        >

        <textarea
            name="description"
            placeholder="Descripción"
        ></textarea>

        <button type="submit">Crear tarea</button>
    </form>

    <h2>Lista de tareas</h2>

    @forelse ($tasks as $task)
        <div class="task">
            <h3>{{ $task->title }}</h3>

            <p>{{ $task->description }}</p>

            <a href="{{ route('tasks.edit', $task) }}">
                Editar
            </a>

            <form
                method="POST"
                action="{{ route('tasks.destroy', $task) }}"
            >
                @csrf
                @method('DELETE')

                <button type="submit">Eliminar</button>
            </form>
        </div>
    @empty
        <p>No hay tareas.</p>
    @endforelse
@endsection
