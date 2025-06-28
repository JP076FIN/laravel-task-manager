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
        return view('home', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500', 'not_regex:/<[^>]*>/'],
            'completed' => ['nullable', 'boolean'],
            'due_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$request->boolean('completed') && $value && $value < date('Y-m-d')) {
                        $fail('The due date cannot be in the past unless the task is completed.');
                    }
                },
            ],
        ]);

        Task::create($request->all());

        return redirect()->route('home');
    }


    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }


    public function update(Request $request, Task $task)
    {

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500', 'not_regex:/<[^>]*>/'],
            'completed' => ['nullable'],
            'due_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$request->boolean('completed') && $value && $value < date('Y-m-d')) {
                        $fail('The due date cannot be in the past unless the task is completed.');
                    }
                },
            ],
        ]);

        $data = $request->all();

        $data['completed'] = $request->has('completed');

        $task->update($data);

        return redirect()->route('home');
    }



    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('home');
    }
}
