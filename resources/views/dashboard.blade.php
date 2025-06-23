<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task Manager') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Create Task Button -->
                <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                    + Create New Task
                </a>

                <!-- Tasks Table -->
                @if ($tasks->count())
                    <table class="min-w-full table-auto text-left text-sm">
                        <thead>
                        <tr>
                            <th class="border-b p-2">Title</th>
                            <th class="border-b p-2">Due Date</th>
                            <th class="border-b p-2">Description</th>
                            <th class="border-b p-2">Completed</th>
                            <th class="border-b p-2">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td class="border-b p-2">{{ $task->title }}</td>
                                <td class="border-b p-2">{{ $task->due_date }}</td>
                                <td class="border-b p-2">{{ $task->description }}</td>
                                <td class="border-b p-2 text-center">
                                    <input type="checkbox" disabled {{ $task->completed ? 'checked' : '' }}>
                                </td>
                                <td class="border-b p-2">
                                    <a href="{{ route('tasks.edit', $task) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mb-4 inline-block">Edit</a>

                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded mb-4 inline-block" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-600 mt-4">No tasks found.</p>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
