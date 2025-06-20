@extends('layouts.layout')

@section('content')
    <h2>Create Task</h2>
    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf
        <label>Title:</label>
        <input type="text" name="title">
        <button type="submit">Save</button>
    </form>
@endsection
