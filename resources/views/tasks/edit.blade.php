@extends('layouts.layout')

@section('content')
<h1>Edit Task</h1>

<form method="POST" action="{{ route('tasks.update', $task->id) }}">
    @csrf
    @method('PUT')
    <label>Title:</label>
    <input type="text" name="title" value="{{ $task->title }}" required>
    <button type="submit">Update</button>
</form>

<a href="{{ route('tasks.index') }}">Back to Tasks</a>
@endsection
