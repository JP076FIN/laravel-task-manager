<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Page Not Found') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 text-center">
            <div class="bg-white shadow-sm sm:rounded-lg p-10">
                <img src="{{ asset('images/error_404.png') }}" alt="404 Error" class="mx-auto w-48 mb-6">

                <h1 class="text-3xl font-bold text-gray-800">You broke the Internet!</h1>
                <p class="text-gray-600 mt-4">
                    Oops, I mean, the page you're trying to see doesn't exist.
                </p>

                <a href="{{ url('/home') }}" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Go to Homepage
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
