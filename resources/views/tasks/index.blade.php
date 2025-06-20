@extends('layouts.layout')

@section('content')
    <h2>All Tasks</h2>
    <a href="{{ route('tasks.create') }}">Create New Task</a>
    <ul>
        @foreach($tasks as $task)
            <li>{{ $task->title }} - <a href="{{ route('tasks.edit', $task->id) }}">Edit</a></li>
        @endforeach
    </ul>
@endsection
