<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $tasks = $request->user()
            ->tasks()
            ->latest()
            ->get();

        return view('dashboard', compact('tasks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $request->user()->tasks()->create($data);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Tarea creada.');
    }

    public function edit(Request $request, Task $task): View
    {
        if ($request->user()->cannot('update', $task)) {
            abort(403);
        }

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        if ($request->user()->cannot('update', $task)) {
            abort(403);
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $task->update($data);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Tarea actualizada.');
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        if ($request->user()->cannot('delete', $task)) {
            abort(403);
        }

        $task->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Tarea eliminada.');
    }

    public function search(Request $request): View
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = $data['q'] ?? '';

        $tasks = $request->user()
            ->tasks()
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('title', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->latest()
            ->get();

        return view('dashboard', compact('tasks', 'query'));
    }
}
