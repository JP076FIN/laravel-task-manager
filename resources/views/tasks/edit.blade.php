{{-- resources/views/tasks/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('tasks.update', $task) }}">
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 font-bold mb-2">Title*</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}"
                               class="w-full p-2 border rounded @error('title') border-red-500 @enderror">
                        @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full p-2 border rounded @error('description') border-red-500 @enderror">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Due Date --}}
                    <div class="mb-4">
                        <label for="due_date" class="block text-gray-700 font-bold mb-2">Due Date</label>
                        <input
                            type="date"
                            name="due_date"
                            id="due_date"
                            value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}"
                            class="w-full p-2 border rounded @error('due_date') border-red-500 @enderror">
                        @error('due_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Completed Checkbox --}}
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="completed" class="form-checkbox" {{ $task->completed ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Completed</span>
                        </label>
                    </div>

                    <div class="mb-2">
                        <p>* Required fields</p>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-between items-center">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Task
                        </button>

                        <a href="{{ route('home') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
