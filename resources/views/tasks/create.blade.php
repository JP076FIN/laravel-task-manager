{{-- resources/views/tasks/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full p-2 border rounded">
                        <x-input-error :messages="$errors->get('title')" class="mt-2" /> {{-- Add error display --}}
                    </div>

                    {{-- Add description, due_date, and completed fields if they are part of task creation --}}
                    {{-- For now, I'm assuming you might want them based on your edit form --}}
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                        <textarea name="description" id="description" rows="4" class="w-full p-2 border rounded">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <label for="due_date" class="block text-gray-700 font-bold mb-2">Due Date</label>
                        <input
                            type="date"
                            name="due_date"
                            id="due_date"
                            value="{{ old('due_date') }}"
                            class="w-full p-2 border rounded">
                        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="completed" class="form-checkbox" {{ old('completed') ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Completed</span>
                        </label>
                        <x-input-error :messages="$errors->get('completed')" class="mt-2" />
                    </div>

                    <div class="flex justify-between items-center">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Task
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
