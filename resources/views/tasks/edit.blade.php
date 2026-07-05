@extends('layouts.app')

@section('title', 'Editar tarea')

@section('content')
    <h1>Editar tarea</h1>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('tasks.update', $task) }}"
    >
        @csrf
        @method('PUT')

        <label>Título</label>

        <input
            type="text"
            name="title"
            value="{{ old('title', $task->title) }}"
            required
        >

        <label>Descripción</label>

        <textarea name="description">{{ old('description', $task->description) }}</textarea>

        <button type="submit">Guardar</button>
    </form>

    <a href="{{ route('dashboard') }}">Volver</a>
@endsection
