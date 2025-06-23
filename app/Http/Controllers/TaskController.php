<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = \App\Models\Task::all();
        return view('dashboard', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Task::create($request->all());
        return redirect()->route('dashboard');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }


    public function update(Request $request, Task $task)
    {
        $data = $request->all();

        // Checkbox fix
        $data['completed'] = $request->has('completed');

        $task->update($data);

        return redirect()->route('dashboard');
    }


    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('dashboard');
    }
}
